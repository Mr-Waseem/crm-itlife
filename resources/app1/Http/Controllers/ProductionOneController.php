<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\UOM;
use App\Models\Product;
use App\Models\Production;
use App\Models\GodownStockDetail;
use App\Models\ProductionDetails;
use App\Models\RecipeCreation;
use App\Models\RecipeCreationDetails;
use App\Models\Machine;
use App\Models\Shift;
use App\Models\Party;
use App\Models\VoucherRights;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ProductionOneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $code = Production::where('warehouse_id', Auth::User()->warehouse_id)
        ->where('p_type', 'PRODUCTION ONE')
        ->orderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->voucher_no + 1;
        }
        // $recipe_products = Product::OrderBy('id', 'asc')
        //     ->where('warehouse_id', Auth::User()->warehouse_id)
        //     ->where('product_type', 'Finish')
        //     ->pluck('product_name', 'id')
        //     ->prepend('Select Product', '');
        // return Auth::User()->warehouse_id;
            if(Auth::user()->role == "Admin"){
                $recipe_products = RecipeCreation::join('products','products.id','=','recipe_creations.product_id')
               ->OrderBy('code', 'asc')
                ->select(
                    'products.id as id',
                     DB::raw('CONCAT(`code`, "-", `product_name`) as product_name'),
                )
                ->pluck('product_name','id')
                ->prepend('Select Product','');  
                // Show all Recipies to show dropdown for edit purpose
                $recipeName = RecipeCreation::Orderby('id', 'asc')->pluck('recipe_name', 'id')
                ->prepend('Select Recipe', '');
            }else{
                $recipe_products = RecipeCreation::join('products','products.id','=','recipe_creations.product_id')
               ->OrderBy('code', 'asc')
                ->select(
                    'products.id as id',
                     DB::raw('CONCAT(`code`, "-", `product_name`) as product_name'),
                )
                ->where('recipe_creations.warehouse_id', Auth::User()->warehouse_id)
                ->pluck('product_name','id')
                ->prepend('Select Product',''); 
                // Show warehouse Recipies to show dropdown for edit purpose
                $recipeName = RecipeCreation::Orderby('id', 'asc')
                ->where('warehouse_id', Auth::User()->warehouse_id)
                ->pluck('recipe_name', 'id')
                ->prepend('Select Recipe', '');
            } 
        // $recipeName = ['Select Recipe' => ''];
        // $recipeName = ['Select Recipe' => ''];
        $products = Product::select(DB::raw('CONCAT(`id`, "_", `code`, "_", `product_name`, "_", `uom`,"_", `product_price`,"_", `product_cost`) AS `id`,`product_name`'))
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->where('product_type', 'Normal')
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');
        $units = UOM::OrderBy('id', 'asc')->pluck('uom', 'id')->prepend('Select Unit', '');
        $machines = Machine::OrderBy('machine_name', 'asc')->pluck('machine_name', 'id')->prepend('Select Machine', '');
        $shift = Shift::OrderBy('shift_name', 'asc')->pluck('shift_name', 'id')->prepend('Select Shift', '');
        // $forman = Party::where('designation_id', 1)->OrderBy('party_name', 'asc')->pluck('party_name', 'id')->prepend('Select Forman', '');
        $forman = DB::table('parties')
        ->select(DB::raw("id,CONCAT(code, '-', party_name) AS  voucher_no")) 
        ->where('designation_id', 1)
        ->orderBy('party_name', 'asc')
        ->pluck('voucher_no','id')
        ->prepend('Select Forman', '');
        $operator = DB::table('parties')
        ->select(DB::raw("id,CONCAT(code, '-', party_name) AS  voucher_no")) 
        ->where('designation_id', 2)
        ->orderBy('party_name', 'asc')
        ->pluck('voucher_no','id')
        ->prepend('Select Operator', '');
        // $operator = Party::where('designation_id', 2)->OrderBy('party_name', 'asc')->pluck('party_name', 'id')->prepend('Select Operator', '');
        return view('production.production-one.index', compact('codes', 'recipe_products', 'products', 'units', 'machines', 'shift', 'forman', 'operator', 'recipeName'));
    }

    public function store(Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'voucher_no' => 'required',
            'date' => 'required',
            'product_id2' => 'required',
            'total_qty' => 'required'
        ], [
            'product_id2.required' => 'The product field is required.',
            'total_qty.required' => 'The quantity field is required.'
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator);
        }


        if (!isset($request->voucher_no)) {
            return redirect()->back()->with('failure_message', 'Please Enter Voucher No');
        }
        if (!isset($request->product_id)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }
        if (!isset($request->rate)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }

        if ($request->update_voucher_id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PRODUCTON 1')
            ->where('right_name', 'EDIT')
            ->first();
            $data = $request->all();
            $data['unit_id'] = 1;
            $data['p_status'] = 0;
            $data['p_type'] = "PRODUCTION ONE";
            $data['product_id'] = $request->product_id2;
            $data['updated_by'] = Auth::User()->id;
            $data['batchNo'] =  date("ymd", strtotime($request->date)).$request->voucher_no;
            // $data['batchNo'] =  date("ymd", strtotime($request->date)).$request->machine_id.$request->shift_id.$request->format_id.$request->operator_id;
            $production = Production::find($request->update_voucher_id);
            $production->update($data);

            ProductionDetails::where('production_id', $request->update_voucher_id)->delete();
            GodownStockDetail::where('transaction_id', $production->id)
            ->where('type', 'PRODUCTION ONE')->delete();
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $productionDetails = new ProductionDetails();
                $productionDetails->production_id = $production['id'];
                $productionDetails->product_id = $request->product_id[$i];
                $productionDetails->unit_id = 1;
                $productionDetails->voucher_no =  $production['voucher_no'];
                $productionDetails->warehouse_id = Auth::User()->warehouse_id;
                $productionDetails->recipe_qty = $request->recipe_qty[$i];
                $productionDetails->quantity = $request->qty[$i];
                $productionDetails->showqty = $request->showqty[$i];
                $productionDetails->rate = $request->rate[$i];
                $productionDetails->amount = $request->total[$i];
                $productionDetails->status = $request->status[$i];
                $productionDetails->created_by = Auth::User()->id;
                $productionDetails->updated_by = Auth::User()->id;
                $productionDetails->save();

                $godownstockDetails = new GodownStockDetail();
                $godownstockDetails->voucher_no = $production->voucher_no;
                $godownstockDetails->transaction_id = $production->id;
                $godownstockDetails->inward_gatepass_id = 0;
                $godownstockDetails->date = $production->date;
                $godownstockDetails->type = 'PRODUCTION ONE';
                $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
                // $godownstockDetails->party_id = $request->party_id;
                $godownstockDetails->product_id = $request->product_id[$i];
                // $godownstockDetails->qty_out = $request->qty[$i];
                $godownstockDetails->qty_out = $request->showqty[$i];
                $godownstockDetails->qty_in =0;
                $godownstockDetails->rate = $request->rate[$i];
                $godownstockDetails->amount = $request->total[$i];
                $godownstockDetails->remarks = null;
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->save();
            }
            $recipe_products = new GodownStockDetail();
            $recipe_products->voucher_no = $production->voucher_no;
            $recipe_products->transaction_id = $production->id;
            $recipe_products->inward_gatepass_id = 0;
            $recipe_products->date = $production->date;
            $recipe_products->type = 'PRODUCTION ONE';
            $recipe_products->warehouse_id = Auth::User()->warehouse_id;
            // $godownstockDetails->party_id = $request->party_id;
            $recipe_products->product_id = $request->product_id2;
            $recipe_products->qty_in = $request->total_qty;
            $recipe_products->qty_out =0;
            $recipe_products->rate = $request->actual_total_rate;
            $recipe_products->amount = $request->total_amount;
            $recipe_products->remarks = null;
            $recipe_products->created_by = Auth::User()->id;
            $recipe_products->save();

            return redirect()->back()->with('flash_message', 'Production Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PRODUCTON 1')
            ->where('right_name', 'ADD')
            ->first();
// return $request;
            $code = Production::where('warehouse_id', Auth::User()->warehouse_id)->orderBy('id', 'desc')->first();
            $codes = 1;
            if ($code) {
                $codes = $code->voucher_no + 1;
            }

            $data = $request->all();
            $data['unit_id'] = 1;
            $data['p_status'] = 0;
            $data['p_type'] = "PRODUCTION ONE";
            $data['product_id'] = $request->product_id2;
            $data['created_by'] = Auth::User()->id;
            $data['voucher_no'] = $codes;
            $data['warehouse_id'] = Auth::User()->warehouse_id;
            $data['batchNo'] =  date("ymd", strtotime($request->date)).$request->voucher_no;
            // $data['batchNo'] =  date("ymd", strtotime($request->date)).$request->machine_id.$request->shift_id.$request->format_id.$request->operator_id;
            $production = Production::create($data);

            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $productionDetails = new ProductionDetails();
                $productionDetails->production_id = $production['id'];
                $productionDetails->product_id = $request->product_id[$i];
                $productionDetails->unit_id = 1;
                $productionDetails->voucher_no =  $production['voucher_no'];
                $productionDetails->warehouse_id = Auth::User()->warehouse_id;
                $productionDetails->recipe_qty = $request->recipe_qty[$i];
                $productionDetails->quantity = $request->qty[$i];
                $productionDetails->showqty = $request->showqty[$i];
                $productionDetails->rate = $request->rate[$i];
                $productionDetails->amount = $request->total[$i];
                $productionDetails->status = $request->status[$i];
                $productionDetails->created_by = Auth::User()->id;
                $productionDetails->save();

                $godownstockDetails = new GodownStockDetail();
                $godownstockDetails->voucher_no = $production->voucher_no;
                $godownstockDetails->transaction_id = $production->id;
                $godownstockDetails->inward_gatepass_id = 0;
                $godownstockDetails->date = $production->date;
                $godownstockDetails->type = 'PRODUCTION ONE';
                $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
                // $godownstockDetails->party_id = $request->party_id;
                $godownstockDetails->product_id = $request->product_id[$i];
                // $godownstockDetails->qty_out = $request->qty[$i];
                $godownstockDetails->qty_out = $request->showqty[$i];
                $godownstockDetails->qty_in = 0;
                $godownstockDetails->rate = $request->rate[$i];
                $godownstockDetails->amount = $request->total[$i];
                $godownstockDetails->remarks = null;
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->save();
            }
            $recipe_products = new GodownStockDetail();
            $recipe_products->voucher_no = $production->voucher_no;
            $recipe_products->transaction_id = $production->id;
            $recipe_products->inward_gatepass_id = 0;
            $recipe_products->date = $production->date;
            $recipe_products->type = 'PRODUCTION ONE';
            $recipe_products->warehouse_id = Auth::User()->warehouse_id;
            // $godownstockDetails->party_id = $request->party_id;
            $recipe_products->product_id = $request->product_id2;
            $recipe_products->qty_in = $request->total_qty;
            $recipe_products->qty_out = 0;
            $recipe_products->rate = $request->actual_total_rate;
            $recipe_products->amount = $request->total_amount;
            $recipe_products->remarks = null;
            $recipe_products->created_by = Auth::User()->id;
            $recipe_products->save();

            return redirect()->back()->with('flash_message', 'Production Added Successfully!');
        }
    }

    public function RecipeProducts(Request $request)
    {
        
        $edit = RecipeCreation::where('id', $request->RecipeID)
        // ->where('warehouse_id', Auth::User()->warehouse_id)
        ->first();
        //  return $edit;
        if ($edit) {
            $edit = RecipeCreationDetails::with('product:id,product_name,uom,code')
                ->with(['recipe_creation' => function ($query) {
                    $query->with('product');
                }])
                ->where('recipe_creation_id', $edit->id)
                // ->where('warehouse_id', Auth::User()->warehouse_id)
                ->get();

            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function RecipeNames(Request $request){
        // return $request;
        $edit = RecipeCreation::where('product_id', $request->ProductID)
        ->get();
        if ($edit) {

            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function editData(Request $request)
    {
        $edit = Production::where('voucher_no', $request->voucher_no)->where('warehouse_id', Auth::User()->warehouse_id)->first();

        if ($edit) {
            $edit = ProductionDetails::with('product:id,product_name,uom,uom_id,code')
                ->with(['production' => function ($query) {
                    $query->with('product');
                    $query->with('recipe_creation');
                }])
                ->where('production_id', $edit->id)
                ->where('warehouse_id', Auth::User()->warehouse_id)
                ->get();

            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        $productionVoucherNo = Production::where('voucher_no', '<', $request->voucher_no)
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->where('p_type', 'PRODUCTION ONE')
        ->max('voucher_no');
        $production = Production::where('voucher_no', $productionVoucherNo)
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->where('p_type', 'PRODUCTION ONE')
        ->first();

        if ($production) {
            $data = ProductionDetails::with('product:id,product_name,uom,uom_id,code')
                ->with(['production' => function ($query) {
                    $query->with('product');
                    $query->with('recipe_creation');
                }])
                ->where('production_id', $production->id)
                // ->where('warehouse_id', Auth::User()->warehouse_id)
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $productionVoucherNo = Production::where('voucher_no', '>', $request->voucher_no)
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->where('p_type', 'PRODUCTION ONE')
        ->min('voucher_no');
        $production = Production::where('voucher_no', $productionVoucherNo)
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->where('p_type', 'PRODUCTION ONE')
        ->first();

        if ($production) {
            $data = ProductionDetails::with('product:id,product_name,uom,uom_id,code')
                ->with(['production' => function ($query) {
                    $query->with('product');
                    $query->with('recipe_creation');
                }])
                ->where('production_id', $production->id)
                ->where('warehouse_id', Auth::User()->warehouse_id)
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function DeleteVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PRODUCTON 1')
            ->where('right_name', 'DELETE')
            ->first();
        $production = Production::where('voucher_no', $request->delete_voucher_no)->where('warehouse_id', Auth::User()->warehouse_id)->first();
        if ($production) {
            Production::where('id', $production->id)->delete();
            ProductionDetails::where('production_id', $production->id)->delete();
            GodownStockDetail::where('transaction_id', $production->id)->where('type', 'PRODUCTION ONE')->delete();
            return redirect()->back()->with('flash_message', 'Production Voucher Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }
    public function PrintVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PRODUCTON 1')
            ->where('right_name', 'PRINT')
            ->first();
        File::cleanDirectory(base_path() . '/upload/production');
        $voucher_no = $request->voucher_no;
        $production = Production::where('voucher_no', $voucher_no)
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->where('p_type', 'PRODUCTION ONE')
        ->first();
        if ($production) {
            $productiondetail = ProductionDetails::with(['production' => function ($qry) {
                $qry->with('product:id,product_name', 'unit:id,uom','warehouse:id,name', 'machine:id,machine_name',
            'shift:id,shift_name', 'generated_by:id,name');
            }])
                ->with('product:id,product_name', 'unit:id,uom')
                ->where('production_id', $production->id)
                ->where('warehouse_id', Auth::User()->warehouse_id)
                ->orderBy('id', 'asc')
                ->get();

            $pdf = PDF::loadView('production.production-one.invoice', compact('productiondetail'));
            $fileName =  'production' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/production/' . $fileName));
            return $fileName;
        } else {
            return false;
        }
    }

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
                  $data = GodownStockDetail::join('products', 'products.id', '=', 'godown_stock_details.product_id')
                //   with('product')
                //   ->with(['production' => function($query){
                    //   $query->with('recipe');
                    //   $query->with('machine');
                    //   $query->with('shift');
                    //   $query->with('forman');
                    //   $query->with('operator');
                    //   $query->with('generated_by');
                //   }])
                  ->select(
                    'products.code',
                    'products.product_name',
                    DB::raw('SUM(godown_stock_details.qty_in) as qty_in'),
                )
                  // ->with('recipe')
                  ->where(function ($query) use ($warehouse_id) {
                      if ($warehouse_id == 0) {
                          $query->orderBy('warehouse_id')->get();
                      } else {
                          $query->where('warehouse_id', $warehouse_id)->get();
                      }
                      $query->with('warehouse:id,name');
                  })
                  ->where(function ($query) use ($product_id) {
                      if ($product_id != 0) {
                          $query->where('godown_stock_details.product_id', $product_id);
                      }
                  })
                  ->where('type', 'PRODUCTION ONE')
                  ->where('qty_in', '>', 0)
                  ->whereDate('date', '>=', $fromDate)
                  ->whereDate('date', '<=', $toDate)
                  ->groupBy('product_id')
                  ->orderBy('date')
                  ->get();

                  //eeee
  
  
                      return $response = [
                          'production' => $data
                      ];
                  return Response::json($response);
            } 
            else if ($report_type == 'detailed') {
                //  return "03";
                $data = GodownStockDetail::with('product:id,product_name,uom,packing,code')
                ->with(['production' => function($query){
                    $query->with('recipe');
                    $query->with('machine');
                    $query->with('shift');
                    $query->with('forman');
                    $query->with('operator');
                    $query->with('generated_by');
                }])
                // ->with('recipe')
                ->where(function ($query) use ($warehouse_id) {
                    if ($warehouse_id == 0) {
                        $query->orderBy('warehouse_id')->get();
                    } else {
                        $query->where('warehouse_id', $warehouse_id)->get();
                    }
                    $query->with('warehouse:id,name');
                })
                ->where(function ($query) use ($product_id) {
                    if ($product_id != 0) {
                        $query->where('godown_stock_details.product_id', $product_id);
                    }
                })
                ->where('type', 'PRODUCTION ONE')
                ->where('qty_in', '>', 0)
                ->whereDate('date', '>=', $fromDate)
                ->whereDate('date', '<=', $toDate)
                ->orderBy('date')
                ->get();


                    return $response = [
                        'production' => $data
                    ];
                return Response::json($response);
            }
          
        }
        $products = Product::orderBy('product_name')->pluck('product_name', 'id')->prepend('Select Product', '0');
        $warehouse = Warehouse::orderBy('name')->pluck('name', 'id')->prepend('All', '0');
        $SingleWarehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id')->first();
        return view('production.report.index', compact('products', 'warehouse', 'SingleWarehouse'));
    }
}