<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use App\Models\{Precinct, ElectionReturn};
use App\Services\Pdf\PuppeteerErPdfRenderer;

/**
 * Notes:
 * - Test #1 (missing binary) is safe in CI and asserts a clean exception.
 * - Test #2 is SKIPPED by default. Enable it locally by setting CHROME_BIN
 *   (e.g. /Applications/Google Chrome.app/Contents/MacOS/Google Chrome)
 *   and ensuring the print route is reachable (app running, or your route
 *   uses an absolute URL that resolves within the test environment).
 */
uses(RefreshDatabase::class);

function seedMinimalEr(): ElectionReturn {
    // Minimal precinct (no ballots required for the print page to render)
    $precinct = Precinct::create([
        'id' => (string) Str::uuid(),
        'code' => 'CURRIMAO-001',
        'location_name' => 'Currimao National High School',
        'latitude' => 17.993217,
        'longitude' => 120.488902,
        'electoral_inspectors' => [
            ['id' => 'uuid-juan',  'name' => 'Juan dela Cruz', 'role' => 'chairperson'],
            ['id' => 'uuid-maria', 'name' => 'Maria Santos',   'role' => 'member'],
        ],
    ]);

    // An ER record with minimal required fields for the print page
    return ElectionReturn::create([
        'id'          => (string) Str::uuid(),
        'code'        => 'ER-TESTPDF1',          // stored bare or with ER- depending on your schema; renderer normalizes
        'precinct_id' => $precinct->id,
        'tallies'     => [],                     // page should still render
        'signatures'  => [],
    ]);
}

it('throws a clear error when the Chrome/Chromium binary is missing', function () {
    $er = seedMinimalEr();

    // Prepare a destination under storage/app/testing
    Storage::disk('local')->makeDirectory('testing');
    $destAbs = Storage::disk('local')->path('testing/er_missing_bin.pdf');

    // Use a definitely-missing binary path
    $renderer = new PuppeteerErPdfRenderer([
        'timeout'    => 10,
        'puppeteer'  => [
            'binary' => '/path/to/definitely/missing/chrome-binary',
            // any extra args you normally pass:
            'args'   => ['--virtual-time-budget=2000'],
        ],
    ]);

    // Convert the ER Eloquent model to the JSON shape your controller/view expects.
    // If your PrintErController pulls the ER by code anyway, it’s okay to pass a minimal array here.
    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage('Puppeteer/Chromium failed');

    $renderer->render([
        'id' => $er->id,
        'code' => ltrim($er->code, 'ER-'),  // renderer normalizes both ways
        'precinct' => [
            'id' => $er->precinct->id,
            'code' => $er->precinct->code,
        ],
        'tallies' => [],
        'signatures' => [],
    ], $destAbs);
});

it('can render a PDF end-to-end when CHROME_BIN is configured (LOCAL ONLY)', function () {
    $chrome = env('CHROME_BIN'); // e.g. /Applications/Google Chrome.app/Contents/MacOS/Google Chrome
    if (!$chrome || !is_file($chrome)) {
        test()->markTestSkipped('Set CHROME_BIN in your .env to run this test locally.');
    }

    $er = seedMinimalEr();

    // (Optional) sanity: your print route should resolve
    // If you named the route('print.er', ['code' => ...]) you can assert it returns 200:
    // $this->get(route('print.er', ['code' => ltrim($er->code, 'ER-')]))->assertOk();

    Storage::disk('local')->makeDirectory('testing');
    $destAbs = Storage::disk('local')->path('testing/er_local_ok.pdf');

    $renderer = new PuppeteerErPdfRenderer([
        'timeout'    => 30,
        'puppeteer'  => [
            'binary' => $chrome,
            // Helpful defaults: wait a bit for SPA to settle; your page should set window.erReady = true as you added.
            'args'   => [
                '--headless=new',
                '--disable-gpu',
                '--no-sandbox',
                '--disable-dev-shm-usage',
                '--virtual-time-budget=5000',
            ],
        ],
    ]);

    $renderer->render([
        'id' => $er->id,
        'code' => ltrim($er->code, 'ER-'),
        'precinct' => [
            'id' => $er->precinct->id,
            'code' => $er->precinct->code,
        ],
        'tallies' => [],
        'signatures' => [],
    ], $destAbs);

    expect(file_exists($destAbs))->toBeTrue();
    expect(filesize($destAbs))->toBeGreaterThan(1000); // rudimentary sanity check
})->skip(); // default: skip; remove ->skip() when running locally
