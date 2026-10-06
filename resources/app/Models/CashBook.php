<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashBook extends Model
{
    protected $fillable = [
        'date',
        'vr_no',
        'vr_type',
        'biller_id',
        'shop_id',
        'party_id',
        'purchase_id',
        'sale_id',
        'cash_receipt_id',
        'cash_payment_id',
        'bank_receipt_id',
        'bank_payment_id',
        'jv_id',
        'description',
        'in',
        'out'
    ];
}
