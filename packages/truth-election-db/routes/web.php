<?php

use Illuminate\Support\Facades\Route;

Route::prefix('truth-election-db')
    ->middleware(['web'])
    ->group(function () {
        Route::get('/health', fn () => response()->json(['status' => 'ok']));
    });

use TruthElectionDb\Actions\SetupElection;

Route::post('/election/setup', SetupElection::class)->name('election.setup');
