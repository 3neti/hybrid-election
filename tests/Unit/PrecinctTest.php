<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Data\ElectoralInspectorData;
use App\Enums\ElectoralInspectorRole;
use Illuminate\Support\Str;
use App\Data\PrecinctData;
use App\Models\Precinct;

uses(RefreshDatabase::class);

it('converts a Precinct model to PrecinctData and back', function () {
    // Arrange: Create a model instance
    $inspectors = collect([
        new ElectoralInspectorData(
            id: (string) Str::uuid(),
            name: 'Maria Santos',
            role: ElectoralInspectorRole::CHAIRPERSON,
        ),
        new ElectoralInspectorData(
            id: (string) Str::uuid(),
            name: 'Jose Dela Cruz',
            role: ElectoralInspectorRole::MEMBER,
        ),
        new ElectoralInspectorData(
            id: (string) Str::uuid(),
            name: 'Ana Reyes',
            role: ElectoralInspectorRole::MEMBER,
        ),
    ]);

    $precinct = Precinct::create([
        'code' => 'PRCT-001',
        'location_name' => 'Barangay Hall, San Juan',
        'latitude' => 14.5995,
        'longitude' => 120.9842,
        'electoral_inspectors' => $inspectors,
    ]);

    // Act: Convert to PrecinctData
    $data = $precinct->getData();

    // Assert: It is an instance of PrecinctData with correct fields
    expect($data)->toBeInstanceOf(PrecinctData::class)
        ->and($data->id)->toBe($precinct->id)
        ->and($data->code)->toBe('PRCT-001')
        ->and($data->location_name)->toBe('Barangay Hall, San Juan')
        ->and($data->latitude)->toBe(14.5995)
        ->and($data->longitude)->toBe(120.9842)
        ->and($data->electoral_inspectors)->toHaveCount(3)
        ->and($data->electoral_inspectors->first()->name)->toBe('Maria Santos')
        ->and($data->electoral_inspectors->first()->role)->toBe(ElectoralInspectorRole::CHAIRPERSON);
});

it('has precinct attributes', function () {
    tap(Precinct::factory()->create(), function (Precinct $precinct) {
        expect($precinct->watchers_count)->toBeNull();
        expect($precinct->precincts_count)->toBeNull();
        expect($precinct->registered_voters_count)->toBeNull();
        expect($precinct->actual_voters_count)->toBeNull();
        expect($precinct->ballots_in_box_count)->toBeNull();
        expect($precinct->unused_ballots_count)->toBeNull();
        expect($precinct->spoiled_ballots_count)->toBeNull();
        expect($precinct->void_ballots_count)->toBeNull();
        $precinct->watchers_count = 2;
        $precinct->precincts_count = 10;
        $precinct->registered_voters_count = 250;
        $precinct->actual_voters_count = 200;
        $precinct->ballots_in_box_count = 180;
        $precinct->unused_ballots_count = 20;
        $precinct->spoiled_ballots_count = 5;
        $precinct->void_ballots_count = 3;
    })->save();

    $precinct = Precinct::first();

    expect($precinct->watchers_count)->toBe(2);
    expect($precinct->precincts_count)->toBe(10);
    expect($precinct->registered_voters_count)->toBe(250);
    expect($precinct->actual_voters_count)->toBe(200);
    expect($precinct->ballots_in_box_count)->toBe(180);
    expect($precinct->unused_ballots_count)->toBe(20);
    expect($precinct->spoiled_ballots_count)->toBe(5);
    expect($precinct->void_ballots_count)->toBe(3);
});

/**
 * 1) Fresh precinct has all meta-style counters null by default
 */
it('has null defaults for additional precinct attributes', function () {
    $p = Precinct::factory()->create();

    expect($p->watchers_count)->toBeNull()
        ->and($p->precincts_count)->toBeNull()
        ->and($p->registered_voters_count)->toBeNull()
        ->and($p->actual_voters_count)->toBeNull()
        ->and($p->ballots_in_box_count)->toBeNull()
        ->and($p->unused_ballots_count)->toBeNull()
        ->and($p->spoiled_ballots_count)->toBeNull()
        ->and($p->void_ballots_count)->toBeNull();
});

/**
 * 2) Setting attributes writes to the meta bag via trait mutators
 */
it('persists additional attributes via setters', function () {
    $p = Precinct::factory()->create();

    $p->watchers_count = 2;
    $p->precincts_count = 10;
    $p->registered_voters_count = 250;
    $p->actual_voters_count = 200;
    $p->ballots_in_box_count = 180;
    $p->unused_ballots_count = 20;
    $p->spoiled_ballots_count = 5;
    $p->void_ballots_count = 3;
    $p->save();

    $p->refresh();

    expect($p->watchers_count)->toBe(2)
        ->and($p->precincts_count)->toBe(10)
        ->and($p->registered_voters_count)->toBe(250)
        ->and($p->actual_voters_count)->toBe(200)
        ->and($p->ballots_in_box_count)->toBe(180)
        ->and($p->unused_ballots_count)->toBe(20)
        ->and($p->spoiled_ballots_count)->toBe(5)
        ->and($p->void_ballots_count)->toBe(3);
});

/**
 * 3) Factory state seeds sensible defaults and allows overrides
 */
it('factory state withPrecinctMeta seeds values and accepts overrides', function () {
    // Use your state defaults; override one to prove it works.
    $p = Precinct::factory()
        ->withPrecinctMeta(['unused_ballots_count' => 99])
        ->create();

    expect($p->watchers_count)->toBe(2)
        ->and($p->precincts_count)->toBe(10)
        ->and($p->registered_voters_count)->toBe(250)
        ->and($p->actual_voters_count)->toBe(200)
        ->and($p->ballots_in_box_count)->toBe(180)
        ->and($p->unused_ballots_count)->toBe(99) // overridden
        ->and($p->spoiled_ballots_count)->toBe(5)
        ->and($p->void_ballots_count)->toBe(3);
});
