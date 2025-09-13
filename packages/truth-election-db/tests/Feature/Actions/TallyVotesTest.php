<?php

use TruthElectionDb\Models\{ElectionReturn, Precinct};
use TruthElectionDb\Actions\{CastBallot, TallyVotes};
use Illuminate\Foundation\Testing\RefreshDatabase;
use TruthElection\Support\ElectionStoreInterface;
use TruthElectionDb\Tests\ResetsElectionStore;
use Spatie\LaravelData\DataCollection;
use TruthElection\Enums\Level;
use TruthElection\Data\{
    ElectionReturnData,
    CandidateData,
    PrecinctData,
    PositionData,
    VoteData
};

uses(ResetsElectionStore::class, RefreshDatabase::class)->beforeEach(function () {
    $this->resetElectionStore();

    $this->store = app(ElectionStoreInterface::class);

    $this->precinct = PrecinctData::from([
        'id' => 'PR001',
        'code' => 'PRECINCT-01',
        'location_name' => 'City Hall',
        'latitude' => 14.5995,
        'longitude' => 120.9842,
        'electoral_inspectors' => [],
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

    $votes3 = collect([
        new VoteData(
            candidates: new DataCollection(CandidateData::class, [
                new CandidateData(code: 'CANDIDATE-006', name: 'Emilio Aguinaldo', alias: 'EMILIO', position: $position = new PositionData(
                    code: 'SENATOR',
                    name: 'Senator',
                    level: Level::NATIONAL,
                    count: 12
                )),
                // 12+1 = 13 senators (overvote), should be rejected
                new CandidateData(code: 'CANDIDATE-007', name: 'Apolinario Mabini', alias: 'APO', position: $position),
                new CandidateData(code: 'CANDIDATE-008', name: 'Gregorio del Pilar', alias: 'GREG', position: $position),
                new CandidateData(code: 'CANDIDATE-009', name: 'Melchora Aquino', alias: 'TANDANG', position: $position),
                new CandidateData(code: 'CANDIDATE-010', name: 'Antonio Luna', alias: 'TONIO', position: $position),
                new CandidateData(code: 'CANDIDATE-011', name: 'Marcelo del Pilar', alias: 'CEL', position: $position),
                new CandidateData(code: 'CANDIDATE-012', name: 'Diego Silang', alias: 'DIEGO', position: $position),
                new CandidateData(code: 'CANDIDATE-013', name: 'Gabriela Silang', alias: 'GABRIELA', position: $position),
                new CandidateData(code: 'CANDIDATE-014', name: 'Francisco Baltazar', alias: 'BALTAZAR', position: $position),
                new CandidateData(code: 'CANDIDATE-015', name: 'Leona Florentino', alias: 'LEONA', position: $position),
                new CandidateData(code: 'CANDIDATE-016', name: 'Josefa Llanes Escoda', alias: 'JOSEFA', position: $position),
                new CandidateData(code: 'CANDIDATE-017', name: 'Manuel Quezon', alias: 'QUEZON', position: $position),
                new CandidateData(code: 'CANDIDATE-018', name: 'Sergio Osmeña', alias: 'OSMENA', position: $position),
            ])
        ),
    ]);

    CastBallot::run('BAL-001', 'PRECINCT-01', $votes1);
    CastBallot::run('BAL-002', 'PRECINCT-01', $votes2);
    CastBallot::run('BAL-003', 'PRECINCT-01', $votes3); // should be rejected due to overvote
});

it('persists election return via handle()', function () {
    $action = app(TallyVotes::class);

    // Resolve the actual Precinct model from the code
    $precinct = Precinct::where('code', 'PRECINCT-01')->first();

    expect($precinct)->not->toBeNull();

    // Act
    $er = $action->run($precinct->code);

    // Assert the returned object
    expect($er)->toBeInstanceOf(ElectionReturnData::class)
        ->and($er->precinct->code)->toBe('PRECINCT-01')
        ->and($er->tallies)->toHaveCount(5) // 2 presidents + 3 valid senators
        ->and($er->ballots)->toHaveCount(3);

    // Assert the return was persisted in DB
    $persisted = ElectionReturn::where('code', $er->code)->first();

    expect($persisted)->not->toBeNull()
        ->and($persisted->precinct_code)->toBe($precinct->code);
});

it('returns a valid election return via controller', function () {
    $response = $this->postJson(route('votes.tally', [
        'precinct_code' => 'PRECINCT-01',
    ]));

    $response->assertOk();

    $data = $response->json();

    expect($data)->toHaveKeys([
        'code',
        'precinct',
        'ballots',
        'tallies',
    ])
        ->and($data['precinct']['code'])->toBe('PRECINCT-01')
        ->and($data['ballots'])->toHaveCount(3)
        ->and($data['tallies'])->toBeArray();

    // One should be invalid

    $tallyCollection = collect($data['tallies']);

    $presidents = $tallyCollection->where('position_code', 'PRESIDENT');
    expect($presidents)->toHaveCount(2); // 2 president votes

    $senators = $tallyCollection->where('position_code', 'SENATOR');
    expect($senators)->toHaveCount(3); // only valid senator votes from BAL-001 and BAL-002
});

it('validates missing precinct_code field', function () {
    $response = $this->postJson(route('votes.tally', []));

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['precinct_code']);
});
