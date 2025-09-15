<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use TruthElection\Support\ElectionStoreInterface;
use TruthElectionDb\Tests\ResetsElectionStore;
use TruthElectionDb\Models\Precinct;
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

    $this->artisan('election:cast', [
        '--json' => '{"ballot_code":"BAL001","precinct_code":"CURRIMAO-001","votes":[{"position":{"code":"PRESIDENT","name":"President","level":"national","count":1},"candidates":[{"code":"LD_001","name":"Leonardo DiCaprio","alias":"LD","position":{"code":"PRESIDENT","name":"President","level":"national","count":1}}]}]}'
    ])->assertExitCode(0);

    $this->artisan('election:tally', [
        'precinct_code' => 'CURRIMAO-001',
    ]);

    $er = app(ElectionStoreInterface::class)->getElectionReturnByPrecinct('CURRIMAO-001');
    $code = $er->code;

    $this->artisan('election:attest', [
        'election_return_code' => $code,
        'payload' => 'BEI:uuid-juan:signature123',
    ]);

    $this->artisan('election:attest', [
        'election_return_code' => $code,
        'payload' => 'BEI:uuid-maria:signature456',
    ]);
});

test('artisan election:record-statistics persists statistics fields', function () {
    $payload = [
        'watchers_count' => 5,
        'registered_voters_count' => 800,
        'actual_voters_count' => 700,
        'ballots_in_box_count' => 695,
        'unused_ballots_count' => 105,
    ];

    $jsonPayload = json_encode($payload);

    $this->artisan('election:record-statistics', [
        'precinct_code' => 'CURRIMAO-001',
        '--payload' => $jsonPayload,
    ])
        ->expectsOutputToContain('✅ Statistics successfully recorded for precinct: CURRIMAO-001')
        ->expectsOutputToContain('watchers_count: 5')
        ->expectsOutputToContain('actual_voters_count: 700')
        ->assertExitCode(0);

    $precinct = Precinct::where('code', 'CURRIMAO-001')->first();

    foreach ($payload as $key => $expected) {
        expect($precinct->{$key})->toBe($expected);
    }
});

test('artisan election:record-statistics fails with malformed JSON', function () {
    $this->artisan('election:record-statistics', [
        'precinct_code' => 'CURRIMAO-001',
        '--payload' => '{"watchers_count": 5,,}',
    ])
        ->expectsOutputToContain('❌ Invalid JSON payload:')
        ->assertExitCode(1);
});

test('artisan election:record-statistics fails with invalid data', function () {
    $this->artisan('election:record-statistics', [
        'precinct_code' => 'CURRIMAO-001',
        '--payload' => json_encode(['actual_voters_count' => -12]),
    ])
        ->expectsOutputToContain('❌ Validation failed:')
        ->expectsOutputToContain('actual_voters_count')
        ->assertExitCode(1);
});

test('artisan election:record-statistics fails with unknown precinct', function () {
    $this->artisan('election:record-statistics', [
        'precinct_code' => 'DOES-NOT-EXIST',
        '--payload' => json_encode(['watchers_count' => 1]),
    ])
        ->expectsOutputToContain('❌ Failed to record statistics: Precinct [DOES-NOT-EXIST] not found in memory.')
        ->assertExitCode(1);
});

test('artisan election:record-statistics fails when payload is missing', function () {
    $this->artisan('election:record-statistics', [
        'precinct_code' => 'CURRIMAO-001',
        // no --payload
    ])
        ->expectsOutputToContain('❌ Please provide a JSON payload using the --payload option.')
        ->assertExitCode(1);
});
