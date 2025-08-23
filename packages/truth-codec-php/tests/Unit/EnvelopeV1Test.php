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
