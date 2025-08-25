<?php

use TruthQr\Contracts\TruthQrWriter;

it('null writer writes deterministic qr lines and preserves keys', function () {
    /** @var TruthQrWriter $writer */
    $writer = app(TruthQrWriter::class);

    // format() should reflect config (defaults to 'png')
    $fmt = $writer->format();
    expect($fmt)->toBe(config('truth-qr.default_format', 'png'));

    // Use non-zero, non-sequential keys to assert key preservation
    $lines = [
        10 => 'ER|v1|XYZ|1/2|PAYLOAD_A',
        11 => 'ER|v1|XYZ|2/2|PAYLOAD_B',
    ];

    $out = $writer->write($lines);

    // shape
    expect($out)->toBeArray()
        ->and(array_keys($out))->toEqual([10, 11]);

    // deterministic content: "qr:<format>:<line>"
    expect($out[10])->toBe("qr:{$fmt}:" . $lines[10])
        ->and($out[11])->toBe("qr:{$fmt}:" . $lines[11]);
});
