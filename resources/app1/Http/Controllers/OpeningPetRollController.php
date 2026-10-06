<?php

namespace App\Http\Controllers;

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

class OpeningPetRollController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

       $code = GodownStock::where('type', 'OPENING PET ROLL')->orderBy('id', 'desc')->first();
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
            return view('opening-pet-rolls.index', compact('codes', 'products', 'warehouses', 'departments', 'warehouseproducts', 'singlewarehouses', 'color'));

       
    }


    public function LoadProducts(Request $request){
        // return $request->WarehouseID;
        return $warehouseproducts = Product::where('warehouse_id', $request->WarehouseID)
        ->where('pack_type', 'ROLL')
        ->orderBy('code', 'asc')
        ->get();
    //    return $warehouseproducts = DB::table('products')
    //     // ->join('parties', 'parties.id', '=', 'sale_orders.party_id')
    //     ->select(DB::raw("id,CONCAT(id, '_', code, '_', product_name, '_', uom, '_', product_cost, '_', packing) AS  voucher_no"), 
    //     DB::raw("id, CONCAT(code, '-', product_name) AS  product_name")) 
    //     // Concatenating the columns
    //     ->where('warehouse_id', $request->WarehouseID)
    //     ->orderBy('code', 'asc')
    //     ->pluck('product_name', 'voucher_no')
    //     ->prepend('Select Product', '');
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
            ->where('voucher_name', 'OPENING PET ROLLS')
            ->where('right_name', 'EDIT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
            
             $data = $request->all();
            $data['updated_by'] = Auth::User()->id;
            $production = GodownStock::find($request->update_voucher_id);
            $production->update($data);
            BatchStock::where('transaction_id', $request->update_voucher_id)->where('p_type', 'OPENING PET ROLL')->delete();
            OpeningPetRoll::where('transaction_id', $request->update_voucher_id)->delete();
            GodownStockDetail::where('transaction_id', $request->update_voucher_id)
            ->where('type', 'OPENING PET ROLL')->delete();
            // return "update";
            $count = count($request->product_id);
        for ($i = 0; $i < $count; $i++) {
            $godownstockDetails = new OpeningPetRoll();
            $godownstockDetails->transaction_id = $production->id;
            $godownstockDetails->date = $production->date;
            $godownstockDetails->product_id = $request->product_id[$i];
            $godownstockDetails->batchNo = $request->batchNo[$i];
            $godownstockDetails->thickness = $request->thickness[$i];
            $godownstockDetails->width = $request->width[$i];
            $godownstockDetails->color = $request->color[$i];
            $godownstockDetails->net_weight = $request->net_weight[$i];
            $godownstockDetails->gross_weight = $request->gross_weight[$i];
            $godownstockDetails->warehouse_id = $request->warehouse_id;
            $godownstockDetails->save();
            // return "ddddd";
           $godownstockDetails = new GodownStockDetail();
            $godownstockDetails->voucher_no = $production['voucher_no'];
            $godownstockDetails->transaction_id = $production->id;
            // $godownstockDetails->inward_gatepass_id =1;
            $godownstockDetails->date = $production->date;
            $godownstockDetails->type = 'OPENING PET ROLL';
            $godownstockDetails->warehouse_id = $request->warehouse_id;
            // $godownstockDetails->party_id = $request->party_id;
            $godownstockDetails->product_id = $request->product_id[$i];
            $godownstockDetails->qty_in = $request->net_weight[$i];
            $godownstockDetails->qty_out = 0;
            $godownstockDetails->demand_qty = $request->net_weight[$i];
            $godownstockDetails->remarks = $request->remarks;
            $godownstockDetails->created_by = Auth::User()->id;
            $godownstockDetails->sale_rate = 0;
            $godownstockDetails->save();

            $batchStock = new BatchStock();
            $batchStock->voucher_no = $production['voucher_no'];
            $batchStock->date = $production->date;
            $batchStock->p_type = 'OPENING PET ROLL';
            $batchStock->p_status = 0;
            $batchStock->product_id = $request->product_id[$i];
            $batchStock->unit_id = 1;
            $batchStock->warehouse_id = $request->warehouse_id;
            $batchStock->from_warehouse_id = $request->warehouse_id;
            $batchStock->transaction_id = $production->id;
            $batchStock->total_qty = $request->net_weight[$i];
            $batchStock->balance_weight = $request->net_weight[$i];
            $batchStock->out_qty = 0;
            $batchStock->gross_weight = $request->gross_weight[$i];
            // $batchStock->total_rate = $request->net_weight[$i];
            $batchStock->color_id = $request->color[$i];
            $batchStock->thickness = $request->thickness[$i];
            $batchStock->width = $request->width[$i];
            $batchStock->batchNo = $request->batchNo[$i];
            $batchStock->remarks = $request->remarks;
            $batchStock->created_by = Auth::User()->id;
            $batchStock->save();
        }


            return redirect()->back()->with('flash_message', 'Record Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'OPENING PET ROLLS')
            ->where('right_name', 'ADD')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
            //   return $request;
            $code = GodownStock::where('type', 'OPENING PET ROLL')
            ->orderBy('id', 'desc')->first();
            $codes = 1;
            if ($code) {
                $codes = $code->voucher_no + 1;
            }
            $data = $request->all();
            $data['created_by'] = Auth::User()->id;
            $data['type'] = "OPENING PET ROLL";
            $data['voucher_no'] = $codes;
            $data['to_warehouse_id'] = $request->warehouse_id;
            $data['remarks'] = $request->remarks;
            // return $request;
           $production = GodownStock::create($data);
           
        $totalAmount = 0;
        $totalSaleRate = 0;
        $count = count($request->product_id);
        for ($i = 0; $i < $count; $i++) {
            
            $godownstockDetails = new OpeningPetRoll();
            $godownstockDetails->transaction_id = $production->id;
            $godownstockDetails->date = $production->date;
            $godownstockDetails->product_id = $request->product_id[$i];
            $godownstockDetails->batchNo = $request->batchNo[$i];
            $godownstockDetails->thickness = $request->thickness[$i];
            $godownstockDetails->width = $request->width[$i];
            $godownstockDetails->color = $request->color[$i];
            $godownstockDetails->net_weight = $request->net_weight[$i];
            $godownstockDetails->gross_weight = $request->gross_weight[$i];
            $godownstockDetails->warehouse_id = $request->warehouse_id;
            $godownstockDetails->save();
            // return "ddddd";
           $godownstockDetails = new GodownStockDetail();
            $godownstockDetails->voucher_no = $production['voucher_no'];
            $godownstockDetails->transaction_id = $production->id;
            // $godownstockDetails->inward_gatepass_id =1;
            $godownstockDetails->date = $production->date;
            $godownstockDetails->type = 'OPENING PET ROLL';
            $godownstockDetails->warehouse_id = $request->warehouse_id;
            // $godownstockDetails->party_id = $request->party_id;
            $godownstockDetails->product_id = $request->product_id[$i];
            $godownstockDetails->qty_in = $request->net_weight[$i];
            $godownstockDetails->qty_out = 0;
            $godownstockDetails->demand_qty = $request->net_weight[$i];
            $godownstockDetails->remarks = $request->remarks;
            $godownstockDetails->created_by = Auth::User()->id;
            $godownstockDetails->sale_rate = 0;
            $godownstockDetails->save();

            $batchStock = new BatchStock();
            $batchStock->voucher_no = $production['voucher_no'];
            $batchStock->date = $production->date;
            $batchStock->p_type = 'OPENING PET ROLL';
            $batchStock->p_status = 0;
            $batchStock->product_id = $request->product_id[$i];
            $batchStock->unit_id = 1;
            $batchStock->warehouse_id = $request->warehouse_id;
            $batchStock->from_warehouse_id = $request->warehouse_id;
            $batchStock->transaction_id = $production->id;
            $batchStock->total_qty = $request->net_weight[$i];
            $batchStock->balance_weight = $request->net_weight[$i];
            $batchStock->out_qty = 0;
            $batchStock->gross_weight = $request->gross_weight[$i];
            // $batchStock->total_rate = $request->net_weight[$i];
            $batchStock->color_id = $request->color[$i];
            $batchStock->thickness = $request->thickness[$i];
            $batchStock->width = $request->width[$i];
            $batchStock->batchNo = $request->batchNo[$i];
            $batchStock->remarks = $request->remarks;
            $batchStock->created_by = Auth::User()->id;
            $batchStock->save();
        }
            return redirect()->back()->with('flash_message', 'Record Added Successfully!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OpeningPetRoll  $openingPetRoll
     * @return \Illuminate\Http\Response
     */
    public function show(OpeningPetRoll $openingPetRoll)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OpeningPetRoll  $openingPetRoll
     * @return \Illuminate\Http\Response
     */
    public function edit(OpeningPetRoll $openingPetRoll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OpeningPetRoll  $openingPetRoll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OpeningPetRoll $openingPetRoll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OpeningPetRoll  $openingPetRoll
     * @return \Illuminate\Http\Response
     */


    public function LoadPreviousData(Request $request)
    {
       $id = GodownStock::where('type', 'OPENING PET ROLL')->where('voucher_no', '<', $request->voucher_no)->max('id');
       $petstock = GodownStock::with(['opening_pet_rolls' => function($query){
        $query->with('product');
        $query->with('color');
       }])
            ->where('id', $id)
            ->get();
        if ($petstock) {
            return Response::json(['petstock' => $petstock]);
        } else {
            return Response::json(['petstock' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
       $id = GodownStock::where('type', 'OPENING PET ROLL')->where('voucher_no', '>', $request->voucher_no)->min('id');
       $petstock = GodownStock::with(['opening_pet_rolls' => function($query){
        $query->with('product');
        $query->with('color');
       }])
            ->where('id', $id)
            ->get();
        if ($petstock) {
            return Response::json(['petstock' => $petstock]);
        } else {
            return Response::json(['petstock' => '']);
        }






        // $id = GodownStock::where('type', 'OPENING PET ROLL')->where('voucher_no', '<', $request->voucher_no)->max('id');
        // $petstock = GodownStock::with(['opening_pet_rolls' => function($query){
        //  $query->with('product');
        // }])
        //      ->where('id', $id)
        //      ->get();
        //  if ($petstock) {
        //      return Response::json(['petstock' => $petstock]);
        //  } else {
        //      return Response::json(['petstock' => '']);
        //  }
    }

    public function editData(Request $request)
    {
        // return $request;
         $id = GodownStock::where('type', 'OPENING PET ROLL')->where('voucher_no', '=', $request->voucher_no)->first();
         $petstock = GodownStock::with(['opening_pet_rolls' => function($query){
            $query->with('product');
            $query->with('color');
           }])
                ->where('id', $id->id)
                ->get();
            if ($petstock) {
                return Response::json(['petstock' => $petstock]);
            } else {
                return Response::json(['petstock' => '']);
            }


    //  $id = GodownStock::where('type', 'OPENING PET ROLL')->where('voucher_no', '<', $request->voucher_no)->max('id');
    //  $petstock = GodownStock::with(['opening_pet_rolls' => function($query){
    //   $query->with('product');
    //  }])
    //       ->where('id', $id)
    //       ->get();
    //   if ($petstock) {
    //       return Response::json(['petstock' => $petstock]);
    //   } else {
    //       return Response::json(['petstock' => '']);
    //   }
    }

    public function PrintVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'OPENING PET ROLLS')
        ->where('right_name', 'PRINT')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }


        File::cleanDirectory(base_path() . '/upload/opening-pet-rolls');
        $voucher_no = $request->voucher_no;
        $data = GodownStock::where('type', 'OPENING PET ROLL')->where('voucher_no', '=', $request->voucher_no)->first();
        if ($data) {

            $production = GodownStock::with(['opening_pet_rolls' => function($query){
                $query->with('product');
                $query->with('color');
               }])
                    ->with('warehouse')
                    ->with('generated_by')
                    ->where('id', $data->id)
                    ->get();

            $pdf = PDF::loadView('opening-pet-rolls.invoice', compact('production'));
            $fileName =  'Opening-Pet-Rolls' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/opening-pet-rolls/' . $fileName));
            return $fileName;
        }else{
            return false;
        }
    }



    public function DeleteVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'OPENING PET ROLLS')
        ->where('right_name', 'DELETE')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }
        // return $request;
        $voucher_no = $request->delete_voucher_no;
        $opening = GodownStock::where('type', 'OPENING PET ROLL')->where('voucher_no', $voucher_no)->first();
        if ($opening) {
            GodownStock::where('id', $opening->id)->where('type', 'OPENING PET ROLL')->delete();
            BatchStock::where('transaction_id', $opening->id)->where('p_type', 'OPENING PET ROLL')->delete();
            OpeningPetRoll::where('transaction_id', $opening->id)->delete();
            GodownStockDetail::where('transaction_id', $opening->id)
            ->where('type', 'OPENING PET ROLL')->delete();
            return redirect()->back()->with('flash_message', 'Record Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
    }

    // public function destroy(OpeningPetRoll $openingPetRoll)
    // {
    //     //
    // }
}
