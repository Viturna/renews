<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 50; $i++) {
            
            if ($i === 1) {
                $xp = 0;
            } else {
                $xp = floor(100 * pow($i - 1, 1.5));
            }

            Level::updateOrCreate(
                ['level_number' => $i],
                [
                    'xp_threshold' => $xp,
                    'title' => 'Niveau ' . $i 
                ]
            );
        }
    }
}