<?php

namespace App\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Lorisleiva\Actions\Concerns\AsAction;

class GetSampleERjson
{
    use AsAction;

    /**
     * Handle core logic: load the sample JSON from docs_path.
     */
    public function handle(): string
    {
        $file = docs_path('sample-er.json');

        if (!File::exists($file)) {
            abort(404, 'Sample Election Return JSON not found.');
        }

        return File::get($file);
    }

    /**
     * Controller method to return the JSON in a HTTP response.
     */
    public function asController(Request $request)
    {
        $contents = $this->handle();

        return Response::make($contents, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
