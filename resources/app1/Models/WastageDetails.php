<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WastageDetails extends Model
{
	protected $fillable = [
		'wastage_id',
		'product_id',
		'party_id',
		'uom_id',
		'discount_id',
		'biller',
		'warehouse_id',
		'quantity',
		'product_cost',
		'cost_amount',
		'discount_id',
		'sale_rate',
		'sale_amount'
	];

	public function products()
	{
		return $this->belongsTo('App\Models\Product', 'product_id');
	}

	public function taxes()
	{
		return $this->belongsTo('App\Models\Tax', 'tax_id');
	}

	public function discount()
	{
		return $this->belongsTo('App\Models\Discount', 'discount_id');
	}

	public function parties()
	{
		return $this->belongsTo('App\Models\Party', 'party_id');
	}

	public function ledger()
	{
		return $this->belongsTo('App\Models\Ledger', 'party_id');
	}

	public function uoms()
	{
		return $this->belongsTo('App\Models\UOM', 'uom_id');
	}

	public function unit()
	{
		return $this->belongsTo('App\Models\UOM', 'uom_id');
	}

	public function sales()
	{
		return $this->belongsTo('App\Models\Sales', 'sale_id');
	}

	public function wastages()
	{
		return $this->belongsTo('App\Models\Wastage', 'wastage_id');
	}
}
