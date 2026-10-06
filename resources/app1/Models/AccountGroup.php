<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
	protected $fillable = ['code', 'name'];

	public function parties()
	{
		return $this->hasMany('App\Models\Party', 'account_group_id')->orderby('party_name', 'asc');
	}
	public function general_vouchers()
	{
		return $this->hasMany('App\Models\GeneralVoucher', 'account_head_id');
	}

	public function suppliers()
	{
		return $this->hasMany('App\Models\Party', 'account_group_id');
	}

	public function locations()
	{
		return $this->hasMany('App\Models\Party', 'location_id');
	}

    public function account_group_2()
	{
		return $this->hasMany(AccountGroup2::class, 'account_group1_id');
	}
}
