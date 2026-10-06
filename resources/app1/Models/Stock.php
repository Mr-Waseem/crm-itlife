<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $fillable = array(
        'voucher_no', 'date', 'type', 'transaction_type', 'dcn_no', 'warehouse_id', 'department_id', 'supplier_id',
        'purchaser_id', 'party_id', 'remarks', 'total_qty', 'total_amount', 'total_sale_rate', 'total_net_weight',
        'created_by', 'updated_by'
    );

    public function stock_details()
    {
        return $this->hasMany(StockDetails::class, 'stock_id')->orderBy('id');
    }

    public function parties()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function billers()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function ledgers()
    {
        return $this->hasMany('App\Models\Ledger');
    }
    public function supplier()
    {
        return $this->belongsTo(Party::class, 'supplier_id');
    }
    public function purchaser()
    {
        return $this->belongsTo(Party::class, 'purchaser_id');
    }
    public function dc_no()
    {
        return $this->belongsTo(DeliveryChallan::class, 'dcn_no');
    }
}