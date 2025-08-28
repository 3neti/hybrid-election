<?php

namespace TruthQrUi\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use TruthQrUi\Actions\DecodePayload;
use TruthQrUi\Support\CodecAliasFactory as Alias;
use TruthCodec\Contracts\Envelope;
use TruthCodec\Contracts\PayloadSerializer;
use TruthCodec\Contracts\TransportCodec;

final class DecodeController extends Controller
{
    /**
     * POST /api/decode
     *
     * Body:
     * {
     *   "lines":  ["ER|v1|...", ...]     // OR
     *   "chunks": [{"text":"..."}, ...],
     *
     *   // Prefer these short aliases (friendly UI):
     *   "envelope":   "v1line" | "v1url",
     *   "transport":  "base64url" | "base64url+deflate" | "base64url+gzip",
     *   "serializer": "json" | "yaml" | "auto",
     *
     *   // Fallback: fully-qualified class names (if aliases omitted):
     *   "envelope_fqcn":   "\\TruthCodec\\Envelope\\EnvelopeV1Line" | "\\TruthCodec\\Envelope\\EnvelopeV1Url",
     *   "transport_fqcn":  "\\TruthCodec\\Transport\\Base64UrlDeflateTransport",
     *   "serializer_fqcn": "\\TruthCodec\\Serializer\\JsonSerializer"
     * }
     */
    public function __invoke(ActionRequest $request): JsonResponse
    {
        try {
            $lines = $this->extractLines($request);

            // --- Resolve collaborators (aliases win; else FQCNs; else sane defaults) ---
            /** @var PayloadSerializer $serializer */
            $serializer = $this->resolveSerializer(
                $request->input('serializer'),
                $request->input('serializer_fqcn')
            );

            /** @var TransportCodec $transport */
            $transport = $this->resolveTransport(
                $request->input('transport'),
                $request->input('transport_fqcn')
            );

            /** @var Envelope $envelope */
            $envelope = $this->resolveEnvelope(
                $request->input('envelope'),
                $request->input('envelope_fqcn')
            );

            // Delegate to the action (explicit constructor style)
            $out = app(DecodePayload::class)->handle(
                lines:      $lines,
                envelope:   $envelope,
                transport:  $transport,
                serializer: $serializer
            );

            return response()->json($out);

        } catch (\InvalidArgumentException|\JsonException $e) {
            // bad input / corrupted payload / conflicting duplicate, etc.
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['error' => 'Decode failed'], 422);
        }
    }

    // ----------------- helpers -----------------

    /** @return array<int,string> */
    private function extractLines(ActionRequest $request): array
    {
        $lines = $request->input('lines');
        if (is_array($lines) && !empty($lines)) {
            return array_map(static fn($v) => (string)$v, array_values($lines));
        }

        $chunks = $request->input('chunks');
        if (is_array($chunks) && !empty($chunks)) {
            return array_map(static fn($c) => (string)($c['text'] ?? ''), array_values($chunks));
        }

        throw new \InvalidArgumentException('Provide either "lines": string[] or "chunks": [{"text":"..."}].');
    }

    private function resolveEnvelope(?string $alias, ?string $fqcn): Envelope
    {
        if (is_string($alias) && $alias !== '') {
            return Alias::makeEnvelope($alias);
        }
        $fqcn = $fqcn ?: \TruthCodec\Envelope\EnvelopeV1Url::class; // default
        return $this->new($fqcn);
    }

    private function resolveTransport(?string $alias, ?string $fqcn): TransportCodec
    {
        if (is_string($alias) && $alias !== '') {
            return Alias::makeTransport($alias);
        }
        $fqcn = $fqcn ?: \TruthCodec\Transport\Base64UrlDeflateTransport::class; // default
        return $this->new($fqcn);
    }

    private function resolveSerializer(?string $alias, ?string $fqcn): PayloadSerializer
    {
        if (is_string($alias) && $alias !== '') {
            return Alias::makeSerializer($alias);
        }
        $fqcn = $fqcn ?: \TruthCodec\Serializer\JsonSerializer::class; // default
        return $this->new($fqcn);
    }

    /**
     * @template T
     * @param class-string<T> $fqcn
     * @return T
     */
    private function new(string $fqcn)
    {
        if (!class_exists($fqcn)) {
            throw new \InvalidArgumentException("Class not found: {$fqcn}");
        }
        return new $fqcn();
    }
}
