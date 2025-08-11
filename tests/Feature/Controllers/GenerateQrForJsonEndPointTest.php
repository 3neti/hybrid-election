<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\getJson;
use App\Actions\GenerateQrForJson;
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

    $inflated = gzinflate(GenerateQrForJson::b64urlDecode($payloadJoined));
    $decoded  = json_decode($inflated, true);

    expect($decoded)->toBeArray()
        ->and($decoded)->toHaveKeys(['id','code','precinct','tallies'])
        ->and($decoded['code'])->toBe('ERTEST001');
});

it('404s for an unknown ER code', function () {
    getJson(route('qr.er', ['code' => 'NOPE-123']))->assertNotFound();
});
