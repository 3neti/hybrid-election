<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            // National positions
            [
                'code' => 'PRESIDENT',
                'name' => 'President of the Philippines',
                'level' => 'national',
                'count' => 1,
            ],
            [
                'code' => 'VICE-PRESIDENT',
                'name' => 'Vice-President of the Philippines',
                'level' => 'national',
                'count' => 1,
            ],
            [
                'code' => 'SENATOR',
                'name' => 'Senator of the Philippines',
                'level' => 'national',
                'count' => 12,
            ],

            // Provincial legislators and executives (example: Ilocos Norte)
            [
                'code' => 'REPRESENTATIVE-PH-ILN-1',
                'name' => 'Representative, Ilocos Norte 1st District',
                'level' => 'local',
                'count' => 1,
            ],
            [
                'code' => 'REPRESENTATIVE-PH-ILN-2',
                'name' => 'Representative, Ilocos Norte 2nd District',
                'level' => 'district',
                'count' => 1,
            ],
            [
                'code' => 'GOVERNOR-PH-ILN',
                'name' => 'Governor of Ilocos Norte',
                'level' => 'local',
                'count' => 1,
            ],
            [
                'code' => 'VICE-GOVERNOR-PH-ILN',
                'name' => 'Vice-Governor of Ilocos Norte',
                'level' => 'local',
                'count' => 1,
            ],
            [
                'code' => 'BOARD-PH-ILN',
                'name' => 'Provincial Board Member, Ilocos Norte',
                'level' => 'local',
                'count' => 6,
            ],

            // Municipal executives and councilors (example: Currimao, Ilocos Norte)
            [
                'code' => 'MAYOR-PH-ILN-CURRIMAO',
                'name' => 'Mayor of Currimao, Ilocos Norte',
                'level' => 'local',
                'count' => 1,
            ],
            [
                'code' => 'VICE-MAYOR-PH-ILN-CURRIMAO',
                'name' => 'Vice-Mayor of Currimao, Ilocos Norte',
                'level' => 'local',
                'count' => 1,
            ],
            [
                'code' => 'COUNCILOR-PH-ILN-CURRIMAO',
                'name' => 'Municipal Councilor, Currimao, Ilocos Norte',
                'level' => 'local',
                'count' => 8,
            ],
            [
                'code' => 'REPRESENTATIVE-PH-PARTY-LIST',
                'name' => 'Representative, Party List',
                'level' => 'national',
                'count' => 1,
            ],
        ];

        foreach ($positions as $pos) {
            Position::updateOrCreate(
                ['code' => $pos['code']], // ensure no duplicates
                $pos
            );
        }
    }
}
