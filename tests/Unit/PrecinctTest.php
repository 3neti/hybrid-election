<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Data\PrecinctData;
use App\Models\Precinct;

uses(RefreshDatabase::class);

it('converts a Precinct model to PrecinctData and back', function () {
    // Arrange: Create a model instance
    $precinct = Precinct::create([
        'code'           => 'PRCT-001',
        'location_name'  => 'Barangay Hall, San Juan',
        'latitude'       => 14.5995,
        'longitude'      => 120.9842,
        'chairman_name'  => 'Maria Santos',
        'member1_name'   => 'Jose Dela Cruz',
        'member2_name'   => 'Ana Reyes',
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
        ->and($data->chairman_name)->toBe('Maria Santos')
        ->and($data->member1_name)->toBe('Jose Dela Cruz')
        ->and($data->member2_name)->toBe('Ana Reyes');

//    // Act again: Convert back to array and assert equality
//    $array = $data->toArray();
//    expect($array)->toMatchArray([
//        'id'             => $precinct->id,
//        'code'           => 'PRCT-001',
//        'location_name'  => 'Barangay Hall, San Juan',
//        'latitude'       => 14.5995,
//        'longitude'      => 120.9842,
//        'chairman_name'  => 'Maria Santos',
//        'member1_name'   => 'Jose Dela Cruz',
//        'member2_name'   => 'Ana Reyes',
//    ]);
});
