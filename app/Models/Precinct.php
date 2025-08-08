<?php

namespace App\Models;

use App\Data\BallotData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataCollection;
use App\Data\ElectoralInspectorData;
use App\Services\VoteTallyService;
use Spatie\LaravelData\WithData;
use App\Data\PrecinctData;

/**
 * Class Precinct
 *
 * Represents a precinct in the electoral system where voting takes place.
 * A precinct can have multiple ballots and is monitored by electoral inspectors.
 *
 * @property string $id                                  The UUID primary key.
 * @property string $code                                Unique code identifying the precinct.
 * @property string|null $location_name                  Optional human-readable name for the precinct location.
 * @property float|null $latitude                        Latitude coordinate of the precinct.
 * @property float|null $longitude                       Longitude coordinate of the precinct.
 * @property DataCollection<ElectoralInspectorData> $electoral_inspectors
 *                                                       JSON-cast collection of electoral inspector data.
 * @property \Illuminate\Support\Carbon $created_at      Timestamp of when the record was created.
 * @property \Illuminate\Support\Carbon $updated_at      Timestamp of the last update.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ballot[] $ballots
 *                                                       The ballots submitted in this precinct.
 * @property-read \Spatie\LaravelData\DataCollection|\App\Data\VoteCountData[] $tallies
 *                                                       Computed tallies of votes per candidate per position.
 */
class Precinct extends Model
{
    /** @use HasFactory<\Database\Factories\PrecinctFactory> */
    use HasFactory;
    use HasUuids;
    use WithData;

    /**
     * The associated data transfer class.
     */
    protected string $dataClass = PrecinctData::class;

    /**
     * Attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'id',
        'code',
        'location_name',
        'latitude',
        'longitude',
        'electoral_inspectors',
    ];

    /**
     * Attribute casting definitions.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'electoral_inspectors' => DataCollection::class . ':' . ElectoralInspectorData::class,
        ];
    }

    /**
     * Get the ballots cast in this precinct.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ballots(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ballot::class);
    }

    /**
     * Compute the vote tallies for the precinct using the VoteTallyService.
     *
     * @return \Spatie\LaravelData\DataCollection|\App\Data\VoteCountData[]
     */
    public function getTallies(): DataCollection
    {
        return app(VoteTallyService::class)->fromPrecinct($this);
    }

    public function getBallots(): DataCollection
    {
        return new DataCollection(BallotData::class, $this->ballots);
    }
}
