<?php

namespace TruthQrUi\Http\Controllers;

use Illuminate\Routing\Controller;

final class PlaygroundController extends Controller
{
    public function show()
    {
        return view('truth-qr-ui::playground');
    }
}
