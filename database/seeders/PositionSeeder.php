<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    /** Single source of truth for positions */
    public const POSITIONS = [
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
            'code' => 'GOVERNOR-ILN',
            'name' => 'Governor of Ilocos Norte',
            'level' => 'local',
            'count' => 1,
        ],
        [
            'code' => 'VICE-GOVERNOR-ILN',
            'name' => 'Vice-Governor of Ilocos Norte',
            'level' => 'local',
            'count' => 1,
        ],
        [
            'code' => 'BOARD-MEMBER-ILN',
            'name' => 'Provincial Board Member, Ilocos Norte',
            'level' => 'local',
            'count' => 6,
        ],
        [
            'code' => 'REPRESENTATIVE-ILN-1',
            'name' => 'Representative, 1st District, Ilocos Norte',
            'level' => 'local',
            'count' => 1,
        ],

        // Municipal executives and councilors (example: Currimao, Ilocos Norte)
        [
            'code' => 'MAYOR-ILN-CURRIMAO',
            'name' => 'Mayor of Currimao, Ilocos Norte',
            'level' => 'local',
            'count' => 1,
        ],
        [
            'code' => 'VICE-MAYOR-ILN-CURRIMAO',
            'name' => 'Vice-Mayor of Currimao, Ilocos Norte',
            'level' => 'local',
            'count' => 1,
        ],
        [
            'code' => 'COUNCILOR-ILN-CURRIMAO',
            'name' => 'Municipal Councilor, Currimao, Ilocos Norte',
            'level' => 'local',
            'count' => 8,
        ],
        [
            'code' => 'REPRESENTATIVE-PARTY-LIST',
            'name' => 'Representative, Party List',
            'level' => 'national',
            'count' => 1,
        ],
    ];

    public function run(): void
    {
        foreach (self::POSITIONS as $p) {
            Position::query()->updateOrCreate(['code' => $p['code']], $p);
        }
    }
}
