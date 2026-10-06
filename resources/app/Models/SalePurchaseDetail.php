<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePurchaseDetail extends Model
{
    use HasFactory;
    protected $table = 'sale_purchase_details';
    protected  $fillable = [
        'voucher_no',
        'sale_purchase_id',
        'product_id',
        'warehouse_id',
        'party_id',
        'demandQty',
        'qty',
        'rate',
        'excl_val',
        'st_rate',
        'sale_tax',
        'sale_qty',
        'total',
        'type',
        'thickness',
        'discount',
        'po_no',
        'created_by',
        'updated_by',
    ];
    public function salepurchase()
    {
        return $this->belongsTo(SalePurchase::class, 'sale_purchase_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function cusproduct()
    {
        return $this->belongsTo(CustomerProduct::class, 'product_id', 'product_id');
    }

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }
}