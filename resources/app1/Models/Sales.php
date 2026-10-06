<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
	protected $fillable = [
		'date','invoice_no','biller','sale_type','type','dcn_no','warehouse_id','party_id','remarks','total_qty','total_sale_amount'
	];

	public function sale_details()
	{
		return $this->hasMany('App\Models\SaleDetail', 'sale_id')->orderBy('id');
	}

	public function parties()
	{
		return $this->belongsTo(Party::class, 'party_id');
	}

	public function billers()
	{
		return $this->belongsTo('App\Models\User', 'biller');
	}

	public function department()
	{
		return $this->belongsTo(Warehouse::class, 'warehouse_id');
	}

	public function ledgers()
	{
		return $this->hasMany('App\Models\Ledger');
	}
}
