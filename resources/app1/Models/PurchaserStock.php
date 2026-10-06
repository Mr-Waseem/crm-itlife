<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaserStock extends Model
{
    use HasFactory;
    protected $fillable = array(
        'bill_no', 'date', 'supplier_id','type','igp_number','total_amount', 'total_qty', 'created_by', 'updated_by'
    );

    public function purchaser_stock_details()
    {
        return $this->hasMany(PurchaserStockDetails::class, 'purchaser_stock_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Party::class, 'supplier_id');
    }

    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
