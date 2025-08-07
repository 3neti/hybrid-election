<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Level;

/**
 * Class Position
 *
 * Represents an elective position in the election (e.g., President, Mayor, Councilor).
 * Each position is identified by a unique string code and includes information about
 * its level (national, provincial, city, etc.) and the number of candidates allowed.
 *
 * @property string          $code        Unique code identifier for the position (primary key).
 * @property string          $name        Descriptive name of the position (e.g., "President of the Philippines").
 * @property \App\Enums\Level $level       Enum value indicating the level of the position (e.g., national, local).
 * @property int             $count       Number of individuals that can be elected to this position per precinct.
 *
 * @method string getKey()               Returns the primary key value of the model.
 */
class Position extends Model
{
    /** @use HasFactory<\Database\Factories\PositionFactory> */
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'code',
        'name',
        'level',
        'count',
    ];

    /**
     * The attributes that should be cast to native types or enums.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'level' => Level::class,
    ];
}
