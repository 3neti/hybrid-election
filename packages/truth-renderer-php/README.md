# Truth Renderer (PHP)

A small, framework-agnostic PHP library to **render structured data (JSON/YAML) into HTML or PDF** using Handlebars templates, with optional **JSON Schema validation** and a clean extension point for helpers/partials.

> Pair it with your TRUTH QR flow: decode data → validate → render to HTML/PDF.

---

## Features

- ✅ Handlebars templating via **zordius/lightncandy**
- ✅ JSON Schema validation via **justinrainbow/json-schema**
- ✅ PDF output via **dompdf/dompdf**
- ✅ Pluggable helpers & partials
- ✅ Strongly-typed DTOs (`RenderRequest`, `RenderResult`)
- ✅ `RendererInterface` with `render()` and `renderToFile()` convenience

---

## Requirements

- PHP **8.2+**
- Extensions: `ext-json`
- Composer dependencies:
    - `zordius/lightncandy:^1.2`
    - `justinrainbow/json-schema:^6.5`
    - `dompdf/dompdf:^3.1`
    - `symfony/yaml:^7.3` (if you parse YAML upstream)

---

## Installation

```bash
composer require lbhurtado/truth-renderer-php
```

> If you’re developing locally, add the package path in your root `composer.json` `"repositories"` as a path repo and run `composer require` with `--prefer-source`.

---

## Quickstart

```php
use TruthRenderer\Renderer;
use TruthRenderer\DTO\RenderRequest;

$renderer = new Renderer();

$template = <<<HBS
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{title}}</title>
  </head>
  <body>
    <h1>{{upper title}}</h1>
    <p>Amount: {{currency amount}}</p>
    <p>Date: {{date created_at "Y/m/d"}}</p>
  </body>
</html>
HBS;

$data = [
  'title'      => 'Invoice #123',
  'amount'     => 1234.5,
  'created_at' => '2024-12-25T10:30:00Z',
];

$request = new RenderRequest(
    template: $template,
    data: $data,
    format: 'pdf',                 // 'html' | 'pdf' | 'md'
    schema: null,                  // optional JSON Schema (array|object)
    partials: [],                  // ['name' => '{{> partial }}']
    paperSize: 'A4',               // A4|Letter|...
    orientation: 'portrait',       // portrait|landscape
    assetsBaseUrl: __DIR__,        // base path for Dompdf to resolve images/CSS
    engineFlags: []                // LightnCandy compile/runtime options
);

$result = $renderer->render($request);
// $result->format === 'pdf'
// $result->content is the binary PDF bytes
file_put_contents(__DIR__ . '/invoice.pdf', $result->content);
```

---

## DTOs

### `RenderRequest`
| Field | Type | Required | Description |
|------|------|----------|-------------|
| `template` | `string` | ✅ | Handlebars template source |
| `data` | `array|object` | ✅ | Structured payload (will be normalized to array for rendering) |
| `format` | `string` | ✅ | One of: `html`, `pdf`, `md` |
| `schema` | `array|object|null` | ❌ | JSON Schema to validate `data` (noop if `null`) |
| `partials` | `array<string,string>` | ❌ | Named partials: `['row' => '{{name}}']` |
| `paperSize` | `string|null` | ❌ | e.g., `A4`, `Letter` (PDF only) |
| `orientation` | `string|null` | ❌ | `portrait` (default) or `landscape` (PDF only) |
| `assetsBaseUrl` | `string|null` | ❌ | Base path/URL for **Dompdf** asset resolution |
| `engineFlags` | `array<string,mixed>` | ❌ | Extra LightnCandy options incl. custom helpers |

### `RenderResult`
| Field | Type | Description |
|------|------|-------------|
| `format` | `string` | `html` | `pdf` | `md` |
| `content` | `string` | Rendered content (binary for PDF) |

---

## Interfaces & Core

### `RendererInterface`
```php
interface RendererInterface {
    public function render(RenderRequest $request): RenderResult;
    public function renderToFile(RenderRequest $request, string $path): RenderResult;
}
```

### `Renderer`
- Validates data with `Validation\Validator` (justinrainbow/json-schema).
- Renders HTML via `Engine\HandlebarsEngine`.
- Converts:
    - to **PDF** via Dompdf (respects `paperSize`, `orientation`, `assetsBaseUrl`);
    - to **MD** via a minimal HTML→Markdown fallback (swap in a richer converter in adapters).

---

## Handlebars Engine

### Built-in Helpers
Provided by `TruthRenderer\Engine\HbsHelpers`:

- `{{upper value}}`
- `{{lower value}}`
- `{{currency value}}` → `$1,234.50`
- `{{date value "Y-m-d"}}`
- `{{multiply a b}}`

### Custom Helpers
You can pass additional helpers per request via `engineFlags['helpers']`. For **LightnCandy**, helpers must be **closures** or **`"Class::method"` strings** (array-callables are not supported by its exporter).

```php
$request = new RenderRequest(
  template: 'Custom: {{triple qty}}',
  data: ['qty' => 7],
  format: 'html',
  schema: null,
  partials: [],
  paperSize: null,
  orientation: null,
  assetsBaseUrl: null,
  engineFlags: [
    'helpers' => [
      'triple' => static fn($v) => (string)((int)$v * 3),
      // OR as a FQCN string:
      // 'triple' => My\Hbs\Helpers::class . '::triple',
    ]
  ]
);
```

### Partials
```php
$request = new RenderRequest(
  template: '<ul>{{#each items}}{{> li}}{{/each}}</ul>',
  data: ['items' => [['name' => 'A'], ['name' => 'B']]],
  format: 'html',
  schema: null,
  partials: [
    'li' => '<li>{{name}}</li>',
  ],
  engineFlags: []
);
```

---

## Validation

Use **JSON Schema** to ensure your data matches the expected shape.

```php
$schema = [
  '$schema'   => 'http://json-schema.org/draft-07/schema#',
  'type'      => 'object',
  'required'  => ['title', 'amount'],
  'properties'=> [
    'title'  => ['type' => 'string'],
    'amount' => ['type' => 'number', 'minimum' => 0],
  ],
];

$request = new RenderRequest(
  template: 'Amount: {{amount}}',
  data: ['title' => 'OK', 'amount' => 10],
  format: 'html',
  schema: $schema,
);
```

When validation fails, a `RuntimeException` is thrown with a normalized error message:
```
Schema validation failed:
 - amount: The property amount is required
```

---

## PDF Notes

- The library sets sensible default Dompdf options:
    - `isRemoteEnabled = true`
    - `isHtml5ParserEnabled = true`
    - `defaultFont = "DejaVu Sans"`
- `assetsBaseUrl` informs Dompdf where to resolve relative paths for images/CSS.
- For production quality typography/formatting, consider:
    - Dedicated templates for PDF (avoid heavy JS; inline or link external CSS)
    - Keep images in accessible paths or embed as data URIs

---

## Extending

- Add new helpers by subclassing `HbsHelpers` or providing additional helper map entries at call time.
- Replace Markdown conversion by adapting `Renderer::htmlToMarkdown()` or decorating `Renderer`.

---

## Testing

Example Pest snippets you can adapt:

```php
it('renders simple variables', function () {
    $engine = new TruthRenderer\Engine\HandlebarsEngine();
    $tpl  = 'Hello, {{name}}!';
    $html = $engine->render($tpl, ['name' => 'World']);
    expect(trim($html))->toBe('Hello, World!');
});
```

```php
it('validates schema', function () {
    $renderer = new TruthRenderer\Renderer();
    $schema = [
        '$schema' => 'http://json-schema.org/draft-07/schema#',
        'type'    => 'object',
        'required'=> ['code', 'amount'],
        'properties'=> [
            'code'   => ['type' => 'string'],
            'amount' => ['type' => 'number'],
        ]
    ];

    $request = new TruthRenderer\DTO\RenderRequest(
        template: 'x',
        data: ['code' => 'OK'],
        format: 'html',
        schema: $schema
    );

    expect(fn () => $renderer->render($request))->toThrow(\RuntimeException::class);
});
```

---

## Framework Adapters (Optional)

Keep the core small. Build thin adapters for:
- **Laravel/Symfony** HTTP streaming of PDFs
- **Storage** (S3/local) integrations for `renderToFile`
- **Template registries** (DB, filesystem, Git-backed)

---

## Roadmap

- DOCX (PHPWord) adapter
- Rich HTML→MD adapter (League)
- Advanced page layout helpers for PDF (headers/footers/page numbers)
- Template pack(s): ballots, ERs, invoices, receipts

---

## License

MIT © 2025
