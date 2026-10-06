<?php

namespace Database\Seeders;

use App\Models\MenuRights;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuRightSeeder extends Seeder
{
    public function run()
    {
        // For Admin User
        for ($i = 1; $i < 7; $i++) {
            MenuRights::create([
                'user_id' => 1,
                'level_id' => $i,
                'type' => 'Level 1'
            ]);
        }
        for ($i = 1; $i < 21; $i++) {
            MenuRights::create([
                'user_id' => 1,
                'level_id' => $i,
                'type' => 'Level 2'
            ]);
        }
        for ($i = 1; $i < 45; $i++) {
            MenuRights::create([
                'user_id' => 1,
                'level_id' => $i,
                'type' => 'Level 3'
            ]);
        }

        // For Demo User
        for ($i = 1; $i < 7; $i++) {
            MenuRights::create([
                'user_id' => 2,
                'level_id' => $i,
                'type' => 'Level 1'
            ]);
        }
        for ($i = 1; $i < 21; $i++) {
            MenuRights::create([
                'user_id' => 2,
                'level_id' => $i,
                'type' => 'Level 2'
            ]);
        }
        for ($i = 1; $i < 45; $i++) {
            MenuRights::create([
                'user_id' => 2,
                'level_id' => $i,
                'type' => 'Level 3'
            ]);
        }
    }
}