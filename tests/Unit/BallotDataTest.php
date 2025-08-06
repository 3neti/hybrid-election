<?php

use App\Data\{
    BallotData,
    CandidateData,
    PositionData,
    VoteData,
    PrecinctData
};
use App\Enums\Level;
use Spatie\LaravelData\DataCollection;

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
                    ],
                    [
                        'code' => 'uuid-mrp-002',
                        'name' => 'Maria Rosario P.',
                        'alias' => 'MRP',
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
        ],
    ]);

    expect($ballot)->toBeInstanceOf(BallotData::class)
        ->and($ballot->id)->toBe('ballot-uuid-1234')
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
        ->and($ballot->precinct->code)->toBe('CURRIMAO-001');
});
