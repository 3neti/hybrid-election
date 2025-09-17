<?php

namespace TruthElection\Support;

class ParseCompactBallotFormat
{
    public function __construct(
        protected ElectionStoreInterface $store
    ) {}

    /**
     * Parse a compact ballot format string and return JSON string.
     *
     * Input format:
     *   BAL001|PRESIDENT:LD_001;VICE-PRESIDENT:TH_001
     *
     * Output format (JSON string):
     *   {
     *     "ballot_code": "BAL001",
     *     "precinct_code": "CURRIMAO-001",
     *     "votes": [
     *       {
     *         "position": {...},
     *         "candidates": [{...}]
     *       },
     *       ...
     *     ]
     *   }
     *
     * @param  string  $compact
     * @param  string  $precinctCode
     * @return string  JSON string
     */
    public function __invoke(string $compact, string $precinctCode): string
    {
        [$ballotCode, $votesPart] = explode('|', $compact, 2);

        $votes = [];

        foreach (explode(';', $votesPart) as $entry) {
            [$positionCode, $candidateCodes] = explode(':', $entry, 2);

            $position = $this->store->getPosition($positionCode);
            if (! $position) {
                throw new \RuntimeException("Unknown position code: $positionCode");
            }

            $candidates = [];
            foreach (explode(',', $candidateCodes) as $candidateCode) {
                $candidate = $this->store->getCandidate($candidateCode);
                if (! $candidate) {
                    throw new \RuntimeException("Unknown candidate code: $candidateCode");
                }

                // Add full candidate object (with position reference if necessary)
                $candidates[] = $candidate->toArray();
            }

            $votes[] = [
                'position' => $position->toArray(),
                'candidates' => $candidates,
            ];
        }

        return json_encode([
            'ballot_code'    => $ballotCode,
            'precinct_code'  => $precinctCode,
            'votes'          => $votes,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
