<?php

namespace TruthElectionDb\Support;


use TruthElection\Data\{BallotData, CandidateData, ElectionReturnData, ElectoralInspectorData, PositionData, PrecinctData, VoteData};
use TruthElectionDb\Models\{Ballot, Candidate, ElectionReturn, Position, Precinct};
use TruthElection\Support\ElectionStoreInterface;
use Illuminate\Validation\ValidationException;
use Spatie\LaravelData\DataCollection;

class DatabaseElectionStore implements ElectionStoreInterface
{
    /** @var array<string, PositionData> */
    protected array $positions = [];

    /** @var array<string, CandidateData> */
    protected array $candidates = [];

    public function getBallotsForPrecinct(string $precinctCode): array
    {
        $precinct = Precinct::whereCode($precinctCode)->first();

        return $precinct
            ? $precinct->ballots
            : [];
    }

    public function getBallots(string $precinctCode): DataCollection
    {
        $precinct = Precinct::whereCode($precinctCode)->first();

        if (! $precinct) {
            return new DataCollection(BallotData::class, []);
        }

        return new DataCollection(BallotData::class, $precinct->ballots);
    }

    public function putBallot(BallotData $ballot, string $precinctCode): void
    {
        $precinct = Precinct::whereCode($precinctCode)->first();

        if (!$precinct) {
            throw new \RuntimeException("Precinct [$precinctCode] not found.");
        }

        // Check for existing ballot with same code
//        if (Ballot::query()->where('code', $ballot->code)->exists()) {
//            throw new \RuntimeException("Duplicate ballot code [{$ballot->code}] for precinct [$precinctCode].");
//        }
        if (Ballot::query()->where('code', $ballot->code)->exists()) {
            throw ValidationException::withMessages([
                'ballot_code' => "Duplicate ballot code [{$ballot->code}] for precinct [{$precinctCode}].",
            ]);
        }

        $ballot->setPrecinctCode($precinctCode);
        Ballot::fromData($ballot);
    }

//    public function putBallot(BallotData $ballot, string $precinctCode): void
//    {
//        $ballot->setPrecinctCode($precinctCode);
//        Ballot::fromData($ballot);
//    }

    public function getPrecinct(string $code): ?PrecinctData
    {
        return Precinct::whereCode($code)->first()?->getData();
    }

    public function putPrecinct(PrecinctData $precinct): void
    {
        Precinct::fromData($precinct);
    }

    public function putElectionReturn(ElectionReturnData $er): void
    {
        ElectionReturn::fromData($er);
    }

    public function getElectionReturn(string $code): ?ElectionReturnData
    {
        return ElectionReturn::whereCode($code)->first()?->getData();
    }

    public function getElectionReturnByPrecinct(string $precinctCode): ?ElectionReturnData
    {
        return ElectionReturn::get()
            ->first(fn ($er) =>
                $er->belongsTo(Precinct::class, 'precinct_code', 'code')->getResults()?->code === $precinctCode
            )?->getData();
    }

    public function replaceElectionReturn(ElectionReturnData $er): void
    {
        $this->putElectionReturn($er);
    }

    public function load(array $positions, PrecinctData $precinct): void
    {
        Precinct::fromData($precinct);

        foreach ($positions as $posArr) {
            $position = PositionData::from($posArr);
            $this->positions[$position->code] = $position;

            $ballot = new BallotData(
                code: $position->code,
                votes: new DataCollection(VoteData::class, []) // ✅ empty collection
            );
            $ballot->setPrecinctCode($precinct->code);
            Ballot::fromData($ballot);
        }
    }

    public function setPositions(array $positionMap): void
    {
        $this->positions = $positionMap;

        foreach ($positionMap as $position) {
            Position::fromData($position);
        }
    }

    public function getPosition(string $code): ?PositionData
    {
        return $this->positions[$code] ?? null;
    }

    public function setCandidates(array $candidateMap): void
    {
        $this->candidates = $candidateMap;

        foreach ($candidateMap as $candidate) {
           Candidate::fromData($candidate);
        }
    }

    public function getCandidate(string $code): ?CandidateData
    {
        return $this->candidates[$code] ?? null;
    }

    public function allPositions(): array
    {
        return $this->positions;
    }

    public function allCandidates(): array
    {
        return $this->candidates;
    }

    public function findInspector(ElectionReturnData $er, string $id): ?ElectoralInspectorData
    {
        return $er->precinct->electoral_inspectors->toCollection()
            ->firstWhere('id', $id);
    }

    public function findPrecinctInspector(ElectionReturnData $er, string $id): ?ElectoralInspectorData
    {
        return $er->precinct->electoral_inspectors->toCollection()
            ->firstWhere('id', $id);
    }

    public function getInspectorsForPrecinct(string $precinctCode): DataCollection
    {
        $precinct = Precinct::query()
            ->whereCode($precinctCode)
            ->first();

        if (! $precinct || !is_array($precinct->electoral_inspectors)) {
            return new DataCollection(ElectoralInspectorData::class, []);
        }

        return new DataCollection(
            ElectoralInspectorData::class,
            collect($precinct->electoral_inspectors)
                ->map(fn ($inspector) => ElectoralInspectorData::from($inspector))
                ->all()
        );
    }

    public function findSignatory(ElectionReturnData $er, string $id): ElectoralInspectorData
    {
        return $er->signatures->toCollection()->firstWhere('id', $id);
    }

    public function reset(): void
    {
        Ballot::truncate();
        ElectionReturn::truncate();
        Precinct::truncate();
        Position::truncate();
        Candidate::truncate();
    }
}
