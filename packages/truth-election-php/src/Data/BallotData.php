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
    public string $precinct_code;

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

    public function mergeWith(BallotData $other): BallotData
    {
        $votesByPosition = collect($this->votes->all())
            ->merge($other->votes->all())
            ->groupBy(fn(VoteData $vote) => $vote->position->code);

        $mergedVotes = $votesByPosition->map(function ($votes, $positionCode) {
            /** @var DataCollection<VoteData> $votes */

            // Get the position info (same across grouped VoteData)
            /** @var PositionData $position */
            $position = $votes->first()->position;

            // Flatten candidates from incoming ballot first (later merged)
            $allCandidates = collect($votes)
                ->reverse() // Prioritize later votes (from $other)
                ->flatMap(fn (VoteData $vote) => $vote->candidates->all())
                ->unique(fn (CandidateData $c) => $c->code)
                ->take($position->count); // 🧠 enforce max count

            return new VoteData(new DataCollection(CandidateData::class, $allCandidates->values()->all()));
        });

        return new BallotData(
            code: $this->code,
            votes: new DataCollection(VoteData::class, $mergedVotes->values()->all())
        );
    }

//    public function mergeWith(BallotData $other): BallotData
//    {
//        // Group VoteData by position code for both ballots
//        $votesByPosition = collect($this->votes->all())
//            ->merge($other->votes->all())
//            ->groupBy(fn (VoteData $vote) => $vote->position->code);
//
//        // Rebuild unique VoteData per position
//        $mergedVotes = $votesByPosition->map(function ($votes, $positionCode) {
//            /** @var DataCollection<VoteData> $votes */
//            $allCandidates = $votes->flatMap(fn (VoteData $vote) => $vote->candidates->all());
//
//            $uniqueCandidates = $allCandidates->unique(fn (CandidateData $candidate) => $candidate->code);
//
//            return new VoteData(new DataCollection(CandidateData::class, $uniqueCandidates->values()->all()));
//        });
//
//        return new BallotData(
//            code: $this->code,
//            votes: new DataCollection(VoteData::class, $mergedVotes->values()->all())
//        );
//    }

//    public function mergeWith(BallotData $other): BallotData
//    {
//        $thisVotes  = $this->votes->toCollection()->keyBy(fn (VoteData $v) => $v->position->code);
//        $otherVotes = $other->votes->toCollection()->keyBy(fn (VoteData $v) => $v->position->code);
//
//        // Votes from $other take precedence
//        $mergedVotes = $thisVotes->merge($otherVotes)->values();
//
//        return new self(
//            code: $this->code,
//            votes: new DataCollection(VoteData::class, $mergedVotes),
//        );
//    }
}
