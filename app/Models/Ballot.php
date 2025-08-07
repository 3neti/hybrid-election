<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataCollection;
use App\Data\{BallotData, VoteData};
use Spatie\LaravelData\WithData;
use App\Traits\HasPrecinct;

/**
 * Class Ballot
 *
 * Represents a single ballot submitted during an election.
 * A ballot contains a set of votes, each representing a voter's selection
 * for candidates under specific positions.
 *
 * @property string $id                            The UUID primary key.
 * @property string $code                          The unique identifier for this ballot.
 * @property DataCollection<VoteData> $votes       The set of votes cast within this ballot.
 * @property string $precinct_id                   Foreign key linking to the parent precinct.
 * @property \App\Models\Precinct $precinct        The precinct to which this ballot belongs.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @method string getKey()                         Get the primary key value for the model.
 */
class Ballot extends Model
{
    use HasPrecinct;
    use HasFactory;
    use HasUuids;
    use WithData;

    /**
     * The data class used to represent this model as a DTO.
     */
    protected string $dataClass = BallotData::class;

    /**
     * Attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'code',
        'votes',
    ];

    /**
     * Relationships that should be eager-loaded.
     *
     * @var array<int, string>
     */
    protected $with = ['precinct'];

    /**
     * Attribute casting definitions.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'votes' => DataCollection::class . ':' . VoteData::class,
        ];
    }
}
