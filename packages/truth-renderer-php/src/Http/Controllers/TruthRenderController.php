<?php

namespace TruthRenderer\Http\Controllers;

use TruthRenderer\Contracts\TemplateRegistryInterface;
use TruthRenderer\Contracts\RendererInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use TruthRenderer\DTO\RenderRequest;
use Illuminate\Routing\Controller;

/**
 * Controller for exposing template listing and rendering endpoints.
 *
 * Provides two endpoints:
 *  - listTemplates(): Returns available templates from the registry.
 *  - render(): Accepts a render request (via JSON payload) and streams HTML, PDF, or Markdown.
 */
class TruthRenderController extends Controller
{
    /**
     * @param TemplateRegistryInterface $registry Template registry for resolving templates by name.
     * @param RendererInterface         $renderer Core rendering service (Handlebars + Dompdf).
     */
    public function __construct(
        private readonly TemplateRegistryInterface $registry,
        private readonly RendererInterface $renderer,
    ) {}

    /**
     * List all available templates from the registry.
     *
     * @return Response JSON response with `templates` key containing an array of template names.
     */
    public function listTemplates(): Response
    {
        return response([
            'templates' => $this->registry->list(),
        ]);
    }

    /**
     * Render a template to the requested format.
     *
     * Input can provide either:
     *  - `templateName`: name of a registered template (e.g. "core:hello")
     *  - `template`: raw template source string
     *
     * Other supported inputs:
     *  - `data` (array|object): Render context data
     *  - `schema` (array|object, optional): JSON Schema for validation
     *  - `partials` (array<string,string>, optional): Handlebars partials
     *  - `engineFlags` (array, optional): LightnCandy compile/runtime options
     *  - `format` ("pdf"|"html"|"md"): Output format (default: pdf)
     *  - `paperSize` (string, optional): e.g. "A4", "Letter" (PDF only)
     *  - `orientation` ("portrait"|"landscape", optional): PDF page orientation
     *  - `assetsBaseUrl` (string, optional): Base path for Dompdf to resolve relative assets
     *  - `filename` (string, optional): Output filename (for PDF disposition header)
     *
     * @param Request $req
     * @return Response Response with the rendered document in the requested format.
     *
     * @throws \RuntimeException if template resolution or rendering fails.
     */
    public function render(Request $req): Response
    {
        // Resolve template from name or inline
        $templateName = $req->has('templateName')
            ? $req->string('templateName')->toString()
            : null;

        $template = $req->has('template')
            ? $req->string('template')->toString()
            : null;

        if ($templateName && !$template) {
            $template = $this->registry->get($templateName);
        }

        if (!$template) {
            return response(['error' => 'Missing template or templateName'], 422);
        }

        // Collect render parameters
        $data        = $req->input('data', []);
        $schema      = $req->input('schema');
        $partials    = $req->input('partials', []);
        $engineFlags = $req->input('engineFlags', []);
        $format      = (string) $req->string('format', 'pdf');
        $paper       = (string) $req->string('paperSize', 'A4');
        $orient      = (string) $req->string('orientation', 'portrait');

        $assetsBase = $req->has('assetsBaseUrl')
            ? $req->string('assetsBaseUrl')->toString()
            : null;

        $filename = $req->has('filename')
            ? $req->string('filename')->toString()
            : 'render';

        // Build render request DTO
        $renderReq = new RenderRequest(
            template:      (string) $template,
            data:          is_array($data) || is_object($data) ? $data : [],
            schema:        is_array($schema) || is_object($schema) ? $schema : null,
            partials:      is_array($partials) ? $partials : [],
            engineFlags:   is_array($engineFlags) ? $engineFlags : [],
            format:        $format,
            paperSize:     $paper,
            orientation:   $orient,
            assetsBaseUrl: $assetsBase,
        );

        $res = $this->renderer->render($renderReq);

        // Stream with proper content type & disposition
        return match ($res->format) {
            'pdf'  => response($res->content, 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => "inline; filename=\"{$filename}.pdf\"",
            ]),
            'html' => response($res->content, 200, [
                'Content-Type' => 'text/html; charset=UTF-8',
            ]),
            'md'   => response($res->content, 200, [
                'Content-Type' => 'text/markdown; charset=UTF-8',
            ]),
            default => response(['error' => 'Unsupported format'], 415),
        };
    }
}
