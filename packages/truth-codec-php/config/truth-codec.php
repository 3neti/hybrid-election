<?php

return [
    // decode order for auto-detection, by name registered in the registry
    'auto_detect_order' => ['json', 'yaml'],

    // which serializer to use for ENCODE when using AutoDetectSerializer
    'primary' => 'json',

    // Transport: 'none', 'base64url', 'base64url+gzip'
    'transport' => env('TRUTH_TRANSPORT', 'none'),
];
