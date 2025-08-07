<?php

use App\Models\ElectionReturn;
use App\Models\Precinct;
use App\Data\SignPayloadData;
use App\Actions\SignElectionReturn;
use App\Data\ElectoralInspectorData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\LaravelData\Optional;

uses(RefreshDatabase::class);

it('signs an election return inspector using a QR code payload', function () {
    // 🧪 Step 1: Create a Precinct with known inspectors
    $precinct = Precinct::factory()->create();

    // Extract an inspector ID
    $inspector = $precinct->electoral_inspectors->first();
    $inspectorId = $inspector->id;

    // 🧪 Step 2: Create an Election Return linked to that precinct
    $electionReturn = ElectionReturn::create([
        'code' => 'ER-0001',
        'precinct_id' => $precinct->id,
        'signatures' => $precinct->electoral_inspectors->toArray(),
    ]);

    // 🧪 Step 3: Assert the signature and signed_at are initially null
    $initial = $electionReturn->signatures
        ->toCollection()
        ->firstWhere('id', $inspectorId);

    expect($initial)->toBeInstanceOf(\App\Data\ElectoralInspectorData::class)
        ->and($initial->signature)->toBeInstanceOf(Optional::class)
        ->and($initial->signed_at)->toBeInstanceOf(Optional::class)
        ;

    // 🧪 Step 4: Create a QR code string like: BEI:<id>:<base64>
    $fakeSignature = base64_encode('signed-by-chair');
    $payload = "BEI:{$inspectorId}:{$fakeSignature}";

    // 🧪 Step 5: Convert to DTO
    $dto = SignPayloadData::fromQrString($payload);

    // 🧪 Step 6: Run the action
    $response = SignElectionReturn::run($dto, $electionReturn->code);

    // ✅ Assertions on return payload
    expect($response)
        ->toHaveKey('message', 'Signature saved successfully.')
        ->and($response)->toHaveKey('id', $inspectorId)
        ->and($response)->toHaveKey('signed_at');

    // 🧪 Step 7: Refresh model and verify the inspector was updated
    $electionReturn->refresh();
    $signed = $electionReturn->signatures
        ->toCollection()
        ->firstWhere('id', $inspectorId);

    expect($signed)
        ->toBeInstanceOf(ElectoralInspectorData::class)
        ->and($signed->signature)->toBe($fakeSignature)
        ->and($signed->signed_at)->not->toBeNull();
});

it('allows multiple inspectors to sign the same election return independently', function () {
    // 🧪 Step 1: Create a precinct with multiple inspectors
    $precinct = Precinct::factory()->create();
    $inspectors = $precinct->electoral_inspectors;

    expect($inspectors)->toHaveCount(3); // Just to be safe

    // 🧪 Step 2: Create Election Return with those inspectors (unsigned)
    $electionReturn = ElectionReturn::create([
        'code' => 'ER-0002',
        'precinct_id' => $precinct->id,
        'signatures' => $inspectors->toArray(),
    ]);

    // 🧪 Step 3: Sign the first inspector
    $inspector1 = $inspectors[0];
    $signature1 = base64_encode('signed-by-1');
    $dto1 = \App\Data\SignPayloadData::fromQrString("BEI:{$inspector1->id}:{$signature1}");
    SignElectionReturn::run($dto1, $electionReturn->code);

    // 🧪 Step 4: Sign the second inspector
    $inspector2 = $inspectors[1];
    $signature2 = base64_encode('signed-by-2');
    $dto2 = \App\Data\SignPayloadData::fromQrString("BEI:{$inspector2->id}:{$signature2}");
    SignElectionReturn::run($dto2, $electionReturn->code);

    // 🧪 Step 5: Refresh model and get current signatures
    $electionReturn->refresh();
    $signatures = $electionReturn->signatures->toCollection();

    // ✅ Inspector 1 signed correctly
    $signed1 = $signatures->firstWhere('id', $inspector1->id);
    expect($signed1->signature)->toBe($signature1)
        ->and($signed1->signed_at)->not->toBeNull();

    // ✅ Inspector 2 signed correctly
    $signed2 = $signatures->firstWhere('id', $inspector2->id);
    expect($signed2->signature)->toBe($signature2)
        ->and($signed2->signed_at)->not->toBeNull();

    // ✅ Inspector 3 is still unsigned
    $inspector3 = $inspectors[2];
    $unsigned = $signatures->firstWhere('id', $inspector3->id);
    expect($unsigned->signature)->toBeInstanceOf(Optional::class)
        ->and($unsigned->signed_at)->toBeInstanceOf(Optional::class)
    ;
});
