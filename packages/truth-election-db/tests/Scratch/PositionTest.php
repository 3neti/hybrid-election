<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use TruthElectionDb\Models\Position;

uses(RefreshDatabase::class, WithFaker::class);

test('position has attributes', function () {
    $position = Position::factory()->create();
    expect($position)->toBeInstanceOf(Position::class);
    expect($position->code)->toBeString();
    expect($position->name)->toBeString();
    expect($position->level)->toBeInstanceOf(\TruthElection\Enums\Level::class);
    expect($position->count)->toBeInt();
});
