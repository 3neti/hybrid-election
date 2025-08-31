<?php

namespace TruthRenderer\DTO;

class RenderResult
{
    public function __construct(
        public string $format,
        public string $content
    ) {}
}
