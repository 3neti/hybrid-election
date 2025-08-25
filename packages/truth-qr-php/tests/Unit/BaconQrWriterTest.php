<?php

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\GDLibRenderer;
use TruthQr\Contracts\TruthQrWriter;

it('renders SVG data for given lines (allows XML prolog)', function () {
    config()->set('truth-qr.driver', 'bacon');
    config()->set('truth-qr.default_format', 'svg');

    /** @var TruthQrWriter $writer */
    $writer = app(TruthQrWriter::class);

    $lines = [
        1 => 'ER|v1|XYZ|1/2|PAYLOAD_A',
        2 => 'ER|v1|XYZ|2/2|PAYLOAD_B',
    ];

    $out = $writer->write($lines);

    expect($out)->toBeArray()
        ->and(array_keys($out))->toEqual([1, 2]);

    // Helper: strip optional BOM, whitespace, and XML prolog so we can assert on <svg>
    $normalize = static function (string $s): string {
        // Strip BOM if present
        if (str_starts_with($s, "\xEF\xBB\xBF")) {
            $s = substr($s, 3);
        }
        $s = ltrim($s);
        // Remove XML prolog if present
        $s = preg_replace('/^<\?xml[^>]*\?>\s*/i', '', $s) ?? $s;
        return ltrim($s);
    };

    $svg1 = $normalize($out[1]);
    $svg2 = $normalize($out[2]);

    // Should now start with <svg and end with </svg>
    expect($svg1)->toStartWith('<svg')->and($svg1)->toEndWith("</svg>\n");
    expect($svg2)->toStartWith('<svg')->and($svg2)->toEndWith("</svg>\n");

    // Optional: quick sanity checks
    expect(strlen($out[1]))->toBeGreaterThan(100); // has some content
    expect(str_contains($out[1], '<rect'))->toBeTrue(); // Bacon often uses <rect> in output
});

it('renders PNG data when PNG backends are present (Imagick or GD)', function () {
    $hasImagick = class_exists(ImagickImageBackEnd::class);
    $hasGd = class_exists(GDLibRenderer::class);

    if (!$hasImagick && !$hasGd) {
        $this->markTestSkipped('PNG backend (Imagick or GDLibRenderer) not available.');
    }

    config()->set('truth-qr.driver', 'bacon');
    config()->set('truth-qr.default_format', 'png');

    /** @var TruthQrWriter $writer */
    $writer = app(TruthQrWriter::class);

    $out = $writer->write([0 => 'TEST']);

    // For Imagick, it’s binary PNG (starts with \x89PNG)
    // For GDLibRenderer, writeString also returns binary data (PNG)
    expect(isset($out[0]))->toBeTrue();
    expect(strlen($out[0]))->toBeGreaterThan(0);

    // If Imagick is used, we can assert the PNG signature; with GD we often can too.
    expect(str_starts_with($out[0], "\x89PNG"))->toBeTrue();
});
