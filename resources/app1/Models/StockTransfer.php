<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    protected $fillable = array('date', 'bill_no', 'from_warehouse_id', 'to_warehouse_id', 'total_qty', 'total_amount', 'type', 'created_by', 'updated_by');

    public function stock_transfer_details()
    {
        return $this->hasMany(StockTransferDetails::class, 'transfer_id')->orderBy('id');
    }

    public function billers()
    {
        return $this->belongsTo('App\Models\User', 'biller');
    }

    public function warehouse_from()
    {
        return $this->belongsTo('App\Models\Warehouse', 'from_warehouse_id');
    }

    public function warehouse_to()
    {
        return $this->belongsTo('App\Models\Warehouse', 'to_warehouse_id');
    }
}