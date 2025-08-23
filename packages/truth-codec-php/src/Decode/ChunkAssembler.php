<?php

namespace TruthCodec\Decode;

use TruthCodec\Contracts\PayloadSerializer;

class ChunkAssembler
{
    private string $code = '';
    private int $total = 0;
    /** @var array<int,string> 1-based parts */
    private array $parts = [];

    public function __construct(
        private readonly PayloadSerializer $serializer
    ) {}

    /** @return array{code:string,total:int,received:int,missing:int[]} */
    public function add(ChunkHeader $h): array
    {
        if ($this->total === 0) {
            $this->total = $h->total;
            $this->code = $h->code;
        }

        if ($h->code !== $this->code) {
            throw new \InvalidArgumentException('Chunks disagree on code');
        }
        if ($h->total !== $this->total) {
            throw new \InvalidArgumentException('Chunks disagree on total');
        }

        // ✅ New check: index must be between 1 and total
        if ($h->index < 1 || $h->index > $this->total) {
            throw new \InvalidArgumentException("Chunk index {$h->index} out of range for total {$this->total}");
        }

        $this->parts[$h->index] = $h->payloadPart;

        $missing = [];
        for ($i = 1; $i <= $this->total; $i++) {
            if (!array_key_exists($i, $this->parts)) $missing[] = $i;
        }

        return [
            'code' => $this->code,
            'total' => $this->total,
            'received' => count($this->parts),
            'missing' => $missing,
        ];
    }

//    public function isComplete(): bool
//    {
//        if ($this->total <= 0) return false;
//        for ($i = 1; $i <= $this->total; $i++) {
//            if (!array_key_exists($i, $this->parts)) {
//                return false;
//            }
//        }
//        return true;
//    }

    public function isComplete(): bool
    {
        return $this->total > 0 && count($this->parts) === $this->total;
    }

    /** @return array<string,mixed> */
    public function assemble(): array
    {
        if (!$this->isComplete()) {
            throw new \RuntimeException('Cannot assemble: missing parts');
        }
        ksort($this->parts);
        $blob = implode('', $this->parts);
        return $this->serializer->decode($blob);
    }

    public function reset(): void
    {
        $this->code = '';
        $this->total = 0;
        $this->parts = [];
    }
}
