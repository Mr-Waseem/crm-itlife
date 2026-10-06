<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Production;
use App\Models\ThermoformingProduction;
use App\Models\Warehouse;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
class ThermoformingProductionReportController extends Controller
{
    public function ProductionReport(Request $request){
        // $fromDate = $request->from_date;
        //     $toDate = $request->to_date;
        //     $product_id = $request->product_id;
        //     $warehouse_id = $request->warehouse_id;
        //     $report_type = $request->report_type;
       
        if ($request->ajax()) {
             
           $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $product_id = $request->product_id;
            $warehouse_id = $request->warehouse_id;
            $report_type = $request->report_type;
            
            if ($report_type == "summary") {
                //   return "ddds";
                  

                  $production = ThermoformingProduction::with('product')->with(['roll_production' => function($query){
                    $query->with('product');
                }])
                    ->with('shift')->with('operator')->with('machine')->with('pressman')
                    // ->whereId($id)
                    // ->where('voucher_no', $data->voucher_no)
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    ->orderBy('voucher_no')
                    ->get();
  
  
                      return $response = [
                          'production' => $production
                      ];
                  return Response::json($response);
            } 
            else if ($report_type == 'detailed') {
                //  return "03";
             


                    return $response = [
                        'production' => $data
                    ];
                return Response::json($response);
            }
          
        }
        $products = Product::orderBy('product_name')->pluck('product_name', 'id')->prepend('Select Product', '0');
        $warehouse = Warehouse::orderBy('name')->pluck('name', 'id')->prepend('All', '0');
        $SingleWarehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id')->first();
        return view('production.thermoforming.report.index', compact('products', 'warehouse', 'SingleWarehouse'));
    }

    public function ProductionReportPrint(Request $request)
    {
    //     $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
    //     ->where('voucher_name', 'THERMOFORMING PRODUCTION')
    //     ->where('right_name', 'PRINT')
    //     ->first();
    // if (!$voucherRight) {
    //     return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    // }
    //  return $request;
        File::cleanDirectory(base_path() . '/upload/production/thermoforming');
        // $voucher_no = $request->voucher_no;
        // $data = ThermoformingProduction::where('voucher_no', '=', $request->voucher_no)->first();
        // return $request;
           $production = ThermoformingProduction::with('product')->with(['roll_production' => function($query){
                $query->with('product');
            }])
                ->with('shift')->with('operator')->with('machine')->with('pressman')
                // ->whereId($id)
                // ->where('voucher_no', $data->voucher_no)
                ->whereDate('date', '>=', $request->from_date)
                ->whereDate('date', '<=', $request->to_date)
                ->orderBy('voucher_no')
                ->get();

            $pdf = PDF::loadView('production.thermoforming.report.print', compact('production'));
            $fileName =  'Thermoforming-Report.pdf';
            $pdf->save(base_path('upload/production/thermoforming/' . $fileName));
            if ($production) {
                return $fileName;
        }else{
            return false;
        }
    }
}
