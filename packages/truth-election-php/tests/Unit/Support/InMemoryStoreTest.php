<?php

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
