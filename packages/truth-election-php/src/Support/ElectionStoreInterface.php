<?php

namespace TruthElection\Support;

use TruthElection\Data\ElectoralInspectorData;
use TruthElection\Data\ElectionReturnData;
use TruthElection\Data\CandidateData;
use TruthElection\Data\PositionData;
use TruthElection\Data\PrecinctData;
use TruthElection\Data\BallotData;

interface ElectionStoreInterface
{
    public function getBallotsForPrecinct(string $precinctCode): array;

    public function putBallot(BallotData $ballot): void;

    public function getPrecinct(string $code): ?PrecinctData;

    public function putPrecinct(PrecinctData $precinct): void;

    public function putElectionReturn(ElectionReturnData $er): void;

    public function getElectionReturn(string $code): ?ElectionReturnData;

    public function getElectionReturnByPrecinct(string $precinctCode): ?ElectionReturnData;

    public function replaceElectionReturn(ElectionReturnData $er): void;

    public function load(array $positions, PrecinctData $precinct): void;

    public function setPositions(array $positionMap): void;

    public function getPosition(string $code): ?PositionData;

    public function setCandidates(array $candidateMap): void;

    public function getCandidate(string $code): ?CandidateData;

    public function allPositions(): array;

    public function allCandidates(): array;

    public function findInspector(ElectionReturnData $er, string $id): ElectoralInspectorData;

    public function findPrecinctInspector(ElectionReturnData $er, string $id): ElectoralInspectorData;

    public function findSignatory(ElectionReturnData $er, string $id): ElectoralInspectorData;

    public function reset(): void;
}
