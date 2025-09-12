<?php

use TruthElection\Data\{CandidateData, ElectionReturnData, PositionData, PrecinctData, SignPayloadData, VoteData};
use TruthElection\Actions\{FinalizeElectionReturn, SignElectionReturn, SubmitBallot, GenerateElectionReturn};
use TruthElection\Enums\{ElectoralInspectorRole, Level};
use TruthElection\Tests\ResetsInMemoryElectionStore;
use TruthElection\Support\InMemoryElectionStore;
use Spatie\LaravelData\DataCollection;

uses(ResetsInMemoryElectionStore::class)->beforeEach(function () {
    $this->store = InMemoryElectionStore::instance();
    $this->store->reset();

    $this->precinct = PrecinctData::from([
        'id' => 'PR001',
        'code' => 'PRECINCT-01',
        'location_name' => 'City Hall',
        'latitude' => 14.5995,
        'longitude' => 120.9842,
        'electoral_inspectors' => [
            ['id' => 'A1', 'name' => 'Alice', 'role' => ElectoralInspectorRole::CHAIRPERSON],
            ['id' => 'B2', 'name' => 'Bob', 'role' => ElectoralInspectorRole::MEMBER],
        ]
    ]);

    $this->store->putPrecinct($this->precinct);

    $votes1 = collect([
        new VoteData(
            candidates: new DataCollection(CandidateData::class, [
                new CandidateData(code: 'CANDIDATE-001', name: 'Juan Dela Cruz', alias: 'JUAN', position: new PositionData(
                    code: 'PRESIDENT',
                    name: 'President of the Philippines',
                    level: Level::NATIONAL,
                    count: 1
                )),
            ])
        ),
        new VoteData(
            candidates: new DataCollection(CandidateData::class, [
                new CandidateData(code: 'CANDIDATE-002', name: 'Maria Santos', alias: 'MARIA', position: $position = new PositionData(
                    code: 'SENATOR',
                    name: 'Senator',
                    level: Level::NATIONAL,
                    count: 12
                )),
                new CandidateData(code: 'CANDIDATE-003', name: 'Pedro Reyes', alias: 'PEDRO', position: $position),
            ])
        ),
    ]);

    $votes2 = collect([
        new VoteData(
            candidates: new DataCollection(CandidateData::class, [
                new CandidateData(code: 'CANDIDATE-004', name: 'Jose Rizal', alias: 'JOSE', position: new PositionData(
                    code: 'PRESIDENT',
                    name: 'President of the Philippines',
                    level: Level::NATIONAL,
                    count: 1
                )),
            ])
        ),
        new VoteData(
            candidates: new DataCollection(CandidateData::class, [
                new CandidateData(code: 'CANDIDATE-002', name: 'Maria Santos', alias: 'MARIA', position: $position = new PositionData(
                    code: 'SENATOR',
                    name: 'Senator',
                    level: Level::NATIONAL,
                    count: 12
                )),
                new CandidateData(code: 'CANDIDATE-005', name: 'Andres Bonifacio', alias: 'ANDRES', position: $position),
            ])
        ),
    ]);

    SubmitBallot::run('BAL-001', 'PRECINCT-01', $votes1);
    SubmitBallot::run('BAL-002', 'PRECINCT-01', $votes2);

    $return = GenerateElectionReturn::run('PRECINCT-01');

    $action = app(SignElectionReturn::class);
    $action->handle(SignPayloadData::fromQrString('BEI:A1:sig1'), $return->code);
    $action->handle(SignPayloadData::fromQrString('BEI:B2:sig2'), $return->code);

    $this->return = $this->store->getElectionReturn($return->code);
});

test('finalize election return passes with complete signatures and valid content', function () {
    $result = FinalizeElectionReturn::run(
        precinctCode: $this->precinct->code,
        disk: 'local',
        payload: 'minimal',
        maxChars: 1200,
        dir: 'final',
        force: false
    );

    expect($result)->toBeInstanceOf(ElectionReturnData::class);
    expect($result->code)->toEqual($this->return->code);

    expect($result->precinct->code)->toEqual('PRECINCT-01');
    expect($result->precinct->location_name)->toBe('City Hall');

    expect($result->signatures)->toHaveCount(2)
        ->and($result->signedInspectors())->toHaveCount(2);

    expect($result->tallies)->not->toBeEmpty();
    expect($result->ballots)->not->toBeEmpty();

    $lastBallot = $result->with()['last_ballot'];
    expect($lastBallot)->not->toBeNull();
    expect($lastBallot)->toBeInstanceOf(\TruthElection\Data\BallotData::class);
});

test('finalize election return fails without required signatures', function () {
    $this->store->reset();

    $precinct = PrecinctData::from([
        'id' => 'PR002',
        'code' => 'PRECINCT-02',
        'location_name' => 'Plaza',
        'latitude' => 14.6,
        'longitude' => 121.0,
        'electoral_inspectors' => [
            ['id' => 'X1', 'name' => 'Xavier', 'role' => ElectoralInspectorRole::CHAIRPERSON],
            ['id' => 'Y2', 'name' => 'Yasmin', 'role' => ElectoralInspectorRole::MEMBER],
        ]
    ]);

    $this->store->putPrecinct($precinct);

    $votes = collect([
        new VoteData(
            candidates: new DataCollection(CandidateData::class, [
                new CandidateData(code: 'CANDIDATE-007', name: 'Luna Star', alias: 'LUNA', position:  new PositionData(
                    code: 'PRESIDENT',
                    name: 'President',
                    level: Level::NATIONAL,
                    count: 1
                )),
            ])
        )
    ]);

    SubmitBallot::run('BAL-003', 'PRECINCT-02', $votes);
    GenerateElectionReturn::run('PRECINCT-02');

    $action = new FinalizeElectionReturn();

    $action->handle(
        precinctCode: 'PRECINCT-02',
        disk: 'local',
        payload: 'minimal',
        maxChars: 1200,
        dir: 'final',
        force: false
    );
})->throws(RuntimeException::class, 'Missing required signatures');

test('finalize can be forced to bypass signature check', function () {
    $this->store->reset();

    $precinct = PrecinctData::from([
        'id' => 'PR004',
        'code' => 'PRECINCT-04',
        'location_name' => 'University Gym',
        'latitude' => 14.61,
        'longitude' => 121.03,
        'electoral_inspectors' => [
            ['id' => 'Z1', 'name' => 'Zoe', 'role' => ElectoralInspectorRole::CHAIRPERSON],
            ['id' => 'W2', 'name' => 'Wally', 'role' => ElectoralInspectorRole::MEMBER],
        ]
    ]);

    $this->store->putPrecinct($precinct);

    $votes = collect([
        new VoteData(
            candidates: new DataCollection(CandidateData::class, [
                new CandidateData(code: 'CANDIDATE-999', name: 'Gorio', alias: 'GORIO', position: new PositionData(
                    code: 'MAYOR',
                    name: 'Mayor',
                    level: Level::LOCAL,
                    count: 1
                )),
            ])
        )
    ]);

    SubmitBallot::run('BAL-005', 'PRECINCT-04', $votes);
    GenerateElectionReturn::run('PRECINCT-04');

    $final = FinalizeElectionReturn::run(
        precinctCode: 'PRECINCT-04',
        disk: 'local',
        payload: 'minimal',
        maxChars: 1200,
        dir: 'final',
        force: true
    );

    expect($final->signedInspectors())->toHaveCount(0);
    expect($final->signatures)->toHaveCount(2);
    expect($final->code)->toBeString(); // to ensure it got finalized
});

test('finalize fails when precinct is missing', function () {
    FinalizeElectionReturn::run(
        precinctCode: 'NON_EXISTENT',
        disk: 'local',
        payload: 'minimal',
        maxChars: 1200,
        dir: 'final',
        force: false
    );
})->throws(RuntimeException::class, 'Precinct [NON_EXISTENT] not found.');

test('finalize fails when election return is missing', function () {
    $this->store->putPrecinct(
        PrecinctData::from([
            'id' => 'PR404',
            'code' => 'PRECINCT-404',
            'location_name' => 'Nowhere',
            'latitude' => 0,
            'longitude' => 0,
            'electoral_inspectors' => [],
        ])
    );

    FinalizeElectionReturn::run(
        precinctCode: 'PRECINCT-404',
        disk: 'local',
        payload: 'minimal',
        maxChars: 1200,
        dir: 'final',
        force: false
    );
})->throws(RuntimeException::class, 'Election Return for [PRECINCT-404] not found.');

test('finalize fails when balloting is already closed and not forced', function () {
    $this->return->precinct->meta['balloting_open'] = false;
    $this->store->putPrecinct($this->return->precinct); // overwrite

    FinalizeElectionReturn::run(
        precinctCode: $this->return->precinct->code,
        disk: 'local',
        payload: 'minimal',
        maxChars: 1200,
        dir: 'final',
        force: false
    );
})->throws(RuntimeException::class, 'Balloting already closed. Nothing to do.');

test('finalize election return sets closed_at timestamp', function () {
    $code = $this->precinct->code;

    $result = FinalizeElectionReturn::run(
        precinctCode: $code,
        disk: 'local',
        payload: 'minimal',
        maxChars: 1200,
        dir: 'final',
        force: false
    );

    $precinct = $this->store->precincts[$code];

    expect($precinct->closed_at)->not->toBeNull()
        ->and(strtotime($precinct->closed_at))->toBeGreaterThan(0);
});
