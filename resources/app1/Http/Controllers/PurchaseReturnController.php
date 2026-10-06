<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Vouchers;
use App\Models\Warehouse;
use App\Models\StockDetails;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\GeneralVoucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\RequestGenerateDetails;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables as DataTables;

class PurchaseReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $purchase = Stock::whereType('Purchase Return')->OrderBy('id', 'desc')->first();
        $codes = 1;
        if ($purchase) {
            $codes = (int)$purchase->voucher_no + 1;
        }

        $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_name`, "_", `uom`,"_", `product_price`, "_", `product_cost`, "_", `pack_type`, "_",`pack_weight`) AS `id`,`product_name`'))
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->OrderBy('id', 'asc')
        ->pluck('product_name', 'id')
        ->prepend('Select Product', '');

        $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            ->select('*', DB::raw("CONCAT(parties.id,'_',parties.party_name,'_',parties.address) as id,party_name"))
            ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->where('parties.account_group_id', '1')
            ->Orwhere('parties.account_group_id', '7')
            ->OrderBy('party_name', 'asc')
            ->pluck('parties.party_name', 'parties.id')
            ->prepend('Select Party Name', '');

        $warehouse = Warehouse::select(DB::raw('CONCAT(`id`, "_", `name`) AS `id`, `name`'))->OrderBy('name', 'asc')->pluck('name', 'id')->prepend('Select Godown', '');
        $transaction_type = array('' => 'Select Type', 'Cash' => 'Cash', 'Credit' => 'Credit');
        $suppliers = Party::whereRole('Supplier')->pluck('party_name', 'id')->prepend('Select Supplier', '');
        $purchasers = Party::whereRole('Purchaser')->pluck('party_name', 'id')->prepend('Select Purchaser', '');

        return view('purchase-return.index', compact('codes', 'customers', 'products', 'warehouse', 'transaction_type', 'suppliers', 'purchasers'));
    }

    public function store(Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'date' => 'required',
            'voucher_no' => 'required',
            'party_name' => 'required'
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
        if (!isset($request->price)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }


        if ($request->update_voucher_id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'PURCHASE RETURN')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }


            $stock = Stock::find($request->update_voucher_id);

            $data = $request->all();
            $data['warehouse_id'] = $request->warehouse_id[0];
            $data['updated_by'] = Auth::User()->id;
            $stock->update($data);

            StockDetails::whereType('Purchase Return')->where('stock_id', $stock->id)->delete();
            Vouchers::where('v_type', 'Purchase Return')->where('voucher_no', $stock->voucher_no)->delete();
            GeneralVoucher::where('v_type', 'Purchase Return')->where('voucher_id', $stock->id)->delete();
            RequestGenerateDetails::where('request_generate_id', $stock->id)->delete();

            $sum = 0;
            $totalQty = 0;
            $totalAmount = 0;
            $totalSaleRate = 0;
            $totalNetWeight = 0;

            $count = count($request->price);
            for ($i = 0; $i < $count; $i++) {
                $stockDetails = new StockDetails();
                $stockDetails->stock_id = $stock->id;
                $stockDetails->voucher_no = $stock->voucher_no;
                $stockDetails->date = $stock->date;
                $stockDetails->type = $stock->type;
                $stockDetails->transaction_type = $stock->transaction_type;
                $stockDetails->dcn_no = $stock->dcn_no;
                $stockDetails->warehouse_id = $stock->warehouse_id;
                $stockDetails->party_id = $stock->party_id;
                $stockDetails->product_id = $request->product_id[$i];
                $stockDetails->unit_id =1;
                $stockDetails->discount_id = $request->discount_id[$i];
                $stockDetails->packing = $request->packing[$i];
                $stockDetails->net_weight = $request->net_weight[$i];
                $stockDetails->qty_in = 0;
                $stockDetails->qty_out = $request->qty[$i];
                $stockDetails->product_cost = $request->product_cost[$i];
                $stockDetails->cost_amount = $request->product_cost[$i] * $request->qty[$i];
                $stockDetails->sale_rate = $request->price[$i];
                $stockDetails->sale_amount = $request->price[$i] * $request->qty[$i];
                $stockDetails->packing = $request->packing[$i];
                $stockDetails->net_weight = $request->net_weight[$i];
                $stockDetails->created_by = $stock->created_by;
                $stockDetails->save();


                $sum = $sum + $request->total[$i];
                $totalQty +=  $request->qty[$i];
                $totalAmount +=  $request->product_cost[$i] * $request->qty[$i];
                $totalSaleRate +=  $request->price[$i] * $request->qty[$i];
                $totalNetWeight += $request->net_weight[$i];


                $requestGenerateDetails = new RequestGenerateDetails();
                $requestGenerateDetails->request_generate_id = $stock->id;
                $requestGenerateDetails->bill_no = $stock->voucher_no;
                $requestGenerateDetails->date = $request->date;
                $requestGenerateDetails->product_code = 0;
                $requestGenerateDetails->supplier_id = $request->supplier_id;
                $requestGenerateDetails->purchaser_id = $request->purchaser_id;
                $requestGenerateDetails->product_id = $request->product_id[$i];
                $requestGenerateDetails->qty = $request->qty[$i];
                $requestGenerateDetails->unit = 1;
                $requestGenerateDetails->comments = null;
                $requestGenerateDetails->created_by = Auth::User()->id;
                $requestGenerateDetails->updated_by = Auth::User()->id;
                $requestGenerateDetails->warehouse_id = Auth::User()->warehouse_id;
                $requestGenerateDetails->status = 1;
                $requestGenerateDetails->type = "Purchase Return";
                $requestGenerateDetails->save();
            }

            $voucherData = new Vouchers();
            $voucherData->account_id = $stock->party_id;
            $voucherData->shop_id = Auth::User()->warehouse_id;
            $voucherData->voucher_no = $stock->voucher_no;
            $voucherData->voucher_date = $stock->date;
            $voucherData->v_type = "Purchase Return";
            $voucherData->total_credit = $sum;
            $voucherData->biller = Auth::User()->id;
            $voucherData->status = 1;
            $voucherData->save();

            $voucherData = new Vouchers();
            $voucherData->account_id = 2;
            $voucherData->shop_id = Auth::User()->warehouse_id;
            $voucherData->voucher_no = $stock->voucher_no;
            $voucherData->voucher_date = $stock->date;
            $voucherData->v_type = "Purchase Return";
            $voucherData->total_debit = $sum;
            $voucherData->biller = Auth::User()->id;
            $voucherData->status = 1;
            $voucherData->save();


            $vouchers = new GeneralVoucher();
            $vouchers->voucher_id = $stock->id;
            $vouchers->account_head_id = $request->party_id;
            $vouchers->date = $request->date;
            $vouchers->voucher_no = $stock->voucher_no;
            $vouchers->v_type = "Purchase Return";
            $vouchers->credit = $sum;
            $vouchers->status = 1;
            $vouchers->save();


            $vouchers = new GeneralVoucher();
            $vouchers->voucher_id = $stock->id;
            $vouchers->account_head_id = 2;
            $vouchers->date = $request->date;
            $vouchers->voucher_no = $stock->voucher_no;
            $vouchers->v_type = "Purchase Return";
            $vouchers->debit = $sum;
            $vouchers->status = 1;
            $vouchers->save();

            $stock->total_qty = $totalQty;
            $stock->total_amount = $totalAmount;
            $stock->total_sale_rate = $totalSaleRate;
            $stock->total_net_weight = $totalNetWeight;
            $stock->save();

            return redirect()->back()->with('flash_message', 'Purchase Return Voucher Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'PURCHASE RETURN')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            $purchase = Stock::whereType('Purchase Return')->OrderBy('id', 'desc')->first();
            $codes = 1;
            if ($purchase) {
                $codes = (int)$purchase->voucher_no + 1;
            }

            $data = $request->all();
            $data['voucher_no']=$codes;
            $data['warehouse_id'] = $request->warehouse_id[0];
            $data['created_by'] = Auth::User()->id;
            $stock = Stock::create($data);


            $sum = 0;
            $totalQty = 0;
            $totalAmount = 0;
            $totalSaleRate = 0;
            $totalNetWeight = 0;

            $count = count($request->price);
            for ($i = 0; $i < $count; $i++) {
                $stockDetails = new StockDetails();
                $stockDetails->stock_id = $stock->id;
                $stockDetails->voucher_no = $stock->voucher_no;
                $stockDetails->date = $stock->date;
                $stockDetails->type = $stock->type;
                $stockDetails->transaction_type = $stock->transaction_type;
                $stockDetails->dcn_no = $stock->dcn_no;
                $stockDetails->warehouse_id = $request->warehouse_id[$i];
                $stockDetails->party_id = $stock->party_id;
                $stockDetails->product_id = $request->product_id[$i];
                $stockDetails->unit_id = 1;
                $stockDetails->discount_id = $request->discount_id[$i];
                $stockDetails->qty_in = 0;
                $stockDetails->qty_out = $request->qty[$i];
                $stockDetails->product_cost = $request->product_cost[$i];
                $stockDetails->cost_amount = $request->product_cost[$i] * $request->qty[$i];
                $stockDetails->sale_rate = $request->price[$i];
                $stockDetails->sale_amount = $request->price[$i] * $request->qty[$i];
                $stockDetails->packing = $request->packing[$i];
                $stockDetails->net_weight = $request->net_weight[$i];
                $stockDetails->created_by = $stock->created_by;
                $stockDetails->save();


                $sum = $sum + $request->total[$i];
                $totalQty +=  $request->qty[$i];
                $totalAmount +=  $request->product_cost[$i] * $request->qty[$i];
                $totalSaleRate +=  $request->price[$i] * $request->qty[$i];
                $totalNetWeight += $request->net_weight[$i];


                $requestGenerateDetails = new RequestGenerateDetails();
                $requestGenerateDetails->request_generate_id = $stock->id;
                $requestGenerateDetails->bill_no = $stock->voucher_no;
                $requestGenerateDetails->date = $request->date;
                $requestGenerateDetails->product_code =0;
                $requestGenerateDetails->supplier_id = $request->supplier_id;
                $requestGenerateDetails->purchaser_id = $request->purchaser_id;
                $requestGenerateDetails->product_id = $request->product_id[$i];
                $requestGenerateDetails->provided_qty = $request->qty[$i];
                $requestGenerateDetails->unit = 1;
                $requestGenerateDetails->comments = null;
                $requestGenerateDetails->created_by = Auth::User()->id;
                $requestGenerateDetails->updated_by = Auth::User()->id;
                $requestGenerateDetails->warehouse_id = $request->warehouse_id[$i];
                $requestGenerateDetails->status = 1;
                $requestGenerateDetails->type = "Purchase Return";
                $requestGenerateDetails->save();
            }

            $voucherData = new Vouchers();
            $voucherData->account_id = $stock->party_id;
            $voucherData->shop_id = Auth::User()->warehouse_id;
            $voucherData->voucher_no = $stock->voucher_no;
            $voucherData->voucher_date = $stock->date;
            $voucherData->v_type = "Purchase Return";
            $voucherData->total_credit = $sum;
            $voucherData->biller = Auth::User()->id;
            $voucherData->status = 1;
            $voucherData->save();

            $voucherData = new Vouchers();
            $voucherData->account_id = 2;
            $voucherData->shop_id = Auth::User()->warehouse_id;
            $voucherData->voucher_no = $stock->voucher_no;
            $voucherData->voucher_date = $stock->date;
            $voucherData->v_type = "Purchase Return";
            $voucherData->total_debit = $sum;
            $voucherData->biller = Auth::User()->id;
            $voucherData->status = 1;
            $voucherData->save();


            $vouchers = new GeneralVoucher();
            $vouchers->voucher_id = $stock->id;
            $vouchers->account_head_id = $request->party_id;
            $vouchers->date = $request->date;
            $vouchers->voucher_no = $stock->voucher_no;
            $vouchers->v_type = "Purchase Return";
            $vouchers->credit = $sum;
            $vouchers->status = 1;
            $vouchers->save();


            $vouchers = new GeneralVoucher();
            $vouchers->voucher_id = $stock->id;
            $vouchers->account_head_id = 2;
            $vouchers->date = $request->date;
            $vouchers->voucher_no = $stock->voucher_no;
            $vouchers->v_type = "Purchase Return";
            $vouchers->debit = $sum;
            $vouchers->status = 1;
            $vouchers->save();

            $stock->total_qty = $totalQty;
            $stock->total_amount = $totalAmount;
            $stock->total_sale_rate = $totalSaleRate;
            $stock->total_net_weight = $totalNetWeight;
            $stock->save();

            return redirect()->back()->with('flash_message', 'Purchase Return Voucher Added Successfully!');
        }
        abort(500);
    }

    public function DeleteVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PURCHASE RETURN')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        $stock = Stock::whereType('Purchase Return')->where('voucher_no', $request->delete_voucher_no)->first();
        
        if ($stock) {
            Stock::findOrFail($stock->id)->delete();
            StockDetails::whereType('Purchase Return')->where('stock_id', $stock->id)->delete();
            Vouchers::where('v_type', 'Purchase Return')->where('voucher_no', $stock->voucher_no)->delete();
            GeneralVoucher::where('v_type', 'Purchase Return')->where('voucher_id', $stock->id)->delete();

            return redirect()->back()->with('flash_message', 'Purchase Return Voucher Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }

    public function editData(Request $request)
    {
        $edit = Stock::whereType('Purchase Return')->where('voucher_no', $request->voucher_no)->first();

        if ($edit) {
            $edit = StockDetails::with('products')
                ->with(['stock' => function ($query) {
                    $query->with('supplier:id,party_name','purchaser:id,party_name','parties:id,party_name,address');
                }])
                ->with('warehouse')
                ->whereType('Purchase Return')
                ->where('stock_id', $edit->id)
                ->get();

            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        $purchase = Stock::whereType('Purchase Return')->where('voucher_no', '<', $request->voucher_no)->max('voucher_no');

        if ($purchase) {
            $data = StockDetails::with('products')
                ->with(['stock' => function ($query) {
                    $query->with('supplier:id,party_name','purchaser:id,party_name','parties:id,party_name,address');
                }])
                ->with('warehouse')
                ->whereType('Purchase Return')
                ->where('voucher_no', $purchase)
                ->get();
            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $purchase = Stock::whereType('Purchase Return')->where('voucher_no', '>', $request->voucher_no)->min('voucher_no');

        if ($purchase) {
            $data = StockDetails::with('products')
                ->with(['stock' => function ($query) {
                    $query->with('supplier:id,party_name','purchaser:id,party_name','parties:id,party_name,address');
                }])
                ->with('warehouse')
                ->whereType('Purchase Return')
                ->where('voucher_no', $purchase)
                ->get();
            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function report(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PURCHASE VOUCHER')
            ->where('right_name', 'PRINT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        if ($request->ajax()) {
            if ($request->report_type == 'summary') {
                $purchase = Purchase::with('parties')
                    ->where('purchase_type', 'Purchase')
                    ->where('party_id', $request->party_id)
                    ->whereDate('date', '>=', $request->from_date)
                    ->whereDate('date', '<=', $request->to_date)
                    ->get();
                return DataTables::of($purchase)
                    ->addIndexColumn()
                    ->addColumn('date', function ($row) {
                        $date = date('d/m/Y', strtotime($row->date));
                        return $date;
                    })
                    ->addColumn('party_id', function ($data) {
                        $party = $data->parties->party_name;
                        return $party;
                    })
                    ->make(true);
            }
        }


        $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            ->pluck('parties.party_name', 'parties.id')
            ->prepend('Select Party Name', '');
        return view('purchases.report', compact('customers'));
    }
    public function PrintVoucher(Request $request)
    {
        File::cleanDirectory(base_path() .'/upload/purchase-return');
        $voucher_no = $request->voucher_no;
        $purchase = Stock::where('type', 'Purchase Return')->where('voucher_no', $voucher_no)->first();
        if ($purchase) {
            $purchaseDetails = StockDetails::with(['stock'=>function($qry){
                            $qry->with('parties:id,party_name,address','supplier:id,party_name','purchaser:id,party_name');
            }])
                ->with('products:id,product_name,uom','warehouse:id,name')
                ->where('voucher_no', $voucher_no)
                ->where('type', 'Purchase Return')
                ->orderBy('id', 'asc')
                ->get();

            $pdf = PDF::loadView('purchase-return.invoice', compact('purchaseDetails'));
            $fileName =  'purchase-return' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/purchase-return/' . $fileName));
            return $fileName;
        } else{
            return false;
        }
    }
}