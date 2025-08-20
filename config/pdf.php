<?php

return [
    'er' => [
        'driver' => env('ER_PDF_DRIVER', 'puppeteer'), // 'puppeteer' or 'reportlab'
        'fallback_to' => env('ER_PDF_FALLBACK', 'reportlab'), // null to disable
        'timeout' => 30, // seconds for external processes
        'puppeteer' => [
            'binary' => env('PUPPETEER_BINARY', '/usr/bin/chromium'),
            'args'   => ['--no-sandbox', '--disable-gpu'],
        ],
        'reportlab' => [
            'python' => env('REPORTLAB_PYTHON', '/usr/bin/python3'),
            'script' => base_path('tools/reportlab/er_json2pdf.py'),
            'venv'   => env('REPORTLAB_VENV', null), // e.g. /opt/er-venv/bin/python
        ],
    ],
];
