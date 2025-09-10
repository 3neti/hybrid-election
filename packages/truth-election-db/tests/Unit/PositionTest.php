<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use TruthElectionDb\Models\Position;
use TruthElection\Enums\Level;

uses(RefreshDatabase::class);

test('it can create a position model via factory', function () {
    $position = Position::factory()->create([
        'code' => 'MAYOR',
        'name' => 'City Mayor',
        'level' => Level::LOCAL,
        'count' => 1,
    ]);

    expect($position)->toBeInstanceOf(Position::class)
        ->and($position->code)->toBe('MAYOR')
        ->and($position->name)->toBe('City Mayor')
        ->and($position->level)->toBe(Level::LOCAL)
        ->and($position->count)->toBe(1);
});

test('it uses string as non-incrementing primary key', function () {
    $position = Position::factory()->make(['code' => 'GOVERNOR']);

    expect($position->getKeyName())->toBe('code')
        ->and($position->getKey())->toBe('GOVERNOR')
        ->and($position->incrementing)->toBeFalse()
        ->and($position->getKeyType())->toBe('string');
});

test('it casts level to Level enum', function () {
    $position = new Position([
        'code' => 'COUNCILOR',
        'name' => 'City Councilor',
        'level' => 'local',
        'count' => 8,
    ]);

    expect($position->level)->toBeInstanceOf(Level::class)
        ->and($position->level)->toEqual(Level::LOCAL);
});

test('it transforms to PositionData via getData', function () {
    $position = Position::factory()->create([
        'code' => 'REP',
        'name' => 'Representative',
        'level' => Level::LOCAL,
        'count' => 1,
    ]);

    $data = $position->getData();

    expect($data)->toBeInstanceOf(\TruthElection\Data\PositionData::class)
        ->and($data->code)->toBe('REP')
        ->and($data->name)->toBe('Representative')
        ->and($data->level)->toBe(Level::LOCAL)
        ->and($data->count)->toBe(1);
});
