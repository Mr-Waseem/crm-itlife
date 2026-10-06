<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationMilestone extends Model
{
	protected $table = 'quotation_milestones';

	protected $fillable = [
		'quotation_id',
		'module_name',
		'payment_percent',
		'timeframe_days',
	];

	public function quotation()
	{
		return $this->belongsTo(Quotation::class, 'quotation_id');
	}
}
