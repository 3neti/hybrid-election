<?php

namespace TruthElectionDb\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use TruthElectionDb\Database\Factories\BallotFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use TruthElection\Data\BallotData;
use Spatie\LaravelData\WithData;
use Illuminate\Support\Carbon;

/**
 * Class Ballot
 *
 * Represents a single ballot submitted during an election.
 * A ballot contains a set of votes, each representing a voter's selection
 * for candidates under specific positions.
 *
 * @property string $id                            The UUID primary key.
 * @property string $code                          The unique identifier for this ballot.
 * @property array $votes                          The set of votes cast within this ballot.
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method string getKey()                         Get the primary key value for the model.
 */
class Ballot extends Model
{
    use HasFactory;
    use HasUuids;
    use WithData;

    protected $fillable = [
        'code',
        'votes',
        'payload_hash',
        'source_ip',
        'user_agent',
        'precinct_code',
    ];

    public static function newFactory(): BallotFactory
    {
        return BallotFactory::new();
    }

    protected $casts = [
        'votes' => 'array'
    ];

    public function precinct()
    {
        return $this->belongsTo(Precinct::class, 'precinct_code', 'code');
    }

    public function dataClass(): BallotData
    {
        return BallotData::from($this);
    }
}
