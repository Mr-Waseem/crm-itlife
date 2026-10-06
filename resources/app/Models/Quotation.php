<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
	protected $fillable = [
		'voucher_no',
		'date',
		'valid_to',
		'warehouse_id',
		'party_id',
		'atten',
		'subject',
		'features',
		'deadline_days',
		'warranty_months',
		'remarks',
		'created_by',
		'updated_by',
	];

	public function quotation_details()
	{
		return $this->hasMany(QuotationDetails::class, 'quotation_id')->orderBy('id');
	}

	public function milestones()
	{
		return $this->hasMany(QuotationMilestone::class, 'quotation_id')->orderBy('id');
	}

	public function party()
	{
		return $this->belongsTo(Party::class, 'party_id');
	}

	public function warehouse()
	{
		return $this->belongsTo(Warehouse::class, 'warehouse_id');
	}

	public function creator()
	{
		return $this->belongsTo(User::class, 'created_by');
	}
}
