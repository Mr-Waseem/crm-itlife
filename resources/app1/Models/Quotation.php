<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
	protected $fillable = [
		'vr_no',
		'from_date',
		'to_date',
		'account_id',
		'reference',
		'best_prices',
		'note',
		'best_regards',
		'biller'
	];

	public function quotation_details()
	{
		return $this->hasMany('App\Models\QuotationDetails', 'quotation_id')->orderBy('id');
	}

	public function parties()
	{
		return $this->belongsTo('App\Models\Party', 'account_id');
	}

	public function billers()
	{
		return $this->belongsTo('App\Models\User', 'biller');
	}
}
