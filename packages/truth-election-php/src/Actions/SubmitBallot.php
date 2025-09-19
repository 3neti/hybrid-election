<?php

namespace TruthElection\Actions;

use TruthElection\Support\ElectionStoreInterface;
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
        protected ElectionStoreInterface $store,
        protected PrecinctContext $precinctContext
    ) {}

    public function handle(string $ballotCode, Collection $votes): BallotData
    {
        $precinct = $this->precinctContext->getPrecinct();

        if (!$precinct) {
            throw new \RuntimeException("Precinct not found.");
        }

        $ballot = new BallotData(
            code: $ballotCode,
            votes: new DataCollection(VoteData::class, $votes->all()),
        );

        $this->store->putBallot($ballot, $precinct->code);

        return $ballot;
    }
}
