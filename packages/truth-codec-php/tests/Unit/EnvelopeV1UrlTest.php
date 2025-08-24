<?php

use TruthCodec\Envelope\EnvelopeV1Url;

it('round-trips a deep-link truth:// URL envelope', function () {
    // Defaults: prefix=ER, version=v1, scheme=truth, no webBase (so deep links)
    $env = new EnvelopeV1Url();

    $code = 'CURRIMAO-001';
    $i    = 1;
    $n    = 3;
    // include characters to exercise RFC3986 encoding
    $payload = 'eyJmb28iOiJiYXI/IyAmIiE9In0=';

    $url = $env->header($code, $i, $n, $payload);

    // Example:
    // truth://v1/ER/CURRIMAO-001/1/3?c=eyJmb28iOiJiYXI%2FIyAmIiE9In0%3D
    expect($url)->toStartWith('truth://v1/ER/'.rawurlencode($code).'/1/3?');

    [$c2, $i2, $n2, $p2] = $env->parse($url);
    expect([$c2, $i2, $n2, $p2])->toEqual([$code, $i, $n, $payload]);
});

it('round-trips a web URL http(s) envelope', function () {
    // Configure a web base so we emit https links
    $env = new EnvelopeV1Url(
        payloadParam: 'c',
        versionParam: 'truth',
        webBase: 'https://truth.example/ingest',
        scheme: 'truth'
    );

    $code = 'XYZ';
    $i    = 2;
    $n    = 4;
    $payload = 'A_B-C~safe.payload';

    $url = $env->header($code, $i, $n, $payload);

    // Example:
    // https://truth.example/ingest?truth=v1&prefix=ER&code=XYZ&i=2&n=4&c=A_B-C~safe.payload
    expect($url)->toStartWith('https://truth.example/ingest?');

    [$c2, $i2, $n2, $p2] = $env->parse($url);
    expect([$c2, $i2, $n2, $p2])->toEqual([$code, $i, $n, $payload]);
});

it('rejects invalid deep-link structures', function () {
    $env = new EnvelopeV1Url();

    // Not enough segments
    $bad = 'truth://v1/ER/CODE/1?c=abc';
    expect(fn () => $env->parse($bad))->toThrow(InvalidArgumentException::class);

    // Missing payload param
    $bad2 = 'truth://v1/ER/CODE/1/3';
    expect(fn () => $env->parse($bad2))->toThrow(InvalidArgumentException::class);

    // Prefix/version mismatch
    $bad3 = 'truth://v2/TRUTH/CODE/1/3?c=x';
    expect(fn () => $env->parse($bad3))->toThrow(InvalidArgumentException::class);

    // Index out of range
    $bad4 = 'truth://v1/ER/CODE/4/3?c=x';
    expect(fn () => $env->parse($bad4))->toThrow(InvalidArgumentException::class);
});

it('rejects invalid web URL structures', function () {
    $env = new EnvelopeV1Url(
        payloadParam: 'c',
        versionParam: 'truth',
        webBase: 'https://truth.example/ingest',
        scheme: 'truth'
    );

    // Missing query
    $bad = 'https://truth.example/ingest';
    expect(fn () => $env->parse($bad))->toThrow(InvalidArgumentException::class);

    // Missing required params
    $bad2 = 'https://truth.example/ingest?truth=v1&prefix=ER&code=XYZ&i=1';
    expect(fn () => $env->parse($bad2))->toThrow(InvalidArgumentException::class);

    // Prefix/version mismatch
    $bad3 = 'https://truth.example/ingest?truth=v2&prefix=ER&code=XYZ&i=1&n=2&c=x';
    expect(fn () => $env->parse($bad3))->toThrow(InvalidArgumentException::class);

    // Index out of range
    $bad4 = 'https://truth.example/ingest?truth=v1&prefix=ER&code=XYZ&i=4&n=3&c=x';
    expect(fn () => $env->parse($bad4))->toThrow(InvalidArgumentException::class);
});
