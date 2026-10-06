<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
	protected $fillable = array(
		'party_id','date', 'bill_no', 'grn_no', 'purchase_type', 'remarks', 'credit_to',
		'total_qty', 'total_amount', 'total_net_weight','biller'
	);

	public function purchase_details()
	{
		return $this->hasMany(PurchaseDetail::class, 'purchase_id');
	}

	public function parties()
	{
		return $this->belongsTo(Party::class, 'party_id');
	}
}
