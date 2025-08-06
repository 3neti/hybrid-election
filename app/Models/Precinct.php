<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataCollection;
use App\Data\ElectoralInspectorData;
use Spatie\LaravelData\WithData;
use App\Data\PrecinctData;

class Precinct extends Model
{
    /** @use HasFactory<\Database\Factories\PrecinctFactory> */
    use HasFactory;
    use HasUuids;
    use WithData;

    protected string $dataClass = PrecinctData::class;

    protected $fillable = [
        'id',
        'code',
        'location_name',
        'latitude',
        'longitude',
        'electoral_inspectors',
    ];

    protected function casts(): array
    {
        return [
            'electoral_inspectors' => DataCollection::class . ':' . ElectoralInspectorData::class,
        ];
    }

    public function ballots(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ballot::class);
    }
}
