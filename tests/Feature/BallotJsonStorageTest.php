<?php

use App\Data\{BallotData, VoteData, PositionData, CandidateData, PrecinctData};
use App\Enums\Level;
use App\Models\{Ballot, Precinct};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\LaravelData\DataCollection;

uses(RefreshDatabase::class);

it('stores and retrieves a ballot with JSON votes and precinct_id correctly', function () {
    $precinct = Precinct::factory()->create();

    $votes = new DataCollection(VoteData::class, [
        new VoteData(
            new PositionData('PRESIDENT', 'President of the Philippines', level: Level::NATIONAL, count: 1),
            new DataCollection(CandidateData::class, [
                new CandidateData('uuid-bbm', 'Ferdinand Marcos Jr.', 'BBM')
            ])
        ),
        new VoteData(
            new PositionData('SENATOR', 'Senator of the Philippines', level: Level::NATIONAL, count: 12),
            new DataCollection(CandidateData::class, [
                new CandidateData('uuid-jdc', 'Juan Dela Cruz', 'JDC'),
                new CandidateData('uuid-mrp', 'Maria Rosario P.', 'MRP')
            ])
        ),
    ]);

    $ballot = Ballot::create([
        'code' => 'BALLOT-001',
        'votes' => $votes,
        'precinct' => $precinct,
    ]);

    $ballot->refresh();

    $this->assertDatabaseHas('ballots', [
        'code' => 'BALLOT-001',
        'precinct_id' => $precinct->id,
    ]);

    expect($ballot->votes)->toBeInstanceOf(DataCollection::class)
        ->and($ballot->votes)->toHaveCount(2)
        ->and($ballot->votes->first())->toBeInstanceOf(VoteData::class)
        ->and($ballot->votes->first()->position->code)->toBe('PRESIDENT')
        ->and($ballot->votes->first()->candidates->first()->alias)->toBe('BBM');
});

it('stores and hydrates BallotData via getData()', function () {
    $precinct = Precinct::factory()->create();

    $votes = new DataCollection(VoteData::class, [
        new VoteData(
            new PositionData('PRESIDENT', 'President of the Philippines', Level::NATIONAL, 1),
            new DataCollection(CandidateData::class, [
                new CandidateData('uuid-bbm', 'Ferdinand Marcos Jr.', 'BBM')
            ])
        ),
        new VoteData(
            new PositionData('SENATOR', 'Senator of the Philippines', Level::NATIONAL, 12),
            new DataCollection(CandidateData::class, [
                new CandidateData('uuid-jdc', 'Juan Dela Cruz', 'JDC'),
                new CandidateData('uuid-mrp', 'Maria Rosario P.', 'MRP')
            ])
        )
    ]);

    $ballot = Ballot::create([
        'code' => 'BALLOT-001',
        'votes' => $votes,
        'precinct' => $precinct,
    ]);
    $ballot->refresh();

    expect($ballot->votes)->toBeInstanceOf(DataCollection::class)
        ->and($ballot->votes->first())->toBeInstanceOf(VoteData::class);

    $ballotData = $ballot->getData();

    expect($ballotData)->toBeInstanceOf(BallotData::class)
        ->and($ballotData->code)->toBe('BALLOT-001')
        ->and($ballotData->votes)->toBeInstanceOf(DataCollection::class)
        ->and($ballotData->votes)->toHaveCount(2)
        ->and($ballotData->votes->first()->position->code)->toBe('PRESIDENT')
        ->and($ballotData->votes->first()->candidates->first()->alias)->toBe('BBM')
        ->and($ballotData->precinct)->toBeInstanceOf(PrecinctData::class)
        ->and($ballotData->precinct->id)->toBe($precinct->id)
    ;
});
