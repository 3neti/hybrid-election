<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\postJson;
use function Pest\Laravel\getJson;
use App\Models\ElectionReturn;
use App\Models\Precinct;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Minimal data: a precinct and an election return with a known code.
    $this->precinct = Precinct::factory()->create();

    // If your ElectionReturn factory auto-fills signatures, that's fine.
    $this->er = ElectionReturn::factory()->create([
        'precinct_id' => $this->precinct->id,
        'code'        => 'ERTEST001',
        'signatures'  => $this->precinct->electoral_inspectors->toArray()
    ]);
});

it('returns QR chunks (with PNG by default) for a valid ER code', function () {
    $res = getJson(route('qr.er', ['code' => 'ERTEST001']));

    $res->assertOk();

    $json = $res->json();

    expect($json)->toHaveKeys(['code', 'version', 'total', 'chunks'])
        ->and($json['code'])->toBe('ERTEST001')
        ->and($json['version'])->toBe('v1')
        ->and($json['total'])->toBeGreaterThanOrEqual(1)
        ->and($json['chunks'])->toBeArray()
        ->and($json['chunks'][0])->toHaveKeys(['index','text','png'])
        ->and($json['chunks'][0]['text'])->toStartWith('ER|v1|ERTEST001|1/')
        ->and($json['chunks'][0]['png'])->toStartWith('data:image/png;base64,');
});

it('can disable PNG generation via query param', function () {
    $res = getJson(route('qr.er', ['code' => 'ERTEST001']) . '?make_images=0');

    $res->assertOk();

    $json = $res->json();

    // Ensure every chunk has no 'png' key
    foreach ($json['chunks'] as $chunk) {
        expect($chunk)->not->toHaveKey('png');
    }
});

it('encodes a reassemble payload in the chunks', function () {
    $res = getJson(route('qr.er', ['code' => 'ERTEST001']) . '?make_images=0&max_chars_per_qr=800');

    $res->assertOk();

    $json = $res->json();
    $pieces = array_map(
        fn ($c) => explode('|', $c['text'], 5)[4] ?? '',
        $json['chunks']
    );

    $payloadJoined = implode('', $pieces);

    $inflated = gzinflate(b64urlDecode($payloadJoined));
    $decoded  = json_decode($inflated, true);

    expect($decoded)->toBeArray()
        ->and($decoded)->toHaveKeys(['id','code','precinct','tallies'])
        ->and($decoded['code'])->toBe('ERTEST001');
});

it('404s for an unknown ER code', function () {
    getJson(route('qr.er', ['code' => 'NOPE-123']))->assertNotFound();
});

/**
 * POST /api/qr/election-return → fromBody()
 * Builds chunks straight from a JSON payload (no DB).
 */
it('POST fromBody builds chunked QR from JSON and decodes back', function () {
    $payload = [
        'id'       => 'ER-POST-001',
        'code'     => 'ERPOST001',
        'precinct' => ['id' => 'P-POST', 'code' => 'CURRIMAO-001'],
        'tallies'  => [
            ['position_code' => 'PRESIDENT', 'candidate_code' => 'JB_oV', 'candidate_name' => 'Josh Brolin',    'count' => 24],
            ['position_code' => 'VP',        'candidate_code' => 'LS_Ti', 'candidate_name' => 'Liza Soberano', 'count' => 17],
        ],
    ];

    // No PNGs (faster tests), modest chunk size to force multiple chunks sometimes
    $res = postJson(route('qr.er.from_json'), [
        'json'            => $payload,
        'code'            => $payload['code'],
        'make_images'     => false,
        'max_chars_per_qr'=> 800,
    ])->assertOk()->json();

    expect($res['code'])->toBe('ERPOST001')
        ->and($res['total'])->toBeGreaterThanOrEqual(1)
        ->and($res['chunks'])->toHaveCount($res['total']);

    // Reassemble like a scanner
    $joined = collect($res['chunks'])
        ->map(fn($c) => explode('|', $c['text'], 5)[4] ?? '')
        ->implode('');

    $inflated = gzinflate(b64urlDecode($joined));
    $decoded  = json_decode($inflated, true);

    expect($decoded)->toMatchArray($payload);
});

it('POST fromBody respects desired_chunks (computes effective chunk size) and can force single', function () {
    $payload = [
        'id'       => 'ER-POST-002',
        'code'     => 'ERPOST002',
        'precinct' => ['id' => 'P-POST', 'code' => 'CURRIMAO-001'],
        // make it a bit larger so desired_chunks has an effect
        'tallies'  => array_map(function ($i) {
            return [
                'position_code'  => 'POS-' . str_pad((string)(($i % 10)+1), 2, '0', STR_PAD_LEFT),
                'candidate_code' => 'C' . str_pad((string)$i, 3, '0', STR_PAD_LEFT),
                'candidate_name' => "Candidate {$i}",
                'count'          => ($i % 51) + 1,
            ];
        }, range(1, 120)),
    ];

    // A) Ask for ~8 chunks, no PNGs
    $a = postJson(route('qr.er.from_json'), [
        'json'            => $payload,
        'code'            => $payload['code'],
        'make_images'     => false,
        'desired_chunks'  => 8,
        // max_chars_per_qr omitted → will be computed & clamped server-side
    ])->assertOk()->json();

    expect($a['total'])->toBeGreaterThanOrEqual(2) // likely multi-chunk
    ->and($a['params'])->toHaveKeys(['effective_max_chars_per_qr','desired_chunks']);

    // B) Force single
    $b = postJson(route('qr.er.from_json'), [
        'json'        => $payload,
        'code'        => $payload['code'],
        'make_images' => false,
        'single'      => true,
    ])->assertOk()->json();

    expect($b['total'])->toBe(1);

    // Quick round-trip check on (A)
    $joined = collect($a['chunks'])->map(fn($c) => explode('|', $c['text'], 5)[4] ?? '')->implode('');
    $inflated = gzinflate(b64urlDecode($joined));
    $decoded  = json_decode($inflated, true);
    expect($decoded)->toMatchArray($payload);
});
