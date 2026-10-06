<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRateDetails extends Model
{
  protected $fillable = ['productrate_id', 'customer_id', 'product_id', 'product_code', 'rate'];

  public function product()
  {
    return $this->BelongsTo('App\Models\Product')->OrderBy('product_name', 'asc');
  }
}
