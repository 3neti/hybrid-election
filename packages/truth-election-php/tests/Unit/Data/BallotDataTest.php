<?php

use Spatie\LaravelData\DataCollection;
use TruthElection\Enums\Level;
use TruthElection\Data\{
    BallotData,
    CandidateData,
    PositionData,
    VoteData,
    PrecinctData
};

it('creates a BallotData object from nested array', function () {
    $ballot = BallotData::from([
        'id' => 'ballot-uuid-1234',
        'code' => 'BALLOT-001',
        'votes' => [
            [
                'position' => [
                    'code' => 'PRESIDENT',
                    'name' => 'President of the Philippines',
                    'level' => 'national',
                    'count' => 1,
                ],
                'candidates' => [
                    [
                        'code' => 'uuid-bbm-1234',
                        'name' => 'Ferdinand Marcos Jr.',
                        'alias' => 'BBM',
                        'position' => [
                            'code' => 'PRESIDENT',
                            'name' => 'President of the Philippines',
                            'level' => 'national',
                            'count' => 1,
                        ],
                    ],
                ],
            ],
            [
                'position' => [
                    'code' => 'SENATOR',
                    'name' => 'Senator of the Philippines',
                    'level' => 'national',
                    'count' => 12,
                ],
                'candidates' => [
                    [
                        'code' => 'uuid-jdc-001',
                        'name' => 'Juan Dela Cruz',
                        'alias' => 'JDC',
                        'position' => [
                            'code' => 'SENATOR',
                            'name' => 'Senator of the Philippines',
                            'level' => 'national',
                            'count' => 12,
                        ],
                    ],
                    [
                        'code' => 'uuid-mrp-002',
                        'name' => 'Maria Rosario P.',
                        'alias' => 'MRP',
                        'position' => [
                            'code' => 'SENATOR',
                            'name' => 'Senator of the Philippines',
                            'level' => 'national',
                            'count' => 12,
                        ],
                    ],
                ],
            ],
        ],
        'precinct' => [
            'id' => 'precinct-uuid-999',
            'code' => 'CURRIMAO-001',
            'location_name' => 'Currimao Central School',
            'latitude' => 17.993217,
            'longitude' => 120.488902,
            'electoral_inspectors' => [],
            'watchers_count' => 2,
            'precincts_count' => 10,
            'registered_voters_count' => 250,
            'actual_voters_count' => 200,
            'ballots_in_box_count' => 198,
            'unused_ballots_count' => 52,
            'spoiled_ballots_count' => 3,
            'void_ballots_count' => 1,
        ],
    ]);

    expect($ballot)->toBeInstanceOf(BallotData::class)
//        ->and($ballot->id)->toBe('ballot-uuid-1234')
        ->and($ballot->code)->toBe('BALLOT-001')
        ->and($ballot->votes)->toBeInstanceOf(DataCollection::class)
        ->and($ballot->votes)->toHaveCount(2);

    $firstVote = $ballot->votes->first();
    expect($firstVote)->toBeInstanceOf(VoteData::class)
        ->and($firstVote->position)->toBeInstanceOf(PositionData::class)
        ->and($firstVote->position->code)->toBe('PRESIDENT')
        ->and($firstVote->position->level)->toBe(Level::NATIONAL)
        ->and($firstVote->candidates)->toBeInstanceOf(DataCollection::class)
        ->and($firstVote->candidates)->toHaveCount(1)
        ->and($firstVote->candidates->first())->toBeInstanceOf(CandidateData::class)
        ->and($firstVote->candidates->first()->alias)->toBe('BBM');

    expect($ballot->precinct)->toBeInstanceOf(PrecinctData::class)
        ->and($ballot->precinct->code)->toBe('CURRIMAO-001')
        ->and($ballot->precinct->watchers_count)->toBe(2)
        ->and($ballot->precinct->precincts_count)->toBe(10)
        ->and($ballot->precinct->registered_voters_count)->toBe(250)
        ->and($ballot->precinct->actual_voters_count)->toBe(200)
        ->and($ballot->precinct->ballots_in_box_count)->toBe(198)
        ->and($ballot->precinct->unused_ballots_count)->toBe(52)
        ->and($ballot->precinct->spoiled_ballots_count)->toBe(3)
        ->and($ballot->precinct->void_ballots_count)->toBe(1);
});

it('requires latitude and longitude to be non-null when provided', function () {
    $precinct = PrecinctData::from([
//        'id' => 'precinct-uuid-777',
        'code' => 'SAN NICOLAS-001',
        'location_name' => 'San Nicolas Elementary School',
        'latitude' => 18.18077,
        'longitude' => 120.59439,
        'electoral_inspectors' => [],
    ]);

    expect($precinct)->toBeInstanceOf(PrecinctData::class)
        ->and($precinct->latitude)->not->toBeNull()
        ->and($precinct->longitude)->not->toBeNull()
        ->and($precinct->latitude)->toBeFloat()
        ->and($precinct->longitude)->toBeFloat()
        ->and($precinct->latitude)->toBeGreaterThanOrEqual(-90)->toBeLessThanOrEqual(90)
        ->and($precinct->longitude)->toBeGreaterThanOrEqual(-180)->toBeLessThanOrEqual(180)
        ;
});

it('throws a TypeError when latitude or longitude are not floats', function () {
    expect(function () {
        PrecinctData::from([
            'id' => 'precinct-uuid-999',
            'code' => 'BAD-PRECINCT',
            'location_name' => 'Nowhere School',
            'latitude' => 'not-a-float', // ❌ string instead of float
            'longitude' => null,         // ❌ null instead of float
            'electoral_inspectors' => [],
        ]);
    })->toThrow(TypeError::class);
});

it('maps precinct meta fields as null when omitted', function () {
    $precinct = PrecinctData::from([
        'id' => 'precinct-uuid-888',
        'code' => 'CURRIMAO-002',
        'location_name' => 'Another School',
        'latitude' => 18.18077,
        'longitude' => 120.59439,
        'electoral_inspectors' => [],
        // intentionally no meta fields here
    ]);

    expect($precinct)->toBeInstanceOf(PrecinctData::class)
        ->and($precinct->watchers_count)->toBeNull()
        ->and($precinct->precincts_count)->toBeNull()
        ->and($precinct->registered_voters_count)->toBeNull()
        ->and($precinct->actual_voters_count)->toBeNull()
        ->and($precinct->ballots_in_box_count)->toBeNull()
        ->and($precinct->unused_ballots_count)->toBeNull()
        ->and($precinct->spoiled_ballots_count)->toBeNull()
        ->and($precinct->void_ballots_count)->toBeNull();
});
