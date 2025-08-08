<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\DataCollection;
use App\Models\{Ballot, Precinct};
use App\Events\BallotSubmitted;
use Illuminate\Support\Str;
use App\Data\BallotData;
use App\Models\{Position, Candidate};
use App\Data\{VoteData, PositionData, CandidateData};

class SubmitBallot
{
    use AsAction;

    public function handle(string $precinctId, string $code, DataCollection $votes): BallotData
    {
        $precinct = Precinct::find($precinctId);

        if (! $precinct) {
            throw new ModelNotFoundException("Precinct not found with ID: $precinctId");
        }

        $ballot = Ballot::create([
            'id' => Str::uuid(),
            'code' => $code,
            'votes' => $votes->toArray(),
            'precinct_id' => $precinct->id,
        ]);

        event(new BallotSubmitted($ballot));

        return BallotData::from($ballot);
    }

    public function rules(): array
    {
        return [
            'precinct_id' => ['required', 'uuid', 'exists:precincts,id'],
            'code' => ['required', 'string', 'unique:ballots,code'],

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
     * Handle the HTTP controller request.
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
        $positions = Position::whereIn('code', $positionCodes)->get()->keyBy('code');
        $candidates = Candidate::whereIn('code', $candidateCodes)->get()->keyBy('code');

        // Build VoteData[]
        $votes = collect($validated['votes'])->map(function (array $vote) use ($positions, $candidates) {
            $posCode = $vote['position']['code'];
            $posModel = $positions->get($posCode);

            // These exist by validation; guard just in case
            if (! $posModel) {
                abort(422, "Unknown position code: {$posCode}");
            }

            $candDatas = collect($vote['candidates'])->map(function (array $c) use ($candidates) {
                $code = $c['code'];
                $model = $candidates->get($code);

                if (! $model) {
                    abort(422, "Unknown candidate code: {$code}");
                }

                return CandidateData::from($model);
            });

            return new VoteData(
                position: PositionData::from($posModel),
                candidates: new DataCollection(CandidateData::class, $candDatas)
            );
        });

        // Hand off to the existing handle() which expects a DataCollection<VoteData>
        return $this->handle(
            precinctId: $validated['precinct_id'],
            code: $validated['code'],
            votes: new DataCollection(VoteData::class, $votes)
        );
    }
}
