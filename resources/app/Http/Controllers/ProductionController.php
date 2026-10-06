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
use App\Models\BatchStock;
use App\Models\Color;
use App\Models\VoucherRights;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ProductionController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $code = Production::where('warehouse_id', Auth::User()->warehouse_id)
        ->where('p_type', 'PRODUCTION')
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
            
        $products = Product::select(DB::raw('CONCAT(`id`, "_", `code`, "_", `product_name`, "_", `uom`,"_", `product_price`,"_", `product_cost`) AS `id`,`product_name`'))
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->where('product_type', 'Normal')
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');
            $units = UOM::OrderBy('id', 'asc')->pluck('uom', 'id')->prepend('Select Unit', '');
        $machines = Machine::OrderBy('machine_name', 'asc')->pluck('machine_name', 'id')->prepend('Select Machine', '');
        $shift = Shift::OrderBy('shift_name', 'asc')->pluck('shift_name', 'id')->prepend('Select Shift', '');
        $color = Color::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('Select Color', '');
       
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

        return view('production.index', compact('codes', 'recipe_products', 'products', 'units', 'machines', 'shift', 'forman', 'operator', 'recipeName', 'color'));
    }

    public function store(Request $request)
    {
        // return $request;
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
        if (!isset($request->color_id)) {
            return redirect()->back()->with('failure_message', 'Color Field is Required');
        }
        if (!isset($request->machine_id)) {
            return redirect()->back()->with('failure_message', 'Machine Field is Required');
        }
        if (!isset($request->shift_id)) {
            return redirect()->back()->with('failure_message', 'Shift Field is Required');
        }
        if (!isset($request->forman_id)) {
            return redirect()->back()->with('failure_message', 'Forman Field is Required');
        }
        if (!isset($request->operator_id)) {
            return redirect()->back()->with('failure_message', 'Operator Field is Required');
        }
        if (!isset($request->product_id)) {
            return redirect()->back()->with('failure_message', 'Product Field is Required');
        }
        if (!isset($request->rate)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }

        if ($request->update_voucher_id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PRODUCTON')
            ->where('right_name', 'EDIT')
            ->first();
            $data = $request->all();
            $data['unit_id'] = 1;
            $data['p_status'] = 0;
            $data['p_type'] = "PRODUCTION";
            $data['product_id'] = $request->product_id2;
            $data['updated_by'] = Auth::User()->id;
            $production = Production::find($request->update_voucher_id);
            $batchStock = BatchStock::where('transaction_id', $production->id)
            ->where('p_type', 'PRODUCTION')
            ->where('p_status', '1')
            ->get();
            if (count($batchStock) >0) {
                // return "d";
                return redirect()->back()->with('access_granted', 'You cant update! Batch Stock Transferred of this Production!');
            }
            $production->update($data);
            // $machine = Machine::where('id', $request->machine_id)->first();
            // $shift = Shift::where('id', $request->shift_id)->first();
            // $forman = Party::where('id', $request->forman_id)->first('code');
            // $operator = Party::where('id', $request->operator_id)->first('code');
            // $data['batchNo'] =  date("ymd", strtotime($request->date)).$machine->machine_name.$shift->shift_name.$forman->code.$operator->code;
            $machine = Machine::where('id', $request->machine_id)->first();
            $shift = Shift::where('id', $request->shift_id)->first();
            $forman = Party::where('id', $request->forman_id)->first('code');
            $operator = Party::where('id', $request->operator_id)->first('code');
            
            // $data['batchNo'] =  date("ymd", strtotime($request->date)).$machine->machine_name.$shift->shift_name.$forman->code.$operator->code;
            $data['batchNo'] =  date("ymd", strtotime($data['date'])).$machine->machine_name.$shift->shift_name.$forman->code.$operator->code.$production->voucher_no;
            // $data['batchNo'] =  date("ymd", strtotime($request->date)).$request->machine_id.$request->shift_id.$request->format_id.$request->operator_id;
            // return $data;
            ProductionDetails::where('production_id', $request->update_voucher_id)->where('p_type', 'PRODUCTION')->delete();
            GodownStockDetail::where('transaction_id', $production->id)->where('type', 'PRODUCTION')->delete();
            BatchStock::where('transaction_id', $production->id)->where('p_type', 'PRODUCTION')->delete();
            $data['transaction_id'] = $production->id;
            $data['from_warehouse_id'] = Auth::User()->warehouse_id;
            $data['warehouse_id'] = Auth::User()->warehouse_id;
            $data['created_by'] = $production->created_by;
            $data['out_qty'] = 0;
            $data['balance_weight'] = $request->total_qty;
            // return $data;
            BatchStock::create($data);
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
                $productionDetails->p_type = "PRODUCTION";
                $productionDetails->created_by = $production['created_by'];
                $productionDetails->updated_by = Auth::User()->id;
                $productionDetails->save();

                $godownstockDetails = new GodownStockDetail();
                $godownstockDetails->voucher_no = $production->voucher_no;
                $godownstockDetails->transaction_id = $production->id;
                $godownstockDetails->inward_gatepass_id = 0;
                $godownstockDetails->date = $production->date;
                $godownstockDetails->type = 'PRODUCTION';
                $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
                // $godownstockDetails->party_id = $request->party_id;
                $godownstockDetails->product_id = $request->product_id[$i];
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
            $recipe_products->type = 'PRODUCTION';
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
            ->where('voucher_name', 'PRODUCTON')
            ->where('right_name', 'ADD')
            ->first();
            $code = Production::where('warehouse_id', Auth::User()->warehouse_id)
            ->where('p_type', 'PRODUCTION')
            ->orderBy('id', 'desc')->first();
            $codes = 1;
            if ($code) {
                $codes = $code->voucher_no + 1;
            }
            $data = $request->all();
            $data['unit_id'] = 1;
            $data['p_status'] = 0;
            $data['p_type'] = "PRODUCTION";
            // $data['date'] = $request->date;
            $data['product_id'] = $request->product_id2;
            $data['created_by'] = Auth::User()->id;
            $data['voucher_no'] = $codes;
            $data['warehouse_id'] = Auth::User()->warehouse_id;
            $machine = Machine::where('id', $request->machine_id)->first();
            $shift = Shift::where('id', $request->shift_id)->first();
            $forman = Party::where('id', $request->forman_id)->first('code');
            $operator = Party::where('id', $request->operator_id)->first('code');
            
            // $data['batchNo'] =  date("ymd", strtotime($request->date)).$machine->machine_name.$shift->shift_name.$forman->code.$operator->code;
            $data['batchNo'] =  date("ymd", strtotime($data['date'])).$machine->machine_name.$shift->shift_name.$forman->code.$operator->code.$codes;
            // $data['batchNo'] =  date("ymd", strtotime($request->date)).$request->machine_id.$request->shift_id.$request->format_id.$request->operator_id;
            // return $data;
           $production = Production::create($data);
           
           $data['transaction_id'] = $production->id;
           $data['from_warehouse_id'] = Auth::User()->warehouse_id;
           $data['warehouse_id'] = Auth::User()->warehouse_id;
           $data['out_qty'] = 0;
           $data['balance_weight'] = $request->total_qty;
           BatchStock::create($data);
            // $production->status = 0;
            // $production->save();
        //    return "d";
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
                $productionDetails->p_type = "PRODUCTION";
                $productionDetails->created_by = Auth::User()->id;
                $productionDetails->save();

                $godownstockDetails = new GodownStockDetail();
                $godownstockDetails->voucher_no = $production->voucher_no;
                $godownstockDetails->transaction_id = $production->id;
                $godownstockDetails->inward_gatepass_id = 0;
                $godownstockDetails->date = $request->date;
                $godownstockDetails->type = 'PRODUCTION';
                $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
                // $godownstockDetails->party_id = $request->party_id;
                $godownstockDetails->product_id = $request->product_id[$i];
                $godownstockDetails->qty_out = $request->showqty[$i];
                $godownstockDetails->qty_in = 0;
                $godownstockDetails->rate = $request->rate[$i];
                $godownstockDetails->amount = $request->total[$i];
                $godownstockDetails->remarks = null;
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->save();
            }
            // return "d";
            $recipe_products = new GodownStockDetail();
            $recipe_products->voucher_no = $production->voucher_no;
            $recipe_products->transaction_id = $production->id;
            $recipe_products->inward_gatepass_id = 0;
            $recipe_products->date = $request->date;
            $recipe_products->type = 'PRODUCTION';
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
        $edit = RecipeCreation::where('product_id', $request->ProductID)->get();
        if ($edit) {
            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function editData(Request $request)
    {
        $edit = Production::where('voucher_no', $request->voucher_no)
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->where('p_type', 'PRODUCTION')
        ->first();

        if ($edit) {
            $edit = ProductionDetails::with('product:id,product_name,uom,uom_id,code')
                ->with(['production' => function ($query) {
                    $query->with('product');
                    $query->with('recipe_creation');
                }])
                ->where('p_type', 'PRODUCTION')
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
        ->where('p_type', 'PRODUCTION')
        ->max('voucher_no');
        $production = Production::where('voucher_no', $productionVoucherNo)
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->where('p_type', 'PRODUCTION')
        ->first();

        if ($production) {
            $data = ProductionDetails::with('product:id,product_name,uom,uom_id,code')
                ->with(['production' => function ($query) {
                    $query->with('product');
                    $query->with('recipe_creation');
                }])
                ->where('p_type', 'PRODUCTION')
                ->where('production_id', $production->id)
                ->where('warehouse_id', Auth::User()->warehouse_id)
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
        ->where('p_type', 'PRODUCTION')
        ->min('voucher_no');
        $production = Production::where('voucher_no', $productionVoucherNo)
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->where('p_type', 'PRODUCTION')
        ->first();

        if ($production) {
            $data = ProductionDetails::with('product:id,product_name,uom,uom_id,code')
                ->with(['production' => function ($query) {
                    $query->with('product');
                    $query->with('recipe_creation');
                }])
                ->where('p_type', 'PRODUCTION')
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
            ->where('voucher_name', 'PRODUCTON')
            ->where('right_name', 'DELETE')
            ->first();
         $production = Production::where('voucher_no', $request->delete_voucher_no)
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->where('p_type', 'PRODUCTION')
        ->first();
         $batchStock = BatchStock::where('transaction_id', $production->id)
        ->where('p_type', 'PRODUCTION')
        ->where('p_status', '1')
        ->get();
        if (count($batchStock) >0) {
            // return "d";
            return redirect()->back()->with('access_granted', 'Batch Stock Transferred of this Production!');
        } else {
            if ($production) {
                Production::where('id', $production->id)->where('p_type', 'PRODUCTION')->delete();
            // Production::findOrFail($production->id)->delete();
                ProductionDetails::where('production_id', $production->id)->where('p_type', 'PRODUCTION')->delete();
                GodownStockDetail::where('transaction_id', $production->id)->delete();
                BatchStock::where('transaction_id', $production->id)->where('p_type', 'PRODUCTION')->delete();
            return redirect()->back()->with('flash_message', 'Production Voucher Deleted Successfully!');
            }else{
                return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
            }
        }

        abort(500);
    }

   
    public function PrintVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PRODUCTON')
            ->where('right_name', 'PRINT')
            ->first();
        File::cleanDirectory(base_path() . '/upload/production');
        $voucher_no = $request->voucher_no;
        $production = Production::where('voucher_no', $voucher_no)
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->where('p_type', 'PRODUCTION')
        ->first();
        if ($production) {
            $productiondetail = ProductionDetails::with(['production' => function ($qry) {
                $qry->with('product:id,product_name', 'unit:id,uom','warehouse:id,name', 'machine:id,machine_name',
            'shift:id,shift_name', 'generated_by:id,name');
            }])
                ->with('product:id,product_name', 'unit:id,uom')
                ->where('p_type', 'PRODUCTION')
                ->where('production_id', $production->id)
                ->where('warehouse_id', Auth::User()->warehouse_id)
                ->orderBy('id', 'asc')
                ->get();

            $pdf = PDF::loadView('production.invoice', compact('productiondetail'));
            $fileName =  'production' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/production/' . $fileName));
            return $fileName;
        } else {
            return false;
        }
    }

  
}
