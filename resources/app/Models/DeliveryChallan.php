<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryChallan extends Model
{
    // public $timestamps = false;

    use HasFactory;
    protected $fillable = array(
        'voucher_no', 'voucher_date', 'party_id', 'warehouse_id', 'remarks', 'type', 'order_date', 'sale_order_no',
         'vehicle_no', 'driver_name', 'builty_no', 'driver_phoneno', 'transport_company', 
         'freight', 'total_qty', 'total_sale_qty', 'total_rate', 'total_net_weight', 'status','po_date', 'po_no', 'created_by', 'updated_by'
    );

    protected $guarded = [
        'options' => 'array',
    ];

    public function party()
    {
        return $this->belongsTo(Party::class);
    }
    public function sale_order()
    {
        return $this->belongsTo(SaleOrder::class, 'sale_order_no');
    }

    public function CreatedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dc_details()
    {
        return $this->hasMany(DeliveryChallanDetails::class,'challan_id');
    }

    public function godown_stock_detail()
    {
        return $this->hasMany(GodownStockDetail::class,'transaction_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    
}
