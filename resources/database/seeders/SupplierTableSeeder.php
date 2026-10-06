<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplier = new Supplier();
        $supplier->name = 'TEST SUPPLIER';
        $supplier->phone = '03004848484';
        $supplier->city = 'LAHORE';
        //$supplier->state = 'PUNJAB';
        //$supplier->country = 'PAKISTAN';
        //$supplier->email = 'SUPPLIER@GMAIL.COM';
        //$supplier->company = 'SUPPLIER COMPANY';
        //$supplier->address = 'LAHORE, PUNJAB, PAKISTAN.';
        $supplier->save();
    }
}
