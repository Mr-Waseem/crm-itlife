<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InwardGatePass extends Model
{
    use HasFactory;
    protected $fillable = array(
        'bill_no','req_gen_id','date', 'supplier_id', 'purchaser_id', 'warehouse_id','vehicle_no', 'driver_name', 'driver_phoneno',
        'transport_company', 'builty_no', 'status', 'total_amount', 'total_qty', 'created_by', 'updated_by'
    );

    public function inward_gatepass_details()
    {
        return $this->hasMany(InwardGatePassDetails::class, 'inward_gatepass_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Party::class, 'supplier_id');
    }
    public function purchaser()
    {
        return $this->belongsTo(Party::class, 'purchaser_id');
    }

    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function request_generate(){
        return $this->belongsTo(RequestGenerate::class,'req_gen_id');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }
}