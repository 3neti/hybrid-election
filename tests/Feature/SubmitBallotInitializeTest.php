<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\{Position, Candidate, Precinct, Ballot};
use function Pest\Laravel\postJson;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Routing\Middleware\ThrottleRequests;

uses(RefreshDatabase::class);

/** Small helpers to write ad-hoc config files the middleware will read. */
function sb_writeElectionJson(string $dir, array $data): string
{
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    $path = $dir . '/election.json';
    File::put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return $path;
}

function sb_writePrecinctYaml(string $dir, array $data): string
{
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    $path = $dir . '/precinct.yaml';
    File::put($path, Yaml::dump($data, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK));
    return $path;
}

beforeEach(function () {
    // Ensure *completely* empty tables
    Position::query()->delete();
    Candidate::query()->delete();
    Precinct::query()->delete();
    Ballot::query()->delete();

    // Disable throttle middleware so multiple posts don’t rate-limit the test
    $this->withoutMiddleware(ThrottleRequests::class);

    $this->tmpDir = base_path('tests/TempBallotInit');
    if (!is_dir($this->tmpDir)) mkdir($this->tmpDir, 0777, true);
});

afterEach(function () {
    if (is_dir($this->tmpDir)) {
        collect(File::allFiles($this->tmpDir))->each(fn($f) => @unlink($f->getPathname()));
        @rmdir($this->tmpDir);
    }
});

it('initial SubmitBallot triggers initialization; subsequent submits do not recreate data', function () {
    // --- Sanity: DB starts empty
    expect(Position::count())->toBe(0);
    expect(Candidate::count())->toBe(0);
    expect(Precinct::count())->toBe(0);
    expect(Ballot::count())->toBe(0);

    // --- Minimal but valid election + precinct configs
    $election = [
        'positions' => [
            ['code' => 'PRESIDENT', 'name' => 'President', 'level' => 'national', 'count' => 1],
        ],
        'candidates' => [
            'PRESIDENT' => [
                ['code' => 'P_BBM', 'name' => 'Ferdinand Marcos Jr.', 'alias' => 'BBM'],
                ['code' => 'P_LRA', 'name' => 'Leni Robredo',        'alias' => 'LRA'],
            ],
        ],
    ];
    $precinct = [
        'code'           => 'CURRIMAO-001',
        'location_name'  => 'Currimao National High School',
        'latitude'       => 17.993217,       // ✅ no trailing comma in the key
        'longitude'      => 120.488902,
        'electoral_inspectors' => [
            ['id' => 'uuid-juan',  'name' => 'Juan dela Cruz', 'role' => 'chairperson'],
            ['id' => 'uuid-maria', 'name' => 'Maria Santos',   'role' => 'member'],
            ['id' => 'uuid-pedro', 'name' => 'Pedro Reyes',    'role' => 'member'],
        ],
    ];

    $electionPath = sb_writeElectionJson($this->tmpDir, $election);
    $precinctPath = sb_writePrecinctYaml($this->tmpDir, $precinct);

    // --- FIRST SUBMIT (no precinct_id in payload anymore)
    // Middleware should initialize the DB from the supplied files, then SubmitBallot succeeds.
    $firstPayload = [
        'code'  => 'BALLOT-001',
        'votes' => [
            [
                'position'   => ['code' => 'PRESIDENT'],
                'candidates' => [
                    ['code' => 'P_BBM'],
                ],
            ],
        ],
    ];

    postJson(
        '/api/ballots?election=' . urlencode($electionPath) . '&precinct=' . urlencode($precinctPath),
        $firstPayload,
        ['Accept' => 'application/json']
    )
        ->assertCreated()
        ->assertJsonStructure(['id', 'code', 'votes', 'precinct']);

    // --- After first request, the system should be initialized and one ballot created
    expect(Position::count())->toBe(1);
    expect(Candidate::count())->toBe(2);
    expect(Precinct::count())->toBe(1);
    expect(Ballot::count())->toBe(1);

    // --- SECOND SUBMIT (no overrides; DB already initialized)
    $secondPayload = [
        'code'  => 'BALLOT-002',
        'votes' => [
            [
                'position'   => ['code' => 'PRESIDENT'],
                'candidates' => [
                    ['code' => 'P_LRA'],
                ],
            ],
        ],
    ];

    postJson('/api/ballots', $secondPayload, ['Accept' => 'application/json'])
        ->assertCreated()
        ->assertJsonStructure(['id', 'code', 'votes', 'precinct']);

    // Idempotency & creation assertions
    expect(Position::count())->toBe(1);
    expect(Candidate::count())->toBe(2);
    expect(Precinct::count())->toBe(1);
    expect(Ballot::count())->toBe(2);
});
