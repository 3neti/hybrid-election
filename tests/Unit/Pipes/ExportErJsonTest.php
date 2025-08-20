<?php
// tests/Unit/Pipes/ExportErJsonTest.php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Console\Pipes\ExportErJson;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// ---------- Helpers ----------

/** Minimal ER DTO-like object with ->toJson() and property access */
function makeErDto(array $data): object {
    return new class($data) {
        public function __construct(private array $a) {}
        public function __get($k) { return $this->a[$k] ?? null; }
        public function toJson($opts = 0) { return json_encode($this->a, $opts); }
    };
}

/** Build a default context the pipe expects */
function exportCtx(object $er, string $dirSuffix = 'final', ?string $disk = 'election'): object {
    return (object) [
        'er'        => $er,
        'dirSuffix' => $dirSuffix,
        'disk'      => $disk, // some implementations ignore this; we still set it
    ];
}

/** Default ER data (arrays for tallies) */
function sampleErArray(): array {
    return [
        'id'   => (string) Str::uuid(),
        'code' => 'ERXCODE1',
        'precinct' => [
            'id'   => (string) Str::uuid(),
            'code' => 'CURRIMAO-001',
        ],
        'tallies' => [
            [
                'position_code'  => 'PRESIDENT',
                'candidate_code' => 'P_AAA',
                'candidate_name' => 'Alice A.',
                'count'          => 123,
            ],
            [
                'position_code'  => 'PRESIDENT',
                'candidate_code' => 'P_BBB',
                'candidate_name' => 'Bob B.',
                'count'          => 122,
            ],
        ],
    ];
}

// ---------- Tests ----------

it('writes full and minimal JSON to the election disk', function () {
    // Ensure an election disk exists & is faked
    config([
        'filesystems.disks.election' => [
            'driver' => 'local',
            'root'   => storage_path('app/election'),
        ],
    ]);
    Storage::fake('election');

    $er = makeErDto(sampleErArray());
    $ctx = exportCtx($er, dirSuffix: 'final');

    $called = false;
    $next = function ($c) use (&$called) { $called = true; return $c; };

    (new ExportErJson())->handle($ctx, $next);

    $folder = 'ER-' . $er->code . '/final';

    Storage::disk('election')->assertExists($folder . '/raw.full.json');
    Storage::disk('election')->assertExists($folder . '/raw.min.json');

    // sanity: minimal JSON shape
    $min = json_decode(Storage::disk('election')->get($folder . '/raw.min.json'), true);
    expect($min)->toMatchArray([
        'id'   => $er->id,
        'code' => $er->code,
        'precinct' => [
            'id'   => $er->precinct['id'],
            'code' => $er->precinct['code'],
        ],
    ]);
    expect($min['tallies'])->toBeArray()->and($min['tallies'][0])->toHaveKeys([
        'position_code','candidate_code','candidate_name','count'
    ]);

    // next pipe was called
    expect($called)->toBeTrue();
});

it('falls back to the local disk when election disk is not configured', function () {
    // Remove election disk; fake local
    config()->offsetUnset('filesystems.disks.election');
    Storage::fake('local');

    $er  = makeErDto(sampleErArray());
    $ctx = exportCtx($er, dirSuffix: 'final', disk: 'local');

    $next = fn($c) => $c;

    (new ExportErJson())->handle($ctx, $next);

    $folder = 'ER-' . $er->code . '/final';
    Storage::disk('local')->assertExists($folder . '/raw.full.json');
    Storage::disk('local')->assertExists($folder . '/raw.min.json');
});

it('tolerates tallies as objects (not just arrays)', function () {
    config([
        'filesystems.disks.election' => [
            'driver' => 'local',
            'root'   => storage_path('app/election'),
        ],
    ]);
    Storage::fake('election');

    $arr = sampleErArray();

    // Convert tallies rows to small objects with public props
    $arr['tallies'] = array_map(function ($t) {
        return new class($t) {
            public function __construct(private array $t) {}
            public function __get($k) { return $this->t[$k] ?? null; }
            public function toArray() { return $this->t; }
            public function __isset($k) { return array_key_exists($k, $this->t); }
            public function __call($name, $args) {
                // allow ->toArray() or other calls if pipe probes
                if ($name === 'toArray') return $this->toArray();
                throw new BadMethodCallException();
            }
        };
    }, $arr['tallies']);

    $er  = makeErDto($arr);
    $ctx = exportCtx($er, dirSuffix: 'final');

    $next = fn($c) => $c;
    (new ExportErJson())->handle($ctx, $next);

    $folder = 'ER-' . $er->code . '/final';
    Storage::disk('election')->assertExists($folder . '/raw.min.json');

    $min = json_decode(Storage::disk('election')->get($folder . '/raw.min.json'), true);

    // Ensure the fields were extracted regardless of object/array form
    expect($min['tallies'][0])->toHaveKeys(['position_code','candidate_code','candidate_name','count']);
});

it('overwrites previous exports for the same dirSuffix (idempotent)', function () {
    config([
        'filesystems.disks.election' => [
            'driver' => 'local',
            'root'   => storage_path('app/election'),
        ],
    ]);
    Storage::fake('election');

    $er  = makeErDto(sampleErArray());
    $ctx = exportCtx($er, dirSuffix: 'final');

    $pipe = new ExportErJson();

    // First run
    $pipe->handle($ctx, fn($c) => $c);
    $folder = 'ER-' . $er->code . '/final';
    Storage::disk('election')->assertExists($folder . '/raw.full.json');

    $firstHash = sha1(Storage::disk('election')->get($folder . '/raw.full.json'));

    // Mutate one count & run again
    $er2Arr = sampleErArray();
    $er2Arr['code'] = $er->code; // same ER
    $er2Arr['tallies'][0]['count'] = 999;

    $er2  = makeErDto($er2Arr);
    $ctx2 = exportCtx($er2, dirSuffix: 'final');

    $pipe->handle($ctx2, fn($c) => $c);

    $secondHash = sha1(Storage::disk('election')->get($folder . '/raw.full.json'));
    expect($secondHash)->not()->toBe($firstHash);
});
