<?php

namespace App\Console\Pipes;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Closure;

final class CloseBalloting
{
    public function handle($ctx, Closure $next)
    {
        // Always stamp in UTC for deterministic tests/archives
//        $nowUtc = CarbonImmutable::now('UTC')->toIso8601String();
        $now = \Carbon\CarbonImmutable::now()->toIso8601String();

        // In normal runtime this is a SchemalessAttributes object.
        // In some tests/seeds it can be null/array, so guard.
        $meta = $ctx->precinct->meta;

        // SchemalessAttributes instance? (most common)
        if (is_object($meta) && method_exists($meta, 'set')) {
            $meta->set('balloting_open', false);
            $meta->set('closed_at', $now);

            // Fallback: null or array — coerce and reassign
        } else {
            $asArray = (array) $meta;
            $asArray['balloting_open'] = false;
            $asArray['closed_at']      = $now;
            $ctx->precinct->meta = $asArray; // reassign so it’s persisted
        }

        $ctx->precinct->save();

        return $next($ctx);
    }
//    public function handle($ctx, Closure $next)
//    {
//        // $ctx->precinct->meta is a SchemalessAttributes instance
//        $meta = $ctx->precinct->meta;
//
//        // Set/overwrite just the keys we own
//        $meta->set('balloting_open', false);
////        $meta->set('closed_at', now()->toIso8601String());
//        $meta->set('closed_at', now('UTC')->toIso8601String());
//
//        // Persist
//        $ctx->precinct->save();
//
//        return $next($ctx);
//    }

//    public function handle($ctx, Closure $next)
//    {
//        // Normalize existing meta into an associative array
//        $raw = $ctx->precinct->meta ?? [];
//
//        if ($raw instanceof Collection) {
//            $meta = $raw->toArray();
//        } elseif (is_string($raw)) {
//            $decoded = json_decode($raw, true);
//            $meta = is_array($decoded) ? $decoded : [];
//        } elseif (is_array($raw)) {
//            $meta = $raw;
//        } elseif (is_object($raw)) {
//            // stdClass or DTO-like
//            $meta = (array) $raw;
//        } else {
//            $meta = [];
//        }
//
//        // Merge our fields without dropping unrelated keys
//        $meta['balloting_open'] = false;
//        $meta['closed_at'] = now()->toIso8601String();
//
//        $ctx->precinct->meta = $meta;
//        $ctx->precinct->save();
//
//        return $next($ctx);
//    }
//    public function handle($ctx, Closure $next)
//    {
//        $meta = (array)($ctx->precinct->meta ?? []);
//        $meta['balloting_open'] = false;
//        $meta['closed_at'] = now()->toIso8601String();
//        $ctx->precinct->meta = $meta;
//        $ctx->precinct->save();
//
//        return $next($ctx);
//    }
}
