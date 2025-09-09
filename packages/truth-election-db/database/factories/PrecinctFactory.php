<?php

namespace TruthElectionDb\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TruthElection\Enums\ElectoralInspectorRole;
use TruthElection\Data\ElectoralInspectorData;
use TruthElectionDb\Models\Precinct;
use Illuminate\Support\Str;

class PrecinctFactory extends Factory
{
    protected $model = Precinct::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
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

    /**
     * Seed additional meta counters (aligns with HasAdditionalPrecinctAttributes)
     * You can override any default by passing an array.
     *
     * Example:
     * Precinct::factory()->withPrecinctMeta(['watchers_count' => 3])->create();
     */
    public function withPrecinctMeta(array $overrides = []): static
    {
        $defaults = [
            'watchers_count'            => 2,
            'precincts_count'           => 10,
            'registered_voters_count'   => 250,
            'actual_voters_count'       => 200,
            'ballots_in_box_count'      => 180,
            'unused_ballots_count'      => 20,
            'spoiled_ballots_count'     => 5,
            'void_ballots_count'        => 3,
        ];

        return $this->state(fn () => array_merge($defaults, $overrides));
    }
}
