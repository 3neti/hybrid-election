<?php

namespace App\Http\Controllers;

use App\Models\ElectionReturn;
use Illuminate\Http\Request;
use Spatie\LaravelData\DataCollection;
use App\Data\ElectionReturnData;
use Spatie\LaravelData\Exceptions\InvalidDataClass;

class ElectionReturnController extends Controller
{
    /**
     * Display a listing of the election returns.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $returns = ElectionReturn::with('precinct')->get();

        $data = $returns->map(fn (ElectionReturn $er) => $er->getData());

        return response()->json(new DataCollection( ElectionReturnData::class, $data->all()));
    }

    /**
     * Display the specified election return.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     * @throws InvalidDataClass
     */
    public function show(string $id)
    {
        $electionReturn = ElectionReturn::with('precinct')->findOrFail($id);

        return response()->json($electionReturn->getData());
    }
}
