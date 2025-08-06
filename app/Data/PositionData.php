<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use App\Enums\Level;

class PositionData extends Data
{
    public function __construct(
        public string $code,   // e.g. "PRESIDENT"
        public string $name,   // e.g. "President of the Philippines"
        #[WithCast(EnumCast::class, Level::class)]
        public Level $level,  // e.g. "national"
        public int $count      // number of candidates allowed to be voted
    ) {}
}
