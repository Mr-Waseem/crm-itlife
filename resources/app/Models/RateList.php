<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateList extends Model
{
    use HasFactory;
    protected $fillable = array('voucher_no', 'voucher_date', 'tax_rate', 'created_by', 'updated_by');

    public function rate_list_details()
    {
        return $this->hasMany(RateListDetails::class,'rate_list_id');
    }
}
