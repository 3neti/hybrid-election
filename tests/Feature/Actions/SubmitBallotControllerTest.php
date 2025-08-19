<?php

use Illuminate\Routing\Middleware\ThrottleRequests;
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

/**
 * Minimal, valid ballot payload builder.
 * Only PRESIDENT is used for speed.
 */
function sb_payload(string $code, string $positionCode, string $candidateCode): array {
    return [
        'code'  => $code,
        'votes' => [
            [
                'position'   => ['code' => $positionCode],
                'candidates' => [
                    ['code' => $candidateCode],
                ],
            ],
        ],
    ];
}

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


it('returns 200 and the same ballot on exact resubmission (same code + same votes)', function () {
    // Keep the test fast & deterministic
    $this->withoutMiddleware(ThrottleRequests::class);

    // First request should create (201)
    $first = postJson('/api/ballots', sb_payload($this->precinct->code, $this->position->code, $this->candidates[0]->code), ['Accept' => 'application/json'])
        ->assertStatus(201)
        ->assertJsonStructure(['id', 'code', 'votes', 'precinct']);

    $firstId   = $first->json('id');
    $firstCode = $first->json('code');

    // Second request with the *same* payload should return 200 and the *same* ballot id
    $second = postJson('/api/ballots', sb_payload($this->precinct->code, $this->position->code, $this->candidates[0]->code), ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure(['id', 'code', 'votes', 'precinct']);

    expect($second->json('id'))->toBe($firstId);
    expect($second->json('code'))->toBe($firstCode);
});

it('returns 409 when a ballot code is reused with a different vote hash', function () {
    $this->withoutMiddleware(ThrottleRequests::class);

    // Create the original ballot
    postJson('/api/ballots', sb_payload($this->precinct->code, $this->position->code, $this->candidates[0]->code), ['Accept' => 'application/json'])
        ->assertStatus(201);

    // Reuse the same code but change the candidate → should be a conflict (409)
   postJson('/api/ballots', sb_payload($this->precinct->code, $this->position->code, $this->candidates[1]->code), ['Accept' => 'application/json'])
        ->assertStatus(409)
        ->assertJsonStructure([
            'message',
//            'code',
//            'existing' => ['id', 'code', 'votes', 'precinct'],
        ])
    ;
});

it('treats different vote ordering as the same ballot (idempotent)', function () {
    $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

    // Build a two-candidate vote for the same position
    $a = sb_payload($this->precinct->code, $this->position->code, $this->candidates[0]->code);
    // add second candidate
    $a['votes'][0]['candidates'][] = ['code' => $this->candidates[1]->code];

    // Submit #1 → create
    postJson('/api/ballots', $a, ['Accept' => 'application/json'])
        ->assertStatus(201);

    // Reorder candidates (hash should normalize/ignore order)
    $b = $a;
    $b['votes'][0]['candidates'] = array_reverse($b['votes'][0]['candidates']);

    // Submit #2 with same code → 200 (same ballot)
    postJson('/api/ballots', $b, ['Accept' => 'application/json'])
        ->assertStatus(200);
});

it('ignores duplicate candidate entries in the vote hash', function () {
    $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

    $base = sb_payload($this->precinct->code, $this->position->code, $this->candidates[0]->code);

    // Create
    postJson('/api/ballots', $base, ['Accept' => 'application/json'])
        ->assertStatus(201);

    // Same code but with duplicated candidate item (hash should de-dupe)
    $dupe = $base;
    $dupe['votes'][0]['candidates'][] = ['code' => $this->candidates[0]->code];

    postJson('/api/ballots', $dupe, ['Accept' => 'application/json'])
        ->assertStatus(200);
});

it('does not consider non-semantic fields in the hash (e.g., candidate name)', function () {
    $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

    $base = sb_payload($this->precinct->code, $this->position->code, $this->candidates[0]->code);

    postJson('/api/ballots', $base, ['Accept' => 'application/json'])
        ->assertStatus(201);

    // Same code, same candidate codes, but with extra non-semantic fields
    $noisy = $base;
    $noisy['votes'][0]['position']['name'] = 'Some Fancy Title';
    $noisy['votes'][0]['candidates'][0]['name'] = 'Does Not Matter';

    postJson('/api/ballots', $noisy, ['Accept' => 'application/json'])
        ->assertStatus(200);
});

it('409 conflict includes a helpful message', function () {
    $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

    postJson('/api/ballots', sb_payload($this->precinct->code, $this->position->code, $this->candidates[0]->code), ['Accept' => 'application/json'])
        ->assertStatus(201);

    postJson('/api/ballots', sb_payload($this->precinct->code, $this->position->code, $this->candidates[1]->code), ['Accept' => 'application/json'])
        ->assertStatus(409)
        ->assertJsonStructure(['message']);
});
