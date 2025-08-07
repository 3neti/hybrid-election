<?php

namespace App\Actions;

use App\Data\{
    ElectionReturnData,
    PrecinctData,
    VoteCountData,
    ElectoralInspectorData
};
use App\Models\{ElectionReturn, Precinct};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\DataCollection;
use App\Services\VoteTallyService;

class GenerateElectionReturn
{
    use AsAction;

    public function __construct(
        protected VoteTallyService $tallyService,
    ) {}

    public function handle(Precinct $precinct): ElectionReturnData
    {
        $tallies = $this->tallyService->fromPrecinct($precinct);

        $existing = ElectionReturn::where('precinct_id', $precinct->id)->first();

        $electionReturn = ElectionReturn::updateOrCreate(
            ['precinct_id' => $precinct->id],
            [
                'code' => $existing?->code ?? Str::upper(Str::random(12)),
                'signatures' => new DataCollection(
                    ElectoralInspectorData::class,
                    $precinct->electoral_inspectors
                ),
            ]
        );

        return new ElectionReturnData(
            id: $electionReturn->id,
            code: $electionReturn->code,
            precinct: PrecinctData::from($precinct),
            tallies: $tallies,
            signatures: new DataCollection(
                ElectoralInspectorData::class,
                $electionReturn->signatures
            ),
            created_at: $electionReturn->created_at,
            updated_at: $electionReturn->updated_at,
        );
    }

//    public function handle(Precinct $precinct): ElectionReturnData
//    {
//        // 🗳️ Tally the votes
//        $tally = [];
//
//        foreach ($precinct->ballots as $ballot) {
//            foreach ($ballot->votes as $vote) {
//                $positionCode = $vote->position->code;
//
//                foreach ($vote->candidates as $candidate) {
//                    $candidateCode = $candidate->code;
//                    $key = "{$positionCode}_{$candidateCode}";
//
//                    if (!isset($tally[$key])) {
//                        $tally[$key] = [
//                            'position_code'   => $positionCode,
//                            'candidate_code'  => $candidateCode,
//                            'candidate_name'  => $candidate->name,
//                            'count'           => 0,
//                        ];
//                    }
//
//                    $tally[$key]['count']++;
//                }
//            }
//        }
//
//        // ✅ Group by position and sort descending
//        $groupedTallies = collect($tally)
//            ->values()
//            ->groupBy('position_code')
//            ->map(fn ($group) => $group->sortByDesc('count')->values())
//            ->flatten(1);
//
//        // 🧾 Persist the ElectionReturn model
//        $existing = ElectionReturn::where('precinct_id', $precinct->id)->first();
//
//        $electionReturn = ElectionReturn::updateOrCreate(
//            ['precinct_id' => $precinct->id],
//            [
//                'code' => $existing?->code ?? Str::upper(Str::random(12)),
//                'signatures' => new DataCollection(
//                    ElectoralInspectorData::class,
//                    $precinct->electoral_inspectors
//                ),
//            ]
//        );
//
//        // 📦 Return hydrated DTO
//        return new ElectionReturnData(
//            id: $electionReturn->id,
//            code: $electionReturn->code,
//            precinct: PrecinctData::from($precinct),
//            tallies: new DataCollection(
//                VoteCountData::class,
//                $groupedTallies->map(fn ($row) => new VoteCountData(...$row))
//            ),
//            signatures: new DataCollection(
//                ElectoralInspectorData::class,
//                $electionReturn->signatures
//            ),
//            created_at: $electionReturn->created_at,
//            updated_at: $electionReturn->updated_at,
//        );
//    }

    public function rules(): array
    {
        return [
            'precinct_code' => 'required|string|exists:precincts,code',
        ];
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $precinct = Precinct::where('code', $request->validated('precinct_code'))->firstOrFail();

        $electionReturn = $this->handle($precinct);

        return response()->json($electionReturn);
    }
}
