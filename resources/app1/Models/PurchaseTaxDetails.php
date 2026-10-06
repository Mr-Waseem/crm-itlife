<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseTaxDetails extends Model
{
	protected $fillable = ['sale_id', 'product_id', 'party_id', 'uom_id', 'quantity', 'rate', 'stvalue', 'taxvalue', 'extratax', 'extraTaxValue', 'price', 'total'];

	public function products()
	{
		return $this->belongsTo('App\Models\Product', 'product_id');
	}

	public function uoms()
	{
		return $this->belongsTo('App\Models\UOM', 'uom_id');
	}
}
