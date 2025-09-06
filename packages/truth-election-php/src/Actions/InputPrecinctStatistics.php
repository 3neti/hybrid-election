<?php

namespace TruthElection\Actions;

use TruthElection\Support\InMemoryElectionStore;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use TruthElection\Data\PrecinctData;
use Illuminate\Http\JsonResponse;

class InputPrecinctStatistics
{
    use AsAction;

    /**
     * Update statistical fields of the given Precinct using the provided payload.
     *
     * @param  string  $precinctCode
     * @param  array   $payload
     * @return PrecinctData
     */
    public function handle(string $precinctCode, array $payload): PrecinctData
    {
        $store = InMemoryElectionStore::instance();
        $precinct = $store->precincts[$precinctCode];

        if (! $precinct) {
            throw new \RuntimeException("Precinct [$precinctCode] not found in memory.");
        }

        $fields = [
            'watchers_count',
            'precincts_count',
            'registered_voters_count',
            'actual_voters_count',
            'ballots_in_box_count',
            'unused_ballots_count',
            'spoiled_ballots_count',
            'void_ballots_count',
        ];

        $data = $precinct->toArray();

        foreach ($fields as $key) {
            if (array_key_exists($key, $payload)) {
                $data[$key] = $payload[$key]; // allow nullable ints
            }
        }

        $updated = PrecinctData::from($data);

        $store->putPrecinct($updated);

        return $updated;
    }

    /**
     * PATCH /precincts/{precinct}/statistics
     *
     * @param  string         $precinct
     * @param  ActionRequest  $request
     * @return JsonResponse
     */
    public function asController(string $precinct, ActionRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $updated = $this->handle($precinct, $validated);

        return response()->json($updated);
    }

    /**
     * Validation rules for updating any subset of fields.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'watchers_count'             => ['sometimes', 'nullable', 'integer', 'min:0'],
            'precincts_count'            => ['sometimes', 'nullable', 'integer', 'min:0'],
            'registered_voters_count'    => ['sometimes', 'nullable', 'integer', 'min:0'],
            'actual_voters_count'        => ['sometimes', 'nullable', 'integer', 'min:0'],
            'ballots_in_box_count'       => ['sometimes', 'nullable', 'integer', 'min:0'],
            'unused_ballots_count'       => ['sometimes', 'nullable', 'integer', 'min:0'],
            'spoiled_ballots_count'      => ['sometimes', 'nullable', 'integer', 'min:0'],
            'void_ballots_count'         => ['sometimes', 'nullable', 'integer', 'min:0'],
        ];
    }
}
