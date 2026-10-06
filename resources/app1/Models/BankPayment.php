<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankPayment extends Model
{
    protected $fillable = ['account_head_id', 'date', 'voucher_no', 'v_type', 'debit', 'credit'];
}
