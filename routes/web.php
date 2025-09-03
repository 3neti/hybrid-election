<?php

use App\Http\Controllers\ElectionReturnController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Route::get('/election-returns', [ElectionReturnController::class, 'index']);
//Route::get('/election-returns/{id}', [ElectionReturnController::class, 'show']);

//Route::get('/decoder', function () {
//    return Inertia::render('Decoder');
//});

Route::get('/sandbox', function () {
    return Inertia::render('Sandbox');
});

Route::get('/tally', function () {
    return Inertia::render('Tally');
});

Route::get('/truth', function () {
    return Inertia::render('Truth');
});

use App\Http\Controllers\PrintErController;

Route::get('/print/er/{code}', PrintErController::class)->name('print.er');

use TruthQr\TruthQrPublisher;
use TruthQr\Writers\BaconQrWriter;

Route::get('/demo-publish', function (TruthQrPublisher $publisher) {
    $payload = [
        'type' => 'ER',
        'code' => 'DEMO123',
        'data' => ['hello' => 'world'],
    ];

    $writer = new BaconQrWriter('svg');
    $images = $publisher->publishQrImages($payload, $payload['code'], $writer, [
        'by' => 'count',
        'count' => 2,
    ]);

    return response()->json($images);
});

use Dompdf\Dompdf;

Route::get('/test-svg-pdf', function () {
    // Load your SVG content from file
    $svg = file_get_contents(storage_path('app/public/qr.svg'));

    // Encode as base64 for image src
    $svgBase64 = base64_encode($svg);
    $imageTag = '<img src="data:image/svg+xml;base64,' . $svgBase64 . '" width="200" height="200" />';

    $html = <<<HTML
    <html>
        <head>
            <style>
                body { font-family: DejaVu Sans, sans-serif; }
                img { display: block; margin-top: 20px; }
            </style>
        </head>
        <body>
            <h1>Hello World with QR Code</h1>
            $imageTag
        </body>
    </html>
    HTML;

    $dompdf = new Dompdf([
        'enable_remote' => true,
    ]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $dompdf->stream('qr-code.pdf');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
