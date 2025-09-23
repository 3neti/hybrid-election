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
        // Validate the mark key exists using MappingContext
        $context = new MappingContext($this->store);

        // Will throw if the mark is invalid 🚨
        $context->getMark($key);

        // Add the mark after validation
        $this->store->addBallotMark($ballotCode, $key);

        // Resolve and return the ballot data
        return $context->resolveBallot($ballotCode);
    }
}
