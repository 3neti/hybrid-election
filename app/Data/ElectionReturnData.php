<?php

namespace App\Data;

use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

/**
 * ElectionReturnData represents a full, persisted election return record,
 * including the election tallies and the electoral board's digital signatures.
 */
class ElectionReturnData extends Data
{
    public function __construct(
        public string $id,

        /** Unique code identifying this election return */
        public string $code,

        /** The associated precinct */
        public PrecinctData $precinct,

        /** @var DataCollection<VoteCountData> The sorted vote counts per candidate */
        public DataCollection $tallies,

        /** @var DataCollection<ElectoralInspectorData> Digital signatures from the electoral board */
        public DataCollection $signatures,

        /** @var DataCollection<BallotData> The ballots casted by voters  */
        public DataCollection $ballots,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:sP')]
        public Carbon $created_at,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:sP')]
        public Carbon $updated_at,
    ) {}

    public function with(): array
    {
        $last = $this->ballots->toCollection()->last();

        return [
            'last_ballot' => $last ? \App\Data\BallotData::from($last) : null,
        ];
    }
}
