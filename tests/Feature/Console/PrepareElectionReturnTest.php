<?php

use App\Models\{Position, Candidate, Precinct, Ballot};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;
use App\Actions\InitializeSystem;

/** ---------- helpers ---------- */
function per_writeElectionJson(string $dir, array $data): string {
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    $path = $dir . '/election.json';
    File::put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return $path;
}
function per_writePrecinctYaml(string $dir, array $data): string {
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    $path = $dir . '/precinct.yaml';
    File::put($path, Yaml::dump($data, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK));
    return $path;
}

uses(RefreshDatabase::class);

beforeEach(function () {
    // Clean slate for every test
    Position::query()->delete();
    Candidate::query()->delete();
    Precinct::query()->delete();
    Ballot::query()->delete();

    $this->tmpDir = base_path('tests/TempPrepareER');
    if (!is_dir($this->tmpDir)) mkdir($this->tmpDir, 0777, true);

    // Minimal but valid config for speed
    $this->election = [
        'positions' => [
            ['code' => 'PRESIDENT',      'name' => 'President', 'level' => 'national', 'count' => 1],
            ['code' => 'VICE-PRESIDENT', 'name' => 'VP',        'level' => 'national', 'count' => 1],
        ],
        'candidates' => [
            'PRESIDENT' => [
                ['code' => 'P_BBM', 'name' => 'Ferdinand Marcos Jr.', 'alias' => 'BBM'],
                ['code' => 'P_LRA', 'name' => 'Leni Robredo',        'alias' => 'LRA'],
            ],
            'VICE-PRESIDENT' => [
                ['code' => 'VP_SD', 'name' => 'Sara Duterte', 'alias' => 'SD'],
                ['code' => 'VP_KP', 'name' => 'Kiko Pangilinan', 'alias' => 'KP'],
            ],
        ],
    ];
    $this->precinct = [
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

    $this->electionPath = per_writeElectionJson($this->tmpDir, $this->election);
    $this->precinctPath = per_writePrecinctYaml($this->tmpDir, $this->precinct);

    // Initialize via the same action used by middleware/console guards
    InitializeSystem::run(
        reset: true,
        electionPath: $this->electionPath,
        precinctPath: $this->precinctPath,
    );
});

afterEach(function () {
    if (is_dir($this->tmpDir)) {
        collect(File::allFiles($this->tmpDir))->each(fn($f) => @unlink($f->getPathname()));
        @rmdir($this->tmpDir);
    }
});

/** ─────────────────────────────────────────────────────────────────────────
 * Happy path: cast ballots then prepare ER; output should include key lines.
 * ───────────────────────────────────────────────────────────────────────── */

it('generates an election return summary after ballots are cast', function () {
    expect(Precinct::count())->toBe(1);
    expect(Position::count())->toBe(2);
    expect(Candidate::count())->toBe(4);

    // Cast a few ballots locally
    Artisan::call('app:cast-ballot', [
        'lines'      => [
            "BAL-001|PRESIDENT:P_BBM;VICE-PRESIDENT:VP_SD",
            "BAL-002|PRESIDENT:P_LRA;VICE-PRESIDENT:VP_KP",
            "BAL-003|PRESIDENT:P_BBM;VICE-PRESIDENT:VP_SD",
        ],
        '--local'    => true,
        '--election' => $this->electionPath,
        '--precinct' => $this->precinctPath,
    ]);
    expect(Ballot::count())->toBe(3);

    // Run the command under test
    $exit = Artisan::call('prepare-er');
    $out  = Artisan::output();

    expect($exit)->toBe(0);
    // Core header lines
    expect($out)->toContain('Election Return ID:')
        ->toContain('Return Code')
        ->toContain('Precinct Code')
        ->toContain('Location')
        ->toContain('Total Ballots');

    // Position blocks should be present
    expect($out)->toContain('Position: PRESIDENT')
        ->toContain('Position: VICE-PRESIDENT');

    // Some candidate names should show up with counts (we don’t assert exact spacing)
    expect($out)->toContain('Ferdinand Marcos Jr.')
        ->toContain('Sara Duterte')
        ->toContain('Leni Robredo')
        ->toContain('Kiko Pangilinan');
});

/** ─────────────────────────────────────────────────────────────────────────
 * Edge: no ballots cast yet → helpful message
 * ───────────────────────────────────────────────────────────────────────── */
it('prints a helpful message when no ballots exist', function () {
    // sanity
    expect(\App\Models\Ballot::count())->toBe(0);

    $exit = Artisan::call('prepare-er');
    $out  = Artisan::output();

    expect($exit)->toBe(0);
    expect($out)->toContain('No ballots found for this precinct yet. Nothing to summarize.');
});

/** ─────────────────────────────────────────────────────────────────────────
 * Edge: if the DB is completely empty (no init), informatively fails.
 * ───────────────────────────────────────────────────────────────────────── */
it('fails with a clear message when no precinct exists', function () {
    // Remove ballots first (FK), then precincts
    \App\Models\Ballot::query()->delete();
    \App\Models\Precinct::query()->delete();

    $exit = Artisan::call('prepare-er');
    $out  = Artisan::output();

    expect($exit)->toBe(1);
    expect($out)->toContain('No query results for model');
});
