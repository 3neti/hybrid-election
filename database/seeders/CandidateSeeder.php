<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Candidate;
use App\Models\Position;

class CandidateSeeder extends Seeder
{
    public function run(): void
    {
        $actorNames = collect([
            // International actors/actresses...
            'Leonardo DiCaprio', 'Scarlett Johansson', 'Denzel Washington', 'Meryl Streep',
            'Brad Pitt', 'Angelina Jolie', 'Tom Hanks', 'Viola Davis', 'Chris Hemsworth',
            'Natalie Portman', 'Robert Downey Jr.', 'Anne Hathaway', 'Johnny Depp',
            'Emma Stone', 'Morgan Freeman', 'Jennifer Lawrence', 'Christian Bale',
            'Sandra Bullock', 'Will Smith', 'Julia Roberts', 'Matt Damon', 'Charlize Theron',
            'Keanu Reeves', 'Nicole Kidman', 'Hugh Jackman', 'Zoe Saldana', 'Benedict Cumberbatch',
            'Gal Gadot', 'Jake Gyllenhaal', 'Amy Adams', 'Daniel Craig', 'Kate Winslet',
            'Mark Ruffalo', 'Emily Blunt', 'Chris Evans', 'Rachel McAdams', 'Chadwick Boseman',
            'Tilda Swinton', 'Jason Momoa', 'Octavia Spencer', 'Ryan Gosling', 'Salma Hayek',
            'Jeremy Renner', 'Zendaya', 'Javier Bardem', 'Michelle Yeoh', 'Mahershala Ali',
            'Eva Green', 'Timothée Chalamet', 'Florence Pugh', 'Pedro Pascal', 'Rami Malek',
            'Anya Taylor-Joy', 'Idris Elba', 'Paul Dano', 'Awkwafina', 'Jessica Chastain',
            'Andrew Garfield', 'Tom Holland', 'Lupita Nyong’o', 'Bryan Cranston', 'Helen Mirren',
            'Edward Norton', 'Jeff Bridges', 'Rosamund Pike', 'Naomi Watts', 'Michael Fassbender',
            'Daniel Kaluuya', 'Jennifer Hudson', 'Reese Witherspoon', 'Halle Berry', 'Robin Wright',
            'Dev Patel', 'Benicio Del Toro', 'Dakota Johnson', 'Adam Driver', 'Gina Rodriguez',
            'Jodie Foster', 'Rachel Weisz', 'Colin Firth', 'Don Cheadle', 'Billy Crudup',
            'Lily James', 'Emily Watson', 'J.K. Simmons', 'Jared Leto', 'Jamie Foxx',
            'Eddie Redmayne', 'Stephen Graham', 'Saoirse Ronan', 'Marion Cotillard', 'Michael Shannon',
            'Chiwetel Ejiofor', 'Gugu Mbatha-Raw', 'David Oyelowo', 'John Boyega', 'Tom Hardy',
            'Josh Brolin', 'Oscar Isaac', 'Ben Mendelsohn', 'Tessa Thompson', 'Daniel Radcliffe',

            // 🇵🇭 Filipino actors and actresses
            'Coco Martin', 'Vice Ganda', 'Daniel Padilla', 'Kathryn Bernardo', 'Liza Soberano',
            'Enrique Gil', 'Alden Richards', 'Maine Mendoza', 'Piolo Pascual', 'Bea Alonzo',
            'John Lloyd Cruz', 'Sarah Geronimo', 'James Reid', 'Nadine Lustre', 'Anne Curtis',
            'Luis Manzano', 'Angel Locsin', 'Kim Chiu', 'Gerald Anderson', 'Jennylyn Mercado',
            'Dennis Trillo', 'Carla Abellana', 'Andrea Brillantes', 'Seth Fedelin', 'Barbie Forteza',
            'Jak Roberto', 'Glaiza de Castro', 'Yassi Pressman', 'Ruru Madrid', 'Sanya Lopez',
            'Kylie Padilla', 'Aljur Abrenica', 'Joshua Garcia', 'Julia Barretto', 'Donny Pangilinan',
            'Belle Mariano', 'Jake Cuenca', 'Xian Lim', 'Maja Salvador', 'Janella Salvador',
            'Lovi Poe', 'Paulo Avelino', 'JM de Guzman', 'Jodi Sta. Maria', 'Richard Gutierrez',
            'Rayver Cruz', 'Maxene Magalona', 'Gretchen Barretto', 'Maricel Soriano', 'Vilma Santos',
            'Nora Aunor', 'Sharon Cuneta', 'Judy Ann Santos', 'Kris Aquino', 'Ai-Ai delas Alas',
            'Bayani Agbayani', 'Joey de Leon', 'Vic Sotto', 'Tito Sotto', 'Ogie Alcasid',
            'Regine Velasquez', 'Zsa Zsa Padilla', 'Lea Salonga', 'Gary Valenciano', 'Martin Nievera',
            'Jaya', 'KZ Tandingan', 'Moira Dela Torre', 'Morissette Amon', 'Jed Madela',
            'Jhong Hilario', 'Vhong Navarro', 'Billy Crawford', 'Iñigo Pascual', 'Kisses Delavin',
            'Heaven Peralejo', 'Bianca Umali', 'Kyline Alcantara', 'Elisse Joson', 'McCoy de Leon',
            'Arjo Atayde', 'Jane de Leon', 'Ella Cruz', 'Mark Anthony Fernandez', 'Lotlot de Leon',
            'Sunshine Cruz', 'Sheryl Cruz', 'Buboy Villar', 'Hero Angeles', 'Joross Gamboa',
            'Marvin Agustin', 'Rica Peralejo', 'Antoinette Taus', 'Tonton Gutierrez', 'Jestoni Alarcon',
            'Kiko Estrada', 'Alessandra de Rossi', 'Empoy Marquez', 'Pepe Herrera', 'Alex Gonzaga',
        ])->shuffle();

        $movieTitles = collect([
            'The Godfather', 'Titanic', 'The Dark Knight', 'Forrest Gump', 'Pulp Fiction',
            'Inception', 'Fight Club', 'The Matrix', 'The Shawshank Redemption', 'Gladiator',
            'Avatar', 'La La Land', 'The Social Network', 'Interstellar', 'Mad Max: Fury Road',
            'The Avengers', 'Joker', 'Frozen', 'The Lord of the Rings', 'Black Panther',
            'Parasite', 'The Revenant', 'Dune', 'Top Gun: Maverick', 'Everything Everywhere All At Once',
            'No Time to Die', 'Knives Out', 'Coco', 'The Grand Budapest Hotel', 'Get Out',
            'Bohemian Rhapsody', 'Oppenheimer', 'Barbie', 'Mission: Impossible', 'A Star Is Born',
            'The Whale', 'Spider-Man: No Way Home', 'Guardians of the Galaxy', '1917', 'Tenet',
            'Her', 'Whiplash', 'The Irishman', 'The Martian', 'The Big Short', 'Coda', 'Soul',
            'Toy Story', 'Inside Out', 'Encanto', 'Up', 'The Lion King', 'Finding Nemo', 'Wall-E',
            'Moana', 'The Incredibles', 'Frozen II', 'Avengers: Endgame', 'Doctor Strange',
            'Iron Man', 'Captain America: Civil War', 'Thor: Ragnarok', 'Ant-Man', 'The Prestige',
            'The Departed', 'The Curious Case of Benjamin Button', 'Cast Away', 'Catch Me If You Can',
            'The Truman Show', 'The Imitation Game', 'The Theory of Everything', 'The King’s Speech',
            'The Blind Side', 'Lincoln', 'Les Misérables', 'Birdman', 'The Shape of Water',
            'American Beauty', 'American History X', 'A Beautiful Mind', 'The Green Mile',
            'Shutter Island', 'The Pianist', 'Requiem for a Dream', 'The Big Lebowski',
            'No Country for Old Men', 'There Will Be Blood', 'Argo', 'Zero Dark Thirty',
            'Hacksaw Ridge', 'The Hurt Locker', 'The Fighter', 'Moneyball', 'Steve Jobs',
            '127 Hours', 'Slumdog Millionaire', 'Don’t Look Up', 'The Banshees of Inisherin',
            'The Menu', 'Tár', 'Arrival', 'Ex Machina', 'Looper', 'The Hateful Eight',
            'Django Unchained', 'Inglourious Basterds', 'Once Upon a Time in Hollywood',
            'Kill Bill', 'Reservoir Dogs', 'The Notebook', 'The Fault in Our Stars',
            'Me Before You', 'The Vow', 'The Proposal', 'Crazy Rich Asians',
            '500 Days of Summer', 'Silver Linings Playbook', 'Brooklyn', 'Little Women',
            'Marriage Story', 'Lady Bird', 'Room', 'Nomadland', 'Minari', 'The Farewell',
            'Spotlight', 'The Post', 'Darkest Hour', 'The Two Popes', 'The Midnight Sky',
            'Don’t Worry Darling', 'Bullet Train', 'Knock at the Cabin', 'Glass Onion',
            'The Batman', 'The Flash', 'Shazam!', 'Aquaman', 'Wonder Woman',
            'Man of Steel', 'The Suicide Squad', 'Birds of Prey', 'Justice League',
            'The Lego Movie', 'Zootopia', 'Tangled', 'Raya and the Last Dragon',
            'Luca', 'Turning Red', 'Brave', 'Cars', 'Monsters, Inc.', 'Ratatouille',
            'The Good Dinosaur', 'Onward', 'Lightyear', 'Sing', 'Despicable Me'
        ])->shuffle();

        $candidates = [];

        Position::all()->each(function ($position) use (&$candidates, &$actorNames, &$movieTitles) {
            $count = match ($position->code) {
                'SENATOR' => 60,
                'REPRESENTATIVE-PH-PARTY-LIST' => 150,
                default => rand(6, 10),
            };

            for ($i = 0; $i < $count; $i++) {
                if ($position->code === 'REPRESENTATIVE-PH-PARTY-LIST') {
                    $title = $movieTitles->shift() ?? 'Party ' . Str::random(5);
                    $alias = Str::slug($title, '_');
                    $candidates[] = [
                        'code' => Str::uuid()->toString(),
                        'name' => $title,
                        'alias' => $alias,
                        'position_code' => $position->code,
                    ];
                } else {
                    $name = $actorNames->isNotEmpty()
                        ? $actorNames->shift()
                        : 'Candidate ' . Str::random(5);

                    $alias = Str::of($name)
                        ->explode(' ')
                        ->map(fn($word) => Str::substr($word, 0, 1))
                        ->join('');

                    $candidates[] = [
                        'code' => strtoupper($alias) . '_' . Str::random(2),
                        'name' => $name,
                        'alias' => $alias,
                        'position_code' => $position->code,
                    ];
                }
            }
        });

        Candidate::factory()->createMany($candidates);
    }
}
