<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class SaleOrderDetails extends Model
{
    use HasFactory;
    protected $fillable = array('voucher_no', 'voucher_date', 'sale_order_id','party_id','type', 'product_id', 'qty', 'packing','order_qty','sale_rate', 'excl_value','s_tax','st_value','sale_amount','delivery_date','remark', 'status');

    public function sale_order()
    {
        return $this->belongsTo(SaleOrder::class);
    }
    public function customer_product()
    {
        return $this->belongsTo(CustomerProduct::class,'product_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function dc_details()
    {
        return $this->hasMany(DeliveryChallanDetails::class,'sale_order_no', 'sale_order_id');
    }

    public function dc_details2()
    {
        return $this->hasMany(DeliveryChallanDetails::class,'order_detail_id');
    }

}