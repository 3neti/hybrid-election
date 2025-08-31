# truth-renderer-php Strategy and Plan

## Backend (truth-renderer-php)

### Goals
- Receive: `{ template, exportType, data, options }`
- Validate `data` against the template’s JSON Schema
- Render via a pluggable pipeline into **HTML** (canonical), then transform to **PDF/MD/DOC** as needed
- Auditable, deterministic, reproducible outputs

### High-level Architecture
- **Core package**: `truth-renderer-php`
    - **Template Registry** (discover/resolve templates)
    - **Schema Validation** (AJV-like via PHP: justinrainbow/json-schema, opis/json-schema, or league/json-guard)
    - **Data Normalizer** (JSON/YAML → PHP arrays; coercion & defaults)
    - **Renderer** (Handlebars/Mustache to HTML as canonical; MD option via templates too)
    - **Post-Processors**:
        - HTML → PDF (wkhtmltopdf or headless Chromium via Spatie Browsershot)
        - HTML → DOCX (phpword template inject or pandoc if available)
        - HTML/MD passthrough for MD/HTML exports
    - **Signature & Provenance** (optional): embed metadata (schema hash, template version, renderer version)
- **Adapters**:
    - **HTTP Controller** (Laravel/Symfony or Slim app) for `/render`
    - **CLI** for batch or offline rendering
    - **Queue Worker** for async heavy PDF jobs
- **Storage**:
    - Templates on disk (versioned) or S3/Git-backed
    - Optional cache (Redis) for compiled templates and schema loads

### Data Model
- **RenderRequest**
    - `template`, `exportType`, `data`, `format`, `locale`, `options`, `metadata`
- **RenderResponse**
    - `status`, `outputType`, `bytes` or `downloadUrl`, `diagnostics`, `errors`

### Template Registry (Versioned)
```
templates/
  invoice/
    basic-v1/
      template.hbs
      schema.json
      partials/
      assets/
      styles.css
      manifest.json
    basic-v2/ ...
  ballot/
    v1/ ...
  election-return/
    v1/ ...
```
- `manifest.json`: `name`, `version`, `exportCapabilities`, `locales`, `description`, etc.
- Support **partials**, **helpers**, **themes**

### Validation & Normalization
- Load schema → validate → normalize (defaults, coercion, canonicalize locales)

### Rendering Pipeline
1. Parse input JSON/YAML → array
2. Validate
3. Preprocess (totals, QR/Barcodes, derived fields)
4. Render to HTML (canonical)
5. Post-process: PDF/DOC/MD
6. Output: file or URL

### API Surface
- HTTP: `/render`, `/templates`
- CLI: `render --template=... --export=pdf ...`
- PHP SDK

### Security & Compliance
- Sandbox templates
- Escape input by default
- Timeouts & limits
- Provenance in PDF metadata
- Signing optional

### Performance & Scale
- Cache compiled templates
- Chromium pool for PDF
- Queue large jobs
- CDN for assets

### Observability
- Logs w/ requestId
- Metrics (duration, pages, errors)
- Template/schema version in logs

### Extensibility
- Template packs (composer)
- Helper packs
- Exporter interface

---

## Frontend (Vue App)

### Goals
- Allow choosing **template**, **export type**, **data** source, **locale**, **options**
- Validate client-side (optional)
- Preview HTML, request backend for PDF/DOC

### Key Screens
1. Template Picker
2. Data Input (JSON/YAML/Upload)
3. Options (export type, paper, theme)
4. Preview & Render
5. History

### Integration Points
- ScannerPanel for QR chunk capture
- JSON editor composable
- Download Manager for outputs

### UX Touches
- Real-time validation
- Sample data presets
- Multi-locale toggle

---

## Inputs Contract

Example request to `/render`:
```json
{
  "template": "invoice/basic-v2",
  "exportType": "pdf",
  "data": { "code": "INV-123", "items": [] },
  "format": "json",
  "locale": "en-US",
  "options": { "paperSize": "A4", "margins": "12mm" },
  "metadata": { "requestId": "uuid-v4", "source": "playground-ui" }
}
```

---

## Template Examples
- invoice/basic-v1
- receipt/simple-v1
- ballot/v1
- election-return/v1
- generic/report-v1

Each has: schema.json, template.hbs, styles.css, manifest.json.

---

## Roadmap

**MVP**: JSON→HTML→PDF, invoice & receipt templates, HTTP `/render`, Vue UI.  
**Phase 2**: DOCX export, theming, queue.  
**Phase 3**: Template marketplace, audit trail, diff viewer.

---

## Recommendations
- HTML canonical → PDF/DOC/MD easier
- Prefer pandoc for DOCX fidelity
- Version schema + template together
- Server-rendered preview early to align CSS.
