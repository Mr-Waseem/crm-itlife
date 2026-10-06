<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherRights extends Model
{
    use HasFactory;
    protected $fillable = array('user_id', 'voucher_name', 'right_name');
}