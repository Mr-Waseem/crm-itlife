<?php

namespace Database\Seeders;

use App\Models\RightsLevel3;
use Illuminate\Database\Seeder;

class RightsLevel3Seeder extends Seeder
{
    public function run()
    {
        // ACCOUNT GROUP
        RightsLevel3::create(['code' => 1, 'title' => 'ACCOUNT GROUP 1', 'right_level2_id' => 1, 'url' => 'account-group']);
        RightsLevel3::create(['code' => 2, 'title' => 'ACCOUNT GROUP 2', 'right_level2_id' => 1, 'url' => 'account-group2']);
        RightsLevel3::create(['code' => 3, 'title' => 'ACCOUNT GROUP 3', 'right_level2_id' => 1, 'url' => 'account-group3']);

        // PRODUCT INFORMATION
        RightsLevel3::create(['code' => 4, 'title' => 'PRODUCT INFORMATION', 'right_level2_id' => 2, 'url' => 'products']);
        RightsLevel3::create(['code' => 5, 'title' => 'PRODUCT GROUP', 'right_level2_id' => 2, 'url' => 'product-group']);

        // ACCOUNTS
        RightsLevel3::create(['code' => 6, 'title' => 'CHART OF ACCOUNT', 'right_level2_id' => 3, 'url' => 'parties']);
        RightsLevel3::create(['code' => 7, 'title' => 'BANKS', 'right_level2_id' => 3, 'url' => 'banks']);
        RightsLevel3::create(['code' => 8, 'title' => 'CITIES', 'right_level2_id' => 3, 'url' => 'cities']);
        RightsLevel3::create(['code' => 9, 'title' => 'GODOWN', 'right_level2_id' => 3, 'url' => 'warehouses']);

        // PARTY PROFILE
        RightsLevel3::create(['code' => 10, 'title' => 'SUPPLIERS', 'right_level2_id' => 4, 'url' => 'supplier']);
        RightsLevel3::create(['code' => 11, 'title' => 'PURCHASER', 'right_level2_id' => 4, 'url' => 'purchasers']);
        RightsLevel3::create(['code' => 12, 'title' => 'CUSTOMERS', 'right_level2_id' => 4, 'url' => 'customers']);

        // OPENING
        RightsLevel3::create(['code' => 13, 'title' => 'OPENING STOCK', 'right_level2_id' => 5, 'url' => 'opening-stock']);
        RightsLevel3::create(['code' => 14, 'title' => 'OPENING BALANCE', 'right_level2_id' => 5, 'url' => 'opening-balance']);

        // FINANCIAL VOUCHERS
        RightsLevel3::create(['code' => 15, 'title' => 'CASH RECEIPT VOUCHER', 'right_level2_id' => 12, 'url' => 'cash-receipts']);
        RightsLevel3::create(['code' => 16, 'title' => 'CASH PAYMENT VOUCHER', 'right_level2_id' => 12, 'url' => 'cash-payments']);
        RightsLevel3::create(['code' => 17, 'title' => 'BANK RECEIPT VOUCHER', 'right_level2_id' => 12, 'url' => 'bank-receipts']);
        RightsLevel3::create(['code' => 18, 'title' => 'BANK PAYMENT VOUCHER', 'right_level2_id' => 12, 'url' => 'bank-payments']);
        RightsLevel3::create(['code' => 19, 'title' => 'JOURNAL VOUCHER', 'right_level2_id' => 12, 'url' => 'journal-voucher']);

        // FINANCIAL VOUCHERS (APPROVALS TAB)
        RightsLevel3::create(['code' => 20, 'title' => 'PENDING VOUCHER', 'right_level2_id' => 13, 'url' => 'pending-voucher']);

        // GATEPASS
        RightsLevel3::create(['code' => 21, 'title' => 'INWARD GATEPASS', 'right_level2_id' => 14, 'url' => 'inward-gatepass']);
        RightsLevel3::create(['code' => 22, 'title' => 'GRN', 'right_level2_id' => 14, 'url' => 'grn']);

        // PURCHASE
        RightsLevel3::create(['code' => 23, 'title' => 'PURCHASE VOUCHER', 'right_level2_id' => 15, 'url' => 'purchase']);
        RightsLevel3::create(['code' => 24, 'title' => 'PURCHASE RETURN', 'right_level2_id' => 15, 'url' => 'purchase-return']);
        RightsLevel3::create(['code' => 25, 'title' => 'PURCHASER STOCK', 'right_level2_id' => 15, 'url' => 'purchaser-stock']);
        RightsLevel3::create(['code' => 26, 'title' => 'PURCHASER REPORT', 'right_level2_id' => 15, 'url' => 'purhcaser-report']);

        // PURCHASE REPORTS
        RightsLevel3::create(['code' => 27, 'title' => 'PURCHASE SUMMARY', 'right_level2_id' => 16, 'url' => 'javascript:void(0)']);
        RightsLevel3::create(['code' => 28, 'title' => 'PURCHASE REPORT', 'right_level2_id' => 16, 'url' => 'purchase/report']);
        RightsLevel3::create(['code' => 29, 'title' => 'PURCHASE RETURN REPORT', 'right_level2_id' => 16, 'url' => 'purchase-return/report']);


        // SUPPLIER REPORTS
        RightsLevel3::create(['code' => 30, 'title' => 'BALANCE', 'right_level2_id' => 17, 'url' => 'javascript:void(0)']);
        RightsLevel3::create(['code' => 31, 'title' => 'LEDGER', 'right_level2_id' => 17, 'url' => 'javascript:void(0)']);
        RightsLevel3::create(['code' => 32, 'title' => 'AGING', 'right_level2_id' => 17, 'url' => 'javascript:void(0)']);

        // SUPPLIER REPORTS
        RightsLevel3::create(['code' => 33, 'title' => 'SALE ORDER', 'right_level2_id' => 18, 'url' => 'sales-order']);
        RightsLevel3::create(['code' => 34, 'title' => 'DELIVERY CHALLAN', 'right_level2_id' => 18, 'url' => 'delivery-challan']);
        RightsLevel3::create(['code' => 35, 'title' => 'SALES INVOICE', 'right_level2_id' => 18, 'url' => 'sales-voucher']);
        RightsLevel3::create(['code' => 36, 'title' => 'SALE RETURN', 'right_level2_id' => 18, 'url' => 'sales-return']);
        RightsLevel3::create(['code' => 62, 'title' => 'QUOTATION', 'right_level2_id' => 18, 'url' => 'quotation']);


        // CUSTOMER REPORTS
        RightsLevel3::create(['code' => 37, 'title' => 'LISTING', 'right_level2_id' => 19, 'url' => 'javascript:void(0)']);
        RightsLevel3::create(['code' => 38, 'title' => 'LEDGER', 'right_level2_id' => 19, 'url' => 'javascript:void(0)']);
        RightsLevel3::create(['code' => 39, 'title' => 'BALANCE', 'right_level2_id' => 19, 'url' => 'javascript:void(0)']);
        RightsLevel3::create(['code' => 40, 'title' => 'AGING (SINGLE)', 'right_level2_id' => 19, 'url' => 'javascript:void(0)']);
        RightsLevel3::create(['code' => 41, 'title' => 'AGING (ALL)', 'right_level2_id' => 19, 'url' => 'javascript:void(0)']);


        // SALES REPORT
        RightsLevel3::create(['code' => 42, 'title' => 'SALES', 'right_level2_id' => 20, 'url' => 'sales-voucher/report']);
        RightsLevel3::create(['code' => 43, 'title' => 'SALE RETURN', 'right_level2_id' => 20, 'url' => 'sales-return/report']);
        RightsLevel3::create(['code' => 44, 'title' => 'STOCK TRANSFER REPORT', 'right_level2_id' => 16, 'url' => 'inventory/stock-report']);



        RightsLevel3::create(['code' => 57, 'title' => 'RATE LIST', 'right_level2_id' => 2, 'url' => 'rate-list']);
        RightsLevel3::create(['code' => 58, 'title' => 'SALESTAX INVOICE', 'right_level2_id' => 18, 'url' => 'salestax-invoice']);


        RightsLevel3::create(['code' => 59, 'title' => 'RATE LIST REPORT', 'right_level2_id' => 23, 'url' => 'rate-list/report']);
        RightsLevel3::create(['code' => 60, 'title' => 'DESIGNATION', 'right_level2_id' => 24, 'url' => 'designations']);
        RightsLevel3::create(['code' => 61, 'title' => 'EMPLOYEE TYPES', 'right_level2_id' => 24, 'url' => 'employee-types']);
    }
}
