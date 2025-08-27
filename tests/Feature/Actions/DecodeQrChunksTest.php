<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{getJson, postJson};
use Illuminate\Support\Facades\Storage;
use App\Data\ElectionReturnData;
use App\Models\Precinct;
use App\Actions\{
    GenerateElectionReturn,
    GenerateQrForJson,
    DecodeQrChunks
};

uses(RefreshDatabase::class);

beforeEach(function () {
    // Your seeders should create at least 1 precinct + positions + candidates + ballots/ER
    $this->artisan('db:seed');
});

/**
 * ─────────────────────────────────────────────────────────────────────────────
 * 1) UNIT: DecodeQrChunks action can decode multi-chunk payload
 * ─────────────────────────────────────────────────────────────────────────────
 */
it('decodes chunks via action (full payload)', function () {
    $precinct = Precinct::firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);
    $original = json_decode($erDto->toJson(), true);

    // Build multi-chunk (no PNGs needed)
    $qr = app(GenerateQrForJson::class)->run(
        json: $original,
        code: $erDto->code,
        makeImages: false,
        maxCharsPerQr: 500,
        forceSingle: false
    );

    $lines = collect($qr['chunks'])->sortBy('index')->pluck('text')->values()->all();

    $out = app(DecodeQrChunks::class)->run($lines, persist: false);

    expect($out['missing_indices'])->toBe([])
        ->and($out['json'])->toBeArray()
        ->and(normalizeArray($out['json']))->toEqual(normalizeArray($original));
});

/**
 * ─────────────────────────────────────────────────────────────────────────────
 * 2) HTTP: Decode only—POST /qr/decode
 * ─────────────────────────────────────────────────────────────────────────────
 */
it('decodes chunks via HTTP endpoint', function () {
    $precinct = Precinct::firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);
    $original = json_decode($erDto->toJson(), true);

    // Generate via action to get well-formed lines
    $qr = app(GenerateQrForJson::class)->run(
        json: $original,
        code: $erDto->code,
        makeImages: false,
        maxCharsPerQr: 500,
        forceSingle: false
    );

    $lines = collect($qr['chunks'])->sortBy('index')->pluck('text')->values()->all();

    $resp = postJson(route('qr.decode'), ['chunks' => $lines])->assertOk();
    $json = $resp->json();

    expect($json['missing_indices'])->toBe([])
        ->and($json['json'])->toBeArray()
        ->and(normalizeArray($json['json']))->toEqual(normalizeArray($original));
});

/**
 * ─────────────────────────────────────────────────────────────────────────────
 * 3) Full round-trip over HTTP: GET qr.er → POST qr.decode
 * ─────────────────────────────────────────────────────────────────────────────
 */
it('round-trips via HTTP (generator → decoder)', function () {
    $precinct = Precinct::firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);
    $expected = json_decode($erDto->toJson(), true);

    // Generate via HTTP (no PNGs)
    $gen = getJson(route('qr.er', ['code' => $erDto->code]) . '?payload=full&make_images=0&max_chars_per_qr=500')
        ->assertOk()
        ->json();

    $lines = collect($gen['chunks'])->sortBy('index')->pluck('text')->values()->all();

    // Decode via HTTP
    $dec = postJson(route('qr.decode'), ['chunks' => $lines])->assertOk()->json();

    expect($dec['missing_indices'])->toBe([])
        ->and(normalizeArray($dec['json']))->toEqual(normalizeArray($expected));
});

/**
 * ─────────────────────────────────────────────────────────────────────────────
 * 4) Edge case: Missing chunk → reports missing_indices, no JSON
 * ─────────────────────────────────────────────────────────────────────────────
 */
it('reports missing indices when a chunk is absent', function () {
    $precinct = Precinct::firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);
    $original = json_decode($erDto->toJson(), true);

    $qr = app(GenerateQrForJson::class)->run(
        json: $original,
        code: $erDto->code,
        makeImages: false,
        maxCharsPerQr: 500,
        forceSingle: false
    );

    // Remove one chunk intentionally
//    $lines = collect($qr['chunks'])
//        ->sortBy('index')
//        ->reject(fn ($c) => $c['index'] === 2) // remove #2
//        ->pluck('text')
//        ->values()
//        ->all();
    $lines = collect($qr['chunks'])
        ->sortBy('index')
        ->reject(function ($c) {
            // header is "...|i/N|payload"
            $idxTot = explode('|', $c['text'], 5)[3] ?? '';
            $i = (int) explode('/', $idxTot, 2)[0];
            return $i === 2; // drop header index 2
        })
        ->pluck('text')
        ->values()
        ->all();

    $out = app(DecodeQrChunks::class)->run($lines, persist: false);

    expect($out['json'])->toBeNull()
        ->and($out['missing_indices'])->toContain(2)
        ->and($out['received_indices'])->not()->toContain(2);
});

/**
 * ─────────────────────────────────────────────────────────────────────────────
 * 5) Edge case: Mismatched code/total → 422
 * ─────────────────────────────────────────────────────────────────────────────
 */
it('fails with 422 on mismatched code/total', function () {
    $precinct = Precinct::firstOrFail();
    /** @var ElectionReturnData $erDto */
    $erDto = app(GenerateElectionReturn::class)->run($precinct);
    $original = json_decode($erDto->toJson(), true);

    $qr = app(GenerateQrForJson::class)->run(
        json: $original,
        code: $erDto->code,
        makeImages: false,
        maxCharsPerQr: 500,
        forceSingle: false
    );

    $lines = collect($qr['chunks'])->sortBy('index')->pluck('text')->values()->all();

    // Tamper: change the code in one line and the total in another
    $tampered = $lines;
    // change code on first line
    $tampered[0] = preg_replace('/^ER\|v1\|[^|]+/', 'ER|v1|DIFFCODE', $tampered[0], 1);
    // change total in second line (e.g. 1/99 instead of 1/N)
    $tampered[1] = preg_replace('/\|\d+\/\d+\|/', '|2/99|', $tampered[1], 1);

    // Action should throw a 422 abort
    $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
    app(DecodeQrChunks::class)->run($tampered, persist: false);
});
