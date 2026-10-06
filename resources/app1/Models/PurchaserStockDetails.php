<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaserStockDetails extends Model
{
    use HasFactory;
    protected $fillable = array(
        'purchaser_stock_id', 'date','bill_no','supplier_id', 'product_id', 'product_code', 'product_name', 'unit',
        'price', 'qty', 'total_amount', 'type','igp_number','comments', 'created_by', 'updated_by'
    );

    public function purchase_stock()
    {
        return $this->belongsTo(PurchaserStock::class, 'purchaser_stock_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
