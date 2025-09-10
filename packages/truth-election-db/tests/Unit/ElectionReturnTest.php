<?php

use TruthElectionDb\Models\{Ballot, Precinct, ElectionReturn};
use TruthElection\Data\{ElectionReturnData, PrecinctData};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\LaravelData\DataCollection;

uses(RefreshDatabase::class);

test('election return has attributes', function () {
    $er = ElectionReturn::factory()->create();
    expect($er)->toBeInstanceOf(ElectionReturn::class);
    expect($er->id)->toBeUuid();
    expect($er->signatures)->toBeArray();
    expect($er->signatures)->toBeEmpty();
    expect($er->ballots)->toBeArray();
    expect($er->ballots)->toBeEmpty();
    expect($er->tallies)->toBeArray();
    expect($er->tallies)->toBeEmpty();
});

test('election return has precinct relation but cast as array', function () {
    $er = ElectionReturn::factory()->forPrecinct()->create();
    expect($er->belongsTo(Precinct::class, 'precinct_code', 'code')->getResults())->toBeInstanceOf(Precinct::class);
    expect($er->precinct)->toBeArray();
    expect($er->precinct)->toMatchArray(Precinct::factory()->definition());
});

test('election return has ballots and tallies', function () {
    $er = ElectionReturn::factory()->forPrecinct()->create();
    expect($er->ballots)->toBeArray();
    expect($er->ballots)->toHaveCount(0);
    expect($er->tallies)->toBeArray();
    expect($er->tallies)->toHaveCount(0);
    $ballots = Ballot::factory(2)
        ->state(['precinct_code' => $er->precinct['code']])
        ->create();
    expect($er->ballots)->toHaveCount(2);
    expect($er->ballots)->toMatchArray($ballots->toArray());
    expect($er->tallies)->toBeGreaterThan(0);
    expect($er->tallies)->toMatchArray($er->precinct['tallies']);
});

test('election return has signatures', function () {
    $er = ElectionReturn::factory()->withSignatures()->create();
    expect($er->signatures)->toMatchArray(ElectionReturn::factory()->signatures());

});

test('election return has a dataClass', function () {
    $precinct = Precinct::factory()->withPrecinctMeta()->create();

    $er = ElectionReturn::factory()
        ->withSignatures()
        ->create(['precinct_code' => $precinct->code]);

    Ballot::factory(2)
        ->state(['precinct_code' => $er->precinct['code']])
        ->create();

    $data = $er->getData();

    expect($data)->toBeInstanceOf(ElectionReturnData::class);
    expect($data->precinct)->toBeInstanceOf(PrecinctData::class);
    expect($data->tallies)->toBeInstanceOf(DataCollection::class);
    expect($data->signatures)->toBeInstanceOf(DataCollection::class);
    expect($data->ballots)->toBeInstanceOf(DataCollection::class);
    //TODO: test the values of each property on each of the DTO
});

it('can be created with a valid precinct', function () {
    $precinct = Precinct::factory()->create([
        'code' => 'PR-001',
        'location_name' => 'Sample Precinct',
    ]);

    $electionReturn = ElectionReturn::factory()->create([
        'code' => 'ER-001',
        'precinct_code' => $precinct->code,
        'signatures' => [],
    ]);

    expect($electionReturn)->toBeInstanceOf(ElectionReturn::class);
    expect($electionReturn->precinct)->toBeArray();
    expect($electionReturn->precinct['code'])->toEqual('PR-001');
});

it('can accept a precinct object in setPrecinctAttribute', function () {
    $precinct = Precinct::factory()->create();

    $electionReturn = new ElectionReturn([
        'code' => 'ER-002',
        'signatures' => [],
    ]);

    $electionReturn->setAttribute('precinct', $precinct);

    expect($electionReturn->precinct_code)->toEqual($precinct->code);
});

it('can accept a precinct code in setPrecinctAttribute', function () {
    $electionReturn = new ElectionReturn([
        'code' => 'ER-003',
        'signatures' => [],
    ]);

    $electionReturn->setAttribute('precinct', 'PR-CUSTOM');

    expect($electionReturn->precinct_code)->toEqual('PR-CUSTOM');
});

it('returns empty array if precinct is missing', function () {
    $electionReturn = new ElectionReturn([
        'code' => 'ER-004',
        'precinct_code' => 'NON-EXISTENT',
        'signatures' => [],
    ]);

    expect($electionReturn->precinct)->toBeArray()->toBeEmpty();
    expect($electionReturn->tallies)->toBeArray()->toBeEmpty();
    expect($electionReturn->ballots)->toBeArray()->toBeEmpty();
});
