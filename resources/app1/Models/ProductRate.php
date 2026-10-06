<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRate extends Model
{
	protected $fillable = ['customer_id', 'vr_no', 'date', 'biller'];


	public function rate_details()
	{
		return $this->hasMany('App\Models\ProductRateDetails', 'productrate_id')->orderBy('id');
	}

	public function party()
	{
		return $this->belongsTo('App\Models\Party', 'customer_id');
	}

	public function billers()
	{
		return $this->belongsTo('App\Models\User', 'biller');
	}
}
