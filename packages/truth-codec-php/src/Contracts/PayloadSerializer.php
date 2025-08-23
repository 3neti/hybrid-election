<?php

namespace TruthCodec\Contracts;

interface PayloadSerializer
{
    /** @return string serialized payload */
    public function encode(array $payload): string;

    /**
     * @throws \InvalidArgumentException on invalid input
     * @return array<string,mixed>
     */
    public function decode(string $text): array;

    /** "json" | "yaml" */
    public function format(): string;
}
