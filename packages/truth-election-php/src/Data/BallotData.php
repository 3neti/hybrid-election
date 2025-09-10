<?php

namespace TruthElection\Data;

use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Data;

/**
 * @property-read string $code
 * @property-read DataCollection<VoteData> $votes
 */
class BallotData extends Data
{
    protected string $precinct_code;

    public function getPrecinctCode(): string
    {
        return $this->precinct_code;
    }

    public function setPrecinctCode(string $precinct_code): static
    {
        $this->precinct_code = $precinct_code;

        return $this;
    }

    /**
     * @param DataCollection<VoteData> $votes
     */
    public function __construct(
        public string $code,
        public DataCollection $votes,
    ) {}
}
