<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = array(
        'code',
        'category_id', 'publisher_id', 'department_id', 'warehouse_id', 'product_code', 'product_name', 'product_english',
        'uom', 'uom_id', 'product_type', 'product_cost', 'product_price', 'tax', 'alert', 'has_recipe', 'pack_type',
        'pack_weight', 'image', 'packing', 'weight', 'dye_pcs', 'remarks', 'created_by', 'updated_by'
    );

    public function party()
    {
        return $this->hasMany('App\Models\Party', 'account_group_id');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function category()
    {
        return $this->belongsTo(Catagory::class, 'category_id');
    }
    public function last_product_price()
    {
        return $this->hasOne(SalePurchaseDetail::class, 'product_id')->orderBy('id', 'desc')->latest();
    }

    public function rate_list_details()
    {
        return $this->hasMany(RateListDetails::class,'product_id');
    }

    public function rate_list_products()
    {
        return $this->hasMany(RateListDetails::class,'product_id');
    }

    
    public function dc_details()
    {
        return $this->hasMany(DeliveryChallanDetails::class,'product_id');
    }

    public function gatepass_details()
    {
        return $this->hasMany(InwardGatePassDetails::class,'product_id');
    }

    public function productRates()
    {
        return $this->hasMany(RateListDetails::class,'product_id');
    }

    public function stock_detail()
    {
        return $this->hasMany(GodownStockDetail::class,'product_id');
    }
    public function opening_stock_detail()
    {
        return $this->hasMany(GodownStockDetail::class,'product_id');
    }

    public function customer_product()
    {
        return $this->hasOne(CustomerProduct::class, 'product_id');
    }
    
}
