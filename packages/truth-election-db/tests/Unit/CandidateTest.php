<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use TruthElectionDb\Models\{Candidate, Position};
use TruthElection\Data\CandidateData;
use TruthElection\Enums\Level;


uses(RefreshDatabase::class);

it('creates a candidate using factory', function () {
    $candidate = Candidate::factory()->create();

    expect($candidate)->toBeInstanceOf(Candidate::class);
    expect($candidate->getKey())->toEqual($candidate->code);
});

it('sets position_code via setPositionAttribute with Position model', function () {
    $position = Position::factory()->create(['code' => 'MAYOR']);
    $candidate = new Candidate([
        'code' => 'CAND-001',
        'name' => 'John Doe',
        'alias' => 'JOHN',
    ]);

    $candidate->setAttribute('position',  $position);

    expect($candidate->position_code)->toEqual('MAYOR');
});

it('sets position_code via setPositionAttribute with string', function () {
    $candidate = new Candidate([
        'code' => 'CAND-002',
        'name' => 'Jane Doe',
        'alias' => 'JANE',
    ]);

    $candidate->setAttribute('position',  'GOVERNOR');

    expect($candidate->position_code)->toEqual('GOVERNOR');
});

it('returns position as array from getPositionAttribute', function () {
    $position = Position::factory()->create([
        'code' => 'PRES',
        'name' => 'President',
        'level' => Level::NATIONAL,
        'count' => 1,
    ]);

    $candidate = Candidate::factory()->create([
        'position_code' => 'PRES',
    ]);

    $array = $candidate->position;

    expect($array)->toBeArray();
    expect($array['code'])->toEqual('PRES');
    expect($array['name'])->toEqual('President');
});

it('returns data object via getData()', function () {
    $position = Position::factory()->create(['code' => 'VICE']);
    $candidate = Candidate::factory()->create([
        'code' => 'CAND-123',
        'name' => 'Vice Lord',
        'alias' => 'VICE',
        'position_code' => 'VICE',
    ]);

    $data = $candidate->getData();

    expect($data)->toBeInstanceOf(CandidateData::class);
    expect($data->code)->toEqual('CAND-123');
    expect($data->alias)->toEqual('VICE');
});
