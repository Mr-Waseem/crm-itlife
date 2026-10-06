<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wareHouse = new Warehouse();
        $wareHouse->code = '01';
        $wareHouse->name = 'HEAD OFFICE';
        $wareHouse->phone = '03XXXXXXXXX';
        $wareHouse->email = 'warehouse1@gmail.com';
        $wareHouse->address = 'Lahore, Punjab, Pakistan.';
        $wareHouse->save();

        $wareHouse = new Warehouse();
        $wareHouse->code = '02';
        $wareHouse->name = 'SHOP 1';
        $wareHouse->phone = '03XXXXXXXXX';
        $wareHouse->email = 'warehouse1@gmail.com';
        $wareHouse->address = 'Lahore, Punjab, Pakistan.';
        $wareHouse->save();

        $wareHouse = new Warehouse();
        $wareHouse->code = '03';
        $wareHouse->name = 'SHOP 2';
        $wareHouse->phone = '03XXXXXXXXX';
        $wareHouse->email = 'warehouse1@gmail.com';
        $wareHouse->address = 'Lahore, Punjab, Pakistan.';
        $wareHouse->save();
    }  //

}
