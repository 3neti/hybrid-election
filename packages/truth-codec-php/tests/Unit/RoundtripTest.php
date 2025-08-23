<?php

use TruthCodec\Decode\{ChunkDecoder, ChunkAssembler};
use TruthCodec\Serializer\JsonSerializer;
use TruthCodec\Serializer\YamlSerializer;
use TruthCodec\Encode\ChunkEncoder;

test('roundtrip json', function () {
    $payload = ['type'=>'ER','code'=>'XYZ','data'=>['hello'=>'world']];
    $enc = new ChunkEncoder(new JsonSerializer());
    $lines = $enc->encodeToChunks($payload, 'XYZ', 16);

    $dec = new ChunkDecoder();
    $asm = new ChunkAssembler(new JsonSerializer());

    foreach ($lines as $line) {
        $asm->add($dec->parseLine($line));
    }

    expect($asm->isComplete())->toBeTrue();
    expect($asm->assemble())->toEqual($payload);
});

test('roundtrip yaml', function () {
    $payload = ['type'=>'ballot','id'=>'B123','votes'=>[['position'=>'PRES','candidate'=>'LD']]];
    $enc = new ChunkEncoder(new YamlSerializer());
    $lines = $enc->encodeToChunks($payload, 'B123', 24);

    $dec = new ChunkDecoder();
    $asm = new ChunkAssembler(new YamlSerializer());

    foreach ($lines as $line) {
        $asm->add($dec->parseLine($line));
    }
    expect($asm->assemble())->toBe($payload);
});
