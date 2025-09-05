<?php

namespace TruthElection\Data;

use Spatie\LaravelData\{Data, DataCollection};

class VoteData extends Data
{
    /**
     * @param DataCollection<CandidateData> $candidates
     */
    public function __construct(
        public PositionData $position,
        public DataCollection $candidates
    ) {}
}
