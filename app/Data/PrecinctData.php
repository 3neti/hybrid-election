<?php

namespace App\Data;

use Spatie\LaravelData\{Data, DataCollection};

class PrecinctData extends Data
{
    /**
     * @param string $id
     * @param string $code
     * @param string $location_name
     * @param float $latitude
     * @param float $longitude
     * @param DataCollection<ElectoralInspectorData> $electoral_inspectors
     */
    public function __construct(
        public string $id,
        public string $code,
        public string $location_name,
        public float $latitude,
        public float $longitude,
        public DataCollection $electoral_inspectors,
    ) {}
}
