<?php

namespace TruthElection\Actions;

use TruthElection\Support\ElectionStoreInterface;
use TruthElection\Support\PrecinctContext;
use TruthElection\Support\MappingContext;
use Lorisleiva\Actions\Concerns\AsAction;
use TruthElection\Data\BallotData;
use Illuminate\Support\Collection;

class FinalizeBallot
{
    use AsAction;

    public function __construct(
        protected ElectionStoreInterface $store,
        protected PrecinctContext $precinctContext,
    ) {}

    public function handle(string $ballotCode): BallotData
    {
        // 1️⃣ Resolve the ballot marks into structured votes
        $mappingContext = new MappingContext($this->store);
        $resolved = $mappingContext->resolveBallot($ballotCode); // Returns BallotData

        // 2️⃣ Submit the resolved ballot under the precinct
        return SubmitBallot::run(
            ballotCode: $resolved->code,
            votes: collect($resolved->votes->items()),
        );
    }
}
