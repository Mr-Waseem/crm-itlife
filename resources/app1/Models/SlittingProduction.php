<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlittingProduction extends Model
{
    use HasFactory;
    protected $fillable = ['voucher_no', 'date', 'consume_product_id', 'remarks', 'warehouse_id',
     'prepared_id', 'updated_id'];

     public function production_details()
    {
        return $this->hasMany('App\Models\SlittingProductionDetail', 'slitting_production_id');
    }

    public function consumed_production()
    {
        return $this->belongsTo(Production::class, 'consume_product_id');
    }

    public function generated_by()
    {
        return $this->belongsTo(User::class, 'prepared_id');
    }

    

    
}
