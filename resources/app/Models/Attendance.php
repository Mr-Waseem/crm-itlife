<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $table='attendances';
    protected $fillable=[
        'date',
        'shop_id',  
        'employee_id',
        'status',
        'time_in',
        'time_out',
        'extra_production',
        'over_time',
        'created_by',
        'updated_by',
    ];
    public function get_partyname(){
        return $this->belongsTo(Party::class,'employee_id');
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'shop_id');
    }
}
