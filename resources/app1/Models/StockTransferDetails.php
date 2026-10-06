<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransferDetails extends Model
{
    protected $fillable = [
        'date', 'bill_no', 'transfer_id', 'product_id', 'warehouse_id', 'qty_in',
        'qty_out', 'sale_rate', 'sale_amount', 'type', 'created_by', 'updated_by'
    ];

    public function stock_transfer()
    {
        return $this->belongsTo(StockTransfer::class, 'transfer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}