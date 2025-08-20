<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Actions\SignElectionReturn as SignElectionReturnAction;
use App\Data\SignPayloadData;
use Symfony\Component\Finder\Finder;

class CertifyElectionReturn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage samples:
     *  php artisan certify-er --er=ER-DNPT6VLVFF3N "id=uuid-juan,signature=ABC123"
     *  php artisan certify-er --er=DNPT6VLVFF3N "uuid-juan|ABC123" "uuid-maria|DEF456"
     *  php artisan certify-er --er=DNPT6VLVFF3N  --file=storage/signatures/demo.txt
     * php artisan certify-er --er=DNPT6VLVFF3N  --dir=storage/signatures
     *  cat signatures.txt | php artisan certify-er --er=ER-ABC123
     */
    protected $signature = 'certify-er
        {signatures? : Zero or more signature lines (use args, --file/--dir or STDIN)}
        {--er= : Election Return code to sign (required)}
        {--file= : Read signature lines from a file (one per line)}
        {--dir= : Read signature lines from all files in this directory}
        {--log= : Optional log file to append results}
        {--dry-run : Parse and show what would happen without writing}
    ';

    protected $description = 'Attach one or more signatures to an Election Return (by ER code).';

    public function handle(): int
    {
        // Read options
        $erOpt  = (string) ($this->option('er') ?? '');
        // Strip "ER-" prefix if present, always return just the code
        $erCode = $this->normalizeErCode($erOpt);

        if ($erCode === '') {
            $this->error('Missing required option: --er=ER_CODE');
            return self::INVALID;
        }

        $dryRun  = (bool) $this->option('dry-run');
        $logPath = $this->option('log') ?: null;

        // 1) Gather lines from args, file, dir, stdin (in that order)
        $lines = $this->gatherLines(
            (array) $this->argument('signatures'),
            (string) ($this->option('file') ?? ''),
            (string) ($this->option('dir') ?? '')
        );

        if (empty($lines)) {
            $this->warn('No signature lines provided. See --help for format and examples.');
            return self::SUCCESS;
        }

        $ok = 0; $failed = 0;

        foreach ($lines as $idx => $line) {
            $n = $idx + 1;
            $line = trim($line);
            if ($line === '' || str_starts_with(ltrim($line), '#')) {
                continue;
            }

            try {
                [$id, $signature] = $this->parseLine($line);

                if ($dryRun) {
                    $this->line(sprintf('DRY-RUN %02d) id=%s signature=%s', $n, $id, $this->shorten($signature)));
                    $this->appendLog($logPath, "DRY-RUN\t{$id}");
                    $ok++;
                    continue;
                }

                $payload = SignPayloadData::from([
                    'id'        => $id,
                    'signature' => $signature,
                ]);

                // Call the action exactly as defined: (SignPayloadData, erCode)
                app(SignElectionReturnAction::class)->run($payload, $erCode);

                $this->info(sprintf('OK %s', $id));
                $this->appendLog($logPath, "OK\t{$id}");
                $ok++;
            } catch (\Throwable $e) {
                $this->error(sprintf('FAIL line %d: %s', $n, $e->getMessage()));
                $this->appendLog($logPath, "FAIL\tline{$n}\t".preg_replace('/\s+/',' ', $e->getMessage()));
                $failed++;
            }
        }

        $this->line(sprintf('Done. OK=%d, FAILED=%d', $ok, $failed));
        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Accepts:
     *   - "id|signature"
     *   - "id=...,signature=..."
     * Returns [id, signature].
     */
    private function parseLine(string $line): array
    {
        // pipe form: id|signature
        if (str_contains($line, '|') && !str_contains($line, '=')) {
            [$id, $sig] = array_pad(explode('|', $line, 2), 2, null);
            $id  = trim((string) $id);
            $sig = trim((string) $sig);
            if ($id === '' || $sig === '') {
                throw new \InvalidArgumentException('Pipe format requires "id|signature".');
            }
            return [$id, $sig];
        }

        // kv form: id=...,signature=...
        if (str_contains($line, '=')) {
            $pairs = preg_split('/\s*,\s*/', $line);
            $bag = [];
            foreach ($pairs as $pair) {
                if ($pair === '') continue;
                [$k, $v] = array_pad(explode('=', $pair, 2), 2, null);
                $k = strtolower(trim((string)$k));
                $v = (string) ($v ?? '');
                if ($k !== '') $bag[$k] = $v;
            }
            $id  = trim((string) ($bag['id'] ?? ''));
            $sig = trim((string) ($bag['signature'] ?? ''));
            if ($id === '' || $sig === '') {
                throw new \InvalidArgumentException('KV format requires id=... and signature=...');
            }
            return [$id, $sig];
        }

        throw new \InvalidArgumentException('Unrecognized line format. Use "id|signature" or "id=...,signature=...".');
    }

    /**
     * Gather input lines from args, --file, --dir, or STDIN (in that order).
     */
    private function gatherLines(array $args, string $file, string $dir): array
    {
        $out = [];

        // args
        foreach ($args as $a) {
            if (is_string($a) && trim($a) !== '') $out[] = $a;
        }

        // file
        if ($file !== '') {
            if (!File::exists($file)) {
                throw new \RuntimeException("File not found: {$file}");
            }
            $out = array_merge($out, $this->readFileLines($file));
        }

        // dir
        if ($dir !== '') {
            if (!File::isDirectory($dir)) {
                throw new \RuntimeException("Directory not found: {$dir}");
            }
            $finder = new Finder();
            $finder->files()->in($dir);
            foreach ($finder as $f) {
                $out = array_merge($out, $this->readFileLines($f->getRealPath()));
            }
        }

        // STDIN (only if nothing else was provided)
        if (empty($out) && !posix_isatty(STDIN)) {
            $buf = stream_get_contents(STDIN);
            if ($buf !== false && trim($buf) !== '') {
                $out = preg_split('/\R/u', $buf, -1, PREG_SPLIT_NO_EMPTY) ?: [];
            }
        }

        return $out;
    }

    private function readFileLines(string $path): array
    {
        $raw = File::get($path);
        return $raw === '' ? [] : (preg_split('/\R/u', $raw, -1, PREG_SPLIT_NO_EMPTY) ?: []);
    }

    private function appendLog(?string $path, string $line): void
    {
        if (!$path) return;
        try {
            File::append($path, $line.PHP_EOL);
        } catch (\Throwable $e) {
            // best-effort
        }
    }

    private function shorten(string $s, int $max = 24): string
    {
        $s = trim($s);
        return mb_strlen($s) <= $max ? $s : (mb_substr($s, 0, $max - 1) . '…');
    }

    private function normalizeErCode(?string $raw): string
    {
        $code = trim((string) $raw);
        if ($code !== '' && str_starts_with($code, 'ER-')) {
            $code = substr($code, 3);
        }
        return $code;
    }
}
