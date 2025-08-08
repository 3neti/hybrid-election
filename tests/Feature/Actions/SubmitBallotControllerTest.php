<?php

use App\Models\{Precinct, Position, Candidate};
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create a precinct
    $this->precinct = Precinct::factory()->create();

    // Create a position with multiple candidates
    $this->position = Position::factory()->create([
        'count' => 2,
    ]);

    $this->candidates = Candidate::factory()
        ->count(3)
        ->create([
            'position_code' => $this->position->code,
        ]);
});

it('submits a ballot successfully via API', function () {
    $response = postJson(route('ballots.submit'), [
        'precinct_id' => $this->precinct->id,
        'code' => 'BAL-TEST-001',
        'votes' => [
            [
                'position' => [
                    'code' => $this->position->code,
                    'name' => $this->position->name,
                    'level' => $this->position->level->value,
                    'count' => $this->position->count,
                ],
                'candidates' => $this->candidates->map(fn ($c) => [
                    'code' => $c->code,
                    'name' => $c->name,
                    'alias' => $c->alias,
                ])->take(2)->values()->all(),
            ]
        ],
    ]);

    $response->assertCreated();
    $response->assertJson([
        'code' => 'BAL-TEST-001',
        'precinct' => [
            'id' => $this->precinct->id,
        ],
        'votes' => [
            [
                'position' => [
                    'code' => $this->position->code,
                ],
                'candidates' => [
                    ['code' => $this->candidates[0]->code],
                    ['code' => $this->candidates[1]->code],
                ],
            ],
        ],
    ]);

    // Ensure it was persisted in the database
    $this->assertDatabaseHas('ballots', [
        'code' => 'BAL-TEST-001',
        'precinct_id' => $this->precinct->id,
    ]);
});

it('submits a ballot successfully via API using json', function () {
    $payload = <<<PAYLOAD
{
    "precinct_id": "{$this->precinct->id}",
    "code": "BAL-TEST-001",
    "votes": [
        {
            "position": {
                "code": "{$this->position->code}",
                "name": "{$this->position->name}",
                "level": "district",
                "count": 2
            },
            "candidates": [
                {
                    "code": "{$this->candidates[0]->code}",
                    "name": "{$this->candidates[0]->name}",
                    "alias": "{$this->candidates[0]->alias}"
                },
                {
                    "code": "{$this->candidates[1]->code}",
                    "name": "{$this->candidates[1]->name}",
                    "alias": "{$this->candidates[1]->alias}"
                }
            ]
        }
    ]
}
PAYLOAD;

    $response = postJson(route('ballots.submit'), json_decode($payload, true));

    $response->assertCreated();

    $response->assertJson([
        'code' => 'BAL-TEST-001',
        'precinct' => [
            'id' => $this->precinct->id,
        ],
        'votes' => [
            [
                'position' => [
                    'code' => $this->position->code,
                ],
                'candidates' => [
                    ['code' => $this->candidates[0]->code],
                    ['code' => $this->candidates[1]->code],
                ],
            ],
        ],
    ]);

    $this->assertDatabaseHas('ballots', [
        'code' => 'BAL-TEST-001',
        'precinct_id' => $this->precinct->id,
    ]);
});

it('submits a ballot with only codes and hydrates server-side', function () {
    // minimal payload: only codes
    $payload = [
        'precinct_id' => $this->precinct->id,
        'code' => 'BAL-CODES-ONLY',
        'votes' => [
            [
                'position' => ['code' => $this->position->code],
                'candidates' => [
                    ['code' => $this->candidates[0]->code],
                    ['code' => $this->candidates[1]->code],
                ],
            ],
        ],
    ];

    $response = postJson(route('ballots.submit'), $payload);

    $response->assertCreated();

    // ✅ Response should be fully hydrated using DB values
    $response->assertJsonPath('code', 'BAL-CODES-ONLY')
        ->assertJsonPath('precinct.id', $this->precinct->id)
        ->assertJsonPath('votes.0.position.code', $this->position->code)
        ->assertJsonPath('votes.0.position.name', $this->position->name) // hydrated
        ->assertJsonPath('votes.0.position.level', $this->position->level->value) // hydrated
        ->assertJsonPath('votes.0.position.count', $this->position->count) // hydrated
        ->assertJsonPath('votes.0.candidates.0.code', $this->candidates[0]->code)
        ->assertJsonPath('votes.0.candidates.0.name', $this->candidates[0]->name) // hydrated
        ->assertJsonPath('votes.0.candidates.0.alias', $this->candidates[0]->alias) // hydrated
        ->assertJsonPath('votes.0.candidates.1.code', $this->candidates[1]->code);

    // ✅ Persisted
    $this->assertDatabaseHas('ballots', [
        'code' => 'BAL-CODES-ONLY',
        'precinct_id' => $this->precinct->id,
    ]);
});

it('fails with 422 when using unknown position/candidate codes', function () {
    $bad = [
        'precinct_id' => $this->precinct->id,
        'code' => 'BAL-BAD-CODES',
        'votes' => [
            [
                'position' => ['code' => 'NOT_A_REAL_POSITION'],
                'candidates' => [
                    ['code' => 'NOT_A_REAL_CANDIDATE'],
                ],
            ],
        ],
    ];

    $response = postJson(route('ballots.submit'), $bad);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'votes.0.position.code',
        'votes.0.candidates.0.code',
    ]);
});
