<?php

use App\Actions\GenerateQrForJson;

it('encodes json to chunked QR payload(s) and decodes back', function () {
    // Sample payload (you can scale this up later)
    $payload = [
        'id' => 'ER-TEST-001',
        'code' => 'ERTEST001',
        'precinct' => ['id' => 'P-1', 'code' => 'CURRIMAO-001'],
        'tallies' => [
            ['position_code' => 'PRESIDENT', 'candidate_code' => 'JB_oV', 'candidate_name' => 'Josh Brolin', 'count' => 24],
            ['position_code' => 'VP', 'candidate_code' => 'LS_Ti', 'candidate_name' => 'Liza Soberano', 'count' => 17],
        ],
    ];

    $res = app(GenerateQrForJson::class)->run($payload, code: 'ERTEST001', makeImages: true, maxCharsPerQr: 800);

    expect($res['code'])->toBe('ERTEST001')
        ->and($res['total'])->toBeGreaterThanOrEqual(1)
        ->and($res['chunks'])->not->toBeEmpty();

    // Each chunk is scan-ready text & has a PNG data URI
    $first = $res['chunks'][0];
    expect($first['text'])->toStartWith('ER|v1|ERTEST001|')
        ->and($first)->toHaveKey('png')
        ->and($first['png'])->toStartWith('data:image/png;base64,');

    // Reassemble & decode like a scanner would
    $joinedPayload = collect($res['chunks'])
        ->map(fn ($c) => explode('|', $c['text'], 5)[4]) // take the <payload> part
        ->implode('');

    $inflated = gzinflate(GenerateQrForJson::b64urlDecode($joinedPayload));
    $decoded  = json_decode($inflated, true);

    expect($decoded)->toMatchArray($payload);
});

it('encodes & decodes a large election return (25 positions, 200 candidates) via chunked QR', function () {
    // Build 25 positions and 200 tallies (≈8 per position)
    $positions = [];
    for ($p = 1; $p <= 25; $p++) {
        $positions[] = sprintf('POS-%02d', $p);
    }

    $tallies = [];
    for ($i = 0; $i < 200; $i++) {
        $posCode = $positions[$i % count($positions)];
        $candCode = sprintf('C%03d_%s', $i + 1, substr(md5((string) $i), 0, 2)); // stable-ish short code
        $tallies[] = [
            'position_code'  => $posCode,
            'candidate_code' => $candCode,
            'candidate_name' => "Candidate {$i}",
            'count'          => ($i % 57) + 1, // some varying counts
        ];
    }

    $payload = [
        'id'       => 'ER-LARGE-TEST-001',
        'code'     => 'ERLARGE001',
        'precinct' => ['id' => 'P-001', 'code' => 'CURRIMAO-001'],
        'tallies'  => $tallies,
    ];

    // Generate QR chunks (no images for speed)
    $res = app(GenerateQrForJson::class)->run(
        $payload,
        code: 'ERLARGE001',
        makeImages: false,
        maxCharsPerQr: 800 // tweak if you want more / fewer chunks
    );

    // Basic structure checks
    expect($res['code'])->toBe('ERLARGE001')
        ->and($res['total'])->toBeGreaterThanOrEqual(1)
        ->and($res['chunks'])->toHaveCount($res['total']);

    // Should likely require multiple chunks for this size
    expect($res['total'])->toBeGreaterThan(1);

    // Reassemble payload exactly like a scanner would
    $joinedPayload = collect($res['chunks'])
        ->map(function ($c) {
            // Header format: ER|v1|<code>|<index>/<total>|<payload>
            $parts = explode('|', $c['text'], 5);
            return $parts[4] ?? '';
        })
        ->implode('');

    $inflated = gzinflate(GenerateQrForJson::b64urlDecode($joinedPayload));
    $decoded  = json_decode($inflated, true);

    // Round-trip integrity
    expect($decoded)->toMatchArray($payload);

    // Sanity: make sure we kept all tallies
    expect($decoded['tallies'])->toHaveCount(200);

    // Optional: keep chunk count under a practical ceiling (adjust as needed)
    expect($res['total'])->toBeLessThan(100);
});
