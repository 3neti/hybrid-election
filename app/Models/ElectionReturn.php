<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataCollection;
use App\Data\ElectoralInspectorData;

/**
 * Class ElectionReturn.
 *
 * Represents the official summary of election results for a given precinct.
 * Each election return is uniquely identified by a code and is associated with a precinct.
 * It includes signatures from electoral inspectors to validate the return.
 *
 * @property string $id                        The UUID primary key.
 * @property string $code                      The unique election return code.
 * @property DataCollection<ElectoralInspectorData> $signatures JSON-cast collection of inspector signatures.
 * @property string $precinct_id               Foreign key reference to the precinct.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read Precinct $precinct           The related precinct.
 */
class ElectionReturn extends Model
{
    /** @use HasFactory<\Database\Factories\ElectionReturnFactory> */
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'id',
        'code',
        'signatures',
        'precinct_id',
    ];

    protected function casts(): array
    {
        return [
            'signatures' => DataCollection::class . ':' . ElectoralInspectorData::class,
        ];
    }

    public function precinct(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Precinct::class);
    }
}
