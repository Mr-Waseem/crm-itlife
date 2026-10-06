<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
	protected $fillable = array(
		'purchase_id', 'bill_no','purchase_type','date', 'party_id', 'product_id','product_name',
		'tax_id', 'unit_id', 'unit', 'warehouse_id', 'quantity', 'packing', 'unit_cost', 'net_weight',
		'total_cost','biller'
	);
	
	public function purchase_tax()
	{
		return $this->belongsTo('App\Models\Tax', 'tax_id');
	}

	public function purchase()
	{
		return $this->belongsTo(Purchase::class,'purchase_id');
	}

	public function products()
	{
		return $this->belongsTo(Product::class, 'product_id');
	}

	public function unit()
	{
		return $this->belongsTo(UOM::class, 'uom_id');
	}

	public function party()
	{
		return $this->belongsTo(Party::class, 'party_id');
	}

	public function warehouse()
	{
		return $this->belongsTo(Warehouse::class, 'warehouse_id');
	}
}
