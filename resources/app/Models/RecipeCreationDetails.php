<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeCreationDetails extends Model
{
    use HasFactory;
    protected $fillable = array(
        'recipe_creation_id','voucher_no', 'product_id','warehouse_id', 'unit_id', 'quantity', 'wastage','rate', 'amount',
        'created_by', 'updated_by'
    );

    public function recipe_creation()
    {
        return $this->belongsTo(RecipeCreation::class, 'recipe_creation_id');
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