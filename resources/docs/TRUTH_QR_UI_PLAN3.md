# Plan: QR Codes for Encoded Results

## 1) Backend Surface
- Reuse `EncodePayload` which already supports `TruthQrWriter`.
- Update `EncodeController` to accept a `writer` param.
- Return `qr` array in the response, aligned with `lines` or `urls`.
- Wrap PNG/EPS binaries into data URLs; keep SVG inline.
- Add guardrails: reject unknown writers, clamp `size` and `margin`, and cap number of rendered parts.

## 2) Writer Options & Formats
- Supported writers:
    - `bacon(svg|png|eps[,size=NN,margin=NN])`
    - `endroid(svg|png[,size=NN,margin=NN])`
    - `null(svg)`
- SVG is preferred (crisp, light, browser-native).
- PNG as fallback (base64 data URL).
- EPS optional.

## 3) API Shape
- Request accepts `writer` (optional).
- Response includes existing fields plus:
  ```json
  {
    "code": "...",
    "by": "...",
    "lines": [...],
    "qr": ["<svg...>", "data:image/png;base64,..."]
  }
  ```

## 4) Frontend UX (Playground)
- Add QR rendering controls:
    - Writer dropdown (`none`, `bacon`, `endroid`).
    - Format dropdown.
    - Size and margin inputs.
- Show QR Gallery panel after encode:
    - Render SVG with `v-html`.
    - Render PNG as `<img src="data:...">`.
    - Download buttons per QR.
    - Pagination for >12 parts.

## 5) Decode Path
- Extend `ScannerPanel` to collect lines from camera.
- Call `/api/decode` incrementally.
- Show progress chips (received i/N).
- Reset & simulate missing controls.

## 6) Error Handling
- Show validation errors (invalid writer spec, extreme size/margin).
- Cap server-side responses (e.g., max 50 parts).
- Frontend displays truncated warnings.

## 7) Security
- Sanitize `v-html` container for SVG.
- Cap payload/part count.
- Avoid rendering thousands of QRs at once.

## 8) Testing
- **PHP Feature Tests**:
    - Encode with bacon(svg) → expect XML string.
    - Encode with endroid(png) → expect data URL.
    - Invalid writer → 422.
- **Browser E2E**:
    - Toggle writer → gallery appears.
    - Download single QR works.
    - Pagination works.

## 9) Docs
- Update README with:
    - Writer options and examples.
    - Curl examples for SVG and PNG.
    - Screenshots of QR gallery.
