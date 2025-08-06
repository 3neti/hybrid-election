<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Data\PrecinctData;
use App\Models\Precinct;
use App\Data\ElectoralInspectorData;
use App\Enums\ElectoralInspectorRole;
use Illuminate\Support\Str;

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
