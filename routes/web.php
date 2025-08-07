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

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
