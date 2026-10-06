<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestGenerateDetails extends Model
{
    use HasFactory;
    protected $fillable = array(
        'request_generate_id', 'bill_no', 'product_code', 'date', 'supplier_id', 'purchaser_id', 'warehouse_id', 'product_id', 'qty', 'rate','excl_val','st_rate','	sale_tax','total','unit', 'comments',
        'status', 'type', 'po', 'po_date', 'provided_qty', 'tax_with_holding','created_by', 'updated_by'
    );

    public function request_generate()
    {
        return $this->belongsTo(RequestGenerate::class, 'request_generate_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function purchaseorder()
    {
        return $this->belongsTo(RequestGenerate::class, 'request_generate_id');
    }
    public function gatepass_details()
    {
        return $this->hasMany(InwardGatePassDetails::class,'request_detail_id');
    }
   
}