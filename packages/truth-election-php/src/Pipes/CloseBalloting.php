<?php

namespace TruthElection\Pipes;

use TruthElection\Actions\InputPrecinctStatistics;
use TruthElection\Support\InMemoryElectionStore;
use TruthElection\Data\FinalizeErContext;
use Closure;

final class CloseBalloting
{
    public function handle(FinalizeErContext $ctx, Closure $next): FinalizeErContext
    {
        $store = InMemoryElectionStore::instance();
        $precinct = $store->precincts[$ctx->precinct->code];

        if (empty($precinct->closed_at)) {
            InputPrecinctStatistics::run($precinct->code, [
                'closed_at' => now()->toISOString(),
            ]);
        }

        return $next($ctx);
    }
}
