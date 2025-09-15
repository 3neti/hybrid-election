<?php

use TruthElectionDb\Models\{Candidate, Position, Precinct};
use Illuminate\Foundation\Testing\RefreshDatabase;
use TruthElectionDb\Tests\ResetsElectionStore;
use Illuminate\Support\Facades\File;

uses(ResetsElectionStore::class, RefreshDatabase::class)->beforeEach(function () {
    File::ensureDirectoryExists(base_path('config'));

    //assumption is test is in tests/Feature/Console/Commands directory
    $electionSource = realpath(__DIR__ . '/../../../../config/election.json');
    $precinctSource = realpath(__DIR__ . '/../../../../config/precinct.yaml');

    expect($electionSource)->not->toBeFalse("Missing election.json fixture");
    expect($precinctSource)->not->toBeFalse("Missing precinct.yaml fixture");

    File::copy($electionSource, base_path('config/election.json'));
    File::copy($precinctSource, base_path('config/precinct.yaml'));
});

test('artisan election:setup works', function () {

    $this->artisan('election:setup')
        ->expectsOutput('✅ Election setup complete.')
        ->assertSuccessful();

    // 🧪 Confirm database state
    expect(Precinct::count())->toBe(1);
    expect(Position::count())->toBeGreaterThan(0);
    expect(Candidate::count())->toBeGreaterThan(0);
});

test('artisan election:setup does not duplicate data when run twice', function () {
    $this->artisan('election:setup')->assertExitCode(0);
    $count = Candidate::count();

    $this->artisan('election:setup')->assertExitCode(0);
    expect(Candidate::count())->toBe($count);
});
