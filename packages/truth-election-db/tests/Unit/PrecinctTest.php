<?php

use TruthElection\Data\{ElectoralInspectorData, PrecinctData, BallotData};
use Illuminate\Foundation\Testing\RefreshDatabase;
use TruthElection\Enums\ElectoralInspectorRole;
use TruthElectionDb\Models\{Precinct, Ballot};
use Spatie\LaravelData\DataCollection;

uses(RefreshDatabase::class);

it('can create a precinct with ballots and map to PrecinctData correctly', function () {
    // Create precinct with inspectors
    $inspectors = collect([
        new ElectoralInspectorData(
            id: 'ei-001',
            name: 'Juan Dela Cruz',
            role: ElectoralInspectorRole::CHAIRPERSON
        ),
        new ElectoralInspectorData(
            id: 'ei-002',
            name: 'Maria Santos',
            role: ElectoralInspectorRole::MEMBER
        ),
    ]);

    $precinct = Precinct::create([
        'code' => 'CURRIMAO-001',
        'location_name' => 'Currimao Central School',
        'latitude' => 17.993217,
        'longitude' => 120.488902,
        'electoral_inspectors' => $inspectors->toArray(),
    ]);

    // Attach ballots
    $ballot1 = Ballot::create([
        'code' => 'BAL-001',
        'precinct_code' => $precinct->code,
        'votes' =>  [
            [
                'position' => [
                    'code' => 'PRESIDENT',
                    'name' => 'President of the Philippines',
                    'level' => 'national',
                    'count' => 1,
                ],
                'candidates' => [
                    [
                        'code' => 'uuid-bbm',
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
        ],
    ]);

    $ballot2 = Ballot::create([
        'code' => 'BAL-002',
        'precinct_code' => $precinct->code,
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
                        'code' => 'uuid-bbm',
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
        ],
    ]);

    // Assert: Precinct model
    expect($precinct)->toBeInstanceOf(Precinct::class)
        ->and($precinct->ballots)->toBeArray()
        ->and($precinct->ballots)->toHaveCount(2)
        ->and($precinct->ballots[0]['code'])->toBe('BAL-001')
    ;

    // Convert to DTO
    $data = $precinct->getData();

    // Assert: DTO
    expect($data)->toBeInstanceOf(PrecinctData::class)
        ->and($data->code)->toBe('CURRIMAO-001')
        ->and($data->location_name)->toBe('Currimao Central School')
        ->and($data->latitude)->toBe(17.993217)
        ->and($data->longitude)->toBe(120.488902)
        ->and($data->electoral_inspectors)->toBeInstanceOf(DataCollection::class)
        ->and($data->electoral_inspectors)->toHaveCount(2)
        ->and($data->electoral_inspectors[0])->toBeInstanceOf(ElectoralInspectorData::class)
        ->and($data->electoral_inspectors[0]->role)->toBe(ElectoralInspectorRole::CHAIRPERSON)
        ->and($data->ballots)->toBeInstanceOf(DataCollection::class)
        ->and($data->ballots)->toHaveCount(2)
        ->and($data->ballots[0])->toBeInstanceOf(BallotData::class)
        ->and($data->ballots[0]->code)->toBe('BAL-001')
    ;

    // Assert: Tally is correctly aggregated
    $tallies = $precinct->tallies;

    expect($tallies)->toBeArray()
        ->and($tallies)->toHaveCount(1)
        ->and($tallies[0]['position_code'])->toBe('PRESIDENT')
        ->and($tallies[0]['candidate_code'])->toBe('uuid-bbm')
        ->and($tallies[0]['candidate_name'])->toBe('Ferdinand Marcos Jr.')
        ->and($tallies[0]['count'])->toBe(2);
});
