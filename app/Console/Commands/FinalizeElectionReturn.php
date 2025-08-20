<?php

namespace App\Console\Commands;

use App\Console\Pipelines\FinalizeErContext;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Console\Command;
use App\Models\Precinct;

final class FinalizeElectionReturn extends Command
{
    protected $signature = 'close-er
        {--force}
        {--persist=final}
        {--payload=minimal}
        {--max-chars-per-qr=1200}';

    protected $description = 'Generate final ER, export artifacts (JSON & QR), and close balloting';

    public function handle(): int
    {
        $precinct = Precinct::query()->firstOrFail();

        $meta = (array)($precinct->meta ?? []);
        if (($meta['balloting_open'] ?? true) === false) {
            $this->warn('Balloting already closed. Nothing to do.');
            return self::SUCCESS;
        }

        $disk    = Storage::disk('election') ? 'election' : 'local';
        $payload = in_array(($this->option('payload') ?? 'minimal'), ['minimal','full'], true)
            ? (string)$this->option('payload') : 'minimal';
        $max     = (int)($this->option('max-chars-per-qr') ?? 1200);
        $dir     = (string)($this->option('persist') ?: 'final');

        // Temp context with placeholder ER (will be recomputed by first pipe)
        $ctx = new FinalizeErContext(
            precinct: $precinct,
            er:      null, // set by ComputeElectionReturn
            disk:    $disk,
            folder:  '',   // set after ER computed
            payload: $payload,
            maxChars:$max,
            force:   (bool)$this->option('force'),
        );

        // run first pipe alone to compute ER so we can set folder
        $ctx = app(\App\Console\Pipes\ComputeElectionReturn::class)->handle($ctx, fn($c) => $c);
        $ctx->folder = 'ER-'.$ctx->er->code.'/'.$dir;

        // now pipeline for the rest
        app(Pipeline::class)
            ->send($ctx)
            ->through([
                \App\Console\Pipes\ValidateSignatures::class,
                \App\Console\Pipes\ExportErJson::class,
                \App\Console\Pipes\ExportQr::class,
                \App\Console\Pipes\MirrorQrArtifacts::class,
                \App\Console\Pipes\CloseBalloting::class,
            ])->thenReturn();

        $this->info('✅ Final ER exported under: storage/app/'.$ctx->folder);
        $this->info('🔒 Balloting CLOSED.');
        return self::SUCCESS;
    }
}
