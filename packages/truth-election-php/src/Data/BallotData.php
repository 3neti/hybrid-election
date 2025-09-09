<?php

namespace TruthElection\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @property-read string $code
 * @property-read DataCollection<VoteData> $votes
 */
class BallotData extends Data
{
    /**
     * @param DataCollection<VoteData> $votes
     */
    public function __construct(
        public string $code,
        public DataCollection $votes,
    ) {}
}
