<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestGenerate extends Model
{
    use HasFactory;
    protected $fillable = array(
        'date', 'bill_no', 'purchaser_id', 'supplier_id', 'warehouse_id','warehouse_name', 'status', 'type', 'request_no', 'po', 'po_date', 'created_by', 'updated_by'
    ,'remarks','tax_with_holding', 'po_created_by', 'po_updated_by');

    public function request_generate_details()
    {
        return $this->hasMany(RequestGenerateDetails::class, 'request_generate_id');
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
    public function created_by_user_po()
    {
        return $this->belongsTo(User::class, 'po_created_by');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }
}