<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Actions\GenerateElectionReturn;
use Illuminate\Contracts\Support\Arrayable;
use App\Models\ElectionReturn;

Route::post('/election-return', GenerateElectionReturn::class);

Route::get('/election-return', function (Request $request) {
    $er = ElectionReturn::query()->with('precinct')->firstOrFail();

    $data = $er->getData();
    if ($data instanceof Arrayable) {
        $data = $data->toArray();
    } elseif (is_object($data)) {
        $data = json_decode(json_encode($data), true);
    }

    // Default: minimal (strip ballots)
    $payload = $request->query('payload', 'minimal'); // default to "minimal"
    if ($payload !== 'full') {
        unset($data['ballots']);
//        unset($data['ballots'], $data['last_ballot']);
    }

    return response()->json($data);
})->name('precinct-tally');

use App\Http\Controllers\ElectionReturnController;

use App\Actions\SignElectionReturn;

Route::post('/election-returns/{electionReturn}/sign', SignElectionReturn::class);

use App\Actions\SubmitBallot;

Route::post('/ballots', SubmitBallot::class)->name('ballots.submit');

use App\Actions\GenerateQrForJson;

Route::get('/qr/election-return/{code}', GenerateQrForJson::class)
    ->name('qr.er');
Route::post('/qr/election-return', [GenerateQrForJson::class, 'fromBody'])
    ->name('qr.er.from_json'); // NEW

use App\Actions\DecodeQrChunks;

Route::post('/qr/decode', DecodeQrChunks::class)->name('qr.decode');

use App\Actions\GetSampleERjson;

Route::get('/sample-er', GetSampleERjson::class)->name('er.sample');
