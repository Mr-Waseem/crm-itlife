<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountGroup3 extends Model
{
    use HasFactory;
    protected $table="account_groups3";
    protected $fillable = array('code', 'account_code', 'name','account_group1_id','account_group2_id');

    public function account_group1()
    {
        return $this->belongsTo(AccountGroup::class,'account_group1_id');
    }

    public function account_group2()
    {
        return $this->belongsTo(AccountGroup2::class,'account_group2_id');
    }

    public function parties()
    {
        return $this->hasMany(Party::class,'account_group_id3');
    }
}
