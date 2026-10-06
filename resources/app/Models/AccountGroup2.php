<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountGroup2 extends Model
{
    use HasFactory;
    protected $table="account_groups2";
    protected $fillable = array('code', 'account_code', 'name','account_group1_id');

    public function account_group1()
    {
        return $this->belongsTo(AccountGroup::class,'account_group1_id');
    }

    public function account_group_3()
	{
		return $this->hasMany(AccountGroup3::class, 'account_group2_id');
	}
}
