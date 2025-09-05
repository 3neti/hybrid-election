<?php

namespace TruthElection\Actions;

use TruthElection\Support\InMemoryElectionStore;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Collection;
use TruthElection\Data\BallotData;
use TruthElection\Data\VoteData;

use Spatie\LaravelData\DataCollection;
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
        if (!isset($store->precincts[$precinctCode])) {
            throw new \RuntimeException("Precinct [$precinctCode] not found.");
        }

        $ballot = new BallotData(
            id: $ballotCode, // In production, replace with ULID/UUID
            code: $ballotCode,
            votes: new DataCollection(VoteData::class, $votes->all()),
            precinct: $precinct
        );

        $store->putBallot($ballot);

        return $ballot;
    }
}
