<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Models\{Position, Candidate, Precinct};
use function Pest\Laravel\postJson;
use App\Http\Middleware\EnsureSystemInitialized;

uses(RefreshDatabase::class);

/**
 * Test-only helpers to write minimal config files
 */
//function writeElectionJson(string $dir, array $data): string
//{
//    if (!is_dir($dir)) mkdir($dir, 0777, true);
//    $path = $dir . '/election.json';
//    File::put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
//    return $path;
//}
//
//function writePrecinctYaml(string $dir, array $data): string
//{
//    if (!is_dir($dir)) mkdir($dir, 0777, true);
//    $path = $dir . '/precinct.yaml';
//    File::put($path, Yaml::dump($data, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK));
//    return $path;
//}

beforeEach(function () {
    // Clean DB (RefreshDatabase already migrates)
    Position::query()->delete();
    Candidate::query()->delete();
    Precinct::query()->delete();

    // Temp folder for ad-hoc config files
    $this->tmpDir = base_path('tests/TempInitMiddleware');
    if (is_dir($this->tmpDir)) {
        collect(File::allFiles($this->tmpDir))->each(fn($f) => @unlink($f->getPathname()));
        @rmdir($this->tmpDir);
    }
    mkdir($this->tmpDir, 0777, true);

    // Define a test-only protected route guarded by the middleware
    Route::middleware(['api', EnsureSystemInitialized::class])
        ->post('/api/protected-ping', fn () => response()->json(['ok' => true]));
});

afterEach(function () {
    if (is_dir($this->tmpDir)) {
        collect(File::allFiles($this->tmpDir))->each(fn($f) => @unlink($f->getPathname()));
        @rmdir($this->tmpDir);
    }
});

it('initializes on first request via middleware and returns ok', function () {
    // Minimal but valid config so test is fast
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

    // Hit the protected endpoint with overrides → middleware should initialize then pass through
    postJson('/api/protected-ping?election=' . urlencode($electionPath) . '&precinct=' . urlencode($precinctPath))
        ->assertOk()
        ->assertJson(['ok' => true]);

    // DB assertions (exact counts from our minimal config)
    expect(Position::count())->toBe(2);
    expect(Candidate::count())->toBe(4);
    expect(Precinct::count())->toBe(1);
});

it('is idempotent across multiple requests (no duplicates)', function () {
    $election = [
        'positions' => [
            ['code' => 'PRESIDENT', 'name' => 'President', 'level' => 'national', 'count' => 1],
        ],
        'candidates' => [
            'PRESIDENT' => [
                ['code' => 'P_BBM', 'name' => 'Ferdinand Marcos Jr.', 'alias' => 'BBM'],
            ],
        ],
    ];
    $precinct = ['code' => 'CURRIMAO-001', 'location_name' => 'Currimao National High School'];

    $electionPath = writeElectionJson($this->tmpDir, $election);
    $precinctPath = writePrecinctYaml($this->tmpDir, $precinct);

    // First request → initializes
    postJson('/api/protected-ping?election=' . urlencode($electionPath) . '&precinct=' . urlencode($precinctPath))
        ->assertOk();

    expect(Position::count())->toBe(1);
    expect(Candidate::count())->toBe(1);
    expect(Precinct::count())->toBe(1);

    // Second request → should be a no-op (fast-path in middleware)
    postJson('/api/protected-ping')
        ->assertOk();

    expect(Position::count())->toBe(1);
    expect(Candidate::count())->toBe(1);
    expect(Precinct::count())->toBe(1);
});
