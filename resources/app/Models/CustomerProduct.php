<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerProduct extends Model
{
    use HasFactory;
    protected $table = 'customer_products';

    protected $fillable = ['customer_id', 'product_id','product_code','product_name'];

    public function customer(){
        return $this->belongsTo(Party::class,'customer_id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
