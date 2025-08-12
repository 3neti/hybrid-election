<?php

namespace App\Actions;

use App\Models\ElectionReturn;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Builder\Builder;

use Endroid\QrCode\RoundBlockSizeMode;


class GenerateQrForJson
{
    use AsAction;

    /**
     * @param  array|string|UploadedFile  $json
     * @param  string  $code  Logical code for this payload (e.g., ER code)
     * @param  bool  $makeImages  If true, returns PNG data URIs per chunk
     * @param  int   $maxCharsPerQr  Safe text length per QR
     * @return array{
     *   code:string,
     *   version:string,
     *   total:int,
     *   chunks: array<int, array{index:int,text:string,png?:string}>
     * }
     */
    public function handle(
        array|string|UploadedFile $json,
        string $code,
        bool $makeImages = true,
        int $maxCharsPerQr = 3200,
        bool $forceSingle = false   // 👈 add this
    ): array {
        // 1) Load JSON
        if ($json instanceof UploadedFile) {
            $raw = file_get_contents($json->getRealPath());
        } elseif (is_array($json)) {
            $raw = json_encode($json, JSON_UNESCAPED_SLASHES);
        } else {
            $raw = (string) $json;
        }

        // 2) Compress + Base64URL
        $deflated = gzdeflate($raw, 9);
        $b64u = $this->b64urlEncode($deflated);

        // 3) Chunk (respect forceSingle)
        $parts = $forceSingle ? [$b64u] : str_split($b64u, $maxCharsPerQr);
        $total = max(1, count($parts));

        $chunks = [];
        foreach ($parts as $i => $payload) {
            $index = $i + 1;
            $text  = $this->wrapChunk($payload, $index, $total, $code);

            $chunk = ['index' => $index, 'text' => $text];

            if ($makeImages) {
                $chunk['png'] = $this->qrPngDataUri($text);
            }

            $chunks[] = $chunk;
        }

        return [
            'code'    => $code,
            'version' => 'v1',
            'total'   => $total,
            'chunks'  => $chunks,
        ];
    }

    /**
     * API endpoint: build QR chunks for a given Election Return code.
     *
     * GET /api/qr/election-return/{code}?make_images=1&max_chars_per_qr=800
     */
    public function asController(ActionRequest $request, string $code)
    {
        $er = \App\Models\ElectionReturn::with('precinct')->where('code', $code)->first();
        if (! $er) {
            abort(404, 'Election return not found.');
        }

        /** @var \App\Data\ElectionReturnData $dto */
        $dto = app(\App\Actions\GenerateElectionReturn::class)->run($er->precinct);
        $json = json_decode($dto->toJson(), true);

        $makeImages   = $request->boolean('make_images', true);
        $maxCharsPer  = (int) $request->input('max_chars_per_qr', 1200);
        $forceSingle  = $request->boolean('single', false); // 👈 read it

        $result = $this->handle(
            json: $json,
            code: $dto->code,
            makeImages: $makeImages,
            maxCharsPerQr: $maxCharsPer,
            forceSingle: $forceSingle       // 👈 pass it
        );

        return response()->json($result);
    }

    // ---- helpers ---------------------------------------------------------

    private function wrapChunk(string $payload, int $index, int $total, string $code): string
    {
        // Format: ER|v1|<CODE>|<index>/<total>|<payload>
        return sprintf('ER|v1|%s|%d/%d|%s', $code, $index, $total, $payload);
    }

    function qrPngDataUri(string $contents): string
    {
        $qr = new QrCode(
            data: $contents,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 512,
            margin: 8,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $writer = new PngWriter();
        $result = $writer->write($qr);

        return $result->getDataUri();
    }

    private function b64urlEncode(string $bin): string
    {
        return rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');
    }

    // For tests / decoding later:
    public static function b64urlDecode(string $txt): string
    {
        $pad = strlen($txt) % 4;
        if ($pad) $txt .= str_repeat('=', 4 - $pad);
        return base64_decode(strtr($txt, '-_', '+/')) ?: '';
    }
}
