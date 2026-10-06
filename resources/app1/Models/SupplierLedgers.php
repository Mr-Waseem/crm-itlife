<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierLedgers extends Model
{
	protected $table = 'supplier_ledgers';
	protected $fillable = [
		'supplier_id',
		'date',
		'particulars',
		'bill_no',
		'debit',
		'credit'
	];
}
