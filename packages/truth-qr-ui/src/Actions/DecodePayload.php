<?php

namespace TruthQrUi\Actions;

use TruthQr\Assembly\TruthAssembler;
use TruthQr\Classify\Classify;

final class DecodePayload
{
    /**
     * @param string[] $lines Full QR texts (ER|v1|CODE|i/N|payload)
     * @param array{persist?:bool,persistDir?:string} $opts
     * @return array<string,mixed>
     * @throws \Throwable
     */
    public function run(array $lines, array $opts = []): array
    {
        /** @var TruthAssembler $asm */
        $asm = app(TruthAssembler::class);

        $classify = new Classify($asm);
        $sess = $classify->newSession();

        foreach ($lines as $line) {
            $sess->addLine($line);
        }

        $status = $sess->status();
        $isComplete = $sess->isComplete();

        $out = [
            'status'   => $status,
            'complete' => $isComplete,
            'json'     => null,
        ];

        if ($isComplete) {
            $out['json'] = $sess->assemble();
        }

        // Optional persistence via your host app (left to you)
        // You can easily add a small Storage helper here if desired.

        return $out;
    }
}
