<?php

namespace App\Console\Pipes;

use App\Actions\GenerateElectionReturn as GenerateEr;
use App\Console\Pipelines\FinalizeErContext;
use Closure;

final class ComputeElectionReturn
{
    public function handle(FinalizeErContext $ctx, Closure $next)
    {
        $ctx->er = GenerateEr::run($ctx->precinct);

        return $next($ctx);
    }
}
