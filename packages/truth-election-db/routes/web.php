<?php

use Illuminate\Support\Facades\Route;

Route::prefix('truth-election-db')
    ->middleware(['web'])
    ->group(function () {
        Route::get('/health', fn () => response()->json(['status' => 'ok']));
    });

use TruthElectionDb\Actions\SetupElection;

Route::post('/election/setup', SetupElection::class)->name('election.setup');

use TruthElectionDb\Actions\CastBallot;

Route::post('/ballot/cast', CastBallot::class)->name('ballot.cast');

use TruthElectionDb\Actions\TallyVotes;

Route::post('/votes/tally', TallyVotes::class)->name('votes.tally');

use TruthElectionDb\Actions\RecordStatistics;

Route::patch('/precincts/{precinct}/statistics', RecordStatistics::class)->name('precinct.statistics');
