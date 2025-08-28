<?php

use Illuminate\Support\Facades\Route;
use TruthQrUi\Http\Controllers\EncodeController;
use TruthCodec\Serializer\JsonSerializer;
use TruthCodec\Transport\Base64UrlDeflateTransport;
use TruthCodec\Envelope\EnvelopeV1Url;

it('EncodeController forwards to EncodePayload::asController', function () {
    Route::post('/api/encode', EncodeController::class);

    $payload = ['type'=>'demo','code'=>'ENC-CTRL-001','data'=>['x'=>1]];

    $resp = $this->postJson('/api/encode', [
        'payload'          => $payload,
        'code'             => $payload['code'],
        'by'               => 'size',
        'size'             => 100,
        'serializer_fqcn'  => JsonSerializer::class,
        'transport_fqcn'   => Base64UrlDeflateTransport::class,
        'envelope_fqcn'    => EnvelopeV1Url::class,
    ])->assertOk();

    $body = $resp->json();
    expect($body)->toHaveKeys(['code','by','urls'])
        ->and($body['code'])->toBe('ENC-CTRL-001');
});
