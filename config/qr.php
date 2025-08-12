<?php

return [
    'persist' => [
        'enabled'   => env('QR_PERSIST_ENABLED', false),
        'disk'      => env('QR_PERSIST_DISK', 'local'),
        'base_path' => env('QR_PERSIST_BASE', 'qr'), // under chosen disk
    ],
];
