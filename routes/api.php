<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Actions\GenerateElectionReturn;

Route::get('/election-return', GenerateElectionReturn::class);
