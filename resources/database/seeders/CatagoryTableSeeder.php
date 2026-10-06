<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catagory;

class CatagoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $catagory = new Catagory();
        $catagory->catagory_code     = '01';
        $catagory->catagory_name = 'MILK PRODUCTS';
        $catagory->save();

        $catagory = new Catagory();
        $catagory->catagory_code  = '02';
        $catagory->catagory_name = 'DAHI BHALLY';
        $catagory->save();

        $catagory = new Catagory();
        $catagory->catagory_code  = '03';
        $catagory->catagory_name = 'COUSIN';
        $catagory->save();

        $catagory = new Catagory();
        $catagory->catagory_code  = '04';
        $catagory->catagory_name = 'BIRYANI';
        $catagory->save();

        $catagory = new Catagory();
        $catagory->catagory_code  = '05';
        $catagory->catagory_name = 'RAW MATERIAL';
        $catagory->save();

        $catagory = new Catagory();
        $catagory->catagory_code  = '06';
        $catagory->catagory_name = 'SWEETS';
        $catagory->save();

        $catagory = new Catagory();
        $catagory->catagory_code  = '07';
        $catagory->catagory_name = 'RAW MATERIAL & PRODUCTION BOTH';
        $catagory->save();
    }
}
