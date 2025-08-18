<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;
use Database\Seeders\PositionSeeder;
use Database\Seeders\CandidateSeeder;
use Database\Seeders\PrecinctSeeder;
use Illuminate\Support\Facades\Storage;

class ExportSeedYaml extends Command
{
    protected $signature = 'seed:export-yaml {--path=bootstrap-seed}';
    protected $description = 'Export seeder source data (constants) into YAML files';

    public function handle(): int
    {
        $dir = $this->option('path') ?: 'bootstrap-seed';

        // ensure dir exists (in storage/app by default)
        Storage::disk('local')->makeDirectory($dir);

        $files = [
            'positions.yaml'  => PositionSeeder::POSITIONS,
            'candidates.yaml' => CandidateSeeder::CANDIDATES,
            'precincts.yaml'  => PrecinctSeeder::PRECINCT,
        ];

        foreach ($files as $name => $data) {
            $yaml = Yaml::dump($data, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
            Storage::disk('local')->put("$dir/$name", $yaml);
            $this->info("Wrote storage/app/$dir/$name");
        }

        $this->info('Done.');
        return self::SUCCESS;
    }
}
