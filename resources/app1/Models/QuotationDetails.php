<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationDetails extends Model
{
	protected $fillable = ['quotation_id', 'product_id', 'uom_id', 'description', 'rate', 'gst', 'amount'];

	public function products()
	{
		return $this->belongsTo('App\Models\Product', 'product_id');
	}

	public function uoms()
	{
		return $this->belongsTo('App\Models\UOM', 'uom_id');
	}
}
