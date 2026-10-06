<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderFormDetails extends Model
{
	protected $fillable = ['order_id', 'account_id', 'product_id', 'quantity', 'price', 'packing_id', 'terms', 'v_type', 'shop_id', 'biller_id'];

	public function products()
	{
		return $this->belongsTo('App\Models\Product', 'product_id');
	}

	public function uom()
	{
		return $this->belongsTo('App\Models\UOM', 'packing_id');
	}
}
