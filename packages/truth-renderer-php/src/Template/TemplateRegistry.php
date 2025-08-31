<?php

namespace TruthRenderer\Template;

use TruthRenderer\Contracts\TemplateRegistryInterface;

class TemplateRegistry implements TemplateRegistryInterface
{
    /**
     * @var array<string, string> in-memory templates
     */
    private array $memory = [];

    /**
     * @var array<string, string> namespace => directory (e.g. ['core' => '/.../templates'])
     * Files expected to end with ".hbs" (Handlebars) or ".html"
     */
    private array $paths;

    /**
     * @param array<string, string> $paths optional namespace=>directory map
     */
    public function __construct(array $paths = [])
    {
        $this->paths = $paths;
    }

    public function set(string $name, string $source): void
    {
        // normalize name to "ns:name" or just "name"
        $this->memory[$name] = $source;
    }

    public function get(string $name): string
    {
        // 1) check memory
        if (isset($this->memory[$name])) {
            return $this->memory[$name];
        }

        // 2) check filesystem paths (support "ns:name" or default namespace)
        [$ns, $basename] = $this->splitName($name);

        if (str_contains($basename, '..') || strpbrk($basename, "/\\") !== false) {
            throw new \RuntimeException("Illegal template name: {$name}");
        }

        $dirs = $this->candidateDirs($ns);
        foreach ($dirs as $dir) {
            foreach (['.hbs', '.html'] as $ext) {
                $file = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $basename . $ext;
                if (is_file($file)) {
                    $src = file_get_contents($file);
                    if ($src === false) {
                        throw new \RuntimeException("Failed to read template file: {$file}");
                    }
                    return $src;
                }
            }
        }

        throw new \RuntimeException("Template not found: {$name}");
    }

    public function list(): array
    {
        $names = array_keys($this->memory);

        foreach ($this->paths as $ns => $dir) {
            if (!is_dir($dir)) {
                continue;
            }
            $dh = opendir($dir);
            if (!$dh) {
                continue;
            }
            while (($f = readdir($dh)) !== false) {
                if ($f === '.' || $f === '..') continue;
                if (preg_match('/^(?<name>.+)\.(hbs|html)$/i', $f, $m)) {
                    $names[] = $ns ? ($ns . ':' . $m['name']) : $m['name'];
                }
            }
            closedir($dh);
        }

        // unique + natural sort
        $names = array_values(array_unique($names));
        natcasesort($names);
        return array_values($names);
    }

    /**
     * @return array{string|null,string} [namespace|null, basename]
     */
    private function splitName(string $name): array
    {
        if (str_contains($name, ':')) {
            [$ns, $base] = explode(':', $name, 2);
            return [trim($ns) ?: null, trim($base)];
        }
        return [null, $name];
    }

    /**
     * @param string|null $ns
     * @return string[] directories to search, in priority order
     */
    private function candidateDirs(?string $ns): array
    {
        // specific namespace
        if ($ns !== null) {
            return isset($this->paths[$ns]) ? [$this->paths[$ns]] : [];
        }
        // all namespaces (for un-namespaced names)
        return array_values($this->paths);
    }
}
