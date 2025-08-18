<?php

namespace App\Actions;

use App\Models\{Candidate, Precinct, Position};
use Illuminate\Support\Facades\{DB, Validator};
use App\Enums\{ElectoralInspectorRole, Level};
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Arr;

class InitializeSystem
{
    use AsAction;

    public function rules(): array
    {
        // Request-level rules (query params). We validate file existence later.
        return [
            'reset'    => ['nullable', 'boolean'],
            'election' => ['nullable', 'string'],
            'precinct' => ['nullable', 'string'],
        ];
    }

    /**
     * @throws ValidationException
     */
    public function asController(): JsonResponse
    {
        return response()->json($this->handle(
            request('reset'),
            request('election'),
            request('precinct'),
        ));
    }

    /**
     * @param bool|null $reset
     * @param string|null $electionPath absolute path or storage-relative; falls back to config/election.json
     * @param string|null $precinctPath absolute path or storage-relative; falls back to config/precinct.yaml|yml
     * @return array
     * @throws ValidationException
     */
    public function handle(?bool $reset = null, ?string $electionPath = null, ?string $precinctPath = null): array
    {
        // ------- 1) Resolve file paths (defaults) -------
        $electionPath = $electionPath ?: base_path('config/election.json');
        $precinctPath = $precinctPath ?: base_path('config/precinct.yaml');

        if (! file_exists($electionPath)) {
            throw ValidationException::withMessages([
                'election' => ["Election config not found at: {$electionPath}"],
            ]);
        }
        if (! file_exists($precinctPath)) {
            throw ValidationException::withMessages([
                'precinct' => ["Precinct config not found at: {$precinctPath}"],
            ]);
        }

        // ------- 2) Load configs -------
        $election = json_decode(file_get_contents($electionPath), true);
        if (! is_array($election)) {
            throw ValidationException::withMessages([
                'election' => ['Election config must be a JSON object.'],
            ]);
        }

        $precinct = Yaml::parseFile($precinctPath);
        if (! is_array($precinct)) {
            throw ValidationException::withMessages([
                'precinct' => ['Precinct config must be a YAML object.'],
            ]);
        }

        // ------- 3) Validate shapes (BEFORE any DB writes) -------
        $this->validateElection($election);
        $this->validatePrecinct($precinct);

        // ------- 4) Optional reset -------
        if ($reset) {
            DB::transaction(function () {
                Candidate::query()->delete();
                Position::query()->delete();
                Precinct::query()->delete();
            });
        }

        // ------- 5) Apply to DB -------
        $summary = [
            'positions'  => ['created' => 0, 'updated' => 0],
            'candidates' => ['created' => 0, 'updated' => 0],
            'precinct'   => ['created' => 0, 'updated' => 0],
        ];

        DB::transaction(function () use ($election, $precinct, &$summary) {
            // Positions
            foreach (Arr::get($election, 'positions', []) as $pos) {
                [$model, $created] = Position::query()->updateOrCreate(
                    ['code' => $pos['code']],
                    [
                        'name'  => $pos['name'],
                        'level' => Level::from($pos['level']),
                        'count' => $pos['count'],
                    ]
                )->wasRecentlyCreated
                    ? [null, true]
                    : [null, false];

                $created ? $summary['positions']['created']++ : $summary['positions']['updated']++;
            }

            // Candidates
            $groups = Arr::get($election, 'candidates', []);
            foreach ($groups as $positionCode => $list) {
                foreach ((array) $list as $c) {
                    [$model, $created] = Candidate::query()->updateOrCreate(
                        ['code' => $c['code']],
                        [
                            'name'          => $c['name'],
                            'alias'         => $c['alias'] ?? null,
                            'position_code' => $positionCode,
                        ]
                    )->wasRecentlyCreated
                        ? [null, true]
                        : [null, false];

                    $created ? $summary['candidates']['created']++ : $summary['candidates']['updated']++;
                }
            }

            // Precinct (single record by code)
            $attributes = [
                'location_name'        => $precinct['location_name'],
                'latitude'             => $precinct['latitude']  ?? null,
                'longitude'            => $precinct['longitude'] ?? null,
                'electoral_inspectors' => $precinct['electoral_inspectors'] ?? [],
            ];

            $existing = Precinct::query()->where('code', $precinct['code'])->first();

            Precinct::query()->updateOrCreate(
                ['code' => $precinct['code']],
                $attributes
            );

            $existing ? $summary['precinct']['updated']++ : $summary['precinct']['created']++;
        });

        return [
            'ok'      => true,
            'summary' => $summary,
            'files'   => [
                'election' => $electionPath,
                'precinct' => $precinctPath,
            ],
        ];
    }

    // ----------------- helpers -----------------

    protected function validateElection(array $election): void
    {
        $v = Validator::make($election, [
            'positions'            => ['required', 'array'],
            'positions.*.code'     => ['required', 'string'],
            'positions.*.name'     => ['required', 'string'],
            'positions.*.level'    => ['required', new Enum(Level::class)],
            'positions.*.count'    => ['required', 'integer', 'min:1'],

            'candidates'           => ['required', 'array'],
            // candidates is a map keyed by position_code ⇒ array of candidates
            'candidates.*'         => ['array'],
            'candidates.*.*.code'  => ['required', 'string'],
            'candidates.*.*.name'  => ['required', 'string'],
            'candidates.*.*.alias' => ['nullable', 'string'],
        ]);

        if ($v->fails()) {
            throw new ValidationException($v);
        }
    }

    /**
     * @throws ValidationException
     */
    protected function validatePrecinct(array $precinct): void
    {
        $v = Validator::make($precinct, [
            'code'                    => ['required', 'string'],
            'location_name'           => ['required', 'string'],
            'latitude'                => ['nullable', 'numeric'],
            'longitude'               => ['nullable', 'numeric'],
            'electoral_inspectors'    => ['nullable', 'array'],
            'electoral_inspectors.*'  => ['array'],
            'electoral_inspectors.*.id'   => ['nullable', 'string'],
            'electoral_inspectors.*.name' => ['required', 'string'],
            'electoral_inspectors.*.role' => ['required', new Enum(ElectoralInspectorRole::class)],
        ]);

        if ($v->fails()) {
            throw new ValidationException($v);
        }
    }
}
