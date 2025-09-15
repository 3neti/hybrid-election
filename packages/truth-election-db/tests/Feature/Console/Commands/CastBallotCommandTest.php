<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use TruthElectionDb\Tests\ResetsElectionStore;
use Illuminate\Support\Facades\File;

uses(ResetsElectionStore::class, RefreshDatabase::class)->beforeEach(function () {
    File::ensureDirectoryExists(base_path('config'));

    $electionSource = realpath(__DIR__ . '/../../../../config/election.json');
    $precinctSource = realpath(__DIR__ . '/../../../../config/precinct.yaml');

    expect($electionSource)->not->toBeFalse("Missing election.json fixture");
    expect($precinctSource)->not->toBeFalse("Missing precinct.yaml fixture");

    File::copy($electionSource, base_path('config/election.json'));
    File::copy($precinctSource, base_path('config/precinct.yaml'));

    $this->artisan('election:setup')->assertExitCode(0);
});

test('artisan election:cast works via json option', function () {
    $this->artisan('election:cast', [
        '--json' => '{"ballot_code":"BAL001","precinct_code":"CURRIMAO-001","votes":[{"position":{"code":"PRESIDENT","name":"President","level":"national","count":1},"candidates":[{"code":"LD_001","name":"Leonardo DiCaprio","alias":"LD","position":{"code":"PRESIDENT","name":"President","level":"national","count":1}}]},{"position":{"code":"VICE-PRESIDENT","name":"Vice President","level":"national","count":1},"candidates":[{"code":"TH_001","name":"Tom Hanks","alias":"TH","position":{"code":"VICE-PRESIDENT","name":"Vice President","level":"national","count":1}}]}]}',
    ])
        ->expectsOutputToContain('✅ Ballot successfully cast')
        ->assertExitCode(0);
});

test('artisan election:cast works via json file', function () {
    // Ensure ballot fixture exists
    $ballotFixture = realpath(__DIR__ . '/../../stubs/ballot.json');
    expect($ballotFixture)->not->toBeFalse("Missing ballot.json fixture");

    $this->artisan('election:cast', [
        '--input' => $ballotFixture,
    ])
        ->expectsOutputToContain('✅ Ballot successfully cast')
        ->assertExitCode(0)
    ;
});

test('artisan election:cast fails with no input or json provided', function () {
    $this->artisan('election:cast')
        ->expectsOutputToContain('❌ No valid input. Please provide --json or --input.')
        ->assertExitCode(1);
});

test('artisan election:cast fails when input file does not exist', function () {
    $this->artisan('election:cast', [
        '--input' => base_path('config/missing-ballot.json'),
    ])
        ->expectsOutputToContain('❌ No valid input. Please provide --json or --input.')
        ->assertExitCode(1);
});

test('artisan election:cast fails with malformed JSON string', function () {
    $this->artisan('election:cast', [
        '--json' => '{"ballot_code": "BAL001", "precinct_code": "P-001", "votes": [}', // malformed JSON
    ])
        ->expectsOutputToContain('❌ Failed to parse JSON:')
        ->assertExitCode(1);
});

test('artisan election:cast fails with malformed JSON file', function () {
    $malformed = realpath(__DIR__ . '/../../stubs/malformed-ballot.json');

    expect($malformed)->not->toBeFalse("Missing malformed-ballot.json fixture");

    $this->artisan('election:cast', [
        '--input' => $malformed,
    ])
        ->expectsOutputToContain('❌ Failed to parse JSON file:')
        ->assertExitCode(1);
});
