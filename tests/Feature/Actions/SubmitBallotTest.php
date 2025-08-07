<?php

use App\Data\{BallotData, CandidateData, PositionData, VoteData};
use App\Models\{Ballot, Candidate, Position, Precinct};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use function Pest\Laravel\assertDatabaseHas;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Facades\Event;
use App\Events\BallotSubmitted;
use App\Actions\SubmitBallot;
use Illuminate\Support\Str;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->precinct = Precinct::factory()->create();
    $this->position = Position::factory()->create(['count' => 1]);
    $this->candidate = Candidate::factory()->create([
        'position_code' => $this->position->code,
    ]);
});

it('submits a ballot successfully and returns BallotData', function () {
    $votes = new DataCollection(
        VoteData::class,
        [new VoteData(
            position: PositionData::from($this->position),
            candidates: new DataCollection(
                CandidateData::class,
                [CandidateData::from($this->candidate)]
            )
        )]
    );

    $ballotCode = 'BAL-TEST-001';

    $data = SubmitBallot::run(
        precinctId: $this->precinct->id,
        code: $ballotCode,
        votes: $votes,
    );

    expect($data)->toBeInstanceOf(BallotData::class)
        ->and($data->code)->toBe($ballotCode)
        ->and($data->votes)->toHaveCount(1);

    assertDatabaseHas(Ballot::class, [
        'code' => $ballotCode,
        'precinct_id' => $this->precinct->id,
    ]);
});

it('throws exception when precinct is not found', function () {
    $fakePrecinctId = Str::uuid()->toString();

    SubmitBallot::run(
        precinctId: $fakePrecinctId,
        code: 'BAL-INVALID',
        votes: new DataCollection(VoteData::class, [])
    );
})->throws(ModelNotFoundException::class);

it('stores vote content accurately in database', function () {
    $votes = new DataCollection(
        VoteData::class,
        [new VoteData(
            position: PositionData::from($this->position),
            candidates: new DataCollection(
                CandidateData::class,
                [CandidateData::from($this->candidate)]
            )
        )]
    );

    $ballotCode = 'BAL-CHECK-VOTES';

    SubmitBallot::run(
        precinctId: $this->precinct->id,
        code: $ballotCode,
        votes: $votes
    );

    $ballot = Ballot::where('code', $ballotCode)->firstOrFail();

    expect($ballot->votes)->toBeInstanceOf(DataCollection::class)
        ->and($ballot->votes)->toHaveCount(1)
        ->and($ballot->votes->first()->candidates)->toHaveCount(1)
        ->and($ballot->votes->first()->candidates->first()->code)->toBe($this->candidate->code);
});

it('supports submitting multiple candidates for a position', function () {
    $position = Position::factory()->create(['count' => 3]);

    $candidates = Candidate::factory()
        ->count(3)
        ->sequence(
            ['alias' => 'A1', 'position_code' => $position->code],
            ['alias' => 'A2', 'position_code' => $position->code],
            ['alias' => 'A3', 'position_code' => $position->code],
        )
        ->create();

    $votes = new DataCollection(
        VoteData::class,
        [new VoteData(
            position: PositionData::from($position),
            candidates: new DataCollection(
                CandidateData::class,
                $candidates->map(fn ($c) => CandidateData::from($c))
            )
        )]
    );

    $ballotCode = 'BAL-MULTI-CAND';

    $data = SubmitBallot::run(
        precinctId: $this->precinct->id,
        code: $ballotCode,
        votes: $votes
    );

    expect($data->votes[0]->candidates)->toHaveCount(3)
        ->and($data->votes[0]->candidates[0])->toBeInstanceOf(CandidateData::class);
});

it('dispatches BallotSubmitted event on successful ballot submission', function () {
    Event::fake();

    $votes = new DataCollection(
        VoteData::class,
        [new VoteData(
            position: PositionData::from($this->position),
            candidates: new DataCollection(
                CandidateData::class,
                [CandidateData::from($this->candidate)]
            )
        )]
    );

    $ballotCode = 'BAL-WITH-EVENT';

    $data = SubmitBallot::run(
        precinctId: $this->precinct->id,
        code: $ballotCode,
        votes: $votes,
    );

    Event::assertDispatched(BallotSubmitted::class, function ($event) use ($data, $ballotCode) {
        return $event->ballot->id === $data->id
            && $event->ballot->code === $ballotCode;
    });
});
