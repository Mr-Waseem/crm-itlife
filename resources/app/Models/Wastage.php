<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wastage extends Model
{
	protected $fillable = [
		'date',
		'invoice_no',
		'localExport',
		'biller',
		'sale_type',
		'sale_list',
		'sample_description',
		'dcn_no',
		'warehouse_id',
		'party_id',
		'due_date',
		'particulars',
		'jobcard',
		'chesis_no',
		'vehicle_no',
		'slip_no',
		'engine_no'
	];

	public function wastage_details()
	{
		return $this->hasMany('App\Models\WastageDetails', 'wastage_id')->orderBy('id');
	}

	public function parties()
	{
		return $this->belongsTo('App\Models\Party', 'party_id');
	}

	public function billers()
	{
		return $this->belongsTo('App\Models\User', 'biller');
	}

	public function ledgers()
	{
		return $this->hasMany('App\Models\Ledger');
	}
}
