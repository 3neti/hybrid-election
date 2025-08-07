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
