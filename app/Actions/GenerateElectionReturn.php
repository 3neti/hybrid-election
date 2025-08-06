<?php

namespace App\Actions;

use App\Data\{ElectionReturnData, PrecinctData, VoteCountData};
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\DataCollection;
use Illuminate\Http\JsonResponse;
use App\Models\Precinct;

class GenerateElectionReturn
{
    use AsAction;

    public function handle(Precinct $precinct): ElectionReturnData
    {
        $ballots = $precinct->ballots;

        $tally = [];

        foreach ($ballots as $ballot) {
            foreach ($ballot->votes as $vote) {
                $positionCode = $vote->position->code;

                foreach ($vote->candidates as $candidate) {
                    $candidateCode = $candidate->code;
                    $key = "{$positionCode}_{$candidateCode}";

                    if (!isset($tally[$key])) {
                        $tally[$key] = [
                            'position_code' => $positionCode,
                            'candidate_code' => $candidate->code,
                            'candidate_name' => $candidate->name,
                            'count' => 0,
                        ];
                    }

                    $tally[$key]['count'] += 1;
                }
            }
        }

        // ✅ Group and sort by position_code and count
        $groupedTallies = collect($tally)
            ->values()
            ->groupBy('position_code')
            ->map(fn ($group) => $group->sortByDesc('count')->values())
            ->flatten(1);

        return new ElectionReturnData(
            precinct: PrecinctData::from($precinct),
            tallies: new DataCollection(
                VoteCountData::class,
                $groupedTallies->map(fn ($row) => new VoteCountData(...$row))
            )
        );
//        $groupedTallies = collect($tally)
//            ->values()
//            ->groupBy('position_code')
//            ->map(function ($group) {
//                return $group->sortByDesc('count')->values();
//            })
//            ->flatten(1);
//
//        return new ElectionReturnData(
//            precinct_code: $precinct->code,
//            tallies: new DataCollection(
//                VoteCountData::class,
//                $groupedTallies->map(fn ($row) => new VoteCountData(...$row))
//            )
//        );
    }

//    public function handle(Precinct $precinct): ElectionReturnData
//    {
//        $ballots = $precinct->ballots;
//
//        $tally = [];
//
//        foreach ($ballots as $ballot) {
//            foreach ($ballot->votes as $vote) {
//                $positionCode = $vote->position->code;
//
//                foreach ($vote->candidates as $candidate) {
//                    $candidateCode = $candidate->code;
//                    $key = "{$positionCode}_{$candidateCode}";
//
//                    if (!isset($tally[$key])) {
//                        $tally[$key] = [
//                            'position_code' => $positionCode,
//                            'candidate_code' => $candidate->code,
//                            'candidate_name' => $candidate->name,
//                            'count' => 0,
//                        ];
//                    }
//
//                    $tally[$key]['count'] += 1;
//                }
//            }
//        }
//
//        return new ElectionReturnData(
//            precinct_code: $precinct->code,
//            tallies: new DataCollection(
//                VoteCountData::class,
//                collect($tally)->values()->map(
//                    fn ($row) => new VoteCountData(...$row)
//                )
//            )
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
        $precinct_code = $request->validated("precinct_code");
        $precinct = Precinct::where('code', $precinct_code)->firstOrFail();

        $electionReturn = $this->handle($precinct);

        return response()->json($electionReturn);
    }
}
