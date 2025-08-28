<?php

namespace TruthQrUi\Actions;

use TruthCodec\Contracts\{Envelope, PayloadSerializer, TransportCodec};
use Lorisleiva\Actions\Concerns\AsAction;
use TruthQrUi\Support\CodecAliasFactory;
use Lorisleiva\Actions\ActionRequest;
use TruthQr\Contracts\TruthQrWriter;
use TruthQr\TruthQrPublisher;

/**
 * EncodePayload
 *
 * Workhorse action that turns an arbitrary payload (array|string) into
 * TRUTH envelope lines (and optional QR images), using **explicitly
 * provided** collaborators instead of container bindings.
 *
 * Usage (programmatic):
 *   $res = app(EncodePayload::class)->handle(
 *       payload:   $arrayOrJson,
 *       code:      'ER-001',
 *       serializer: new JsonSerializer(),
 *       transport:  new Base64UrlDeflateTransport(),
 *       envelope:   new EnvelopeV1Url(),   // or EnvelopeV1Line
 *       writer:     new BaconQrWriter('svg', 512, 16), // optional
 *       opts:       ['by' => 'size', 'size' => 800]    // or ['by' => 'count', 'count' => 4]
 *   );
 *
 * Returns:
 *   [
 *     'code'  => string,
 *     'by'    => 'size'|'count',
 *     'lines' => string[],           // ER|v1|... or truth://... lines (1..N)
 *     'qr'    => array<int,string>,  // optional QR binaries (keys 1..N), writer-dependent
 *   ]
 */
final class EncodePayload
{
    use AsAction;

    /**
     * Core encoder: **does not** rely on IoC bindings.
     *
     * @param array<string,mixed>|string $payload
     * @param string                     $code
     * @param PayloadSerializer          $serializer
     * @param TransportCodec             $transport
     * @param Envelope                   $envelope
     * @param TruthQrWriter|null         $writer     Optional QR renderer (to also return images)
     * @param array{by?:'size'|'count',size?:int,count?:int} $opts
     *        - by:    'size' (default) or 'count'
     *        - size:  target bytes/chars per encoded fragment (when by='size')
     *        - count: number of parts (when by='count')
     *
     * @return array{code:string,by:string,lines:array<int,string>,qr?:array<int,string>}
     */
    public function handle(
        array|string   $payload,
        string         $code,
        PayloadSerializer $serializer,
        TransportCodec $transport,
        Envelope       $envelope,
        ?TruthQrWriter $writer = null,
        array          $opts = []
    ): array {
        // Normalize options
        $by    = ($opts['by'] ?? 'size') === 'count' ? 'count' : 'size';
        $size  = max(1, (int)($opts['size']  ?? 1200));
        $count = max(1, (int)($opts['count'] ?? 3));

        // Construct a publisher using the *explicit* collaborators
        $publisher = new TruthQrPublisher(
            serializer: $serializer,
            transport:  $transport,
            envelope:   $envelope,
        );

        // Publish envelope lines according to strategy
        $lines = $publisher->publish(
            payload: is_array($payload) ? $payload : $this->jsonToArray($payload),
            code:    $code,
            options: $by === 'size' ? ['by' => 'size', 'size' => $size]
                : ['by' => 'count','count' => $count]
        );

        // Optionally render QR images
        $qr = null;
        if ($writer) {
            $qr = $publisher->publishQrImages(
                payload: is_array($payload) ? $payload : $this->jsonToArray($payload),
                code:    $code,
                writer:  $writer,
                options: $by === 'size' ? ['by' => 'size', 'size' => $size]
                    : ['by' => 'count','count' => $count]
            );
        }

        $out = [
            'code'  => $code,
            'by'    => $by,
            'lines' => array_values($lines), // 1..N lines; keep tests simple
        ];
        if ($qr !== null) {
            // keep 1-based keys if your writer/publisher returns them; tests usually just ignore 'qr'
            $out['qr'] = $qr;
        }
        return $out;
    }

    /**
     * Optional HTTP entrypoint that **still constructs** collaborators explicitly
     * from FQCN query/body params (no bindings). You can adapt routes to call this.
     *
     * Query/body params (all optional; sensible defaults below):
     *   - serializer_fqcn: TruthCodec\Serializer\JsonSerializer
     *   - transport_fqcn:  TruthCodec\Transport\Base64UrlDeflateTransport
     *   - envelope_fqcn:   TruthCodec\Envelope\EnvelopeV1Url (or EnvelopeV1Line)
     *   - writer_fqcn:     TruthQr\Writers\BaconQrWriter (omit to skip images)
     *   - by: 'size'|'count' (default 'size')
     *   - size: int         (default 1200)
     *   - count: int        (default 3)
     *   - code: string      (defaults to payload['code'] or generated)
     *   - payload: array|string (JSON)
     */
    public function asController(ActionRequest $request)
    {
        // Accept either aliases (preferred) or FQCNs (fallback)
        $envAlias = $request->input('envelope');   // e.g., 'v1line' | 'v1url'
        $txAlias  = $request->input('transport');  // e.g., 'base64url+deflate'
        $serAlias = $request->input('serializer'); // e.g., 'json' | 'yaml' | 'auto'

        $serFqcn = (string) $request->input('serializer_fqcn', \TruthCodec\Serializer\JsonSerializer::class);
        $txFqcn  = (string) $request->input('transport_fqcn',  \TruthCodec\Transport\Base64UrlDeflateTransport::class);
        $envFqcn = (string) $request->input('envelope_fqcn',   \TruthCodec\Envelope\EnvelopeV1Url::class);
        $wrFqcn  = $request->input('writer_fqcn'); // null → no images

        // Build collaborators: alias takes precedence if provided
        /** @var PayloadSerializer $serializer */
        $serializer = is_string($serAlias) && $serAlias !== ''
            ? CodecAliasFactory::makeSerializer($serAlias)
            : $this->new($serFqcn);

        /** @var TransportCodec $transport */
        $transport = is_string($txAlias) && $txAlias !== ''
            ? CodecAliasFactory::makeTransport($txAlias)
            : $this->new($txFqcn);

        /** @var Envelope $envelope */
        $envelope = is_string($envAlias) && $envAlias !== ''
            ? CodecAliasFactory::makeEnvelope($envAlias)
            : $this->new($envFqcn);

        /** @var \TruthQr\Contracts\TruthQrWriter|null $writer */
        $writer = $wrFqcn ? $this->new((string) $wrFqcn) : null;

        // Options
        $by    = $request->input('by', 'size');
        $size  = (int) $request->input('size', 1200);
        $count = (int) $request->input('count', 3);

        // Payload + code
//        $payload = $request->input('payload', []);
//        if (is_string($payload)) {
//            $payload = $this->jsonToArray($payload);
//        }
//        if (!is_array($payload)) {
//            return response()->json(['error' => 'payload must be array or JSON string'], 422);
//        }
//        $code = (string) ($request->input('code') ?? ($payload['code'] ?? 'ER-'.bin2hex(random_bytes(3))));

        // Payload + code (must be present and non-empty; JSON string must decode to object/array)
        if (!$request->has('payload')) {
            return response()->json(['error' => 'payload is required'], 422);
        }

        $payload = $request->input('payload');

        if (is_string($payload)) {
            try {
                $payload = $this->jsonToArray($payload);
            } catch (\Throwable $e) {
                return response()->json(['error' => 'payload must be a valid JSON object string or an array'], 422);
            }
        }

        if (!is_array($payload) || $payload === []) {
            return response()->json(['error' => 'payload must be a non-empty array or valid JSON object'], 422);
        }

        $code = (string) ($request->input('code') ?? ($payload['code'] ?? 'ER-'.bin2hex(random_bytes(3))));

        $res = $this->handle(
            payload:    $payload,
            code:       $code,
            serializer: $serializer,
            transport:  $transport,
            envelope:   $envelope,
            writer:     $writer,
            opts:       ['by' => $by, 'size' => $size, 'count' => $count]
        );

        // Normalize output list key to match the envelope type
        $listKey = ($envelope instanceof \TruthCodec\Envelope\EnvelopeV1Line) ? 'lines' : 'urls';

        // Re-map if needed (handle() always returns 'lines')
        $out = [
            'code'    => $res['code'] ?? $code,
            'by'      => $res['by']   ?? $by,
            $listKey  => $res['lines'] ?? [],
        ];

        if (isset($res['qr'])) {
            $out['qr'] = $res['qr'];
        }

        return response()->json($out);
    }

    // ----------------- helpers -----------------

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

    /**
     * @param string $json
     * @return array<string,mixed>
     */
    private function jsonToArray(string $json): array
    {
        $data = json_decode($json, true);
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Invalid JSON payload');
        }
        return $data;
    }
}
