<?php

use TruthElection\Data\ElectoralInspectorData;
use TruthElection\Tests\ResetsInMemoryElectionStore;
use TruthElection\Support\InMemoryElectionStore;
use TruthElection\Data\BallotData;

uses(ResetsInMemoryElectionStore::class)->beforeEach(fn () => $this->resetElectionStore());

it('can store and retrieve ballots and precincts in memory', function () {
    $store = InMemoryElectionStore::instance();
    $store->reset();

    $ballot = BallotData::from([
        'id' => '123',
        'code' => 'BAL-001',
        'precinct' => [
            'id' => '123',
            'code' => 'PRECINCT-01',
            'location_name' => 'City Hall',
            'latitude' => 18.2,
            'longitude' => 120.5,
            'electoral_inspectors' => [],
        ],
        'votes' => [],
        'signature' => null,
    ]);

    $store->putPrecinct($ballot->precinct);
    $store->putBallot($ballot);

    $ballots = $store->getBallotsForPrecinct('PRECINCT-01');

    expect($store->precincts)->toHaveKey('PRECINCT-01')
        ->and($store->ballots)->toHaveKey('BAL-001')
        ->and($ballots)->toHaveCount(1)
        ->and($ballots['BAL-001']->code)->toBe('BAL-001');
});

it('can store multiple ballots for the same precinct', function () {
    $store = InMemoryElectionStore::instance();
    $store->reset();

    $precinctCode = 'PRECINCT-01';

    foreach (range(1, 3) as $i) {
        $store->putBallot(BallotData::from([
            'id' => "id-$i",
            'code' => "BAL-00$i",
            'precinct' => [
                'id' => 'precinct-1',
                'code' => $precinctCode,
                'location_name' => 'School',
                'latitude' => 10.0,
                'longitude' => 120.0,
                'electoral_inspectors' => [],
            ],
            'votes' => [],
            'signature' => null,
        ]));
    }

    $ballots = $store->getBallotsForPrecinct($precinctCode);

    expect($ballots)->toHaveCount(3);
});

it('resets the store correctly', function () {
    $store = InMemoryElectionStore::instance();
    $store->reset();

    expect($store->precincts)->toBeEmpty()
        ->and($store->ballots)->toBeEmpty();
});

it('returns empty array when no ballots exist for a precinct', function () {
    $store = InMemoryElectionStore::instance();
    $store->reset();

    $ballots = $store->getBallotsForPrecinct('NON_EXISTENT');

    expect($ballots)->toBeArray()->toBeEmpty();
});

use Illuminate\Support\Carbon;
use TruthElection\Data\ElectionReturnData;

it('can retrieve election return by code', function () {
    $store = InMemoryElectionStore::instance();
    $store->reset();

    $electionReturn = ElectionReturnData::from([
        'id' => 'er-id-001',
        'code' => 'ER-001',
        'precinct' => [
            'id' => 'precinct-1',
            'code' => 'PRECINCT-99',
            'location_name' => 'Gymnasium',
            'latitude' => 14.6,
            'longitude' => 121.0,
            'electoral_inspectors' => [],
        ],
        'tallies' => [
            [
                'position_code' => 'PRESIDENT',
                'candidate_code' => 'CAND-A',
                'candidate_name' => 'Candidate A',
                'count' => 123,
            ],
        ],
        'signatures' => [],
        'ballots' => [],
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ]);

    $store->putPrecinct($electionReturn->precinct);
    $store->putElectionReturn($electionReturn);

    $fetched = $store->getElectionReturn('ER-001');

    expect($fetched)->not->toBeNull()
        ->and($fetched->code)->toBe('ER-001')
        ->and($fetched->precinct->code)->toBe('PRECINCT-99')
        ->and($fetched->tallies)->toHaveCount(1)
        ->and($fetched->tallies[0]->candidate_name)->toBe('Candidate A');
});

it('can update election return signatures and replace it in the store', function () {
    $store = InMemoryElectionStore::instance();
    $store->reset();

    // Step 1: Seed original election return with inspectors
    $original = ElectionReturnData::from([
        'id' => 'er-id-001',
        'code' => 'ER-001',
        'precinct' => [
            'id' => 'precinct-1',
            'code' => 'PRECINCT-99',
            'location_name' => 'Gymnasium',
            'latitude' => 14.6,
            'longitude' => 121.0,
            'electoral_inspectors' => [
                [
                    'id' => 'A1',
                    'name' => 'Alice',
                    'role' => 'chairperson',
                ],
                [
                    'id' => 'B2',
                    'name' => 'Bob',
                    'role' => 'member',
                ],
            ],
        ],
        'tallies' => [
            [
                'position_code' => 'PRESIDENT',
                'candidate_code' => 'CAND-A',
                'candidate_name' => 'Candidate A',
                'count' => 123,
            ],
        ],
        'signatures' => [],
        'ballots' => [],
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ]);

    $store->putPrecinct($original->precinct);
    $store->putElectionReturn($original);

    // Step 2: Sign as "Bob" (B2), get his info from precinct
    $inspector = $store->findInspector($original, 'B2');
    expect($inspector)->toBeInstanceOf(ElectoralInspectorData::class);

    $signed = new ElectoralInspectorData(
        id: $inspector->id,
        name: $inspector->name,
        role: $inspector->role,
        signature: 'data:image/png;base64,FAKESIGNATURE==',
        signed_at: Carbon::now()
    );

    // Step 3: Rebuild election return with updated signatures
    $updatedArray = $original->toArray();
    $updatedArray['signatures'][] = $signed->toArray(); // or just use $signed directly with from()

    $updated = ElectionReturnData::from($updatedArray);
    $store->replaceElectionReturn($updated);

    // Step 4: Verify
    $fetched = $store->getElectionReturn('ER-001');

    expect($fetched)->not->toBeNull()
        ->and($fetched->signatures)->toHaveCount(1)
        ->and($fetched->signatures[0]->id)->toBe('B2')
        ->and($fetched->signatures[0]->name)->toBe('Bob')
        ->and($fetched->signatures[0]->role->value)->toBe('member')
        ->and($fetched->signatures[0]->signature)->toBe('data:image/png;base64,FAKESIGNATURE==');
});
