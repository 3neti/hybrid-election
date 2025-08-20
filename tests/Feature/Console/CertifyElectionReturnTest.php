<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{Artisan, File};
use App\Models\{Precinct, ElectionReturn};
use App\Enums\ElectoralInspectorRole;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Minimal precinct with known inspectors
    $this->precinct = Precinct::create([
        'id' => (string) Str::uuid(),
        'code' => 'CURRIMAO-001',
        'location_name' => 'Currimao National High School',
        'latitude' => 17.993217,
        'longitude' => 120.488902,
        'electoral_inspectors' => [
            ['id' => 'uuid-juan',  'name' => 'Juan dela Cruz', 'role' => ElectoralInspectorRole::CHAIRPERSON],
            ['id' => 'uuid-maria', 'name' => 'Maria Santos',   'role' => ElectoralInspectorRole::MEMBER],
            ['id' => 'uuid-pedro', 'name' => 'Pedro Reyes',    'role' => ElectoralInspectorRole::MEMBER],
        ],
    ]);

    // Fresh ER with known code (and a variant without "ER-" prefix)
    $this->er = ElectionReturn::create([
        'id'         => (string) Str::uuid(),
        'code'       => 'DNPT6VLVFF3N',
        'precinct_id'=> $this->precinct->id,
        'tallies'    => [],    // not relevant for signing
        'signatures' => [],    // start clean
    ]);

    $this->sigDir = storage_path('signatures');
    if (!is_dir($this->sigDir)) {
        mkdir($this->sigDir, 0777, true);
    }
});

afterEach(function () {
    if (is_dir($this->sigDir)) {
        collect(File::allFiles($this->sigDir))->each(fn($f) => @unlink($f->getPathname()));
        @rmdir($this->sigDir);
    }
});

/** --- helpers --- */
function getSignaturesFor(string $erCode): array {
    $erCode = $erCode !== '' && str_starts_with($erCode, 'ER-')
        ? substr($erCode, 3)
        : $erCode;

    /** @var \App\Models\ElectionReturn $er */
    $er = \App\Models\ElectionReturn::where('code', $erCode)->firstOrFail();
    $sigs = $er->signatures ?? [];

    // Normalize Spatie DataCollection / Laravel Collection / DTOs → plain arrays
    if ($sigs instanceof \Spatie\LaravelData\DataCollection) {
        return array_values($sigs->toArray());
    }
    if ($sigs instanceof \Illuminate\Support\Collection) {
        return array_values($sigs->toArray());
    }
    if (is_object($sigs) && method_exists($sigs, 'toArray')) {
        return array_values($sigs->toArray());
    }
    if (is_array($sigs)) {
        return array_values($sigs);
    }

    return [];
}

/** ───────────────────────── tests ───────────────────────── */

it('accepts a single inline key=value signature', function () {
    $exit = Artisan::call('certify-er', [
        '--er' => 'ER-DNPT6VLVFF3N',
        'signatures' => ['id=uuid-juan,signature=ABC123'],
    ]);
    $out = Artisan::output();

    expect($exit)->toBe(0);
    $sigs = getSignaturesFor('ER-DNPT6VLVFF3N');
    expect($sigs)->toHaveCount(1);
    expect($sigs[0]['id'])->toBe('uuid-juan');
    expect($sigs[0]['signature'])->toBe('ABC123');
    // Optional: check role/name are filled from roster
    expect($sigs[0]['name'])->toBe('Juan dela Cruz');
});

it('accepts multiple pipe arguments "id|signature"', function () {
    $exit = Artisan::call('certify-er', [
        '--er' => 'DNPT6VLVFF3N', // allow without ER- prefix
        'signatures' => [
            'uuid-juan|ABC123',
            'uuid-maria|DEF456',
        ],
    ]);
    $out = Artisan::output();

    expect($exit)->toBe(0);

    $sigs = getSignaturesFor('ER-DNPT6VLVFF3N');
    expect($sigs)->toHaveCount(2);

// 1) IDs present (order-insensitive)
    $ids = collect($sigs)->pluck('id')->all();
    expect($ids)->toEqualCanonicalizing(['uuid-juan', 'uuid-maria']);

// 2) Each signature row has the expected keys
    expect($sigs)->each->toHaveKeys(['id', 'name', 'role', 'signature', 'signed_at']);

// 3) Quick content spot-checks via id-indexing
    $byId = collect($sigs)->keyBy('id');

    expect($byId['uuid-juan']['name'])->toBe('Juan dela Cruz');
    expect($byId['uuid-juan']['role'])->toBe('chairperson');
    expect($byId['uuid-juan']['signature'])->toBe('ABC123');

    expect($byId['uuid-maria']['name'])->toBe('Maria Santos');
    expect($byId['uuid-maria']['role'])->toBe('member');
    expect($byId['uuid-maria']['signature'])->toBe('DEF456');

// (Optional) if you want to sanity-check the timestamp format without pinning exact value:
    expect($byId['uuid-juan']['signed_at'])->toMatch('/^\d{4}-\d{2}-\d{2}T/');
    expect($byId['uuid-maria']['signed_at'])->toMatch('/^\d{4}-\d{2}-\d{2}T/');
});

it('ingests signatures from a file (--file)', function () {
    $file = $this->sigDir . '/demo.txt';
    File::put($file, implode(PHP_EOL, [
            'id=uuid-juan,signature=SIG-JUAN-123',
            'id=uuid-maria,signature=SIG-MARIA-456',
            'id=uuid-pedro,signature=SIG-PEDRO-789',
        ]) . PHP_EOL);

    $exit = Artisan::call('certify-er', [
        '--er'   => 'DNPT6VLVFF3N',              // allow without ER- prefix
        '--file' => 'storage/signatures/demo.txt',
    ]);
    $out = Artisan::output();

    expect($exit)->toBe(0);

    $sigs = getSignaturesFor('ER-DNPT6VLVFF3N');

// 1) Count and id set (order-insensitive)
    expect($sigs)->toHaveCount(3);
    $ids = collect($sigs)->pluck('id')->all();
    expect($ids)->toEqualCanonicalizing(['uuid-juan','uuid-maria','uuid-pedro']);

// 2) Each signature row has the expected keys
    expect($sigs)->each->toHaveKeys(['id', 'name', 'role', 'signature', 'signed_at']);

// 3) Content spot-checks via id-indexing
    $byId = collect($sigs)->keyBy('id');

// Juan
    expect($byId['uuid-juan']['name'])->toBe('Juan dela Cruz');
    expect($byId['uuid-juan']['role'])->toBe('chairperson');
    expect($byId['uuid-juan']['signature'])->toBe('SIG-JUAN-123');

// Maria
    expect($byId['uuid-maria']['name'])->toBe('Maria Santos');
    expect($byId['uuid-maria']['role'])->toBe('member');
    expect($byId['uuid-maria']['signature'])->toBe('SIG-MARIA-456');

// Pedro
    expect($byId['uuid-pedro']['name'])->toBe('Pedro Reyes');
    expect($byId['uuid-pedro']['role'])->toBe('member');
    expect($byId['uuid-pedro']['signature'])->toBe('SIG-PEDRO-789');

// 4) (Optional) timestamps look ISO-ish, without pinning exact values
    expect($byId['uuid-juan']['signed_at'])->toMatch('/^\d{4}-\d{2}-\d{2}T/');
    expect($byId['uuid-maria']['signed_at'])->toMatch('/^\d{4}-\d{2}-\d{2}T/');
    expect($byId['uuid-pedro']['signed_at'])->toMatch('/^\d{4}-\d{2}-\d{2}T/');
});

it('ingests all *.txt files in a directory (--dir)', function () {
    // Two valid .txt files + one non-.txt that should be ignored
    File::put($this->sigDir . '/a.txt', "id=uuid-juan,signature=A1\n");
    File::put($this->sigDir . '/b.txt', "id=uuid-maria,signature=B2\n");
    File::put($this->sigDir . '/ignore.md', "# not parsed\n");

    $exit = Artisan::call('certify-er', [
        '--er'  => 'DNPT6VLVFF3N',    // no ER- prefix required
        '--dir' => 'storage/signatures',
    ]);
    $out = Artisan::output();

    expect($exit)->toBe(0);

    $sigs = getSignaturesFor('ER-DNPT6VLVFF3N');

    // 1) Exactly the two from *.txt, order-insensitive
    expect($sigs)->toHaveCount(2);
    $ids = collect($sigs)->pluck('id')->all();
    expect($ids)->toEqualCanonicalizing(['uuid-juan','uuid-maria']);

    // 2) Row shape sanity
    expect($sigs)->each->toHaveKeys(['id', 'name', 'role', 'signature', 'signed_at']);

    // 3) Content checks (roster-driven name/role, file-driven signature)
    $byId = collect($sigs)->keyBy('id');

    expect($byId['uuid-juan']['name'])->toBe('Juan dela Cruz');
    expect($byId['uuid-juan']['role'])->toBe('chairperson');
    expect($byId['uuid-juan']['signature'])->toBe('A1');

    expect($byId['uuid-maria']['name'])->toBe('Maria Santos');
    expect($byId['uuid-maria']['role'])->toBe('member');
    expect($byId['uuid-maria']['signature'])->toBe('B2');

    // 4) Timestamps look ISO-ish (don’t pin exact time)
    expect($byId['uuid-juan']['signed_at'])->toMatch('/^\d{4}-\d{2}-\d{2}T/');
    expect($byId['uuid-maria']['signed_at'])->toMatch('/^\d{4}-\d{2}-\d{2}T/');

    // 5) Ensure non-.txt file truly ignored
    expect($out)->not->toContain('ignore.md');
});

it('updates an existing signer entry when the same id is provided again', function () {
    // First sign
    Artisan::call('certify-er', [
        '--er' => 'ER-DNPT6VLVFF3N',
        'signatures' => ['id=uuid-juan,signature=OLD'],
    ]);
    // Re-sign with new token
    Artisan::call('certify-er', [
        '--er' => 'ER-DNPT6VLVFF3N',
        'signatures' => ['uuid-juan|NEW'],
    ]);

    $sigs = getSignaturesFor('ER-DNPT6VLVFF3N');
    expect($sigs)->toHaveCount(1);
    expect($sigs[0]['id'])->toBe('uuid-juan');
    expect($sigs[0]['signature'])->toBe('NEW');
});

it('fails clearly for unknown inspector id', function () {
    $exit = Artisan::call('certify-er', [
        '--er' => 'ER-DNPT6VLVFF3N',
        'signatures' => ['id=nope-unknown,signature=XYZ'],
    ]);
    $out = Artisan::output();

    expect($exit)->toBe(1);
    expect($out)->toContain("not found in precinct roster");
});

it('auto-picks the only ER when --er is omitted', function () {
    // arrange one precinct + one ER in DB...
    // call:
    $exit = Artisan::call('certify-er', [
        'signatures' => ['id=uuid-juan,signature=ABC123'],
    ]);
    expect($exit)->toBe(0);
    $sigs = getSignaturesFor('ER-DNPT6VLVFF3N'); // or whichever you created
    expect(count($sigs))->toBe(1);
});

it('fails when no ER exists and --er is omitted', function () {
    \App\Models\ElectionReturn::query()->delete();
    $exit = Artisan::call('certify-er', [
        'signatures' => ['id=uuid-juan,signature=ABC123'],
    ]);
    $out = Artisan::output();
    expect($exit)->toBe(1);
    expect($out)->toContain('No election return found');
});

it('fails when multiple ERs exist and --er is omitted', function () {
    $precinct = Precinct::factory()->create(['code' => 'DEF456']);
    ElectionReturn::create([
        'id'         => (string) Str::uuid(),
        'code'       => 'AACS6VLVFF3N',
        'precinct_id'=> $precinct->id,
        'tallies'    => [],    // not relevant for signing
        'signatures' => [],    // start clean
    ]);

    $exit = Artisan::call('certify-er', [
        'signatures' => ['id=uuid-juan,signature=ABC123'],
    ]);
    $out = Artisan::output();
    expect($exit)->toBe(1);
    expect($out)->toContain('Multiple election returns found');
});

