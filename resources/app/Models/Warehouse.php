<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
	protected $fillable = ['code', 'name', 'phone', 'email', 'ntn', 'strn', 'logo', 'address', 'purchase_account_id', 'sale_account_id', 'purchasetax_account_id', 'saletax_account_id', 'tax_account_id'];


	public function products()
	{
		return $this->hasMany('App\Models\Product', 'warehouse_id');
	}

	public function products_detail()
	{
		return $this->hasMany('App\Models\PurchaseDetail');
	}

	public function grn_detail()
	{
		return $this->hasMany('App\Models\GRNDetails');
	}
	public function purchase_detail()
	{
		return $this->hasMany('App\Models\PurchaseDetail');
	}

	public function sale_detail()
	{
		return $this->hasMany('App\Models\SaleDetail', 'warehouse_id');
	}

	public function challan_detail()
	{
		return $this->hasMany('App\Models\DeliveryChallanDetail', 'product_id');
	}

	public function sale_return_detail()
	{
		return $this->hasMany('App\Models\SaleReturnDetails', 'product_id');
	}

	public function purchase_return_detail()
	{
		return $this->hasMany('App\Models\PurchaseReturnDetails', 'product_id');
	}

	public function publisher_detail()
	{
		return $this->BelongsTO('App\Models\Publisher', 'publisher_id');
	}

	public function publishers()
	{
		return $this->BelongsTo('App\Models\Publisher', 'publisher_id');
	}

	public function catagories()
	{
		return $this->BelongsTo('App\Models\Catagory', 'catagory_id');
	}

	public function party()
	{
		return $this->hasMany('App\Models\Party', 'account_group_id');
	}

	// public function production_stockin()
	// {
	// return $this->hasMany('App\Production', 'products_id');
	// }

	
	public function products_mapping()
	{
		return $this->hasOne('App\Models\ProductMapping', 'warehouse_id');
	}
}
