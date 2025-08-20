<?php

namespace App\Console\Pipes;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Services\Qr\QrExporter;
use Closure;

final class ExportQr
{
    public function __construct(private QrExporter $qr) {}

    public function handle($ctx, \Closure $next)
    {
        // Run exporter
        $res = $this->qr->export($ctx->er->code, [
            'payload'   => $ctx->payload,
            'max_chars' => $ctx->maxChars,
            'dir'       => basename($ctx->folder), // usually 'final'
        ]);

        $ctx->qrPersistedAbs = $res->persistedToAbs;

        // -------- Mirror artifacts into the election disk (robust defaults) --------
        // If the context doesn't specify a disk, prefer 'election' if configured, otherwise 'local'.
        $diskName = (isset($ctx->disk) && $ctx->disk)
            ? $ctx->disk
            : (config('filesystems.disks.election') ? 'election' : 'local');

        $electionDisk = \Illuminate\Support\Facades\Storage::disk($diskName);

        // Ensure ER-<code>/<dir>/qr exists on the chosen disk
        $targetDir = rtrim((string) $ctx->folder, '/').'/qr';
        $electionDisk->makeDirectory($targetDir);

        $persistedAbs = (string) $ctx->qrPersistedAbs;
        $localRoot    = \Illuminate\Support\Facades\Storage::disk('local')->path('');

        if (\Illuminate\Support\Str::startsWith($persistedAbs, $localRoot)) {
            // Copy from "local" disk using relative path
            $rel = ltrim(substr($persistedAbs, strlen($localRoot)), DIRECTORY_SEPARATOR);

            if (\Illuminate\Support\Facades\Storage::disk('local')->exists($rel)) {
                foreach (\Illuminate\Support\Facades\Storage::disk('local')->files($rel) as $file) {
                    $basename = basename($file);
                    $bytes    = \Illuminate\Support\Facades\Storage::disk('local')->get($file);
                    $electionDisk->put("{$targetDir}/{$basename}", $bytes);
                }
            }
        } else {
            // Fallback: filesystem copy from absolute path outside the "local" disk
            if (is_dir($persistedAbs)) {
                foreach (\Illuminate\Support\Facades\File::files($persistedAbs) as $f) {
                    $basename = $f->getFilename();
                    $bytes    = \Illuminate\Support\Facades\File::get($f->getRealPath());
                    $electionDisk->put("{$targetDir}/{$basename}", $bytes);
                }
            }
        }
        // ---------------------------------------------------------------------------

        return $next($ctx);
    }

//    public function handle($ctx, Closure $next)
//    {
//        $res = $this->qr->export($ctx->er->code, [
//            'payload'   => $ctx->payload,
//            'max_chars' => $ctx->maxChars,
//            'dir'       => basename($ctx->folder), // usually 'final'
//        ]);
//        $ctx->qrPersistedAbs = $res->persistedToAbs;
//        return $next($ctx);
//    }
}
