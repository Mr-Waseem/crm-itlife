<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleTax extends Model
{
	protected $fillable = ['date', 'sale_type', 'invoice_no', 'dcn_no', 'p_order', 'remarks', 'biller', 'party_id'];

	public function saletax_details()
	{
		return $this->hasMany('App\Models\SaleTaxDetails', 'sale_id');
	}

	public function products()
	{
		return $this->belongsTo('App\Models\Product', 'product_id');
	}

	public function parties()
	{
		return $this->belongsTo('App\Models\Party', 'party_id');
	}

	public function billers()
	{
		return $this->belongsTo('App\Models\User', 'biller');
	}
}
