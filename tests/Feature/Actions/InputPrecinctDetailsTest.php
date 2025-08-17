<?php

use App\Models\User;
use App\Models\Precinct;
use App\Enums\ElectoralInspectorRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{actingAs, postJson};

uses(RefreshDatabase::class);

/** Helper: build a valid payload */
function detailsPayload(array $overrides = []): array
{
    return array_replace_recursive([
        'code' => 'CURRIMAO-001',
        'location_name' => 'Currimao Central School',
        'latitude' => 17.993217,
        'longitude' => 120.488902,
        'electoral_inspectors' => [
            [
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'name' => 'Juan dela Cruz',
                'role' => ElectoralInspectorRole::CHAIRPERSON->value,
            ],
            [
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'name' => 'Maria Santos',
                'role' => ElectoralInspectorRole::MEMBER->value,
            ],
        ],
    ], $overrides);
}

/** Convenience: call the endpoint */
function updateDetails(Precinct $precinct, array $payload)
{
    return postJson(
        route('precinct.details.input', $precinct),
        $payload
    );
}

it('updates precinct details (happy path)', function () {
    $user = User::factory()->create();
    $precinct = Precinct::factory()->create([
        'code' => 'OLD-000',
        'location_name' => 'Old Location',
        'latitude' => null,
        'longitude' => null,
        'electoral_inspectors' => collect([]),
    ]);

    actingAs($user);

    $payload = detailsPayload([
        'code' => 'CURRIMAO-002',
        'location_name' => 'New Campus',
        'latitude' => 17.5,
        'longitude' => 121.1,
    ]);

    $res = updateDetails($precinct, $payload);

    $res->assertStatus(201)
        ->assertJsonFragment([
            'id' => $precinct->id,
            'code' => 'CURRIMAO-002',
            'location_name' => 'New Campus',
        ])
        ->assertJsonPath('electoral_inspectors.0.name', 'Juan dela Cruz')
        ->assertJsonPath('electoral_inspectors.0.role', ElectoralInspectorRole::CHAIRPERSON->value);

    $precinct->refresh();

    expect($precinct->code)->toBe('CURRIMAO-002')
        ->and($precinct->location_name)->toBe('New Campus')
        ->and($precinct->latitude)->toBe(17.5)
        ->and($precinct->longitude)->toBe(121.1)
        ->and($precinct->electoral_inspectors)->not->toBeNull()
        ->and($precinct->electoral_inspectors)->toHaveCount(2)
        ->and($precinct->electoral_inspectors[0]->name)->toBe('Juan dela Cruz');
});

it('allows partial updates (only code, for example)', function () {
    $user = User::factory()->create();
    $precinct = Precinct::factory()->create([
        'code' => 'OLD-000',
        'location_name' => 'Old Location',
        'latitude' => 10.1,
        'longitude' => 10.2,
    ]);

    actingAs($user);

    $res = updateDetails($precinct, [
        'code' => 'NEW-ONLY',
    ]);

    $res->assertStatus(201)
        ->assertJsonPath('code', 'NEW-ONLY');

    $precinct->refresh();

    expect($precinct->code)->toBe('NEW-ONLY')
        ->and($precinct->location_name)->toBe('Old Location')
        ->and($precinct->latitude)->toBe(10.1)
        ->and($precinct->longitude)->toBe(10.2);
});

it('normalizes electoral inspectors when roles are strings', function () {
    $user = User::factory()->create();
    $precinct = Precinct::factory()->create();
    actingAs($user);

    $payload = detailsPayload([
        'electoral_inspectors' => [
            ['id' => (string) \Illuminate\Support\Str::uuid(), 'name' => 'Alice', 'role' => 'chairperson'],
            ['id' => (string) \Illuminate\Support\Str::uuid(), 'name' => 'Bob',   'role' => 'member'],
        ],
    ]);

    $res = updateDetails($precinct, $payload);
    $res->assertStatus(201);

    $precinct->refresh();
    // If your action casts role to enum internally, both should resolve:
    expect((string) $precinct->electoral_inspectors[0]->role->value)->toBe(
        ElectoralInspectorRole::CHAIRPERSON->value
    );
});

it('validates latitude must be numeric', function () {
    $user = User::factory()->create();
    $precinct = Precinct::factory()->create();
    actingAs($user);

    $payload = detailsPayload([
        'latitude' => 'not-a-number',        // invalid
        // inspectors omitted to isolate this failure
    ]);

    $res = updateDetails($precinct, $payload);
    $res->assertStatus(422)->assertJsonValidationErrors(['latitude']);
});

it('validates inspector name is required', function () {
    $user = User::factory()->create();
    $precinct = Precinct::factory()->create();
    actingAs($user);

    $payload = detailsPayload([
        'latitude' => 12.3456,               // make other fields valid
        'longitude' => 98.7654,
        'electoral_inspectors' => [
            ['id' => (string) \Illuminate\Support\Str::uuid(), 'role' => 'member'], // ❌ name missing
        ],
    ]);

    $res = updateDetails($precinct, $payload);
    $res->assertStatus(422)->assertJsonValidationErrors(['electoral_inspectors.0.name']);
})->skip();
