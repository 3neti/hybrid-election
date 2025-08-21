<?php

use App\Console\Pipelines\FinalizeErContext;
use App\Models\Precinct;
use Illuminate\Support\Str;
use App\Data\ElectionReturnData;

use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;
use Pest\Expectation;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
 // ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

/**
 * Usage: expect($actual)->toEqualNormalized($expected)
 */
expect()->extend('toEqualNormalized', function ($expected, string $message = '') {
    $actualNorm   = normalizeArray($this->value);
    $expectedNorm = normalizeArray($expected);

    return expect($actualNorm)->toEqual($expectedNorm, $message);
});

/**
 * Test-only helpers to write minimal config files
 */
function writeElectionJson(string $dir, array $data): string
{
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    $path = $dir . '/election.json';
    File::put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return $path;
}

function writePrecinctYaml(string $dir, array $data): string
{
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    $path = $dir . '/precinct.yaml';
    File::put($path, Yaml::dump($data, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK));
    return $path;
}

/**
 * Your canonical ER JSON (array) for tests.
 */
function sample_er_array(): array
{
    return [
        'id' => 'uuid-er-001',
        'code' => 'ER-001',
        'precinct' => [
            'id' => 'uuid-precinct-001',
            'code' => 'CURRIMAO-001',
            'location_name' => 'Currimao Central School',
            'latitude' => 17.993217,
            'longitude' => 120.488902,
            'electoral_inspectors' => [
                ['id' => 'uuid-ei-001', 'name' => 'Juan dela Cruz', 'role' => 'chairperson'],
                ['id' => 'uuid-ei-002', 'name' => 'Maria Santos', 'role' => 'member'],
            ],
            'watchers_count' => 2,
            'precincts_count' => 10,
            'registered_voters_count' => 250,
            'actual_voters_count' => 200,
            'ballots_in_box_count' => 198,
            'unused_ballots_count' => 52,
            'spoiled_ballots_count' => 3,
            'void_ballots_count' => 1,
        ],
        'tallies' => [
            [
                'position_code' => 'PRESIDENT',
                'candidate_code' => 'uuid-bbm',
                'candidate_name' => 'Ferdinand Marcos Jr.',
                'count' => 300,
            ],
            [
                'position_code' => 'SENATOR',
                'candidate_code' => 'uuid-jdc',
                'candidate_name' => 'Juan Dela Cruz',
                'count' => 280,
            ],
        ],
        'signatures' => [
            [
                'id' => 'uuid-ei-001',
                'name' => 'Juan dela Cruz',
                'role' => 'chairperson',
                'signature' => 'base64-image-data',
                'signed_at' => '2025-08-07T12:00:00+08:00',
            ],
            [
                'id' => 'uuid-ei-002',
                'name' => 'Maria Santos',
                'role' => 'member',
                'signature' => 'base64-image-data',
                'signed_at' => '2025-08-07T12:05:00+08:00',
            ],
        ],
        'ballots' => [
            [
                'id' => 'uuid-ballot-001',
                'code' => 'BAL-001',
                'precinct' => [
                    'id' => 'uuid-precinct-001',
                    'code' => 'CURRIMAO-001',
                    'location_name' => 'Currimao Central School',
                    'latitude' => 17.993217,
                    'longitude' => 120.488902,
                    'electoral_inspectors' => [
                        ['id' => 'uuid-ei-001', 'name' => 'Juan dela Cruz', 'role' => 'chairperson'],
                        ['id' => 'uuid-ei-002', 'name' => 'Maria Santos', 'role' => 'member'],
                    ],
                ],
                'votes' => [[
                    'position' => [
                        'code' => 'PRESIDENT',
                        'name' => 'President of the Philippines',
                        'level' => 'national',
                        'count' => 1,
                    ],
                    'candidates' => [[
                        'code' => 'uuid-bbm',
                        'name' => 'Ferdinand Marcos Jr.',
                        'alias' => 'BBM',
                    ]],
                ]],
            ],
        ],
        'created_at' => '2025-08-07T12:00:00+08:00',
        'updated_at' => '2025-08-07T12:10:00+08:00',
    ];
}

/**
 * Build a FinalizeErContext from an ER array.
 * - Creates a matching Precinct in DB (so pipes can persist against it).
 * - Builds the ER DTO (uses Spatie Data) for pipes that read $ctx->er->code, tallies, etc.
 *
 * @param array|null $er The ER array (default: sample_er_array()).
 * @param array $opts disk|folder|payload|maxChars|force overrides
 * @return FinalizeErContext
 */
function finalize_ctx_from_array(array|null $er = null, array|null $opts = null): FinalizeErContext
{
    $er = $er ?? sample_er_array();
    $opts = $opts ?? [];

    // Create a real Precinct that mirrors the ER’s precinct data
    $precinct = Precinct::create([
        'id' => $er['precinct']['id'] ?? (string)Str::uuid(),
        'code' => $er['precinct']['code'] ?? 'PREC-TEST',
        'location_name' => $er['precinct']['location_name'] ?? null,
        'latitude' => $er['precinct']['latitude'] ?? null,
        'longitude' => $er['precinct']['longitude'] ?? null,
        'electoral_inspectors' => $er['precinct']['electoral_inspectors'] ?? [],
        // meta is schemaless; leave as default unless you want to prefill
    ]);

    // If your ER Data class requires specific shape, ensure array matches it
    $dto = ElectionReturnData::from($er);

    $disk = $opts['disk'] ?? 'election';
    $payload = $opts['payload'] ?? 'minimal';
    $maxChars = $opts['maxChars'] ?? 1200;
    $force = (bool)($opts['force'] ?? false);
    $folder = $opts['folder'] ?? ('ER-' . ltrim($dto->code, 'ER-') . '/final');

    return new FinalizeErContext(
        precinct: $precinct,
        er: $dto,
        disk: $disk,
        folder: $folder,
        payload: $payload,
        maxChars: $maxChars,
        force: $force,
        qrPersistedAbs: null,
    );
}

/**
 * Minimal context for pipes that only need a Precinct (e.g., CloseBalloting).
 */
function finalize_ctx_with_precinct(Precinct $precinct, array $opts = []): FinalizeErContext
{
    $disk = $opts['disk'] ?? 'election';
    $payload = $opts['payload'] ?? 'minimal';
    $maxChars = $opts['maxChars'] ?? 1200;
    $force = (bool)($opts['force'] ?? false);

    // ER & folder can be dummies for precinct-only tests
    $dummyCode = $opts['erCode'] ?? 'ER-DUMMY';
    $folder = $opts['folder'] ?? ('ER-' . ltrim($dummyCode, 'ER-') . '/final');

    return new FinalizeErContext(
        precinct: $precinct,
        er: null,     // precinct-only pipes don’t need the ER DTO
        disk: $disk,
        folder: $folder,
        payload: $payload,
        maxChars: $maxChars,
        force: $force,
        qrPersistedAbs: null,
    );
}
