<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StockSchema extends Migration
{
    public function up()
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id')->unsigned();
            $table->string('account_type', 100)->nullable();
            $table->string('code', 30)->nullable();
            $table->string('party_name', 100);
            $table->integer('location_id')->unsigned()->nullable();
            $table->string('cnic', 20)->nullable();
            $table->integer('bank_id')->unsigned()->nullable();
            $table->string('bank_account_no', 50)->nullable();
            $table->string('phone', 100)->nullable();
            $table->string('ntn', 100)->nullable();
            $table->string('strn', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('address', 200)->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('employee_status', 15)->nullable();
            $table->decimal('fatrate', 10, 2)->nullable();
            $table->decimal('unitrate', 10, 2)->nullable();
            $table->integer('account_group_id')->nullable();
            $table->integer('account_group_id2')->nullable();
            $table->integer('account_group_id3')->nullable();
            $table->integer('status')->nullable();
            $table->string('type')->nullable();
            $table->string('role', 50)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('designation_id')->nullable();
            $table->integer('employee_type_id')->nullable();
            $table->timestamps();
        });


        Schema::create('catagories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('catagory_code');
            $table->string('catagory_name', 100);
            $table->timestamps();
        });

        Schema::create('uoms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uom', 20);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->integer('category_id')->nullable();
            $table->integer('publisher_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->string('product_code', 100)->nullable();
            $table->string('product_name', 100);
            $table->string('product_english', 100)->nullable();
            $table->string('uom', 100);
            $table->integer('uom_id')->nullable();
            $table->string('product_type', 100)->nullable();
            $table->decimal('product_cost', 20, 2);
            $table->decimal('product_price', 20, 2);
            $table->decimal('tax', 10, 2)->nullable();
            $table->string('alert', 10)->nullable();
            $table->string('has_recipe', 5)->nullable();
            $table->string('pack_type', 20)->nullable();
            $table->string('pack_weight', 20)->nullable();
            $table->string('image')->nullable();
            $table->decimal('packing')->nullable();
            $table->decimal('weight')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tax_title', 100);
            $table->decimal('tax_rate', 20, 2);
            $table->string('tax_type', 100);
            $table->timestamps();
        });

        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100);
            $table->decimal('discount', 20, 2);
            $table->string('type', 100);
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('phone', 100);
            $table->string('city', 100);
            $table->timestamps();
        });


        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('party_id');
            $table->date('date');
            $table->string('bill_no', 100);
            $table->string('grn_no', 100)->nullable();
            $table->string('purchase_type', 20);
            $table->text('remarks')->nullable();
            $table->integer('total_qty')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->decimal('total_net_weight')->nullable();
            $table->integer('biller');
            $table->timestamps();
        });

        Schema::create('purchase_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_id');
            $table->string('purchase_type', 30);
            $table->integer('bill_no');
            $table->date('date');
            $table->integer('party_id');
            $table->integer('product_id');
            $table->string('product_name');
            $table->integer('unit_id')->nullable();
            $table->string('unit')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->string('quantity', 100);
            $table->string('packing', 100);
            $table->string('unit_cost', 100);
            $table->decimal('net_weight', 20);
            $table->decimal('total_cost', 20, 2);
            $table->integer('biller');
            $table->timestamps();
        });


        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('invoice_no');
            $table->string('biller', 100)->nullable();
            $table->string('sale_type', 20);
            $table->string('type');
            $table->string('dcn_no', 20)->nullable();
            $table->integer('warehouse_id')->unsigned();
            $table->integer('party_id')->unsigned();
            $table->text('remarks')->nullable();
            $table->decimal('total_qty')->nullable();
            $table->decimal('total_sale_amount')->nullable();
            $table->timestamps();
        });

        Schema::create('sale_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_no');
            $table->date('date')->nullable();
            $table->string('type');
            $table->integer('sale_id');
            $table->integer('product_id');
            $table->integer('party_id');
            $table->integer('uom_id');
            $table->integer('discount_id');
            $table->integer('biller');
            $table->integer('warehouse_id');
            $table->string('quantity', 100);
            $table->string('product_cost', 30);
            $table->string('cost_amount', 30);
            $table->decimal('sale_rate', 20, 2);
            $table->decimal('sale_amount', 20, 2);
            $table->timestamps();
        });

        Schema::create('repairings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('party_id')->unsigned();
            $table->date('date');
            $table->string('reference_no', 100);
            $table->timestamps();
        });

        Schema::create('repairing_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('repairing_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('party_id')->unsigned();
            $table->string('quantity', 100);
            $table->decimal('charges', 20, 2);
            $table->timestamps();
        });


        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('system_name', 100)->nullable();
            $table->string('title', 100)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('address4')->nullable();
            $table->string('phone', 100)->nullable();
            $table->string('phone2')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('currency', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('footer_line', 200)->nullable();
            $table->string('ntn', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('system_logos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image', 250)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        //
    }
}
