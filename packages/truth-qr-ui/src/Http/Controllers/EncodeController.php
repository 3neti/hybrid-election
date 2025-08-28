<?php

namespace TruthQrUi\Http\Controllers;

use Lorisleiva\Actions\ActionRequest;
use Illuminate\Http\JsonResponse;
use TruthQrUi\Actions\EncodePayload;

/**
 * # EncodeController
 *
 * Thin HTTP adapter around {@see EncodePayload}. This controller **does not**
 * implement its own encoding logic; instead, it forwards the incoming request
 * to the Action’s `asController` method, keeping the source of truth in one place
 * and preventing drift between “action code” and “controller code”.
 *
 * ## What does it do?
 * - Accepts a payload (array or JSON string) and codec options via HTTP (POST).
 * - Delegates to {@see EncodePayload::asController()} which:
 *   - Instantiates the requested Envelope/Transport/Serializer (explicit constructors).
 *   - Publishes TRUTH lines/URLs (and optionally QR images) using {@see \TruthQr\TruthQrPublisher}.
 *   - Normalizes the response shape to:
 *       - `{"code","by","lines":[...], "qr":{...}}` for line envelopes, or
 *       - `{"code","by","urls":[...],  "qr":{...}}` for URL envelopes.
 *
 * ## Why keep the controller thin?
 * - **Single responsibility**: the Action owns all encoding behavior (business logic).
 * - **Testability**: your unit/integration tests already target the Action; this controller
 *   stays a pass-through so tests don’t need to duplicate cases.
 * - **Maintainability**: any future changes to encoding/options happen once in the Action.
 *
 * ## Request (JSON body)
 * Uses the same parameters that {@see EncodePayload::asController()} supports:
 *
 * - `payload`          : array|string (JSON) — required
 * - `code`             : string — optional (defaults to payload.code or generated)
 * - Partitioning:
 *   - `by`             : "size" | "count" (default "size")
 *   - `size`           : int (default 1200) — target size per part when by="size"
 *   - `count`          : int (default 3)    — number of parts when by="count"
 * - Collaborators (FQCNs):
 *   - `serializer_fqcn`: class-string of TruthCodec\Contracts\PayloadSerializer
 *   - `transport_fqcn` : class-string of TruthCodec\Contracts\TransportCodec
 *   - `envelope_fqcn`  : class-string of TruthCodec\Contracts\Envelope
 *   - `writer_fqcn`    : class-string of TruthQr\Contracts\TruthQrWriter (optional; include to return QR binaries)
 *
 * > Tip: If your UI prefers short aliases (e.g. `"json"`, `"base64url+deflate"`, `"v1url"`),
 * > expose an endpoint that resolves aliases first and then calls this controller
 * > (or call {@see EncodePayload} directly). Keeping this controller as a forwarder
 * > ensures one code path.
 *
 * ## Responses
 * - **200 OK**: on success. Body is one of:
 *   - `{"code","by","lines":[...], "qr":{...}}` (EnvelopeV1Line)
 *   - `{"code","by","urls":[...],  "qr":{...}}` (EnvelopeV1Url)
 * - **422 Unprocessable Entity**: invalid payload or bad collaborator class names.
 *
 * ## Example route
 * ```php
 * use TruthQrUi\Http\Controllers\EncodeController;
 * Route::post('/api/encode', EncodeController::class);
 * ```
 *
 * ## Example request
 * ```json
 * {
 *   "payload": {"type":"demo","code":"ENC-001","data":{"a":1}},
 *   "code": "ENC-001",
 *   "by": "size",
 *   "size": 120,
 *   "serializer_fqcn": "TruthCodec\\Serializer\\JsonSerializer",
 *   "transport_fqcn":  "TruthCodec\\Transport\\Base64UrlDeflateTransport",
 *   "envelope_fqcn":   "TruthCodec\\Envelope\\EnvelopeV1Url"
 * }
 * ```
 */
final class EncodeController
{
    /**
     * Handle the HTTP request by forwarding to the Action’s controller method.
     *
     * @param  ActionRequest  $request
     * @return JsonResponse
     */
    public function __invoke(ActionRequest $request): JsonResponse
    {
        // Delegate to the Action’s HTTP handler to avoid duplicating logic.
        /** @var EncodePayload $action */
        $action = app(EncodePayload::class);

        // NOTE: We pass the same ActionRequest instance so validation,
        // option parsing, and response shaping remain identical.
        return $action->asController($request);
    }
}
