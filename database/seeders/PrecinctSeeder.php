<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Precinct;
use App\Enums\ElectoralInspectorRole;

class PrecinctSeeder extends Seeder
{
    public const PRECINCT = [
        'code' => 'CURRIMAO-001',
        'location_name'  => 'Currimao National High School',
        'latitude'       => 17.993217,
        'longitude'      => 120.488902,
        'electoral_inspectors' => [
            [
                'id'   => 'uuid-juan',
                'name' => 'Juan dela Cruz',
                'role' => ElectoralInspectorRole::CHAIRPERSON,
            ],
            [
                'id'   => 'uuid-maria',
                'name' => 'Maria Santos',
                'role' => ElectoralInspectorRole::MEMBER,
            ],
            [
                'id'   => 'uuid-pedro',
                'name' => 'Pedro Reyes',
                'role' => ElectoralInspectorRole::MEMBER,
            ],
        ],
    ];

    public function run(): void
    {
        $precinct = PrecinctSeeder::PRECINCT;

        Precinct::updateOrCreate(
            ['code' => $precinct['code']],
            [
                // keep a stable UUID if a row already exists; otherwise set a new one
                'id'                   => (string) Str::uuid(),
                'location_name'        => $precinct['location_name'],
                'latitude'             => $precinct['latitude'],
                'longitude'            => $precinct['longitude'],
                'electoral_inspectors' => $precinct['electoral_inspectors'],
            ]
        );
    }
}
