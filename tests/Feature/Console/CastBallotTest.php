<?php

use App\Models\{Ballot, Candidate, Position, Precinct};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{Artisan, File};
use Symfony\Component\Yaml\Yaml;

uses(RefreshDatabase::class);

///** Helpers to write minimal config files */
//function cb_writeElectionJson(string $dir, array $data): string {
//    if (!is_dir($dir)) mkdir($dir, 0777, true);
//    $path = $dir . '/election.json';
//    File::put($path, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
//    return $path;
//}
//function cb_writePrecinctYaml(string $dir, array $data): string {
//    if (!is_dir($dir)) mkdir($dir, 0777, true);
//    $path = $dir . '/precinct.yaml';
//    File::put($path, Yaml::dump($data, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK));
//    return $path;
//}

beforeEach(function () {
    // Just in case: fully empty tables
    Position::query()->delete();
    Candidate::query()->delete();
    Precinct::query()->delete();
    Ballot::query()->delete();

    $this->tmpDir = base_path('tests/TempCastBallot');
    if (!is_dir($this->tmpDir)) mkdir($this->tmpDir, 0777, true);

    // Minimal, valid election + precinct
    $this->election = [
        'positions' => [
            ['code' => 'PRESIDENT', 'name' => 'President', 'level' => 'national', 'count' => 1],
            ['code' => 'SENATOR',   'name' => 'Senator',   'level' => 'national', 'count' => 12],
        ],
        'candidates' => [
            'PRESIDENT' => [
                ['code' => 'SJ_002', 'name' => 'Sample Juan', 'alias' => 'SJ'],
                ['code' => 'LR_001', 'name' => 'Leni R',      'alias' => 'LR'],
            ],
            'SENATOR' => [
                ['code' => 'JD_001', 'name' => 'Juan D', 'alias' => 'JD'],
                ['code' => 'JL_004', 'name' => 'Juan L', 'alias' => 'JL'],
            ],
        ],
    ];
    $this->precinct = [
        'code' => 'CURRIMAO-001',
        'location_name' => 'Currimao NHS',
        'latitude' => 17.993217,
        'longitude' => 120.488902,
        'electoral_inspectors' => [
            ['id' => 'uuid-juan',  'name' => 'Juan dela Cruz', 'role' => 'chairperson'],
            ['id' => 'uuid-maria', 'name' => 'Maria Santos',   'role' => 'member'],
            ['id' => 'uuid-pedro', 'name' => 'Pedro Reyes',    'role' => 'member'],
        ],
    ];

    $this->electionPath = writeElectionJson($this->tmpDir, $this->election);
    $this->precinctPath = writePrecinctYaml($this->tmpDir, $this->precinct);
});

afterEach(function () {
    if (is_dir($this->tmpDir)) {
        collect(File::allFiles($this->tmpDir))->each(fn($f) => @unlink($f->getPathname()));
        @rmdir($this->tmpDir);
    }
});

/** ───────────────────────── Tests ───────────────────────── */

it('creates a ballot (201) then returns 200 on exact resubmission (local mode)', function () {
    // Sanity: empty DB
    expect(Position::count())->toBe(0);
    expect(Candidate::count())->toBe(0);
    expect(Precinct::count())->toBe(0);
    expect(Ballot::count())->toBe(0);

    // FIRST RUN → should auto-initialize + create
    $exit1 = Artisan::call('app:cast-ballot', [
        'lines'     => ["BAL-001|PRESIDENT:SJ_002;SENATOR:JD_001,JL_004"],
        '--local'   => true,
        '--election'=> $this->electionPath,
        '--precinct'=> $this->precinctPath,
    ]);
    $out1 = Artisan::output();

    expect($exit1)->toBe(0);
    expect($out1)->toContain('CREATED BAL-001'); // local 201 default from command
    expect(Position::count())->toBe(2);
    expect(Candidate::count())->toBe(4);
    expect(Precinct::count())->toBe(1);
    expect(Ballot::count())->toBe(1);

    // SECOND RUN (same payload) → OK 200 (duplicate same-hash)
    $exit2 = Artisan::call('app:cast-ballot', [
        'lines'     => ["BAL-001|PRESIDENT:SJ_002;SENATOR:JD_001,JL_004"],
        '--local'   => true,
        '--election'=> $this->electionPath,
        '--precinct'=> $this->precinctPath,
    ]);
    $out2 = Artisan::output();

    expect($exit2)->toBe(0);
    expect($out2)->toContain('CREATED BAL-001');
    expect(Ballot::count())->toBe(1); // still one ballot
});

it('returns SKIP (409 conflict) when same code has different votes (local mode)', function () {
    // Create original
    Artisan::call('app:cast-ballot', [
        'lines'     => ["BAL-009|PRESIDENT:SJ_002"],
        '--local'   => true,
        '--election'=> $this->electionPath,
        '--precinct'=> $this->precinctPath,
    ]);

    // Reuse code, different candidate → conflict (mapped to SKIP in logging)
    $exit = Artisan::call('app:cast-ballot', [
        'lines'     => ["BAL-009|PRESIDENT:LR_001"],
        '--local'   => true,
        '--election'=> $this->electionPath,
        '--precinct'=> $this->precinctPath,
    ]);
    $out = Artisan::output();

    expect($exit)->toBe(0);
    expect($out)->toContain('SKIP BAL-009'); // 409 conflict line
    expect(Ballot::count())->toBe(1);
});

it('supports --dry-run (parses but does not submit)', function () {
    $exit = Artisan::call('app:cast-ballot', [
        'lines'     => ["BAL-DRY|PRESIDENT:SJ_002;SENATOR:JD_001"],
        '--local'   => true,
        '--election'=> $this->electionPath,
        '--precinct'=> $this->precinctPath,
        '--dry-run' => true,
    ]);
    $out = Artisan::output();

    expect($exit)->toBe(0);
    expect($out)->toContain('DRY-RUN parsed BAL-DRY');
    expect(Ballot::count())->toBe(0);
});

it('handles multiple lines and prints the final summary', function () {
    $lines = [
        "# a comment line",
        "BAL-101|PRESIDENT:SJ_002",
        "BAL-102|PRESIDENT:LR_001",
        "", // blank
        "BAL-101|PRESIDENT:SJ_002", // duplicate same-hash → OK 200
        "BAL-101|PRESIDENT:LR_001", // conflict → SKIP 409
    ];

    $exit = Artisan::call('app:cast-ballot', [
        'lines'     => $lines,
        '--local'   => true,
        '--election'=> $this->electionPath,
        '--precinct'=> $this->precinctPath,
    ]);
    $out = Artisan::output();

    expect($exit)->toBe(0);
    expect($out)->toContain('CREATED BAL-101');
    expect($out)->toContain('CREATED BAL-102');
    expect($out)->toContain('CREATED BAL-101');
    expect($out)->toContain('SKIP BAL-101'); // conflict
    expect($out)->toMatch('/Done\. OK=\d+, SKIPPED=\d+, FAILED=\d+/');

    expect(Ballot::count())->toBe(2);
});
