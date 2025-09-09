<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use TruthElectionDb\Models\{Candidate,Position};

uses(RefreshDatabase::class, WithFaker::class);

test('candidate has attributes', function () {
    $candidate = Candidate::factory()->forPosition()->create();
    expect($candidate)->toBeInstanceOf(Candidate::class);
    expect($candidate->position)->toBeInstanceOf(Position::class);
    expect($candidate->code)->toBeString();
    expect($candidate->name)->toBeString();
    expect($candidate->alias)->toBeString();
});

test('candidate has position setter', function () {
    $candidate = Candidate::factory()->make();
    $position = Position::factory()->create();
    $candidate->position = $position;
    $candidate->save();
    expect($candidate->position)->toBeInstanceOf(Position::class);
    expect($candidate->position->is($position))->toBeTrue();
});
