<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturnDetails extends Model
{
	protected $fillable = [
		'purchase_id',
		'product_id',
		'uom_id',
		'quantity',
		'unit_cost',
		'total_cost'
	];
	public function purchase_tax()
	{
		return $this->belongsTo('App\Models\Tax', 'tax_id');
	}

	public function purchaseReturn()
	{
		return $this->belongsTo('App\Models\PurchaseReturn');
	}

	public function products()
	{
		return $this->belongsTo('App\Models\Product', 'product_id');
	}

	public function unit()
	{
		return $this->belongsTo('App\Models\UOM', 'uom_id');
	}
}
