<?php

namespace TruthElection\Actions;

use TruthElection\Data\{ElectionReturnData, FinalizeErContext, PrecinctData};
use TruthElection\Support\InMemoryElectionStore;
use TruthElection\Pipes\ValidateSignatures;
use Lorisleiva\Actions\Concerns\AsAction;
use TruthElection\Pipes\CloseBalloting;
use Illuminate\Pipeline\Pipeline;
use RuntimeException;

class FinalizeElectionReturn
{
    use AsAction;

    public function handle(
        string $precinctCode,
        string $disk = 'local',
        string $payload = 'minimal',
        int $maxChars = 1200,
        string $dir = 'final',
        bool $force = false,
    ): ElectionReturnData {
        $store = InMemoryElectionStore::instance();

        $precinct = $store->precincts[$precinctCode] ?? null;
        if (! $precinct instanceof PrecinctData) {
            throw new RuntimeException("Precinct [$precinctCode] not found.");
        }

        if (! $force && ($precinct->meta['balloting_open'] ?? true) === false) {
            throw new RuntimeException("Balloting already closed. Nothing to do.");
        }

        $er = $store->electionReturns[$precinctCode] ?? null;
        if (! $er instanceof ElectionReturnData) {
            throw new RuntimeException("Election Return for [$precinctCode] not found.");
        }

        $ctx = new FinalizeErContext(
            precinct: $precinct,
            er: $er,
            disk: $disk,
            folder: "ER-{$er->code}/{$dir}",
            payload: $payload,
            maxChars: $maxChars,
            force: $force,
        );

        $configuredPipes = config('truth-election.finalize_election_return.pipes', []);

        app(Pipeline::class)
            ->send($ctx)
            ->through(array_merge(
                [ValidateSignatures::class],
                $configuredPipes,
                [CloseBalloting::class]
            ))
            ->thenReturn();

        return $ctx->er;
    }
}
