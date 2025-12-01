<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        if (DB::table('levels')->count() == 0) {
            DB::table('levels')->insert([
                [
                    'level_number' => 1,
                    'xp_threshold' => 100,
                    'title' => 'Novice',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'level_number' => 2,
                    'xp_threshold' => 300,
                    'title' => 'Curieux',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'level_number' => 3,
                    'xp_threshold' => 600,
                    'title' => 'Informé',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
