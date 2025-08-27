# Truth QR – Next Steps Roadmap

This document lists the *practical, short-to-medium term steps* to take now that **GenerateQrForJson** and **DecodeQrChunks** pass all tests and the `truth-codec-php` / `truth-qr-php` packages are integrated.

---

## 1) Commit & Tag the Current State

1. Review the diff:
   ```bash
   git add -A
   git status
   ```
2. Run the full test suite:
   ```bash
   php artisan test
   ```
3. Commit with a clear message:
   ```bash
   git commit -m "feat(qr): integrate truth-qr-php classify/publish flows; actions stabilized; e2e passing"
   ```
4. (Optional) Tag prerelease versions for both packages:
   ```bash
   git tag truth-qr-php-v0.2.0
   git tag truth-codec-php-v0.2.0
   ```
5. Push:
   ```bash
   git push --tags
   ```

---

## 2) Wire Packages in the Host App

- **Service providers**
    - Ensure `TruthCodecServiceProvider` and `TruthQrServiceProvider` are registered (Testbench already does this in tests).
- **Config**
    - Publish & adjust config where needed. Key settings:
        - `truth-qr.writer.driver` = `bacon` or `endroid`
        - `truth-qr.writer.format` = `svg` or `png` (EPS supported for Bacon)
        - `truth-qr.transport` = `base64url+deflate` (recommended) or `base64url+gzip`
        - `truth-qr.publish` defaults (e.g., `strategy`, `count`, `size`)
- **Bindings**
    - The `TruthQrWriter` binding supports `null`, `bacon`, `endroid` drivers.
- **Routes**
    - Use the package registrar (or your own group) with optional throttle:
      ```php
      // routes/api.php in host app
      \TruthQr\Routing\RouteRegistrar::register([
          'prefix' => 'truth',
          'middleware' => ['api', 'throttle:60,1'],
      ]);
      ```

---

## 3) End-to-End User Flows

### A) Publish QR for an Election Return
- Use `App\Actions\GenerateQrForJson`.
- Supports *full/minimal* payload, target chunk count vs max size, and optional persistence.
- Example:
  ```php
  $res = app(\App\Actions\GenerateQrForJson::class)->run(
      json: $payloadArray,
      code: 'ER-001',
      makeImages: true,
      maxCharsPerQr: 1200,
      forceSingle: false
  );
  // $res['chunks'][i]['text'] and optional ['png']
  ```
- Alternative: use `TruthQrPublisherFactory` directly if you want pure URL/LINE envelopes and hand off to a writer later.

### B) Scan & Decode (Round Trip)
- Use `App\Actions\DecodeQrChunks` for sets of scanned lines:
  ```php
  $out = app(\App\Actions\DecodeQrChunks::class)->run($lines);
  // $out['json'] when complete, or missing indices otherwise
  ```
- For streaming ingest: `Classify` + `ClassifySession` accumulate lines over time and assemble when complete.

---

## 4) CLI & Background Ingestion

- **CLI**: `truth:ingest-file` (reads lines, assembles when complete, writes artifact).
  ```bash
  php artisan truth:ingest-file storage/lines.txt --print --out storage/artifact.json
  ```
- **Webhook flow**: `/truth/ingest` → `TruthIngestController` → store → assemble → fetch via `/truth/artifact/{code}`.
- Add a queue worker for high-throughput scan uploads; consider idempotent dedupe and backpressure (throttle).

---

## 5) Hardening & Operational Readiness

- **Chunk sizing heuristics**: Keep an eye on real scanner tolerance; adjust default `publish.strategy` (`count` vs `size`) and bounds.
- **Transport consistency**: Production recommends `base64url+deflate`. Ensure both generator and decoder agree.
- **Duplicate & conflict handling**: Already covered in `Classify`/decoder; keep explicit tests.
- **Validation**: Mismatched code/version/total → 422 with clear message.
- **Rate limiting**: Use `throttle:60,1` (tune as needed) on public endpoints.
- **Caching**: Artifact caching is implemented; decide retention & eviction (e.g., Redis TTL or cleanup job).

---

## 6) Additional Tests to Add

- **Writers**: Endroid vs Bacon parity (SVG/PNG), EPS (if available).
- **PublisherFactory**: defaults vs per-call overrides; count/size strategies.
- **Classify**: missing chunk reporting; duplicate tolerance; out-of-order ingest.
- **Transports**: round-trip for `base64url+deflate` and `base64url+gzip`.
- **Routes**: throttle present, artifact stream headers, 404/422 behaviors.
- **Actions**: persistence branches (exports/decodes) create expected files.

---

## 7) Monitoring & Observability

- Log structured events for: ingest, dedupe, missing indices, assembly success/failure.
- Emit metrics (Prometheus/StatsD): lines ingested, sessions complete, average chunks per ER, error rates.
- Add minimal trace IDs to correlate scan → assembly → artifact download.

---

## 8) Security & Privacy

- **Artifacts**: define TTLs & access rules; avoid leaking PII in logs.
- **CORS**: limit origins for public endpoints as needed.
- **Webhooks**: validate signatures if used externally.
- **Signatures/Images**: keep out of “minimal” payload; document policy clearly.

---

## 9) Documentation to Maintain

- **README** for both packages (codec & qr): quickstart, config, examples.
- **CHANGELOG**: semver entries for features & breaking changes.
- **Migration guide**: how host apps move from legacy implementation to `truth-qr-php` actions/publisher/classify.
- **API docs**: endpoints, request/response shapes, error codes.

---

## 10) Future Enhancements (Backlog)

- Shortlink deep-links for QR scan flows.
- Progressive scanner UI (shows which chunks are missing).
- Alternate envelopes (LINE vs URL) togglable at publish time.
- On-device pre-check to estimate readability before printing.
- Batch operations for precincts / bulk export.

---

## 11) Release/Deployment Checklist

- ✅ Green tests locally and in CI.
- ✅ Package discovery & config in host app.
- ✅ Routes protected with throttle & optional auth if needed.
- ✅ Observability & logs enabled.
- ✅ Tag & release to Packagist (if public) or private registry; pin versions in composer.
- ✅ Rollout plan with canaries if this is user-facing.

---

### Quick References

**Publish group with throttle**
```php
\TruthQr\Routing\RouteRegistrar::register([
    'prefix' => 'truth',
    'middleware' => ['api', 'throttle:60,1'],
]);
```

**Transport default (host .env)**
```
TRUTH_TRANSPORT=base64url+deflate
```

**Writer (host .env)**
```
TRUTH_QR_WRITER=bacon      # or endroid
TRUTH_QR_FORMAT=svg        # or png
TRUTH_QR_SIZE=512
TRUTH_QR_MARGIN=16
```

---

**Status:** You’re in a strong position to extend features. Proceed with commit/tag, integrate the route group, and begin wiring the end-user flows (publish → print → scan → decode) with observability and guardrails.
