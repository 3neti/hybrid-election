<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PrecinctSeeder::class);
        $this->call(PositionSeeder::class);
        $this->call(CandidateSeeder::class);
        $this->call(BallotSeeder::class);
    }
}
