<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountGroup;

class AccountGroupSeeder extends Seeder
{
    public function run()
    {
        AccountGroup::create(['code' => '01', 'name' => 'CUSTOMER']);
        AccountGroup::create(['code' => '02', 'name' => 'SUPPLIER']);
        AccountGroup::create(['code' => '03', 'name' => 'ASSET']);
        AccountGroup::create(['code' => '04', 'name' => 'LIABILITY']);
        AccountGroup::create(['code' => '05', 'name' => 'BANK']);
        AccountGroup::create(['code' => '06', 'name' => 'EXPENSES']);
        AccountGroup::create(['code' => '07', 'name' => 'CLIENT & SUPPLIER BOTH']);
        AccountGroup::create(['code' => '08', 'name' => 'SALARY']);
        AccountGroup::create(['code' => '09', 'name' => 'EMPLOYEES']);
        AccountGroup::create(['code' => '10', 'name' => 'MILK SUPPLIER']);
        AccountGroup::create(['code' => '11', 'name' => 'LOCATION']);
        AccountGroup::create(['code' => '12', 'name' => 'PURCHASER']);
    }
}