<?php

namespace App\Console\Commands;

use App\Actions\SignElectionReturn;
use App\Data\SignPayloadData;
use App\Models\ElectionReturn;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;

class CertifyElectionReturn extends Command
{
    /**
     * Signature:
     *  - Optional --er (may be "ER-XXXX" or just "XXXX"); if omitted, we try to infer the single ER in DB.
     *  - Optional --file / --dir to ingest signatures from files
     *  - Positional "signatures" (0..*) for inline entries (key=value, pipe, or csv forms)
     */
    protected $signature = 'certify-er
        {signatures?* : Inline signature entries (e.g., "id=uuid-juan,signature=ABC123" or "uuid-juan|ABC123")}
        {--er= : Election return code (with or without ER- prefix). Optional if exactly one ER exists.}
        {--file= : Path to a file containing signature lines}
        {--dir= : Path to a directory of *.txt files with signature lines}';

    protected $description = 'Attach one or more signatures to an election return (non-interactive).';

    public function handle(): int
    {
        try {
            // Resolve ER code or fail with a clear message (returns plain code without "ER-" prefix)
            $erCode = $this->resolveErCodeOrFail();

            // Collect all signature lines: arguments + file + dir + STDIN
            $lines = $this->gatherLines();

            if (empty($lines)) {
                $this->warn('No signatures supplied. Nothing to do.');
                return self::SUCCESS;
            }

            $ok = 0;
            $failed = 0;
            foreach ($lines as $idx => $line) {
                $ln = $idx + 1;
                $line = trim($line);
                if ($line === '' || str_starts_with(ltrim($line), '#')) {
                    continue;
                }

                try {
                    $payload = $this->parseSignatureLine($line);
                    // Convert into data DTO
                    $data = SignPayloadData::from([
                        'id'        => $payload['id'] ?? null,
                        'signature' => $payload['signature'] ?? null,
                        'when'      => $payload['when'] ?? null, // optional; action uses now() if null
                    ]);

                    // Run the action (expects plain ER code, no "ER-" prefix)
                    $res = SignElectionReturn::run($data, $erCode);
                    $this->line(sprintf('OK line %d: %s (%s)', $ln, $payload['id'] ?? 'unknown', $res['message'] ?? 'signed'));
                    $ok++;
                } catch (Throwable $e) {
                    $this->error(sprintf('FAIL line %d: %s', $ln, $e->getMessage()));
                    $failed++;
                }
            }

            $this->line(sprintf('Done. OK=%d, FAILED=%d', $ok, $failed));
            return $failed > 0 ? self::FAILURE : self::SUCCESS;

        } catch (Throwable $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }

    /**
     * Resolve the election return code to use (without the "ER-" prefix).
     * - If --er is provided, normalize and return.
     * - If not provided:
     *    - 0 ER rows  -> error
     *    - 1 ER row   -> use that one
     *    - >1 ER rows -> error (ambiguous)
     */
    protected function resolveErCodeOrFail(): string
    {
        $erOpt = (string) ($this->option('er') ?? '');

        // If provided, normalize and return
        if ($erOpt !== '') {
            return str_starts_with($erOpt, 'ER-') ? substr($erOpt, 3) : $erOpt;
        }

        // No --er: infer from DB
        $count = ElectionReturn::count();
        if ($count === 0) {
            throw new \RuntimeException(
                'No election return found. Please run "php artisan prepare-er" first or pass --er=CODE.'
            );
        }
        if ($count > 1) {
            throw new \RuntimeException(
                'Multiple election returns found. Please specify which one with --er=CODE (with or without ER- prefix).'
            );
        }

        // Exactly one ER → use its code
        /** @var ElectionReturn $er */
        $er = ElectionReturn::firstOrFail();
        $code = $er->code;
        return str_starts_with($code, 'ER-') ? substr($code, 3) : $code;
    }

    /**
     * Collect signature lines from:
     *  - positional arguments
     *  - --file (single file of lines)
     *  - --dir (all *.txt files)
     *  - STDIN (piped)
     */
    protected function gatherLines(): array
    {
        $lines = [];

        // positional arguments
        $argLines = (array) $this->argument('signatures');
        foreach ($argLines as $l) {
            if (is_string($l) && trim($l) !== '') $lines[] = $l;
        }

        // --file
        $file = $this->option('file');
        if (is_string($file) && $file !== '') {
            $path = base_path($file);
            if (!File::exists($path)) {
                throw new \RuntimeException("File not found: {$file}");
            }
            $content = File::get($path);
            $rows = preg_split('/\R/u', $content, -1, PREG_SPLIT_NO_EMPTY) ?: [];
            foreach ($rows as $l) {
                if (trim($l) !== '') $lines[] = $l;
            }
        }

        // --dir
        $dir = $this->option('dir');
        if (is_string($dir) && $dir !== '') {
            $dirPath = base_path($dir);
            if (!is_dir($dirPath)) {
                throw new \RuntimeException("Directory not found: {$dir}");
            }
            $files = collect(File::files($dirPath))
                ->filter(fn ($f) => Str::endsWith(strtolower($f->getFilename()), '.txt'))
                ->sortBy(fn ($f) => $f->getFilename());

            foreach ($files as $f) {
                $rows = preg_split('/\R/u', File::get($f->getRealPath()), -1, PREG_SPLIT_NO_EMPTY) ?: [];
                foreach ($rows as $l) {
                    if (trim($l) !== '') $lines[] = $l;
                }
            }
        }

        // STDIN
        if (function_exists('posix_isatty') ? !posix_isatty(STDIN) : false) {
            $stdin = stream_get_contents(STDIN);
            if ($stdin !== false && trim($stdin) !== '') {
                $rows = preg_split('/\R/u', $stdin, -1, PREG_SPLIT_NO_EMPTY) ?: [];
                foreach ($rows as $l) {
                    if (trim($l) !== '') $lines[] = $l;
                }
            }
        }

        return $lines;
    }

    /**
     * Accepts:
     *  - key=value form: "id=uuid-juan,signature=ABC123[,when=...]"
     *  - pipe form:      "uuid-juan|ABC123[|2025-08-19T...Z]"
     *  - CSV form:       "uuid-juan,ABC123[,2025-08-19T...Z]"
     */
    protected function parseSignatureLine(string $line): array
    {
        // key=value
        if (str_contains($line, '=')) {
            $pairs = array_filter(array_map('trim', preg_split('/[,;]/', $line)));
            $out = [];
            foreach ($pairs as $pair) {
                [$k, $v] = array_pad(array_map('trim', explode('=', $pair, 2)), 2, null);
                if ($k !== null && $v !== null) {
                    $out[$k] = $v;
                }
            }
            if (empty($out['id']) || empty($out['signature'])) {
                throw new \RuntimeException('Missing required fields "id" and/or "signature".');
            }
            return $out;
        }

        // pipe
        if (str_contains($line, '|')) {
            $parts = array_map('trim', explode('|', $line));
            if (count($parts) < 2) {
                throw new \RuntimeException('Invalid pipe entry. Expected "id|signature[|when]".');
            }
            return [
                'id'        => $parts[0],
                'signature' => $parts[1],
                'when'      => $parts[2] ?? null,
            ];
        }

        // csv
        $parts = array_map('trim', explode(',', $line));
        if (count($parts) < 2) {
            throw new \RuntimeException('Invalid entry. Use key=value, "id|signature", or "id,signature".');
        }
        return [
            'id'        => $parts[0],
            'signature' => $parts[1],
            'when'      => $parts[2] ?? null,
        ];
    }
}
