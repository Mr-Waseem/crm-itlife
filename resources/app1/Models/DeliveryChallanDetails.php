<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryChallanDetails extends Model
{
    use HasFactory;
    protected $fillable = array(
        'challan_id', 'product_id','customer_product_id','party_id','uom_id', 'voucher_no', 'type','voucher_date', 'warehouse_id',
        'demandqty', 'demandPCS', 'quantity', 'packing','sale_qty','sale_rate', 'tax_rate', 'created_by', 'updated_by'
    );

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }
    public function cusproduct()
    {
        return $this->belongsTo(CustomerProduct::class, 'customer_product_id');
    }

    public function uom()
    {
        return $this->belongsTo(UOM::class, 'uom_id');
    }

    public function delivery_challan()
    {
        return $this->belongsTo(DeliveryChallan::class, 'challan_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function saleorderdetails()
    {
        return $this->hasMany(SaleOrderDetails::class, 'sale_order_id', 'sale_order_no');
    }

    public function saleorderdetail()
    {
        return $this->BelongsTo(SaleOrderDetails::class, 'order_detail_id');
    }

    // public function DCdetails()
    // {
    //     return $this->hasMany(DeliveryChallanDetails::class, 'sale_order_id', 'sale_order_no');
    // }

    public function customer_product()
    {
        return $this->belongsTo(CustomerProduct::class, 'product_id', 'product_id');
    }

    // public function dc_details2()
    // {
    //     return $this->hasMany(DeliveryChallanDetails::class,'order_detail_id');
    // }

    public function customers_products()
    {
        return $this->belongsTo(CustomerProduct::class, 'product_id');
    }
}
