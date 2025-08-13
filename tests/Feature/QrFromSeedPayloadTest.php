<?php

use Illuminate\Support\Facades\{Artisan, Storage};
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\GenerateElectionReturn;
use function Pest\Laravel\getJson;
use App\Models\Precinct;

/**
 * ─────────────────────────────────────────────────────────────────────────────
 * Election Return QR Export & Round-Trip Test Suite
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * PURPOSE:
 * These tests verify the integrity, persistence, and chunking of election return
 * data when encoded into QR code segments via the `/qr.er` HTTP endpoint.
 *
 * SCOPE:
 * 1. **Minimal Payload** – Verifies small, stripped-down payloads can be split
 *    into a target number of QR chunks (~4) and successfully reconstructed
 *    (round-tripped) from HTTP responses and/or persisted files.
 *
 * 2. **Full Payload** – Tests full election return JSON payloads with/without PNG
 *    generation, ensuring proper chunk counts, file persistence, and lossless
 *    reconstruction.
 *
 * 3. **Persistence & File Validation** – Ensures that generated QR chunks are
 *    written to disk under `storage/app/qr_exports/{precinct_code}/{unique_dir}`
 *    along with `manifest.json`, `raw.json`, `README.md`, and corresponding
 *    `.txt` / `.png` chunk files.
 *
 * 4. **Round-Trip Decoding** – Validates that compressed base64-url QR chunk data
 *    can be decompressed (gzinflate) and parsed back into its original DTO
 *    structure without data loss.
 *
 * UTILITIES:
 * - `_b64uToBin()` – Decodes base64-url strings to binary.
 * - `_norm()` – Normalizes associative/ordered arrays for comparison.
 * - `_buildMinimalPayload()` – Extracts only the minimal fields from
 *   `ElectionReturnData` for minimal payload scenarios.
 * - `uniqueDir()` – Generates a unique export folder name per test run.
 *
 * NOTES:
 * - `->skip()` is applied to some tests for selective execution.
 * - Storage is not faked in persistence tests to validate real file creation.
 * - Cleanup of persisted files is controlled by the `KEEP_QR_EXPORTS` env var.
 *
 * RELATED:
 * - `GenerateElectionReturn` Action – Produces the `ElectionReturnData` DTO
 *   used in these tests.
 * - `/qr.er` route – Handles QR export logic, chunking, compression, and optional
 *   image generation.
 */

/* ───────────────────── Helpers ───────────────────── */

function _b64uToBin(string $s): string {
    $pad = strlen($s) % 4;
    if ($pad) $s .= str_repeat('=', 4 - $pad);
    return base64_decode(strtr($s, '-_', '+/')) ?: '';
}

function _norm(mixed $v): mixed {
    if (!is_array($v)) return $v;
    foreach ($v as $k => $vv) $v[$k] = _norm($vv);
    $assoc = array_keys($v) !== range(0, count($v) - 1);
    if ($assoc) ksort($v);
    return $v;
}

/** Build the “minimal” JSON payload (mirror of controller) */
function _buildMinimalPayload(\App\Data\ElectionReturnData $dto): array {
    // --- tallies ---
    $tArr = is_array($dto->tallies) ? $dto->tallies : $dto->tallies->toArray();
    $tallies = array_map(fn($t) => [
        'position_code'  => is_array($t) ? ($t['position_code'] ?? null) : $t->position_code,
        'candidate_code' => is_array($t) ? ($t['candidate_code'] ?? null) : $t->candidate_code,
        'candidate_name' => is_array($t) ? ($t['candidate_name'] ?? null) : $t->candidate_name,
        'count'          => is_array($t) ? ($t['count'] ?? null) : $t->count,
    ], $tArr);

    // --- precinct core ---
    $precinct = [
        'id'   => $dto->precinct->id,
        'code' => $dto->precinct->code,
    ];

    // precinct extras (include only when present)
    $locationName = $dto->precinct->location_name ?? null;
    $lat = $dto->precinct->latitude ?? null;
    $lon = $dto->precinct->longitude ?? null;
    if ($locationName !== null && $locationName !== '') $precinct['location_name'] = $locationName;
    if ($lat !== null) $precinct['latitude'] = $lat;
    if ($lon !== null) $precinct['longitude'] = $lon;

    // electoral inspectors (id, name, optional role)
    $eiRaw = $dto->precinct->electoral_inspectors ?? [];
    if (!is_array($eiRaw) && method_exists($eiRaw, 'toArray')) $eiRaw = $eiRaw->toArray();
    $inspectors = array_values(array_map(function ($ei) {
        $id   = is_array($ei) ? ($ei['id']   ?? null) : ($ei->id   ?? null);
        $name = is_array($ei) ? ($ei['name'] ?? null) : ($ei->name ?? null);
        $role = is_array($ei) ? ($ei['role'] ?? null) : ($ei->role ?? null);
        $row = ['id' => $id, 'name' => $name];
        if ($role !== null && $role !== '') $row['role'] = $role;
        return $row;
    }, is_array($eiRaw) ? $eiRaw : []));
    if (count($inspectors) > 0) $precinct['electoral_inspectors'] = $inspectors;

    // assemble result
    $result = [
        'id'       => $dto->id,
        'code'     => $dto->code,
        'precinct' => $precinct,
        'tallies'  => $tallies,
    ];

    // --- signatures (lightweight only, include when present) ---
    $sigRaw = $dto->signatures ?? [];
    if (!is_array($sigRaw) && method_exists($sigRaw, 'toArray')) $sigRaw = $sigRaw->toArray();
    $signatures = array_values(array_map(function ($s) {
        $id   = is_array($s) ? ($s['id']        ?? null) : ($s->id        ?? null);
        $name = is_array($s) ? ($s['name']      ?? null) : ($s->name      ?? null);
        $role = is_array($s) ? ($s['role']      ?? null) : ($s->role      ?? null);
        $when = is_array($s) ? ($s['signed_at'] ?? null) : ($s->signed_at ?? null);
        $row = [];
        if ($id   !== null) $row['id'] = $id;
        if ($name !== null) $row['name'] = $name;
        if ($role !== null && $role !== '') $row['role'] = $role;
        if ($when !== null && $when !== '') $row['signed_at'] = $when;
        return $row;
    }, is_array($sigRaw) ? $sigRaw : []));
    if (count($signatures) > 0) $result['signatures'] = $signatures;

    return $result;
}

/** Unique folder per test to avoid collisions */
function uniqueDir(string $prefix): string {
    return $prefix.'_'.bin2hex(random_bytes(6));
}

/* ───────────────────── Setup ───────────────────── */

uses(RefreshDatabase::class);

beforeEach(function () {
    Artisan::call('db:seed');
});

/* ───────────────────── Tests ───────────────────── */

/** Minimal payload, target ~4 chunks, PNGs on, custom ECC/size/margin */
it('HTTP minimal payload hits ~4 chunks and round-trips (PNG on)', function () {
    $precinct = Precinct::query()->firstOrFail();
    $dto = app(GenerateElectionReturn::class)->run($precinct);

    $resp = getJson(
        route('qr.er', ['code' => $dto->code]) .
        '?payload=minimal&desired_chunks=4&make_images=1&ecc=medium&size=640&margin=12'
    );
    $resp->assertOk();

    $json = $resp->json();

    // Around 4 chunks (±2)
    expect($json['total'])->toBeGreaterThanOrEqual(2)
        ->and($json['total'])->toBeLessThanOrEqual(6);

    // PNG is best-effort; accept either a PNG or a png_error note
    expect(isset($json['chunks'][0]['png']) || isset($json['chunks'][0]['png_error']))->toBeTrue();

    // Round-trip the payload text only
    $joined = collect($json['chunks'])->sortBy('index')
        ->map(fn($c) => explode('|', $c['text'], 5)[4] ?? '')
        ->implode('');

    $inflated = gzinflate(_b64uToBin($joined));
    $decoded  = json_decode($inflated, true);

    $expected = _buildMinimalPayload($dto);
    expect(_norm($decoded))->toEqual(_norm($expected));
});

it('HTTP minimal payload (~4 chunks) persists files and round-trips', function () {
    // NOTE: no Storage::fake() here — we want real files!
    $precinct = Precinct::query()->firstOrFail();
    $dto = app(GenerateElectionReturn::class)->run($precinct);

    $suffix = uniqueDir('mini_4');
    $rel    = 'qr_exports/'.$dto->code.'/'.$suffix;

    $resp = getJson(
        route('qr.er', ['code' => $dto->code]) .
        '?payload=minimal&desired_chunks=4&make_images=1&ecc=medium&size=640&margin=12' .
        '&persist=1&persist_dir=' . $suffix
    );
    $resp->assertOk();

    $json = $resp->json();

    // Around 4 chunks (±2)
    expect($json['total'])->toBeGreaterThanOrEqual(2)
        ->and($json['total'])->toBeLessThanOrEqual(6);

    // Files exist on disk
    expect(Storage::disk('local')->exists($rel.'/manifest.json'))->toBeTrue()
        ->and(Storage::disk('local')->exists($rel.'/raw.json'))->toBeTrue()
        ->and(Storage::disk('local')->exists($rel.'/README.md'))->toBeTrue();

    $files = collect(Storage::disk('local')->files($rel));
    $txt   = $files->filter(fn($p) => str_ends_with($p, '.txt'))->values();
    $png   = $files->filter(fn($p) => str_ends_with($p, '.png'))->values();

    expect($txt)->toHaveCount($json['total'])
        ->and($png->count())->toBe($json['total']);

    // Reassemble from .txt files
    $joined = collect(range(1, (int)$json['total']))->map(function ($i) use ($rel, $json) {
        $path = "{$rel}/chunk_{$i}of{$json['total']}.txt";
        if (!Storage::disk('local')->exists($path)) {
            $cand = collect(Storage::disk('local')->files($rel))
                ->first(fn($p) => str_contains($p, "chunk_{$i}of"));
            $path = $cand ?? $path;
        }
        $line = Storage::disk('local')->get($path);
        return explode('|', $line, 5)[4] ?? '';
    })->implode('');

    $inflated = gzinflate(_b64uToBin($joined));
    $decoded  = json_decode($inflated, true);

    $expected = _buildMinimalPayload($dto);
    expect(_norm($decoded))->toEqual(_norm($expected));

    // ---- visibility: tell you where the files are ----
    $abs = Storage::disk('local')->path($rel);
    echo "\n[Persisted] {$abs}\n";

    // optional cleanup toggle (defaults to KEEP)
    if (!env('KEEP_QR_EXPORTS', true)) {
        Storage::disk('local')->deleteDirectory($rel);
        echo "[Cleanup] Removed persisted files (KEEP_QR_EXPORTS=false)\n";
    }
})->skip();

it('HTTP full payload persists text and round-trips from disk (no PNGs)', function () {
    // NOTE: no Storage::fake() here — we want real files!

    $precinct = Precinct::query()->firstOrFail();
    $dto = app(GenerateElectionReturn::class)->run($precinct);

    $suffix = uniqueDir('full_no_png');
    $rel    = 'qr_exports/'.$dto->code.'/'.$suffix;

    $resp = getJson(
        route('qr.er', ['code' => $dto->code]) .
        '?payload=full&desired_chunks=4&make_images=0' .
        '&persist=1&persist_dir=' . $suffix
    );
    $resp->assertOk();

    $json = $resp->json();

    // Files present
    expect(Storage::disk('local')->exists($rel.'/manifest.json'))->toBeTrue();

    $files = collect(Storage::disk('local')->files($rel));
    $txt   = $files->filter(fn($p) => str_ends_with($p, '.txt'))->values();
    $png   = $files->filter(fn($p) => str_ends_with($p, '.png'))->values();

    expect($txt)->toHaveCount($json['total'])
        ->and($png->count())->toBe(0); // no PNGs expected

    // Reassemble from disk
    $joined = collect(range(1, (int)$json['total']))->map(function ($i) use ($rel, $json) {
        $p = "{$rel}/chunk_{$i}of{$json['total']}.txt";
        if (!Storage::disk('local')->exists($p)) {
            $cand = collect(Storage::disk('local')->files($rel))
                ->first(fn($x) => str_contains($x, "chunk_{$i}of"));
            $p = $cand ?? $p;
        }
        $line = Storage::disk('local')->get($p);
        return explode('|', $line, 5)[4] ?? '';
    })->implode('');

    $inflated = gzinflate(_b64uToBin($joined));
    $decoded  = json_decode($inflated, true);

    $expected = json_decode($dto->toJson(), true);
    expect(_norm($decoded))->toEqual(_norm($expected));

    // Show where they landed
    $abs = Storage::disk('local')->path($rel);
    echo "\n[Persisted] {$abs}\n";

    // Optional cleanup via env (defaults to KEEP)
    if (!env('KEEP_QR_EXPORTS', true)) {
        Storage::disk('local')->deleteDirectory($rel);
        echo "[Cleanup] Removed persisted files (KEEP_QR_EXPORTS=false)\n";
    }
})->skip();

/** Full payload with desired_chunks (no PNGs, just round-trip via HTTP response) */
it('HTTP full payload also round-trips with desired_chunks', function () {
    $precinct = Precinct::query()->firstOrFail();
    $dto = app(GenerateElectionReturn::class)->run($precinct);

    $resp = getJson(
        route('qr.er', ['code' => $dto->code]) .
        '?payload=full&desired_chunks=4&make_images=0'
    );
    $resp->assertOk();

    $json = $resp->json();
    expect($json['total'])->toBeGreaterThan(1)
        ->and($json)->toHaveKey('params.effective_max_chars_per_qr');

    $joined = collect($json['chunks'])
        ->sortBy('index')
        ->map(fn($c) => explode('|', $c['text'], 5)[4] ?? '')
        ->implode('');
    $inflated = gzinflate(_b64uToBin($joined));
    $decoded  = json_decode($inflated, true);

    $expected = json_decode($dto->toJson(), true);
    expect(_norm($decoded))->toEqual(_norm($expected));
})->skip();

/** Persistence with minimal payload and desired_chunks */
it('HTTP minimal+persist writes files and round-trips from disk', function () {
    // NOTE: no Storage::fake() here — we want real files!

    $precinct = Precinct::query()->firstOrFail();
    $dto = app(GenerateElectionReturn::class)->run($precinct);

    $suffix = uniqueDir('mini_desired4');
    $rel    = 'qr_exports/'.$dto->code.'/'.$suffix;

    $resp = getJson(
        route('qr.er', ['code' => $dto->code]) .
        '?payload=minimal&desired_chunks=4&make_images=1&persist=1&persist_dir=' . $suffix
    );
    $resp->assertOk();

    $json = $resp->json();

    // Files exist
    expect(Storage::disk('local')->exists($rel.'/manifest.json'))->toBeTrue()
        ->and(Storage::disk('local')->exists($rel.'/raw.json'))->toBeTrue()
        ->and(Storage::disk('local')->exists($rel.'/README.md'))->toBeTrue();

    // We should have exactly N .txt and N .png
    $files = collect(Storage::disk('local')->files($rel));
    $txt   = $files->filter(fn($p) => str_ends_with($p, '.txt'))->values();
    $png   = $files->filter(fn($p) => str_ends_with($p, '.png'))->values();
    expect($txt)->toHaveCount($json['total'])
        ->and($png->count())->toBe($json['total']);

    // Reassemble from .txt files
    $joined = collect(range(1, (int)$json['total']))->map(function ($i) use ($rel, $json) {
        $path = "{$rel}/chunk_{$i}of{$json['total']}.txt";
        if (!Storage::disk('local')->exists($path)) {
            $cand = collect(Storage::disk('local')->files($rel))
                ->first(fn($p) => str_contains($p, "chunk_{$i}of"));
            $path = $cand ?? $path;
        }
        $line = Storage::disk('local')->get($path);
        return explode('|', $line, 5)[4] ?? '';
    })->implode('');

    $inflated = gzinflate(_b64uToBin($joined));
    $decoded  = json_decode($inflated, true);

    $expected = _buildMinimalPayload($dto);
    expect(_norm($decoded))->toEqual(_norm($expected));

    // Show persisted location
    $abs = Storage::disk('local')->path($rel);
    echo "\n[Persisted] {$abs}\n";

    // Optional cleanup
    if (!env('KEEP_QR_EXPORTS', true)) {
        Storage::disk('local')->deleteDirectory($rel);
        echo "[Cleanup] Removed persisted files (KEEP_QR_EXPORTS=false)\n";
    }
})->skip();
