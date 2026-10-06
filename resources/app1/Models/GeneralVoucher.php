<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralVoucher extends Model
{
    protected $fillable = ['account_head_id', 'other_head_id', 'voucher_id', 'bank_id', 
    'warehouse_id', 'product_id', 'date', 'voucher_no', 'cheque_no', 'cheque_date', 
    'rate', 'strate', 'stvalue', 'quantity', 'v_type', 'narration', 'debit', 'credit', 'status'];


    public function banks()
    {
        return $this->BelongsTo(Banks::class, 'bank_id');
    }

    public function parties()
    {
        return $this->belongsTo(Party::class, 'account_head_id');
    }
    
    public function other_parties()
    {
        return $this->BelongsTo(Party::class, 'other_head_id');
    }

    public function party()
    {
        return $this->BelongsTo(Party::class, 'other_head_id');
    }
    public function voucher()
    {
        return $this->belongsTo(Vouchers::class, 'voucher_id');
    }

    public function salepurchase()
    {
        return $this->belongsTo(SalePurchase::class, 'voucher_id');
    }

    public function products()
    {
        return $this->BelongsTo(Product::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->BelongsTo(Warehouse::class, 'warehouse_id');
    }

    
    public function employees()
    {
        return $this->BelongsTo(Party::class, 'advance_employee_id');
    }

    public function customer_products()
    {
        return $this->belongsTo(CustomerProduct::class, 'product_id', 'product_id');
    }
}