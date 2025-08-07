<?php

namespace App\Actions;

use Illuminate\Support\{Carbon, Collection, Str};
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Data\ElectoralInspectorData;
use App\Data\SignPayloadData;
use App\Models\ElectionReturn;

class SignElectionReturn
{
    use AsAction;

    public function handle(SignPayloadData $data, string $electionReturnCode): array
    {
        $electionReturn = ElectionReturn::where('code', $electionReturnCode)->firstOrFail();
//dd($electionReturn->signatures);
        $inspectors = collect($electionReturn->signatures ?? [])
            ->map(fn (array $i) => ElectoralInspectorData::from($i));

        $inspector = $inspectors->firstWhere('id', $data->id);

        if (! $inspector) {
            abort(404, "Inspector with ID '{$data->id}' not found.");
        }

        $updatedInspectors = $inspectors
            ->reject(fn (ElectoralInspectorData $i) => $i->id === $data->id)
            ->push(new ElectoralInspectorData(
                id: $data->id,
                name: $inspector->name,
                role: $inspector->role,
                signature: $data->signature,
                signed_at: now(),
            ));

        $electionReturn->signatures = $updatedInspectors;
        $electionReturn->save();

        return [
            'message' => 'Signature saved successfully.',
            'id' => $data->id,
            'name' => $inspector->name,
            'role' => $inspector->role->value,
            'signed_at' => now()->toIso8601String(),
        ];
    }

    public function rules(): array
    {
        return [
            'payload' => ['required', 'string'],
        ];
    }

    public function asController(ActionRequest $request, string $electionReturnCode): \Illuminate\Http\JsonResponse
    {
        $data = SignPayloadData::fromQrString($request->input('payload'));
//        $electionReturnCode = $request->route('electionReturn');

        $result = $this->handle($data, $electionReturnCode);

        return response()->json($result);
    }
}
