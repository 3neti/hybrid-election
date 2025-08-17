<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use App\Data\PrecinctData;
use App\Models\Precinct;

class InputPrecinctStatistics
{
    use AsAction;

    /**
     * Update a subset of statistic fields on the given Precinct.
     *
     * @param  Precinct  $precinct
     * @param  array     $payload  Validated key=>value stats (nullable ints)
     * @return Precinct
     */
    public function handle(Precinct $precinct, array $payload): Precinct
    {
        // Only set fields that are present in payload; allow nulls (to clear)
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

        foreach ($fields as $key) {
            if (array_key_exists($key, $payload)) {
                // Trait setters handle storing into meta
                $precinct->{$key} = $payload[$key]; // may be null or int
            }
        }

        $precinct->save();

        return $precinct->refresh();
    }

    /**
     * Validation rules: all fields optional, nullable, integer >= 0
     * This lets clients PATCH any subset safely.
     */
    public function rules(): array
    {
        // using "sometimes" so omission means "leave as is"
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

    /**
     * Controller entry: PATCH /precincts/{precinct}/statistics
     * Relies on implicit route-model binding for {precinct}.
     */
    public function asController(Precinct $precinct, ActionRequest $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();

        $updated = $this->handle($precinct, $validated);

        // Return your DTO for a clean contract
        return response()->json(PrecinctData::from($updated));
    }
}
