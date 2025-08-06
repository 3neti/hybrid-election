<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataCollection;
use App\Data\{BallotData, PrecinctData, VoteData};
use Spatie\LaravelData\WithData;

/**
 * Class Ballot.
 *
 * @property string $id
 * @property string $code
 * @property DataCollection<VoteData> $votes
 * @property Precinct $precinct
 *
 * @method string getKey()
 */
class Ballot extends Model
{
    use HasFactory;
    use HasUuids;
    use WithData;

    protected string $dataClass = BallotData::class;

    protected $fillable = [
        'code',
        'votes',
        'precinct_id',
        'precinct',
    ];

    protected $with = ['precinct'];

    protected function casts(): array
    {
        return [
            'votes' => DataCollection::class . ':' . VoteData::class,
//            'precinct' => PrecinctData::class,
        ];
    }

    public function precinct(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Precinct::class);
    }

    public function setPrecinctAttribute(Precinct|string $precinct): static
    {
        if (is_string($precinct)) {
            $this->precinct_id = $precinct;
        } elseif ($precinct instanceof Precinct) {
            $this->precinct_id = $precinct->id;
        }

        return $this;
    }

    public function getPrecinctAttribute(): Precinct
    {
        return $this->getRelationValue('precinct');
    }
}
