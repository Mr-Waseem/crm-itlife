<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateListDetails extends Model
{
    use HasFactory;
    protected $fillable = array('rate_list_id','product_id', 'previous_rate', 'new_rate', 'packing', 'remarks', 'voucher_date', 'tax_rate');

    public function rate_list()
    {
        return $this->belongsTo(RateList::class,'rate_list_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
