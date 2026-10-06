<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            SettingsTableSeeder::class,
            SystemLogoTableSeeder::class,
            UOMSeeder::class,
            RoleTableSeeder::class,
            UsersTableSeeder::class,
            AccountGroupSeeder::class,
            CatagoryTableSeeder::class,
            DepartmentSeeder::class,
            TradeGroupSeeder::class,
            PartyTableSeeder::class,
            ProductRateSeeder::class,
            ProductTableSeeder::class,
            SupplierTableSeeder::class,
            WarehouseTableSeeder::class,
            CitiesSeeder::class,
            RightsLevel1Seeder::class,
            RightsLevel2Seeder::class,
            RightsLevel3Seeder::class,
            MenuRightSeeder::class,
            VoucherNamesSeeder::class,
            RightNamesSeeder::class,
            MachineSeeder::class,
            ShiftSeeder::class,
        ]);
    }
}