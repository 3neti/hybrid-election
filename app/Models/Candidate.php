<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Candidate
 *
 * Represents an electoral candidate running for a specific position in the election.
 * Each candidate is uniquely identified by a string code, and is associated with one position.
 *
 * @property string                      $code           Unique code identifying the candidate (primary key).
 * @property string                      $name           Full name of the candidate.
 * @property string                      $alias          Nickname or ballot name of the candidate.
 * @property string                      $position_code  Foreign key linking to the associated position's code.
 *
 * @property-read \App\Models\Position  $position       The position this candidate is running for.
 *
 * @method string getKey()                              Get the primary key value for the model.
 */
class Candidate extends Model
{
    /** @use HasFactory<\Database\Factories\CandidateFactory> */
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
        'code', 'name', 'alias', 'position_code',
    ];

    /**
     * Get the position that the candidate is running for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_code', 'code');
    }

    /**
     * Set the position for the candidate.
     * Accepts either a Position model or a position code string.
     *
     * @param \App\Models\Position|string $position
     * @return static
     */
    public function setPositionAttribute(Position|string $position): static
    {
        if (is_string($position)) {
            $this->position_code = $position;
        } elseif ($position instanceof Position) {
            $this->position_code = $position->getKey();
        }

        return $this;
    }
}
