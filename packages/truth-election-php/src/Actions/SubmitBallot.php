<?php

namespace TruthElection\Actions;

use TruthElection\Support\PrecinctContext;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Collection;
use TruthElection\Data\BallotData;
use TruthElection\Data\VoteData;

class SubmitBallot
{
    use AsAction;

    public function __construct(
        protected PrecinctContext $precinctContext
    ) {}

    public function handle(string $ballotCode, Collection $votes): BallotData
    {
        $precinct = $this->precinctContext->getPrecinct();

        if (!$precinct) {
            throw new \RuntimeException("Precinct not found.");
        }

        $ballot = (new BallotData(
            code: $ballotCode,
            votes: new DataCollection(VoteData::class, $votes->all()),
        // Set precinct code after construction (not part of core ballot identity)
        ))->setPrecinctCode($precinct->code);

        $this->precinctContext->putBallot($ballot);

        return $ballot;
    }
}
