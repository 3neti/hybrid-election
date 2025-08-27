<?php

namespace TruthQrUi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use TruthQrUi\Actions\DecodePayload;

final class DecodeController extends Controller
{
    public function show()
    {
        return view('truth-qr-ui::decode');
    }

    public function decode(Request $request)
    {
        // Accept a textarea with lines, one per QR content
        $linesText = (string) $request->input('lines', '');
        $lines = array_values(array_filter(
            array_map('trim', preg_split('/\R/u', $linesText) ?: []),
            fn ($x) => $x !== ''
        ));

        $result = app(DecodePayload::class)->run($lines, [
            'persist'    => (bool) $request->boolean('persist', false),
            'persistDir' => (string) $request->input('persist_dir', ''),
        ]);

        return view('truth-qr-ui::decode', [
            'input'  => compact('linesText'),
            'result' => $result,
        ]);
    }
}
