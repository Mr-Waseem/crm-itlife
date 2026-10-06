<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vouchers extends Model
{
    protected $fillable = ['account_id', 'warehouse_id', 'voucher_no', 'voucher_date', 'v_type', 'total_debit', 'total_credit', 'status', 'advance_type_id', 'advance_types', 'biller'];

    public function voucher_details()
    {
        return $this->hasMany('App\Models\GeneralVoucher', 'voucher_id')->orderBy('id');
    }

    public function parties()
    {
        return $this->belongsTo('App\Models\Party', 'account_id');
    }

    public function billers()
    {
        return $this->belongsTo(User::class, 'biller');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function advance_type()
    {
        return $this->belongsTo(Party::class, 'advance_type_id');
    }
}