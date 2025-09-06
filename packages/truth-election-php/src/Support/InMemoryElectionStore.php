<?php

namespace TruthElection\Support;

use TruthElection\Data\BallotData;
use TruthElection\Data\ElectoralInspectorData;
use TruthElection\Data\PrecinctData;
use TruthElection\Data\ElectionReturnData;

class InMemoryElectionStore
{
    /** @var array<string, BallotData> */
    public array $ballots = [];

    /** @var array<string, PrecinctData> */
    public array $precincts = [];

    /** @var array<string, ElectionReturnData> */
    public array $electionReturns = [];

    private static ?self $instance = null;

    private function __construct()
    {
        // optionally preload demo data here
    }

    /**
     * Access the singleton instance.
     */
    public static function instance(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * Add or replace a ballot.
     */
    public function putBallot(BallotData $ballot): void
    {
        $this->ballots[$ballot->code] = $ballot;
    }

    /**
     * Add or replace a precinct.
     */
    public function putPrecinct(PrecinctData $precinct): void
    {
        $this->precincts[$precinct->code] = $precinct;
    }

    /**
     * Add or replace an election return.
     */
    public function putElectionReturn(ElectionReturnData $er): void
    {
        $this->electionReturns[$er->precinct->code] = $er;
    }

    /**
     * Retrieve ballots by precinct code.
     *
     * @return BallotData[]
     */
    public function getBallotsForPrecinct(string $precinctCode): array
    {
        return array_filter($this->ballots, fn (BallotData $ballot) =>
            $ballot->precinct->code === $precinctCode
        );
    }

    /**
     * Reset all data (useful for test teardown).
     */
    public function reset(): void
    {
        $this->ballots = [];
        $this->precincts = [];
        $this->electionReturns = [];
    }

    /**
     * Retrieve election return by its unique code.
     */
    public function getElectionReturn(string $code): ?ElectionReturnData
    {
        foreach ($this->electionReturns as $er) {
            if ($er->code === $code) {
                return $er;
            }
        }

        return null;
    }

    function findInspector(ElectionReturnData $er, string $id): ElectoralInspectorData {
        $raw = collect($er->precinct->electoral_inspectors)->firstWhere('id', $id);

        return ElectoralInspectorData::from($raw);
    }

    function findPrecinctInspector(ElectionReturnData $er, string $id): ElectoralInspectorData {
        $raw = collect($er->precinct->electoral_inspectors)->firstWhere('id', $id);

        return ElectoralInspectorData::from($raw);
    }

    function findSignatory(ElectionReturnData $er, string $id): ElectoralInspectorData {
        $raw = collect($er->signatures)->firstWhere('id', $id);

        return ElectoralInspectorData::from($raw);
    }

    public function replaceElectionReturn(ElectionReturnData $er): void
    {
        foreach ($this->electionReturns as $i => $e) {
            if ($e->code === $er->code) {
                $this->electionReturns[$i] = $er;
                return;
            }
        }
    }
}
