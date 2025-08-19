<?php

namespace App\Console\Commands;

use Illuminate\Http\Client\{HttpClientException, RequestException};
use App\Data\{CandidateData, PositionData, VoteData};
use App\Actions\{InitializeSystem, SubmitBallot};
use App\Models\{Candidate, Position, Precinct};
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Throwable;

class CastBallot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage styles:
     * 1) Single line (HTTP):  php artisan app:cast-ballot "BAL-001|PRESIDENT:SJ_002;SENATOR:JD_001,JL_004"
     * 2) Local mode (no HTTP): php artisan app:cast-ballot --local "BAL-001|PRESIDENT:SJ_002"
     * 3) From a file/pipe:     cat ballots.txt | php artisan app:cast-ballot --local
     *
     * Options:
     *  --endpoint=           Override URL (default: route('ballots.submit') if available, else /api/ballots)
     *  --local               Use local SubmitBallot action instead of HTTP
     *  --election=           Optional path to election.json (for first-run init)
     *  --precinct=           Optional path to precinct.yaml (for first-run init)
     *  --log=                Optional log file path to append results
     *  --dry-run             Parse only, don’t submit
     */
    protected $signature = 'app:cast-ballot
        {lines?* : One or more ballot lines in CODE|POS1:CANDA,CANDB;POS2:... format. If omitted, read from STDIN.}
        {--endpoint= : Override POST endpoint (default route(\'ballots.submit\') or /api/ballots)}
        {--local : Use local SubmitBallot action instead of HTTP}
        {--election= : Path to election.json (for first-run auto-initialize)}
        {--precinct= : Path to precinct.yaml (for first-run auto-initialize)}
        {--log= : File path to append a per-line result log}
        {--dry-run : Parse only; do not submit}';

    /**
     * The console command description.
     */
    protected $description = <<<DESC
Cast ballots from a compact, delimiter-based format.

INPUT FORMAT (no spaces needed; case-sensitive candidate/position codes):
  CODE|POS1:CANDA,CANDB;POS2:CANDX;POS3:C1,C2,C3

Examples:
  BAL-001|PRESIDENT:SJ_002;SENATOR:JD_001,JL_004
  BAL-123|VICE-PRESIDENT:CH_003;COUNCILOR:C1,C2,C3,C4,C5,C6,C7,C8

Multiple lines can be supplied:
  php artisan app:cast-ballot "BAL-001|PRESIDENT:SJ_002" "BAL-002|PRESIDENT:LRA_001"

From a file/pipe:
  cat ballots.txt | php artisan app:cast-ballot --local

Modes:
  HTTP (default)    → POSTs to route('ballots.submit') or /api/ballots
  Local (--local)   → Uses SubmitBallot action directly (no HTTP). Ensures system is initialized on first-run.
DESC;

    public function handle(): int
    {
        $useLocal     = (bool) $this->option('local');
        $endpointOpt  = $this->option('endpoint');
        $dryRun       = (bool) $this->option('dry-run');
        $logPath      = $this->option('log');
        $electionPath = $this->option('election') ?: null;
        $precinctPath = $this->option('precinct') ?: null;

        // Determine endpoint for HTTP mode
        $endpoint = $endpointOpt
            ?: (function () {
                try {
                    if (function_exists('route')) {
                        return route('ballots.submit');
                    }
                } catch (\Throwable) {
                    // fall back
                }
                return '/api/ballots';
            })();

        // Read input lines either from arguments or STDIN
        $argLines = (array) $this->argument('lines');
        $lines    = [];
        if (!empty($argLines)) {
            $lines = $argLines;
        } else {
            // Read from STDIN (piped / redirected)
            $stdin = '';
            while (!feof(STDIN)) {
                $chunk = fgets(STDIN);
                if ($chunk === false) break;
                $stdin .= $chunk;
            }
            if (trim($stdin) !== '') {
                $lines = preg_split('/\R/u', $stdin, -1, PREG_SPLIT_NO_EMPTY) ?: [];
            }
        }

        if (empty($lines)) {
            $this->warn('No ballot lines provided. See --help for format and examples.');
            return self::SUCCESS;
        }

        // If local mode, ensure the system is initialized once (idempotent)
        if ($useLocal) {
            $this->ensureInitializedOnce($electionPath, $precinctPath);
        }

        $ok = 0; $skipped = 0; $failed = 0;
        $context = $useLocal ? 'local' : 'HTTP';

        foreach ($lines as $idx => $line) {
            $lineNo = $idx + 1;
            $line   = trim($line);
            if ($line === '' || str_starts_with(ltrim($line), '#')) {
                // Skip blank/comment lines
                continue;
            }

            try {
                [$code, $votesSpec] = $this->parseLine($lineNo, $line);

                if ($dryRun) {
                    $this->line(sprintf('DRY-RUN parsed %s → %s', $code, json_encode($votesSpec)));
                    $ok++;
                    $this->appendLog($logPath, "DRY-RUN\t{$code}\tOK");
                    continue;
                }

                if ($useLocal) {
                    try {
                        // Build VoteData from DB models
                        $dataVotes = $this->buildVoteData($lineNo, $votesSpec);

                        // Run action (local mode)
                        $result = SubmitBallot::run(
                            code: $code,
                            votes: new DataCollection(VoteData::class, $dataVotes),
                        );

                        // Local mode may not attach _status; default to 201 (created)
                        $status = $result->_status ?? 201;

                        // ---------- Inline logging & counters ----------
                        if ($status === 201) {
                            $this->info(sprintf('CREATED %s (%s %d)', $code, $context, $status));
                            $ok++;
                            $this->appendLog($logPath, "CREATED\t{$code}\t{$status}");
                        } elseif ($status === 200) {
                            $this->line(sprintf('OK %s (%s %d)', $code, $context, $status));
                            $ok++;
                            $this->appendLog($logPath, "OK\t{$code}\t{$status}");
                        } elseif ($status === 409) {
                            $this->warn(sprintf('SKIP %s (%s %d conflict)', $code, $context, $status));
                            $skipped++;
                            $this->appendLog($logPath, "SKIP\t{$code}\t{$status}");
                        } else {
                            $this->error(sprintf('FAIL %s (%s %d)', $code, $context, $status));
                            $failed++;
                            $this->appendLog($logPath, "FAIL\t{$code}\t{$status}");
                        }
                        // ----------------------------------------------

                    } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
                        // SubmitBallot throws HttpException(409) on code reuse with different votes
                        $status = $e->getStatusCode();

                        if ($status === 409) {
                            $this->warn(sprintf('SKIP %s (%s %d conflict)', $code, $context, $status));
                            $skipped++;
                            $this->appendLog($logPath, "SKIP\t{$code}\t{$status}\t".$e->getMessage());
                        } else {
                            $this->error(sprintf('Line %d: %s', $lineNo, $e->getMessage()));
                            $failed++;
                            $this->appendLog($logPath, "FAIL\t{$code}\t{$status}\t".$e->getMessage());
                        }

                    } catch (\Illuminate\Validation\ValidationException $e) {
                        $this->error(sprintf('Line %d: %s', $lineNo, $e->getMessage()));
                        $failed++;
                        $this->appendLog($logPath, "FAIL\t{$code}\t0\t".$e->getMessage());

                    } catch (\Throwable $e) {
                        $this->error(sprintf('Line %d: %s', $lineNo, $e->getMessage()));
                        $failed++;
                        $this->appendLog($logPath, "FAIL\t{$code}\t0\t".$e->getMessage());
                    }

                } else {
                    // HTTP mode
                    $payload = $this->toHttpPayload($code, $votesSpec);
                    try {
                        $res = Http::acceptJson()->asJson()->retry(0, 0)->post($endpoint, $payload);
                    } catch (RequestException $e) {
                        $status = $e->response ? $e->response->status() : 0;
                        $this->error(sprintf('Line %d: HTTP error for %s: %s', $lineNo, $code, $e->getMessage()));
                        $failed++;
                        $this->appendLog($logPath, "FAIL\t{$code}\t{$status}\t{$e->getMessage()}");
                        continue;
                    } catch (HttpClientException $e) {
                        $this->error(sprintf('Line %d: HTTP client error for %s: %s', $lineNo, $code, $e->getMessage()));
                        $failed++;
                        $this->appendLog($logPath, "FAIL\t{$code}\t0\t{$e->getMessage()}");
                        continue;
                    }

                    $status = $res->status();

                    // ---------- Inline logging & counters ----------
                    if ($status === 201) {
                        $this->info(sprintf('CREATED %s (%s %d)', $code, $context, $status));
                        $ok++;
                        $this->appendLog($logPath, "CREATED\t{$code}\t{$status}");
                    } elseif ($status === 200) {
                        $this->line(sprintf('OK %s (%s %d)', $code, $context, $status));
                        $ok++;
                        $this->appendLog($logPath, "OK\t{$code}\t{$status}");
                    } elseif ($status === 409) {
                        $this->warn(sprintf('SKIP %s (%s %d conflict)', $code, $context, $status));
                        $skipped++;
                        $this->appendLog($logPath, "SKIP\t{$code}\t{$status}");
                    } else {
                        $msg = $res->json('message') ?? $res->body();
                        $this->error(sprintf('FAIL %s (%s %d): %s', $code, $context, $status, Str::limit((string) $msg, 200)));
                        $failed++;
                        $this->appendLog($logPath, "FAIL\t{$code}\t{$status}\t".preg_replace('/\s+/',' ',(string)$msg));
                    }
                    // ----------------------------------------------
                }

            } catch (Throwable $e) {
                $this->error(sprintf('Line %d: %s', $lineNo, $e->getMessage()));
                $failed++;
                $this->appendLog($logPath, "FAIL\tline{$lineNo}\t0\t".preg_replace('/\s+/',' ', $e->getMessage()));
            }
        }

        $this->line(sprintf('Done. OK=%d, SKIPPED=%d, FAILED=%d', $ok, $skipped, $failed));
        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Parse "CODE|POS1:CANDA,CANDB;POS2:C1,C2" into [code, [ [position => 'POS', candidates => ['A','B']], ... ] ]
     */
    private function parseLine(int $lineNo, string $line): array
    {
        $parts = explode('|', $line, 2);
        if (count($parts) !== 2) {
            throw new \InvalidArgumentException("Line {$lineNo}: Expected 'CODE|...'; got: {$line}");
        }
        $code = trim($parts[0]);
        $spec = trim($parts[1]);

        if ($code === '') {
            throw new \InvalidArgumentException("Line {$lineNo}: Missing ballot CODE before '|'.");
        }
        if ($spec === '') {
            throw new \InvalidArgumentException("Line {$lineNo}: Missing positions/candidates after '|'.");
        }

        $blocks = array_filter(array_map('trim', explode(';', $spec)));
        $votes  = [];

        foreach ($blocks as $block) {
            [$pos, $candPart] = array_pad(explode(':', $block, 2), 2, null);
            $pos = trim((string) $pos);
            if ($pos === '' || $candPart === null) {
                throw new \InvalidArgumentException("Line {$lineNo}: Bad block '{$block}', expected POS:CODE[,CODE...]");
            }
            $candCodes = array_values(array_filter(array_map(fn($c) => trim($c), explode(',', $candPart)), fn($c) => $c !== ''));
            if (empty($candCodes)) {
                throw new \InvalidArgumentException("Line {$lineNo}: No candidates provided for position {$pos}.");
            }
            $votes[] = ['position' => $pos, 'candidates' => $candCodes];
        }

        return [$code, $votes];
    }

    /**
     * Build VoteData[] for local SubmitBallot::run() by loading models.
     */
    private function buildVoteData(int $lineNo, array $votesSpec): array
    {
        // Make sure we have the single precinct
        $precinct = Precinct::query()->first();
        if (!$precinct) {
            throw new \RuntimeException("Line {$lineNo}: System not initialized (no precinct).");
        }

        $built = [];
        foreach ($votesSpec as $v) {
            $posCode = $v['position'];
            /** @var Position|null $pos */
            $pos = Position::query()->where('code', $posCode)->first();
            if (!$pos) {
                throw new \InvalidArgumentException("Line {$lineNo}: Unknown position code: {$posCode}");
            }

            $candModels = Candidate::query()
                ->whereIn('code', $v['candidates'])
                ->get()
                ->keyBy('code');

            // Validate all candidate codes exist
            foreach ($v['candidates'] as $c) {
                if (!$candModels->has($c)) {
                    throw new \InvalidArgumentException("Line {$lineNo}: Unknown candidate code: {$c}");
                }
            }

            $candData = $candModels
                ->values()
                ->map(fn(Candidate $c) => new CandidateData(code: $c->code, name: $c->name, alias: $c->alias));

            $built[] = new VoteData(
                position: new PositionData(code: $pos->code, name: $pos->name, level: $pos->level, count: $pos->count),
                candidates: new DataCollection(CandidateData::class, $candData)
            );
        }

        return $built;
    }

    /**
     * HTTP payload shape expected by SubmitBallot controller.
     */
    private function toHttpPayload(string $code, array $votesSpec): array
    {
        return [
            'code'  => $code,
            'votes' => array_map(function (array $v) {
                return [
                    'position'   => ['code' => $v['position']],
                    'candidates' => array_map(fn($c) => ['code' => $c], $v['candidates']),
                ];
            }, $votesSpec),
        ];
    }

    /**
     * Minimal in-command bootstrap similar to EnsureSystemInitialized middleware.
     */
    private function ensureInitializedOnce(?string $electionPath, ?string $precinctPath): void
    {
        // If anything already exists, skip
        if (Position::query()->exists() && Precinct::query()->exists()) return;

        // Call the action, non-destructive on first run
        InitializeSystem::run(
            reset: false,
            electionPath: $electionPath,
            precinctPath: $precinctPath
        );
    }

    private function appendLog(?string $path, string $line): void
    {
        if (!$path) return;
        try {
            file_put_contents($path, $line . PHP_EOL, FILE_APPEND);
        } catch (\Throwable) {
            // swallow logging errors
        }
    }
}
