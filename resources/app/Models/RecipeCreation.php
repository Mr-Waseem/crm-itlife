<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeCreation extends Model
{
    use HasFactory;
    protected $fillable = array('voucher_no', 'date', 'recipe_code', 'product_id','warehouse_id', 'recipe_name', 'unit_id', 'created_by', 'updated_by');

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function unit()
    {
        return $this->belongsTo(UOM::class, 'unit_id');
    }

    public function generated_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }


    
}