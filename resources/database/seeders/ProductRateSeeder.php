<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductRate;

class ProductRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bank = new ProductRate();
        $bank->customer_id = '1';
        $bank->vr_no = '0';
        $bank->date = '2000-01-01';
        $bank->biller = '1';
        $bank->save();
    }
}
