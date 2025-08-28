<?php
// tests/Unit/CodecAliasFactoryTest.php

declare(strict_types=1);

use TruthQrUi\Support\CodecAliasFactory as Alias;

// truth-codec-php
use TruthCodec\Contracts\Envelope;
use TruthCodec\Contracts\PayloadSerializer;
use TruthCodec\Contracts\TransportCodec;
use TruthCodec\Envelope\EnvelopeV1Line;
use TruthCodec\Envelope\EnvelopeV1Url;
use TruthCodec\Serializer\AutoDetectSerializer;
use TruthCodec\Serializer\JsonSerializer;
use TruthCodec\Serializer\YamlSerializer;
use TruthCodec\Transport\Base64UrlDeflateTransport;
use TruthCodec\Transport\Base64UrlGzipTransport;
use TruthCodec\Transport\Base64UrlTransport;
use TruthCodec\Transport\NoopTransport;

// truth-qr-php
use TruthQr\Contracts\TruthQrWriter;
use TruthQr\Writers\BaconQrWriter;
use TruthQr\Writers\EndroidQrWriter;
use TruthQr\Writers\NullQrWriter;

/** Serializers */
it('maps serializer aliases to concrete implementations', function () {
    expect(Alias::makeSerializer('json'))->toBeInstanceOf(JsonSerializer::class);
    expect(Alias::makeSerializer('yaml'))->toBeInstanceOf(YamlSerializer::class);
    expect(Alias::makeSerializer('auto'))->toBeInstanceOf(AutoDetectSerializer::class);
//    // alias variants
    expect(Alias::makeSerializer('yml'))->toBeInstanceOf(YamlSerializer::class);
    expect(Alias::makeSerializer('autodetect'))->toBeInstanceOf(AutoDetectSerializer::class);
});

/** Transports */
it('maps transport aliases to concrete implementations', function () {
    expect(Alias::makeTransport('none'))->toBeInstanceOf(NoopTransport::class);
    expect(Alias::makeTransport('base64url'))->toBeInstanceOf(Base64UrlTransport::class);
    expect(Alias::makeTransport('b64url'))->toBeInstanceOf(Base64UrlTransport::class);
    expect(Alias::makeTransport('base64url+deflate'))->toBeInstanceOf(Base64UrlDeflateTransport::class);
    expect(Alias::makeTransport('b64url+deflate'))->toBeInstanceOf(Base64UrlDeflateTransport::class);
    expect(Alias::makeTransport('base64url+gzip'))->toBeInstanceOf(Base64UrlGzipTransport::class);
    expect(Alias::makeTransport('b64url+gzip'))->toBeInstanceOf(Base64UrlGzipTransport::class);
});

/** Envelopes */
it('maps envelope aliases to concrete implementations', function () {
    expect(Alias::makeEnvelope('v1url'))->toBeInstanceOf(EnvelopeV1Url::class);
    expect(Alias::makeEnvelope('url'))->toBeInstanceOf(EnvelopeV1Url::class);
    expect(Alias::makeEnvelope('v1line'))->toBeInstanceOf(EnvelopeV1Line::class);
    expect(Alias::makeEnvelope('line'))->toBeInstanceOf(EnvelopeV1Line::class);
});

/** Writers: Null (always available) */
it('creates a Null writer from spec', function () {
    $w = Alias::makeWriter('null(svg)');
    expect($w)->toBeInstanceOf(NullQrWriter::class);
    // interface contract typically exposes ->format()
    expect(method_exists($w, 'format'))->toBeTrue();
    expect($w->format())->toBe('svg');
});

/** Writers: Bacon (skip if package not installed) */
it('creates a Bacon writer from spec with tunables', function () {
    if (!class_exists(\BaconQrCode\Writer::class)) {
        test()->markTestSkipped('bacon/qr-code not installed.');
    }

    /** @var TruthQrWriter $w */
    $w = Alias::makeWriter('bacon(svg,size=300,margin=8)');
    expect($w)->toBeInstanceOf(BaconQrWriter::class);
    expect($w->format())->toBe('svg');
})->group('writers');

/** Writers: Endroid (skip if package not installed) */
it('creates an Endroid writer from spec', function () {
    if (!class_exists(\Endroid\QrCode\Builder\Builder::class)) {
        test()->markTestSkipped('endroid/qr-code not installed.');
    }

    /** @var TruthQrWriter $w */
    $w = Alias::makeWriter('endroid(png,size=256,margin=12)');
    expect($w)->toBeInstanceOf(EndroidQrWriter::class);
    expect($w->format())->toBe('png');
})->group('writers');

/** Invalid inputs */
it('throws for unknown aliases and specs', function () {
    expect(fn () => Alias::makeSerializer('toml'))->toThrow(InvalidArgumentException::class);
    expect(fn () => Alias::makeTransport('foobar'))->toThrow(InvalidArgumentException::class);
    expect(fn () => Alias::makeEnvelope('v2line'))->toThrow(InvalidArgumentException::class);
    expect(fn () => Alias::makeWriter('pixelart(gif)'))->toThrow(InvalidArgumentException::class);
});
