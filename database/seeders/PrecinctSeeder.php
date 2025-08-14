<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Precinct;
use App\Enums\ElectoralInspectorRole;

class PrecinctSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Build the inspectors payload (plain arrays so it works in prod w/o factories)
        $inspectors = [
            [
                'id'   => (string) Str::uuid(),
                'name' => 'Juan dela Cruz',
                'role' => ElectoralInspectorRole::CHAIRPERSON, // stored as enum value
            ],
            [
                'id'   => (string) Str::uuid(),
                'name' => 'Maria Santos',
                'role' => ElectoralInspectorRole::MEMBER,
            ],
            [
                'id'   => (string) Str::uuid(),
                'name' => 'Pedro Reyes',
                'role' => ElectoralInspectorRole::MEMBER,
            ],
        ];

        // Create or update the known precinct by unique code
        Precinct::updateOrCreate(
            ['code' => 'CURRIMAO-001'],
            [
                // keep a stable UUID if a row already exists; otherwise set a new one
                'id'             => (string) Str::uuid(),
                'location_name'  => 'Currimao Central School',
                'latitude'       => 17.993217,
                'longitude'      => 120.488902,
                'electoral_inspectors' => $inspectors, // assumes JSON/array cast on the model
            ]
        );
    }
}
