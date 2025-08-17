<?php

namespace App\Actions;

use App\Data\PrecinctData;
use App\Enums\ElectoralInspectorRole;
use App\Models\Precinct;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class InputPrecinctDetails
{
    use AsAction;

    /**
     * Optional: lock this down with a policy if you have one.
     */
    public function authorize(ActionRequest $request): bool
    {
        /** @var \App\Models\Precinct|null $precinct */
        $precinct = $request->route('precinct');

        // If you have a PrecinctPolicy@update, prefer this:
        // return $request->user()?->can('update', $precinct) ?? false;

        // Otherwise, keep it open for now:
        return true;
    }


    public function rules(): array
    {
        $roles = array_map(fn ($c) => $c->value, ElectoralInspectorRole::cases());

        return [
            'code'                   => ['sometimes', 'string', 'max:255'],
            'location_name'          => ['sometimes', 'nullable', 'string', 'max:255'],
            'latitude'               => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
            'longitude'              => ['sometimes', 'nullable', 'numeric', 'between:-180,180'],

            'electoral_inspectors'          => ['sometimes', 'nullable', 'array'],
            'electoral_inspectors.*'        => ['required', 'array'], // ✅ ensure each item is validated
            'electoral_inspectors.*.id'     => ['sometimes', 'nullable', 'uuid'],
            'electoral_inspectors.*.name'   => ['required', 'string', 'max:255'],
            'electoral_inspectors.*.role'   => ['required', Rule::in($roles)],
        ];
    }

//    public function rules(): array
//    {
//        $roles = array_map(fn ($c) => $c->value, ElectoralInspectorRole::cases());
//
//        return [
//            'code'              => ['sometimes', 'string', 'max:100'],
//            'location_name'     => ['sometimes', 'nullable', 'string', 'max:255'],
//            'latitude'          => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
//            'longitude'         => ['sometimes', 'nullable', 'numeric', 'between:-180,180'],
//
//            'electoral_inspectors'            => ['sometimes', 'array'],
//            'electoral_inspectors.*.id'       => ['sometimes', 'nullable', 'uuid'],
//            // Require these for every row when the array is provided:
//
//            'electoral_inspectors.*.name' => ['required', 'string', 'max:255'],
//            'electoral_inspectors.*.role' => ['required', 'string', Rule::in($roles)],
//        ];
//    }

//    public function rules(): array
//    {
//        return [
//            // All fields are optional; only provided keys will update.
//            'code'           => ['sometimes', 'string', 'max:100'],
//            'location_name'  => ['sometimes', 'nullable', 'string', 'max:255'],
//            'latitude'       => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
//            'longitude'      => ['sometimes', 'nullable', 'numeric', 'between:-180,180'],
//
//            'electoral_inspectors'                => ['sometimes', 'array'],
//            'electoral_inspectors.*.id'           => ['sometimes', 'uuid'],
//            'electoral_inspectors.*.name'         => ['required_with:electoral_inspectors.*', 'string', 'max:255'],
//            'electoral_inspectors.*.role'         => ['nullable', Rule::enum(ElectoralInspectorRole::class)],
//        ];
//    }

    /**
     * Core updater. Only whitelisted keys are applied.
     */
    public function handle(Precinct $precinct, array $input): Precinct
    {
        $data = Arr::only($input, [
            'code',
            'location_name',
            'latitude',
            'longitude',
            'electoral_inspectors',
        ]);

        // Normalize electoral_inspectors into your model’s expected shape.
        // If your model already casts to a DataCollection of ElectoralInspectorData,
        // assigning an array of associative arrays is fine; keep it simple.
        if (array_key_exists('electoral_inspectors', $data)) {
            $data['electoral_inspectors'] = collect($data['electoral_inspectors'] ?? [])
                ->filter(fn ($i) => is_array($i))
                ->map(function (array $i) {
                    return [
                        'id'   => $i['id']   ?? (string) Str::uuid(),
                        'name' => $i['name'] ?? '',
                        'role' => isset($i['role']) ? (string) $i['role'] : null, // enum value as string
                    ];
                })
                ->values()
                ->all();
        }

        $precinct->fill($data)->save();

        return $precinct->refresh();
    }

    /**
     * Controller entry: route-model binding injects the target Precinct.
     */
    public function asController(ActionRequest $request, Precinct $precinct): PrecinctData
    {
        $updated = $this->handle($precinct, $request->validated());

        // Return DTO for consistency with the rest of your API
        return PrecinctData::from($updated);
    }
}
