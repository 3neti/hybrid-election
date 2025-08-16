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
     * @property int $watchers_count
     * @property int $precincts_count
     * @property int $registered_voters_count
     * @property int $actual_voters_count
     * @property int $ballots_in_box_count
     * @property int $unused_ballots_count
     * @property int $spoiled_ballots_count
     * @property int $void_ballots_count
 */
    public function __construct(
        public string $id,
        public string $code,
        public string $location_name,
        public float $latitude,
        public float $longitude,
        public DataCollection $electoral_inspectors,
        public ?int $watchers_count = null,
        public ?int $precincts_count = null,
        public ?int $registered_voters_count = null,
        public ?int $actual_voters_count = null,
        public ?int $number_of_ballots_in_box = null,
        public ?int $number_of_unused_ballots = null,
        public ?int $number_of_spoiled_ballots = null,
        public ?int $number_of_marked_or_void_ballots = null,
    ) {}
}
