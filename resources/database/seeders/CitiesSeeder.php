<?php

namespace Database\Seeders;

use App\Models\Cities;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder
{
    public function run()
    {
        Cities::create(['name'=>'LAHORE']);
        Cities::create(['name'=>'KARACHI']);
        Cities::create(['name'=>'RAWALPINDI']);
        Cities::create(['name'=>'ISLAMABAD']);
        Cities::create(['name'=>'MULTAN']);
    }
}
