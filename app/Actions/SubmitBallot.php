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
            'votes.*.position.code' => ['required', 'string'],
            'votes.*.position.name' => ['required', 'string'],
            'votes.*.position.level' => ['required', 'string'],
            'votes.*.position.count' => ['required', 'integer'],
            'votes.*.candidates' => ['required', 'array'],
            'votes.*.candidates.*.code' => ['required', 'string'],
            'votes.*.candidates.*.name' => ['required', 'string'],
            'votes.*.candidates.*.alias' => ['required', 'string'],
        ];
    }

    /**
     * Handle the HTTP controller request.
     */
    public function asController(ActionRequest $request): BallotData
    {
        $validated = $request->validated();

        return $this->handle(
            precinctId: $validated['precinct_id'],
            code: $validated['code'],
            votes: new DataCollection(
                \App\Data\VoteData::class,
                $validated['votes']
            )
        );
    }
}
