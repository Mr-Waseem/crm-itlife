<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shift;
class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplier = new Shift();
        $supplier->shift_name = 'A';
        $supplier->save();
        
        $supplier = new Shift();
        $supplier->shift_name = 'B';
        $supplier->save();

        $supplier = new Shift();
        $supplier->shift_name = 'C';
        $supplier->save();
    }
}
