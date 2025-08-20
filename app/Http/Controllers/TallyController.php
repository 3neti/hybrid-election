<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

/** @deprecated  */
class TallyController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Tally', [

        ]);
    }
}
