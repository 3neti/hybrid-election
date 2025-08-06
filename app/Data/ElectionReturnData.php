<?php

namespace App\Data;

use Spatie\LaravelData\{Data, DataCollection};

class ElectionReturnData extends Data
{
    public function __construct(
        public PrecinctData $precinct,
        /** @var DataCollection<VoteCountData> */
        public DataCollection $tallies
    ) {}
}
