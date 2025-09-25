<?php

use TruthElection\Support\ElectionStoreInterface;
use Illuminate\Support\Facades\Route;

Route::get('election-return', function () {
    return app(ElectionStoreInterface::class)->getElectionReturn();
})->name('election-return');
