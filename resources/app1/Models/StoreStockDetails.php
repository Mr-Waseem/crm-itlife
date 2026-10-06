<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreStockDetails extends Model
{
    use HasFactory;
    protected $fillable = array(
        'store_stock_id', 'date', 'supplier_id', 'product_id', 'product_code', 'product_name', 'unit',
        'price', 'qty', 'total_amount', 'type','igp_number','comments', 'created_by', 'updated_by'
    );

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
