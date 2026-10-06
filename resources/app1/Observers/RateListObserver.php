<?php

namespace App\Observers;

use App\Models\RateList;
use Illuminate\Support\Facades\Auth;

class RateListObserver
{
    private $voucher_no = 1;
    public function __construct()
    {
        $rate_list = RateList::orderBy('id', 'desc')->first();
        if ($rate_list) {
            $this->voucher_no = $rate_list->voucher_no + 1;
        }
    }

    public function creating(RateList $rateList)
    {
        $rateList->voucher_no = $this->voucher_no;
        $rateList->created_by = Auth::user()->id;
    }
}
