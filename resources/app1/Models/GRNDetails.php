<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GRNDetails extends Model
{
    use HasFactory;
    protected $table = "grn_details";
    protected $fillable = array(
        'grn_id', 'inward_gatepass_id','warehouse_id','voucher_no', 'voucher_date', 'product_code', 'product_id',
        'product_name', 'unit', 'price', 'qty', 'total_amount', 'comments', 'created_by', 'updated_by'
    );

    public function grn()
    {
        return $this->belongsTo(GRN::class, 'grn_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }
    public function inward_gatepass(){
        return $this->belongsTo(InwardGatePass::class,'inward_gatepass_id');
    }
}