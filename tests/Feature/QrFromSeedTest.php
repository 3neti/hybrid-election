<?php

use App\Actions\{GenerateElectionReturn, GenerateQrForJson};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{Artisan, Storage};
use function Pest\Laravel\getJson;
use App\Data\ElectionReturnData;
use App\Models\{Precinct};

/** ---------- helpers ---------- */
function b64urlDecode(string $txt): string {
    $pad = strlen($txt) % 4;
    if ($pad) $txt .= str_repeat('=', 4 - $pad);
    return base64_decode(strtr($txt, '-_', '+/')) ?: '';
}

/** Use Spatie Data JSON serialization so `with()` (last_ballot) is applied */
function dtoJsonArray(\Spatie\LaravelData\Data $dto): array {
    return json_decode($dto->toJson(), true);
}

/**
 * Make arrays comparable by:
 * - removing volatile keys if needed
 * - sorting associative arrays by keys (recursively)
 * NOTE: keeps indexed arrays order as-is (important for tallies order).
 */
function normalizeArray(mixed $value): mixed {
    if (!is_array($value)) return $value;

    // If needed, ignore volatile timestamps:
    // unset($value['created_at'], $value['updated_at']);

    foreach ($value as $k => $v) {
        $value[$k] = normalizeArray($v);
    }

    $isAssoc = array_keys($value) !== range(0, count($value) - 1);
    if ($isAssoc) {
        ksort($value);
    }

    return $value;
}

uses(RefreshDatabase::class);

beforeEach(function () {
    Artisan::call('db:seed');
});

it('single-QR: decodes and equals the ER JSON', function () {
    $precinct = Precinct::query()->firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);

    $original = dtoJsonArray($erDto);

    $qr = app(GenerateQrForJson::class)->run(
        json: $original,
        code: $erDto->code,
        makeImages: false,
        maxCharsPerQr: 999999,
        forceSingle: true
    );

    expect($qr['total'])->toBe(1);

    $line = $qr['chunks'][0]['text']; // ER|v1|CODE|1/1|<payload>
    $payload = explode('|', $line, 5)[4] ?? '';
    $inflated = gzinflate(b64urlDecode($payload));
    $decoded  = json_decode($inflated, true);

    expect(normalizeArray($decoded))
        ->toEqual(normalizeArray($original));
});

it('multi-QR: decodes and equals the ER JSON', function () {
    $precinct = Precinct::query()->firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);

    $original = dtoJsonArray($erDto);

    $qr = app(GenerateQrForJson::class)->run(
        json: $original,
        code: $erDto->code,
        makeImages: false,
        maxCharsPerQr: 500, // force multiple chunks
        forceSingle: false
    );

    expect($qr['total'])->toBeGreaterThan(1);

    $joined = collect($qr['chunks'])
        ->sortBy('index')
        ->map(fn ($c) => explode('|', $c['text'], 5)[4] ?? '')
        ->implode('');

    $inflated = gzinflate(b64urlDecode($joined));
    $decoded  = json_decode($inflated, true);

    expect(normalizeArray($decoded))
        ->toEqual(normalizeArray($original));
});

it('HTTP single-QR endpoint: decodes and equals the ER JSON', function () {
    $precinct = Precinct::query()->firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);

    $resp = getJson(route('qr.er', ['code' => $erDto->code]) . '?single=1&make_images=0');
    $resp->assertOk();

    $json = $resp->json();
    expect($json['total'])->toBe(1);

    $line = $json['chunks'][0]['text'];
    $payload = explode('|', $line, 5)[4] ?? '';
    $inflated = gzinflate(b64urlDecode($payload));
    $decoded  = json_decode($inflated, true);

    expect(normalizeArray($decoded))
        ->toEqual(normalizeArray(dtoJsonArray($erDto)));
});

it('HTTP multi-QR endpoint: decodes and equals the ER JSON', function () {
    $precinct = Precinct::query()->firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);

    $resp = getJson(route('qr.er', ['code' => $erDto->code]) . '?make_images=0&max_chars_per_qr=500');
    $resp->assertOk();

    $json = $resp->json();
    expect($json['total'])->toBeGreaterThan(1);

    $joined = collect($json['chunks'])
        ->sortBy('index')
        ->map(fn ($c) => explode('|', $c['text'], 5)[4] ?? '')
        ->implode('');

    $inflated = gzinflate(b64urlDecode($joined));
    $decoded  = json_decode($inflated, true);

    expect(normalizeArray($decoded))
        ->toEqual(normalizeArray(dtoJsonArray($erDto)));
});

/** ───────────────────────── persist tests ───────────────────────── */

it('HTTP multi-QR endpoint persists chunks & PNGs and files round-trip', function () {
    $precinct = Precinct::query()->firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);

    // Force multiple chunks, persist them, and include PNGs
    $dirSuffix = 'multi_test_' . now()->format('Ymd_His');
    $resp = getJson(
        route('qr.er', ['code' => $erDto->code])
        . '?make_images=1&max_chars_per_qr=500&persist=1&persist_dir=' . $dirSuffix
    );
    $resp->assertOk();

    $json = $resp->json();
    expect($json['total'])->toBeGreaterThan(1)
        ->and($json)->toHaveKey('persisted_to');

    // Compute the relative storage dir we expect
    $relDir = 'qr_exports/'.$erDto->code.'/'.$dirSuffix;

    // Files should exist
    expect(Storage::disk('local')->exists($relDir . '/manifest.json'))->toBeTrue();

    // Find chunk text files and PNGs
    $files = collect(Storage::disk('local')->files($relDir));
    $txt   = $files->filter(fn($p) => str_ends_with($p, '.txt'))->values();
    $png   = $files->filter(fn($p) => str_ends_with($p, '.png'))->values();

    expect($txt)->toHaveCount($json['total'])
        ->and($png->count())->toBe($json['total']); // PNGs present for all chunks

    // Reassemble from persisted text files (sorted by index)
    // Reassemble from persisted text files (sorted by index)
    $joined = collect(range(1, (int) $json['total']))->map(function ($i) use ($relDir, $json) {
        // Expected filename (our action names files like chunk_<index>of<total>.txt)
        $expected = "{$relDir}/chunk_{$i}of{$json['total']}.txt";

        // Read the line (fallback to pattern search if filename ever changes)
        if (Storage::disk('local')->exists($expected)) {
            $line = Storage::disk('local')->get($expected);
        } else {
            $cand = collect(Storage::disk('local')->files($relDir))
                ->first(fn($p) => str_contains($p, "chunk_{$i}of"));
            $line = $cand ? Storage::disk('local')->get($cand) : '';
        }

        // Extract <payload> from ER|v1|CODE|i/total|<payload>
        return explode('|', $line, 5)[4] ?? '';
    })->implode('');
//    $joined = collect(range(1, (int) $json['total']))->map(function ($i) use ($relDir) {
//        $path = "{$relDir}/chunk_{$i}of{$GLOBALS['json']['total']}.txt"; // use $json in outer scope
//        // Fallback: scan for the matching filename if needed
//        if (!Storage::disk('local')->exists($path)) {
//            $candidates = collect(Storage::disk('local')->files($relDir))
//                ->first(fn($p) => str_contains($p, "chunk_{$i}of"));
//            $path = $candidates ?? $path;
//        }
//        return explode('|', Storage::disk('local')->get($path), 5)[4] ?? '';
//    })->implode('');

    $inflated = gzinflate(b64urlDecode($joined));
    $decoded  = json_decode($inflated, true);

    expect(normalizeArray($decoded))
        ->toEqual(normalizeArray(dtoJsonArray($erDto)));
});

it('HTTP single-QR endpoint persists text (no PNG) and file round-trips', function () {
    $precinct = Precinct::query()->firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);

    // Force single, persist, BUT skip PNG to avoid "data too big" for some payloads
    $dirSuffix = 'single_test_' . now()->format('Ymd_His');
    $resp = getJson(
        route('qr.er', ['code' => $erDto->code])
        . '?single=1&make_images=0&persist=1&persist_dir=' . $dirSuffix
    );
    $resp->assertOk();

    $json = $resp->json();
    expect($json['total'])->toBe(1)
        ->and($json)->toHaveKey('persisted_to');

    $relDir = 'qr_exports/'.$erDto->code.'/'.$dirSuffix;

    // One text chunk, manifest present
    expect(Storage::disk('local')->exists($relDir . '/manifest.json'))->toBeTrue();

    $files = collect(Storage::disk('local')->files($relDir));
    $txt   = $files->filter(fn($p) => str_ends_with($p, '.txt'))->values();
    $png   = $files->filter(fn($p) => str_ends_with($p, '.png'))->values();

    expect($txt)->toHaveCount(1)
        ->and($png->count())->toBe(0); // no PNG expected

    // Round-trip from text file
    $line     = Storage::disk('local')->get($txt[0]);
    $payload  = explode('|', $line, 5)[4] ?? '';
    $inflated = gzinflate(b64urlDecode($payload));
    $decoded  = json_decode($inflated, true);

    expect(normalizeArray($decoded))
        ->toEqual(normalizeArray(dtoJsonArray($erDto)));
});
