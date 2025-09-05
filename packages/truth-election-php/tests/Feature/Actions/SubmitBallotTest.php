<?php

use TruthElection\Tests\ResetsInMemoryElectionStore;
use TruthElection\Support\InMemoryElectionStore;
use TruthElection\Actions\SubmitBallot;
use Spatie\LaravelData\DataCollection;
use TruthElection\Data\CandidateData;
use TruthElection\Data\PrecinctData;
use TruthElection\Data\PositionData;
use TruthElection\Data\BallotData;
use TruthElection\Data\VoteData;
use TruthElection\Enums\Level;

uses(ResetsInMemoryElectionStore::class)->beforeEach(function () {
    $this->resetElectionStore();

    $this->store = InMemoryElectionStore::instance();

    $this->precinct = PrecinctData::from([
        'id' => 'PR001',
        'code' => 'PRECINCT-01',
        'location_name' => 'City Hall',
        'latitude' => 14.5995,
        'longitude' => 120.9842,
        'electoral_inspectors' => [],
    ]);

    $this->store->putPrecinct($this->precinct);

    $this->votes = collect([
        new VoteData(
            position: new PositionData(
                code: 'PRESIDENT',
                name: 'President of the Philippines',
                level: Level::NATIONAL,
                count: 1
            ),
            candidates: new DataCollection(CandidateData::class, [
                new CandidateData(
                    code: 'CANDIDATE-001',
                    name: 'Juan Dela Cruz',
                    alias: 'JUAN'
                ),
            ])
        ),
        new VoteData(
            position: new PositionData(
                code: 'SENATOR',
                name: 'Senator',
                level: Level::NATIONAL,
                count: 12
            ),
            candidates: new DataCollection(CandidateData::class, [
                new CandidateData(
                    code: 'CANDIDATE-002',
                    name: 'Maria Santos',
                    alias: 'MARIA'
                ),
                new CandidateData(
                    code: 'CANDIDATE-003',
                    name: 'Pedro Reyes',
                    alias: 'PEDRO'
                ),
            ])
        ),
    ]);
});

it('submits a ballot to an existing precinct', function () {
    $ballot = SubmitBallot::run('BAL-001', 'PRECINCT-01', $this->votes);

    expect($ballot)->toBeInstanceOf(BallotData::class)
        ->and($ballot->code)->toBe('BAL-001')
        ->and($ballot->precinct->code)->toBe('PRECINCT-01')
        ->and($ballot->votes)->toHaveCount(2)
        ->and($this->store->ballots)->toHaveKey('BAL-001');
});

it('throws if precinct does not exist', function () {
    SubmitBallot::run('BAL-002', 'NONEXISTENT', $this->votes);
})->throws(RuntimeException::class, 'Precinct [NONEXISTENT] not found.');

it('stores vote data correctly', function () {
    $ballot = SubmitBallot::run('BAL-003', 'PRECINCT-01', $this->votes);

    $vote1 = $ballot->votes[0];
    $vote2 = $ballot->votes[1];

    expect($vote1->position->code)->toBe('PRESIDENT')
        ->and($vote1->candidates)->toHaveCount(1)
        ->and($vote2->position->code)->toBe('SENATOR')
        ->and($vote2->candidates[1]->alias)->toBe('PEDRO');
});
