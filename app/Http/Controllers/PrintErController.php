<?php

namespace App\Http\Controllers;

use App\Models\ElectionReturn;
use Illuminate\Http\Response;

class PrintErController
{
    public function __invoke(string $code): Response
    {
        $bare = str_starts_with($code, 'ER-') ? substr($code, 3) : $code;

        /** @var \App\Models\ElectionReturn $er */
        $er = ElectionReturn::with('precinct')->where('code', $bare)->firstOrFail();

        $erJson = json_encode($er->getData()->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        [$jsTag, $cssTag] = $this->assetTags();

        $html = <<<HTML
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Election Return {$er->code}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    @page { size: A4; margin: 12mm; }
    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    html, body, #app { height: 100%; }
  </style>
  {$cssTag}
</head>
<body>
  <div id="app"></div>
  <script>
    // Vue entry will read this as props
    window.__ER_DATA__ = {$erJson};
  </script>
  {$jsTag}
</body>
</html>
HTML;

        return new Response($html, 200, ['Content-Type' => 'text/html; charset=utf-8']);
    }

    /**
     * Resolve tags for the er-print entry from Vite.
     * - In dev: point to the Vite server and client.
     * - In prod: read public/build/manifest.json for hashed filenames.
     *
     * @return array{0: string $jsTag, 1: string $cssTag}
     */
    private function assetTags(): array
    {
        // DEV mode: use Vite server
        if (app()->isLocal() && env('VITE_DEV', false)) {
            $viteUrl = rtrim(env('VITE_DEV_URL', 'http://localhost:5173'), '/');
            $client  = '<script type="module" src="'.$viteUrl.'/@vite/client"></script>';
            $entry   = '<script type="module" src="'.$viteUrl.'/resources/js/er-print.ts"></script>';
            return [$client . "\n  " . $entry, ''];
        }

        // PROD: use manifest
        $manifestPath = public_path('build/manifest.json');
        if (!is_file($manifestPath)) {
            // Helpful message if you forgot to build
            $fallback = '<!-- Missing build/manifest.json. Run: npm run build -->';
            return [$fallback, ''];
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);
        $key = 'resources/js/er-print.ts';
        $item = $manifest[$key] ?? null;

        if (!$item) {
            $fallback = '<!-- Entry '.$key.' not found in manifest. -->';
            return [$fallback, ''];
        }

        $jsSrc  = '/build/'.$item['file'];
        $jsTag  = '<script type="module" src="'.$jsSrc.'"></script>';

        // Optional CSS emitted by Vite
        $cssFiles = $item['css'] ?? [];
        $cssTag   = '';
        if (!empty($cssFiles)) {
            $cssTag = collect($cssFiles)
                ->map(fn($c) => '<link rel="stylesheet" href="/build/'.$c.'">')
                ->implode("\n  ");
        }

        return [$jsTag, $cssTag];
    }
}
