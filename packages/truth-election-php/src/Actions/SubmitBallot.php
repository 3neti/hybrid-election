<?php

namespace TruthElection\Actions;

use TruthElection\Support\ElectionStoreInterface;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Collection;
use TruthElection\Data\BallotData;
use TruthElection\Data\VoteData;

class SubmitBallot
{
    use AsAction;


    public function __construct(protected ElectionStoreInterface $store){}

    public function handle(string $ballotCode, string $precinctCode, Collection $votes): BallotData
    {
        $store = $this->store;

        $precinct = $store->getPrecinct($precinctCode);

        if (!$precinct) {
            throw new \RuntimeException("Precinct [$precinctCode] not found.");
        }

        $ballot = new BallotData(
            code: $ballotCode,
            votes: new DataCollection(VoteData::class, $votes->all()),
        );

        // 🆕 Important: Also store in ballots array
        $store->putBallot($ballot, $precinctCode);

        return $ballot;
    }
}
