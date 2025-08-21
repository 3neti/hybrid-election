<?php

namespace App\Console\Pipes;

use Illuminate\Support\Facades\{File, Storage};
use App\Console\Pipelines\FinalizeErContext;
use Closure;

final class MirrorQrArtifacts
{
    public function handle(FinalizeErContext $ctx, Closure $next)
    {
        if (!$ctx->qrPersistedAbs) return $next($ctx);

        $target = "{$ctx->folder}/qr";
        $election = Storage::disk($ctx->disk);
        $election->makeDirectory($target);

        $localRoot = Storage::disk('local')->path('');
        if (str_starts_with($ctx->qrPersistedAbs, $localRoot)) {
            $rel = ltrim(substr($ctx->qrPersistedAbs, strlen($localRoot)), DIRECTORY_SEPARATOR);
            if (Storage::disk('local')->exists($rel)) {
                foreach (Storage::disk('local')->files($rel) as $file) {
                    $election->put("{$target}/".basename($file), Storage::disk('local')->get($file));
                }
            }
        } else {
            foreach (File::files($ctx->qrPersistedAbs) as $f) {
                $election->put("{$target}/".$f->getFilename(), File::get($f->getRealPath()));
            }
        }

        return $next($ctx);
    }
}
