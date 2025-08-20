<?php

namespace App\Console\Pipes;

use App\Actions\GenerateElectionReturn as GenerateEr;
use Closure;

final class ComputeElectionReturn
{
    public function handle($ctx, Closure $next)
    {
        $ctx->er = GenerateEr::run($ctx->precinct);
        return $next($ctx);
    }
}
