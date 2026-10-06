<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\CollectionMilk;
use App\Models\CarryMilk;
use App\Models\PurchaseMilk;

class TotalLossGainController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        return view('purchase-milk.total-lossgain.index');
    }

    public function store(Request $request)
    {
        $company_detail = Setting::where('id', '=', 1)->get();
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $milk = CollectionMilk::with('location')->with('vehicle')->OrderBy('date', 'asc')

            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->selectRaw('date')
            ->selectRaw('sum(ts13) as ts13')
            ->selectRaw('sum(fat5p) as fat5p')
            ->groupBy('date')
            ->get();

        //return $milk;

        $carryMilk = CarryMilk::with('vehicle')
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            //->where('location_id', '=', $LocationID)
            ->selectRaw('date')
            ->selectRaw('sum(ts13) as ts13')
            ->selectRaw('sum(fat5p) as fat5p')
            ->groupBy('date')
            ->get();


        //      $data = CarryMilk::with('location_head')->with('vehicle')->OrderBy('date', 'asc')
        // ->whereDate('date', '>=', $fromDate)
        // ->whereDate('date', '<=', $toDate)
        // ->selectRaw('date')
        // ->selectRaw('sum(ts13) as ts13')
        // ->selectRaw('sum(fat5p) as fat5p')
        // ->get();

        $purchaseMilk = PurchaseMilk::with('vehicle')->OrderBy('date', 'asc')
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->selectRaw('date')
            ->selectRaw('sum(ts13) as ts13')
            ->selectRaw('sum(fat5p) as fat5p')
            ->groupBy('date')
            ->get();
        //return $purchaseMilk; 
        return view('purchase-milk.total-lossgain.report', compact('company_detail', 'fromDate', 'toDate', 'milk', 'carryMilk', 'purchaseMilk'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
