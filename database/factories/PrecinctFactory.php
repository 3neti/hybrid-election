<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ElectoralInspectorRole;
use App\Data\ElectoralInspectorData;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Precinct>
 */
class PrecinctFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'code' => 'CURRIMAO-001',
            'location_name' => 'Currimao Central School',
            'latitude' => 17.993217,
            'longitude' => 120.488902,
            'electoral_inspectors' => collect([
                new ElectoralInspectorData(
                    id: (string) Str::uuid(),
                    name: 'Juan dela Cruz',
                    role: ElectoralInspectorRole::CHAIRPERSON,
                ),
                new ElectoralInspectorData(
                    id: (string) Str::uuid(),
                    name: 'Maria Santos',
                    role: ElectoralInspectorRole::MEMBER,
                ),
                new ElectoralInspectorData(
                    id: (string) Str::uuid(),
                    name: 'Pedro Reyes',
                    role: ElectoralInspectorRole::MEMBER,
                ),
            ]),
        ];
    }
}
