<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GodownStockDetail extends Model
{
    use HasFactory;
    protected $table='godown_stock_details';
    protected $fillable=[
        'voucher_no',
        'transaction_id',//godownstock id
        'product_id',
        'inward_gatepass_id',
        'date',
        'type',
        'warehouse_id',
        'party_id',
        'qty_in',
        'qty_out',
        'demand_qty',
        'rate',
        'amount',
        'remarks',
        'created_by',
        'updated_by',
        'sale_rate'
    ];

    public function godownstock()
    {
        return $this->belongsTo(GodownStock::class, 'transaction_id');
    }

    public function production()
    {
        return $this->belongsTo(Production::class, 'transaction_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->orderBy('product_name','ASC');
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }
    public function inward_gatepass(){
        return $this->belongsTo(InwardGatePass::class,'inward_gatepass_id');
    }
     public function delivery_challan(){
        return $this->belongsTo(DeliveryChallan::class,'transaction_id');
    }
    public function party(){
        return $this->belongsTo(Party::class, 'party_id');
    }
   
    

    
}
