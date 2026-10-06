<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePurchase extends Model
{
    use HasFactory;
    protected $table='sale_purchases';
    protected $fillable=['voucher_no','date','type', 'sale_return_type', 'sale_return_invoice_no', 'grn_dc_id','party_id', 'purchaser_id', 'warehouse_id','remarks', 
    'credit_to','vehicle_no','transport_company','driver_name','builty_no','freight','driver_phoneno', 'extra_charges', 'extra_discount', 'created_by','updated_by'];
    public function grn(){
        return $this->belongsTo(GodownStock::class,'grn_dc_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }
    public function dc(){
        return $this->belongsTo(DeliveryChallan::class,'grn_dc_id');
    }

    public function party(){
        return $this->belongsTo(Party::class,'party_id');
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }

    public function sale_purchase_details(){
        return $this->hasMany(SalePurchaseDetail::class,'sale_purchase_id');
    }
}
