<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use DB;

class SlittingProductionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

       $code = SlittingProduction::where('warehouse_id', Auth::user()->warehouse_id)->orderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->voucher_no + 1;
        }
        //   return $codes;
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

                $RoleOnly = Production::join('products','products.id','=','productions.product_id')
                //    ->OrderBy('code', 'asc')
                    // ->select(
                    //     'products.id as id',
                    //      DB::raw('CONCAT(`code`, "-", `product_name`) as product_name'),
                    // )
                //     ->where('recipe_creations.warehouse_id', Auth::User()->warehouse_id)
                    //  ->where('productions.total_qty', '!=', 'productions.RoleConsumed')
                    //  ->where('color', 'WHITE')
                    ->pluck('productions.batchNo','productions.id')
                    ->prepend('Select Product',''); 
    
                // return Auth::user()->role;
    
            $warehouses = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('Select Godown', '');
            $singlewarehouses = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id')->prepend('Select Godown', '');
             $departments = Departments::pluck('name', 'id')->prepend('Select Department', '');
            return view('production.slitting.index', compact('codes', 'products', 'warehouses', 'departments', 'warehouseproducts', 'singlewarehouses', 'RoleOnly'));

       
    }

    public function RoleData(Request $request){
        // return $request;
        $production = Production::with('product')->where('id', $request->product_id)->first();
        return $production;
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
            'consume_product_id' => 'required'
        ], [
            'consume_product_id.required' => 'The Product field is required.',
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator);
        }

        if (!isset($request->thickness)) {
            return redirect()->back()->with('failure_message', 'Please Enter Voucher No');
        }
        // if (!isset($request->product_id)) {
        //     return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        // }
        // if (!isset($request->pressman_no)) {
        //     return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        // }
        //    return $request;
        if ($request->update_voucher_id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'SLITTING PRODUCTION')
            ->where('right_name', 'EDIT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
             $data = $request->all();
            // $data['unit_id'] = 1;
            // $data['product_id'] = $request->product_id2;
            $data['updated_by'] = Auth::User()->id;
            // $data['batchNo'] =  date("ymd", strtotime($request->date)).$request->machine_id.$request->shift_id.$request->format_id.$request->operator_id;
            $production = SlittingProduction::find($request->update_voucher_id);
            $production->update($data);

            SlittingProductionDetail::where('slitting_production_id', $request->update_voucher_id)->delete();
            GodownStockDetail::where('transaction_id', $request->update_voucher_id)
            ->where('type', 'SLITTING PRODUCTION')->delete();
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $godownstockDetails = new SlittingProductionDetail();
                $godownstockDetails->slitting_production_id = $production->id;
                $godownstockDetails->date = $production->date;
                $godownstockDetails->product_id = $request->product_id[$i];
                $godownstockDetails->warehouse_id = $production->warehouse_id;
                $godownstockDetails->thickness = $request->thickness[$i];
                $godownstockDetails->width = $request->width[$i];
                $godownstockDetails->length = $request->length[$i];
                $godownstockDetails->qty = $request->qty[$i];
                $godownstockDetails->packing = $request->packing[$i];
                $godownstockDetails->weight = $request->weight[$i];
                $godownstockDetails->status = 0;
                $godownstockDetails->save();

                $godownstockDetails = new GodownStockDetail();
                $godownstockDetails->voucher_no = $production['voucher_no'];
                $godownstockDetails->transaction_id = $production->id;
                $godownstockDetails->date = $production->date;
                $godownstockDetails->type = 'SLITTING PRODUCTION';
                $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
                $godownstockDetails->product_id = $production['consume_product_id'];
                $godownstockDetails->qty_in = 0;
                $godownstockDetails->qty_out = $request->weight[$i];
                $godownstockDetails->demand_qty = $request->weight[$i];
                $godownstockDetails->remarks = $production->remarks;
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->sale_rate = 0;
                $godownstockDetails->save();


                $godownstockDetails = new GodownStockDetail();
                $godownstockDetails->voucher_no = $production['voucher_no'];
                $godownstockDetails->transaction_id = $production->id;
                $godownstockDetails->date = $production->date;
                $godownstockDetails->type = 'SLITTING PRODUCTION';
                $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
                $godownstockDetails->product_id = $request->product_id[$i];
                $godownstockDetails->qty_in = $request->weight[$i];
                $godownstockDetails->qty_out = 0;
                $godownstockDetails->demand_qty = $request->weight[$i];
                $godownstockDetails->remarks = $production->remarks;
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->sale_rate = 0;
                $godownstockDetails->save();
            }


            return redirect()->back()->with('flash_message', 'Production Updated Successfully!');
        } else {
            //  return $request;
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'SLITTING PRODUCTION')
            ->where('right_name', 'ADD')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

            $code = SlittingProduction::where('warehouse_id', Auth::User()->warehouse_id)->orderBy('id', 'desc')->first();
            $codes = 1;
            if ($code) {
                $codes = $code->voucher_no + 1;
            }
            
            $data = $request->all();
            $data['prepared_id'] = Auth::User()->id;
            $data['voucher_no'] = $codes;
            $data['warehouse_id'] = Auth::User()->warehouse_id;
            //  return $data;
           $production = SlittingProduction::create($data);
        //    return $codes;

           $count = count($request->product_id);
           for ($i = 0; $i < $count; $i++) {
               $godownstockDetails = new SlittingProductionDetail();
               $godownstockDetails->slitting_production_id = $production->id;
               $godownstockDetails->date = $production->date;
               $godownstockDetails->product_id = $request->product_id[$i];
               $godownstockDetails->warehouse_id = $production->warehouse_id;
               $godownstockDetails->thickness = $request->thickness[$i];
               $godownstockDetails->width = $request->width[$i];
               $godownstockDetails->length = $request->length[$i];
               $godownstockDetails->qty = $request->qty[$i];
               $godownstockDetails->packing = $request->packing[$i];
               $godownstockDetails->weight = $request->weight[$i];
               $godownstockDetails->status = 0;
               $godownstockDetails->save();

                $godownstockDetails = new GodownStockDetail();
                $godownstockDetails->voucher_no = $production['voucher_no'];
                $godownstockDetails->transaction_id = $production->id;
                $godownstockDetails->date = $production->date;
                $godownstockDetails->type = 'SLITTING PRODUCTION';
                $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
                $godownstockDetails->product_id = $production['consume_product_id'];
                $godownstockDetails->qty_in = 0;
                $godownstockDetails->qty_out = $request->weight[$i];
                $godownstockDetails->demand_qty = $request->weight[$i];
                $godownstockDetails->remarks = $production->remarks;
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->sale_rate = 0;
                $godownstockDetails->save();


                $godownstockDetails = new GodownStockDetail();
                $godownstockDetails->voucher_no = $production['voucher_no'];
                $godownstockDetails->transaction_id = $production->id;
                $godownstockDetails->date = $production->date;
                $godownstockDetails->type = 'SLITTING PRODUCTION';
                $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
                $godownstockDetails->product_id = $request->product_id[$i];
                $godownstockDetails->qty_in = $request->weight[$i];
                $godownstockDetails->qty_out = 0;
                $godownstockDetails->demand_qty = $request->weight[$i];
                $godownstockDetails->remarks = $production->remarks;
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->sale_rate = 0;
                $godownstockDetails->save();
           }

            return redirect()->back()->with('flash_message', 'Slitting Production Added Successfully!');
        }
    }

    public function LoadPreviousData(Request $request)
    {

         $id = SlittingProduction::where('voucher_no', '<', $request->voucher_no)->max('id');
        // $id = RequestGenerate::where('bill_no', '<', $request->voucher_no) ->whereType('Request Generate')->where('warehouse_id', Auth::User()->warehouse_id)->max('id');
         $production = SlittingProduction::with(['consumed_production' => function($query){
            $query->with('product');
         }])
         ->with(['production_details' => function($query){
            $query->with('product');
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
        $id = SlittingProduction::where('voucher_no', '=', $request->voucher_no)->first();
        // $id = RequestGenerate::where('bill_no', '<', $request->voucher_no) ->whereType('Request Generate')->where('warehouse_id', Auth::User()->warehouse_id)->max('id');
        $production = SlittingProduction::with(['consumed_production' => function($query){
            $query->with('product');
         }])
         ->with(['production_details' => function($query){
            $query->with('product');
        }])
            ->whereId($id->id)
            ->first();

        if ($production) {
            return Response::json(['production' => $production]);
        } else {
            return Response::json(['production' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
       $id = SlittingProduction::where('voucher_no', '>', $request->voucher_no)->min('id');
        // $id = RequestGenerate::where('bill_no', '<', $request->voucher_no) ->whereType('Request Generate')->where('warehouse_id', Auth::User()->warehouse_id)->max('id');
        $production = SlittingProduction::with(['consumed_production' => function($query){
            $query->with('product');
         }])
         ->with(['production_details' => function($query){
            $query->with('product');
        }])
            ->whereId($id)
            ->first();

        if ($production) {
            return Response::json(['production' => $production]);
        } else {
            return Response::json(['production' => '']);
        }
    }

    public function PrintVoucher(Request $request)
    {
        //  return $request;
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'SLITTING PRODUCTION')
        ->where('right_name', 'PRINT')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }
        File::cleanDirectory(base_path() . '/upload/production/slitting');
        $voucher_no = $request->voucher_no;
       $data = SlittingProduction::where('voucher_no', '=', $request->voucher_no)->first();
        if ($data) {
        // $id = RequestGenerate::where('bill_no', '<', $request->voucher_no) ->whereType('Request Generate')->where('warehouse_id', Auth::User()->warehouse_id)->max('id');
        $production = SlittingProduction::with(['consumed_production' => function($query){
            $query->with('product');
         }])
         ->with(['production_details' => function($query){
            $query->with('product');
        }])
            ->with('generated_by')
            ->where('voucher_no', $voucher_no)
            ->first();

            $pdf = PDF::loadView('production.slitting.invoice', compact('production'));
            $fileName =  'slitting' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/production/slitting/' . $fileName));
            return $fileName;
        }else{
            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SlittingProduction  $slittingProduction
     * @return \Illuminate\Http\Response
     */
    public function show(SlittingProduction $slittingProduction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SlittingProduction  $slittingProduction
     * @return \Illuminate\Http\Response
     */
    public function edit(SlittingProduction $slittingProduction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SlittingProduction  $slittingProduction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SlittingProduction $slittingProduction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SlittingProduction  $slittingProduction
     * @return \Illuminate\Http\Response
     */
    public function destroy(SlittingProduction $slittingProduction)
    {
        //
    }
}
