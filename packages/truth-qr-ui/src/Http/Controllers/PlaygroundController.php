<?php

namespace TruthQrUi\Http\Controllers;

use Illuminate\Http\Request;

/**
 * PlaygroundController
 *
 * Purpose:
 * --------
 * A thin, opt-in controller that renders a UI "playground" page for
 * trying the encode/decode HTTP endpoints exposed by this package.
 *
 * Important design choice:
 * ------------------------
 * - This package does NOT ship Inertia/Vue or any frontend build tooling.
 * - The *host application* is responsible for installing/configuring
 *   Inertia + Vue 3 (or any other frontend), and for owning the page file.
 *
 * How this controller works:
 * --------------------------
 * - If the host app has Inertia installed, we render the page name
 *   the host app provides (default: 'TruthQrUi/Playground').
 * - If Inertia is not present, we return a 501 with a helpful message
 *   so it fails loudly and predictably (and without coupling).
 *
 * Host-app requirements:
 * ----------------------
 * 1) Install and configure Inertia + Vue in the *host app*, not the package:
 *      composer require inertiajs/inertia-laravel
 *      php artisan inertia:middleware
 *      npm i -D vite
 *      npm i vue @inertiajs/vue3
 *
 * 2) Create the page component in the host app (example path):
 *      resources/js/Pages/TruthQrUi/Playground.vue
 *
 * 3) Register the route in the host app (e.g. in routes/web.php):
 *      use TruthQrUi\Http\Controllers\PlaygroundController;
 *      Route::get('/playground', PlaygroundController::class)
 *           ->name('truthqr.playground');
 *
 * 4) (Optional) Change the page name via config:
 *      // config/truthqr-ui.php
 *      return [
 *          'playground' => [
 *              'inertia_page' => 'TruthQrUi/Playground',
 *          ],
 *      ];
 *
 * 5) (Optional) Disable the playground route entirely in production.
 */
final class PlaygroundController
{
    /**
     * Single-action controller entrypoint.
     *
     * - Requires the host app to have Inertia installed.
     * - Renders the page specified in config('truthqr-ui.playground.inertia_page')
     *   or falls back to 'TruthQrUi/Playground'.
     */
    public function __invoke(Request $request)
    {
        // Fail fast with a helpful message if Inertia isn’t present in the host app.
        if (!class_exists(\Inertia\Inertia::class)) {
            // 501 = Not Implemented - keeps signal clear that frontend isn't wired
            abort(501, 'Inertia is not installed in the host application. '
                . 'Install Inertia/Vue in the host app and add a page at resources/js/Pages/TruthQrUi/Playground.vue '
                . 'or override the page name in config("truthqr-ui.playground.inertia_page").');
        }

        $page = config('truth-qr-ui.playground.inertia_page', 'TruthQrUi/Playground');

        // You can pass any defaults useful for the UI here:
        // e.g., default aliases, example payloads, etc.
        return \Inertia\Inertia::render($page, [
            'defaults' => [
                'envelope'   => 'v1url',
                'transport'  => 'base64url+deflate',
                'serializer' => 'json',
                'example'    => [
                    'type' => 'demo',
                    'code' => 'DEMO-001',
                    'data' => ['hello' => 'world'],
                ],
            ],
            'routes' => [
                'encode' => route('truth-qr.encode'), // ensure your host routes name these
                'decode' => route('truth-qr.decode'),
            ],
        ]);
    }
}
