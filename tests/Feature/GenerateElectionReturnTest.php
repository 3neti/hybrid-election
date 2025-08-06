<?php

use App\Models\{Ballot, Precinct};
use App\Actions\GenerateElectionReturn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Data\ElectionReturnData;
use App\Data\PrecinctData;
use Spatie\LaravelData\DataCollection;

uses(RefreshDatabase::class);

it('generates election returns per precinct', function () {
    $precinct = Precinct::factory()->hasBallots(10)->create();

    /** @var ElectionReturnData $result */
    $result = app(GenerateElectionReturn::class)->run($precinct);

    expect($result)->toBeInstanceOf(ElectionReturnData::class)
        ->and($result->precinct)->toBeInstanceOf(PrecinctData::class)
        ->and($result->precinct->code)->toBe($precinct->code)
        ->and($result->tallies)->toBeInstanceOf(DataCollection::class)
        ->and($result->tallies)->not->toBeEmpty()
        ->and($result->tallies->first()->count)->toBeGreaterThan(0);
});
