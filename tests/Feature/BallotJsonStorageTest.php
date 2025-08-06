use App\Data\{BallotData, VoteData, PositionData, CandidateData};
use App\Enums\Level;
use App\Models\{Ballot, Precinct};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\LaravelData\DataCollection;

uses(RefreshDatabase::class);

it('stores and retrieves a ballot with JSON votes and precinct_id correctly', function () {
// Create a precinct
$precinct = Precinct::factory()->create();

// Create the data structure
$votes = new DataCollection(VoteData::class, [
new VoteData(
new PositionData('PRESIDENT', 'President of the Philippines', level: Level::NATIONAL, count: 1),
new DataCollection(CandidateData::class, [
new CandidateData('uuid-bbm', 'Ferdinand Marcos Jr.', 'BBM')
])
),
new VoteData(
new PositionData('SENATOR', 'Senator of the Philippines', level: Level::NATIONAL, count: 12),
new DataCollection(CandidateData::class, [
new CandidateData('uuid-jdc', 'Juan Dela Cruz', 'JDC'),
new CandidateData('uuid-mrp', 'Maria Rosario P.', 'MRP')
])
),
]);

// Store in DB
$ballot = Ballot::create([
'code' => 'BALLOT-001',
'votes' => $votes,
'precinct_id' => $precinct->id,
]);

$ballot->refresh();

// Assert database record
$this->assertDatabaseHas('ballots', [
'code' => 'BALLOT-001',
'precinct_id' => $precinct->id,
]);

// Ensure casted structure
expect($ballot->votes)->toBeInstanceOf(DataCollection::class)
->and($ballot->votes)->toHaveCount(2)
->and($ballot->votes->first())->toBeInstanceOf(VoteData::class)
->and($ballot->votes->first()->position->code)->toBe('PRESIDENT')
->and($ballot->votes->first()->candidates->first()->alias)->toBe('BBM');
});

it('stores and hydrates BallotData via getData()', function () {
$precinct = Precinct::factory()->create();

$votes = new DataCollection(VoteData::class, [
new VoteData(
new PositionData('PRESIDENT', 'President of the Philippines', Level::NATIONAL, 1),
new DataCollection(CandidateData::class, [
new CandidateData('uuid-bbm', 'Ferdinand Marcos Jr.', 'BBM')
])
),
new VoteData(
new PositionData('SENATOR', 'Senator of the Philippines', Level::NATIONAL, 12),
new DataCollection(CandidateData::class, [
new CandidateData('uuid-jdc', 'Juan Dela Cruz', 'JDC'),
new CandidateData('uuid-mrp', 'Maria Rosario P.', 'MRP')
])
)
]);

$ballot = Ballot::create([
'code' => 'BALLOT-001',
'votes' => $votes,
'precinct_id' => $precinct->id,
]);

$ballot->refresh();

// Basic assertions
expect($ballot->votes)->toBeInstanceOf(DataCollection::class)
->and($ballot->votes->first())->toBeInstanceOf(VoteData::class);

// ✅ Now test getData()
$ballotData = $ballot->getData();

expect($ballotData)->toBeInstanceOf(BallotData::class)
->and($ballotData->code)->toBe('BALLOT-001')
->and($ballotData->votes)->toBeInstanceOf(DataCollection::class)
->and($ballotData->votes)->toHaveCount(2)
->and($ballotData->votes->first()->position->code)->toBe('PRESIDENT')
->and($ballotData->votes->first()->candidates->first()->alias)->toBe('BBM');
});
