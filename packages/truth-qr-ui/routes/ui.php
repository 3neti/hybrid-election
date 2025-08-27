<?php

use Illuminate\Support\Facades\Route;
use TruthQrUi\Http\Controllers\EncodeController;
use TruthQrUi\Http\Controllers\DecodeController;
use TruthQrUi\Http\Controllers\PlaygroundController;

Route::group(config('truth-qr-ui.routes'), function () {
    Route::get('/encode', [EncodeController::class, 'show'])->name('truthqr.ui.encode');
    Route::post('/encode', [EncodeController::class, 'encode'])->name('truthqr.ui.encode.run');

    Route::get('/decode', [DecodeController::class, 'show'])->name('truthqr.ui.decode');
    Route::post('/decode', [DecodeController::class, 'decode'])->name('truthqr.ui.decode.run');

    Route::get('/playground', [PlaygroundController::class, 'show'])->name('truthqr.ui.playground');
});
