<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    use HasFactory;
    protected $fillable = array(
        'voucher_no', 'party_voucher_no', 'voucher_date', 'party_id', 'warehouse_id', 'remarks','type','payment_mode','credit_days','po_date','po_no','shipment_term', 'total_qty','total_packing','total_order_qty', 'total_sale_rate', 'status','created_by', 'updated_by'
    );

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function sale_order_details(){
        return $this->hasMany(SaleOrderDetails::class,'sale_order_id');
    }
}