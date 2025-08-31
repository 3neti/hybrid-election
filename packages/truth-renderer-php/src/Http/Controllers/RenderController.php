<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use TruthRenderer\Contracts\RendererInterface;
use TruthRenderer\DTO\RenderRequest;

class RenderController extends Controller
{
    public function pdf(Request $req, RendererInterface $renderer)
    {
        $r = new RenderRequest(
            template: view('templates.invoice')->render(), // or load from storage
            data:     $req->input('data', []),
            schema:   null, // or load a JSON schema
            partials: [],   // or ['header' => '...', 'footer' => '...']
            engineFlags: [],
            format: 'pdf',
            paperSize: 'A4',
            orientation: 'portrait',
            assetsBaseUrl: public_path(''), // helps Dompdf resolve /css,/img
        );

        $result = $renderer->render($r);

        return new StreamedResponse(function () use ($result) {
            echo $result->content;
        }, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"',
        ]);
    }
}
