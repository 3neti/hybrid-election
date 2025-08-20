<?php

namespace App\Services\Pdf;

use App\Contracts\ElectionReturnPdfRenderer;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class ReportLabErPdfRenderer implements ElectionReturnPdfRenderer
{
    public function __construct(private array $cfg) {}

    public function render(array $erJson, string $destAbs, array $opts = []): void
    {
        $tmpJson = storage_path('app/tmp/er_'.Str::random(6).'.json');
        @mkdir(dirname($tmpJson), 0777, true);
        file_put_contents($tmpJson, json_encode($erJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));

        @mkdir(dirname($destAbs), 0777, true);

        // pick python (venv wins if configured)
        $python = $this->cfg['reportlab']['venv']
            ?: ($this->cfg['reportlab']['python'] ?? '/usr/bin/python3');
        $script = $this->cfg['reportlab']['script'];

        $p = new Process([$python, $script, '--input', $tmpJson, '--output', $destAbs], null, null, null, (int)($this->cfg['timeout'] ?? 45));
        $p->run();

        @unlink($tmpJson);

        if (!$p->isSuccessful() || !file_exists($destAbs)) {
            throw new \RuntimeException('ReportLab failed: '.$p->getErrorOutput());
        }
    }
}
