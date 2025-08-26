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

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
