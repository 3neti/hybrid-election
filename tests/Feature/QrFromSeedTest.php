<?php

use App\Actions\{GenerateElectionReturn, GenerateQrForJson};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
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
