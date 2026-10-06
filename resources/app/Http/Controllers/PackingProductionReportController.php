<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\Product;
use App\Models\Production;
use App\Models\ThermoformingProduction;
use App\Models\PackingProductionDetails;
use App\Models\Warehouse;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use DB;

class PackingProductionReportController extends Controller
{
    public function index(Request $request){
        // $fromDate = $request->from_date;
        //     $toDate = $request->to_date;
        //     $product_id = $request->product_id;
        //     $warehouse_id = $request->warehouse_id;
        //     $report_type = $request->report_type;
       
        if ($request->ajax()) {
            //   return $request;
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $product_id = $request->product_id;
            $employee_id = $request->employee_id;
            $report_type = $request->report_type;
            // alert("dddf");
         

            
            
            if($report_type == "summary") {
                if($employee_id == 0 && $product_id == 0)
                {
                    $data = PackingProductionDetails::join('products', 'products.id', '=', 'packing_production_details.product_id')
                    ->select(
                        'products.code',
                        'products.product_name',
                        'products.packing',
                         DB::raw('SUM(packing_production_details.qty) as qty'),
                         DB::raw('SUM(packing_production_details.pcs) as pcs'),      
                    )
                    ->orderBy('products.code', 'asc')
                    ->groupBy('packing_production_details.product_id')
                    ->get();

                     if ($data) {
                         return $response = ['production' => $data];
                         }else{
                             return false; 
                         }
                 }
     
                 if($employee_id == 0 && $product_id != 0){
                     // return "all employee, single product";
                    //  $data = PackingProductionDetails::with('employee')->with(['packing' => function($query){
                    //  $query->with('product');
                    // }])
                    //  ->where('product_id', $product_id)
                    //  ->whereDate('date', '>=', $fromDate)
                    //  ->whereDate('date', '<=', $toDate)
                    //  ->get();


                     $data = PackingProductionDetails::join('products', 'products.id', '=', 'packing_production_details.product_id')
                    ->select(
                        'products.code',
                        'products.product_name',
                        'products.packing',
                         DB::raw('SUM(packing_production_details.qty) as qty'),
                         DB::raw('SUM(packing_production_details.pcs) as pcs'),      
                    )
                    ->where('product_id', $product_id)
                    ->orderBy('products.code', 'asc')
                    ->groupBy('packing_production_details.product_id')
                    ->get();


                     if ($data) {
                         return $response = ['production' => $data];
                         }else{
                             return false; 
                         }
                 }
     
                 if($employee_id != 0 && $product_id != 0){
                    //  $data = PackingProductionDetails::with('employee')->with(['packing' => function($query){
                    //      $query->with('product');
                    //     }])
                    //  ->where('product_id', $product_id)
                    //  ->where('employee_id', $employee_id)
                    //  ->whereDate('date', '>=', $fromDate)
                    //  ->whereDate('date', '<=', $toDate)
                    //  ->get();


                     $data = PackingProductionDetails::join('products', 'products.id', '=', 'packing_production_details.product_id')
                     ->select(
                         'products.code',
                         'products.product_name',
                         'products.packing',
                          DB::raw('SUM(packing_production_details.qty) as qty'),
                          DB::raw('SUM(packing_production_details.pcs) as pcs'),      
                     )
                     ->where('product_id', $product_id)
                     ->where('employee_id', $employee_id)
                     ->orderBy('products.code', 'asc')
                     ->groupBy('packing_production_details.product_id')
                     ->get();


                     if ($data) {
                         return $response = ['production' => $data];
                         }else{
                             return false; 
                         }
                 }
                  
                  if($employee_id != 0 && $product_id == 0){
                     // return "single employee, all product";
                    //  $data = PackingProductionDetails::with('employee')->with(['packing' => function($query){
                    //      $query->with('product');
                    //     }])
                    //  ->where('employee_id', $employee_id)
                    //  ->whereDate('date', '>=', $fromDate)
                    //  ->whereDate('date', '<=', $toDate)
                    //  ->get();


                     $data = PackingProductionDetails::join('products', 'products.id', '=', 'packing_production_details.product_id')
                     ->select(
                         'products.code',
                         'products.product_name',
                         'products.packing',
                          DB::raw('SUM(packing_production_details.qty) as qty'),
                          DB::raw('SUM(packing_production_details.pcs) as pcs'),      
                     )
                     ->where('employee_id', $employee_id)
                     ->orderBy('products.code', 'asc')
                     ->groupBy('packing_production_details.product_id')
                     ->get();
                     if ($data) {
                         return $response = ['production' => $data];
                         }else{
                             return false; 
                         }
                  } 
            } 
            else if ($report_type == 'detailed') {
                if($employee_id == 0 && $product_id == 0)
                {
                    $data = PackingProductionDetails::with('employee')->with(['packing' => function($query){
                     $query->with('product');
                    }])
                     ->whereDate('date', '>=', $fromDate)
                     ->whereDate('date', '<=', $toDate)
                     ->get();
                     if ($data) {
                         return $response = ['production' => $data];
                         }else{
                             return false; 
                         }
                 }
     
                 if($employee_id == 0 && $product_id != 0){
                     // return "all employee, single product";
                     $data = PackingProductionDetails::with('employee')->with(['packing' => function($query){
                     $query->with('product');
                    }])
                     ->where('product_id', $product_id)
                     ->whereDate('date', '>=', $fromDate)
                     ->whereDate('date', '<=', $toDate)
                     ->get();
                     if ($data) {
                         return $response = ['production' => $data];
                         }else{
                             return false; 
                         }
                 }
     
                 if($employee_id != 0 && $product_id != 0){
                     $data = PackingProductionDetails::with('employee')->with(['packing' => function($query){
                         $query->with('product');
                        }])
                     ->where('product_id', $product_id)
                     ->where('employee_id', $employee_id)
                     ->whereDate('date', '>=', $fromDate)
                     ->whereDate('date', '<=', $toDate)
                     ->get();
                     if ($data) {
                         return $response = ['production' => $data];
                         }else{
                             return false; 
                         }
                 }
                  
                  if($employee_id != 0 && $product_id == 0){
                     // return "single employee, all product";
                     $data = PackingProductionDetails::with('employee')->with(['packing' => function($query){
                         $query->with('product');
                        }])
                     ->where('employee_id', $employee_id)
                     ->whereDate('date', '>=', $fromDate)
                     ->whereDate('date', '<=', $toDate)
                     ->get();
                     if ($data) {
                         return $response = ['production' => $data];
                         }else{
                             return false; 
                         }
                  }
            }
          
        }
        $products = Product::where('product_type', 'Finish')->orderBy('product_name')->pluck('product_name', 'id')->prepend('All Products', '0');
        $warehouse = Warehouse::orderBy('name')->pluck('name', 'id')->prepend('All', '0');
        $employee = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `code`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
            ->where('designation_id', 4)
            ->orderBy('party_name', 'asc')
            ->pluck('party_name','id')
            ->prepend('All Employees', '0');
        $SingleWarehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id')->first();
        return view('production.packing.report.index', compact('products', 'warehouse', 'SingleWarehouse', 'employee'));
    }

    public function ProductionReportPrint(Request $request)
    {
        // return $request;
    //     $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
    //     ->where('voucher_name', 'THERMOFORMING PRODUCTION')
    //     ->where('right_name', 'PRINT')
    //     ->first();
    // if (!$voucherRight) {
    //     return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    // }
    //  return $request;
        File::cleanDirectory(base_path() . '/upload/production/packing');
        // $voucher_no = $request->voucher_no;
        // $data = ThermoformingProduction::where('voucher_no', '=', $request->voucher_no)->first();
        // return $request;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $product_id = $request->product_id;
        $employee_id = $request->employee_id;
        $report_type = $request->report_type;
        // alert("dddf");
        if($employee_id == 0 && $product_id == 0){
            $data = PackingProductionDetails::with('employee')->with(['packing' => function($query){
                $query->with('product');
               }])
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->get();

            $pdf = PDF::loadView('production.packing.report.print', compact('production'));
            $fileName =  'Packing-Report.pdf';
            $pdf->save(base_path('upload/production/packing/' . $fileName));
            if ($production) {
                return $fileName;
        }else{
            return false;
        }
        }

          
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
