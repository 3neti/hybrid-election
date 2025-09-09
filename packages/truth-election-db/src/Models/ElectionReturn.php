<?php

namespace TruthElectionDb\Models;

use TruthElection\Data\{BallotData, ElectionReturnData, ElectoralInspectorData};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use TruthElectionDb\Traits\HasPrecinct;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\WithData;
use DateTimeInterface;

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
    use HasPrecinct;
    use HasFactory;
    use HasUuids;
    use WithData;

    protected string $dataClass = ElectionReturnData::class;

    protected $fillable = [
        'id',
        'code',
        'signatures',
    ];

    protected $with = ['precinct'];

    protected $appends = ['tallies'];

    protected $casts = ['signatures' => 'array'];

//    protected function casts(): array
//    {
//        return [
//            'signatures' => DataCollection::class . ':' . ElectoralInspectorData::class,
//        ];
//    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d\TH:i:sP');
    }

    public function getTalliesAttribute(): array
    {
        return $this->precinct->getTallies()->toArray();
    }

    public function getBallotsAttribute(): DataCollection
    {
        return $this->precinct->getBallots();
    }
}
