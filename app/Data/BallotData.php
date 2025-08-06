<?php

namespace App\Data;

use Spatie\LaravelData\{Data, DataCollection};

class BallotData extends Data
{
    /**
     * @param string $code
     * @param DataCollection<VoteData> $votes
     */
    public function __construct(
        public string $code,
        public DataCollection $votes
    ) {}
}
