<?php

namespace App\Data;

use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use App\Enums\ElectoralInspectorRole;
use Spatie\LaravelData\Data;
use Carbon\Carbon;

class ElectoralInspectorData extends Data
{
    public function __construct(
        public string $id,                        // id of member
        public string $name,                      // e.g. 'Juan Dela Cruz'
        #[WithCast(EnumCast::class, ElectoralInspectorRole::class)]
        public ElectoralInspectorRole $role,      // e.g. 'chairperson', 'member'
        public ?string $signature = null,         // base64-encoded image or file path
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:sP')]
        public ?Carbon $signed_at = null,         // timestamp of when the inspector signed
    ) {}
}
