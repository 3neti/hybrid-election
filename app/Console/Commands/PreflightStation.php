<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;
use App\Models\{Position, Precinct};
use App\Actions\InitializeSystem;

class PreflightStation extends Command
{
    /**
     * Signature
     *
     * NOTE: We use --detail (not --verbose) to avoid colliding with Symfony's built-in verbosity.
     */
    protected $signature = 'preflight-er
        {--election=config/election.json : Path to election.json}
        {--precinct=config/precinct.yaml : Path to precinct.yaml}
        {--force : Initialize from config even if DB has data (non-destructive update)}
        {--fail-on-warn : Treat warnings as failures (exit 1)}
        {--detail : Print each check result}
        {--base= : Base URL for endpoint checks (e.g. http://127.0.0.1:8000). If omitted, endpoint checks are skipped}
        {--endpoints= : Comma-separated paths to probe (default: /api/health,/api/election-return?payload=minimal)}
        {--auth= : Bearer token for endpoint checks (optional)}
        {--timeout=2000 : Endpoint timeout in ms}';

    protected $description = 'Run preflight checks for ER station: config, DB, initialization, storage, and API endpoints';

    public function handle(): int
    {
        $electionPath = (string) $this->option('election');
        $precinctPath = (string) $this->option('precinct');
        $force        = (bool) $this->option('force');
        $failOnWarn   = (bool) $this->option('fail-on-warn');
        $detail       = (bool) $this->option('detail');

        $base      = $this->option('base');                 // null → skip endpoint checks
        $auth      = $this->option('auth') ?: null;
        $timeoutMs = (int) $this->option('timeout');

        $endpoints = $this->option('endpoints')
            ? array_values(array_filter(array_map('trim', explode(',', $this->option('endpoints')))))
            : ['/api/health', '/api/election-return?payload=minimal'];

        $results = [];

        // 1) Config files present & valid
        $results[] = $this->checkConfigFiles($electionPath, $precinctPath, $detail);

        // 2) Storage writable (local disk)
        $results[] = $this->checkStorageWritable($detail);

        // 3) DB initialized (positions + precinct)
        $initialized = Position::query()->exists() && Precinct::query()->exists();

        if (! $initialized) {
            if ($detail) {
                $this->warn('• System not initialized (positions/precinct missing).');
            }

            // 4) If requested, initialize now (non-destructive)
            if ($force) {
                try {
                    if ($detail) {
                        $this->line('  → Initializing from config via InitializeSystem...');
                    }

                    // Non-destructive bootstrap: reset=false, but let action upsert
                    InitializeSystem::run(
                        reset: false,
                        electionPath: $this->toAbsolutePath($electionPath),
                        precinctPath: $this->toAbsolutePath($precinctPath)
                    );

                    $initialized = Position::query()->exists() && Precinct::query()->exists();
                    if ($initialized) {
                        $results[] = $this->resOk('Initialize system', 'System initialized successfully.');
                        if ($detail) $this->info('  ✓ Initialization complete.');
                    } else {
                        $results[] = $this->resFail('Initialize system', 'Initialization did not create required records.');
                        if ($detail) $this->error('  ✗ Initialization did not produce required records.');
                    }
                } catch (\Throwable $e) {
                    $results[] = $this->resFail('Initialize system', 'Initialization error: '.$e->getMessage(), [
                        'exception' => get_class($e),
                    ]);
                    if ($detail) {
                        $this->error('  ✗ Initialization failed: '.$e->getMessage());
                    }
                }
            } else {
                $results[] = $this->resWarn('System initialized', 'System not initialized. Run with --force to initialize.');
                if ($detail) $this->warn('  ! Use --force to initialize from config.');
            }
        } else {
            $results[] = $this->resOk('System initialized', 'Positions and precinct exist.');
            if ($detail) $this->info('• System initialized: OK');
        }

        // 5) Endpoint reachability (optional)
        if ($base) {
            $results[] = $this->checkEndpoints($base, $endpoints, $auth, $timeoutMs, $detail);
        } else {
            if ($detail) $this->line('• Endpoints: skipped (no --base provided)');
        }

        // ---- Summary & exit code ----
        $summary = $this->summarize($results);
        $this->renderSummaryTable($results);

        if ($summary['fail'] > 0) {
            $this->error('Preflight: FAIL (one or more checks failed).');
            return self::FAILURE;
        }
        if ($failOnWarn && $summary['warn'] > 0) {
            $this->warn('Preflight: WARN treated as FAIL (--fail-on-warn set).');
            return self::FAILURE;
        }

        $this->info('Preflight: OK');
        return self::SUCCESS;
    }

    // ------------------------------------------------------------------
    // Checks (pluggable-style helpers)
    // ------------------------------------------------------------------

    protected function checkConfigFiles(string $electionPath, string $precinctPath, bool $detail): array
    {
        $absElection = $this->toAbsolutePath($electionPath);
        $absPrecinct = $this->toAbsolutePath($precinctPath);

        // election.json
        if (! File::exists($absElection)) {
            if ($detail) $this->error("• Config: missing {$electionPath}");
            return $this->resFail('Config files', "Missing {$electionPath}");
        }
        $electionJson = File::get($absElection);
        $electionData = json_decode($electionJson, true);
        if (! is_array($electionData)) {
            if ($detail) $this->error("• Config: invalid JSON in {$electionPath}");
            return $this->resFail('Config files', "Invalid JSON in {$electionPath}");
        }

        // precinct.yaml
        if (! File::exists($absPrecinct)) {
            if ($detail) $this->error("• Config: missing {$precinctPath}");
            return $this->resFail('Config files', "Missing {$precinctPath}");
        }
        try {
            $precinctData = Yaml::parseFile($absPrecinct);
            if (! is_array($precinctData)) {
                throw new \RuntimeException('Not an array.');
            }
        } catch (\Throwable $e) {
            if ($detail) $this->error("• Config: invalid YAML in {$precinctPath}: ".$e->getMessage());
            return $this->resFail('Config files', "Invalid YAML in {$precinctPath}", ['error' => $e->getMessage()]);
        }

        // minimal shape
        $missing = [];
        foreach (['positions','candidates'] as $key) {
            if (! array_key_exists($key, $electionData)) $missing[] = "election.json:{$key}";
        }
        foreach (['code'] as $key) {
            if (! array_key_exists($key, $precinctData)) $missing[] = "precinct.yaml:{$key}";
        }
        if ($missing) {
            if ($detail) $this->warn('• Config: present but missing keys: '.implode(', ', $missing));
            return $this->resWarn('Config files', 'Present but some keys missing', ['missing' => $missing]);
        }

        if ($detail) $this->info('• Config: OK (election.json & precinct.yaml present and parseable)');
        return $this->resOk('Config files', 'Present and parseable');
    }

    protected function checkStorageWritable(bool $detail): array
    {
        // Check local storage/app writability & a few common subdirs we use
        $paths = [
            storage_path('app'),
            storage_path('app/qr_exports'),
            storage_path('logs'),
        ];

        $notWritable = [];
        foreach ($paths as $p) {
            if (! File::exists($p)) {
                // Attempt to create directories we control
                @File::makeDirectory($p, 0777, true);
            }
            if (! is_writable($p)) $notWritable[] = $p;
        }

        if ($notWritable) {
            if ($detail) $this->error('• Storage writable: some paths not writable');
            return $this->resFail('Storage writable', 'Some storage paths are not writable', ['paths' => $notWritable]);
        }

        if ($detail) $this->info('• Storage writable: OK');
        return $this->resOk('Storage writable', 'All required storage paths writable');
    }

    protected function checkEndpoints(string $base, array $endpoints, ?string $auth, int $timeoutMs, bool $detail): array
    {
        $client = Http::timeout(max(1, (int)ceil($timeoutMs / 1000)))->acceptJson();
        if ($auth) $client = $client->withToken($auth);

        $meta = [];
        $allOk = true;

        foreach ($endpoints as $path) {
            $url = rtrim($base, '/') . '/' . ltrim($path, '/');
            $started = microtime(true);

            try {
                $res = $client->head($url);
                if ($res->status() === 405) {
                    $res = $client->get($url);
                }
                $ms = (int)((microtime(true) - $started) * 1000);

                $okStatus = in_array($res->status(), [200, 204], true);
                $entry = [
                    'url'        => $url,
                    'status'     => $res->status().' '.$res->reason(),
                    'latency_ms' => $ms,
                ];

                if ($okStatus) {
                    $meta[] = $entry;
                    if ($detail) $this->info(sprintf('• Endpoint OK: %s (%s, %dms)', $url, $entry['status'], $ms));
                } else {
                    $allOk = false;
                    $entry['snippet'] = str($res->body())->limit(200)->toString();
                    $meta[] = $entry;
                    if ($detail) $this->error(sprintf('• Endpoint FAIL: %s (%s)', $url, $entry['status']));
                }
            } catch (\Throwable $e) {
                $allOk = false;
                $meta[] = ['url' => $url, 'error' => $e->getMessage()];
                if ($detail) $this->error(sprintf('• Endpoint ERROR: %s (%s)', $url, $e->getMessage()));
            }
        }

        return $allOk
            ? $this->resOk('Endpoints', 'All endpoints reachable', $meta)
            : $this->resFail('Endpoints', 'One or more endpoints failed', $meta);
    }

    // ------------------------------------------------------------------
    // Utilities
    // ------------------------------------------------------------------

    protected function toAbsolutePath(string $path): string
    {
        // If already absolute or stream, return as-is
        if ($path === '' || str_starts_with($path, DIRECTORY_SEPARATOR) || preg_match('/^[a-zA-Z]:[\\\\\\/]/', $path)) {
            return $path;
        }
        return base_path($path);
    }

    protected function resOk(string $name, string $message, array $meta = []): array
    {
        return ['name' => $name, 'status' => 'ok', 'message' => $message, 'meta' => $meta];
    }

    protected function resWarn(string $name, string $message, array $meta = []): array
    {
        return ['name' => $name, 'status' => 'warn', 'message' => $message, 'meta' => $meta];
    }

    protected function resFail(string $name, string $message, array $meta = []): array
    {
        return ['name' => $name, 'status' => 'fail', 'message' => $message, 'meta' => $meta];
    }

//    protected function ok(string $name, string $message, array $meta = []): array
//    {
//        return ['name' => $name, 'status' => 'ok', 'message' => $message, 'meta' => $meta];
//    }
//
//    protected function warn(string $name, string $message, array $meta = []): array
//    {
//        return ['name' => $name, 'status' => 'warn', 'message' => $message, 'meta' => $meta];
//    }
//
//    protected function fail(string $name, string $message, array $meta = []): array
//    {
//        return ['name' => $name, 'status' => 'fail', 'message' => $message, 'meta' => $meta];
//    }

    protected function summarize(array $results): array
    {
        $ok = $warn = $fail = 0;
        foreach ($results as $r) {
            if ($r['status'] === 'ok') $ok++;
            elseif ($r['status'] === 'warn') $warn++;
            elseif ($r['status'] === 'fail') $fail++;
        }
        return compact('ok','warn','fail');
    }

    protected function renderSummaryTable(array $results): void
    {
        $rows = array_map(fn($r) => [$r['name'], strtoupper($r['status']), $r['message']], $results);
        $this->line('');
        $this->table(['Check', 'Status', 'Message'], $rows);
    }
}
