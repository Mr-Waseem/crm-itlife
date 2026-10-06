<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingProduction extends Model
{
    use HasFactory;
    protected $fillable = ['voucher_no', 'date', 'batch_id', 'product_id', 'warehouse_id', 'remarks', 'prepared_id', 'updated_id'];

    public function packing_production()
    {
        return $this->hasMany('App\Models\PackingProductionDetails', 'transaction_id');
    }

    public function thermoforming_production()
    {
        return $this->belongsTo(ThermoformingProduction::class, 'batch_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function prepared_by()
    {
        return $this->belongsTo(User::class, 'prepared_id');
    }
}
