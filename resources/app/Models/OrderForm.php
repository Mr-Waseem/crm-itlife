<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderForm extends Model
{
	protected $fillable = ['account_id', 'voucher_no', 'voucher_date', 'delivery_date', 'remarks', 'v_type', 'shop_id', 'biller_id'];

	public function billers()
	{
		return $this->belongsTo('App\Models\User', 'biller_id');
	}

	public function customer()
	{
		return $this->belongsTo('App\Models\Party', 'account_id');
	}

	public function order_details()
	{
		return $this->hasMany('App\Models\OrderFormDetails', 'order_id');
	}
}
