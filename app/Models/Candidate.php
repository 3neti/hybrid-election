<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Candidate.
 *
 * @property int                         $id
 * @property string                      $code
 * @property string                      $name
 * @property string                      $alias
 * @property string                      $position_code
 * @property-read Position               $position
 *
 * @method int getKey()
 */
class Candidate extends Model
{
    /** @use HasFactory<\Database\Factories\CandidateFactory> */
    use HasFactory;

    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code', 'name', 'alias', 'position_code',
    ];

    /**
     * Belongs to Position using 'code' as foreign key.
     */
    public function position(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_code', 'code');
    }

    /**
     * Set the position using a Position model or position code string.
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
