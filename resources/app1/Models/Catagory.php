<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catagory extends Model
{
	protected $fillable = [
		'catagory_code',
		'catagory_name'
	];


	public function products()
	{
		return $this->hasMany('App\Models\Product', 'category_id');
	}

	public function products2()
	{
		return $this->hasMany('App\Models\Product', 'category_id');
		// return $this->hasMany(Product::class, 'category_id');
	}
	
}
