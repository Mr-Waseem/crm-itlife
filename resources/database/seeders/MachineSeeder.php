<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Machine;
class MachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplier = new Machine();
        $supplier->machine_name = '1';
        $supplier->save();

        $supplier = new Machine();
        $supplier->machine_name = '2';
        $supplier->save();

        $supplier = new Machine();
        $supplier->machine_name = '3';
        $supplier->save();
    }
}
