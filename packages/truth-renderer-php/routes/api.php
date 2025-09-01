<?php

use Illuminate\Support\Facades\Route;
use TruthRenderer\Http\Controllers\TruthRenderController;

Route::get('/truth/templates', [TruthRenderController::class, 'listTemplates']);
Route::post('/truth/render',  [TruthRenderController::class, 'render']);
