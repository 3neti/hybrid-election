<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Console\Pipelines\FinalizeErContext;
use App\Console\Pipes\CloseBalloting;
use Illuminate\Support\Carbon;
use App\Models\Precinct;

uses(RefreshDatabase::class);

/** Small helper: run the pipe and capture the context it passes along */
function run_close_balloting_pipe(FinalizeErContext $ctx): object {
    $pipe = new CloseBalloting();
    return $pipe->handle($ctx, fn ($c) => $c);
}

it('closes balloting and stamps closed_at (happy path)', function () {
    Carbon::setTestNow('2025-08-19T12:34:56+08:00');

    $p = Precinct::factory()->create([
        'meta' => ['balloting_open' => true],
    ]);

    $ctxIn  = finalize_ctx_with_precinct($p);
    $ctxOut = run_close_balloting_pipe($ctxIn);

    // Context is passed through
    expect($ctxOut)->toBeObject()->and($ctxOut->precinct->is($p))->toBeTrue();

    // Reload and assert meta flags
    $fresh = $p->fresh();
    expect($fresh->meta['balloting_open'] ?? null)->toBeFalse();
    expect($fresh->meta['closed_at'] ?? null)->toBe('2025-08-19T12:34:56+08:00');

    Carbon::setTestNow();
});

it('works when meta is null or empty', function () {
    Carbon::setTestNow('2025-01-02T03:04:05Z');

    $p = Precinct::factory()->create(['meta' => null]);

    run_close_balloting_pipe(finalize_ctx_with_precinct($p));

    $fresh = $p->fresh();
    expect($fresh->meta['balloting_open'] ?? null)->toBeFalse();

    // ✅ Compare instants (timezone-agnostic)
    $closed = $fresh->meta['closed_at'] ?? null;
    expect($closed)->not->toBeNull();
    expect(\Carbon\Carbon::parse($closed)->equalTo(now()))->toBeTrue();
    // Alternatively:
//     expect(\Carbon\Carbon::parse($closed)->diffInSeconds(now()))->toBe(0);

    Carbon::setTestNow();
});

it('preserves unrelated meta keys', function () {
    $p = Precinct::factory()->create([
        'meta' => [
            'balloting_open' => true,
            'some_flag'      => 'keep-me',
            'number'         => 42,
        ],
    ]);

    run_close_balloting_pipe(finalize_ctx_with_precinct($p));

    $fresh = $p->fresh();
    expect($fresh->meta['some_flag'])->toBe('keep-me');
    expect($fresh->meta['number'])->toBe(42);
    expect($fresh->meta['balloting_open'])->toBeFalse();
    expect($fresh->meta)->toHaveKey('closed_at');
});

use Carbon\CarbonImmutable as CI;

it('running twice keeps balloting closed and refreshes closed_at timestamp', function () {
    Carbon::setTestNow('2025-02-01T10:00:00Z');

    $p = Precinct::factory()->create(['meta' => ['balloting_open' => true]]);
    run_close_balloting_pipe(finalize_ctx_with_precinct($p));

    $firstStr = $p->fresh()->meta->get('closed_at');
    $first    = CI::parse($firstStr);

    // Assert instant == 10:00Z (regardless of offset formatting)
    expect($first->equalTo(CI::parse('2025-02-01T10:00:00Z')))->toBeTrue();

    // Advance time and run again
    Carbon::setTestNow('2025-02-01T10:05:00Z');
    run_close_balloting_pipe(finalize_ctx_with_precinct($p));

    $fresh    = $p->fresh();
    $second   = CI::parse($fresh->meta->get('closed_at'));

    expect($fresh->meta->get('balloting_open'))->toBeFalse();

    // Same-instant assertion in a TZ-agnostic way
    expect($second->equalTo(CI::parse('2025-02-01T10:05:00Z')))->toBeTrue();

    // And ensure it actually refreshed
    expect($second->greaterThan($first))->toBeTrue();

    Carbon::setTestNow();
});
