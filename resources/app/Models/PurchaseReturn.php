<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
	protected $fillable = [
		'account_id',
		'date',
		'bill_no',
		'grn_no',
		'purchase_type',
		'due_date',
		'particulars'
	];

	public function purchase_return_detail()
	{
		return $this->hasMany('App\Models\PurchaseReturnDetails', 'purchase_id');
	}

	public function parties()
	{
		return $this->belongsTo('App\Models\Party', 'account_id');
	}

	public function unit()
	{
		return $this->belongsTo('App\Models\UOM', 'uom_id');
	}
}
