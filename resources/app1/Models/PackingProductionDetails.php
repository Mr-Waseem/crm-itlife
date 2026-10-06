<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingProductionDetails extends Model
{
    use HasFactory;
    protected $fillable = ['voucher_no', 'date', 'transaction_id', 'product_id', 'warehouse_id', 'employee_id', 
    'qty', 'pcs'];

    public function employee()
    {
        return $this->belongsTo(Party::class, 'employee_id');
    }

    public function packing()
    {
        return $this->belongsTo(PackingProduction::class, 'transaction_id');
    }
}
