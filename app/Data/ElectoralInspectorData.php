<?php

namespace App\Data;

use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\{Data, Optional};
use Spatie\LaravelData\Casts\EnumCast;
use App\Enums\ElectoralInspectorRole;
use Carbon\Carbon;

class ElectoralInspectorData extends Data
{
    public function __construct(
        public string $id,                        // id of member
        public string $name,                      // e.g. 'Juan Dela Cruz'
        #[WithCast(EnumCast::class, ElectoralInspectorRole::class)]
        public ElectoralInspectorRole $role,      // e.g. 'chairperson', 'member'
        public string|Optional $signature = new Optional(),
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:sP')]
        public Carbon|Optional $signed_at = new Optional()
    ) {}
}
