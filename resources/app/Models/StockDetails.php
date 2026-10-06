<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDetails extends Model
{
    use HasFactory;
    protected $fillable = array(
        'voucher_no', 'stock_id', 'date', 'type', 'transaction_type', 'dcn_no', 'supplier_id', 'purchaser_id',
        'warehouse_id', 'department_id', 'party_id', 'product_id', 'unit_id', 'discount_id', 'qty_in', 'qty_out', 'product_cost', 'cost_amount',
        'sale_rate', 'sale_amount', 'packing', 'net_weight', 'created_by', 'updated_by'
    );

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function taxes()
    {
        return $this->belongsTo('App\Models\Tax', 'tax_id');
    }

    public function discount()
    {
        return $this->belongsTo('App\Models\Discount', 'discount_id');
    }

    public function parties()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function ledger()
    {
        return $this->belongsTo('App\Models\Ledger', 'party_id');
    }

    public function uoms()
    {
        return $this->belongsTo(UOM::class, 'uom_id');
    }

    public function unit()
    {
        return $this->belongsTo(UOM::class, 'unit_id');
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}