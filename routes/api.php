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
})->name('precinct-tally');

use App\Http\Controllers\ElectionReturnController;

use App\Actions\SignElectionReturn;

Route::post('/election-returns/{electionReturn}/sign', SignElectionReturn::class);

use App\Actions\SubmitBallot;

Route::post('/ballots', SubmitBallot::class)->name('ballots.submit');

use App\Actions\GenerateQrForJson;

Route::get('/qr/election-return/{code}', GenerateQrForJson::class)
    ->name('qr.er');

use App\Actions\DecodeQrChunks;

Route::post('/qr/decode', DecodeQrChunks::class)->name('qr.decode');

use App\Actions\GetSampleERjson;

Route::get('/sample-er', GetSampleERjson::class)->name('er.sample');
