<?php

use App\Models\{Ballot, Precinct};
use App\Actions\GenerateElectionReturn;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates election returns per precinct', function () {
    $precinct = Precinct::factory()->hasBallots(10)->create();

    $result = app(GenerateElectionReturn::class)->run($precinct);

    expect($result->precinct_code)->toBe($precinct->code)
        ->and($result->tallies)->toBeInstanceOf(\Spatie\LaravelData\DataCollection::class)
        ->and($result->tallies->first()->count)->toBeGreaterThan(0);
});
