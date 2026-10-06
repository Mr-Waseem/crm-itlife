<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
	protected $fillable = [
		'sale_id','invoice_no','type','sale_type','date','product_id','party_id','uom_id','discount_id','biller','warehouse_id',
		'quantity','product_cost','cost_amount','discount_id','sale_rate','sale_amount'
	];

	public function products()
	{
		return $this->belongsTo(Product::class, 'product_id');
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
		return $this->belongsTo(Party::class, 'party_id');
	}

	public function ledger()
	{
		return $this->belongsTo('App\Models\Ledger', 'party_id');
	}

	public function uoms()
	{
		return $this->belongsTo(UOM::class, 'uom_id');
	}

	public function unit()
	{
		return $this->belongsTo(UOM::class, 'uom_id');
	}

	public function sales()
	{
		return $this->belongsTo(Sales::class, 'sale_id');
	}

	public function department()
	{
		return $this->belongsTo(Warehouse::class, 'warehouse_id');
	}
}
