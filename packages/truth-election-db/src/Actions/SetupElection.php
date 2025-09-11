<?php

namespace TruthElectionDb\Actions;

use Symfony\Component\HttpFoundation\Response;
use TruthElection\Actions\InitializeSystem;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\Request;

class SetupElection
{
    use AsAction;

    /**
     * Handle the action. You can customize `$electionPath` and `$precinctPath` if needed.
     */
    public function handle(?string $electionPath = null, ?string $precinctPath = null): array
    {
        return InitializeSystem::run($electionPath, $precinctPath);
    }

    /**
     * Expose as a controller endpoint (e.g., POST /election/setup)
     */
    public function asController(Request $request): Response
    {
        $result = $this->handle(
            electionPath: $request->input('election_path'),
            precinctPath: $request->input('precinct_path'),
        );

        return response()->json($result);
    }
}
