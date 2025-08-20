<?php

use App\Console\Pipes\ExportQr;
use App\Services\Qr\QrExporter;
use App\Services\Qr\QrExportResult;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Fake disks used by the pipe: 'election' for final export, 'local' for QR export source
    Storage::fake('election');
    Storage::fake('local');

    // Minimal route so route('qr.er', ...) resolves in unit tests
    Route::get('/api/qr/election-return/{code}', function (string $code) {
        return response()->json(['ok' => true, 'code' => $code]);
    })->name('qr.er');
});

/** Helper to make a minimal context object */
function qr_ctx(string $code = 'ERTEST', string $dirSuffix = 'final', ?string $disk = 'election'): object {
    $er = (object) [
        'id'   => 'er-id',
        'code' => $code,
        'precinct' => (object) ['id' => 'p1', 'code' => 'P-001'],
        'tallies'  => [],
    ];

    return (object) [
        'er'        => $er,
        'dirSuffix' => $dirSuffix,
        'disk'      => $disk,   // ExportQr will use this for the destination disk
    ];
}

it('stores the exported QR persisted path on the context', function () {
    $code = 'ER1ABC';
    $dir  = 'final';
    $c = (object)[
        'er'       => (object)['code' => $code],
        'payload'  => 'minimal',
        'maxChars' => 1200,
        'folder'   => "ER-{$code}/{$dir}",
    ];

    // Simulated path returned by the QR exporter
    $qrRel = "private/qr_exports/{$code}/{$dir}";
    $qrAbs = Storage::disk('local')->path($qrRel);

    // Fake QrExporter that returns the correct type
    $fakeExporter = new class($qrAbs) implements QrExporter {
        public function __construct(private string $abs) {}
        public function export(string $code, array $opts = []): QrExportResult {
            return new QrExportResult(total: 2, persistedToAbs: $this->abs);
        }
    };

    $pipe = new ExportQr($fakeExporter);

    $out = $pipe->handle($c, fn ($ctx) => $ctx);

    expect($out->qrPersistedAbs)->toBe($qrAbs);
});

it('throws when the QR export layer reports a failure', function () {
    // Context helper (same shape ExportQr expects)
    $c = (object)[
        'er'       => (object)['code' => 'ERFAIL'],
        'payload'  => 'minimal',
        'maxChars' => 1200,
        'folder'   => 'ER-ERFAIL/final',
    ];

    // Fake exporter that simulates a failure
    $failingExporter = new class implements QrExporter {
        public function export(string $code, array $opts = []): never
        {
            throw new \RuntimeException('QR export failed (simulated).');
        }
    };

    $pipe = new ExportQr($failingExporter);

    expect(fn () => $pipe->handle($c, fn ($ctx) => $ctx))
        ->toThrow(\RuntimeException::class, 'QR export failed');
});

it('falls back to filesystem copy when persisted_to is outside local disk', function () {
    $code = 'ERXPATH';
    $dir = 'final';

    // Context the pipe expects
    $c = (object)[
        'er' => (object)['code' => $code],
        'payload' => 'minimal',
        'maxChars' => 1200,
        'folder' => "ER-{$code}/{$dir}",
    ];

    // Real temp folder OUTSIDE Storage::disk('local')->path('')
    $tmpBase = sys_get_temp_dir() . '/qrtest_' . uniqid('', true);
    @mkdir($tmpBase, 0777, true);
    file_put_contents($tmpBase . '/manifest.json', '{"ok":true}');
    file_put_contents($tmpBase . '/chunk_1of1.txt', 'QR|v1|...|1/1|Z');

    // Fake QrExporter that returns that absolute path
    $fakeExporter = new class($tmpBase) implements QrExporter {
        public function __construct(private string $abs)
        {
        }

        public function export(string $code, array $opts = []): QrExportResult
        {
            return new QrExportResult(total: 1, persistedToAbs: $this->abs);
        }
    };

    // Run pipe
    $pipe = new ExportQr($fakeExporter);
    $pipe->handle($c, fn($ctx) => $ctx);

    // Assert mirrored artifacts on "election" disk
    $base = "ER-{$code}/{$dir}/qr";
    expect(Storage::disk('election')->exists("{$base}/manifest.json"))->toBeTrue();
    expect(Storage::disk('election')->exists("{$base}/chunk_1of1.txt"))->toBeTrue();

    // Cleanup temp
    @unlink($tmpBase . '/manifest.json');
    @unlink($tmpBase . '/chunk_1of1.txt');
    @rmdir($tmpBase);
});
