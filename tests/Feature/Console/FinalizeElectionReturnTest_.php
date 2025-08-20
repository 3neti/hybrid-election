<?php

namespace Tests\Feature\Console;

use App\Actions\SubmitBallot;
use App\Actions\GenerateElectionReturn as GenerateElectionReturnAction;
use App\Data\{CandidateData, PositionData, VoteData};
use App\Events\BallotSubmitted;
use App\Models\{Ballot, Candidate, ElectionReturn, Position, Precinct};
use App\Enums\ElectoralInspectorRole;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\LaravelData\DataCollection;
use function Pest\Laravel\artisan;

uses(RefreshDatabase::class);

/** ---------- small helpers ---------- */
function makeMinimalElection(): void {
    Position::updateOrCreate(['code' => 'PRESIDENT'], [
        'id'    => (string) Str::uuid(),
        'code'  => 'PRESIDENT',
        'name'  => 'President',
        'level' => 'national',
        'count' => 1,
    ]);

    Candidate::updateOrCreate(['code' => 'P_AAA'], [
        'id'            => (string) Str::uuid(),
        'code'          => 'P_AAA',
        'name'          => 'Alice A.',
        'alias'         => 'AAA',
        'position_code' => 'PRESIDENT',
    ]);

    Candidate::updateOrCreate(['code' => 'P_BBB'], [
        'id'            => (string) Str::uuid(),
        'code'          => 'P_BBB',
        'name'          => 'Bob B.',
        'alias'         => 'BBB',
        'position_code' => 'PRESIDENT',
    ]);
}

function makePrecinctWithBoard(): Precinct {
    return Precinct::create([
        'id' => (string) Str::uuid(),
        'code' => 'CURRIMAO-001',
        'location_name' => 'Currimao National High School',
        'latitude' => 17.993217,
        'longitude' => 120.488902,
        'electoral_inspectors' => [
            ['id' => 'uuid-juan',  'name' => 'Juan dela Cruz', 'role' => ElectoralInspectorRole::CHAIRPERSON],
            ['id' => 'uuid-maria', 'name' => 'Maria Santos',   'role' => ElectoralInspectorRole::MEMBER],
            ['id' => 'uuid-pedro', 'name' => 'Pedro Reyes',    'role' => ElectoralInspectorRole::MEMBER],
        ],
        // keep balloting open by default
        'meta' => ['balloting_open' => true],
    ]);
}

function castOneBallot(): void {
    $pos  = Position::where('code','PRESIDENT')->firstOrFail();
    $cand = Candidate::where('code','P_AAA')->firstOrFail();

    $vote = new VoteData(
        position: PositionData::from($pos),
        candidates: new DataCollection(CandidateData::class, [CandidateData::from($cand)]),
    );

    // SubmitBallot now auto-resolves the single precinct
    SubmitBallot::run(
        code: 'BAL-001',
        votes: new DataCollection(VoteData::class, [$vote]),
    );
}

/** ---------- common per-test setup ---------- */
beforeEach(function () {
    // We call the command that writes to both disks:
    Storage::fake('election');     // storage/app/election/...
    Storage::fake('local');        // QR export action persists under local (private) in your setup

    // Don’t actually dispatch events (faster)
    Event::fake([BallotSubmitted::class]);

    // Minimal, valid system
    makeMinimalElection();
    $this->precinct = makePrecinctWithBoard();
    castOneBallot();

    // Ensure an ER exists / can be generated (the command calls GenerateElectionReturn internally)
    // Not strictly necessary, but keeps the flow realistic.
    GenerateElectionReturnAction::run($this->precinct);
});

/** ---------- tests ---------- */

it('finalizes the election return, exports artifacts, and closes balloting (happy path)', function () {
    // Fake the QR export endpoint the command calls
    Http::fake([
        // match route('qr.er', ['code' => ...]) → "*qr/election-return/*"
        '*' => Http::response([
            'ok'          => true,
            'total'       => 3,
            'chunks'      => [],
            'persisted_to'=> storage_path('app/private/qr_exports/DUMMY/final'),
        ], 200),
    ]);

    $exit = artisan('close-er')->run();
    expect($exit)->toBe(0);

    // Get the latest ER the command produced/refreshed
    /** @var ElectionReturn $er */
    $er = ElectionReturn::query()->latest('created_at')->firstOrFail();

    // Export bundle exists on the election disk
    $folder = "ER-{$er->code}/final";
    expect(Storage::disk('election')->exists("{$folder}/raw.full.json"))->toBeTrue();
    expect(Storage::disk('election')->exists("{$folder}/raw.min.json"))->toBeTrue();
    expect(Storage::disk('election')->exists("{$folder}/qr/manifest.json"))->toBeTrue();

    // Balloting closed & timestamped
    $precinct = $this->precinct->fresh();
    expect(($precinct->meta['balloting_open'] ?? null))->toBeFalse();
    expect(isset($precinct->meta['closed_at']))->toBeTrue();
});

//it('fails when required signatures are missing (no chair + member), unless forced', function () {
//    // Remove all signatures from any existing ER
//    ElectionReturn::query()->update(['signatures' => []]);
//
//    Http::fake(['*' => Http::response(['ok'=>true,'total'=>0,'chunks'=>[]], 200)]);
//
//    // Should FAIL (exit code 1) without signatures
//    $exit = artisan('close-er')->run();
//    expect($exit)->toBe(1);
//
//    // With --force, it should proceed and return a distinct non-zero (we chose 2)
//    $exitForced = artisan('close-er', ['--force' => true])->run();
//    expect($exitForced)->toBe(2);
//
//    // After forced close, balloting should be closed
//    $precinct = $this->precinct->fresh();
//    expect(($precinct->meta['balloting_open'] ?? null))->toBeFalse();
//    expect(isset($precinct->meta['closed_at']))->toBeTrue();
//});

it('reports a QR export failure clearly and returns non-zero', function () {
    // Ensure we DO have signatures to reach the QR step
    $er = ElectionReturn::query()->latest('created_at')->firstOrFail();
    $er->signatures = [
        ['id'=>'uuid-juan', 'name'=>'Juan dela Cruz', 'role'=>'chairperson', 'signature'=>'S1', 'signed_at'=>CarbonImmutable::now()->toIso8601String()],
        ['id'=>'uuid-maria','name'=>'Maria Santos',   'role'=>'member',      'signature'=>'S2', 'signed_at'=>CarbonImmutable::now()->toIso8601String()],
    ];
    $er->save();

    // Simulate a 500 from the QR export API
    Http::fake(['*' => Http::response('Internal Error', 500)]);

    $exit = artisan('close-er')->run();
    expect($exit)->toBe(1);

    // Should NOT close balloting when QR export fails
    $precinct = $this->precinct->fresh();
    expect(($precinct->meta['balloting_open'] ?? null))->toBeTrue();
});

/** Optional: if you want to assert the command prints the ER code and paths, you can add: */
/*
it('prints useful messages', function () {
    Http::fake(['*' => Http::response([
        'ok'=>true,'total'=>2,'chunks'=>[],'persisted_to'=>storage_path('app/private/qr_exports/DUMMY/final')
    ], 200)]);

    $this->artisan('close-er')
        ->expectsOutputToContain('ER code:')
        ->expectsOutputToContain('QR export OK')
        ->expectsOutputToContain('Final ER exported under:')
        ->expectsOutputToContain('Balloting CLOSED.')
        ->assertExitCode(0);
});
*/
