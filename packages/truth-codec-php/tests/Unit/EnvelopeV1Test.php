<?php

use TruthCodec\Envelope\EnvelopeV1;

test('header roundtrip', function () {
    $h = EnvelopeV1::header('C3VJOY0NVVHP', 2, 5);
    expect($h)->toBe('ER|v1|C3VJOY0NVVHP|2/5');

    [$code,$i,$n,$payload] = EnvelopeV1::parseHeader($h.'|xyz');
    expect($code)->toBe('C3VJOY0NVVHP')
        ->and($i)->toBe(2)
        ->and($n)->toBe(5)
        ->and($payload)->toBe('xyz');
});

it('uses default prefix/version when no config/overrides', function () {
    EnvelopeV1::setPrefixOverride(null);
    EnvelopeV1::setVersionOverride(null);

    $hdr = EnvelopeV1::header('XYZ', 1, 3);
    expect($hdr)->toStartWith('ER|v1|XYZ|1/3');
});

it('respects runtime overrides', function () {
    EnvelopeV1::setPrefixOverride('TR');
    EnvelopeV1::setVersionOverride('v1');

    $hdr = EnvelopeV1::header('ABC', 2, 2);
    expect($hdr)->toStartWith('TR|v1|ABC|2/2');

    // parse should also accept the override
    [$code, $idx, $tot, $payload] = EnvelopeV1::parseHeader('TR|v1|CODEX|1/1|payload');
    expect($code)->toBe('CODEX');

    // cleanup
    EnvelopeV1::setPrefixOverride(null);
    EnvelopeV1::setVersionOverride(null);
});

it('reads prefix from config when available', function () {
    // Only works in Laravel test case with config() helper
    config()->set('truth-codec.envelope.prefix', 'ZZ');
    config()->set('truth-codec.envelope.version', 'v1');

    $hdr = EnvelopeV1::header('PQR', 1, 1);
    expect($hdr)->toStartWith('ZZ|v1|PQR|1/1');

    // And parse with config-based prefix
    [$code] = EnvelopeV1::parseHeader('ZZ|v1|C|1/1|x');
    expect($code)->toBe('C');
});
