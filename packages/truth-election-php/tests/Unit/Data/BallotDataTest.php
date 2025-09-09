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
    ]);

    expect($ballot)->toBeInstanceOf(BallotData::class)
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

test('BallotData holds structured votes correctly', function () {
    $president = new PositionData('PRESIDENT', 'President', Level::NATIONAL, 1);
    $senator = new PositionData('SENATOR', 'Senator', Level::NATIONAL, 12);

    $votePresident = new VoteData(new DataCollection(CandidateData::class, [
        new CandidateData('CAND-001', 'Juan Dela Cruz', 'JUAN', $president),
    ]));

    $voteSenator = new VoteData(new DataCollection(CandidateData::class, [
        new CandidateData('CAND-002', 'Maria Santos', 'MARIA', $senator),
        new CandidateData('CAND-003', 'Pedro Reyes', 'PEDRO', $senator),
    ]));

    $ballot = new BallotData('BALLOT-123', new DataCollection(VoteData::class, [
        $votePresident,
        $voteSenator,
    ]));

    expect($ballot)->toBeInstanceOf(BallotData::class)
        ->and($ballot->code)->toBe('BALLOT-123')
        ->and($ballot->votes)->toHaveCount(2)
        ->and($ballot->votes[0]->position->code)->toBe('PRESIDENT')
        ->and($ballot->votes[1]->candidates[1]->alias)->toBe('PEDRO');
});
