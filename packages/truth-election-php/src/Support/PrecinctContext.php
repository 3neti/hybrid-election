<?php

namespace TruthElection\Support;

use TruthElection\Data\BallotData;
use TruthElection\Data\ElectionReturnData;
use TruthElection\Data\ElectoralInspectorData;
use Spatie\LaravelData\DataCollection;
use TruthElection\Data\PrecinctData;

class PrecinctContext
{
    protected ?PrecinctData $precinct = null;

    public function __construct(
        protected ElectionStoreInterface $store,
        ?string $precinctCode = null,
    ) {
        // Automatically resolve the precinct from the store
        $this->precinct = $store->getPrecinct($precinctCode);
    }

    public function getPrecinct(): ?PrecinctData
    {
        return $this->precinct;
    }

    public function updatePrecinct(PrecinctData $precinct): void
    {
        $this->store->putPrecinct($precinct);
    }

    public function getBallots(): DataCollection
    {
        return $this->store->getBallots($this->getPrecinct()->code);
    }

    public function putBallot(BallotData $ballot): void
    {
        $this->store->putBallot($ballot, $this->getPrecinct()->code);
    }

    public function updateElectionReturn(ElectionReturnData $er): void
    {
        $this->store->putElectionReturn($er);
    }

    public function getInspectors(): DataCollection
    {
        return $this->store->getInspectorsForPrecinct($this->getPrecinct()->code);
    }

    public function code(): ?string
    {
        return $this->precinct?->code;
    }

    public function location(): ?string
    {
        return $this->precinct?->location_name;
    }

    public function latitude(): ?float
    {
        return $this->precinct?->latitude;
    }

    public function longitude(): ?float
    {
        return $this->precinct?->longitude;
    }

    public function inspectors(): DataCollection
    {
        $inspectors = $this->precinct?->electoral_inspectors ?? [];

        return new DataCollection(ElectoralInspectorData::class, $inspectors);
    }

    public function chairperson(): ?ElectoralInspectorData
    {
        return $this->inspectors()->toCollection()->firstWhere('role', 'chairperson');
    }

    public function members(): DataCollection
    {
        return new DataCollection(
            ElectoralInspectorData::class,
            $this->inspectors()->toCollection()->filter(fn ($i) => $i->role->value === 'member')->all()
        );
    }
}
