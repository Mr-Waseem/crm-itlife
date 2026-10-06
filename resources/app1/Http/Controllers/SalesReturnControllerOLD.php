<?php

namespace App\Http\Controllers;

use App\Models\UOM;
use App\Models\Party;
use App\Models\Sales;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Vouchers;
use App\Models\Warehouse;
use App\Models\SaleDetail;
use App\Models\StockDetails;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\GeneralVoucher;
use App\Models\DeliveryChallan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables as DataTables;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SalesReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $codes = 1;
        $sales = Stock::whereType('Sales Return')->OrderBy('id', 'desc')->first();
        if ($sales) {
            $codes = (int)$sales->voucher_no + 1;
        }

        $DeliveryChallan = DeliveryChallan::OrderBy('voucher_no', 'asc')->pluck('voucher_no', 'voucher_no')->prepend('Select Challan', '');
        $products = Product::select(DB::raw('CONCAT(`id`, "_",`product_name`, "_", `uom`, "_", `product_price`, "_", `product_cost`) AS `id`, `product_name`'))
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

        $uoms = UOM::select(DB::raw('CONCAT(`id`, "_", `uom`) AS `id`, `uom`'))->OrderBy('id', 'asc')->pluck('uom', 'id')->prepend('GRAMS', '9_GRAMS');
        $warehouse = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id');
        $transaction_type = array('' => 'Select Type', 'Cash' => 'Cash', 'Credit' => 'Credit');

        return view('sales-return.index', compact('codes', 'customers', 'products', 'DeliveryChallan', 'uoms', 'warehouse', 'transaction_type'));
    }

    public function store(Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'date' => 'required',
            'voucher_no' => 'required',
            'party_name' => 'required',
            'transaction_type' => 'required'
        ], [
            'date.required' => 'The Voucher Date field is required.',
            'voucher_no.required' => 'The Voucher No field is required.',
            'transaction_type.required' => 'The Type field is required.'
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
                ->where('voucher_name', 'SALE RETURN')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }


            $stock = Stock::find($request->update_voucher_id);

            $data = $request->all();
            $data['dcn_no'] = $request->dcn_no1;
            $data['updated_by'] = Auth::User()->id;
            $stock->update($data);

            StockDetails::whereType('Sales Return')->where('stock_id', $stock->id)->delete();
            Vouchers::where('v_type', 'Sales Return')->where('voucher_no', $stock->voucher_no)->delete();
            GeneralVoucher::where('v_type', 'Sales Return')->where('voucher_id', $stock->id)->delete();

            $sum = 0;
            $totalQty = 0;
            $totalAmount = 0;
            $totalSaleRate = 0;

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
                $stockDetails->unit_id = 1;
                $stockDetails->discount_id = $request->discount_id[$i];
                $stockDetails->qty_in = $request->qty[$i];
                $stockDetails->qty_out = 0;
                $stockDetails->product_cost = $request->product_cost[$i];
                $stockDetails->cost_amount = $request->product_cost[$i] * $request->qty[$i];
                $stockDetails->sale_rate = $request->price[$i];
                $stockDetails->sale_amount = $request->price[$i] * $request->qty[$i];
                $stockDetails->created_by = $stock->created_by;
                $stockDetails->save();


                $sum = $sum + $request->total[$i];
                $totalQty +=  $request->qty[$i];
                $totalAmount +=  $request->product_cost[$i] * $request->qty[$i];
                $totalSaleRate +=  $request->price[$i] * $request->qty[$i];
            }

            $voucherData = new Vouchers();
            $voucherData->account_id = $stock->party_id;
            $voucherData->shop_id = Auth::User()->warehouse_id;
            $voucherData->voucher_no = $stock->voucher_no;
            $voucherData->voucher_date = $stock->date;
            $voucherData->v_type = "Sale Return";
            $voucherData->total_debit = $sum;
            $voucherData->biller = Auth::User()->id;
            $voucherData->status = 1;
            $voucherData->save();

            $voucherData = new Vouchers();
            $voucherData->account_id = 3;
            $voucherData->shop_id = Auth::User()->warehouse_id;
            $voucherData->voucher_no = $stock->voucher_no;
            $voucherData->voucher_date = $stock->date;
            $voucherData->v_type = "Sale Return";
            $voucherData->total_credit = $sum;
            $voucherData->biller = Auth::User()->id;
            $voucherData->status = 1;
            $voucherData->save();


            $vouchers = new GeneralVoucher();
            $vouchers->voucher_id = $stock->id;
            $vouchers->account_head_id = $request->party_id;
            $vouchers->date = $request->date;
            $vouchers->voucher_no = $stock->voucher_no;
            $vouchers->v_type = "Sale Return";
            $vouchers->debit = $sum;
            $vouchers->status = 1;
            $vouchers->save();


            $vouchers = new GeneralVoucher();
            $vouchers->voucher_id = $stock->id;
            $vouchers->account_head_id = 3;
            $vouchers->date = $request->date;
            $vouchers->voucher_no = $stock->voucher_no;
            $vouchers->v_type = "Sale Return";
            $vouchers->credit = $sum;
            $vouchers->status = 1;
            $vouchers->save();



            $stock->total_qty = $totalQty;
            $stock->total_amount = $totalAmount;
            $stock->total_sale_rate = $totalSaleRate;
            $stock->save();

            return redirect()->back()->with('flash_message', 'Sale Return Invoice Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'SALE RETURN')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            $codes = 1;
            $sales = Stock::whereType('Sales Return')->OrderBy('id', 'desc')->first();
            if ($sales) {
                $codes = (int)$sales->voucher_no + 1;
            }
            $data = $request->all();
            $data['created_by'] = Auth::User()->id;
            $data['voucher_no']=$codes;
            $stock = Stock::create($data);

            $sum = 0;
            $totalQty = 0;
            $totalAmount = 0;
            $totalSaleRate = 0;

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
                $stockDetails->unit_id = 1;
                $stockDetails->discount_id = $request->discount_id[$i];
                $stockDetails->qty_in = $request->qty[$i];
                $stockDetails->qty_out = 0;
                $stockDetails->product_cost = $request->product_cost[$i];
                $stockDetails->cost_amount = $request->product_cost[$i] * $request->qty[$i];
                $stockDetails->sale_rate = $request->price[$i];
                $stockDetails->sale_amount = $request->price[$i] * $request->qty[$i];
                $stockDetails->created_by = $stock->created_by;
                $stockDetails->save();


                $sum = $sum + $request->total[$i];
                $totalQty +=  $request->qty[$i];
                $totalAmount +=  $request->product_cost[$i] * $request->qty[$i];
                $totalSaleRate +=  $request->price[$i] * $request->qty[$i];
            }


            $voucherData = new Vouchers();
            $voucherData->account_id = $stock->party_id;
            $voucherData->shop_id = Auth::User()->warehouse_id;
            $voucherData->voucher_no = $stock->voucher_no;
            $voucherData->voucher_date = $stock->date;
            $voucherData->v_type = "Sale Return";
            $voucherData->total_debit = $sum;
            $voucherData->biller = Auth::User()->id;
            $voucherData->status = 1;
            $voucherData->save();

            $voucherData = new Vouchers();
            $voucherData->account_id = 3;
            $voucherData->shop_id = Auth::User()->warehouse_id;
            $voucherData->voucher_no = $stock->voucher_no;
            $voucherData->voucher_date = $stock->date;
            $voucherData->v_type = "Sale Return";
            $voucherData->total_credit = $sum;
            $voucherData->biller = Auth::User()->id;
            $voucherData->status = 1;
            $voucherData->save();


            $vouchers = new GeneralVoucher();
            $vouchers->voucher_id = $stock->id;
            $vouchers->account_head_id = $request->party_id;
            $vouchers->date = $request->date;
            $vouchers->voucher_no = $stock->voucher_no;
            $vouchers->v_type = "Sale Return";
            $vouchers->debit = $sum;
            $vouchers->status = 1;
            $vouchers->save();


            $vouchers = new GeneralVoucher();
            $vouchers->voucher_id = $stock->id;
            $vouchers->account_head_id = 3;
            $vouchers->date = $request->date;
            $vouchers->voucher_no = $stock->voucher_no;
            $vouchers->v_type = "Sale Return";
            $vouchers->credit = $sum;
            $vouchers->status = 1;
            $vouchers->save();


            $stock->total_qty = $totalQty;
            $stock->total_amount = $totalAmount;
            $stock->total_sale_rate = $totalSaleRate;
            $stock->save();

            // DeliveryChallan::find($request->dcn_no)->update([
            //     'status' => 1
            // ]);

            return redirect()->back()->with('flash_message', 'Sale Return Invoice Added Successfully!');
        }
    }

    public function editData(Request $request)
    {
        $edit = Stock::whereType('Sales Return')->where('voucher_no', $request->voucher_no)->first();

        if ($edit) {
            $edit = StockDetails::with('products')
                ->with(['stock' => function ($query) {
                    $query->with('parties');
                }])
                ->whereType('Sales Return')
                ->where('stock_id', $edit->id)
                ->get();

            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        $sale = Stock::whereType('Sales Return')->where('voucher_no', '<', $request->voucher_no)->max('voucher_no');

        if ($sale) {
            $data = StockDetails::with('products')
                ->with(['stock' => function ($query) {
                    $query->with('parties');
                }])
                ->whereType('Sales Return')
                ->where('voucher_no', $sale)
                ->get();
            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $sale = Stock::whereType('Sales Return')->where('voucher_no', '<', $request->voucher_no)->min('voucher_no');

        if ($sale) {
            $data = StockDetails::with('products')
                ->with(['stock' => function ($query) {
                    $query->with('parties');
                }])
                ->whereType('Sales Return')
                ->where('voucher_no', $sale)
                ->get();
            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function DeleteVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'SALE RETURN')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        $stock = Stock::whereType('Sales Return')->where('voucher_no', $request->delete_voucher_no)->first();
        if ($stock) {
            Stock::findOrFail($stock->id)->delete();
            StockDetails::whereType('Sales Return')->where('stock_id', $stock->id)->delete();
            Stock::findOrFail($stock->id)->delete();
            StockDetails::whereType('Sales Return')->where('stock_id', $stock->id)->delete();
            Vouchers::where('v_type', 'Sales Return')->where('voucher_no', $stock->voucher_no)->delete();
            GeneralVoucher::where('v_type', 'Sales Return')->where('voucher_id', $stock->id)->delete();

            return redirect()->back()->with('flash_message', 'Sale Return Invoice Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }

    public function report(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'SALE RETURN')
            ->where('right_name', 'PRINT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        if ($request->ajax()) {
            if ($request->report_type == 'summary') {
                $sales = Sales::with('department', 'parties')
                    ->where('type', 'Sale')
                    ->where('party_id', $request->party_id)
                    ->whereDate('date', '>=', $request->from_date)
                    ->whereDate('date', '<=', $request->to_date)
                    ->get();
                return DataTables::of($sales)
                    ->addIndexColumn()
                    ->addColumn('date', function ($row) {
                        $date = date('d/m/Y', strtotime($row->date));
                        return $date;
                    })
                    ->addColumn('warehouse_id', function ($data) {
                        $department = $data->department->name;
                        return $department;
                    })
                    ->addColumn('party_id', function ($data) {
                        $party = $data->parties->party_name;
                        return $party;
                    })
                    ->make(true);
            } else
			if ($request->report_type == 'detailed') {
                $sales = SaleDetail::with('department', 'parties')
                    ->where('type', 'Sale')
                    ->where('party_id', $request->party_id)
                    ->whereDate('date', '>=', $request->from_date)
                    ->whereDate('date', '<=', $request->to_date)
                    ->get();
                return DataTables::of($sales)
                    ->addIndexColumn()
                    ->addColumn('date', function ($row) {
                        $date = date('d/m/Y', strtotime($row->date));
                        return $date;
                    })
                    ->addColumn('warehouse_id', function ($data) {
                        $department = $data->department->name;
                        return $department;
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
            ->where('parties.account_group_id', '1')
            ->Orwhere('parties.account_group_id', '7')
            ->OrderBy('party_name', 'asc')
            ->pluck('parties.party_name', 'parties.id')
            ->prepend('Select Party Name', '');
        return view('sales.report', compact('customers'));
    }
    public function PrintVoucher(Request $request)
    {
        File::cleanDirectory(base_path() .'/upload/sales-return');
        $voucher_no = $request->voucher_no;
        $salereturn = Stock::where('type', 'Sales Return')->where('voucher_no', $voucher_no)->first();
        if ($salereturn) {
            $salereturnDetails = StockDetails::with(['stock'=>function($qry){
                            $qry->with('parties:id,party_name,address','dc_no:id,voucher_no');
            }])
                ->with('products:id,product_name,uom')
                ->where('voucher_no', $voucher_no)
                ->where('type', 'Sales Return')
                ->orderBy('id', 'asc')
                ->get();

            $pdf = PDF::loadView('sales-return.invoice', compact('salereturnDetails'));
            $fileName =  'sales-return' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/sales-return/' . $fileName));
            return $fileName;
        } 
        else{
            return false;
        }
    }
}