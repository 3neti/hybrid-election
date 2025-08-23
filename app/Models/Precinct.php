<?php

namespace App\Models;

use App\Traits\{HasAdditionalPrecinctAttributes, HasMeta};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Data\{BallotData, PrecinctData};
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Facades\Cache;
use App\Data\ElectoralInspectorData;
use App\Services\VoteTallyService;
use Spatie\LaravelData\WithData;

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
 * @property int $watchers_count
 * @property int $precincts_count
 * @property int $registered_voters_count
 * @property int $actual_voters_count
 * @property int $ballots_in_box_count
 * @property int $unused_ballots_count
 * @property int $spoiled_ballots_count
 * @property int $void_ballots_count
 */
class Precinct extends Model
{
    /** @use HasFactory<\Database\Factories\PrecinctFactory> */
    use HasAdditionalPrecinctAttributes;
    use HasFactory;
    use HasUuids;
    use WithData;
    use HasMeta;

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
     * Remove from cache.
     *
     */
    protected static function booted() {
        static::saved(fn() => Cache::forget('shared.precinct'));
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
