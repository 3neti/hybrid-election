<?php

use App\Policies\Signatures\ChairPlusMemberPolicy;
use App\Policies\Signatures\SignaturePolicy;
use App\Console\Pipes\ValidateSignatures;
use Illuminate\Support\Collection;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// --- helpers --------------------------------------------------------------

/** Build a signature row, role can be string or an enum instance */
function sig(string|object $role, ?string $id = null, ?string $name = null): array {
    return [
        'id'   => $id ?? ('uuid-' . strtolower(is_object($role) ? (property_exists($role,'value') ? $role->value : 'x') : $role)),
        'name' => $name ?? 'Person',
        'role' => $role,
    ];
}

/** Minimal ctx object for the pipe */
function ctx(array|Collection $signatures, bool $force = false): object {
    $er = (object) ['signatures' => $signatures];
    return (object) ['er' => $er, 'force' => $force];
}

// --- tests ---------------------------------------------------------------

it('passes when chair + at least one member are present', function () {
    /** @var SignaturePolicy $policy */
    $policy = new ChairPlusMemberPolicy();
    $pipe   = new ValidateSignatures($policy);

    $called = false;
    $next = function ($c) use (&$called) { $called = true; return $c; };

    $c = ctx([
        sig('chairperson', 'uuid-chair', 'Chair'),
        sig('member', 'uuid-m1', 'Member 1'),
    ]);

    $pipe->handle($c, $next);

    expect($called)->toBeTrue();
});

it('throws when requirements are not met and not forced', function () {
    $policy = new ChairPlusMemberPolicy();
    $pipe   = new ValidateSignatures($policy);

    $next = fn ($c) => $c;

    $c = ctx([
        // missing chairperson here
        sig('member', 'uuid-m1', 'Member 1'),
    ], force: false);

    $pipe->handle($c, $next);
})->throws(RuntimeException::class, 'Missing required signatures');

it('allows closing with --force even if signatures are incomplete', function () {
    $policy = new ChairPlusMemberPolicy();
    $pipe   = new ValidateSignatures($policy);

    $called = false;
    $next = function ($c) use (&$called) { $called = true; return $c; };

    $c = ctx([
        // still incomplete (no chair), but force=true
        sig('member', 'uuid-m1', 'Member 1'),
    ], force: true);

    $pipe->handle($c, $next);

    expect($called)->toBeTrue();
});

it('normalizes mixed inputs (Collection + enum role objects)', function () {
    $policy = new ChairPlusMemberPolicy();
    $pipe   = new ValidateSignatures($policy);

    $called = false;
    $next = function ($c) use (&$called) { $called = true; return $c; };

    // If you have an enum like App\Enums\ElectoralInspectorRole, use it:
    $chairEnum  = class_exists(\App\Enums\ElectoralInspectorRole::class)
        ? \App\Enums\ElectoralInspectorRole::CHAIRPERSON
        : (object)['value' => 'chairperson']; // fallback fake enum-like

    $memberEnum = class_exists(\App\Enums\ElectoralInspectorRole::class)
        ? \App\Enums\ElectoralInspectorRole::MEMBER
        : (object)['value' => 'member'];

    // Mixed input: first as Collection, second as array
    $signatures = new Collection([
        collect(sig($chairEnum, 'uuid-chair', 'Chair')), // nested collection
        sig($memberEnum, 'uuid-m1', 'Member 1'),         // plain array
    ]);

    $c = ctx($signatures, force: false);

    // Should NOT throw, and should call $next
    $pipe->handle($c, $next);

    expect($called)->toBeTrue();
});
