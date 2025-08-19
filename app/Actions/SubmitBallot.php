<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\{Response, JsonResponse};
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\DataCollection;
use App\Models\{Ballot, Precinct, Position, Candidate};
use App\Events\BallotSubmitted;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Data\{BallotData, VoteData, PositionData, CandidateData};

class SubmitBallot
{
    use AsAction;

    /**
     * Stable, semantic hash based on position.code + sorted candidate codes.
     * Ignores ordering of input and extra fields.
     */
    private function computePayloadHash(array $votes): string
    {
        // $votes: [ ['position' => ['code' => ...], 'candidates' => [['code' => ...], ...]], ... ]
        $norm = collect($votes)
            ->map(function (array $vote) {
                $pos = $vote['position']['code'] ?? null;

                $candCodes = collect($vote['candidates'] ?? [])
                    ->map(fn ($c) => is_array($c) ? ($c['code'] ?? null) : ($c->code ?? null))
                    ->filter(fn ($code) => $code !== null && $code !== '')
                    ->map(fn ($code) => (string) $code)
                    ->unique()          // <<< de-dupe
                    ->sort()            // stable order
                    ->values()
                    ->all();

                return [
                    'position'   => $pos,
                    'candidates' => $candCodes,
                ];
            })
            ->filter(fn ($v) => $v['position'] !== null)
            ->sortBy('position')
            ->values()
            ->all();

        return hash('sha256', json_encode($norm, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Create a ballot for the single (strict) precinct.
     * - Strict single-precinct resolution stays here (no duplicate query elsewhere).
     * - We recompute the hash here as well to persist it reliably.
     */
    public function handle(string $code, DataCollection $votes): BallotData
    {
        return DB::transaction(function () use ($code, $votes) {
            // ── Resolve the single precinct (strict) ─────────────────────────────
            $precinct = Precinct::query()->first();
            if (!$precinct) {
                throw new ModelNotFoundException('System not initialized: no precinct found.');
            }
            if (Precinct::query()->count() > 1) {
                abort(409, 'Multiple precincts found; single-precinct system expected.');
            }

            // Build a minimal semantic array for hashing from the VoteData collection
            $semanticVotes = collect($votes->toArray())
                ->map(function ($v) {
                    // $v may be array-y (from DataCollection) → normalize
                    $posCode = $v['position']['code'] ?? null;
                    $cand = collect($v['candidates'] ?? [])
                        ->map(fn ($c) => ['code' => (is_array($c) ? ($c['code'] ?? null) : null)])
                        ->all();
                    return [
                        'position'   => ['code' => $posCode],
                        'candidates' => $cand,
                    ];
                })
                ->all();

            $payloadHash = $this->computePayloadHash($semanticVotes);

            // Pre-check same code (idempotency / conflict)
            $existing = Ballot::where('code', $code)->first();
            if ($existing) {
                if ($existing->payload_hash && hash_equals($existing->payload_hash, $payloadHash)) {
                    // Idempotent replay → return existing
                    return BallotData::from($existing);
                }
                // Same code, different payload → conflict
                abort(409, 'Ballot code already used with a different payload.');
            }

            // Create the ballot
            $ballot = Ballot::create([
                'id'           => Str::uuid(),
                'code'         => $code,
                'votes'        => $votes->toArray(),
                'precinct_id'  => $precinct->id,
                'payload_hash' => $payloadHash,
                // pull from the current request context if available
                'source_ip'    => request()->ip(),
                'user_agent'   => request()->userAgent(),
            ]);

            event(new BallotSubmitted($ballot));

            // Fresh ballot created
            $dto = BallotData::from($ballot);
            $dto->_status = 201;
            return $dto;
//            return BallotData::from($ballot);
        });
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string'], // removed unique; we handle 200/409 ourselves

            'votes' => ['required', 'array'],
            'votes.*.position.code' => ['required', 'string', 'exists:positions,code'],
            'votes.*.position.name' => ['nullable', 'string'],
            'votes.*.position.level' => ['nullable', 'string'],
            'votes.*.position.count' => ['nullable', 'integer'],

            'votes.*.candidates' => ['required', 'array'],
            'votes.*.candidates.*.code' => ['required', 'string', 'exists:candidates,code'],
            'votes.*.candidates.*.name' => ['nullable', 'string'],
            'votes.*.candidates.*.alias' => ['nullable', 'string'],
        ];
    }

    /**
     * HTTP controller entrypoint.
     * - Builds VoteData[]
     * - Pre-checks existing ballot by code (idempotent/409)
     * - Calls handle() which resolves the single precinct strictly and persists
     */
    public function asController(ActionRequest $request): BallotData
    {
        $validated = $request->validated();

        // Collect unique codes first to minimize queries
        $positionCodes = collect($validated['votes'])
            ->pluck('position.code')
            ->filter()
            ->unique()
            ->values();

        $candidateCodes = collect($validated['votes'])
            ->flatMap(fn ($v) => collect($v['candidates'])->pluck('code'))
            ->filter()
            ->unique()
            ->values();

        // Lookups
        $positions  = Position::whereIn('code', $positionCodes)->get()->keyBy('code');
        $candidates = Candidate::whereIn('code', $candidateCodes)->get()->keyBy('code');

        // Build DataCollection<VoteData> for storage/DTOs
        $votes = collect($validated['votes'])->map(function (array $vote) use ($positions, $candidates) {
            $posCode  = $vote['position']['code'];
            $posModel = $positions->get($posCode);
            if (!$posModel) abort(422, "Unknown position code: {$posCode}");

            $candDatas = collect($vote['candidates'])->map(function (array $c) use ($candidates) {
                $code  = $c['code'];
                $model = $candidates->get($code);
                if (!$model) abort(422, "Unknown candidate code: {$code}");
                return CandidateData::from($model);
            });

            return new VoteData(
                position: PositionData::from($posModel),
                candidates: new DataCollection(CandidateData::class, $candDatas)
            );
        });

        // Pre-check idempotency/conflict by code BEFORE we hit handle()
        // (We recompute inside handle too, but this saves a write path.)
        // Compute semantic hash from validated payload for the pre-check:
        $semanticVotes = collect($validated['votes'])
            ->map(fn ($v) => [
                'position'   => ['code' => $v['position']['code']],
                'candidates' => collect($v['candidates'])->map(fn ($c) => ['code' => $c['code']])->all(),
            ])
            ->all();
        $payloadHash = $this->computePayloadHash($semanticVotes);

        $existing = Ballot::where('code', $validated['code'])->first();
        if ($existing) {
            if ($existing->payload_hash && hash_equals($existing->payload_hash, $payloadHash)) {
                // Idempotent replay
                $dto = BallotData::from($existing);
                $dto->_status = 200;
                return $dto;
//                return BallotData::from($existing);
            }
            abort(409, 'Ballot code already used with a different payload.');
        }

        // Create via handle(); precinct resolution stays there (strict, single query).
        return $this->handle(
            code: $validated['code'],
            votes: new DataCollection(VoteData::class, $votes)
        );
    }

    public function jsonResponse($result, ActionRequest $request): JsonResponse
    {
        // Detect context flags that asController() attached
        $status = $result->_status ?? 200; // default fallback

        // Remove the helper field before returning JSON
        if ($result instanceof BallotData) {
            $array = $result->toArray();
            unset($array['_status']);
        } else {
            $array = $result;
        }

        return response()->json($array, $status);
    }

    /** Optional: catch-all for non-JSON requests */
    public function htmlResponse($result, ActionRequest $request)
    {
        // delegate to JSON for now; or render a view if you ever need HTML
        return $this->jsonResponse($result, $request);
    }
}
