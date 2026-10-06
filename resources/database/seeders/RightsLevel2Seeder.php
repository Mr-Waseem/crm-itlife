<?php

namespace Database\Seeders;

use App\Models\RightsLevel2;
use Illuminate\Database\Seeder;

class RightsLevel2Seeder extends Seeder
{
    public function run()
    {
        // DEFINATION
        RightsLevel2::create(['code' => 1, 'title' => 'ACCOUNT GROUPS', 'right_level1_id' => 1, 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel2::create(['code' => 2, 'title' => 'PRODUCT INFORMATION', 'right_level1_id' => 1, 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel2::create(['code' => 3, 'title' => 'ACCOUNTS', 'right_level1_id' => 1, 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel2::create(['code' => 4, 'title' => 'PARTY PROFILE', 'right_level1_id' => 1, 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel2::create(['code' => 5, 'title' => 'OPENING', 'right_level1_id' => 1, 'url' => 'javascript:void(0)', 'status' => 1]);

        // SYSTEM
        RightsLevel2::create(['code' => 6, 'title' => 'USER INFORMATION', 'right_level1_id' => 2, 'url' => 'users', 'status' => 1]);
        RightsLevel2::create(['code' => 7, 'title' => 'USER RIGHTS', 'right_level1_id' => 2, 'url' => 'user-rights', 'status' => 1]);

        // TRANSACTIONS
        RightsLevel2::create(['code' => 8, 'title' => 'REQUEST GENERATE', 'right_level1_id' => 3, 'url' => 'request-generate', 'status' => 1]);
        RightsLevel2::create(['code' => 9, 'title' => 'STOCK TRANSFER', 'right_level1_id' => 3, 'url' => 'stock-transfer', 'status' => 1]);
        RightsLevel2::create(['code' => 10, 'title' => 'RECIPE CREATION', 'right_level1_id' => 3, 'url' => 'recipe-creation', 'status' => 1]);
        RightsLevel2::create(['code' => 11, 'title' => 'PRODUCTION', 'right_level1_id' => 3, 'url' => 'production', 'status' => 1]);

        // FINANCIALS
        RightsLevel2::create(['code' => 12, 'title' => 'VOUCHERS', 'right_level1_id' => 4, 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel2::create(['code' => 13, 'title' => 'APPROVAL', 'right_level1_id' => 4, 'url' => 'javascript:void(0)', 'status' => 1]);

        // INVENTORY
        RightsLevel2::create(['code' => 14, 'title' => 'GATEPASS', 'right_level1_id' => 5, 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel2::create(['code' => 15, 'title' => 'PURCHASE', 'right_level1_id' => 5, 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel2::create(['code' => 16, 'title' => 'REPORTS', 'right_level1_id' => 5, 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel2::create(['code' => 17, 'title' => 'SUPPLIER REPORTS', 'right_level1_id' => 5, 'url' => 'javascript:void(0)', 'status' => 1]);

        // SALES
        RightsLevel2::create(['code' => 18, 'title' => 'VOUCHERS', 'right_level1_id' => 6, 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel2::create(['code' => 19, 'title' => 'CUSTOMER REPORTS', 'right_level1_id' => 6, 'url' => 'javascript:void(0)', 'status' => 1]);
        RightsLevel2::create(['code' => 20, 'title' => 'SALES REPORT', 'right_level1_id' => 6, 'url' => 'javascript:void(0)', 'status' => 1]);
    }
}