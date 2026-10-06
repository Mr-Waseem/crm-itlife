<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    use HasFactory;
    protected $fillable = array('code', 'name', 'phone', 'email', 'address','warehouse_id');


    public function products()
    {
        return $this->hasMany('App\Models\Product', 'department_id');
    }

    public function products_detail()
    {
        return $this->hasMany('App\Models\PurchaseDetail');
    }

    public function grn_detail()
    {
        return $this->hasMany('App\Models\GRNDetails');
    }
    public function purchase_detail()
    {
        return $this->hasMany('App\Models\PurchaseDetail');
    }

    public function sale_detail()
    {
        return $this->hasMany('App\Models\SaleDetail', 'warehouse_id');
    }

    public function challan_detail()
    {
        return $this->hasMany('App\Models\DeliveryChallanDetail', 'product_id');
    }

    public function sale_return_detail()
    {
        return $this->hasMany('App\Models\SaleReturnDetails', 'product_id');
    }

    public function purchase_return_detail()
    {
        return $this->hasMany('App\Models\PurchaseReturnDetails', 'product_id');
    }

    public function publisher_detail()
    {
        return $this->BelongsTO('App\Models\Publisher', 'publisher_id');
    }

    public function publishers()
    {
        return $this->BelongsTo('App\Models\Publisher', 'publisher_id');
    }

    public function catagories()
    {
        return $this->BelongsTo('App\Models\Catagory', 'catagory_id');
    }

    public function party()
    {
        return $this->hasMany('App\Models\Party', 'account_group_id');
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }
}
