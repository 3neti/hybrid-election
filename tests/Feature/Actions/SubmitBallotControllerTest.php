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
function sb_payload(string $precinctCode, string $positionCode, string|array $candidateCodes, string $code = 'BAL-001'): array {
    $list = is_array($candidateCodes) ? $candidateCodes : [$candidateCodes];
    return [
        'code'  => $code,
        'votes' => [[
            'position'   => ['code' => $positionCode],
            'candidates' => collect($list)->map(fn($c) => ['code' => $c])->all(),
        ]],
    ];
}

function sb_payload_multi(string $precinctCode, array $rows, string $code = 'BAL-ORD'): array {
    return [
        'code'  => $code,
        'votes' => collect($rows)->map(fn($r) => [
            'position'   => ['code' => $r[0]],
            'candidates' => collect((array) $r[1])->map(fn($c) => ['code' => $c])->all(),
        ])->all(),
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

it('treats different candidate order as the same ballot (200 on resubmit)', function () {
    $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

    // Make a multi-vote position (e.g., Senator) or use your existing $this->position if it has count >= 2
    $position = $this->position; // assume count >= 2 in fixtures
    $a = $this->candidates[0]->code;
    $b = $this->candidates[1]->code;

    // First create
    postJson('/api/ballots', sb_payload($this->precinct->code, $position->code, [$a, $b]), ['Accept' => 'application/json'])
        ->assertStatus(201);

    // Reuse same code but reversed candidate order → should be treated same (200)
    $payload = sb_payload($this->precinct->code, $position->code, [$b, $a]);
    postJson('/api/ballots', $payload, ['Accept' => 'application/json'])
        ->assertStatus(200);
});

it('treats different position order as the same ballot (200 on resubmit)', function () {
    $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
    $position = Position::factory()->create([
        'count' => 2,
    ]);

    $candidates = Candidate::factory()
        ->count(3)
        ->create([
            'position_code' => $position->code,
        ]);

    // Ensure we have two positions
    $posA = $this->position;
    $posB = \App\Models\Position::where('code', '!=', $posA->code)->firstOrFail();

    $candA = \App\Models\Candidate::where('position_code', $posA->code)->inRandomOrder()->value('code');
    $candB = \App\Models\Candidate::where('position_code', $posB->code)->inRandomOrder()->value('code');

    // Create with A then B
    $p1 = sb_payload_multi($this->precinct->code, [
        [$posA->code, [$candA]],
        [$posB->code, [$candB]],
    ]);
    postJson('/api/ballots', $p1, ['Accept' => 'application/json'])->assertStatus(201);

    // Same code but order B then A → 200
    $p2 = sb_payload_multi($this->precinct->code, [
        [$posB->code, [$candB]],
        [$posA->code, [$candA]],
    ]);
    postJson('/api/ballots', $p2, ['Accept' => 'application/json'])->assertStatus(200);
});

it('ignores extraneous fields in votes when computing the hash', function () {
    $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

    $base = sb_payload($this->precinct->code, $this->position->code, $this->candidates[0]->code);
    postJson('/api/ballots', $base, ['Accept' => 'application/json'])->assertStatus(201);

    // Add harmless extra fields; semantics must remain identical → 200
    $mut = $base;
    $mut['votes'][0]['position']['name'] = 'Any Extra Noise';
    $mut['votes'][0]['candidates'][0]['alias'] = 'NOOP';
    postJson('/api/ballots', $mut, ['Accept' => 'application/json'])->assertStatus(200);
});

it('treats code formatting differences as the same vote (200 idempotent)', function () {
    $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

    // Create original (ballot code = BAL-FMT)
    $firstPayload = sb_payload(
        $this->precinct->code,
        $this->position->code,
        $this->candidates[0]->code,
        'BAL-FMT'
    );

    $created = postJson('/api/ballots', $firstPayload, ['Accept' => 'application/json'])
        ->assertStatus(201)
        ->assertJsonStructure(['id', 'code', 'votes', 'precinct'])
        ->json();

    $firstId = $created['id'];

    // Same ballot code; tweak candidate code case + whitespace
    $mut = $firstPayload;
    $mut['votes'][0]['candidates'][0]['code'] = ' ' . strtolower($this->candidates[0]->code) . ' ';

    // Because computePayloadHash() normalizes, this is considered the same vote → 200, same id
    $second = postJson('/api/ballots', $mut, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure(['id', 'code', 'votes', 'precinct'])
        ->json();

    expect($second['id'])->toBe($firstId);
});

it('returns 409 when candidate set differs, even if overlapping', function () {
    $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

    $pos = $this->position;
    $a = $this->candidates[0]->code;
    $b = $this->candidates[1]->code;

    // Create with [a, b]
    postJson('/api/ballots', sb_payload($this->precinct->code, $pos->code, [$a, $b], 'BAL-SET'), ['Accept' => 'application/json'])
        ->assertStatus(201);

    // Reuse code with only [a] → conflict
    postJson('/api/ballots', sb_payload($this->precinct->code, $pos->code, [$a], 'BAL-SET'), ['Accept' => 'application/json'])
        ->assertStatus(409);
});

it('returns 409 when any position’s candidates change', function () {
    $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
    $position = Position::factory()->create([
        'count' => 2,
    ]);

    $candidates = Candidate::factory()
        ->count(3)
        ->create([
            'position_code' => $position->code,
        ]);

    $posA = $this->position;
    $posB = \App\Models\Position::where('code', '!=', $posA->code)->firstOrFail();

    $a1 = \App\Models\Candidate::where('position_code', $posA->code)->value('code');
    $b1 = \App\Models\Candidate::where('position_code', $posB->code)->value('code');
    $b2 = \App\Models\Candidate::where('position_code', $posB->code)->where('code', '!=', $b1)->value('code');

    // Create with A=a1, B=b1
    postJson('/api/ballots',
        sb_payload_multi($this->precinct->code, [
            [$posA->code, [$a1]],
            [$posB->code, [$b1]],
        ], 'BAL-MULTI'),
        ['Accept' => 'application/json']
    )->assertStatus(201);

    // Reuse code but change B → conflict
    postJson('/api/ballots',
        sb_payload_multi($this->precinct->code, [
            [$posA->code, [$a1]],
            [$posB->code, [$b2]],
        ], 'BAL-MULTI'),
        ['Accept' => 'application/json']
    )->assertStatus(409);
});

it('handles a larger ballot deterministically (idempotent resubmit → 200)', function () {
    $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

    // build a payload across several positions/candidates (adjust to your fixtures)
    $rows = \App\Models\Position::query()->take(4)->get()->map(function ($p) {
        $cand = \App\Models\Candidate::where('position_code', $p->code)->inRandomOrder()->limit(max(1, min(3, $p->count)))->pluck('code')->all();
        return [$p->code, $cand];
    })->all();

    $payload = sb_payload_multi($this->precinct->code, $rows, 'BAL-BIG');

    postJson('/api/ballots', $payload, ['Accept' => 'application/json'])->assertStatus(201);
    postJson('/api/ballots', $payload, ['Accept' => 'application/json'])->assertStatus(200);
});
