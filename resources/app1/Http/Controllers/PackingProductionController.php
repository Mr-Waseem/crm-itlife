<?php

namespace App\Http\Controllers;

use App\Models\PackingProduction;
use App\Models\SlittingProduction;
use App\Models\SlittingProductionDetail;
use App\Models\OpeningPetRoll;
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
use App\Models\GodownStock;
use App\Models\Departments;
use App\Models\PackingProductionDetails;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use DB;

class PackingProductionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
       $code = PackingProduction::where('warehouse_id', Auth::user()->warehouse_id)->orderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->voucher_no + 1;
        }
            $product = Product::select(DB::raw('CONCAT(`id`, "_", `product_name`, "_", `packing`) AS `id`, CONCAT(`code`,"-",`product_name`) `product_name`'))
            ->where('product_type', 'Finish')
            ->OrderBy('product_name', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');
            // $packing = DB::table('parties')
            //     ->select(DB::raw("id,CONCAT(id, '-', party_name) AS  voucher_no")) 
            //     // ->select(DB::raw('id,CONCAT(`id`, "_", `party_name`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
            //     ->where('designation_id', 4)
            //     ->orderBy('party_name', 'asc')
            //     ->pluck('voucher_no','id')
            //     ->prepend('Select Employee', '');
            $packing = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `code`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
            ->where('designation_id', 4)
            ->orderBy('party_name', 'asc')
            ->pluck('party_name','id')
            ->prepend('Select Employee', '');

            $ThermoProduction = ThermoformingProduction::join('productions','productions.id','=','thermoforming_productions.batch_id')
            ->pluck('productions.batchNo','thermoforming_productions.id')
            ->prepend('Select Batch',''); 
            return view('production.packing.index', compact('codes', 'product', 'ThermoProduction', 'packing'));

       
    }


    public function ThermoformingProduction(Request $request){
        // return $request;
        $data = ThermoformingProduction::with('product')->with('operator')->with('machine')->with('shift')->with('pressman')
        ->where('id', $request->product_id)->first();
        if ($data) {
            return Response::json(['data' => $data]);
        }
        else {
        return Response::json(['data' => '']);
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
        $Validator = Validator::make($request->all(), [
            'voucher_no' => 'required',
            'date' => 'required',
            'thermo_production_id' => 'required',
            'product_id' => 'required',
            'employee_id' => 'required',
            // 'qty1' => 'required',
        ], 
        [
            'thermo_production_id.required' => 'The Batch No field is required.',
            'product_id.required' => 'The Product field is required.',
            'employee_id.required' => 'The Employee field is required.',
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator);
        }
        // return $request;
        // if (!isset($request->voucher_no)) {
        //     return redirect()->back()->with('failure_message', 'Please Enter Voucher No');
        // }
        // if (!isset($request->product_id)) {
        //     return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        // }
        // if (!isset($request->pressman_no)) {
        //     return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        // }
        //  return $request;
        if ($request->update_voucher_id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PACKING PRODUCTION')
            ->where('right_name', 'EDIT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
             $data = $request->all();
            // $data['unit_id'] = 1;
            // $data['product_id'] = $request->product_id2;
            // $data['updated_by'] = Auth::User()->id;
            // $data['batch_id'] = $request->consumed_product_id;

            $data['updated_id'] = Auth::User()->id;
            // $data['voucher_no'] = $codes;
            $data['warehouse_id'] = Auth::User()->warehouse_id;
            $data['batch_id'] = $request->thermo_production_id;
            // $data['batchNo'] =  date("ymd", strtotime($request->date)).$request->machine_id.$request->shift_id.$request->format_id.$request->operator_id;
            $production = PackingProduction::find($request->update_voucher_id);
            $production->update($data);
            
            PackingProductionDetails::where('transaction_id', $request->update_voucher_id)
            // ->where('type', 'PACKING PRODUCTION')
            ->delete();
            GodownStockDetail::where('transaction_id', $request->update_voucher_id)
            ->where('type', 'PACKING PRODUCTION')->delete();
            // return "Dd";
            $count = count($request->employee_id);
           for ($i = 0; $i < $count; $i++) {
            $stockTransferDetails = new PackingProductionDetails();
               $stockTransferDetails->transaction_id = $production->id;
               $stockTransferDetails->date = $production->date;
               $stockTransferDetails->voucher_no = $production->voucher_no;
               $stockTransferDetails->product_id = $request->prod_id;
               $stockTransferDetails->warehouse_id = $production->warehouse_id;
               $stockTransferDetails->employee_id = $request->employee_id[$i];
               $stockTransferDetails->qty = $request->qty[$i];
               $stockTransferDetails->pcs =$request->pcs[$i];
               $stockTransferDetails->save();

               $stockTransferDetails = new GodownStockDetail();
               $stockTransferDetails->transaction_id = $production->id;
               $stockTransferDetails->date = $production->date;
               $stockTransferDetails->voucher_no = $production->voucher_no;
               $stockTransferDetails->product_id = $request->prod_id;
               $stockTransferDetails->warehouse_id = $production->warehouse_id;
               $stockTransferDetails->qty_out = $request->pcs[$i];
               $stockTransferDetails->qty_in = 0;
               $stockTransferDetails->rate = 0;
               $stockTransferDetails->amount = 0;
               $stockTransferDetails->type = "PACKING PRODUCTION";
               $stockTransferDetails->created_by = $production->prepared_id;
               $stockTransferDetails->updated_by = Auth::User()->id;
               $stockTransferDetails->save();
           }





            return redirect()->back()->with('flash_message', 'Production Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PACKING PRODUCTION')
            ->where('right_name', 'ADD')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
           $code = PackingProduction::where('warehouse_id', Auth::User()->warehouse_id)->orderBy('id', 'desc')->first();
            $codes = 1;
            if ($code) {
                $codes = $code->voucher_no + 1;
            }
            // return $codes;

            $data = $request->all();
            $data['prepared_id'] = Auth::User()->id;
            $data['voucher_no'] = $codes;
            $data['warehouse_id'] = Auth::User()->warehouse_id;
            $data['batch_id'] = $request->thermo_production_id;
            //  return $data;
           $production = PackingProduction::create($data);
           $count = count($request->employee_id);
           for ($i = 0; $i < $count; $i++) {
            $stockTransferDetails = new PackingProductionDetails();
               $stockTransferDetails->transaction_id = $production->id;
               $stockTransferDetails->date = $production->date;
               $stockTransferDetails->voucher_no = $production->voucher_no;
               $stockTransferDetails->product_id = $request->prod_id;
               $stockTransferDetails->warehouse_id = $production->warehouse_id;
               $stockTransferDetails->employee_id = $request->employee_id[$i];
               $stockTransferDetails->qty = $request->qty[$i];
               $stockTransferDetails->pcs =$request->pcs[$i];
               $stockTransferDetails->save();

               $stockTransferDetails = new GodownStockDetail();
               $stockTransferDetails->transaction_id = $production->id;
               $stockTransferDetails->date = $production->date;
               $stockTransferDetails->voucher_no = $production->voucher_no;
               $stockTransferDetails->product_id = $request->prod_id;
               $stockTransferDetails->warehouse_id = $production->warehouse_id;
               $stockTransferDetails->qty_out = $request->pcs[$i];
               $stockTransferDetails->qty_in = 0;
               $stockTransferDetails->rate = 0;
               $stockTransferDetails->amount = 0;
               $stockTransferDetails->type = "PACKING PRODUCTION";
               $stockTransferDetails->created_by = Auth::User()->id;
               $stockTransferDetails->save();
           }

            return redirect()->back()->with('flash_message', 'Production Added Successfully!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PackingProduction  $packingProduction
     * @return \Illuminate\Http\Response
     */
    public function show(PackingProduction $packingProduction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PackingProduction  $packingProduction
     * @return \Illuminate\Http\Response
     */
    public function edit(PackingProduction $packingProduction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PackingProduction  $packingProduction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PackingProduction $packingProduction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PackingProduction  $packingProduction
     * @return \Illuminate\Http\Response
     */
    public function LoadPreviousData(Request $request)
    {
        $id = PackingProduction::where('voucher_no', '<', $request->voucher_no)->max('id');
        // $id = RequestGenerate::where('bill_no', '<', $request->voucher_no) ->whereType('Request Generate')->where('warehouse_id', Auth::User()->warehouse_id)->max('id');
        $production = PackingProduction::with(['thermoforming_production' => function($query){
            $query->with('product');
            $query->with('operator');
            $query->with('shift');
            $query->with('machine');
            $query->with('pressman');
        }])
        ->with('product')
        ->with(['packing_production' => function($query){
            $query->with('employee');
        }])
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
       $id = PackingProduction::where('voucher_no', '>', $request->voucher_no)->min('id');
       $production = PackingProduction::with(['thermoforming_production' => function($query){
        $query->with('product');
        $query->with('operator');
        $query->with('shift');
        $query->with('machine');
        $query->with('pressman');
    }])
    ->with('product')
    ->with(['packing_production' => function($query){
        $query->with('employee');
    }])
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
        $id = PackingProduction::where('voucher_no', '=', $request->voucher_no)->first();
        // $id = RequestGenerate::where('bill_no', '<', $request->voucher_no) ->whereType('Request Generate')->where('warehouse_id', Auth::User()->warehouse_id)->max('id');
        $production = PackingProduction::with(['thermoforming_production' => function($query){
            $query->with('product');
            $query->with('operator');
            $query->with('shift');
            $query->with('machine');
            $query->with('pressman');
        }])
        ->with('product')
        ->with(['packing_production' => function($query){
            $query->with('employee');
        }])
            ->whereId($id->id)
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
        ->where('voucher_name', 'PACKING PRODUCTION')
        ->where('right_name', 'PRINT')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }
        File::cleanDirectory(base_path() . '/upload/production/packing');
        $voucher_no = $request->voucher_no;
         $data = PackingProduction::where('voucher_no', '=', $request->voucher_no)->first();
        if ($data) {
            $production = PackingProduction::with(['thermoforming_production' => function($query){
                $query->with('product');
                $query->with('operator');
                $query->with('shift');
                $query->with('machine');
                $query->with('pressman');
                $query->with('roll_production');
            }])
            ->with('prepared_by')
            ->with('product')
            ->with(['packing_production' => function($query){
                $query->with('employee');
            }])
                ->where('voucher_no', $voucher_no)
                ->first();

            $pdf = PDF::loadView('production.packing.invoice', compact('production'));
            $fileName =  'Packing' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/production/packing/' . $fileName));
            return $fileName;
        }else{
            return false;
        }
    }



    public function destroy(Request $request)
    {
        // return $request;
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'PACKING PRODUCTION')
        ->where('right_name', 'DELETE')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }
        $voucher_no = $request->delete_voucher_no;
        $packing = PackingProduction::where('voucher_no', $voucher_no)->first();
        if ($packing) {
            PackingProduction::where('id', $packing->id)->delete();
            PackingProductionDetails::where('transaction_id', $packing->id)->delete();
            GodownStockDetail::where('transaction_id', $packing->id)
            ->where('type', 'PACKING PRODUCTION')->delete();
            // ConsignmentDetail::where('consignment_no', $bill_no)->delete();
            return redirect()->back()->with('flash_message', 'Thermoforming Production Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
    }
}
