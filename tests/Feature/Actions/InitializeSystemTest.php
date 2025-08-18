<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{Candidate, Position, Precinct};
use Illuminate\Support\Facades\File;
use function Pest\Laravel\postJson;
use Symfony\Component\Yaml\Yaml;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ensure a clean slate
    Position::query()->delete();
    Candidate::query()->delete();
    Precinct::query()->delete();

    // Keep a temp folder for config files used in these tests
    $this->tmpDir = base_path('tests/TempInit');
    if (!is_dir($this->tmpDir)) {
        mkdir($this->tmpDir, 0777, true);
    }
});

afterEach(function () {
    if (is_dir($this->tmpDir)) {
        collect(File::allFiles($this->tmpDir))->each(fn($f) => @unlink($f->getPathname()));
        @rmdir($this->tmpDir);
    }
});

///**
// * Helpers to write ad-hoc config files for each test
// */
//function writeElectionJson(string $dir, array $data): string
//{
//    $path = $dir . '/election.json';
//    File::put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
//    return $path;
//}
//
//function writePrecinctYaml(string $dir, array $data): string
//{
//    $path = $dir . '/precinct.yaml';
//    File::put($path, Yaml::dump($data, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK));
//    return $path;
//}

it('initializes system from provided election.json and precinct.yaml', function () {
    // Minimal but valid payloads (kept small for speed)
    $election = [
        'positions' => [
            ['code' => 'PRESIDENT', 'name' => 'President', 'level' => 'national', 'count' => 1],
            ['code' => 'SENATOR',   'name' => 'Senator',   'level' => 'national', 'count' => 12],
        ],
        'candidates' => [
            'PRESIDENT' => [
                ['code' => 'P_BBM', 'name' => 'Ferdinand Marcos Jr.', 'alias' => 'BBM'],
                ['code' => 'P_LRA', 'name' => 'Leni Robredo',        'alias' => 'LRA'],
            ],
            'SENATOR' => [
                ['code' => 'S_001', 'name' => 'Juan Dela Cruz', 'alias' => 'JDC'],
                ['code' => 'S_002', 'name' => 'Maria Santos',   'alias' => 'MS'],
            ],
        ],
    ];
    $precinct = [
        'code' => 'CURRIMAO-001',
        'location_name' => 'Currimao National High School',
        'latitude' => 17.993217,
        'longitude' => 120.488902,
        'electoral_inspectors' => [
            ['id' => 'uuid-juan',  'name' => 'Juan dela Cruz', 'role' => 'chairperson'],
            ['id' => 'uuid-maria', 'name' => 'Maria Santos',   'role' => 'member'],
            ['id' => 'uuid-pedro', 'name' => 'Pedro Reyes',    'role' => 'member'],
        ],
    ];

    $electionPath = writeElectionJson($this->tmpDir, $election);
    $precinctPath = writePrecinctYaml($this->tmpDir, $precinct);

    $res = postJson(
        route('system.initialize'),
        [],
        ['Accept' => 'application/json'],
    )->assertOk(); // no overrides → if your route uses defaults, skip overrides

    // Call with explicit overrides so we’re certain these files were used:
    $res = postJson(
        route('system.initialize', ['reset' => true, 'precinct' => $precinctPath, 'election' => $electionPath]),
        [],
        ['Accept' => 'application/json'],
    )->assertOk()
        ->assertJsonPath('ok', true)
        ->assertJsonStructure([
            'ok',
            'summary' => [
                'positions'  => ['created', 'updated'],
                'candidates' => ['created', 'updated'],
                'precinct'   => ['created', 'updated'],
            ],
            'files' => ['election', 'precinct'],
        ]);

    // DB assertions
    expect(Position::count())->toBe(2);
    expect(Candidate::count())->toBe(4);
    expect(Precinct::count())->toBe(1);

    $p = Precinct::firstOrFail();
    expect($p->code)->toBe('CURRIMAO-001')
        ->and($p->location_name)->toBe('Currimao National High School')
        ->and($p->latitude)->toBe(17.993217)
        ->and($p->longitude)->toBe(120.488902)
        ->and((array) $p->electoral_inspectors)->toHaveCount(3);
});

it('is idempotent (second run updates, no duplicates)', function () {
    // First config
    $election1 = [
        'positions' => [
            ['code' => 'PRESIDENT', 'name' => 'President', 'level' => 'national', 'count' => 1],
        ],
        'candidates' => [
            'PRESIDENT' => [
                ['code' => 'P_BBM', 'name' => 'Ferdinand Marcos Jr.', 'alias' => 'BBM'],
            ],
        ],
    ];
    $precinct1 = [
        'code' => 'CURRIMAO-001',
        'location_name' => 'Currimao National High School',
    ];
    $electionPath1 = writeElectionJson($this->tmpDir, $election1);
    $precinctPath1 = writePrecinctYaml($this->tmpDir, $precinct1);

    // First run → creates
    postJson('/api/initialize-system?election=' . urlencode($electionPath1) . '&precinct=' . urlencode($precinctPath1))
        ->assertOk();

    expect(Position::count())->toBe(1);
    expect(Candidate::count())->toBe(1);
    expect(Precinct::count())->toBe(1);

    // Second config tweaks name (should update, not create another)
    $election2 = $election1;
    $election2['positions'][0]['name'] = 'President of the Philippines';
    $electionPath2 = writeElectionJson($this->tmpDir, $election2);

    postJson('/api/initialize-system?election=' . urlencode($electionPath2) . '&precinct=' . urlencode($precinctPath1))
        ->assertOk();

    expect(Position::count())->toBe(1);
    expect(Candidate::count())->toBe(1);
    expect(Precinct::count())->toBe(1);

    $pos = Position::firstOrFail();
    expect($pos->name)->toBe('President of the Philippines');
});

it('fails cleanly when a config file is missing', function () {
    $electionPath = $this->tmpDir . '/missing-election.json';
    $precinctPath = writePrecinctYaml($this->tmpDir, ['code' => 'CURRIMAO-001']);

    $res = postJson('/api/initialize-system?election=' . urlencode($electionPath) . '&precinct=' . urlencode($precinctPath));

    $res->assertStatus(422)
        ->assertJson([
            'message' => "Election config not found at: {$electionPath}",
        ]);
});

it('validates basic shapes and reports helpful errors', function () {
    // Invalid: positions missing / candidates not an array
    $badElection = [
        'positions' => [], // ok to be empty, but keep a candidate shape error below
        'candidates' => 'not-an-array',
    ];
    $electionPath = writeElectionJson($this->tmpDir, $badElection);
    $precinctPath = writePrecinctYaml($this->tmpDir, ['code' => 'CURRIMAO-001']);

    $res = postJson('/api/initialize-system?election=' . urlencode($electionPath) . '&precinct=' . urlencode($precinctPath));

    $res->assertStatus(422)
        ->assertJsonValidationErrors(['candidates']);
});
