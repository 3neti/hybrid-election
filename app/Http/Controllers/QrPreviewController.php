<?php

namespace App\Http\Controllers;

use App\Actions\GenerateQrForJson;
use App\Models\ElectionReturn;

class QrPreviewController extends Controller
{
    public function show(string $code)
    {
        $er = ElectionReturn::where('code', $code)->firstOrFail();

        // keep payload minimal (id, code, precinct, tallies)
        $payload = [
            'id'       => $er->id,
            'code'     => $er->code,
            'precinct' => [
                'id'   => $er->precinct->id,
                'code' => $er->precinct->code,
            ],
            'tallies'  => $er->getData()->tallies->toArray(), // already sorted
        ];

        $result = app(GenerateQrForJson::class)->handle(
            payload: $payload,
            code: $er->code,
            makeImages: true,     // 👈 include PNG data URIs
            maxCharsPerQr: 800    // tweak if you want fewer/more chunks
        );

        return response()->json($result);
    }
}
