<?php

namespace App\Actions;

use Illuminate\Support\Carbon;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Data\SignPayloadData;
use App\Models\ElectionReturn;

class SignElectionReturn
{
    use AsAction;

    public function handle(SignPayloadData $data, string $electionReturnCode): array
    {
        /** @var ElectionReturn $er */
        $er = ElectionReturn::with('precinct')->where('code', $electionReturnCode)->firstOrFail();

        // 1) Find the person in the precinct’s roster (source of truth)
        $roster = collect($er->precinct->electoral_inspectors ?? []);
        $person = $roster->firstWhere('id', $data->id);
        if (! $person) {
            abort(404, "Inspector with ID '{$data->id}' not found in precinct roster.");
        }

        // Normalize role whether it’s enum or string
        $role = is_object($person['role'] ?? null) && property_exists($person['role'], 'value')
            ? $person['role']->value
            : ($person['role'] ?? null);

        // 2) Build updated signatures array (plain PHP arrays only)
        $nowIso = Carbon::now()->toIso8601String();
        $existing = collect($er->signatures ?? [])
            ->values()
            ->map(function ($s) {
                // Allow object/array inputs; coerce to plain array
                return is_array($s) ? $s : (is_object($s) ? (array) $s : []);
            });

        // Remove any previous entry for this id, then push updated
        $updated = $existing
            ->reject(fn (array $s) => ($s['id'] ?? null) === $data->id)
            ->push([
                'id'        => $data->id,
                'name'      => $person['name'] ?? null,
                'role'      => $role,
                'signature' => $data->signature,   // keep as provided (e.g., base64)
                'signed_at' => $nowIso,            // store ISO8601 for transport friendliness
            ])
            ->values()
            ->all();

        // 3) Persist as JSON array (ensure in model: protected $casts = ['signatures' => 'array'];)
        $er->signatures = $updated;
        $er->save();

        return [
            'message'   => 'Signature saved successfully.',
            'id'        => $data->id,
            'name'      => $person['name'] ?? null,
            'role'      => $role,
            'signed_at' => $nowIso,
        ];
    }

    public function rules(): array
    {
        return ['payload' => ['required', 'string']];
    }

    public function asController(ActionRequest $request, string $electionReturnCode)
    {
        $data = SignPayloadData::fromQrString($request->input('payload'));
        $result = $this->handle($data, $electionReturnCode);
        return response()->json($result);
    }
}
