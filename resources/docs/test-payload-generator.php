<?php
/**
 * test-payload-generator.php
 *
 * Generate QR-style chunk lines from a JSON file and (optionally) POST them
 * to a decode endpoint (e.g., /api/qr/decode).
 *
 * Usage (basic):
 *   php test-payload-generator.php --file=resources/docs/sample-er.json --code=DEMO123 --max=1200
 *
 * Usage (target ~N chunks instead of fixed max):
 *   php test-payload-generator.php --file=resources/docs/sample-er.json --code=DEMO123 --desired=5
 *
 * Usage (POST to decoder endpoint too):
 *   php test-payload-generator.php --file=resources/docs/sample-er.json --code=DEMO123 --desired=5 \
 *     --post=http://localhost/api/qr/decode --persist=1 --persist-dir=cli_demo --token="Bearer X..."
 */

ini_set('memory_limit', '512M');

parse_str(implode('&', array_slice($argv, 1)), $args);

function requireArg(array $args, string $key, string $hint) {
    if (!isset($args[$key]) || $args[$key] === '') {
        fwrite(STDERR, "Missing --{$key}. {$hint}\n");
        exit(1);
    }
    return $args[$key];
}

function b64url_encode(string $bin): string {
    return rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');
}

function human_bytes(int $n): string {
    if ($n < 1024) return "{$n} bytes";
    if ($n < 1024*1024) return sprintf('%.2f KB', $n/1024);
    return sprintf('%.2f MB', $n/1024/1024);
}

$file     = requireArg($args, 'file', 'Point to a JSON file.');
$code     = requireArg($args, 'code', 'Provide a short ER code like DEMO123.');
$max      = isset($args['max']) ? (int)$args['max'] : null;             // max chars per QR
$desired  = isset($args['desired']) ? max(1, (int)$args['desired']) : null; // target chunks
$postUrl  = isset($args['post']) ? (string)$args['post'] : null;        // endpoint to POST chunks
$persist  = isset($args['persist']) ? (bool)$args['persist'] : false;
$persistDir = isset($args['persist-dir']) ? trim((string)$args['persist-dir']) : '';
$token    = isset($args['token']) ? (string)$args['token'] : '';        // e.g. "Bearer <token>"

if (!file_exists($file)) {
    fwrite(STDERR, "File not found: {$file}\n");
    exit(1);
}

$raw = file_get_contents($file);
if ($raw === false) {
    fwrite(STDERR, "Failed to read file: {$file}\n");
    exit(1);
}
if (!json_decode($raw, true)) {
    fwrite(STDERR, "Warning: input file is not valid JSON or contains errors (continuing anyway)…\n");
}

$deflated   = gzdeflate($raw, 9);
$base64url  = b64url_encode($deflated);
$b64Len     = strlen($base64url);

$chosenMax = $max;
if ($desired && !$max) {
    // Compute suggested max that yields ≈ desired chunks (clamp for scan reliability)
    $computed  = (int)ceil($b64Len / max(1, $desired));
    $chosenMax = max(600, min($computed, 2400));
}
if (!$chosenMax) {
    $chosenMax = 1200; // default
}

$chunks = str_split($base64url, $chosenMax);
$total  = count($chunks);
$lines  = [];
foreach ($chunks as $i => $payload) {
    $lines[] = sprintf('ER|v1|%s|%d/%d|%s', $code, $i+1, $total, $payload);
}

// Save lines to a file next to the script
$outFile = __DIR__ . "/test-payload-{$code}.txt";
file_put_contents($outFile, implode("\n", $lines));

// Stats
echo "QR / Compression measurement\n";
echo "-----------------------------\n";
echo "File:              {$file}\n";
echo "Raw JSON:          " . strlen($raw) . " bytes (" . human_bytes(strlen($raw)) . ")\n";
echo "DEFLATE:           " . strlen($deflated) . " bytes (" . human_bytes(strlen($deflated)) . ")\n";
echo "Base64URL length:  {$b64Len} chars (" . human_bytes($b64Len) . " text)\n";
echo "max_chars_per_qr:  {$chosenMax}\n";
echo "Chunk count:       ~{$total}\n";
echo "Saved lines:       {$outFile}\n\n";

// Optional POST
if ($postUrl) {
    echo "Posting to decoder: {$postUrl}\n";
    $payload = [
        'chunks'      => $lines,
        'persist'     => $persist ? true : false,
    ];
    if ($persist && $persistDir !== '') {
        $payload['persist_dir'] = $persistDir;
    }

    $ch = curl_init($postUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => array_values(array_filter([
            'Content-Type: application/json',
            $token ? "Authorization: {$token}" : null,
        ])),
        CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_SLASHES),
        CURLOPT_TIMEOUT        => 60,
    ]);

    $resp = curl_exec($ch);
    $err  = curl_error($ch);
    $codeHttp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($resp === false) {
        fwrite(STDERR, "cURL error: {$err}\n");
        exit(2);
    }

    echo "HTTP {$codeHttp}\n";
    $json = json_decode($resp, true);
    if (is_array($json)) {
        // Nice summary of what the server said
        $missing = $json['missing_indices'] ?? [];
        echo "Decoder said:\n";
        echo "  code:              " . ($json['code'] ?? '-') . "\n";
        echo "  version:           " . ($json['version'] ?? '-') . "\n";
        echo "  total:             " . ($json['total'] ?? '-') . "\n";
        echo "  received_indices:  " . json_encode($json['received_indices'] ?? []) . "\n";
        echo "  missing_indices:   " . json_encode($missing) . "\n";
        if (empty($missing) && isset($json['json'])) {
            echo "  ✅ JSON reconstructed by server.\n";
        } else {
            echo "  ⚠ Server could not reconstruct JSON (missing or error).\n";
        }
        if (!empty($json['persisted_to'])) {
            echo "  persisted_to:      " . $json['persisted_to'] . "\n";
        }
    } else {
        echo "Response:\n{$resp}\n";
    }
}
