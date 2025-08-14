<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\{Candidate, Position, Precinct};
use App\Data\{VoteData, CandidateData, PositionData};
use App\Actions\SubmitBallot;
use Spatie\LaravelData\DataCollection;

class BallotSeeder extends Seeder
{
    public function run(): void
    {
        $precinct = Precinct::first();

        if (!$precinct) {
            $this->command->warn('No precinct found. Run PrecinctSeeder first.');
            return;
        }

        $positions = Position::all();

        foreach (range(1, 200) as $i) {
            $votes = $positions->map(function ($position) {
                $maxVotes = $position->count;

                // Decide how many votes to cast for this position
                if ($maxVotes > 1) {
                    $minVotes = max(1, $maxVotes - rand(1, 3));
                    $votesToCast = rand($minVotes, $maxVotes);
                } else {
                    $votesToCast = 1;
                }

                // Random candidates for this position
                $candidates = Candidate::where('position_code', $position->code)
                    ->inRandomOrder()
                    ->take($votesToCast)
                    ->get();

                return new VoteData(
                    position: new PositionData(
                        code: $position->code,
                        name: $position->name,
                        level: $position->level,
                        count: $position->count,
                    ),
                    candidates: new DataCollection(
                        CandidateData::class,
                        $candidates->map(fn ($candidate) => new CandidateData(
                            code: $candidate->code,
                            name: $candidate->name,
                            alias: $candidate->alias,
                        ))
                    )
                );
            });

            // Store the ballot using the SubmitBallot action
            SubmitBallot::run(
                precinctId: $precinct->id,
                code: sprintf('BAL-%03d', $i),
                votes: new DataCollection(VoteData::class, $votes),
            );
        }
    }
}
