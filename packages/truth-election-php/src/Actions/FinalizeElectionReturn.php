<?php

namespace TruthElection\Actions;

use TruthElection\Pipes\CloseBalloting;
use TruthElection\Support\InMemoryElectionStore;
use TruthElection\Data\FinalizeErContext;
use TruthElection\Data\ElectionReturnData;
use TruthElection\Data\PrecinctData;
use Illuminate\Pipeline\Pipeline;
use Lorisleiva\Actions\Concerns\AsAction;
use RuntimeException;
use TruthElection\Pipes\ValidateSignatures;

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

        app(Pipeline::class)
            ->send($ctx)
            ->through([
                ValidateSignatures::class,
                // ExportErJson::class,
                // PerformQrExport::class,
                // MirrorQrArtifacts::class,
                 CloseBalloting::class,
            ])
            ->thenReturn();

        return $ctx->er;
    }
}
