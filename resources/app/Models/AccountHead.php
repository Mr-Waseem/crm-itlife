<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountHead extends Model
{
    protected $table = "account_groups";
    protected $fillable = ['account_group', 'title', 'account_no'];

    public function ledger_details()
    {
        return $this->hasMany('App\Models\GeneralVoucher', 'account_head_id');
    }
}
