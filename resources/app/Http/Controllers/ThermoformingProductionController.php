<?php

namespace App\Http\Controllers;

use App\Models\GodownStockDetail;
use App\Models\ThermoformingProduction;
use Illuminate\Http\Request;
use App\Models\Machine;
use App\Models\Shift;
use App\Models\Product;
use App\Models\Production;
use App\Models\User;
use App\Models\Role;
use App\Models\Warehouse;
use App\Models\RightsLevel1;
use App\Models\RightsLevel2;
use App\Models\RightsLevel3;
use App\Models\MenuRights;
use App\Models\Party;
use App\Models\RightNames;
use App\Models\VoucherNames;
use App\Models\VoucherRights;
use App\Models\ProductMapping;
use App\Models\BatchStock;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use DB;
class ThermoformingProductionController extends Controller
{
  
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $code = ThermoformingProduction::where('warehouse_id', Auth::User()->warehouse_id)->orderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->voucher_no + 1;
        }
        //  $warehouses = Warehouse::pluck('name', 'id')->prepend('Select Godown', '');
        // $product = Product::where('product_type', 'Finish')->orderBy('product_name')->pluck('product_name', 'id');
        //    $RoleOnly = DB::table('productions')
        //     ->join('products','products.id','=','productions.product_id')
        //     ->select(DB::raw("productions.id,CONCAT(productions.batchNo, '-', productions.thickness, '-', productions.width, '-', productions.color_id, '-', productions.total_qty) AS  voucher_no")) 
        //     ->where('productions.p_status', '0')
        //     ->where('productions.p_type', 'PRODUCTION')
        //     ->pluck('voucher_no','delivery_challans.id')
        //     ->prepend('Select Product', '');
           $batchStock = DB::table('batch_stocks')
            ->join('colors','colors.id','=','batch_stocks.color_id')
            ->select(DB::raw("batch_stocks.id,CONCAT(batch_stocks.batchNo, '-', batch_stocks.thickness, '-', batch_stocks.width, '-', colors.name, '-', batch_stocks.balance_weight) AS  voucher_no")) 
            ->where('batch_stocks.warehouse_id', Auth::User()->warehouse_id)
             ->where('batch_stocks.total_qty', '>', '0')
             ->where('batch_stocks.p_status', '0')
            ->pluck('voucher_no','batch_stocks.id')
            ->prepend('Select Product', '');

            $WarehouseID = Auth::User()->warehouse_id;
            $mapping = ProductMapping::where('warehouse_id', $WarehouseID)->first('product_warehouse_id');
            if(isset($mapping)){
                  $mapproduct = $mapping->product_warehouse_id;
                   if($mapproduct > 0){
                    //    return $warehouseproducts = Product::where('warehouse_id', $mapproduct)
                    //    ->orderBy('code', 'asc')
                    //    ->get();
                    // return "ddd";
                   $product = Product::select(DB::raw('CONCAT(`id`, "_", `dye_pcs`) AS `id`, CONCAT(`code`,"-",`product_name`) `product_name`'))
                        // ->where('warehouse_id', Auth::User()->warehouse_id)
                        // ->where('product_type', 'Finish')
                        ->where('warehouse_id', $mapproduct)
                        ->OrderBy('product_name', 'asc')
                        ->pluck('product_name', 'id')
                        ->prepend('Select Product', '');
                   }
                   else{
                    $product = Product::select(DB::raw('CONCAT(`id`, "_", `dye_pcs`) AS `id`, CONCAT(`code`,"-",`product_name`) `product_name`'))
                    // ->where('warehouse_id', Auth::User()->warehouse_id)
                    ->where('product_type', 'Finish')
                    // ->where('warehouse_id', $WarehouseID)
                    ->OrderBy('product_name', 'asc')
                    ->pluck('product_name', 'id')
                    ->prepend('Select Product', '');
                   }
           }else{
               
            $product = Product::select(DB::raw('CONCAT(`id`, "_", `dye_pcs`) AS `id`, CONCAT(`code`,"-",`product_name`) `product_name`'))
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->where('product_type', 'Finish')
            // ->where('warehouse_id', $WarehouseID)
            ->OrderBy('product_name', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');
           }
        $machines = Machine::OrderBy('machine_name', 'asc')->pluck('machine_name', 'id')->prepend('Select Machine', '');
        $shift = Shift::OrderBy('shift_name', 'asc')->pluck('shift_name', 'id')->prepend('Select Shift', '');
        // $forman = Party::where('designation_id', 1)->OrderBy('party_name', 'asc')->pluck('party_name', 'id')->prepend('Select Forman', '');
        $pressman = DB::table('parties')
        ->select(DB::raw("id,CONCAT(code, '-', party_name) AS  voucher_no")) 
        ->where('designation_id', 3)
        ->orderBy('party_name', 'asc')
        ->pluck('voucher_no','id')
        ->prepend('Select Pressman', '');
        $operator = DB::table('parties')
        ->select(DB::raw("id,CONCAT(code, '-', party_name) AS  voucher_no")) 
        ->where('designation_id', 2)
        ->orderBy('party_name', 'asc')
        ->pluck('voucher_no','id')
        ->prepend('Select Operator', '');
        return view('production.thermoforming.index', compact('codes', 'product', 'pressman', 'shift', 'machines', 'operator', 'batchStock'));
    }

    public function RoleData(Request $request){
        // return $request;
        // $production = Production::where('id', $request->product_id)->first();
        $production = BatchStock::with('color')->where('id', $request->Batchid)->first();
        return $production;
    }

    public function LoadPreviousData(Request $request)
    {


        $id = ThermoformingProduction::where('voucher_no', '<', $request->voucher_no)->max('id');
        // $id = RequestGenerate::where('bill_no', '<', $request->voucher_no) ->whereType('Request Generate')->where('warehouse_id', Auth::User()->warehouse_id)->max('id');
        $production = ThermoformingProduction::with('product')->with('roll_production')
            ->whereId($id)
            ->first();

        if ($production) {
            return Response::json(['production' => $production]);
        } else {
            return Response::json(['production' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $id = ThermoformingProduction::where('voucher_no', '>', $request->voucher_no)->min('id');
        // $id = RequestGenerate::where('bill_no', '<', $request->voucher_no) ->whereType('Request Generate')->where('warehouse_id', Auth::User()->warehouse_id)->max('id');
        $production = ThermoformingProduction::with('product')->with('roll_production')
            ->whereId($id)
            ->first();

        if ($production) {
            return Response::json(['production' => $production]);
        } else {
            return Response::json(['production' => '']);
        }
    }

    public function editData(Request $request)
    {
        // return $request;
         $id = ThermoformingProduction::where('voucher_no', '=', $request->voucher_no)->first();
        // $id = RequestGenerate::where('bill_no', '<', $request->voucher_no) ->whereType('Request Generate')->where('warehouse_id', Auth::User()->warehouse_id)->max('id');
        $production = ThermoformingProduction::with('product')->with('roll_production')
            // ->whereId($id)
            ->where('voucher_no', $id->voucher_no)
            ->first();

        if ($production) {
            return Response::json(['production' => $production]);
        } else {
            return Response::json(['production' => '']);
        }
    }

    public function PrintVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'THERMOFORMING PRODUCTION')
        ->where('right_name', 'PRINT')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }
        File::cleanDirectory(base_path() . '/upload/request-generate');
        $voucher_no = $request->voucher_no;
        $data = ThermoformingProduction::where('voucher_no', '=', $request->voucher_no)->first();
        if ($data) {
        // $id = RequestGenerate::where('bill_no', '<', $request->voucher_no) ->whereType('Request Generate')->where('warehouse_id', Auth::User()->warehouse_id)->max('id');
        $production = ThermoformingProduction::with('product')->with(['roll_production' => function($query){
            $query->with('product');
        }])
            ->with('shift')->with('operator')->with('machine')->with('pressman')
            // ->whereId($id)
            ->where('voucher_no', $data->voucher_no)
            ->first();

            $pdf = PDF::loadView('production.thermoforming.invoice', compact('production'));
            $fileName =  'Thermoforming' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/production/thermoforming/' . $fileName));
            return $fileName;
        }else{
            return false;
        }
    }



    public function destroy(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'THERMOFORMING PRODUCTION')
        ->where('right_name', 'DELETE')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }
       $voucher_no = $request->delete_voucher_id;
        $RequestGenerate = ThermoformingProduction::where('voucher_no', $voucher_no)->first();
        if ($RequestGenerate) {
            ThermoformingProduction::where('voucher_no', $voucher_no)->delete();
            GodownStockDetail::where('transaction_id', $request->delete_voucher_id)
            ->where('type', 'THERMOFORMING PRODUCTION')->delete();
            // ConsignmentDetail::where('consignment_no', $bill_no)->delete();
            return redirect()->back()->with('flash_message', 'Thermoforming Production Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return "ddd";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $Validator = Validator::make($request->all(), [
            'voucher_no' => 'required',
            'date' => 'required',
            'total_sheets' => 'required',
            'pressman_no' => 'required'
        ], [
            'net_sheets.required' => 'The Net Sheets field is required.',
            'net_sku.required' => 'The Net SKU field is required.'
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
        if (!isset($request->pressman_no)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }
        // return $request;
        if ($request->idd != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'THERMOFORMING PRODUCTION')
            ->where('right_name', 'EDIT')
            ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            $data = $request->all();
            // $data['unit_id'] = 1;
            // $data['product_id'] = $request->product_id2;
            $data['updated_by'] = Auth::User()->id;
            $data['batch_id'] = $request->consumed_product_id;
            // $data['batchNo'] =  date("ymd", strtotime($request->date)).$request->machine_id.$request->shift_id.$request->format_id.$request->operator_id;
            $production = ThermoformingProduction::find($request->idd);
            $production->update($data);

            GodownStockDetail::where('transaction_id', $request->idd)
            ->where('type', 'THERMOFORMING PRODUCTION')->delete();

            $godownstockDetails = new GodownStockDetail();
            $godownstockDetails->voucher_no = $production['voucher_no'];
            $godownstockDetails->transaction_id = $production->id;
            // $godownstockDetails->inward_gatepass_id =1;
            $godownstockDetails->date = $production->date;
            $godownstockDetails->type = 'THERMOFORMING PRODUCTION';
            $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
            // $godownstockDetails->party_id = $request->party_id;
            $godownstockDetails->product_id = $production->consumed_product_id;
            $godownstockDetails->qty_in = 0;
            $godownstockDetails->qty_out = $production->consumed;
            $godownstockDetails->demand_qty = $production->consumed;
            $godownstockDetails->remarks = $production->remarks;
            $godownstockDetails->created_by = Auth::User()->id;
            $godownstockDetails->sale_rate = 0;
            $godownstockDetails->save();

            $godownstockDetails = new GodownStockDetail();
            $godownstockDetails->voucher_no = $production['voucher_no'];
            $godownstockDetails->transaction_id = $production->id;
            // $godownstockDetails->inward_gatepass_id =1;
            $godownstockDetails->date = $production->date;
            $godownstockDetails->type = 'THERMOFORMING PRODUCTION';
            $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
            // $godownstockDetails->party_id = $request->party_id;
            $godownstockDetails->product_id = $production->product_id;
            $godownstockDetails->qty_in = $production->net_sku;
            $godownstockDetails->qty_out = 0;
            $godownstockDetails->demand_qty = $production->consumed;
            $godownstockDetails->remarks = $production->remarks;
            $godownstockDetails->created_by = Auth::User()->id;
            $godownstockDetails->sale_rate = 0;
            $godownstockDetails->save();





            return redirect()->back()->with('flash_message', 'Production Updated Successfully!');
        } else {
            // return "save0";
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'THERMOFORMING PRODUCTION')
            ->where('right_name', 'ADD')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
            $code = ThermoformingProduction::where('warehouse_id', Auth::User()->warehouse_id)->orderBy('id', 'desc')->first();
            $codes = 1;
            if ($code) {
                $codes = $code->voucher_no + 1;
            }
            $data = $request->all();

            $data['created_by'] = Auth::User()->id;
            $data['voucher_no'] = $codes;
            $data['warehouse_id'] = Auth::User()->warehouse_id;
            // $data['warehouse_id'] = Auth::User()->warehouse_id;
            $data['batch_id'] = $request->consumed_product_id;
            //  return $data;
            // return "d";
            // return $productionStatus = Production::where('id', $request->consumed_product_id)->first();
            // $productionStatus->p_status = 1;
            // $productionStatus->save();

            //Reduce Stock
            $batchBalance = BatchStock::where('id', $request->consumed_product_id)->first();
            $batchBalance->balance_weight = $request->balance_weight;
            $batchBalance->save();
            //Hide from list
            if($request->balance_weight == 0){
                $batchBalance = BatchStock::where('id', $request->consumed_product_id)->first();
                $batchBalance->p_status = 1;
                $batchBalance->save(); 
            }

           $production = ThermoformingProduction::create($data);

            // $godownstockDetails = new GodownStockDetail();
            // $godownstockDetails->voucher_no = $production['voucher_no'];
            // $godownstockDetails->transaction_id = $production->id;
            // // $godownstockDetails->inward_gatepass_id =1;
            // $godownstockDetails->date = $production->date;
            // $godownstockDetails->type = 'THERMOFORMING PRODUCTION';
            // $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
            // // $godownstockDetails->party_id = $request->party_id;
            // $godownstockDetails->product_id = $production->consumed_product_id;
            // $godownstockDetails->qty_in = 0;
            // $godownstockDetails->qty_out = $production->consumed;
            // $godownstockDetails->demand_qty = $production->consumed;
            // $godownstockDetails->remarks = $production->remarks;
            // $godownstockDetails->created_by = Auth::User()->id;
            // $godownstockDetails->sale_rate = 0;
            // $godownstockDetails->save();

            $godownstockDetails = new GodownStockDetail();
            $godownstockDetails->voucher_no = $production['voucher_no'];
            $godownstockDetails->transaction_id = $production->id;
            // $godownstockDetails->inward_gatepass_id =1;
            $godownstockDetails->date = $production->date;
            $godownstockDetails->type = 'THERMOFORMING PRODUCTION';
            $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
            // $godownstockDetails->party_id = $request->party_id;
            $godownstockDetails->product_id = $production->product_id;
            $godownstockDetails->qty_in = $production->net_sku;
            $godownstockDetails->qty_out = 0;
            $godownstockDetails->demand_qty = $production->consumed;
            $godownstockDetails->remarks = $production->remarks;
            $godownstockDetails->created_by = Auth::User()->id;
            $godownstockDetails->sale_rate = 0;
            $godownstockDetails->save();

            return redirect()->back()->with('flash_message', 'Production Added Successfully!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ThermoformingProduction  $thermoformingProduction
     * @return \Illuminate\Http\Response
     */
    public function show(ThermoformingProduction $thermoformingProduction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ThermoformingProduction  $thermoformingProduction
     * @return \Illuminate\Http\Response
     */
    public function edit(ThermoformingProduction $thermoformingProduction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ThermoformingProduction  $thermoformingProduction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ThermoformingProduction $thermoformingProduction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ThermoformingProduction  $thermoformingProduction
     * @return \Illuminate\Http\Response
     */
    // public function destroy(ThermoformingProduction $thermoformingProduction)
    // {
    //     //
    // }
}
