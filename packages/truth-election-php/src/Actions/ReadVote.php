<?php

namespace TruthElection\Actions;

use TruthElection\Support\ElectionStoreInterface;
use TruthElection\Support\PrecinctContext;
use TruthElection\Support\MappingContext;
use Lorisleiva\Actions\Concerns\AsAction;
use TruthElection\Data\BallotData;

class ReadVote
{
    use AsAction;

    public function __construct(
        protected ElectionStoreInterface $store,
        protected PrecinctContext $precinctContext,
    ) {}

    public function handle(string $ballotCode, string $key): BallotData
    {
        $this->store->addBallotMark($ballotCode, $key);

        return (new MappingContext($this->store))->resolveBallot($ballotCode);
    }
}
