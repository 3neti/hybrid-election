<?php

use Illuminate\Support\Facades\Storage;
use App\Support\SignatureFileStore;

it('persists chairperson and members with correct filenames and content', function () {
    Storage::fake('signatures');

    $roster = [
        ['id' => 'uuid-juan',  'name' => 'Juan dela Cruz', 'role' => 'chairperson'],
        ['id' => 'uuid-maria', 'name' => 'Maria Santos',   'role' => 'member'],
        ['id' => 'uuid-pedro', 'name' => 'Pedro Reyes',    'role' => 'member'],
    ];

    $svc = new SignatureFileStore();

    // Chairperson
    $svc->persist([
        'id'        => 'uuid-juan',
        'name'      => 'Juan dela Cruz',
        'role'      => 'chairperson',
        'signature' => 'SIG-JUAN-123',
        'signed_at' => '2025-08-19T09:30:00Z',
    ], 'ER-TEST123', $roster);

    // Member #1
    $svc->persist([
        'id'        => 'uuid-maria',
        'name'      => 'Maria Santos',
        'role'      => 'member',
        'signature' => 'SIG-MARIA-456',
        'signed_at' => '2025-08-19T09:31:00Z',
    ], 'ER-TEST123', $roster);

    // Member #2
    $svc->persist([
        'id'        => 'uuid-pedro',
        'name'      => 'Pedro Reyes',
        'role'      => 'member',
        'signature' => 'SIG-PEDRO-789',
        'signed_at' => '2025-08-19T09:32:00Z',
    ], 'ER-TEST123', $roster);

    Storage::disk('signatures')->assertExists('ER-TEST123/chairperson.sig');
    Storage::disk('signatures')->assertExists('ER-TEST123/member1.sig');
    Storage::disk('signatures')->assertExists('ER-TEST123/member2.sig');

    expect(Storage::disk('signatures')->get('ER-TEST123/chairperson.sig'))
        ->toContain('id=uuid-juan')
        ->toContain('role=chairperson')
        ->toContain('signature=SIG-JUAN-123');

    expect(Storage::disk('signatures')->get('ER-TEST123/member1.sig'))
        ->toContain('id=uuid-maria')
        ->toContain('role=member')
        ->toContain('signature=SIG-MARIA-456');

    expect(Storage::disk('signatures')->get('ER-TEST123/member2.sig'))
        ->toContain('id=uuid-pedro')
        ->toContain('role=member')
        ->toContain('signature=SIG-PEDRO-789');
});

it('overwrites existing signature file on re-sign', function () {
    Storage::fake('signatures');

    $roster = [
        ['id' => 'uuid-maria', 'name' => 'Maria Santos', 'role' => 'member'],
        ['id' => 'uuid-pedro', 'name' => 'Pedro Reyes',  'role' => 'member'],
    ];

    $svc = new SignatureFileStore();

    // First sign (member1)
    $svc->persist([
        'id'        => 'uuid-maria',
        'name'      => 'Maria Santos',
        'role'      => 'member',
        'signature' => 'A1',
        'signed_at' => '2025-08-19T09:31:00Z',
    ], 'ER-XYZ', $roster);

    // Re-sign (should overwrite member1.sig)
    $svc->persist([
        'id'        => 'uuid-maria',
        'name'      => 'Maria Santos',
        'role'      => 'member',
        'signature' => 'NEW-A1',
        'signed_at' => '2025-08-19T10:00:00Z',
    ], 'ER-XYZ', $roster);

    Storage::disk('signatures')->assertExists('ER-XYZ/member1.sig');
    expect(Storage::disk('signatures')->get('ER-XYZ/member1.sig'))
        ->toContain('signature=NEW-A1');
});
