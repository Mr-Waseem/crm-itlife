<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransferTo extends Model
{
	protected $table = "stock_transfer_to";
	protected $fillable = [
		'transfer_id',
		'product_id',
		'uom_id',
		'to_warehouse_id',
		'quantity',
		'sale_rate',
		'sale_amount'
	];

	public function products()
	{
		return $this->belongsTo('App\Models\Product', 'product_id');
	}

	public function uoms()
	{
		return $this->belongsTo('App\Models\UOM', 'uom_id');
	}
}
