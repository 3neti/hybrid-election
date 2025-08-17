<?php

use Illuminate\Testing\TestResponse;
use App\Models\Precinct;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\assertDatabaseHas;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Minimal user to satisfy authorize() => (bool) $request->user()
    $this->user = User::factory()->create();
    $this->precinct = Precinct::factory()->create(); // uses your updated factory
});

function updateStats(Precinct $precinct, array $payload): TestResponse {
    return patchJson(route('precinct.statistics.update', $precinct), $payload);
}

it('updates a subset of precinct statistics (partial PATCH)', function () {
    actingAs($this->user);

    $payload = [
        'watchers_count'            => 3,
        'registered_voters_count'   => 250,
        // leave others out → should remain unchanged
    ];

    $res = updateStats($this->precinct, $payload);
    $res->assertOk();

    // Response returns PrecinctData DTO — assert values in JSON
    $res->assertJsonPath('id', $this->precinct->id);
    $res->assertJsonPath('watchers_count', 3);
    $res->assertJsonPath('registered_voters_count', 250);

    // Non-provided keys should be present (DTO) but unchanged/null by default
    $res->assertJsonPath('precincts_count', null);
    $res->assertJsonPath('actual_voters_count', null);
    $res->assertJsonPath('ballots_in_box_count', null);
    $res->assertJsonPath('unused_ballots_count', null);
    $res->assertJsonPath('spoiled_ballots_count', null);
    $res->assertJsonPath('void_ballots_count', null);

    // Optionally verify persisted state by reloading the model
    $this->precinct->refresh();
    expect($this->precinct->watchers_count)->toBe(3);
    expect($this->precinct->registered_voters_count)->toBe(250);
    expect($this->precinct->precincts_count)->toBeNull();
});

it('accepts null to clear a previously set value', function () {
    actingAs($this->user);

    // First set a value
    $first = updateStats($this->precinct, ['unused_ballots_count' => 5]);
    $first->assertOk()->assertJsonPath('unused_ballots_count', 5);

    // Then clear it
    $second = updateStats($this->precinct, ['unused_ballots_count' => null]);
    $second->assertOk()->assertJsonPath('unused_ballots_count', null);//error here

    $this->precinct->refresh();
    expect($this->precinct->unused_ballots_count)->toBeNull();
});

it('rejects negative integers via validation', function () {
    actingAs($this->user);

    $res = updateStats($this->precinct, [
        'ballots_in_box_count' => -1,
        'spoiled_ballots_count' => -5,
    ]);

    $res->assertStatus(422);
    $res->assertJsonValidationErrors(['ballots_in_box_count', 'spoiled_ballots_count']);
});

it('updates all supported statistic fields at once', function () {
    actingAs($this->user);

    $payload = [
        'watchers_count'             => 2,
        'precincts_count'            => 7,
        'registered_voters_count'    => 310,
        'actual_voters_count'        => 298,
        'ballots_in_box_count'       => 302,
        'unused_ballots_count'       => 4,
        'spoiled_ballots_count'      => 2,
        'void_ballots_count'         => 1,
    ];

    $res = updateStats($this->precinct, $payload);
    $res->assertOk();

    foreach ($payload as $k => $v) {
        $res->assertJsonPath($k, $v);
    }

    $this->precinct->refresh();
    foreach ($payload as $k => $v) {
        expect($this->precinct->{$k})->toBe($v);
    }
});

it('requires authentication (per authorize)', function () {
    // no actingAs()
    $res = updateStats($this->precinct, ['watchers_count' => 1]);

    // Adjust if you return 403 instead of 401 depending on middleware setup
    $res->assertStatus(302)->assertRedirect(); // if web guard redirects to login
    // or: $res->assertStatus(401);
    // or: $res->assertStatus(403);
})->skip();
