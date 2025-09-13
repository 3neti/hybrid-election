<?php

namespace TruthElectionDb\Actions;

use TruthElection\Actions\GenerateElectionReturn;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Http\JsonResponse;

class TallyVotes extends GenerateElectionReturn
{
    public function rules(): array
    {
        return [
            'precinct_code' => ['required', 'string'],
        ];
    }

    public function asController(ActionRequest $request): \Illuminate\Http\Response|JsonResponse
    {
        $precinctCode = $request->get('precinct_code');
        $electionReturn = $this->handle($precinctCode);

        return response()->json($electionReturn);
    }
}
