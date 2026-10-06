<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockRegisterSpecificItem extends Model
{
    protected $fillable = ['purchase_id', 'purchase_ret_id', 'sale_id', 'sale_ret_id', 'dc_id', 'purchasetax_id', 'grn_id', 'production_id', 'stock_transferID', 'direct_transferID', 'purchase_milk_id', 'sample_id', 'date', 'code', 'party_id', 'product_id', 'recipe_id', 'voucher_type', 'warehouse_id', 'uom_id', 'purchase_quantity', 'pur_ret_quantity', 'sale_quantity', 'sale_ret_quantity', 'cost_rate'];

    public function products()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function parties()
    {
        return $this->belongsTo('App\Models\Party', 'party_id');
    }

    public function uoms()
    {
        return $this->belongsTo('App\Models\UOM', 'uom_id');
    }

    public function recipe_name()
    {
        return $this->belongsTo('App\Models\Product', 'recipe_id');
    }

    public function shops()
    {
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_id');
    }
}
