<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use TruthElection\Support\ElectionStoreInterface;
use TruthElectionDb\Tests\ResetsElectionStore;
use TruthElection\Data\ElectionReturnData;
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
    $this->er_code = $er->code;

    $jsonPayload = json_encode([
        'watchers_count' => 5,
        'registered_voters_count' => 800,
        'actual_voters_count' => 700,
        'ballots_in_box_count' => 695,
        'unused_ballots_count' => 105,
    ]);

    $this->artisan('election:record-statistics', [
        'precinct_code' => 'CURRIMAO-001',
        '--payload' => $jsonPayload,
    ]);
});

test('artisan election:wrapup completes and finalizes return', function () {
    $this->artisan('election:attest', [
        'election_return_code' => $this->er_code,
        'payload' => 'BEI:uuid-juan:signature123',
    ]);

    $this->artisan('election:attest', [
        'election_return_code' => $this->er_code,
        'payload' => 'BEI:uuid-maria:signature456',
    ]);

    $this->artisan('election:wrapup', [
        'precinct_code' => 'CURRIMAO-001',
        '--disk' => 'local',
        '--payload' => 'minimal',
        '--max_chars' => 1200,
        '--dir' => 'final',
        '--force' => false,
    ])
        ->expectsOutputToContain('✅ Election Return successfully finalized.')
        ->expectsOutputToContain('🗳 Precinct: CURRIMAO-001')
        ->expectsOutputToContain('📦 Saved to:')
        ->assertExitCode(0)
    ;

    $precinct = Precinct::query()->where('code', 'CURRIMAO-001')->first();
    expect($precinct->closed_at)->not->toBeNull();

    $store = app(ElectionStoreInterface::class);
    $er = $store->getElectionReturnByPrecinct('CURRIMAO-001');
    expect($er)->toBeInstanceOf(ElectionReturnData::class);
    expect($er->signedInspectors())->toHaveCount(2);
});

test('artisan election:wrapup throws if already finalized without --force', function () {
    $precinct = Precinct::query()->where('code', 'CURRIMAO-001')->first();
    $precinct->closed_at = now()->toISOString();
    $precinct->save();

    $this->artisan('election:wrapup', [
        'precinct_code' => 'CURRIMAO-001',
    ])
//        ->expectsOutputToContain('Balloting already closed. Nothing to do.')
        ->assertExitCode(1)
    ;
});

test('artisan election:wrapup fails if signatures are incomplete', function () {
    $this->artisan('election:attest', [
        'election_return_code' => $this->er_code,
        'payload' => 'BEI:uuid-juan:signature123',
    ]);

    $this->artisan('election:wrapup', [
        'precinct_code' => 'CURRIMAO-001',
        '--force' => false,
    ])
//        ->expectsOutputToContain('Signature validation failed')
        ->assertExitCode(1)
    ;
});
