<?php

namespace App\Console\Commands;

use App\Actions\SignElectionReturn as SignElectionReturnAction;
use App\Data\SignPayloadData;
use App\Models\ElectionReturn;
use App\Support\SignatureFileStore;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CertifyElectionReturn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * `signatures` is optional; if provided inline (not via --file/--dir/STDIN),
     * we will also persist per-role signature files to the `signatures` disk.
     */
    protected $signature = 'certify-er
        {signatures?* : Inline signature entries (e.g., "id=uuid-juan,signature=ABC123" or "uuid-juan|ABC123")}
        {--er= : Election return code (with or without ER- prefix). Optional if exactly one ER exists.}
        {--file= : Path to a file containing signature lines}
        {--dir= : Path to a directory of *.txt files with signature lines}';

    /**
     * The console command description.
     */
    protected $description = 'Apply one or more signatures to an Election Return (non-interactive).';

    public function handle(): int
    {
        // --- Resolve target ER code (DB stores bare code; users may pass with/without ER-)
        $erOpt  = (string) ($this->option('er') ?? '');
        $erCode = $erOpt !== '' && str_starts_with($erOpt, 'ER-')
            ? substr($erOpt, 3)
            : $erOpt;

        if ($erCode === '') {
            // QoL: auto-pick only ER if exactly one exists; else error
            $count = ElectionReturn::query()->count();
            if ($count === 0) {
                $this->error('No Election Return exists yet. Prepare an ER before certifying.');
                return self::FAILURE;
            }
            if ($count > 1) {
                $this->error('Multiple Election Returns found. Please specify --er=CODE (with or without ER- prefix).');
                return self::FAILURE;
            }
            $single = ElectionReturn::query()->firstOrFail();
            $erCode = $single->code; // already bare or ER-? Your schema stores bare; safe to use as-is
        }

        /** @var ElectionReturn $er */
        $er = ElectionReturn::with('precinct')->where('code', $erCode)->first();
        if (!$er) {
            $this->error('No query results for model [App\Models\ElectionReturn].');
            return self::FAILURE;
        }

        $erFolderName = str_starts_with($er->code, 'ER-') ? $er->code : ('ER-' . $er->code);
//        $roster = (array) ($er->precinct->electoral_inspectors ?? []);
        $roster = collect($er->precinct->electoral_inspectors ?? [])
            ->map(function ($ei) {
                // Coerce to array
                $arr = is_array($ei) ? $ei : (array) $ei;

                // Extract fields
                $id   = $arr['id']   ?? (is_object($ei) && isset($ei->id)   ? $ei->id   : null);
                $name = $arr['name'] ?? (is_object($ei) && isset($ei->name) ? $ei->name : null);

                // role can be scalar or enum
                $roleRaw = $arr['role'] ?? (is_object($ei) && isset($ei->role) ? $ei->role : null);
                if (is_object($roleRaw) && property_exists($roleRaw, 'value')) {
                    $roleRaw = $roleRaw->value;
                }

                return [
                    'id'   => (string) $id,
                    'name' => (string) $name,
                    'role' => is_string($roleRaw) ? strtolower(trim($roleRaw)) : '',
                ];
            })
            ->all();

        // --- Gather inputs: inline args, file, dir, STDIN
        $inline   = (array) $this->argument('signatures') ?: [];
        $filePath = (string) ($this->option('file') ?? '');
        $dirPath  = (string) ($this->option('dir') ?? '');

        $lines = [];
        if (!empty($inline)) {
            $lines = array_merge($lines, $inline);
        }
        if ($filePath !== '') {
            $lines = array_merge($lines, $this->readLinesFromFile($filePath));
        }
        if ($dirPath !== '') {
            $lines = array_merge($lines, $this->readLinesFromDir($dirPath));
        }
        if (empty($inline) && $filePath === '' && $dirPath === '') {
            // If nothing provided, try STDIN (non-interactive)
            $stdin = $this->readStdin();
            if ($stdin !== '') {
                $lines = array_merge($lines, $this->splitLines($stdin));
            }
        }

        if (empty($lines)) {
            $this->warn('No signature lines provided. Nothing to do.');
            return self::SUCCESS;
        }

        // Only persist per-role files when inline arguments are used (per your requirement)
        $persistInlineFiles = !empty($inline);
        $store = app(SignatureFileStore::class);

        $ok = 0; $failed = 0;
        foreach ($lines as $idx => $rawLine) {
            $lineNo = $idx + 1;
            $rawLine = trim($rawLine);
            if ($rawLine === '' || str_starts_with(ltrim($rawLine), '#')) {
                continue; // skip blanks and comments
            }

            try {
                $payload = $this->toPayload($rawLine);

                // Run the action (DB lookup by roster id; action returns array message+person+when)
                $result = SignElectionReturnAction::run($payload, $er->code);

                // Log success
                $who   = $result['id']   ?? '(unknown-id)';
                $role  = $result['role'] ?? '(unknown-role)';
                $when  = $result['signed_at'] ?? CarbonImmutable::now()->toIso8601String();
                $this->info(sprintf('OK line %d: %s signed as %s @ %s', $lineNo, $who, $role, $when));
                $ok++;

                // Persist inline signatures to files only when inline args were used
                if ($persistInlineFiles) {
                    try {
                        $store->persist($result, $erFolderName, $roster);
                    } catch (\Throwable $e) {
                        $this->warn(sprintf('WARN line %d: could not persist signature file for %s: %s', $lineNo, $who, $e->getMessage()));
                    }
                }

            } catch (\Throwable $e) {
                $this->error(sprintf('FAIL line %d: %s', $lineNo, $e->getMessage()));
                $failed++;
            }
        }

        $this->line(sprintf('Done. OK=%d, FAILED=%d', $ok, $failed));
        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Convert a raw line into SignPayloadData.
     * Supported formats:
     *   - "id=uuid-juan,signature=ABC123[,signed_at=ISO]"
     *   - "uuid-juan|ABC123" (id|signature)
     */
    private function toPayload(string $line): SignPayloadData
    {
        // key=value format
        if (str_contains($line, '=')) {
            $pairs = collect(preg_split('/\s*,\s*/', $line, -1, PREG_SPLIT_NO_EMPTY))
                ->mapWithKeys(function ($kv) {
                    $parts = array_map('trim', explode('=', $kv, 2));
                    if (count($parts) === 2 && $parts[0] !== '') {
                        return [$parts[0] => $parts[1]];
                    }
                    return [];
                })->all();

            $id  = $pairs['id'] ?? null;
            $sig = $pairs['signature'] ?? null;
            $when= $pairs['signed_at'] ?? null;

            if (!$id || !$sig) {
                throw new \RuntimeException('Missing required "id" or "signature" (key=value format).');
            }

            return SignPayloadData::from([
                'id'        => $id,
                'signature' => $sig,
                'signed_at' => $when, // optional
            ]);
        }

        // pipe format: id|signature
        if (str_contains($line, '|')) {
            $parts = array_map('trim', explode('|', $line));
            if (count($parts) < 2) {
                throw new \RuntimeException('Invalid pipe format. Expected "id|signature".');
            }
            [$id, $sig] = $parts;

            if ($id === '' || $sig === '') {
                throw new \RuntimeException('Invalid pipe format. "id" and "signature" cannot be empty.');
            }

            return SignPayloadData::from([
                'id'        => $id,
                'signature' => $sig,
                // no signed_at → action will stamp the current time
            ]);
        }

        throw new \RuntimeException('Unrecognized signature line format. Use "id=...,signature=..." or "id|signature".');
    }

    /** Read lines from a single file (trim, skip blanks/comments). */
    private function readLinesFromFile(string $path): array
    {
        $abs = base_path($path);
        if (!File::exists($abs)) {
            // try as given (relative to CWD)
            $abs = $path;
        }
        if (!File::exists($abs)) {
            throw new \RuntimeException("File not found: {$path}");
        }
        $contents = File::get($abs);
        return $this->splitLines($contents);
    }

    /** Read all *.txt in a directory, concatenated (trim, skip blanks/comments). */
    private function readLinesFromDir(string $dir): array
    {
        $abs = base_path($dir);
        if (!is_dir($abs)) {
            // try as given
            $abs = $dir;
        }
        if (!is_dir($abs)) {
            throw new \RuntimeException("Directory not found: {$dir}");
        }

        $lines = [];
        foreach (File::files($abs) as $file) {
            if (strtolower($file->getExtension()) !== 'txt') {
                continue;
            }
            $contents = File::get($file->getRealPath());
            $lines = array_merge($lines, $this->splitLines($contents));
        }
        return $lines;
    }

    /** Split multi-line content into trimmed, non-empty, non-comment lines. */
    private function splitLines(string $contents): array
    {
        return array_values(array_filter(array_map(function ($l) {
            $l = rtrim($l, "\r\n");
            return (trim($l) === '' || str_starts_with(ltrim($l), '#')) ? null : $l;
        }, preg_split('/\R/u', $contents) ?: [])));
    }

    /** Read entire STDIN if any (non-blocking in common test/invoke patterns). */
    private function readStdin(): string
    {
        // If STDIN is not a pipe, many environments behave as empty; safe to attempt
        $buf = '';
        // phpcs:ignore
        while (!feof(STDIN)) {
            $chunk = fgets(STDIN);
            if ($chunk === false) break;
            $buf .= $chunk;
        }
        return trim($buf);
    }
}
