<?php

namespace TruthCodec\Contracts;

/**
 * Envelope abstraction for chunk headers/lines.
 *
 * Implementations (e.g., V1 line, V1 URL) must:
 *  - create a transport line from code/index/total/payload
 *  - parse a transport line back to [code, index, total, payload]
 */
/** @deprecated  */
interface Envelope
{
    /**
     * Build a complete transport line for one chunk.
     *
     * @param string $code    caller-supplied group code
     * @param int    $index   1-based index of this chunk
     * @param int    $total   total expected chunks
     * @param string $payload payload fragment (already serialized+transported)
     */
    public function makeLine(string $code, int $index, int $total, string $payload): string;

    /**
     * Parse a transport line into metadata + payload fragment.
     *
     * @return array{0:string,1:int,2:int,3:string} [code, index, total, payloadFragment]
     * @throws \InvalidArgumentException on malformed/unsupported input
     */
    public function parseLine(string $line): array;
}
