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
use App\Models\Catagory;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use DB;

class SlittingStockTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
       $code = GodownStock::where('type', 'SLITTING STOCK TRANSFER')->orderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->voucher_no + 1;
        }
       $productGroup = Catagory::Orderby('catagory_name', 'asc')->pluck('catagory_name', 'id')->prepend('Choose Product Group', '');
        $slittingProduction = SlittingProductionDetail::with('slitting_production')->with('product')
        ->where('status', 0)->get();
        $warehouseFrom = Warehouse::select('name')->where('id', Auth::User()->warehouse_id)->first();
        $warehouseTo = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('Select Godown', '');
        return view('stock-transfer.slitting.index', compact('codes', 'warehouseFrom', 'warehouseTo', 'slittingProduction', 'productGroup')); 
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
    //    return $request;
        $Validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'voucher_no' => 'required',
            'from_warehouse_id' => 'required',
            'to_warehouse_id' => 'required'
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator)->withInput();
        }
        // if (!isset($request->price)) {
        //     return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        // }

        // if (!isset($request->qty)) {
        //     return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        // }
      
        if ($request->update_voucher_id != null || $request->update_voucher_id != 0) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'SLITTING STOCK TRANSFER')
            ->where('right_name', 'EDIT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        // return "below";
            $stockTransfer = GodownStock::where('id', $request->update_voucher_id)->where('type','SLITTING STOCK TRANSFER')->first();
            $stockTransfer = GodownStock::findOrFail($stockTransfer->id);
            $data = $request->all();
            // $data['voucher_no'] = $codes;
            // $data['status'] =0;
            $data['updated_by'] = Auth::User()->id;
            $data['type'] = "SLITTING STOCK TRANSFER";
            $stockTransfer->update($request->all());
            

            GodownStockDetail::where('id', $request->update_voucher_id)
            ->where('type','SLITTING STOCK TRANSFER')->delete();
            // return "neee";

            $count = count($request->slitting_detail_id);
            $totalQty = 0;
            $totalAmount = 0;
                // return $request;
            for ($i = 0; $i < $count; $i++) {
                    if($request->status){
                        //  return $request->status[$i];
                        $data = SlittingProductionDetail::where('id', $request->slitting_detail_id[$i])->first();
                        $data->status = 1;
                        $data->godownID_for_edit = $stockTransfer->id;
                        $data->save();
                    }
                    else{
                        // return "ddd";
                        $data = SlittingProductionDetail::where('id', $request->slitting_detail_id[$i])->first();
                        $data->status = 0;
                        $data->godownID_for_edit = $stockTransfer->id;
                        $data->save();
                    }
          
                
                 
                $stockTransferDetails = new GodownStockDetail();
                $stockTransferDetails->transaction_id = $stockTransfer->id;
                $stockTransferDetails->date = $stockTransfer->date;
                $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                $stockTransferDetails->product_id = $request->product_id[$i];
                $stockTransferDetails->warehouse_id = $stockTransfer->from_warehouse_id;
                $stockTransferDetails->qty_out = $request->weight[$i];
                $stockTransferDetails->qty_in =0;
                $stockTransferDetails->rate = 0;
                $stockTransferDetails->amount = 0;
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = Auth::User()->id;
                $stockTransferDetails->save();
                // return $data;
                $stockTransferDetails = new GodownStockDetail();
                $stockTransferDetails->transaction_id = $stockTransfer->id;
                $stockTransferDetails->date = $stockTransfer->date;
                $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                $stockTransferDetails->product_id = $request->product_id[$i];
                $stockTransferDetails->warehouse_id = $stockTransfer->to_warehouse_id;
                $stockTransferDetails->qty_in = $request->weight[$i];
                $stockTransferDetails->qty_out =0;
                $stockTransferDetails->rate = 0;
                $stockTransferDetails->amount = 0;
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = Auth::User()->id;
                $stockTransferDetails->save();
            }

           
            // GodownStockDetail::where('voucher_no', $request->update_voucher_no)->where('type','STOCK TRANSFER')->delete();
            // $count = count($request->product_id);
            // $totalQty = 0;
            // $totalAmount = 0;
            // for ($i = 0; $i < $count; $i++) {
            //     $stockTransferDetails = new GodownStockDetail();
            //     $stockTransferDetails->transaction_id = $stockTransfer->id;
            //     $stockTransferDetails->date = $stockTransfer->date;
            //     $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
            //     $stockTransferDetails->product_id = $request->product_id[$i];
            //     $stockTransferDetails->warehouse_id = $stockTransfer->from_warehouse_id;
            //     $stockTransferDetails->qty_out = $request->qty[$i];
            //     $stockTransferDetails->qty_in =0;
            //     $stockTransferDetails->rate = $request->price[$i];
            //     $stockTransferDetails->amount = $request->total[$i];
            //     $stockTransferDetails->type = $stockTransfer->type;
            //     $stockTransferDetails->created_by = Auth::User()->id;
            //     $stockTransferDetails->save();

            //     $stockTransferDetails = new GodownStockDetail();
            //     $stockTransferDetails->transaction_id = $stockTransfer->id;
            //     $stockTransferDetails->date = $stockTransfer->date;
            //     $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
            //     $stockTransferDetails->product_id = $request->product_id[$i];
            //     $stockTransferDetails->warehouse_id = $stockTransfer->to_warehouse_id;
            //     $stockTransferDetails->qty_in = $request->qty[$i];
            //     $stockTransferDetails->qty_out =0;
            //     $stockTransferDetails->rate = $request->price[$i];
            //     $stockTransferDetails->amount = $request->total[$i];
            //     $stockTransferDetails->type = $stockTransfer->type;
            //     $stockTransferDetails->created_by = Auth::User()->id;
            //     $stockTransferDetails->save();

            // }
          
            // $stockTransfer->created_by = Auth::User()->id;
            // $stockTransfer->updated_by = Auth::User()->id;
            // $stockTransfer->save();

            return redirect()->back()->with('flash_message', 'Stock Transfer Updated Successfully!');
        } else {
            
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'SLITTING STOCK TRANSFER')
            ->where('right_name', 'ADD')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        
         $code = GodownStock::where('from_warehouse_id', Auth::User()->warehouse_id)->where('type','SLITTING STOCK TRANSFER')->OrderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->voucher_no + 1;
        }
         
        $data = $request->all();
        $data['voucher_no'] = $codes;
        $data['status'] =0;
        $data['created_by'] = Auth::User()->id;
        $data['type'] = "SLITTING STOCK TRANSFER";
        $stockTransfer = GodownStock::create($data);
       
        // return "done";
             $count = count($request->slitting_detail_id);
            $totalQty = 0;
            $totalAmount = 0;
            //  return $request;
            for ($i = 0; $i < $count; $i++) {
                $data = SlittingProductionDetail::where('id', $request->slitting_detail_id[$i])->first();
                $data->status = 1;
                $data->godownID_for_edit = $stockTransfer->id;
                $data->save();
                 
                $stockTransferDetails = new GodownStockDetail();
                $stockTransferDetails->transaction_id = $stockTransfer->id;
                $stockTransferDetails->date = $stockTransfer->date;
                $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                $stockTransferDetails->product_id = $request->product_id[$i];
                $stockTransferDetails->warehouse_id = $stockTransfer->from_warehouse_id;
                $stockTransferDetails->qty_out = $request->weight[$i];
                $stockTransferDetails->qty_in =0;
                $stockTransferDetails->rate = 0;
                $stockTransferDetails->amount = 0;
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = Auth::User()->id;
                $stockTransferDetails->save();
                // return $data;
                $stockTransferDetails = new GodownStockDetail();
                $stockTransferDetails->transaction_id = $stockTransfer->id;
                $stockTransferDetails->date = $stockTransfer->date;
                $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                $stockTransferDetails->product_id = $request->product_id[$i];
                $stockTransferDetails->warehouse_id = $stockTransfer->to_warehouse_id;
                $stockTransferDetails->qty_in = $request->weight[$i];
                $stockTransferDetails->qty_out =0;
                $stockTransferDetails->rate = 0;
                $stockTransferDetails->amount = 0;
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = Auth::User()->id;
                $stockTransferDetails->save();
            }

            // $stockTransfer->created_by = Auth::User()->id;
            // $stockTransfer->save();

            return redirect()->back()->with('flash_message', 'Stock Transfer Added Successfully!');
        }
        abort(500);
    }

        // LoadProducts
        public function LoadProducts(Request $request){
            //  return $request;
            // return $products = Product::where('category_id', $request->groupId)->get();
            $products = SlittingProductionDetail::join('products','products.id','=','slitting_production_details.product_id')
            ->join('slitting_productions','slitting_productions.id','=','slitting_production_details.slitting_production_id')
             ->where('products.category_id', $request->groupId)
            ->where('slitting_production_details.status', 0)
            ->get(['slitting_production_details.id','slitting_production_details.product_id', 'slitting_productions.voucher_no', 'slitting_productions.date', 'products.product_name', 'products.uom', 'slitting_production_details.thickness', 'slitting_production_details.width', 
            'slitting_production_details.length', 'slitting_production_details.qty', 'slitting_production_details.packing', 'slitting_production_details.weight']);
            return $products;
    
            
        }

    public function LoadPreviousData(Request $request)
    {
        // return $request;
         $GodownStock = GodownStock::where('voucher_no', '<', $request->voucher_no)
        ->where('type','SLITTING STOCK TRANSFER')
        ->where('from_warehouse_id',Auth::User()->warehouse_id)->max('voucher_no');

         $godownid = GodownStock::where('voucher_no', $GodownStock)->first();
        if ($GodownStock) {
             $GodownStockDetail = SlittingProductionDetail::with('product')->with('slitting_production')
             ->with('godown')
            ->where('godownID_for_edit', $godownid->id)
            ->where('status', 1)
            // ->where('type','STOCK TRANSFER')
            // ->where('warehouse_id',Auth::User()->warehouse_id)
            // ->where('qty_out','!=',null)
            ->get();
            return Response::json(
                [
                    'data' => $GodownStockDetail,
                    'godowndata' => $godownid,
                ]
            );
        } else {
            return Response::json(['data' => '']);
        }
    }


    public function PrintVoucher(Request $request)
    {
    //     $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
    //     ->where('voucher_name', 'STOCK TRANSFER')
    //     ->where('right_name', 'PRINT')
    //     ->first();
    // if (!$voucherRight) {
    //     return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    // }
    //  return $request;
        File::cleanDirectory(base_path() .'/upload/production/slitting');
        $voucher_no = $request->voucher_no;
         $GodownStock = GodownStock::where('type','SLITTING STOCK TRANSFER')
        ->where('from_warehouse_id',Auth::User()->warehouse_id)
        ->where('voucher_no', $voucher_no)->first();
        $godownid = GodownStock::where('id', $GodownStock->id)->first();
        if ($GodownStock) {
            // $production = SlittingProductionDetail::with('product')->with('slitting_production')
            //  ->with('godown')
            // ->where('godownID_for_edit', $godownid->id)
            // ->where('status', 1)
            // ->get();

            $production = GodownStock::with(['slitting_production_details' => function($query){
                $query->with('product');
            }])
            ->with('warehouse_from')
            ->with('warehouse_to')
            ->with('generated_by')
            // ->where('godownID_for_edit', $godownid->id)
            ->where('id', $godownid->id)
            ->get();


            $pdf = PDF::loadView('stock-transfer.slitting.invoice', compact('production'));
            $fileName =  'slitting-transfer' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/stock-transfer/slitting/' . $fileName));
            return $fileName;
        } else{
            return false;  
        }
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
    public function destroy(Request $request)
    {
            // return $request;
         $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
         ->where('voucher_name', 'SLITTING STOCK TRANSFER')
         ->where('right_name', 'DELETE')
         ->first();
     if (!$voucherRight) {
         return redirect()->back()->with('access_granted', 'Insufficient Permission.');
     }
         $delete = GodownStock::where('voucher_no', $request->delete_voucher_no)->where('type','SLITTING STOCK TRANSFER')->where('from_warehouse_id',Auth::User()->warehouse_id)->first();
          $slittingproductionDetail= SlittingProductionDetail::where('godownID_for_edit', $delete->id)->get();
         foreach($slittingproductionDetail as $data){
            $data->status = 0;
            $data->save();
         }
        //  return 'dd';
        if ($delete) {
            GodownStock::findOrFail($delete->id)->delete();
            GodownStockDetail::where('transaction_id', $delete->id)->where('type','SLITTING STOCK TRANSFER')->delete();
            return redirect()->back()->with('flash_message', 'Stock Transfer Voucher Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }
}
