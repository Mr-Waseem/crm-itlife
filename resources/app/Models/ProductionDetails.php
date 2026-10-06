<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionDetails extends Model
{
    use HasFactory;
    protected $fillable = array('production_id','voucher_no', 'product_id', 'unit_id', 'warehouse_id','quantity','showqty', 'rate', 'amount', 'created_by', 'updated_by');

    public function production()
    {
        return $this->belongsTo(Production::class, 'production_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function unit()
    {
        return $this->belongsTo(UOM::class, 'unit_id');
    }
}