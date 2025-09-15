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

    // Cast a valid ballot
    $this->artisan('election:cast', [
        '--json' => '{"ballot_code":"BAL001","precinct_code":"CURRIMAO-001","votes":[{"position":{"code":"PRESIDENT","name":"President","level":"national","count":1},"candidates":[{"code":"LD_001","name":"Leonardo DiCaprio","alias":"LD","position":{"code":"PRESIDENT","name":"President","level":"national","count":1}}]}]}',
    ])->assertExitCode(0);
});

test('artisan election:tally shows expected vote tally for precinct', function () {
    $this->artisan('election:tally', [
        'precinct_code' => 'CURRIMAO-001',
    ])
        ->expectsOutputToContain('✅ Tally complete:')
        ->expectsOutputToContain('Precinct: CURRIMAO-001')
        ->expectsOutputToContain('Position: President')
        ->expectsOutputToContain('Leonardo DiCaprio (1 vote)')
        ->assertExitCode(0)
    ;
});
