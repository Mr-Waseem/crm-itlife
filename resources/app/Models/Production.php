<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;
    protected $fillable = array('voucher_no', 'date', 'product_id', 'recipe_id', 'unit_id', 'warehouse_id','total_qty', 'gross_weight',
    'total_rate', 'total_amount', 'remarks', 'color_id', 'machine_id', 'shift_id', 'forman_id', 'operator_id', 'thickness',
     'width', 'batchNo', 'p_status', 'p_type', 'gweight_value', 'created_by', 'updated_by');

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function recipe_creation(){
        return $this->belongsTo(RecipeCreation::class,'recipe_id');
    }

    public function unit()
    {
        return $this->belongsTo(UOM::class, 'unit_id');
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }

    public function machine(){
        return $this->belongsTo(Machine::class,'machine_id');
    }

    public function shift(){
        return $this->belongsTo(Shift::class,'shift_id');
    }

    public function Forman(){
        return $this->belongsTo(Party::class,'forman_id');
    }

    public function Operator(){
        return $this->belongsTo(Party::class,'operator_id');
    }

    public function generated_by(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function recipe(){
        return $this->belongsTo(RecipeCreation::class,'recipe_id');
    }

    public function color(){
        return $this->belongsTo(Color::class,'color_id');
    }
    
    

    
}