<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class PrecinctData extends Data
{
    public function __construct(
        public string $id,
        public string $code,
        public string $location_name,
        public float $latitude,
        public float $longitude,
        public string $chairman_name,
        public string $member1_name,
        public string $member2_name,
    ) {}
}
