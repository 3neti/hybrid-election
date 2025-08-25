<?php

return [
    // Which writer to bind by default: 'null' | 'bacon'
    'driver' => env('TRUTH_QR_DRIVER', 'null'),

    // Default output format for writers that support multiple formats.
    // bacon: 'svg' (no deps) or 'png' (requires GD/Imagick)
    'default_format' => env('TRUTH_QR_FORMAT', 'png'),

    // Bacon settings
    'bacon' => [
        'size'    => (int) env('TRUTH_QR_SIZE', 512),     // pixels
        'margin'  => (int) env('TRUTH_QR_MARGIN', 16),    // quiet zone
        'level'   => env('TRUTH_QR_ECLEVEL', 'M'),        // L | M | Q | H
    ],
    /*
    |--------------------------------------------------------------------------
    | Default QR size & margin
    |--------------------------------------------------------------------------
    */
    'size'   => 512,
    'margin' => 16,
];
