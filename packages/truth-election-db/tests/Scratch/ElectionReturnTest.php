<?php

use TruthElectionDb\Models\{ElectionReturn, Precinct};
use Illuminate\Foundation\Testing\RefreshDatabase;
use TruthElection\Enums\ElectoralInspectorRole;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

it('can persist and retrieve an election return with precinct and inspector signatures', function () {
    $precinct = Precinct::factory()->create();

    $signatures = [
        [
            'id' => 'uuid-1',
            'name' => 'Juan Dela Cruz',
            'role' => ElectoralInspectorRole::CHAIRPERSON->value,
            'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA...',
            'signed_at' => '2025-08-06T10:00:00+08:00',
        ],
        [
            'id' => 'uuid-2',
            'name' => 'Maria Santos',
            'role' => ElectoralInspectorRole::MEMBER->value,
            'signature' => 'data:image/png;base64,aGVsbG8gd29ybGQ=',
            'signed_at' => '2025-08-06T10:05:00+08:00',
        ],
    ];

    $return = ElectionReturn::create([
        'code' => 'ER-CURRIMAO-001',
        'signatures' => $signatures,
        'precinct_id' => $precinct->id,
    ]);

    $return->refresh();

    expect($return)->toBeInstanceOf(ElectionReturn::class)
        ->and($return->code)->toBe('ER-CURRIMAO-001')
        ->and($return->signatures)->toBeArray()
        ->and($return->signatures)->toHaveCount(2);

    $first = $return->signatures[0];
    $second = $return->signatures[1];

    expect($first)->toMatchArray([
        'id' => 'uuid-1',
        'name' => 'Juan Dela Cruz',
        'role' => ElectoralInspectorRole::CHAIRPERSON->value,
    ])
        ->and($first['signature'])->toStartWith('data:image/png;base64,')
        ->and(Carbon::parse($first['signed_at'])->format('Y-m-d H:i'))->toBe('2025-08-06 10:00');

    expect($second)->toMatchArray([
        'id' => 'uuid-2',
        'name' => 'Maria Santos',
        'role' => ElectoralInspectorRole::MEMBER->value,
    ])
        ->and($second['signature'])->toStartWith('data:image/png;base64,')
        ->and(Carbon::parse($second['signed_at'])->format('Y-m-d H:i'))->toBe('2025-08-06 10:05');

    expect($return->precinct)->toBeInstanceOf(Precinct::class)
        ->and($return->precinct->id)->toBe($precinct->id);

    dd($return->getData());
});
