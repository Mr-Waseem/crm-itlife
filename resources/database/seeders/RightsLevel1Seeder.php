<?php

namespace Database\Seeders;

use App\Models\RightsLevel1;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RightsLevel1Seeder extends Seeder
{
    public function run()
    {
        RightsLevel1::create(['code' => 1, 'title' => 'DEFINATION', 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel1::create(['code' => 2, 'title' => 'SYSTEM', 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel1::create(['code' => 3, 'title' => 'TRANSACTIONS', 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel1::create(['code' => 4, 'title' => 'FINANCIAL', 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel1::create(['code' => 5, 'title' => 'INVENTORY', 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel1::create(['code' => 6, 'title' => 'SALES', 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel1::create(['code' => 7, 'title' => 'HR', 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel1::create(['code' => 8, 'title' => 'REPORTS', 'url' => 'javascript:void(0)', 'status' => 1]);
    }
}