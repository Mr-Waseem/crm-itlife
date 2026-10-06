<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRoll extends Model
{
    use HasFactory;
    protected $fillable = ['voucher_no', 'date', 'warehouse_id', 'igp', 'created_by', 'updated_by'];


    public function purchase_roll_details()
    {
        return $this->hasMany(PurchaseRollDetail::class,'transaction_id');
    }

    public function inward()
    {
        return $this->hasOne(InwardGatePass::class,'bill_no', 'igp');
    }

    public function warehouse()
    {
        return $this->BelongsTo(Warehouse::class,'warehouse_id');
    }

    public function preparedby()
    {
        return $this->BelongsTo(User::class,'created_by');
    }
}
