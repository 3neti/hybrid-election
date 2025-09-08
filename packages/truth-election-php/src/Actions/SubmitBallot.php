<?php

namespace TruthElection\Actions;

use TruthElection\Support\InMemoryElectionStore;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Collection;
use TruthElection\Data\BallotData;
use TruthElection\Data\VoteData;

class SubmitBallot
{
    use AsAction;


    public function handle(string $ballotCode, string $precinctCode, Collection $votes): BallotData
    {
        $store = InMemoryElectionStore::instance();

        if (!isset($store->precincts[$precinctCode])) {
            throw new \RuntimeException("Precinct [$precinctCode] not found.");
        }

        $precinct = $store->precincts[$precinctCode];

        $ballot = new BallotData(
            code: $ballotCode,
            votes: new DataCollection(VoteData::class, $votes->all()),
        );

        // ✳️ Attach ballot to the precinct (replace or push to collection)
        $existingBallots = $precinct->ballots ?? new DataCollection(BallotData::class, []);

        $updatedBallots = new DataCollection(
            BallotData::class,
            $existingBallots->toCollection()->push($ballot)
        );

        // 💾 Update the precinct in store with new ballots
        $store->precincts[$precinctCode] = $precinct->copyWith([
            'ballots' => $updatedBallots,
        ]);


        // 🆕 Important: Also store in ballots array
        $store->putBallot($ballot, $precinctCode);

        return $ballot;
    }
}
