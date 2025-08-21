<?php

namespace App\Console\Pipelines;

use App\Data\ElectionReturnData;
use App\Models\Precinct;

final class FinalizeErContext
{
    public function __construct(
        public Precinct $precinct,
        public ElectionReturnData|null $er,
        public string $disk,                       // 'election'
        public string $folder,                     // "ER-{$er->code}/final"
        public string $payload,                    // 'minimal'|'full'
        public int $maxChars,                      // 1200
        public bool $force,                        // from --force
        public ?string $qrPersistedAbs = null
    ) {}
}
