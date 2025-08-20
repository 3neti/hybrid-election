<?php

namespace App\Console\Pipelines;

use App\Models\Precinct;
use Spatie\LaravelData\Data;

final class FinalizeErContext
{
    public function __construct(
        public Precinct $precinct,
        /** @var Data $er */ public $er,           // ElectionReturnData DTO
        public string $disk,                       // 'election'
        public string $folder,                     // "ER-{$er->code}/final"
        public string $payload,                    // 'minimal'|'full'
        public int $maxChars,                      // 1200
        public bool $force,                        // from --force
        public ?string $qrPersistedAbs = null
    ) {}
}
