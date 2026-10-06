<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationDetails extends Model
{
	protected $table = 'quotation_details';

	protected $fillable = [
		'quotation_id',
		'warehouse_id',
		'party_id',
		'product_id',
		'product_name',
		'description',
		'line_date',
	];

	public function product()
	{
		return $this->belongsTo(Product::class, 'product_id');
	}

	public function quotation()
	{
		return $this->belongsTo(Quotation::class, 'quotation_id');
	}

	public function party()
	{
		return $this->belongsTo(Party::class, 'party_id');
	}
}
