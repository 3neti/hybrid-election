<?php

use TruthElectionDb\Actions\RecordStatistics;
use TruthElectionDb\Actions\SetupElection;
use TruthElectionDb\Actions\CastBallot;
use TruthElectionDb\Actions\TallyVotes;
use Illuminate\Support\Facades\Route;

Route::post('setup-precinct', SetupElection::class)->name('election.setup');
Route::post('cast-ballot', CastBallot::class)->name('cast.ballot');
Route::post('tally-votes', TallyVotes::class)->name('tally.votes');
Route::patch('record-statistics', RecordStatistics::class)->name('record.statistics');
