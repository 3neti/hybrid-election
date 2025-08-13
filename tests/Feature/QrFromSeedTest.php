<?php

use App\Actions\{GenerateElectionReturn, GenerateQrForJson};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{Artisan, Storage};
use function Pest\Laravel\getJson;
use App\Data\ElectionReturnData;
use App\Models\Precinct;

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

/** Build the same minimal payload the controller produces when payload=minimal */
function minimalFromDto(ElectionReturnData $dto): array {
    // Tallies might be DataCollection or array; normalize to array
    $talliesRaw = is_array($dto->tallies) ? $dto->tallies : $dto->tallies->toArray();

    $tallies = array_map(
        fn ($t) => [
            'position_code'  => is_array($t) ? ($t['position_code'] ?? null) : $t->position_code,
            'candidate_code' => is_array($t) ? ($t['candidate_code'] ?? null) : $t->candidate_code,
            'candidate_name' => is_array($t) ? ($t['candidate_name'] ?? null) : $t->candidate_name,
            'count'          => is_array($t) ? ($t['count'] ?? null) : $t->count,
        ],
        $talliesRaw
    );

    // Precinct core
    $precinct = [
        'id'   => $dto->precinct->id,
        'code' => $dto->precinct->code,
    ];

    // Precinct extras (match controller: include when available)
    $locationName = $dto->precinct->location_name ?? null;
    $lat          = $dto->precinct->latitude ?? null;
    $lon          = $dto->precinct->longitude ?? null;

    if ($locationName !== null && $locationName !== '') {
        $precinct['location_name'] = $locationName;
    }
    if ($lat !== null)  $precinct['latitude']  = $lat;
    if ($lon !== null)  $precinct['longitude'] = $lon;

    // Electoral inspectors (subset of fields)
    $eiRaw = $dto->precinct->electoral_inspectors ?? [];
    // Support both array and collection-like
    if (!is_array($eiRaw) && method_exists($eiRaw, 'toArray')) {
        $eiRaw = $eiRaw->toArray();
    }
    $inspectors = array_values(array_map(function ($ei) {
        // $ei may be array or object
        $id   = is_array($ei) ? ($ei['id']   ?? null) : ($ei->id   ?? null);
        $name = is_array($ei) ? ($ei['name'] ?? null) : ($ei->name ?? null);
        $role = is_array($ei) ? ($ei['role'] ?? null) : ($ei->role ?? null);

        $row = ['id' => $id, 'name' => $name];
        if ($role !== null && $role !== '') $row['role'] = $role;
        return $row;
    }, is_array($eiRaw) ? $eiRaw : []));
    if (count($inspectors) > 0) {
        $precinct['electoral_inspectors'] = $inspectors;
    }

    $result = [
        'id'       => $dto->id,
        'code'     => $dto->code,
        'precinct' => $precinct,
        'tallies'  => $tallies,
    ];

    // Signatures (lightweight metadata only, no blobs)
    $sigRaw = $dto->signatures ?? [];
    if (!is_array($sigRaw) && method_exists($sigRaw, 'toArray')) {
        $sigRaw = $sigRaw->toArray();
    }
    $signatures = array_values(array_map(function ($s) {
        $id   = is_array($s) ? ($s['id']        ?? null) : ($s->id        ?? null);
        $name = is_array($s) ? ($s['name']      ?? null) : ($s->name      ?? null);
        $role = is_array($s) ? ($s['role']      ?? null) : ($s->role      ?? null);
        $when = is_array($s) ? ($s['signed_at'] ?? null) : ($s->signed_at ?? null);

        $row = [];
        if ($id   !== null) $row['id']   = $id;
        if ($name !== null) $row['name'] = $name;
        if ($role !== null && $role !== '') $row['role'] = $role;
        if ($when !== null && $when !== '') $row['signed_at'] = $when;
        return $row;
    }, is_array($sigRaw) ? $sigRaw : []));
    if (count($signatures) > 0) {
        $result['signatures'] = $signatures;
    }

    return $result;
}

/**
 * Make arrays comparable by:
 * - recursively normalize values
 * - sort associative arrays by keys
 * (Keeps indexed arrays order intact.)
 */
function normalizeArray(mixed $value): mixed {
    if (!is_array($value)) return $value;

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

/** ───────────────────────── action (full payload) ───────────────────────── */

it('single-QR (action/full): decodes and equals the ER JSON', function () {
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

it('multi-QR (action/full): decodes and equals the ER JSON', function () {
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

/** ───────────────────────── HTTP (full payload) ───────────────────────── */

it('HTTP single-QR (full): decodes and equals the ER JSON', function () {
    $precinct = Precinct::query()->firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);

    // Explicitly payload=full to match dtoJsonArray
    $resp = getJson(route('qr.er', ['code' => $erDto->code]) . '?payload=full&single=1&make_images=0');
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

it('HTTP multi-QR (full): decodes and equals the ER JSON', function () {
    $precinct = Precinct::query()->firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);

    $resp = getJson(route('qr.er', ['code' => $erDto->code]) . '?payload=full&make_images=0&max_chars_per_qr=500');
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

/** ───────────────────────── HTTP (minimal payload) ───────────────────────── */

it('HTTP multi-QR (minimal): decodes and equals the minimal JSON', function () {
    $precinct = Precinct::query()->firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);

    // Minimal payload should reduce chunk count substantially
    $resp = getJson(route('qr.er', ['code' => $erDto->code]) . '?payload=minimal&make_images=0&max_chars_per_qr=1200');
    $resp->assertOk();

    $json = $resp->json();
    expect($json['total'])->toBeGreaterThanOrEqual(1);

    $joined = collect($json['chunks'])
        ->sortBy('index')
        ->map(fn ($c) => explode('|', $c['text'], 5)[4] ?? '')
        ->implode('');

    $inflated = gzinflate(b64urlDecode($joined));
    $decoded  = json_decode($inflated, true);

    // Compare against our local minimal shape
    expect(normalizeArray($decoded))
        ->toEqual(normalizeArray(minimalFromDto($erDto)));
});

/** ───────────────────────── persist tests ───────────────────────── */

it('HTTP multi-QR (minimal) persists chunks & PNGs and files round-trip', function () {
    $precinct = Precinct::query()->firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);

    // Minimal + PNGs + persist; chunk size moderate for reasonable count
    $dirSuffix = 'multi_min_' . now()->format('Ymd_His');
    $resp = getJson(
        route('qr.er', ['code' => $erDto->code])
        . '?payload=minimal&make_images=1&max_chars_per_qr=1200&persist=1&persist_dir=' . $dirSuffix
    );
    $resp->assertOk();

    $json = $resp->json();
    expect($json['total'])->toBeGreaterThanOrEqual(1)
        ->and($json)->toHaveKey('persisted_to');

    $relDir = 'qr_exports/'.$erDto->code.'/'.$dirSuffix;

    // Required files present
    expect(Storage::disk('local')->exists($relDir . '/manifest.json'))->toBeTrue()
        ->and(Storage::disk('local')->exists($relDir . '/raw.json'))->toBeTrue()
        ->and(Storage::disk('local')->exists($relDir . '/README.md'))->toBeTrue();

    // Find chunks
    $files = collect(Storage::disk('local')->files($relDir));
    $txt   = $files->filter(fn($p) => str_ends_with($p, '.txt'))->values();
    $png   = $files->filter(fn($p) => str_ends_with($p, '.png'))->values();

    expect($txt)->toHaveCount($json['total'])
        ->and($png->count())->toBe($json['total']); // PNGs for every chunk

    // Reassemble from persisted text (by index)
    $joined = collect(range(1, (int) $json['total']))->map(function ($i) use ($relDir, $json) {
        $expected = "{$relDir}/chunk_{$i}of{$json['total']}.txt";
        if (Storage::disk('local')->exists($expected)) {
            $line = Storage::disk('local')->get($expected);
        } else {
            $cand = collect(Storage::disk('local')->files($relDir))
                ->first(fn($p) => str_contains($p, "chunk_{$i}of"));
            $line = $cand ? Storage::disk('local')->get($cand) : '';
        }
        return explode('|', $line, 5)[4] ?? '';
    })->implode('');

    $inflated = gzinflate(b64urlDecode($joined));
    $decoded  = json_decode($inflated, true);

    expect(normalizeArray($decoded))
        ->toEqual(normalizeArray(minimalFromDto($erDto)));
});

it('HTTP single-QR (minimal) persists text (no PNG) and file round-trips', function () {
    $precinct = Precinct::query()->firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);

    // Force single, persist, skip PNGs; minimal reduces risk of overly long QR text
    $dirSuffix = 'single_min_' . now()->format('Ymd_His');
    $resp = getJson(
        route('qr.er', ['code' => $erDto->code])
        . '?payload=minimal&single=1&make_images=0&persist=1&persist_dir=' . $dirSuffix
    );
    $resp->assertOk();

    $json = $resp->json();
    expect($json['total'])->toBe(1)
        ->and($json)->toHaveKey('persisted_to');

    $relDir = 'qr_exports/'.$erDto->code.'/'.$dirSuffix;

    // Exactly one text chunk, no PNGs
    $files = collect(Storage::disk('local')->files($relDir));
    $txt   = $files->filter(fn($p) => str_ends_with($p, '.txt'))->values();
    $png   = $files->filter(fn($p) => str_ends_with($p, '.png'))->values();

    expect(Storage::disk('local')->exists($relDir . '/manifest.json'))->toBeTrue()
        ->and(Storage::disk('local')->exists($relDir . '/raw.json'))->toBeTrue()
        ->and(Storage::disk('local')->exists($relDir . '/README.md'))->toBeTrue()
        ->and($txt)->toHaveCount(1)
        ->and($png->count())->toBe(0);

    // Round-trip from text file
    $line     = Storage::disk('local')->get($txt[0]);
    $payload  = explode('|', $line, 5)[4] ?? '';
    $inflated = gzinflate(b64urlDecode($payload));
    $decoded  = json_decode($inflated, true);

    expect(normalizeArray($decoded))
        ->toEqual(normalizeArray(minimalFromDto($erDto)));
});
