<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
	protected $table="settings";
	protected $fillable =
	[
		'system_name',
		'title',
		'address',
		'address2',
		'address3',
		'address4',
		'phone',
		'phone2',
		'email',
		'currency',
		'city',
		'state',
		'country',
		'ntn'
	];
}
