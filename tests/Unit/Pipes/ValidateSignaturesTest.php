<?php

use App\Console\Pipelines\FinalizeErContext;
use App\Console\Pipes\ValidateSignatures;
use App\Models\Precinct;
use App\Policies\Signatures\ChairPlusMemberPolicy;
use App\Policies\Signatures\SignaturePolicy;
use Illuminate\Support\Collection;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// --- helpers --------------------------------------------------------------

/** Build a signature row, role can be string or an enum instance */
function sig(string|object $role, ?string $id = null, ?string $name = null): array {
    $roleVal = is_object($role) && property_exists($role, 'value') ? $role->value : $role;

    return [
        'id'   => $id ?? ('uuid-' . strtolower((string)$roleVal)),
        'name' => $name ?? 'Person',
        'role' => $role, // keep original (string or enum-like); pipe/policy will normalize
    ];
}

/**
 * Build a minimal FinalizeErContext the pipe expects.
 * - Creates a real Precinct (DB) to satisfy the typed context.
 * - Wraps signatures in a tiny ER-like object with ->signatures.
 */
function ctx(array|Collection $signatures, bool $force = false): FinalizeErContext {
    /** @var Precinct $precinct */
    $precinct = Precinct::factory()->create();

    // tiny ER-like object that exposes ->signatures
    $er = new class($signatures) {
        public function __construct(public array|Collection $signatures) {}
    };

    return new FinalizeErContext(
        precinct: $precinct,
        er:       $er,
        disk:     'election',          // not used by this pipe, but context requires it
        folder:   'ER-DUMMY/final',    // ditto
        payload:  'minimal',
        maxChars: 1200,
        force:    $force,
    );
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
})->skip();

it('throws when requirements are not met and not forced', function () {
    $policy = new ChairPlusMemberPolicy();
    $pipe   = new ValidateSignatures($policy);

    $next = fn ($c) => $c;

    $c = ctx([
        // missing chairperson here
        sig('member', 'uuid-m1', 'Member 1'),
    ], force: false);

    $pipe->handle($c, $next);
})->throws(RuntimeException::class, 'Missing required signatures')->skip();

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
})->skip();

it('normalizes mixed inputs (Collection + enum role objects)', function () {
    $policy = new ChairPlusMemberPolicy();
    $pipe   = new ValidateSignatures($policy);

    $called = false;
    $next = function ($c) use (&$called) { $called = true; return $c; };

    // If you have an enum like App\Enums\ElectoralInspectorRole, use it:
    $chairEnum  = class_exists(\App\Enums\ElectoralInspectorRole::class)
        ? \App\Enums\ElectoralInspectorRole::CHAIRPERSON
        : (object)['value' => 'chairperson']; // fallback enum-like

    $memberEnum = class_exists(\App\Enums\ElectoralInspectorRole::class)
        ? \App\Enums\ElectoralInspectorRole::MEMBER
        : (object)['value' => 'member'];

    // Mixed input: first as Collection (even nested), second as array
    $signatures = new Collection([
        collect(sig($chairEnum, 'uuid-chair', 'Chair')), // nested collection
        sig($memberEnum, 'uuid-m1', 'Member 1'),         // plain array
    ]);

    $c = ctx($signatures, force: false);

    // Should NOT throw, and should call $next
    $pipe->handle($c, $next);

    expect($called)->toBeTrue();
})->skip();
