<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GRN extends Model
{
    use HasFactory;
    protected $table = "grn";
    protected $fillable = array('voucher_no', 'voucher_date', 'inward_gatepass_id', 'warehouse_id','total_qty', 'total_amount', 'created_by', 'updated_by');
    public function inward_gatepass(){
        return $this->belongsTo(InwardGatePass::class,'inward_gatepass_id');
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }
    
}