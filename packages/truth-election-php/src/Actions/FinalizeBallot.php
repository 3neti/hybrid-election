<?php

namespace TruthElection\Actions;

use TruthElection\Support\ElectionStoreInterface;
use TruthElection\Support\PrecinctContext;
use TruthElection\Support\MappingContext;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Log;
use TruthElection\Data\BallotData;

class FinalizeBallot
{
    use AsAction;

    public function __construct(
        protected ElectionStoreInterface $store,
        protected PrecinctContext $precinctContext,
    ) {}

    public function handle(string $ballotCode): BallotData
    {
        Log::info("[FinalizeBallot] Resolving ballot marks for: {$ballotCode}");

        $mappingContext = new MappingContext($this->store);
        $resolved = $mappingContext->resolveBallot($ballotCode);

        Log::info("[FinalizeBallot] Submitting resolved ballot", [
            'ballot_code' => $resolved->code,
            'vote_count' => $resolved->votes->count(),
        ]);

        return SubmitBallot::run($resolved);
    }
}
