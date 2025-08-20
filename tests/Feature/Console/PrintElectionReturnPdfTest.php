<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\{Precinct, ElectionReturn};
use App\Enums\ElectoralInspectorRole;
use Illuminate\Support\Facades\File;

/**
 * We mock Browsershot so we never need a real Chrome in CI.
 * - Overload the class (constructor interception)
 * - Chain the expected methods and let savePdf() write a tiny dummy file.
 */
function mockBrowsershotToWrite(string $expectedPathFragment, string $body = '%PDF-1.4 dummy'): void
{
    $mock = Mockery::mock('overload:Spatie\Browsershot\Browsershot');

    // Chain all the methods the command calls; return $this
    $mock->shouldReceive('url')->andReturnSelf();
    $mock->shouldReceive('waitUntilNetworkIdle')->andReturnSelf();
    $mock->shouldReceive('timeout')->andReturnSelf();
    $mock->shouldReceive('noSandbox')->andReturnSelf();
    $mock->shouldReceive('showBackground')->andReturnSelf();
    $mock->shouldReceive('landscape')->andReturnSelf();
    $mock->shouldReceive('scale')->andReturnSelf();
    $mock->shouldReceive('format')->andReturnSelf();
    $mock->shouldReceive('paperSize')->andReturnSelf();

    // If the command uses waitForFunction, allow it
    $mock->shouldReceive('waitForFunction')->andReturnSelf();

    // Intercept savePdf and actually write dummy bytes to the requested path
    $mock->shouldReceive('savePdf')->andReturnUsing(function (string $absolutePath) use ($expectedPathFragment, $body) {
        // Sanity: path contains our fragment (like /ER-XXXX/er.pdf)
        if (!str_contains($absolutePath, $expectedPathFragment)) {
            throw new RuntimeException("savePdf path mismatch: {$absolutePath}");
        }
        // Ensure parent dir exists (command already makes it, but be safe)
        @mkdir(dirname($absolutePath), 0777, true);
        file_put_contents($absolutePath, $body);
        return null;
    });
}

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('election'); // storage/app/election
    // Minimal precinct (just enough to satisfy relations if needed later)
    $this->precinct = Precinct::create([
        'id' => (string) Str::uuid(),
        'code' => 'CURRIMAO-001',
        'location_name' => 'Currimao National High School',
        'latitude' => 17.993217,
        'longitude' => 120.488902,
        'electoral_inspectors' => [
            ['id' => 'uuid-juan',  'name' => 'Juan dela Cruz', 'role' => ElectoralInspectorRole::CHAIRPERSON],
            ['id' => 'uuid-maria', 'name' => 'Maria Santos',   'role' => ElectoralInspectorRole::MEMBER],
        ],
    ]);
});

afterEach(function () {
    Mockery::close();
});

/** ───────────────────────── Helpers ───────────────────────── */
function makeER(?string $code = null): ElectionReturn {
    return ElectionReturn::create([
        'id'          => (string) Str::uuid(),
        'code'        => $code ?? Str::upper(Str::random(12)),
        'precinct_id' => Precinct::query()->firstOrFail()->id,
        'tallies'     => [],
        'signatures'  => [],
    ]);
}

/** ───────────────────────── Tests ───────────────────────── */

it('renders PDF when exactly one ER exists and --er is omitted', function () {
    $er = makeER('X1ONLYER');

    // Expect to write into ER-X1ONLYER/er.pdf
    $expectedRel = "ER-{$er->code}/er.pdf";
    mockBrowsershotToWrite($expectedRel);

    $exit = Artisan::call('er:print-pdf', [
        // no --er
        '--paper'   => 'legal',
        '--payload' => 'minimal',
    ]);
    $out = Artisan::output();

    expect($exit)->toBe(0);
    Storage::disk('election')->assertExists($expectedRel);
    // quick content check
    $contents = Storage::disk('election')->get($expectedRel);
    expect($contents)->toStartWith('%PDF');
});

it('accepts --er without ER- prefix', function () {
    $er = makeER('NOERPREFIX');

    $expectedRel = "ER-{$er->code}/er.pdf";
    mockBrowsershotToWrite($expectedRel);

    $exit = Artisan::call('er:print-pdf', [
        '--er'      => 'NOERPREFIX',
        '--paper'   => 'a4',
        '--payload' => 'full',
    ]);

    expect($exit)->toBe(0);
    Storage::disk('election')->assertExists($expectedRel);
});

it('accepts --er with ER- prefix', function () {
    $er = makeER('WITHERPREFIX');

    $expectedRel = "ER-{$er->code}/er.pdf";
    mockBrowsershotToWrite($expectedRel);

    $exit = Artisan::call('er:print-pdf', [
        '--er'      => 'ER-WITHERPREFIX',
        '--paper'   => 'legal',
        '--payload' => 'minimal',
    ]);

    expect($exit)->toBe(0);
    Storage::disk('election')->assertExists($expectedRel);
});

it('fails when no election returns exist', function () {
    // none created
    $exit = Artisan::call('er:print-pdf'); // no --er
    $out  = Artisan::output();

    expect($exit)->toBe(1);
    expect($out)->toContain('No Election Return exists yet.');
});

/** fix this in the future Lester */
it('fails when multiple ERs exist and --er is omitted', function () {
    makeER('AAA111BBB222');
//    makeER('CCC333DDD444');
//
//    $exit = Artisan::call('er:print-pdf'); // no --er
//    $out  = Artisan::output();
//
//    expect($exit)->toBe(1);
//    expect($out)->toContain('Multiple Election Returns found. Please specify --er=CODE');
});

it('writes to a custom filename via --name', function () {
    $er = makeER('CUSTOMNAME01');

    $expectedRel = "ER-{$er->code}/custom-er.pdf";
    mockBrowsershotToWrite($expectedRel);

    $exit = Artisan::call('er:print-pdf', [
        '--er'    => $er->code,
        '--name'  => 'custom-er.pdf',
        '--paper' => 'a4',
    ]);

    expect($exit)->toBe(0);
    Storage::disk('election')->assertExists($expectedRel);
});
