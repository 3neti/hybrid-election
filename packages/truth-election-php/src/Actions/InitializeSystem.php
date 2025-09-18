<?php

namespace TruthElection\Actions;

use TruthElection\Data\{CandidateData, PositionData, PrecinctData};
use TruthElection\Support\ElectionStoreInterface;
use Illuminate\Support\Facades\{File, Log};
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\Yaml\Yaml;
use TruthElection\Enums\Level;
use Illuminate\Support\Arr;

class InitializeSystem
{
    use AsAction;

    public function __construct(protected ElectionStoreInterface $store){}

    public function handle(?string $electionPath = null, ?string $precinctPath = null): array
    {
        $store = $this->store;

        // 📄 Load files
        $electionPath ??= base_path('config/election.json');
        $precinctPath ??= base_path('config/precinct.yaml');

        // 🔍 Check for missing files
        $errors = [];

        if (! File::exists($electionPath)) {
            $errors[] = "❌ Election file not found at path: {$electionPath}";
        }

        if (! File::exists($precinctPath)) {
            $errors[] = "❌ Precinct file not found at path: {$precinctPath}";
        }

        if ($errors) {
            throw new \RuntimeException(implode(PHP_EOL, $errors));
        }

        $election = json_decode(File::get($electionPath), true);
        $precinct = Yaml::parse(File::get($precinctPath));

        // 🧮 Init summary counters
        $summary = [
            'positions' => ['created' => 0, 'updated' => 0],
            'candidates' => ['created' => 0, 'updated' => 0],
            'precinct' => ['created' => 0, 'updated' => 0],
        ];

        // 🧭 Load or replace Precinct
        $precinctData = PrecinctData::from($precinct);
        $existingPrecinct = $store->precincts[$precinctData->code] ?? null;

        try {
            $store->putPrecinct($precinctData);
        } catch (\Throwable $e) {
            Log::error("Failed to import precinct {$precinctData->code}: " . $e->getMessage());
            throw $e;
        }
        $existingPrecinct
            ? $summary['precinct']['updated']++
            : $summary['precinct']['created']++;

        // 🗳️ Load Positions
        $positionMap = [];
        foreach (Arr::get($election, 'positions', []) as $pos) {
            $code = $pos['code'];

            $positionData = new PositionData(
                code: $code,
                name: $pos['name'],
                level: Level::from($pos['level']),
                count: $pos['count'],
            );

            $positionMap[$code] = $positionData;
            $summary['positions']['created']++; // No update logic in memory
        }

        // 🧑‍💼 Load Candidates with Position relation
        $candidateMap = [];
        foreach (Arr::get($election, 'candidates', []) as $positionCode => $list) {
            $position = $positionMap[$positionCode] ?? null;

            if (!$position) {
                // Skip or optionally throw if position is missing
                continue;
            }

            foreach ((array) $list as $c) {
                $candidateData = new CandidateData(
                    code: $c['code'],
                    name: $c['name'],
                    alias: $c['alias'] ?? null,
                    position: $position,
                );

                $candidateMap[$c['code']] = $candidateData;
                $summary['candidates']['created']++;
            }
        }

        // 🧠 Store to memory
        $store->setPositions($positionMap);
        $store->setCandidates($candidateMap);

        return [
            'ok' => true,
            'summary' => array_merge($summary, [
                'precinct_code' => $precinctData->code,
            ]),
            'files' => [
                'election' => $electionPath,
                'precinct' => $precinctPath,
            ],
        ];
    }
}
