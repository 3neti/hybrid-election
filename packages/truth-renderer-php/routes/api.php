<?php

use TruthRenderer\Http\Controllers\TruthTemplateUploadController;
use TruthRenderer\Http\Controllers\TruthRenderController;
use Illuminate\Support\Facades\Route;

Route::get('/truth/templates', [TruthRenderController::class, 'listTemplates']);
Route::post('/truth/render',  [TruthRenderController::class, 'render']);


Route::post('/truth/templates/upload', [TruthTemplateUploadController::class, 'upload'])->name('truth-template.upload');
