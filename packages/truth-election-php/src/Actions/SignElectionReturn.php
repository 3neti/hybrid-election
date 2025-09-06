<?php

namespace TruthElection\Actions;

use TruthElection\Policies\Signatures\SignaturePolicy;
use TruthElection\Support\InMemoryElectionStore;
use Lorisleiva\Actions\Concerns\AsAction;
use TruthElection\Data\SignPayloadData;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Http\JsonResponse;

class SignElectionReturn
{
    use AsAction;

    public function __construct(
        protected SignaturePolicy $policy,
    ) {}

    /**
     * Handles the signing of an election return by a specific inspector.
     *
     * @param  SignPayloadData  $payload
     * @param  string  $electionReturnCode
     * @return array{
     *     message: string,
     *     id: string,
     *     name: string,
     *     role: string,
     *     signed_at: string
     * }
     */
    public function handle(SignPayloadData $payload, string $electionReturnCode): array
    {
        $store = InMemoryElectionStore::instance();

        $original = $store->getElectionReturn($electionReturnCode)
            ?? abort(404, "Election return [$electionReturnCode] not found.");

        $inspector = $store->findInspector($original, $payload->id)
            ?? abort(404, "Inspector with ID [{$payload->id}] not found.");

        $updated = $original->withInspectorSignature($payload, $inspector);

        $store->replaceElectionReturn($updated);

        return [
            'message'   => 'Signature saved successfully.',
            'id'        => $payload->id,
            'name'      => $inspector->name,
            'role'      => $inspector->role->value,
            'signed_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Validation rules for the controller.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return ['payload' => ['required', 'string']];
    }

    /**
     * Controller entrypoint for signing the election return.
     *
     * @param  ActionRequest  $request
     * @param  string  $electionReturnCode
     * @return JsonResponse
     */
    public function asController(ActionRequest $request, string $electionReturnCode): JsonResponse
    {
        $data = SignPayloadData::fromQrString($request->input('payload'));

        $result = $this->handle($data, $electionReturnCode);

        return response()->json($result);
    }
}
