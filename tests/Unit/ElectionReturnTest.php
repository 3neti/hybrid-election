<?php

use App\Models\{ElectionReturn, Precinct};
use App\Data\ElectoralInspectorData;
use App\Enums\ElectoralInspectorRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

it('can persist and retrieve an election return with precinct and inspector signatures', function () {
    $precinct = Precinct::factory()->create();

    $signatures = new DataCollection(
        ElectoralInspectorData::class,
        [
            new ElectoralInspectorData(
                id: 'uuid-1',
                name: 'Juan Dela Cruz',
                role: ElectoralInspectorRole::CHAIRPERSON,
                signature: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA...',
                signed_at: Carbon::parse('2025-08-06T10:00:00+08:00'),
            ),
            new ElectoralInspectorData(
                id: 'uuid-2',
                name: 'Maria Santos',
                role: ElectoralInspectorRole::MEMBER,
                signature: 'data:image/png;base64,aGVsbG8gd29ybGQ=',
                signed_at: Carbon::parse('2025-08-06T10:05:00+08:00'),
            ),
        ]
    );

    $return = ElectionReturn::create([
        'code' => 'ER-CURRIMAO-001',
        'signatures' => $signatures,
        'precinct' => $precinct,
    ]);

    $return->refresh();

    expect($return)->toBeInstanceOf(ElectionReturn::class)
        ->and($return->code)->toBe('ER-CURRIMAO-001')
        ->and($return->signatures)->toBeInstanceOf(DataCollection::class)
        ->and($return->signatures)->toHaveCount(2);

    $first = $return->signatures->first();

    expect($first)->toBeInstanceOf(ElectoralInspectorData::class)
        ->and($first->name)->toBe('Juan Dela Cruz')
        ->and($first->role)->toBe(ElectoralInspectorRole::CHAIRPERSON)
        ->and($first->signature)->toStartWith('data:image/png;base64,')
        ->and($first->signed_at->format('Y-m-d H:i'))->toBe('2025-08-06 10:00');

    expect($return->precinct)->toBeInstanceOf(Precinct::class)
        ->and($return->precinct->id)->toBe($precinct->id);
});
