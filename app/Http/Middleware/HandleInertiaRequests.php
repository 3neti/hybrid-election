<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Tighten\Ziggy\Ziggy;
use App\Models\Precinct;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        $precinct = Cache::remember(
            'shared.precinct',
            now()->addMinutes(10), // or rememberForever()
            function () {
                $p = Precinct::query()->first();
                // If you use Spatie Data: $p->getData()->toArray()
                // If not, fallback to arrayable Eloquent:
                return method_exists($p, 'getData')
                    ? $p->getData()->toArray()
                    : $p?->toArray();
            }
        );

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'precinct' => $precinct
        ];
    }
}
