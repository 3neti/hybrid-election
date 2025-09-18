<?php

namespace TruthElection\Services;

use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class ConfigFileReader
{
    protected string $defaultElectionPath;
    protected string $defaultPrecinctPath;

    public function __construct(
        string $electionPath = null,
        string $precinctPath = null,
    ) {
        $this->defaultElectionPath = $electionPath ?? base_path('config/election.json');
        $this->defaultPrecinctPath = $precinctPath ?? base_path('config/precinct.yaml');
    }

    public function read(): array
    {
        $errors = [];

        if (! File::exists($this->defaultElectionPath)) {
            $errors[] = "❌ Election file not found at path: {$this->defaultElectionPath}";
        }

        if (! File::exists($this->defaultPrecinctPath)) {
            $errors[] = "❌ Precinct file not found at path: {$this->defaultPrecinctPath}";
        }

        if ($errors) {
            throw new \RuntimeException(implode(PHP_EOL, $errors));
        }

        $election = json_decode(File::get($this->defaultElectionPath), true);
        $precinct = Yaml::parse(File::get($this->defaultPrecinctPath));

        return [
            'election' => $election,
            'precinct' => $precinct,
            'paths' => [
                'election' => $this->defaultElectionPath,
                'precinct' => $this->defaultPrecinctPath,
            ],
            'precinct_code' => $precinct['code'] ?? null,
        ];
    }
}
