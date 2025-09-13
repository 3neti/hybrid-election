<?php

namespace TruthElection\Actions;

use TruthElection\Support\ElectionStoreInterface;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use TruthElection\Data\{
    ElectionReturnData,
    VoteCountData,
    PrecinctData,
    BallotData,
};

class GenerateElectionReturn
{
    use AsAction;

    public function __construct(protected ElectionStoreInterface $store){}

    public function handle(string $precinctCode): ElectionReturnData
    {
        $store = $this->store;
        $precinct = $store->getPrecinct($precinctCode);
        if (!$precinct) {
            throw new \RuntimeException("Precinct [$precinctCode] not found.");
        }
        $ballots = $store->getBallots($precinctCode);

        // 🗳️ Tally votes
        $tallies = [];

        foreach ($ballots as $ballot) {
            foreach ($ballot->votes as $vote) {
                $positionCode = $vote->position->code;

                // ✳️ Enforce max allowed selections per position
                $maxSelections = $vote->position->maxSelections();

                if (count($vote->candidates) > $maxSelections) {
                    continue; // skip overvote
                }

                foreach ($vote->candidates as $candidate) {
                    $candidateCode = $candidate->code;
                    $key = "{$positionCode}_{$candidateCode}";

                    if (!isset($tallies[$key])) {
                        $tallies[$key] = [
                            'position_code'   => $positionCode,
                            'candidate_code'  => $candidate->code,
                            'candidate_name'  => $candidate->name,
                            'count'           => 0,
                        ];
                    }

                    $tallies[$key]['count']++;
                }
            }
        }

        $voteCounts = collect($tallies)
            ->values()
            ->groupBy('position_code')
            ->map(fn ($group) => $group->sortByDesc('count')->values())
            ->flatten(1)
            ->map(fn ($row) => new VoteCountData(
                position_code: $row['position_code'],
                candidate_code: $row['candidate_code'],
                candidate_name: $row['candidate_name'],
                count: $row['count'],
            ));

        // 🆔 Generate ID and code (in real app, these would be persisted)
        $electionReturnId = (string) Str::uuid();
        $electionReturnCode = strtoupper(Str::random(12));
        $timestamp = Carbon::now();

        $electionReturn =  new ElectionReturnData(
            id: $electionReturnId,
            code: $electionReturnCode,
            precinct: PrecinctData::from($precinct),
            tallies: new DataCollection(VoteCountData::class, $voteCounts->all()),
            signatures: $store->getInspectorsForPrecinct($precinctCode),
            ballots: new DataCollection(BallotData::class, $ballots->all()),
            created_at: $timestamp,
            updated_at: $timestamp,
        );

        $store->putElectionReturn($electionReturn);

        return $electionReturn;
    }
}
