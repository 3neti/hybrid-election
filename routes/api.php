<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Actions\GenerateElectionReturn;

Route::post('/election-return', GenerateElectionReturn::class);
Route::get('/election-return', function (Request $request) {
    return \App\Models\ElectionReturn::first()->getData();
});

use App\Http\Controllers\ElectionReturnController;

use App\Actions\SignElectionReturn;

Route::post('/election-returns/{electionReturn}/sign', SignElectionReturn::class);

use App\Actions\SubmitBallot;

Route::post('/ballots', SubmitBallot::class)->name('ballots.submit');
