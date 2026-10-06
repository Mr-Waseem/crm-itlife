<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GodownStock extends Model
{
    use HasFactory;
    protected $table = 'godown_stocks';
    protected $fillable = [
        'voucher_no',
        'inward_gatepass_id',
        'date',
        'type',
        'from_warehouse_id',
        'to_warehouse_id',
        'party_id',
        'remarks',
        'status',
        'created_by',
        'updated_by',
    ];
    public function inward_gatepass(){
        return $this->belongsTo(InwardGatePass::class,'inward_gatepass_id');
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'to_warehouse_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }
    public function warehouse_from(){
        return $this->belongsTo(Warehouse::class,'from_warehouse_id');
    }
    public function warehouse_to(){
        return $this->belongsTo(Warehouse::class,'to_warehouse_id');
    }

    public function generated_by(){
        return $this->belongsTo(User::class, 'created_by');
    }

    
    public function opening_pet_rolls(){
        return $this->hasMany(OpeningPetRoll::class, 'transaction_id');
    }

    public function slitting_production_details(){
        return $this->hasMany(SlittingProductionDetail::class, 'godownID_for_edit');
    }

    public function godown_stock_details(){
        return $this->hasMany(GodownStockDetail::class, 'transaction_id');
    }
}
