<?php

use App\Console\Pipes\MirrorQrArtifacts;
use Illuminate\Support\Facades\Storage;

it('mirrors manifest and files into election disk on success', function () {
    $code = 'ER1ABC';
    $dir  = 'final';

    $ctx = (object)[
        'er'      => (object)['code' => $code],
        'disk'    => 'election',
        'folder'  => "ER-{$code}/{$dir}",
    ];

    // Seed the "persisted_to" directory under the local disk
    $qrRel = "private/qr_exports/{$code}/{$dir}";
    $qrAbs = Storage::disk('local')->path($qrRel);

    Storage::disk('local')->put($qrRel.'/manifest.json', json_encode(['hello' => 'world']));
    Storage::disk('local')->put($qrRel.'/chunk_1of2.txt', 'QR|v1|...|1/2|AAA');
    Storage::disk('local')->put($qrRel.'/chunk_2of2.txt', 'QR|v1|...|2/2|BBB');
    Storage::disk('local')->put($qrRel.'/chunk_1of2.png', 'PNG1');
    Storage::disk('local')->put($qrRel.'/chunk_2of2.png', 'PNG2');

    // Pretend ExportQr already ran
    $ctx->qrPersistedAbs = $qrAbs;

    // Run mirror pipe
    $pipe = new MirrorQrArtifacts();
    $pipe->handle($ctx, fn($c) => $c);

    // Expect mirrored files at ER-<code>/<dir>/qr/*
    $base = "ER-{$code}/{$dir}/qr";
    expect(Storage::disk('election')->exists("{$base}/manifest.json"))->toBeTrue();
    expect(Storage::disk('election')->exists("{$base}/chunk_1of2.txt"))->toBeTrue();
    expect(Storage::disk('election')->exists("{$base}/chunk_2of2.txt"))->toBeTrue();
    expect(Storage::disk('election')->exists("{$base}/chunk_1of2.png"))->toBeTrue();
    expect(Storage::disk('election')->exists("{$base}/chunk_2of2.png"))->toBeTrue();
});
