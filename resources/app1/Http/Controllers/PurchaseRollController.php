<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRoll;
use App\Models\PurchaseRollDetail;
use App\Models\OpeningPetRoll;
use App\Models\GodownStockDetail;
use App\Models\ThermoformingProduction;
use Illuminate\Http\Request;
use App\Models\InwardGatePass;
use App\Models\Machine;
use App\Models\Shift;
use App\Models\Product;
use App\Models\Production;
use App\Models\User;
use App\Models\Role;
use App\Models\Color;
use App\Models\Warehouse;
use App\Models\BatchStock;
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
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use DB;

class PurchaseRollController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

       $code = PurchaseRoll::orderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->voucher_no + 1;
        }
        //  return $codes;
            $product = Product::select(DB::raw('CONCAT(`id`, "-", `product_name`) AS `id`, CONCAT(`code`,"-",`product_name`) `product_name`'))
            ->where('product_type', 'Finish')
            ->OrderBy('product_name', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');
            // return view('opening-pet-rolls.index', compact('codes', 'product'));

            //return $codes;
            // return $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_code`, "_", `product_name`, "_", `uom`, "_", `product_cost`)
            //  AS `id`, `product_code`, `product_name`, `uom`, `product_cost`'))
            //     ->OrderBy('id', 'asc')
            //     ->pluck('product_name', 'id')
            //     ->prepend('Select Product', '');
            //  $products = Product::
            // select(
            //     DB::raw("CONCAT(id, '_', code,'_', product_name, '_', uom, '_', product_cost) AS product_name")
            //     )
            //     ->
            //     pluck('product_name', 'product_name')
            //     ->prepend('Select Product', '');
    
                $products = DB::table('products')
                ->where('pack_type', 'ROLL')
                // ->join('parties', 'parties.id', '=', 'sale_orders.party_id')
                ->select(DB::raw("id,CONCAT(id, '_', code, '_', product_name, '_', uom, '_', product_cost, '_', packing) AS  voucher_no"), 
                DB::raw("id, CONCAT(code, '-', product_name) AS  product_name")) 
                // Concatenating the columns
                ->orderBy('code', 'asc')
                
                ->pluck('product_name', 'voucher_no')
                ->prepend('Select Product', '');
    
              $warehouseproducts = DB::table('products')
              ->where('pack_type', 'ROLL')
                // ->join('parties', 'parties.id', '=', 'sale_orders.party_id')
                ->select(DB::raw("id,CONCAT(id, '_', code, '_', product_name, '_', uom, '_', product_cost, '_', packing) AS  voucher_no"), 
                DB::raw("id, CONCAT(code, '-', product_name) AS  product_name")) 
                // Concatenating the columns
                ->where('warehouse_id', Auth::user()->warehouse_id)
                ->orderBy('code', 'asc')
                ->pluck('product_name', 'voucher_no')
                ->prepend('Select Product', '');
    
                // return Auth::user()->role;
    
            $warehouses = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('Select Godown', '');
            $singlewarehouses = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id')->prepend('Select Godown', '');
             $departments = Departments::pluck('name', 'id')->prepend('Select Department', '');
            //  $color = Color::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('Select Color', '');
             $color = DB::table('colors')
        ->select(DB::raw("name,CONCAT(id, '_', name) AS  voucher_no")) 
        // ->where('designation_id', 1)
        ->orderBy('name', 'asc')
        ->pluck('name','voucher_no')
        ->prepend('Select Color', '');
            return view('purchase-rolls.index', compact('codes', 'products', 'warehouses', 'departments', 'warehouseproducts', 'singlewarehouses', 'color'));

       
    }

    public function LoadIGPqty(Request $request){
        $ipgqty = InwardGatePass::where('bill_no', $request->igpNo)->first(['total_qty']);
        if ($ipgqty) {
            return Response::json(['ipgqty' => $ipgqty]);
        } else {
            return Response::json(['ipgqty' => '']);
        }
        // return $ipgqty;
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
    //     $Validator = Validator::make($request->all(), [
    //         'voucher_no' => 'required',
    //         'date' => 'required',
    //         'product_id' => 'required',
    //         'batchNo' => 'required',
    //         'thickness' => 'required',
    //         'width' => 'required',
    //         'color' => 'required',
    //         'net_weight' => 'required',
    //         'gross_weight' => 'required',
    //     ]
    //     // , [
    //     //     'color.required' => 'The Net Sheets field is required.',
    //     //     'thickness.required' => 'The Net SKU field is required.'
    //     // ]
    // );
        // if ($Validator->fails()) {
        //     return redirect()->back()->withErrors($Validator);
        // }

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
            ->where('voucher_name', 'PURCHASE ROLLS')
            ->where('right_name', 'EDIT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
            
             $data = $request->all();
            $data['updated_by'] = Auth::User()->id;
            $roll = PurchaseRoll::find($request->update_voucher_id);
            $roll->update($data);
            // return "done";
            BatchStock::where('transaction_id', $request->update_voucher_id)->where('p_type', 'PURCHASE ROLLS')->delete();
            PurchaseRollDetail::where('transaction_id', $request->update_voucher_id)->delete();
          
            // return "update";
            $count = count($request->product_id);
        for ($i = 0; $i < $count; $i++) {
            $batchStock = new PurchaseRollDetail();
            $batchStock->transaction_id = $roll->id;
            $batchStock->date = $roll->date;
            $batchStock->product_id = $request->product_id[$i];
            $batchStock->batchNo = $request->batchNo[$i];
            $batchStock->thickness = $request->thickness[$i];
            $batchStock->width = $request->width[$i];
            $batchStock->color = $request->color[$i];
            $batchStock->net_weight = $request->net_weight[$i];
            $batchStock->gross_weight = $request->gross_weight[$i];
            $batchStock->warehouse_id = $request->warehouse_id;
            $batchStock->save();

            $batchStock = new BatchStock();
            $batchStock->voucher_no = $roll->voucher_no;
            $batchStock->date = $roll->date;
            $batchStock->p_type = 'PURCHASE ROLLS';
            $batchStock->p_status = 0;
            $batchStock->product_id = $request->product_id[$i];
            $batchStock->unit_id = 1;
            $batchStock->warehouse_id = $request->warehouse_id;
            $batchStock->from_warehouse_id = $request->warehouse_id;
            $batchStock->transaction_id = $roll->id;
            $batchStock->total_qty = $request->net_weight[$i];
            $batchStock->balance_weight = $request->net_weight[$i];
            $batchStock->out_qty = 0;
            $batchStock->gross_weight = $request->gross_weight[$i];
            $batchStock->color_id = $request->color[$i];
            $batchStock->thickness = $request->thickness[$i];
            $batchStock->width = $request->width[$i];
            $batchStock->batchNo = $request->batchNo[$i];
            // $batchStock->remarks = $request->remarks;
            $batchStock->created_by = Auth::User()->id;
            $batchStock->save();
        }


            return redirect()->back()->with('flash_message', 'Record Updated Successfully!');
        } else {
            // return "d";
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PURCHASE ROLLS')
            ->where('right_name', 'ADD')
            ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            //   return $request;
            $code = PurchaseRoll::orderBy('id', 'desc')->first();
            $codes = 1;
            if ($code) {
                $codes = $code->voucher_no + 1;
            }
            
            $data = $request->all();
            $data['created_by'] = Auth::User()->id;
            // $data['type'] = "PURCHASE ROLLS";
            $data['voucher_no'] = $codes;
            // $data['to_warehouse_id'] = $request->warehouse_id;
            // $data['remarks'] = $request->remarks;
            // return $request;
           $roll = PurchaseRoll::create($data);
            //    return $request;
            // $totalAmount = 0;
            // $totalSaleRate = 0;
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
            

            $batchStock = new PurchaseRollDetail();
            $batchStock->transaction_id = $roll->id;
            $batchStock->date = $roll->date;
            $batchStock->product_id = $request->product_id[$i];
            $batchStock->batchNo = $request->batchNo[$i];
            $batchStock->thickness = $request->thickness[$i];
            $batchStock->width = $request->width[$i];
            $batchStock->color = $request->color[$i];
            $batchStock->net_weight = $request->net_weight[$i];
            $batchStock->gross_weight = $request->gross_weight[$i];
            $batchStock->warehouse_id = $request->warehouse_id;
            $batchStock->save();

            $batchStock = new BatchStock();
            $batchStock->voucher_no = $roll->voucher_no;
            $batchStock->date = $roll->date;
            $batchStock->p_type = 'PURCHASE ROLLS';
            $batchStock->p_status = 0;
            $batchStock->product_id = $request->product_id[$i];
            $batchStock->unit_id = 1;
            $batchStock->warehouse_id = $request->warehouse_id;
            $batchStock->from_warehouse_id = $request->warehouse_id;
            $batchStock->transaction_id = $roll->id;
            $batchStock->total_qty = $request->net_weight[$i];
            $batchStock->balance_weight = $request->net_weight[$i];
            $batchStock->out_qty = 0;
            $batchStock->gross_weight = $request->gross_weight[$i];
            $batchStock->color_id = $request->color[$i];
            $batchStock->thickness = $request->thickness[$i];
            $batchStock->width = $request->width[$i];
            $batchStock->batchNo = $request->batchNo[$i];
            // $batchStock->remarks = $request->remarks;
            $batchStock->created_by = Auth::User()->id;
            $batchStock->save();
        }
            return redirect()->back()->with('flash_message', 'Record Added Successfully!');
        }
    }


    public function LoadPreviousData(Request $request)
    {
        // return $request;
        $id = PurchaseRoll::where('voucher_no', '<', $request->voucher_no)->max('id');
         $rolls = PurchaseRoll::with(['purchase_roll_details' => function($query){
        $query->with('product:id,code,product_name');
        $query->with('color:id,name');
       }])
       ->with('inward:bill_no,total_qty')
            ->where('id', $id)
            ->get();
        if ($rolls) {
            return Response::json(['rolls' => $rolls]);
        } else {
            return Response::json(['rolls' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
       $id = PurchaseRoll::where('voucher_no', '>', $request->voucher_no)->min('id');
       $rolls = PurchaseRoll::with(['purchase_roll_details' => function($query){
        $query->with('product:id,code,product_name');
        $query->with('color:id,name');
       }])
       ->with('inward:bill_no,total_qty')
            ->where('id', $id)
            ->get();
        if ($rolls) {
            return Response::json(['rolls' => $rolls]);
        } else {
            return Response::json(['rolls' => '']);
        }

    }

    public function editData(Request $request)
    {
        // return $request;
         $id = PurchaseRoll::where('voucher_no', '=', $request->voucher_no)->first();
         $rolls = PurchaseRoll::with(['purchase_roll_details' => function($query){
            $query->with('product:id,code,product_name');
            $query->with('color:id,name');
           }])
           ->with('inward:bill_no,total_qty')
                ->where('id', $id->id)
                ->get();
            if ($rolls) {
                return Response::json(['rolls' => $rolls]);
            } else {
                return Response::json(['rolls' => '']);
            }
    }

    public function PrintVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'PURCHASE ROLLS')
        ->where('right_name', 'PRINT')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }


        File::cleanDirectory(base_path() . '/upload/opening-pet-rolls');
        $voucher_no = $request->voucher_no;
        $data = PurchaseRoll::where('voucher_no', '=', $request->voucher_no)->first();
        if ($data) {

             $production = PurchaseRoll::with(['purchase_roll_details' => function($query){
                $query->with('product:id,code,product_name');
                $query->with('color:id,name');
               }])
               ->with('warehouse')
               
               ->with('inward:bill_no,total_qty')
               ->with('preparedby:id,name')
                    ->where('id', $data->id)
                    ->get();

            $pdf = PDF::loadView('purchase-rolls.invoice', compact('production'));
            $fileName =  'Purchase-Rolls' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/opening-pet-rolls/' . $fileName));
            return $fileName;
        }else{
            return false;
        }
    }



    public function DeleteVoucher(Request $request)
    {
         $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'PURCHASE ROLLS')
        ->where('right_name', 'DELETE')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }
        //  return $request;
        $voucher_no = $request->delete_voucher_no;
         $opening = PurchaseRoll::where('voucher_no', $voucher_no)->first();
        if ($opening) {
            PurchaseRoll::where('id', $opening->id)->delete();
            BatchStock::where('transaction_id', $opening->id)->where('p_type', 'PURCHASE ROLLS')->delete();
            PurchaseRollDetail::where('transaction_id', $opening->id)->delete();
            return redirect()->back()->with('flash_message', 'Record Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseRoll  $purchaseRoll
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseRoll $purchaseRoll)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseRoll  $purchaseRoll
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseRoll $purchaseRoll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseRoll  $purchaseRoll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseRoll $purchaseRoll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseRoll  $purchaseRoll
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseRoll $purchaseRoll)
    {
        //
    }
}
