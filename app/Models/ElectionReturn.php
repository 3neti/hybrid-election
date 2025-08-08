<?php

namespace App\Models;

use App\Data\{BallotData, ElectionReturnData, ElectoralInspectorData};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\WithData;
use App\Traits\HasPrecinct;

/**
 * Class ElectionReturn
 *
 * Represents the official summary of election results for a given precinct.
 * This model aggregates and signs the tallies of votes cast via the associated ballots.
 *
 * @property string $id                                   The UUID primary key.
 * @property string $code                                 Unique code identifying this election return.
 * @property DataCollection<ElectoralInspectorData> $signatures
 *                                                        Digital signatures from electoral inspectors.
 * @property string $precinct_id                          Foreign key reference to the associated precinct.
 * @property \Illuminate\Support\Carbon $created_at       Timestamp when the record was created.
 * @property \Illuminate\Support\Carbon $updated_at       Timestamp when the record was last updated.
 *
 * @property-read \App\Models\Precinct $precinct          The associated precinct that submitted ballots.
 * @property-read DataCollection<BallotData> $ballots     (Virtual) JSON-cast ballots data [unused unless hydrated manually].
 * @property-read DataCollection|\App\Data\VoteCountData[] $tallies
 *                                                        Computed vote tallies per candidate per position (accessor).
 */
class ElectionReturn extends Model
{
    /** @use HasFactory<\Database\Factories\ElectionReturnFactory> */
    use HasPrecinct;
    use HasFactory;
    use HasUuids;
    use WithData;

    /**
     * The data class that represents this model as a DTO.
     */
    protected string $dataClass = ElectionReturnData::class;

    /**
     * Attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'id',
        'code',
        'signatures',
    ];

    /**
     * Relationships that should be eagerly loaded.
     *
     * @var array<int, string>
     */
    protected $with = ['precinct'];

    /**
     * Accessors that should be appended to model's array/json form.
     *
     * @var array<int, string>
     */
    protected $appends = ['tallies'];

    /**
     * Attribute casting definitions.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'signatures' => DataCollection::class . ':' . ElectoralInspectorData::class,
        ];
    }

    /**
     * Compute vote tallies by delegating to the precinct's tally service.
     *
     * @return \Spatie\LaravelData\DataCollection|\App\Data\VoteCountData[]
     */
    public function getTalliesAttribute(): DataCollection
    {
        return $this->precinct->getTallies();
    }

    public function getBallotsAttribute(): DataCollection
    {
        return $this->precinct->getBallots();
    }
}
