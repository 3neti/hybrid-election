<?php

namespace TruthQrUi\Actions;

use TruthCodec\Contracts\{Envelope, PayloadSerializer, TransportCodec};
use Lorisleiva\Actions\Concerns\AsAction;
use TruthQrUi\Support\CodecAliasFactory;
use Lorisleiva\Actions\ActionRequest;
use TruthQr\Assembly\TruthAssembler;
use TruthQr\Stores\ArrayTruthStore;
use TruthQr\Classify\Classify;

/**
 * DecodePayload
 *
 * Ingest TRUTH envelope lines/URLs and (when complete) return the decoded payload
 * using **explicitly provided collaborators** (no IoC bindings inside handle()).
 *
 * Programmatic usage:
 *   $res = app(DecodePayload::class)->handle(
 *       lines:      [... "truth://v1/..." or "ER|v1|..." ...],
 *       envelope:   new EnvelopeV1Url(),           // or EnvelopeV1Line
 *       transport:  new Base64UrlDeflateTransport(),
 *       serializer: new JsonSerializer(),
 *   );
 *
 * Returns:
 *   [
 *     'code'     => string,
 *     'total'    => int,
 *     'received' => int,
 *     'missing'  => int[],
 *     'complete' => bool,
 *     'payload'  => array|null,  // present when complete
 *   ]
 */
final class DecodePayload
{
    use AsAction;

    /**
     * @param  array<int,string> $lines One line per element (ER|v1|... or truth://v1/...)
     * @return array{
     *   code:string,total:int,received:int,missing:array<int,int>,complete:bool,
     *   payload?:array<string,mixed>
     * }
     */
    public function handle(
        array $lines,
        Envelope $envelope,
        TransportCodec $transport,
        PayloadSerializer $serializer
    ): array {
        $asm = new TruthAssembler(
            store: new ArrayTruthStore(),
            envelope: $envelope,
            transport: $transport,
            serializer: $serializer
        );

        $classify = new Classify($asm);
        $sess     = $classify->newSession();
        $sess->addLines($lines);

        $st = $sess->status();        // ['code','total','received','missing'=>[]]
        $complete = $sess->isComplete();

        $out = [
            'code'     => (string)($st['code'] ?? ''),
            'total'    => (int)   ($st['total'] ?? 0),
            'received' => (int)   ($st['received'] ?? 0),
            'missing'  => array_values($st['missing'] ?? []),
            'complete' => $complete,
        ];

        if ($complete) {
            $out['payload'] = $sess->assemble();
        }

        return $out;
    }

    /**
     * HTTP controller entrypoint (explicit constructor style with friendly aliases).
     *
     * POST /api/decode
     * Body:
     * {
     *   "lines": ["truth://v1/...", ...] | "chunks": [{"text":"..."}, ...],
     *   "envelope":   "v1url"|"v1line"                      (default: v1url)
     *   "transport":  "base64url"|"base64url+deflate"|...   (default: base64url+deflate)
     *   "serializer": "json"|"yaml"|"auto"                  (default: json)
     *
     *   // or pass FQCNs instead of aliases:
     *   "envelope_fqcn":   "\\TruthCodec\\Envelope\\EnvelopeV1Url",
     *   "transport_fqcn":  "\\TruthCodec\\Transport\\Base64UrlDeflateTransport",
     *   "serializer_fqcn": "\\TruthCodec\\Serializer\\JsonSerializer"
     * }
     */
    public function asController(ActionRequest $request)
    {
        try {
            $lines = $this->extractLines($request->input('lines'), $request->input('chunks'));

            $envAlias = (string) $request->input('envelope', 'v1line');
            $txAlias  = (string) $request->input('transport', 'base64url');
            $serAlias = (string) $request->input('serializer', 'json');

            $envelope   = \TruthQrUi\Support\CodecAliasFactory::makeEnvelope($envAlias);
            $transport  = \TruthQrUi\Support\CodecAliasFactory::makeTransport($txAlias);
            $serializer = \TruthQrUi\Support\CodecAliasFactory::makeSerializer($serAlias);

            $res = $this->handle($lines, $envelope, $transport, $serializer);

            return response()->json($res);
        } catch (\InvalidArgumentException|\JsonException $e) {
            // bad input / corrupted payload / conflicting duplicate, etc.
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            // keep logs, return safe error
            report($e);
            return response()->json(['error' => 'Decode failed'], 422);
        }
    }

    // -------- helpers --------

    /**
     * Accept either:
     * - lines: ["ER|v1|...","truth://v1/..."]
     * - chunks: [{"text":"..."}, ...]
     *
     * @param  mixed $lines
     * @param  mixed $chunks
     * @return array<int,string>
     */
    private function extractLines(mixed $lines, mixed $chunks): array
    {
        if (is_array($lines) && !empty($lines)) {
            return array_map(static fn($v) => (string) $v, array_values($lines));
        }

        if (is_array($chunks) && !empty($chunks)) {
            return array_map(
                static fn($c) => (string) ($c['text'] ?? ''),
                array_values($chunks)
            );
        }

        abort(422, 'Provide either "lines": string[] or "chunks": [{"text": "..."}].');
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
