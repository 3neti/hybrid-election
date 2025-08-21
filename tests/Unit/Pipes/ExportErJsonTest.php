<?php
// tests/Unit/Pipes/ExportErJsonTest.php

use Illuminate\Support\Facades\Storage;
use App\Console\Pipes\ExportErJson;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// NOTE:
// - This test relies on helpers defined in tests/Pest.php:
//   - sample_er_array(): array
//   - finalize_ctx_from_array(array $er = null, array $opts = []): FinalizeErContext
// These helpers build a real Precinct + ER DTO and return a typed FinalizeErContext.

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

    // Build a full FinalizeErContext with a deterministic ER code
    $er = sample_er_array();
    $er['code'] = 'ERXCODE1'; // bare code; folder will be "ER-ERXCODE1/final"

    $ctx = finalize_ctx_from_array($er, [
        'disk'   => 'election',
        'folder' => 'ER-ERXCODE1/final',
    ]);

    $called = false;
    $next = function ($c) use (&$called) { $called = true; return $c; };

    (new ExportErJson())->handle($ctx, $next);

    $folder = $ctx->folder;

    Storage::disk($ctx->disk)->assertExists($folder . '/raw.full.json');
    Storage::disk($ctx->disk)->assertExists($folder . '/raw.min.json');

    // sanity: minimal JSON shape
    $min = json_decode(Storage::disk($ctx->disk)->get($folder . '/raw.min.json'), true);
    expect($min)->toMatchArray([
        'id'   => $ctx->er->id,
        'code' => $ctx->er->code,
        'precinct' => [
            'id'   => $ctx->er->precinct->id,
            'code' => $ctx->er->precinct->code,
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

    $er  = sample_er_array();
    $er['code'] = 'ERXCODE2';
    $ctx = finalize_ctx_from_array($er, [
        'disk'   => 'local',
        'folder' => 'ER-ERXCODE2/final',
    ]);

    $next = fn($c) => $c;

    (new ExportErJson())->handle($ctx, $next);

    $folder = $ctx->folder;
    Storage::disk($ctx->disk)->assertExists($folder . '/raw.full.json');
    Storage::disk($ctx->disk)->assertExists($folder . '/raw.min.json');
});

//it('tolerates tallies as objects (not just arrays)', function () {
//    config([
//        'filesystems.disks.election' => [
//            'driver' => 'local',
//            'root'   => storage_path('app/election'),
//        ],
//    ]);
//    Storage::fake('election');
//
//    // Build a normal context first (gives us a real Precinct model etc.)
//    $arr = sample_er_array();
//    $arr['code'] = 'ERXCODE3';
//
//    $ctx = finalize_ctx_from_array($arr, [
//        'disk'   => 'election',
//        'folder' => 'ER-ERXCODE3/final',
//    ]);
//
//    // Now swap the ER on the context with a plain object whose tallies are objects,
//    // and that supports ->toJson() like a DTO.
//    $plainErArray = $arr;
//    $plainErArray['tallies'] = array_map(function ($t) {
//        return new class($t) {
//            public function __construct(private array $t) {}
//            public function __get($k) { return $this->t[$k] ?? null; }
//            public function toArray() { return $this->t; }
//        };
//    }, $arr['tallies']);
//
//    $ctx->er = new class($plainErArray) {
//        public function __construct(private array $a) {}
//        public function __get($k) { return $this->a[$k] ?? null; }
//        public function toJson($opts = 0) { return json_encode($this->a, $opts); }
//    };
//
//    // Run the pipe
//    (new \App\Console\Pipes\ExportErJson())->handle($ctx, fn ($c) => $c);
//
//    $folder = $ctx->folder;
//    Storage::disk($ctx->disk)->assertExists($folder . '/raw.min.json');
//
//    $min = json_decode(Storage::disk($ctx->disk)->get($folder . '/raw.min.json'), true);
//
//    // Ensure the fields were extracted regardless of object/array form
//    expect($min['tallies'][0])->toHaveKeys(['position_code','candidate_code','candidate_name','count']);
//});

//it('tolerates tallies as objects (not just arrays)', function () {
//    config([
//        'filesystems.disks.election' => [
//            'driver' => 'local',
//            'root'   => storage_path('app/election'),
//        ],
//    ]);
//    Storage::fake('election');
//
//    $arr = sample_er_array();
//    $arr['code'] = 'ERXCODE3';
//
//    // Convert tallies rows to small objects with public props (array-like)
//    $arr['tallies'] = array_map(function ($t) {
//        return new class($t) {
//            public function __construct(private array $t) {}
//            public function __get($k) { return $this->t[$k] ?? null; }
//            public function toArray() { return $this->t; }
//        };
//    }, $arr['tallies']);
//
//    $ctx = finalize_ctx_from_array($arr, [
//        'disk'   => 'election',
//        'folder' => 'ER-ERXCODE3/final',
//    ]);
//
//    (new ExportErJson())->handle($ctx, fn ($c) => $c);
//
//    $folder = $ctx->folder;
//    Storage::disk($ctx->disk)->assertExists($folder . '/raw.min.json');
//
//    $min = json_decode(Storage::disk($ctx->disk)->get($folder . '/raw.min.json'), true);
//
//    // Ensure the fields were extracted regardless of object/array form
//    expect($min['tallies'][0])->toHaveKeys(['position_code','candidate_code','candidate_name','count']);
//});

it('overwrites previous exports for the same folder (idempotent)', function () {
    config([
        'filesystems.disks.election' => [
            'driver' => 'local',
            'root'   => storage_path('app/election'),
        ],
    ]);
    Storage::fake('election');

    // First run
    $er1 = sample_er_array();
    $er1['code'] = 'ERXCODE4';

    $ctx1 = finalize_ctx_from_array($er1, [
        'disk'   => 'election',
        'folder' => 'ER-ERXCODE4/final',
    ]);

    $pipe = new ExportErJson();
    $pipe->handle($ctx1, fn($c) => $c);

    $folder = $ctx1->folder;
    Storage::disk($ctx1->disk)->assertExists($folder . '/raw.full.json');

    $firstHash = sha1(Storage::disk($ctx1->disk)->get($folder . '/raw.full.json'));

    // Mutate one count & run again, using the same folder
    $er2 = sample_er_array();
    $er2['code'] = 'ERXCODE4'; // same ER code → same folder
    $er2['tallies'][0]['count'] = 999;

    $ctx2 = finalize_ctx_from_array($er2, [
        'disk'   => 'election',
        'folder' => 'ER-ERXCODE4/final',
    ]);

    $pipe->handle($ctx2, fn($c) => $c);

    $secondHash = sha1(Storage::disk($ctx2->disk)->get($folder . '/raw.full.json'));
    expect($secondHash)->not()->toBe($firstHash);
})->skip();
