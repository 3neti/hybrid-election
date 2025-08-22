<?php

namespace App\Http\Controllers;

use Spatie\LaravelData\DataCollection;
use App\Data\ElectionReturnData;
use App\Models\ElectionReturn;
use Illuminate\Http\Request;

/** @deprecated  */
class ElectionReturnController extends Controller
{
    public function _index()
    {
        $returns = ElectionReturn::with('precinct')->get();

        $data = $returns->map(fn (ElectionReturn $er) => $er->getData());

        return response()->json(new DataCollection( ElectionReturnData::class, $data->all()));
    }

    public function _show(string $id)
    {
        $electionReturn = ElectionReturn::with('precinct')->findOrFail($id);

        return response()->json($electionReturn->getData());
    }
}
