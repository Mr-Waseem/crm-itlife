<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThermoformingProduction extends Model
{
    use HasFactory;
    protected $fillable = ['batch_id', 'voucher_no', 'date', 'remarks', 'product_id', 'dye_units', 'shift_id', 
    'operator_id', 'machine_id', 'pressman_id', 'warehouse_id', 'pressman_no', 
    'total_sheets', 'check_sheets', 'wastage', 'net_sheets', 'net_sku', 'consumed', 'balance_weight', 'consumed_product_id'];

    public function roll_production()
    {
        return $this->BelongsTO('App\Models\Production', 'consumed_product_id');
    }

    public function product()
    {
        return $this->BelongsTO('App\Models\Product', 'product_id');
    }

    public function operator()
    {
        return $this->BelongsTO('App\Models\Party', 'operator_id');
    }

    public function shift()
    {
        return $this->BelongsTO('App\Models\Shift', 'shift_id');
    }

    public function machine()
    {
        return $this->BelongsTO('App\Models\Machine', 'machine_id');
    }

    public function pressman()
    {
        return $this->BelongsTO('App\Models\Party', 'pressman_id');
    }
}
