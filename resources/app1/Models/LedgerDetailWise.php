<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerDetailWise extends Model
{
	protected $table = 'ledger_detail_wise';
	protected $fillable = [
		'purchase_id',
		'purchase_ret_id',
		'sale_id',
		'sale_ret_id',
		'dc_id',
		'purchasetax_id',
		'grn_id',
		'lc_id',
		'product_id',
		'party_id',
		'other_head_id',
		'voucher_id',
		'bank_id',
		'attendance_id',
		'purchase_milk_id',
		'warehouse_id',
		'invoice_no',
		'voucher_no',
		'voucher_type',
		'date',
		'quantity',
		'rate',
		'other',
		'debit',
		'credit'
	];

	public function products()
	{
		return $this->BelongsTo('App\Models\Product', 'product_id');
	}

	public function banks()
	{
		return $this->BelongsTo('App\Models\Banks', 'bank_id');
	}

	public function parties()
	{
		return $this->BelongsTo('App\Models\Party', 'party_id');

		// return $this->BelongsTo('App\Models\Party', 'account_head_id')->where('account_group_id', '=', '1')->get();
	}

	public function other_parties()
	{
		return $this->BelongsTo('App\Models\Party', 'other_head_id');
	}
}
