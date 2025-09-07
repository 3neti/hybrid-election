<?php

namespace TruthElection\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class BallotData extends Data
{
    /**
     * @param string $id
     * @param string $code
     * @param DataCollection<VoteData> $votes
     * @param PrecinctData|null $precinct
     */
    public function __construct(
        public string $code,
        public DataCollection $votes,
        public ?PrecinctData $precinct = null,
    ) {}
}
