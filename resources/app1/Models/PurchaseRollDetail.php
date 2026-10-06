<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRollDetail extends Model
{
    protected $table = "purchase_rolls_details";
    use HasFactory;
    protected $fillable = ['transaction_id', 'date', 'product_id', 'batchNo', 'thickness', 
    'width', 'color', 'net_weight', 'gross_weight', 'warehouse_id'];

    public function product()
    {
        return $this->BelongsTO('App\Models\Product', 'product_id');
    }

    public function color()
    {
        return $this->BelongsTO('App\Models\Color', 'color');
    }

    public function prepared_by()
    {
        return $this->BelongsTO('App\Models\User', 'created_by');
    }
}
