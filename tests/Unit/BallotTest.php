use App\Models\{Ballot, Precinct};
use App\Data\{BallotData, VoteData};
use Spatie\LaravelData\DataCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a ballot using the factory', function () {
// Create a related precinct
$precinct = Precinct::factory()->create();

// Create ballot using factory, linking to precinct
$ballot = Ballot::factory()->create([
'precinct_id' => $precinct->id,
]);

// Assert relationships and structure
expect($ballot)->toBeInstanceOf(Ballot::class)
->and($ballot->code)->toStartWith('BALLOT-')
->and($ballot->votes)->toBeInstanceOf(DataCollection::class)
->and($ballot->votes)->toHaveCount(2)
->and($ballot->votes->first())->toBeInstanceOf(VoteData::class)
->and($ballot->precinct)->toBeInstanceOf(Precinct::class)
->and($ballot->precinct->id)->toBe($precinct->id);

// Check the toData() output
$data = $ballot->getData();

expect($data)->toBeInstanceOf(BallotData::class)
->and($data->code)->toBe($ballot->code)
->and($data->votes)->toBeInstanceOf(DataCollection::class)
->and($data->votes)->toHaveCount(2)
->and($data->votes->first()->position->code)->toBe('PRESIDENT');
});
