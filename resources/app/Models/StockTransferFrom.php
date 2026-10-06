<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransferFrom extends Model
{
	protected $table = "stock_transfer_from";
	protected $fillable = [
		'transfer_id',
		'product_id',
		'uom_id',
		'from_warehouse_id',
		'quantity',
		'sale_rate',
		'sale_amount'
	];



	public function products()
	{
		return $this->belongsTo('App\Product', 'product_id');
	}


	public function uoms()
	{
		return $this->belongsTo('App\UOM', 'uom_id');
	}
}
