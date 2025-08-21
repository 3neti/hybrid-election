<?php

namespace App\Console\Pipes;

use App\Policies\Signatures\SignaturePolicy;
use App\Console\Pipelines\FinalizeErContext;
use Closure;

final class ValidateSignatures
{
    public function __construct(private SignaturePolicy $policy) {}

    public function handle(FinalizeErContext $ctx, Closure $next)
    {
        $sigs = $ctx->er->signatures ?? [];
        if ($sigs instanceof \Spatie\LaravelData\DataCollection) $sigs = $sigs->toArray();
        elseif ($sigs instanceof \Illuminate\Support\Collection) $sigs = $sigs->toArray();

        $this->policy->assertSatisfied((array)$sigs, $ctx->force);
        return $next($ctx);
    }
}
