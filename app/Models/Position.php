<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Level;

/**
 * Class Position.
 *
 * @property int                         $id
 * @property string                      $code
 * @property string                      $name
 * @property string                      $level
 * @property int                         $count
 *
 * @method int getKey()
 */
class Position extends Model
{
    /** @use HasFactory<\Database\Factories\PositionFactory> */
    use HasFactory;

    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'name',
        'level',
        'count',
    ];

    protected $casts = [
        'level' => Level::class,
    ];
}
