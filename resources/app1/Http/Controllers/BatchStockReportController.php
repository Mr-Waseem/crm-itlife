<?php

namespace App\Http\Controllers;

use App\Models\VoucherRights;
use App\Models\Party;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Machine;
use App\Models\Shift;
use App\Models\Color;
use App\Models\BatchStock;
use Illuminate\Http\Request;
use App\Models\RequestGenerate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\RequestGenerateDetails;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\InwardGatePass;
use App\Models\InwardGatePassDetails;

class BatchStockReportController extends Controller
{
    public function index(Request $request)
    {
        // return "d";
         
        $machines = Machine::OrderBy('machine_name', 'asc')->pluck('machine_name', 'id')->prepend('All Machines', '0');
        $shift = Shift::OrderBy('shift_name', 'asc')->pluck('shift_name', 'id')->prepend('All Shifts', '0');
        $color = Color::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('All Colors', '0');
        
        $user = Auth::User()->role;
        if($user == "Admin"){
            
            $warehouse = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('All Godowns', '0');
        }else{
            $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id')->prepend('All Godown', '0');
            $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
        }
        // $forman = Party::where('designation_id', 1)->OrderBy('party_name', 'asc')->pluck('party_name', 'id')->prepend('Select Forman', '');
        $forman = DB::table('parties')
        ->select(DB::raw("id,CONCAT(code, '-', party_name) AS  voucher_no")) 
        ->where('designation_id', 1)
        ->orderBy('party_name', 'asc')
        ->pluck('voucher_no','id')
        ->prepend('All Formans', '0');

        $operator = DB::table('parties')
        ->select(DB::raw("id,CONCAT(code, '-', party_name) AS  voucher_no")) 
        ->where('designation_id', 2)
        ->orderBy('party_name', 'asc')
        ->pluck('voucher_no','id')
        ->prepend('Select Operator', '');

        $products = Product::where('pack_type', 'ROLL')
        ->pluck('product_name', 'id')
        ->prepend('All Products', 0);
        $type = ['0' => 'All', 'RECEIVE' => 'RECEIVE', 'TRANSFER' => 'TRANSFER'];
        // $supplier = Party::where('account_type', 'Supplier')->pluck('party_name', 'id')->prepend('All Suppliers', 0);
        // $purchaser = Party::where('account_type', 'Purchaser')->pluck('party_name', 'id')->prepend('All Purchasers', 0);
        return view('inventory-reports.batch-stock.index', compact('products', 'shift', 'forman', 'color', 'warehouse', 'machines', 'type'));
        
    }
    public function report1(){
        $data = Product::Orderby('id', 'asc')->delete();
        return "Done";
    }

    public function report(Request $request)
    {
        // return $request;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $warehouseID = $request->warehouseID;
        $product_id = $request->productID;
        // $supplier_id = $request->supplier_id;
        $colorID = $request->colorID;
        $formanID = $request->formanID;
        $shiftID = $request->shiftID;
        $thickness = $request->thickness;
        $machineID = $request->machineID;
        $Receivetype = $request->Receivetype;
        $report_type = $request->report_type;

         $shift = Shift::where('id', $shiftID)->first(['shift_name']);
         $forman = Party::where('id', $formanID)->first(['code', 'party_name']);
         $product = Product::where('id', $product_id)->first(['code', 'product_name']);
         $color = Color::where('id', $colorID)->first(['name']);
         $warhouse = Warehouse::where('id', $warehouseID)->first(['name']);
         $machine = Machine::where('id', $machineID)->first(['machine_name']);
        //  $machines = Machine::OrderBy('machine_name', 'asc')->pluck('machine_name', 'id')->prepend('Select Machine', '');
        if($report_type == "summary"){
            //  $stock = BatchStock::with('forman')->with('shift')->with('color')
            //  ->with('operator')->with('warehouse')->with('machine')->with('from_warehouse')
            //  ->with('to_warehouse')
             $stock = BatchStock::join('parties as forman', 'forman.id', 'batch_stocks.forman_id')
            ->join('shifts', 'shifts.id', 'batch_stocks.shift_id')
            ->join('colors', 'colors.id', 'batch_stocks.color_id')
            ->join('parties as operator', 'operator.id', 'batch_stocks.operator_id')
            ->join('warehouses as warehouse', 'warehouse.id', 'batch_stocks.warehouse_id')
            ->join('machines', 'machines.id', 'batch_stocks.machine_id')
            ->join('warehouses as from_warehouse', 'from_warehouse.id', 'batch_stocks.from_warehouse_id')
            ->join('warehouses as to_warehouse', 'to_warehouse.id', 'batch_stocks.to_warehouse_id')
            // ->('forman')->with('shift')->with('color')
            //  ->with('operator')->with('warehouse')->with('machine')->with('from_warehouse')
            //  ->with('to_warehouse')
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->where(function ($queryy) use ($warehouseID) {
                if ($warehouseID != 0) {
                    // $queryy->where('warehouse_id', $warehouseID);
                    $queryy->where('from_warehouse_id', $warehouseID);
                }
            })
            ->where(function ($queryy) use ($machineID) {
                if ($machineID != 0) {
                    $queryy->where('machine_id', $machineID);
                }
            })
            ->where(function ($queryy) use ($shiftID) {
                if ($shiftID != 0) {
                    $queryy->where('shift_id', $shiftID);
                }
            })
            ->where(function ($queryy) use ($formanID) {
                if ($formanID != 0) {
                    $queryy->where('forman_id', $formanID);
                }
            })
            ->where(function ($queryy) use ($colorID) {
                if ($colorID != 0) {
                    $queryy->where('color_id', $colorID);
                }
            })
            ->where(function ($queryy) use ($product_id) {
                if ($product_id != 0) {
                    $queryy->where('product_id', $product_id);
                }
            })
            ->where(function ($queryy) use ($thickness) {
                if ($thickness != 0) {
                    $queryy->where('thickness', $thickness);
                }
            })
            ->where(function ($queryy) use ($Receivetype) {
                if ($Receivetype == "RECEIVE") {
                    $queryy->where('total_qty', '>', 0);
                }
                if ($Receivetype == "TRANSFER") {
                    $queryy->where('out_qty', '>', 0);
                }
            })
            ->select('batch_stocks.voucher_no', 'batch_stocks.date', 'batch_stocks.p_type', 'batch_stocks.batchNo', 
            'batch_stocks.width','batch_stocks.thickness',
            'forman.party_name', 'machines.machine_name', 'shifts.shift_name', 
            'forman.party_name as forman', 'operator.party_name as operator', 'warehouse.name as warehouse', 
            'from_warehouse.name as from_warehouse', 'colors.name as color','to_warehouse.name as to_warehouse',
            DB::raw('SUM(total_qty) as net_weight'), DB::raw('SUM(gross_weight) as gross_weight'), DB::raw('SUM(out_qty) as total_out')
)
            ->groupby('product_id')
            // ->orderBy('date', 'asc')
            // ->orderBy('voucher_no', 'asc')
            ->get();
            return Response::json(['data' => $stock]);
        }else{
            $stock = BatchStock::with('forman')->with('shift')->with('color')->with('operator')->with('warehouse')->with('machine')->with('from_warehouse')->with('to_warehouse')
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->where(function ($queryy) use ($warehouseID) {
                if ($warehouseID != 0) {
                    $queryy->where('warehouse_id', $warehouseID);
                }
            })
            ->where(function ($queryy) use ($machineID) {
                if ($machineID != 0) {
                    $queryy->where('machine_id', $machineID);
                }
            })
            ->where(function ($queryy) use ($shiftID) {
                if ($shiftID != 0) {
                    $queryy->where('shift_id', $shiftID);
                }
            })
            ->where(function ($queryy) use ($formanID) {
                if ($formanID != 0) {
                    $queryy->where('forman_id', $formanID);
                }
            })
            ->where(function ($queryy) use ($colorID) {
                if ($colorID != 0) {
                    $queryy->where('color_id', $colorID);
                }
            })
            ->where(function ($queryy) use ($product_id) {
                if ($product_id != 0) {
                    $queryy->where('product_id', $product_id);
                }
            })
            ->where(function ($queryy) use ($thickness) {
                if ($thickness != 0) {
                    $queryy->where('thickness', $thickness);
                }
            })
            ->where(function ($queryy) use ($Receivetype) {
                if ($Receivetype == "RECEIVE") {
                    $queryy->where('total_qty', '>', 0);
                }
                if ($Receivetype == "TRANSFER") {
                    $queryy->where('out_qty', '>', 0);
                }
            })
            
            ->orderBy('date', 'asc')
            ->orderBy('voucher_no', 'asc')
            ->get();
            return Response::json(['data' => $stock]);
        }
        

    }

    public function printPDF(Request $request){
        // return $request;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $warehouseID = $request->warehouseID;
        $product_id = $request->productID;
        // $supplier_id = $request->supplier_id;
        $colorID = $request->colorID;
        $formanID = $request->formanID;
        $shiftID = $request->shiftID;
        $thickness = $request->thickness;
        $machineID = $request->machineID;
        $Receivetype = $request->Receivetype;
        $report_type = $request->report_type;
        
         $shift = Shift::where('id', $shiftID)->first(['shift_name']);
         $forman = Party::where('id', $formanID)->first(['code', 'party_name']);
         $product = Product::where('id', $product_id)->first(['code', 'product_name']);
         $color = Color::where('id', $colorID)->first(['name']);
         $warhouse = Warehouse::where('id', $warehouseID)->first(['name']);
         $machine = Machine::where('id', $machineID)->first(['machine_name']);
        //  $machines = Machine::OrderBy('machine_name', 'asc')->pluck('machine_name', 'id')->prepend('Select Machine', '');
        if($report_type == "summary"){
            //  $stock = BatchStock::with('forman')->with('shift')->with('color')
            //  ->with('operator')->with('warehouse')->with('machine')->with('from_warehouse')
            //  ->with('to_warehouse')
             $stock = BatchStock::join('parties as forman', 'forman.id', 'batch_stocks.forman_id')
            ->join('shifts', 'shifts.id', 'batch_stocks.shift_id')
            ->join('colors', 'colors.id', 'batch_stocks.color_id')
            ->join('parties as operator', 'operator.id', 'batch_stocks.operator_id')
            ->join('warehouses as warehouse', 'warehouse.id', 'batch_stocks.warehouse_id')
            ->join('machines', 'machines.id', 'batch_stocks.machine_id')
            ->join('warehouses as from_warehouse', 'from_warehouse.id', 'batch_stocks.from_warehouse_id')
            ->join('warehouses as to_warehouse', 'to_warehouse.id', 'batch_stocks.to_warehouse_id')
            // ->('forman')->with('shift')->with('color')
            //  ->with('operator')->with('warehouse')->with('machine')->with('from_warehouse')
            //  ->with('to_warehouse')
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->where(function ($queryy) use ($warehouseID) {
                if ($warehouseID != 0) {
                    // $queryy->where('warehouse_id', $warehouseID);
                    $queryy->where('from_warehouse_id', $warehouseID);
                }
            })
            ->where(function ($queryy) use ($machineID) {
                if ($machineID != 0) {
                    $queryy->where('machine_id', $machineID);
                }
            })
            ->where(function ($queryy) use ($shiftID) {
                if ($shiftID != 0) {
                    $queryy->where('shift_id', $shiftID);
                }
            })
            ->where(function ($queryy) use ($formanID) {
                if ($formanID != 0) {
                    $queryy->where('forman_id', $formanID);
                }
            })
            ->where(function ($queryy) use ($colorID) {
                if ($colorID != 0) {
                    $queryy->where('color_id', $colorID);
                }
            })
            ->where(function ($queryy) use ($product_id) {
                if ($product_id != 0) {
                    $queryy->where('product_id', $product_id);
                }
            })
            ->where(function ($queryy) use ($thickness) {
                if ($thickness != 0) {
                    $queryy->where('thickness', $thickness);
                }
            })
            ->where(function ($queryy) use ($Receivetype) {
                if ($Receivetype == "RECEIVE") {
                    $queryy->where('total_qty', '>', 0);
                }
                if ($Receivetype == "TRANSFER") {
                    $queryy->where('out_qty', '>', 0);
                }
            })
            ->select('batch_stocks.voucher_no', 'batch_stocks.date', 'batch_stocks.p_type', 'batch_stocks.batchNo', 
            'batch_stocks.width','batch_stocks.thickness',
            'forman.party_name', 'machines.machine_name', 'shifts.shift_name', 
            'forman.party_name as forman', 'operator.party_name as operator', 'warehouse.name as warehouse', 
            'from_warehouse.name as from_warehouse', 'colors.name as color','to_warehouse.name as to_warehouse',
            DB::raw('SUM(total_qty) as net_weight'), DB::raw('SUM(gross_weight) as gross_weight'), DB::raw('SUM(out_qty) as total_out')
)
            ->groupby('product_id')
            // ->orderBy('date', 'asc')
            // ->orderBy('voucher_no', 'asc')
            ->get();
            $pdf = PDF::loadView('inventory-reports.batch-stock.summary', compact('stock', 'fromDate', 'toDate', 'shift', 'forman', 'color', 'product', 'thickness', 'warhouse', 'machine'))->setPaper('a4', 'landscape');
        $fileName =  'Batch Stock Report.pdf';
        $pdf->save(base_path('upload/stock/batch/' . $fileName));
        return $fileName;
            // return Response::json(['data' => $stock]);
        }else{
        $stock = BatchStock::with('forman')->with('shift')->with('color')->with('operator')->with('warehouse')
        ->whereDate('date', '>=', $fromDate)
        ->whereDate('date', '<=', $toDate)
        ->where(function ($queryy) use ($warehouseID) {
            if ($warehouseID != 0) {
                $queryy->where('warehouse_id', $warehouseID);
            }
        })
        ->where(function ($queryy) use ($machineID) {
            if ($machineID != 0) {
                $queryy->where('machine_id', $machineID);
            }
        })
        ->where(function ($queryy) use ($shiftID) {
            if ($shiftID != 0) {
                $queryy->where('shift_id', $shiftID);
            }
        })
        ->where(function ($queryy) use ($formanID) {
            if ($formanID != 0) {
                $queryy->where('forman_id', $formanID);
            }
        })
        ->where(function ($queryy) use ($colorID) {
            if ($colorID != 0) {
                $queryy->where('color_id', $colorID);
            }
        })
        ->where(function ($queryy) use ($product_id) {
            if ($product_id != 0) {
                $queryy->where('product_id', $product_id);
            }
        })
        ->where(function ($queryy) use ($thickness) {
            if ($thickness != 0) {
                $queryy->where('thickness', $thickness);
            }
        })
        ->where(function ($queryy) use ($Receivetype) {
            if ($Receivetype == "RECEIVE") {
                $queryy->where('total_qty', '>', 0);
            }
            if ($Receivetype == "TRANSFER") {
                $queryy->where('out_qty', '>', 0);
            }
        })
        ->orderBy('date', 'asc')
        ->orderBy('voucher_no', 'asc')
        ->get();
        $pdf = PDF::loadView('inventory-reports.batch-stock.detail', compact('stock', 'fromDate', 'toDate', 'shift', 'forman', 'color', 'product', 'thickness', 'warhouse', 'machine'))->setPaper('a4', 'landscape');
        $fileName =  'Batch Stock Report.pdf';
        $pdf->save(base_path('upload/stock/batch/' . $fileName));
        return $fileName;
    }
}


}
