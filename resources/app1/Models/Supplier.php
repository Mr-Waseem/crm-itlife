<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
	protected $fillable = [
		'name',
		'email',
		'phone',
		'company',
		'address',
		'city',
		'state',
		'country'
	];
}
