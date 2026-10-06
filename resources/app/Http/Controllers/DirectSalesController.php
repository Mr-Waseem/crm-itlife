<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Party;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\SalePurchase;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\DeliveryChallan;
use App\Models\GodownStockDetail;
use App\Models\SalePurchaseDetail;
use App\Models\GeneralVoucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\DeliveryChallanDetails;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Session;
class DirectSalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // return "d";
        $codes = 1;
        $sales = SalePurchase::whereType('DIRECT SALES')
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->OrderBy('id', 'desc')->first();
        if ($sales) {
            $codes = (int)$sales->voucher_no + 1;
        }
         $products = Product::select(
            DB::raw('
            CONCAT(`id`, "_", `code`, "_", `product_name`, "_", `uom`,"_", `product_price`) AS `id`,
            CONCAT(`code`, "-", `product_name`) AS `product_name`
            '))
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');

         $customers = Party::select(DB::raw('CONCAT(`code`, "-", `party_name`) AS `party_name`, `id`'))
            ->where('role', '=', 'Customer')
            ->where('type', '=', 'Un Registered')
            // ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party Name', '');
            $Accountsbelow = Product::OrderBy('product_name', 'asc')
            // ->pluck('party_name', 'id')
            ->get(['id', 'product_name', 'code']);

            $usertype = Auth::User()->role;
            if($usertype == "Admin"){
                $warehouse = Warehouse::OrderBy('id')->pluck('name', 'id')->prepend('Select Warehouse', '');
            }else{
                $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)
                ->pluck('name', 'id');
            }

        // $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
        return view('sales.direct.index', compact('codes', 'customers', 'products', 'warehouse', 'Accountsbelow'));
    }

    public function Warehouse_voucherNo(Request $request){
        // return $request;
        $codes = 1;
        $code = SalePurchase::where('type', 'DIRECT SALES')
        ->where('warehouse_id', $request->warehouseID)
        ->OrderBy('id', 'desc')->first();
        if ($code) {
            $codes = (int)$code->voucher_no + 1;
        }
        return Response::json(['codes' => $codes]);
    }

    public function partyLastInvoice(Request $request)
    {
        $query = SalePurchase::where('type', 'DIRECT SALES')
            ->where('party_id', $request->party_id);

        if ($request->warehouse_id) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        $last = $query->orderBy('id', 'desc')->first();

        if (!$last) {
            return Response::json(['data' => '']);
        }

        $details = SalePurchaseDetail::with('product:id,product_name,uom,code')
            ->where('sale_purchase_id', $last->id)
            ->where('type', 'DIRECT SALES')
            ->get();

        return Response::json(['data' => $details]);
    }

    // $sales = SalePurchase::whereType('DIRECT SALES')
    //     ->where('warehouse_id', Auth::User()->warehouse_id)
    //     ->OrderBy('id', 'desc')->first();
    //     if ($sales) {
    //         $codes = (int)$sales->voucher_no + 1;
    //     }

    public function edit($SaleID){
        $data = SalePurchase::whereId($SaleID)->first();
                // $DeliveryChallan = DeliveryChallan::where('type','DCNonGST')->whereStatus(0)->OrderBy('voucher_no', 'asc')->pluck('voucher_no', 'id')->prepend('Select Challan', '');
                $DeliveryChallan = DB::table('delivery_challans')
                ->join('parties', 'parties.id', '=', 'delivery_challans.party_id')
                ->select(DB::raw("delivery_challans.id,CONCAT(delivery_challans.voucher_no, '-', parties.party_name) AS  voucher_no")) 
                ->where('delivery_challans.type', 'DCNonGST')
                ->where('delivery_challans.status', 0)
                ->orderBy('delivery_challans.id', 'asc')
                ->pluck('voucher_no','delivery_challans.id')
                ->prepend('Select Challan', '');
            $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_code`, "_", `product_name`, "_", `uom`,"_", `product_price`,"_", `product_cost`) AS `id`,`product_name`'))
                ->where('warehouse_id', $request->warehouse_id)
                ->OrderBy('id', 'asc')
                ->pluck('product_name', 'id')
                ->prepend('Select Product', '');
            // $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            //     ->select('*', DB::raw("CONCAT(parties.id,'_',parties.party_name,'_',parties.address) as id,party_name"))
            //     ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            //     ->where('parties.account_group_id', '1')
            //     ->Orwhere('parties.account_group_id', '7')
            //     ->OrderBy('party_name', 'asc')
            //     ->pluck('parties.party_name', 'parties.id')
            //     ->prepend('Select Party Name', '');
            $customers = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `address`) AS `id`, `party_name`'))
                ->where('role', '=', 'Customer')
                // ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->OrderBy('party_name', 'asc')
                ->pluck('party_name', 'id')
                ->prepend('Select Party Name', '');
    
            $warehouse = Warehouse::where('id', $request->warehouse_id)->pluck('name', 'id');
            return view('sales.index', compact('data', 'customers', 'products', 'DeliveryChallan', 'warehouse',));
    }

    public function store(Request $request)
    {
        //  return $request;
        $Validator = Validator::make($request->all(), [
            'date' => 'required',
            'voucher_no' => 'required',
        ], [
            'date.required' => 'The Voucher Date field is required.'
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
        
        $SaleWarehouse = Warehouse::where('id', $request->warehouse_id)->first();
        // return $request;
        if ($request->update_voucher_id != null) {
             $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'DIRECT SALES')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            
            $salePurchase = SalePurchase::where('id', $request->update_voucher_id)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('type', 'DIRECT SALES')->first();
            
            $data = $request->all();
            $data['type'] = 'DIRECT SALES';
            $data['warehouse_id'] = $request->warehouse_id;
            $data['updated_by'] = Auth::User()->id;
            // $data['grn_dc_id'] = $request->dcn_id1;
            $salePurchase->update($data);
            // return $request;
            SalePurchaseDetail::whereType('DIRECT SALES')
            ->where('warehouse_id', $request->warehouse_id)
            ->where('sale_purchase_id', $salePurchase->id)->delete();
            GodownStockDetail::whereType('DIRECT SALES')
            ->where('warehouse_id', $request->warehouse_id)
            ->where('transaction_id', $salePurchase->id)->delete();
            // GeneralVoucher::where('v_type', 'DIRECT SALES')->where('voucher_id', $salePurchase->id)->delete();
             GeneralVoucher::where('voucher_id', $salePurchase->id)
            // ->where('warehouse_id', $request->warehouse_id)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('v_type', 'DIRECT SALES')->delete();


            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $salePurchaseDetails = new SalePurchaseDetail();
                $salePurchaseDetails->sale_purchase_id = $salePurchase->id;
                $salePurchaseDetails->voucher_no = $salePurchase->voucher_no;
                $salePurchaseDetails->date = $salePurchase->date;
                $salePurchaseDetails->type = 'DIRECT SALES';
                $salePurchaseDetails->warehouse_id = $request->warehouse_id;
                $salePurchaseDetails->party_id = $salePurchase->party_id;
                $salePurchaseDetails->product_id = $request->product_id[$i];
                // $salePurchaseDetails->demandQty = $request->demandQty[$i];
                $salePurchaseDetails->qty = $request->qty[$i];
                $salePurchaseDetails->rate = $request->price[$i];
                // $salePurchaseDetails->excl_val = $request->excl_val[$i];
                // $salePurchaseDetails->st_rate = $request->st_rate[$i];
                // $salePurchaseDetails->sale_tax = $request->sale_tax[$i];
               $salePurchaseDetails->sale_qty = $request->qty[$i];
                $salePurchaseDetails->total = $request->total[$i];
                $salePurchaseDetails->thickness = $request->thickness[$i];
                $salePurchaseDetails->discount = $request->discount[$i];
                 $salePurchaseDetails->created_by = Auth::User()->id;
                $salePurchaseDetails->save();

                $godownstockDetails=new GodownStockDetail();
                $godownstockDetails->voucher_no = $salePurchase->voucher_no;
                $godownstockDetails->transaction_id = $salePurchase->id;
                // $godownstockDetails->inward_gatepass_id = $godownstock->inward_gatepass_id;
                $godownstockDetails->date= $salePurchase->date;
                $godownstockDetails->type = 'DIRECT SALES';
                $godownstockDetails->warehouse_id = $request->warehouse_id;
                $godownstockDetails->party_id =$salePurchase->party_id;
                $godownstockDetails->product_id = $request->product_id[$i];
                $godownstockDetails->qty_in =0;
                $godownstockDetails->qty_out = $request->qty[$i];
                // $godownstockDetails->demand_qty =0;
                //  $godownstockDetails->demand_qty =$request->demandQty[$i];
                $godownstockDetails->remarks = $salePurchase->remarks;
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->save();

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                $generalVoucher->account_head_id = $salePurchase->party_id;
                $generalVoucher->other_head_id = $SaleWarehouse->sale_account_id;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->product_id = $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                // $generalVoucher->quantity = $request->qty[$i];
                $generalVoucher->quantity = $request->qty[$i];
                $generalVoucher->rate = $request->price[$i];
                $generalVoucher->v_type =  'DIRECT SALES';
                $generalVoucher->narration = 'DIRECT SALES';
                $generalVoucher->debit = $request->total[$i];
                $generalVoucher->save();

              

                 $generalVoucher = new GeneralVoucher();
                 $generalVoucher->voucher_id = $salePurchase->id;
                 $generalVoucher->account_head_id = $SaleWarehouse->sale_account_id;
                 $generalVoucher->other_head_id = $salePurchase->party_id;
                 $generalVoucher->warehouse_id =  $request->warehouse_id;
                 $generalVoucher->product_id = $request->product_id[$i];
                 $generalVoucher->date = $salePurchase->date;
                 $generalVoucher->voucher_no = $salePurchase->voucher_no;
                //  $generalVoucher->quantity = $request->qty[$i];
                 $generalVoucher->quantity = $request->qty[$i];
                 $generalVoucher->rate = $request->price[$i];
                 $generalVoucher->v_type =  'DIRECT SALES';
                $generalVoucher->narration = 'DIRECT SALES';
                 $generalVoucher->credit = $request->total[$i];
                 $generalVoucher->save();

                 if($request->discount[$i] > 0){
                    //Discount
                    $generalVoucher = new GeneralVoucher();
                    $generalVoucher->voucher_id = $salePurchase->id;
                    $generalVoucher->account_head_id = 2407;
                    $generalVoucher->other_head_id = 2407;
                    $generalVoucher->date = $salePurchase->date;
                    $generalVoucher->voucher_no = $salePurchase->voucher_no;
                    $generalVoucher->v_type = "DIRECT SALES";
                    $generalVoucher->warehouse_id =  $request->warehouse_id;
                    $generalVoucher->narration = "DISCOUNT";
                    $generalVoucher->debit = $request->discount[$i];
                    $generalVoucher->save();
                    //Discount
                    $generalVoucher = new GeneralVoucher();
                    $generalVoucher->voucher_id = $salePurchase->id;
                    $generalVoucher->account_head_id = 2407;
                    $generalVoucher->other_head_id = 2407;
                    $generalVoucher->date = $salePurchase->date;
                    $generalVoucher->voucher_no = $salePurchase->voucher_no;
                    $generalVoucher->v_type = "DIRECT SALES";
                    $generalVoucher->warehouse_id =  $request->warehouse_id;
                    $generalVoucher->narration = "DISCOUNT";
                    $generalVoucher->credit = $request->discount[$i];
                    $generalVoucher->save();
                 }
               
            }

            if($salePurchase->extra_discount > 0){
             //Discount
             $generalVoucher = new GeneralVoucher();
             $generalVoucher->voucher_id = $salePurchase->id;
             $generalVoucher->account_head_id = 2407;
             $generalVoucher->other_head_id = 2407;
             $generalVoucher->date = $salePurchase->date;
             $generalVoucher->voucher_no = $salePurchase->voucher_no;
             $generalVoucher->v_type = "DIRECT SALES";
             $generalVoucher->warehouse_id =  $request->warehouse_id;
             $generalVoucher->narration = "EXTRA DISCOUNT";
             $generalVoucher->debit = $salePurchase->extra_discount;
             $generalVoucher->save();
             //Discount
             $generalVoucher = new GeneralVoucher();
             $generalVoucher->voucher_id = $salePurchase->id;
             $generalVoucher->account_head_id = 2407;
             $generalVoucher->other_head_id = 2407;
             $generalVoucher->date = $salePurchase->date;
             $generalVoucher->voucher_no = $salePurchase->voucher_no;
             $generalVoucher->v_type = "DIRECT SALES";
             $generalVoucher->warehouse_id =  $request->warehouse_id;
             $generalVoucher->narration = "EXTRA DISCOUNT";
             $generalVoucher->credit = $salePurchase->extra_discount;
             $generalVoucher->save();
            }

             if($salePurchase->extra_charges > 0){
                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                $generalVoucher->account_head_id = 2572;
                $generalVoucher->other_head_id = 2572;
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->v_type = "DIRECT SALES";
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->narration = "EXTRA CHARGES";
                $generalVoucher->debit = $salePurchase->extra_charges;
                $generalVoucher->save();
    
                $generalVoucher = new GeneralVoucher();
               $generalVoucher->voucher_id = $salePurchase->id;
               $generalVoucher->account_head_id = 2572;
               $generalVoucher->other_head_id = 2572;
               $generalVoucher->date = $salePurchase->date;
               $generalVoucher->voucher_no = $salePurchase->voucher_no;
               $generalVoucher->v_type = "DIRECT SALES";
               $generalVoucher->warehouse_id =  $request->warehouse_id;
               $generalVoucher->narration = "EXTRA CHARGES";
               $generalVoucher->credit = $salePurchase->extra_charges;
               $generalVoucher->save();
             }
            

          
            Session::flash('flash_message', 'Sale Voucher Updated Successfully!');
            return redirect('direct-sales');
            return redirect()->back()->with('flash_message', 'Sale Voucher Updated Successfully!');
        } else {

             $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'DIRECT SALES')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            $codes = 1;
           
              $sales = SalePurchase::whereType('DIRECT SALES')
            ->where('warehouse_id', $request->warehouse_id)
            ->OrderBy('id', 'desc')->first();
            
            if ($sales) {
                $codes = (int)$sales->voucher_no + 1;
            }
            
             $data = $request->all();
            $data['warehouse_id'] = $request->warehouse_id;
            $data['created_by'] = Auth::User()->id;
            $data['voucher_no'] = $codes;
            $data['type'] = 'DIRECT SALES';
            // $data['grn_dc_id'] = $request->dcn_id;
            // $data['challan_type'] = $request->challantype;
            $salePurchase = SalePurchase::create($data);
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $salePurchaseDetails = new SalePurchaseDetail();
                $salePurchaseDetails->sale_purchase_id = $salePurchase->id;
                $salePurchaseDetails->voucher_no = $salePurchase->voucher_no;
                $salePurchaseDetails->date = $salePurchase->date;
                $salePurchaseDetails->type = 'DIRECT SALES';
                $salePurchaseDetails->warehouse_id = $request->warehouse_id;
                $salePurchaseDetails->party_id = $salePurchase->party_id;
                $salePurchaseDetails->product_id = $request->product_id[$i];
                // $salePurchaseDetails->demandQty = $request->demandQty[$i];
                $salePurchaseDetails->qty = $request->qty[$i];
                $salePurchaseDetails->rate = $request->price[$i];
                // $salePurchaseDetails->excl_val = $request->excl_val[$i];
                // $salePurchaseDetails->st_rate = $request->st_rate[$i];
                // $salePurchaseDetails->sale_tax = $request->sale_tax[$i];
               $salePurchaseDetails->sale_qty = $request->qty[$i];
                $salePurchaseDetails->total = $request->total[$i];
                $salePurchaseDetails->thickness = $request->thickness[$i];
                $salePurchaseDetails->discount = $request->discount[$i];
                 $salePurchaseDetails->created_by = Auth::User()->id;
                $salePurchaseDetails->save();

                $godownstockDetails=new GodownStockDetail();
                $godownstockDetails->voucher_no = $salePurchase->voucher_no;
                $godownstockDetails->transaction_id = $salePurchase->id;
                // $godownstockDetails->inward_gatepass_id = $godownstock->inward_gatepass_id;
                $godownstockDetails->date= $salePurchase->date;
                $godownstockDetails->type = 'DIRECT SALES';
                $godownstockDetails->warehouse_id = $request->warehouse_id;
                $godownstockDetails->party_id =$salePurchase->party_id;
                $godownstockDetails->product_id = $request->product_id[$i];
                $godownstockDetails->qty_in =0;
                $godownstockDetails->qty_out = $request->qty[$i];
                // $godownstockDetails->demand_qty =0;
                //  $godownstockDetails->demand_qty =$request->demandQty[$i];
                $godownstockDetails->remarks = $salePurchase->remarks;
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->save();

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                $generalVoucher->account_head_id = $salePurchase->party_id;
                $generalVoucher->other_head_id = $SaleWarehouse->sale_account_id;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->product_id = $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                // $generalVoucher->quantity = $request->qty[$i];
                $generalVoucher->quantity = $request->qty[$i];
                $generalVoucher->rate = $request->price[$i];
                $generalVoucher->v_type =  'DIRECT SALES';
                $generalVoucher->narration = 'DIRECT SALES';
                $generalVoucher->debit = $request->total[$i];
                $generalVoucher->save();

              

                 $generalVoucher = new GeneralVoucher();
                 $generalVoucher->voucher_id = $salePurchase->id;
                 $generalVoucher->account_head_id = $SaleWarehouse->sale_account_id;
                 $generalVoucher->other_head_id = $salePurchase->party_id;
                 $generalVoucher->warehouse_id =  $request->warehouse_id;
                 $generalVoucher->product_id = $request->product_id[$i];
                 $generalVoucher->date = $salePurchase->date;
                 $generalVoucher->voucher_no = $salePurchase->voucher_no;
                //  $generalVoucher->quantity = $request->qty[$i];
                 $generalVoucher->quantity = $request->qty[$i];
                 $generalVoucher->rate = $request->price[$i];
                 $generalVoucher->v_type =  'DIRECT SALES';
                $generalVoucher->narration = 'DIRECT SALES';
                 $generalVoucher->credit = $request->total[$i];
                 $generalVoucher->save();

                 if($request->discount[$i] > 0){
                    //Discount
                    $generalVoucher = new GeneralVoucher();
                    $generalVoucher->voucher_id = $salePurchase->id;
                    $generalVoucher->account_head_id = $salePurchase->party_id;
                    $generalVoucher->other_head_id = 2407;
                    $generalVoucher->date = $salePurchase->date;
                    $generalVoucher->voucher_no = $salePurchase->voucher_no;
                    $generalVoucher->v_type = "DIRECT SALES";
                    $generalVoucher->warehouse_id =  $request->warehouse_id;
                    $generalVoucher->narration = "DISCOUNT";
                    $generalVoucher->debit = $request->discount[$i];
                    $generalVoucher->save();
                    //Discount
                    $generalVoucher = new GeneralVoucher();
                    $generalVoucher->voucher_id = $salePurchase->id;
                    $generalVoucher->account_head_id = 2407;
                    $generalVoucher->other_head_id = $salePurchase->party_id;
                    $generalVoucher->date = $salePurchase->date;
                    $generalVoucher->voucher_no = $salePurchase->voucher_no;
                    $generalVoucher->v_type = "DIRECT SALES";
                    $generalVoucher->warehouse_id =  $request->warehouse_id;
                    $generalVoucher->narration = "DISCOUNT";
                    $generalVoucher->debit = $request->discount[$i];
                    $generalVoucher->save();
                 }
               
            }

            if($salePurchase->extra_discount > 0){
             //Discount
             $generalVoucher = new GeneralVoucher();
             $generalVoucher->voucher_id = $salePurchase->id;
             $generalVoucher->account_head_id = $salePurchase->party_id;
             $generalVoucher->other_head_id = 2407;
             $generalVoucher->date = $salePurchase->date;
             $generalVoucher->voucher_no = $salePurchase->voucher_no;
             $generalVoucher->v_type = "DIRECT SALES";
             $generalVoucher->warehouse_id =  $request->warehouse_id;
             $generalVoucher->narration = "DISCOUNT";
             $generalVoucher->debit = $salePurchase->extra_discount;
             $generalVoucher->save();
             //Discount
             $generalVoucher = new GeneralVoucher();
             $generalVoucher->voucher_id = $salePurchase->id;
             $generalVoucher->account_head_id = 2407;
             $generalVoucher->other_head_id = $salePurchase->party_id;
             $generalVoucher->date = $salePurchase->date;
             $generalVoucher->voucher_no = $salePurchase->voucher_no;
             $generalVoucher->v_type = "DIRECT SALES";
             $generalVoucher->warehouse_id =  $request->warehouse_id;
             $generalVoucher->narration = "DISCOUNT";
             $generalVoucher->debit = $salePurchase->extra_discount;
             $generalVoucher->save();
            }

             if($salePurchase->extra_charges > 0){
                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                $generalVoucher->account_head_id = $salePurchase->party_id;
                $generalVoucher->other_head_id = 2572;
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->v_type = "DIRECT SALES";
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->narration = "EXTRA CHARGES";
                $generalVoucher->debit = $salePurchase->extra_charges;
                $generalVoucher->save();
    
                $generalVoucher = new GeneralVoucher();
               $generalVoucher->voucher_id = $salePurchase->id;
               $generalVoucher->account_head_id = 2572;
               $generalVoucher->other_head_id = $salePurchase->party_id;
               $generalVoucher->date = $salePurchase->date;
               $generalVoucher->voucher_no = $salePurchase->voucher_no;
               $generalVoucher->v_type = "DIRECT SALES";
               $generalVoucher->warehouse_id =  $request->warehouse_id;
               $generalVoucher->narration = "EXTRA CHARGES";
               $generalVoucher->credit = $salePurchase->extra_charges;
               $generalVoucher->save();
             }
           
          
            // return $request->all();
            // $record = DeliveryChallan::where('id', $request->dcn_id)->first();
            // $record->update(['status' => 1]);
            Session::flash('flash_message', 'Sale Voucher Added Successfully!');
            return redirect('direct-sales');
            return redirect()->back()->with('flash_message', 'Sale Voucher Added Successfully!');
        }
    }

    public function editData(Request $request)
    {
        $editdata = SalePurchase::whereType('DIRECT SALES')
        ->where('warehouse_id', $request->warehouseID)
        ->where('voucher_no', $request->voucher_no)->first();
        if ($editdata) {
            $edit = SalePurchase::with(['sale_purchase_details' => function($query){
                $query->with('product:id,product_name,uom,tax,code,packing');
            }])
            ->with('party:id,party_name,address')
            ->where('id', $editdata->id)
            ->where('warehouse_id', $request->warehouseID)
            ->whereType('DIRECT SALES')
            ->first();
            return Response::json(['data' => $edit]);
           
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        // return "d";
        $prevoucher = SalePurchase::where('voucher_no', '<', $request->voucher_no)
        ->where('warehouse_id', $request->warehouseID)
        ->whereType('DIRECT SALES')->max('voucher_no');
         $sale = SalePurchase::where('voucher_no', $prevoucher)->whereType('DIRECT SALES')->first();
        if ($sale) {
            $edit = SalePurchase::with(['sale_purchase_details' => function($query){
                $query->with('product:id,product_name,uom,tax,code,packing');
            }])
            ->with('party:id,party_name,address')
            ->where('voucher_no', $prevoucher)
            ->where('warehouse_id', $request->warehouseID)
            ->whereType('DIRECT SALES')
            ->first();
            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $nextvoucher = SalePurchase::whereType('DIRECT SALES')
        ->where('warehouse_id', $request->warehouseID)
        ->where('voucher_no', '>', $request->voucher_no)->min('voucher_no');
        $sale = SalePurchase::where('voucher_no', $nextvoucher)->whereType('DIRECT SALES')->first();
        if ($sale) {
            $edit = SalePurchase::with(['sale_purchase_details' => function($query){
                $query->with('product:id,product_name,uom,tax,code,packing');
            }])
            ->with('party:id,party_name,address')
            ->where('voucher_no', $nextvoucher)
            ->where('warehouse_id', $request->warehouseID)
            ->whereType('DIRECT SALES')
            ->first();
            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function DeleteVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'DIRECT SALES')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        // return $request;
         $sale = SalePurchase::whereType('DIRECT SALES')
        ->where('warehouse_id', $request->delete_warehouse_id)
        ->where('voucher_no', $request->delete_voucher_no)->first();
        if ($sale) {
            SalePurchase::where('id', $sale->id)->where('type', 'DIRECT SALES')->delete();
            SalePurchaseDetail::whereType('DIRECT SALES')
            ->where('warehouse_id', $request->delete_warehouse_id)
            ->where('sale_purchase_id', $sale->id)->delete();
            // GeneralVoucher::where('v_type', 'DIRECT SALES')->where('voucher_id', $sale->id)->delete();
            GeneralVoucher::where('voucher_id', $sale->id)
            // ->where('warehouse_id', $request->warehouse_id)
            ->where('warehouse_id', $request->delete_warehouse_id)
            ->where('v_type', 'DIRECT SALES')->delete();
            GodownStockDetail::whereType('DIRECT SALES')
            ->where('warehouse_id', $request->delete_warehouse_id)
            ->where('transaction_id', $sale->id)->delete();
            Session::flash('flash_message', 'Sale Voucher Deleted Successfully!');
            return redirect('direct-sales');
            // return redirect()->back()->with('flash_message', 'Sale Invoice Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }

    public function report(Request $request)
    {
        if ($request->ajax()) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $party_id = $request->party_id;
            $reportType = $request->report_type;
            if ($reportType == 'summary') {
                $summaryReport = SalePurchaseDetail::join('products', 'products.id', '=', 'sale_purchase_details.product_id')
                    ->join('parties', 'parties.id', '=', 'sale_purchase_details.party_id')
                    ->select(
                        'parties.party_name',
                        DB::raw('SUM(sale_purchase_details.qty) as qty'),
                        DB::raw('SUM(sale_purchase_details.total) as total'),
                        'sale_purchase_details.date',
                        'sale_purchase_details.voucher_no',
                    )
                    ->where('sale_purchase_details.party_id', $party_id)
                    ->whereDate('sale_purchase_details.date', '>=', $fromDate)
                    ->whereDate('sale_purchase_details.date', '<=', $toDate)
                    ->groupBy('parties.party_name')
                    ->orderBy('parties.id')
                    ->get();
                return response()->json(['data' => $summaryReport]);
            }
            if ($reportType == 'detail') {
                $detailReport = SalePurchaseDetail::with('party:id,party_name', 'product:id,product_name,code,uom')
                    ->where('party_id', $party_id)
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    ->get();
                return response()->json(['data' => $detailReport]);
            }
        }
        $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->where('parties.account_group_id', '1')
            ->Orwhere('parties.account_group_id', '7')
            ->OrderBy('party_name', 'asc')
            ->pluck('parties.party_name', 'parties.id')
            ->prepend('Select Party Name', '');

        return view('sales.report', compact('customers'));
    }

    public function PrintVoucher(Request $request)
    {
        // return $request;
        File::cleanDirectory(base_path() . '/upload/sales-voucher');
        $voucher_no = $request->voucher_no;
         $salevoucher = SalePurchase::where('type', 'DIRECT SALES')
        ->where('warehouse_id', $request->warehouseID)
        ->where('voucher_no', $voucher_no)->first();
        if ($salevoucher) {
                $salevoucherDetails = SalePurchaseDetail::with(['salepurchase' => function ($qry) {
                    $qry->with('dc');
                }])
                    ->with('product:id,product_name,uom,tax,code,packing')
                    ->where('sale_purchase_id', $salevoucher->id)
                    ->where('warehouse_id', $request->warehouseID)
                    ->where('type', 'DIRECT SALES')
                    ->orderBy('id', 'asc')
                    ->get();
            
            $warehouseTitle = Setting::first();
            $warehouse = Warehouse::where('id', $request->warehouseID)->first();
            $pdf = PDF::loadView('sales.direct.invoice', compact('salevoucherDetails', 'warehouseTitle', 'warehouse'));
            $fileName =  'sales-voucher' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/sales-voucher/' . $fileName));
            return $fileName;
        } else {
            return false;
        }
    }

}
