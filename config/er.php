<?php

return [
    'pdf' => [
        'driver' => env('ER_PDF_DRIVER', 'puppeteer'), // puppeteer|reportlab

        // Shared
        'timeout' => (int) env('ER_PDF_TIMEOUT', 30), // seconds

        // Puppeteer/Chromium (Spatie Browsershot or raw chromium)
        'puppeteer' => [
            // Absolute path to Chrome/Chromium binary (mac example shown)
            'binary' => env('CHROME_BIN', '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome'),
            // Extra CLI args if you use a raw Process-based renderer (optional)
            'args'   => explode(' ', env('CHROME_ARGS', '--headless=new --disable-gpu')),
        ],

        // ReportLab (if/when you add it)
        'reportlab' => [
            'python_bin' => env('PYTHON_BIN', 'python3'),
            'script'     => env('REPORTLAB_SCRIPT', base_path('bin/json2pdf.py')),
        ],
    ],
];
