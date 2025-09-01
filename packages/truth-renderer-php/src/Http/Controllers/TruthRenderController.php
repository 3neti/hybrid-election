<?php

namespace TruthRenderer\Http\Controllers;

use TruthRenderer\Contracts\TemplateRegistryInterface;
use TruthRenderer\Template\TemplateAssetsLoader;
use TruthRenderer\Contracts\RendererInterface;
use Illuminate\Http\{Request, Response};
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

        // Will be filled if templateName is provided
        $autoPartials = [];
        $autoSchema = null;
        $assetsBaseFromTemplateDir = null;

        if ($templateName && !$template) {
            // Get template source
            $template = $this->registry->get($templateName);

            // Find the directory that contains this template
            $tplDir = $this->registry->resolveDir($templateName);
            if ($tplDir && is_dir($tplDir)) {
                // 1) Auto-load partials: {tplDir}/partials/*.hbs|*.html
                $partialsDir = $tplDir . DIRECTORY_SEPARATOR . 'partials';
                if (is_dir($partialsDir)) {
                    $dh = opendir($partialsDir);
                    if ($dh) {
                        while (($f = readdir($dh)) !== false) {
                            if ($f === '.' || $f === '..') continue;
                            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                            if ($ext !== 'hbs' && $ext !== 'html') continue;

                            $abs = $partialsDir . DIRECTORY_SEPARATOR . $f;
                            $src = file_get_contents($abs);
                            if ($src !== false) {
                                // partial name is filename (no extension), e.g. "itemRow"
                                $name = pathinfo($f, PATHINFO_FILENAME);
                                $autoPartials[$name] = $src;
                            }
                        }
                        closedir($dh);
                    }
                }

                // 2) Auto-load schema: {tplDir}/schema.json (if present)
                $schemaFile = $tplDir . DIRECTORY_SEPARATOR . 'schema.json';
                if (is_file($schemaFile)) {
                    $json = file_get_contents($schemaFile);
                    if ($json !== false) {
                        $decoded = json_decode($json, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $autoSchema = $decoded;
                        }
                    }
                }

                // 3) Nice default for Dompdf assets: allow relative CSS/images next to template
                $assetsBaseFromTemplateDir = $tplDir;
            }
        }

        if (!$template) {
            return response(['error' => 'Missing template or templateName'], 422);
        }

        // Collect render parameters from request (manual overrides still allowed)
        $data        = $req->input('data', []);
        $schema      = $req->input('schema');          // optional override
        $partials    = $req->input('partials', []);    // optional override/extra
        $engineFlags = $req->input('engineFlags', []);
        $format      = (string) $req->string('format', 'pdf');
        $paper       = (string) $req->string('paperSize', 'A4');
        $orient      = (string) $req->string('orientation', 'portrait');

        $assetsBase = $req->has('assetsBaseUrl')
            ? $req->string('assetsBaseUrl')->toString()
            : $assetsBaseFromTemplateDir; // default to template directory if not provided

        $filename = $req->has('filename')
            ? $req->string('filename')->toString()
            : 'render';

        // Merge auto-discovered with explicitly provided (explicit wins on same key)
        $partialsFinal = array_merge($autoPartials, is_array($partials) ? $partials : []);
        $schemaFinal   = (is_array($schema) || is_object($schema)) ? $schema : $autoSchema;

        // Build render request DTO
        $renderReq = new RenderRequest(
            template:      (string) $template,
            data:          is_array($data) || is_object($data) ? $data : [],
            schema:        $schemaFinal,
            partials:      $partialsFinal,
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
