<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\{Ballot, Candidate, Position, Precinct};
use App\Data\{BallotData, VoteData, CandidateData, PositionData};
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

                // For positions with more than 1 vote allowed, randomly reduce vote count
                if ($maxVotes > 1) {
                    // Voter may fill anywhere from (max - 3) to max votes, with randomness
                    $minVotes = max(1, $maxVotes - rand(1, 3));
                    $votesToCast = rand($minVotes, $maxVotes);
                } else {
                    $votesToCast = 1;
                }

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

            Ballot::create([
                'id' => Str::uuid(),
                'code' => sprintf('BAL-%03d', $i),
                'votes' => (new DataCollection(VoteData::class, $votes))->toArray(),
                'precinct_id' => $precinct->id,
            ]);
        }
    }
}
