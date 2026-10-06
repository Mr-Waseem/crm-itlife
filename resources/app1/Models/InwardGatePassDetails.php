<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InwardGatePassDetails extends Model
{
    use HasFactory;
    protected $fillable = array(
        'inward_gatepass_id', 'date', 'bill_no', 'request_no', 'supplier_id', 'purchaser_id','warehouse_id', 'request_detail_id', 'product_id', 'product_code', 'product_name', 'unit',
        'price', 'qty', 'qtyshow','total_amount', 'status', 'comments', 'created_by', 'updated_by'
    );

    public function inward()
    {
        return $this->belongsTo(InwardGatePass::class, 'inward_gatepass_id');
    }

    public function request_detail()
    {
        return $this->belongsTo(RequestGenerateDetails::class, 'request_detail_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }
    
}