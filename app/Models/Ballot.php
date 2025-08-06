<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataCollection;
use App\Data\{BallotData, VoteData};
use Spatie\LaravelData\WithData;

/**
 * Class Ballot.
 *
 * @property string $id
 * @property string $code
 * @property DataCollection<VoteData> $votes
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
    ];

    protected function casts(): array
    {
        return [
            'votes' => DataCollection::class . ':' . VoteData::class,
        ];
    }

    public function precinct(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Precinct::class);
    }
}
