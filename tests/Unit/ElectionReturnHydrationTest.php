<?php

use App\Models\ElectionReturn;
use App\Models\Precinct;
use App\Enums\ElectoralInspectorRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

it('hydrates an election return from JSON-like payload and serializes back to array', function () {
    $precinct = Precinct::factory()->create();

    $payload = [
        'code' => 'ER-CURRIMAO-002',
        'precinct_id' => $precinct->id,
        'signatures' => [
            [
                'id' => 'uuid-abc-001',
                'name' => 'Chair Inspector',
                'role' => 'chairperson',
                'signature' => 'data:image/png;base64,abc123==',
                'signed_at' => '2025-08-06T09:00:00+08:00',
            ],
            [
                'id' => 'uuid-def-002',
                'name' => 'Member Inspector',
                'role' => 'member',
                'signature' => 'data:image/png;base64,def456==',
                'signed_at' => '2025-08-06T09:05:00+08:00',
            ],
        ],
    ];

    /** @var ElectionReturn $model */
    $model = ElectionReturn::create($payload);

    expect($model->signatures)->toHaveCount(2)
        ->and($model->signatures[0]->name)->toBe('Chair Inspector')
        ->and($model->signatures[0]->role)->toBe(ElectoralInspectorRole::CHAIRPERSON)
        ->and($model->signatures[0]->signed_at)->toEqual(Carbon::parse('2025-08-06T09:00:00+08:00'));

    // Now export it to an array (simulating JSON API response)
    $array = $model->toArray();

    expect($array)->toHaveKeys(['id', 'code', 'signatures', 'precinct_id'])
        ->and($array['signatures'])->toHaveCount(2)
        ->and($array['signatures'][0]['name'])->toBe('Chair Inspector')
        ->and($array['signatures'][0]['role'])->toBe('chairperson')
        ->and($array['signatures'][0]['signed_at'])->toBe('2025-08-06T09:00:00+08:00');
});
