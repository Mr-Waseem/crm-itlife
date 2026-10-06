<?php

namespace Database\Seeders;

use App\Models\RightNames;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RightNamesSeeder extends Seeder
{
    public function run()
    {
        RightNames::create(['code' => 1, 'name' => 'ADD']);
        RightNames::create(['code' => 2, 'name' => 'EDIT']);
        RightNames::create(['code' => 3, 'name' => 'DELETE']);
        RightNames::create(['code' => 4, 'name' => 'PRINT']);
    }
}