<?php

namespace TruthRenderer\Contracts;

interface TemplateRegistryInterface
{
    /**
     * Resolve a template by name and return its raw source (Handlebars/HTML).
     *
     * @throws \RuntimeException when not found
     */
    public function get(string $name): string;

    /**
     * Register a template source under a name (runtime/in-memory).
     */
    public function set(string $name, string $source): void;

    /**
     * List all available template names (from memory + configured paths).
     *
     * @return string[]
     */
    public function list(): array;
}
