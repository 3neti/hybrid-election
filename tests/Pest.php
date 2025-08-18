<?php

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
