<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use App\Models\Position;

uses(RefreshDatabase::class, WithFaker::class);

test('position has attributes', function () {
    $position = Position::factory()->create();
    expect($position)->toBeInstanceOf(Position::class);
    expect($position->code)->toBeString();
    expect($position->name)->toBeString();
    expect($position->level)->toBeInstanceOf(\App\Enums\Level::class);
    expect($position->count)->toBeInt();
});
