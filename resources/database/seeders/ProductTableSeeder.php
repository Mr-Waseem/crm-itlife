<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductTableSeeder extends Seeder
{
    public function run()
    {
        $product = new Product();
        $product->category_id = 1;
        $product->publisher_id = '1';
        $product->department_id = 1;
        $product->warehouse_id = 1;
        $product->product_code = '1';
        $product->product_name = 'MILK';
        $product->product_english = 'Test';
        $product->uom = 'KG';
        $product->uom_id = 1;
        $product->product_type = 'Normal';
        $product->product_cost = '80';
        $product->product_price = '100';
        $product->has_recipe = '0';
        $product->tax = '17.00';
        $product->alert = '10';
        $product->pack_type = 'BAG';
        $product->pack_weight = '50';
        $product->save();

        $product = new Product();
        $product->category_id = 1;
        $product->publisher_id = '1';
        $product->department_id = 1;
        $product->warehouse_id = 1;
        $product->product_code = '2';
        $product->product_name = 'SUGAR';
        $product->product_english = 'Caustic Soda';
        $product->uom = 'KG';
        $product->uom_id = 1;
        $product->product_type = 'Normal';
        $product->product_cost = '50';
        $product->product_price = '70';
        $product->has_recipe = '0';
        $product->tax = '17.00';
        $product->alert = '10';
        $product->pack_type = 'BAG';
        $product->pack_weight = '25';
        $product->save();

        $product = new Product();
        $product->category_id = 1;
        $product->publisher_id = '1';
        $product->department_id = 1;
        $product->warehouse_id = 1;
        $product->product_code = '3';
        $product->product_name = 'BARFI';
        $product->product_english = 'Caustic Soda';
        $product->uom = 'KG';
        $product->uom_id = 1;
        $product->product_type = 'Finish';
        $product->product_cost = '300';
        $product->product_price = '600';
        $product->has_recipe = '0';
        $product->tax = '17.00';
        $product->alert = '10';
        $product->pack_type = 'BAG';
        $product->pack_weight = '25';
        $product->save();
    }
}