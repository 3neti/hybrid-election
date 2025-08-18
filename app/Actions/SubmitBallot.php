<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
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
     * Now resolves the single precinct internally.
     */
    public function handle(string $code, DataCollection $votes): BallotData
    {
        return DB::transaction(function () use ($code, $votes) {
            // --- Resolve the single precinct (strict) ---
            $count = Precinct::count();
            if ($count === 0) {
                throw new ModelNotFoundException('No precinct found. The system is not initialized.');
            }
            if ($count > 1) {
                // Be explicit: this action assumes exactly one precinct.
                throw ValidationException::withMessages([
                    'precinct' => ['Multiple precincts found. SubmitBallot expects exactly one precinct in the system.'],
                ]);
            }
            $precinct = Precinct::firstOrFail(); // Exactly 1 row guaranteed above.

            // --- Create ballot ---
            $ballot = Ballot::create([
                'id'          => Str::uuid(),
                'code'        => $code,
                'votes'       => $votes->toArray(),
                'precinct_id' => $precinct->id,
            ]);

            event(new BallotSubmitted($ballot));

            // Eager load the relation so the DTO has full shape
            $ballot->load('precinct');

            return BallotData::from($ballot);
        });
    }

    /**
     * No more precinct_id here.
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'unique:ballots,code'],

            'votes' => ['required', 'array'],
            'votes.*.position.code'   => ['required', 'string', 'exists:positions,code'],
            'votes.*.position.name'   => ['nullable', 'string'],
            'votes.*.position.level'  => ['nullable', 'string'],
            'votes.*.position.count'  => ['nullable', 'integer'],

            'votes.*.candidates'        => ['required', 'array'],
            'votes.*.candidates.*.code' => ['required', 'string', 'exists:candidates,code'],
            'votes.*.candidates.*.name' => ['nullable', 'string'],
            'votes.*.candidates.*.alias'=> ['nullable', 'string'],
        ];
    }

    /**
     * HTTP entrypoint: builds VoteData[] and defers to handle()
     */
    public function asController(ActionRequest $request): BallotData
    {
        $validated = $request->validated();

        // Collect unique codes for efficient lookups
        $positionCodes = collect($validated['votes'])
            ->pluck('position.code')->filter()->unique()->values();

        $candidateCodes = collect($validated['votes'])
            ->flatMap(fn ($v) => collect($v['candidates'])->pluck('code'))
            ->filter()->unique()->values();

        $positions  = Position::whereIn('code', $positionCodes)->get()->keyBy('code');
        $candidates = Candidate::whereIn('code', $candidateCodes)->get()->keyBy('code');

        $votes = collect($validated['votes'])->map(function (array $vote) use ($positions, $candidates) {
            $posCode  = $vote['position']['code'];
            $posModel = $positions->get($posCode);
            if (! $posModel) {
                abort(422, "Unknown position code: {$posCode}");
            }

            $candDatas = collect($vote['candidates'])->map(function (array $c) use ($candidates) {
                $code  = $c['code'];
                $model = $candidates->get($code);
                if (! $model) {
                    abort(422, "Unknown candidate code: {$code}");
                }
                return CandidateData::from($model);
            });

            return new VoteData(
                position: PositionData::from($posModel),
                candidates: new DataCollection(CandidateData::class, $candDatas),
            );
        });

        return $this->handle(
            code:  $validated['code'],
            votes: new DataCollection(VoteData::class, $votes),
        );
    }
}
