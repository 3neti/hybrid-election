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

Route::get('/election-returns', [ElectionReturnController::class, 'index']);
Route::get('/election-returns/{id}', [ElectionReturnController::class, 'show']);

use App\Http\Controllers\TallyController;
Route::get('/tally', [TallyController::class, 'index']);

Route::get('/decoder', function () {
    return Inertia::render('Decoder');
});

//Route::get('/tally-standalone', function () {
//    return Inertia::render('TallyStandalone');
//});
Route::get('/tally', function () {
    return Inertia::render('TallyStandalone');
});

use App\Http\Controllers\PrintErController;

Route::get('/print/er/{code}', PrintErController::class)->name('print.er');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
