<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlittingProductionDetail extends Model
{
    use HasFactory;
    protected $fillable = ['slitting_production_id', 'warehouse_id', 'date', 'product_id', 'thickness', 'width', 
    'length', 'qty', 'packing', 'weight', 'status', 'godownID_for_edit'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function slitting_production()
    {
        return $this->belongsTo(SlittingProduction::class, 'slitting_production_id');
    }

    
    public function godown()
    {
        return $this->belongsTo(GodownStock::class, 'godownID_for_edit');
    }
}
