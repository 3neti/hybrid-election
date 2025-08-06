<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class VoteCountData extends Data
{
    public function __construct(
        public string $position_code,
        public string $candidate_code,
        public string $candidate_name,
        public int $count,
    ) {}
}
