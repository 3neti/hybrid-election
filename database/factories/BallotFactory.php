<?php

namespace Database\Factories;


use App\Data\{BallotData, VoteData, PositionData, CandidateData};
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Str;
use App\Models\Ballot;
use App\Enums\Level;


/**
 * @extends Factory<Ballot>
 */
class BallotFactory extends Factory
{
    public function definition(): array
    {
        $votes = new DataCollection(VoteData::class, [
            new VoteData(
                new PositionData(
                    code: 'PRESIDENT',
                    name: 'President of the Philippines',
                    level: Level::NATIONAL,
                    count: 1,
                ),
                new DataCollection(CandidateData::class, [
                    new CandidateData(
                        code: Str::uuid()->toString(),
                        name: 'Ferdinand Marcos Jr.',
                        alias: 'BBM'
                    ),
                ])
            ),
            new VoteData(
                new PositionData(
                    code: 'SENATOR',
                    name: 'Senator of the Philippines',
                    level: Level::NATIONAL,
                    count: 12,
                ),
                new DataCollection(CandidateData::class, [
                    new CandidateData(Str::uuid(), 'Juan Dela Cruz', 'JDC'),
                    new CandidateData(Str::uuid(), 'Maria Rosario P.', 'MRP'),
                ])
            ),
        ]);

        return [
            'code' => 'BALLOT-' . Str::uuid()->toString(),
            'votes' => $votes,
        ];
    }
}
