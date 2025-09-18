<?php

namespace TruthElection\Actions;

use TruthElection\Data\{CandidateData, PositionData, PrecinctData};
use TruthElection\Support\ElectionStoreInterface;
use TruthElection\Services\ConfigFileReader;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Log;
use TruthElection\Enums\Level;
use Illuminate\Support\Arr;

class InitializeSystem
{
    use AsAction;

    public function __construct(protected ElectionStoreInterface $store, protected ConfigFileReader $reader){}

    public function handle(?string $electionPath = null, ?string $precinctPath = null): array
    {
        $store = $this->store;

        // 📄 Read and parse config files
        $config = (new ConfigFileReader($electionPath, $precinctPath))->read();
        $election = $config['election'];
        $precinct = $config['precinct'];

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
                'election' => $config['paths']['election'], // $electionPath,
                'precinct' => $config['paths']['precinct'] //$precinctPath,
            ],
        ];
    }
}
