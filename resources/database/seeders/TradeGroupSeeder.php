<?php

namespace Database\Seeders;

use App\Models\TradeGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TradeGroupSeeder extends Seeder
{
    public function run()
    {
        TradeGroup::create(['name' => 'Trade Group 1']);
        TradeGroup::create(['name' => 'Trade Group 2']);
        TradeGroup::create(['name' => 'Trade Group 3']);
        TradeGroup::create(['name' => 'Trade Group 4']);
        TradeGroup::create(['name' => 'Trade Group 5']);
    }
}