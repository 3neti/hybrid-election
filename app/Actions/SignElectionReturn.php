<?php

namespace App\Actions;

use Illuminate\Support\{Carbon, Collection, Str};
use Lorisleiva\Actions\Concerns\AsAction;
use App\Data\ElectoralInspectorData;
use App\Models\ElectionReturn;

class SignElectionReturn
{
    use AsAction;

    /**
     * Handles appending or updating a BEI signature in the election return.
     *
     * @param string $payload Format: "BEI:<id>:<signature>"
     * @param string $electionReturnCode Code of the ElectionReturn model
     * @return array
     */
    public function handle(string $payload, string $electionReturnCode): array
    {
        // 🛑 Validate prefix
        if (!Str::startsWith($payload, 'BEI:')) {
            abort(400, 'Invalid QR code prefix.');
        }

        // 🧾 Extract parts
        [$prefix, $id, $signature] = explode(':', $payload, 3);

        // 🔍 Fetch election return
        $electionReturn = ElectionReturn::where('code', $electionReturnCode)->firstOrFail();

        // 🧱 Convert raw array to Data objects
        $inspectors = collect($electionReturn->signatures ?? [])
            ->map(fn (array $i) => ElectoralInspectorData::from($i));

        // 🕵️ Find inspector by ID
        $inspector = $inspectors->firstWhere('id', $id);

        if (! $inspector) {
            abort(404, "Inspector with ID '{$id}' not found.");
        }

        // ✍️ Replace or update the inspector
        $updatedInspectors = $inspectors
            ->reject(fn (ElectoralInspectorData $i) => $i->id === $id)
            ->push(new ElectoralInspectorData(
                id: $id,
                name: $inspector->name,
                role: $inspector->role,
                signature: $signature,
                signed_at: Carbon::now(),
            ));

        // 💾 Persist
        $electionReturn->signatures = $updatedInspectors;
        $electionReturn->save();

        return [
            'message' => 'Signature saved successfully.',
            'id' => $id,
            'name' => $inspector->name,
            'role' => $inspector->role->value,
            'signed_at' => now()->toIso8601String(),
        ];
    }
}
