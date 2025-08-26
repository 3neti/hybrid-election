<?php

use TruthQr\Publishing\TruthQrPublisherFactory;
use TruthCodec\Serializer\JsonSerializer;
use TruthCodec\Contracts\TransportCodec;
use TruthCodec\Envelope\EnvelopeV1Line;
use TruthCodec\Envelope\EnvelopeV1Url;
use TruthQr\Assembly\TruthAssembler;
use TruthQr\Stores\ArrayTruthStore;
use TruthQr\TruthQrPublisher;

// ---- If you already have this in truth-codec-php, import it.
// ---- Otherwise, keep this tiny inline fake for the first test.
class IdentityTransport implements TransportCodec {
    public function encode(string $data): string { return $data; }
    public function decode(string $data): string { return $data; }
    public function name(): string { return 'identity'; }
}

// ---- If you have a real Base64UrlTransport in truth-codec-php, use it.
// use TruthCodec\Transport\Base64UrlTransport;
class Base64UrlTransport implements TransportCodec {
    public function encode(string $data): string {
        $b64 = rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
        return $b64;
    }
    public function decode(string $data): string {
        $pad = strlen($data) % 4;
        if ($pad) $data .= str_repeat('=', 4 - $pad);
        return base64_decode(strtr($data, '-_', '+/'));
    }
    public function name(): string { return 'base64url'; }
}

/**
 * Helper: split a blob into N roughly-equal parts.
 * We emulate your chunk encoder logic here for clarity.
 *
 * @return array<int,string> 1-based parts
 */
function split_into_parts(string $blob, int $parts): array {
    $len = strlen($blob);
    $chunk = (int) ceil($len / $parts);
    $out = [];
    $i = 1;
    for ($off = 0; $off < $len; $off += $chunk, $i++) {
        $out[$i] = substr($blob, $off, $chunk);
    }
    return $out;
}

it('round-trips ER JSON using LINE envelope + Identity transport', function () {
    // Arrange
    $store      = new ArrayTruthStore();             // in-memory TruthStore
    $envelope   = new EnvelopeV1Line();              // "ER|v1|<code>|i/N|<frag>"
    $serializer = new JsonSerializer();              // canonical JSON
    $transport  = new IdentityTransport();           // no packing

    $asm = new TruthAssembler(
        store: $store,
        envelope: $envelope,
        transport: $transport,
        serializer: $serializer
    );

    $payload = [
        'type' => 'ER',
        'code' => 'CURRIMAO-001',
        'data' => [
            'hello' => 'world',
            'tallies' => [
                ['cand' => 'A', 'votes' => 10],
                ['cand' => 'B', 'votes' => 12],
            ],
        ],
    ];

    // Serialize → split into 4 parts → envelope each part (LINE)
    $blob  = $serializer->encode($payload);
    $parts = split_into_parts($blob, 4);
    $lines = [];
    foreach ($parts as $i => $part) {
        $packed  = $transport->encode($part);
        $lines[] = $envelope->header($payload['code'], $i, count($parts), $packed);
    }

    // Shuffle to simulate random scan order
    shuffle($lines);

    // Ingest
    foreach ($lines as $line) {
        $asm->ingestLine($line);
    }

    // Assemble
    expect($asm->isComplete($payload['code']))->toBeTrue();
    $decoded = $asm->assemble($payload['code']);
    expect($decoded)->toEqual($payload);

    // Artifact (JSON)
    $artifact = $asm->artifact($payload['code']);
    expect($artifact)->toBeArray()
        ->and($artifact['mime'])->toBe('application/json')
        ->and($artifact['body'])->toBe($blob);
});

it('round-trips ER JSON using URL envelope + Base64Url transport (split by count)', function () {
    // Arrange
    $store      = new ArrayTruthStore();
    $envelope   = new EnvelopeV1Url();               // truth://v1/ER/<code>/<i>/<n>?c=<frag>
    $serializer = new JsonSerializer();
    $transport  = new Base64UrlTransport();          // pack with base64url

    $publisher  = new TruthQrPublisher($serializer, $transport, $envelope);

    $asm = new TruthAssembler(
        store: $store,
        envelope: $envelope,
        transport: $transport,
        serializer: $serializer
    );

    $payload = [
        'id' => 'uuid-er-001',
        'code' => 'ER-001',
        'precinct' => [
            'id' => 'uuid-precinct-001',
            'code' => 'CURRIMAO-001',
            'location_name' => 'Currimao Central School',
            'latitude' => 17.993217,
            'longitude' => 120.488902,
            'electoral_inspectors' => [
                [
                    'id' => 'uuid-ei-001',
                    'name' => 'Juan dela Cruz',
                    'role' => 'chairperson',
//                    'signature' => null,
//                    'signed_at' => null,
                ],
                [
                    'id' => 'uuid-ei-002',
                    'name' => 'Maria Santos',
                    'role' => 'member',
//                    'signature' => null,
//                    'signed_at' => null,
                ],
            ],
            'watchers_count' => 2,
            'precincts_count' => 10,
            'registered_voters_count' => 250,
            'actual_voters_count' => 200,
            'ballots_in_box_count' => 198,
            'unused_ballots_count' => 52,
            'spoiled_ballots_count' => 3,
            'void_ballots_count' => 1,
        ],
        'tallies' => [
            [
                'position_code' => 'PRESIDENT',
                'candidate_code' => 'uuid-bbm',
                'candidate_name' => 'Ferdinand Marcos Jr.',
                'count' => 300,
            ],
            [
                'position_code' => 'SENATOR',
                'candidate_code' => 'uuid-jdc',
                'candidate_name' => 'Juan Dela Cruz',
                'count' => 280,
            ],
        ],
        'signatures' => [
            [
                'id' => 'uuid-ei-001',
                'name' => 'Juan dela Cruz',
                'role' => 'chairperson',
                'signature' => 'base64-image-data',
                'signed_at' => '2025-08-07T12:00:00+08:00',
            ],
            [
                'id' => 'uuid-ei-002',
                'name' => 'Maria Santos',
                'role' => 'member',
                'signature' => 'base64-image-data',
                'signed_at' => '2025-08-07T12:05:00+08:00',
            ],
        ],
        'ballots' => [
            [
                'id' => 'uuid-ballot-001',
                'code' => 'BAL-001',
                'precinct' => [
                    'id' => 'uuid-precinct-001',
                    'code' => 'CURRIMAO-001',
                    'location_name' => 'Currimao Central School',
                    'latitude' => 17.993217,
                    'longitude' => 120.488902,
                    'electoral_inspectors' => [
                        [
                            'id' => 'uuid-ei-001',
                            'name' => 'Juan dela Cruz',
                            'role' => 'chairperson',
//                    'signature' => null,
//                    'signed_at' => null,
                        ],
                        [
                            'id' => 'uuid-ei-002',
                            'name' => 'Maria Santos',
                            'role' => 'member',
//                    'signature' => null,
//                    'signed_at' => null,
                        ],
                    ],
                ],
                'votes' => [
                    [
                        'position' => [
                            'code' => 'PRESIDENT',
                            'name' => 'President of the Philippines',
                            'level' => 'national',
                            'count' => 1,
                        ],
                        'candidates' => [
                            [
                                'code' => 'uuid-bbm',
                                'name' => 'Ferdinand Marcos Jr.',
                                'alias' => 'BBM',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'created_at' => '2025-08-07T12:00:00+08:00',
        'updated_at' => '2025-08-07T12:10:00+08:00',
    ];

    // Serialize → split into 3 → envelope each part (URL)
    $urls = $publisher->publish($payload, $payload['code'], [
        'by'   => 'count',
        'count' => 5,
        'size' => 3, // force multiple parts
    ]);

    // Ingest in order (doesn’t matter)
    foreach ($urls as $u) {
        $asm->ingestLine($u);
    }

    // Assemble
    expect($asm->isComplete($payload['code']))->toBeTrue();
    $decoded = $asm->assemble($payload['code']);
    expect($decoded)->toEqual($payload);

    // Artifact (JSON)
    $artifact = $asm->artifact($payload['code']);
    expect($artifact)->toBeArray()
        ->and($artifact['mime'])->toBe('application/json')
//        ->and($artifact['body'])->toBe($blob)
    ;
});

it('round-trips ER JSON using URL envelope + Base64Url transport', function () {
    $store = new ArrayTruthStore();
    $env   = new EnvelopeV1Url();                 // truth://v1/ER/<code>/<i>/<n>?c=...
    $ser   = new JsonSerializer();
    $tx    = new Base64UrlTransport();

    $publisherFactory = app(TruthQrPublisherFactory::class);

    $asm = new TruthAssembler(
        store: $store,
        envelope: $env,
        transport: $tx,
        serializer: $ser
    );

    $payload = [
        'id' => 'uuid-er-001',
        'code' => 'ER-001',
        'precinct' => [
            'id' => 'uuid-precinct-001',
            'code' => 'CURRIMAO-001',
            'location_name' => 'Currimao Central School',
            'latitude' => 17.993217,
            'longitude' => 120.488902,
            'electoral_inspectors' => [
                [
                    'id' => 'uuid-ei-001',
                    'name' => 'Juan dela Cruz',
                    'role' => 'chairperson',
//                    'signature' => null,
//                    'signed_at' => null,
                ],
                [
                    'id' => 'uuid-ei-002',
                    'name' => 'Maria Santos',
                    'role' => 'member',
//                    'signature' => null,
//                    'signed_at' => null,
                ],
            ],
            'watchers_count' => 2,
            'precincts_count' => 10,
            'registered_voters_count' => 250,
            'actual_voters_count' => 200,
            'ballots_in_box_count' => 198,
            'unused_ballots_count' => 52,
            'spoiled_ballots_count' => 3,
            'void_ballots_count' => 1,
        ],
        'tallies' => [
            [
                'position_code' => 'PRESIDENT',
                'candidate_code' => 'uuid-bbm',
                'candidate_name' => 'Ferdinand Marcos Jr.',
                'count' => 300,
            ],
            [
                'position_code' => 'SENATOR',
                'candidate_code' => 'uuid-jdc',
                'candidate_name' => 'Juan Dela Cruz',
                'count' => 280,
            ],
        ],
        'signatures' => [
            [
                'id' => 'uuid-ei-001',
                'name' => 'Juan dela Cruz',
                'role' => 'chairperson',
                'signature' => 'base64-image-data',
                'signed_at' => '2025-08-07T12:00:00+08:00',
            ],
            [
                'id' => 'uuid-ei-002',
                'name' => 'Maria Santos',
                'role' => 'member',
                'signature' => 'base64-image-data',
                'signed_at' => '2025-08-07T12:05:00+08:00',
            ],
        ],
        'ballots' => [
            [
                'id' => 'uuid-ballot-001',
                'code' => 'BAL-001',
                'precinct' => [
                    'id' => 'uuid-precinct-001',
                    'code' => 'CURRIMAO-001',
                    'location_name' => 'Currimao Central School',
                    'latitude' => 17.993217,
                    'longitude' => 120.488902,
                    'electoral_inspectors' => [
                        [
                            'id' => 'uuid-ei-001',
                            'name' => 'Juan dela Cruz',
                            'role' => 'chairperson',
//                    'signature' => null,
//                    'signed_at' => null,
                        ],
                        [
                            'id' => 'uuid-ei-002',
                            'name' => 'Maria Santos',
                            'role' => 'member',
//                    'signature' => null,
//                    'signed_at' => null,
                        ],
                    ],
                ],
                'votes' => [
                    [
                        'position' => [
                            'code' => 'PRESIDENT',
                            'name' => 'President of the Philippines',
                            'level' => 'national',
                            'count' => 1,
                        ],
                        'candidates' => [
                            [
                                'code' => 'uuid-bbm',
                                'name' => 'Ferdinand Marcos Jr.',
                                'alias' => 'BBM',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'created_at' => '2025-08-07T12:00:00+08:00',
        'updated_at' => '2025-08-07T12:10:00+08:00',
    ];

    $lines = $publisherFactory->publish($payload, $payload['code']);

    // Ingest out of order
    shuffle($lines);
    foreach ($lines as $line) {
        $asm->ingestLine($line);
    }

    expect($asm->isComplete($payload['code']))->toBeTrue();

    $decoded = $asm->assemble($payload['code']);
    expect($decoded)->toEqual($payload);
});
