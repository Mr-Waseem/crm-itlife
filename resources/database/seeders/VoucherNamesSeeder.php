<?php

namespace Database\Seeders;

use App\Models\VoucherNames;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherNamesSeeder extends Seeder
{
    public function run()
    {
        VoucherNames::create(['code' => 1, 'voucher_name' => 'CASH RECEIPT VOUCHER']);
        VoucherNames::create(['code' => 2, 'voucher_name' => 'CASH PAYMENT VOUCHER']);
        VoucherNames::create(['code' => 3, 'voucher_name' => 'BANK RECEIPT VOUCHER']);
        VoucherNames::create(['code' => 4, 'voucher_name' => 'BANK PAYMENT VOUCHER']);
        VoucherNames::create(['code' => 5, 'voucher_name' => 'JOURNAL VOUCHER']);
    }
}