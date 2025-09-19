<?php

use TruthElection\Support\PrecinctContext;
use TruthElection\Data\PrecinctData;
use TruthElection\Data\ElectoralInspectorData;
use TruthElection\Support\ElectionStoreInterface;
use Spatie\LaravelData\DataCollection;
use TruthElection\Enums\ElectoralInspectorRole;

use function Pest\Laravel\mock;

it('can resolve the precinct data and expose attributes', function () {
    $precinctCode = 'CURRIMAO-001';

    $inspectors = [
        new ElectoralInspectorData(
            id: 'uuid-juan',
            name: 'Juan Dela Cruz',
            role: ElectoralInspectorRole::CHAIRPERSON
        ),
        new ElectoralInspectorData(
            id: 'uuid-maria',
            name: 'Maria Santos',
            role: ElectoralInspectorRole::MEMBER
        ),
        new ElectoralInspectorData(
            id: 'uuid-pedro',
            name: 'Pedro Reyes',
            role: ElectoralInspectorRole::MEMBER
        ),
    ];

    $mockPrecinct = new PrecinctData(
        code: $precinctCode,
        location_name: 'Currimao National High School',
        latitude: 17.993217,
        longitude: 120.488902,
        electoral_inspectors: new DataCollection(ElectoralInspectorData::class, $inspectors),
        watchers_count: 0,
        precincts_count: 0,
        registered_voters_count: 0,
        actual_voters_count: 0,
        ballots_in_box_count: 0,
        unused_ballots_count: 0,
        spoiled_ballots_count: 0,
        void_ballots_count: 0,
    );

    // Mock ElectionStoreInterface
    $mockStore = $this->mock(ElectionStoreInterface::class)
        ->shouldReceive('getPrecinct')
        ->with($precinctCode)
        ->andReturn($mockPrecinct)
        ->getMock();

    // Instantiate PrecinctContext
    $context = new PrecinctContext($mockStore, $precinctCode);

    // Accessors
    expect($context->code())->toBe($precinctCode);
    expect($context->location())->toBe('Currimao National High School');
    expect($context->latitude())->toBe(17.993217);
    expect($context->longitude())->toBe(120.488902);

    // Inspectors
    $inspectorsCollection = $context->inspectors();
    expect($inspectorsCollection)->toBeInstanceOf(DataCollection::class);
    expect($inspectorsCollection)->toHaveCount(3);

    // Chairperson
    $chairperson = $context->chairperson();
    expect($chairperson)->toBeInstanceOf(ElectoralInspectorData::class);
    expect($chairperson->name)->toBe('Juan Dela Cruz');
    expect($chairperson->role)->toBe(ElectoralInspectorRole::CHAIRPERSON);

    // Members
    $members = $context->members();
    expect($members)->toBeInstanceOf(DataCollection::class);
    expect($members)->toHaveCount(2);
    expect($members->toCollection()->pluck('name')->all())->toBe(['Maria Santos', 'Pedro Reyes']);
});

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

it('resolves PrecinctContext via container using request input', function () {
    // Register a test route to simulate request lifecycle
    Route::get('/test-precinct-context', function (Request $request) {
        return app(PrecinctContext::class)->code();
    });

    // Prepare mock precinct
    $precinctCode = 'CURRIMAO-001';

    $mockPrecinct = new PrecinctData(
        code: $precinctCode,
        location_name: 'Currimao National High School',
        latitude: 17.993217,
        longitude: 120.488902,
        electoral_inspectors: new DataCollection(ElectoralInspectorData::class, []),
        watchers_count: 0,
        precincts_count: 0,
        registered_voters_count: 0,
        actual_voters_count: 0,
        ballots_in_box_count: 0,
        unused_ballots_count: 0,
        spoiled_ballots_count: 0,
        void_ballots_count: 0,
    );

    // Mock the ElectionStoreInterface binding
    $this->mock(ElectionStoreInterface::class)
        ->shouldReceive('getPrecinct')
        ->with($precinctCode)
        ->andReturn($mockPrecinct);

    // Simulate a request with precinct_code input
    $this->get("/test-precinct-context?precinct_code={$precinctCode}")
        ->assertOk()
        ->assertSee($precinctCode);
});
