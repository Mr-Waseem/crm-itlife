<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UOM;

class UOMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $uoms = new UOM();
        $uoms->id = '1';
        $uoms->uom = 'KG';
        $uoms->save();

        $uoms = new UOM();
        $uoms->id = '2';
        $uoms->uom = 'PCS';
        $uoms->save();

        $uoms = new UOM();
        $uoms->id = '3';
        $uoms->uom = 'DRUMS';
        $uoms->save();

        $uoms = new UOM();
        $uoms->id = '4';
        $uoms->uom = 'LITRE';
        $uoms->save();

        $uoms = new UOM();
        $uoms->id = '5';
        $uoms->uom = 'BAG';
        $uoms->save();

        $uoms = new UOM();
        $uoms->id = '6';
        $uoms->uom = 'BOTTLES';
        $uoms->save();

        $uoms = new UOM();
        $uoms->id = '7';
        $uoms->uom = 'CANS';
        $uoms->save();

        $uoms = new UOM();
        $uoms->id = '8';
        $uoms->uom = 'COTTON';
        $uoms->save();
    }
}
