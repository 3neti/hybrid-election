<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use App\Models\{Position, Precinct};
use App\Actions\InitializeSystem;

class EnsureSystemInitialized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Fast path: already initialized?
        if (Position::query()->exists() && Precinct::query()->exists()) {
            return $next($request);
        }

        // Optional file overrides from the request (useful for tests)
        $electionPath = $request->query('election');
        $precinctPath = $request->query('precinct');

        // Single-process guard to avoid duplicate initialization in concurrent requests
        Cache::lock('init-system-lock', 10)->block(10, function () use ($electionPath, $precinctPath) {
            // Double-check after acquiring the lock
            if (Position::query()->exists() && Precinct::query()->exists()) {
                return;
            }

            // IMPORTANT: pass discrete args (not an array). Use named args for clarity.
            InitializeSystem::run(
                reset: false,                  // first-run bootstrap should be non-destructive
                electionPath: $electionPath,   // null → the action will use its default config path
                precinctPath: $precinctPath
            );
        });

        return $next($request);
    }
}
