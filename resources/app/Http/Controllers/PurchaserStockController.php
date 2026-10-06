<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\InwardGatePass;
use App\Models\PurchaserStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\InwardGatePassDetails;
use App\Models\PurchaserStockDetails;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PurchaserStockController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $purchaserStockNumber = PurchaserStock::orderBy('id', 'desc')->first();
        $codes = 1;
        if ($purchaserStockNumber) {
            $codes = $purchaserStockNumber->bill_no + 1;
        }

        $suppliers = Party::where('account_type', 'SUPPLIER')->pluck('party_name', 'id')->prepend("No Supplier", 0);
        $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_code`, "_", `product_name`, "_", `uom`) AS `id`, `product_code`, `product_name`, `uom`'))
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '')
            ->toArray();
        $inwardGatePassNumbers = InwardGatePass::where('status', 0)->pluck('bill_no', 'bill_no')->prepend('Select IGP', '');

        return view('purchaser-stock.index', compact('codes', 'suppliers', 'products', 'inwardGatePassNumbers'));
    }

    public function store(Request $request)
    {
        if ($request->update_id != null || $request->update_id != 0) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'PURCHASER STOCK')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }


            $Validator = Validator::make($request->all(), [
                'type' => 'required',
                'date' => 'required',
                'bill_no' => 'required'
            ]);
            if ($Validator->fails()) {
                return redirect()->back()->withErrors($Validator)->withInput();
            }

            if (!isset($request->price)) {
                return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
            }

            if (!isset($request->qty)) {
                return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
            }

            $purchaserStock = PurchaserStock::find($request->update_id);

            $data = $request->all();
            $data['igp_number'] = $purchaserStock->igp_number;
            $data['type'] = $request->type;

            $purchaserStock->update($data);

            $count = count($request->product_id);
            $totalAmount = 0;
            $totalQty = 0;

            PurchaserStockDetails::where('purchaser_stock_id', $request->update_id)->delete();
            for ($i = 0; $i < $count; $i++) {
                $purchaserStockDetails = new PurchaserStockDetails();
                $purchaserStockDetails->purchaser_stock_id = $purchaserStock->id;
                $purchaserStockDetails->date = $purchaserStock->date;
                $purchaserStockDetails->type = $purchaserStock->type;
                $purchaserStockDetails->bill_no = $purchaserStock->bill_no;
                $purchaserStockDetails->supplier_id = $purchaserStock->supplier_id;
                $purchaserStockDetails->product_code = $request->code[$i];
                $purchaserStockDetails->product_id = $request->product_id[$i];
                $purchaserStockDetails->product_name = $request->product_name[$i];
                $purchaserStockDetails->unit = $request->unit[$i];
                $purchaserStockDetails->price = $request->price[$i];
                $purchaserStockDetails->qty = $request->qty[$i];
                $purchaserStockDetails->total_amount = $request->total[$i];
                $purchaserStockDetails->comments = $request->comments[$i];
                $purchaserStockDetails->igp_number = $purchaserStock->igp_number;
                $purchaserStockDetails->created_by = Auth::User()->id;
                $purchaserStockDetails->save();

                $totalAmount += $request->total[$i];
                $totalQty += $request->qty[$i];
            }
            $purchaserStock->total_amount = $totalAmount;
            $purchaserStock->total_qty = $totalQty;
            $purchaserStock->updated_by = Auth::User()->id;
            $purchaserStock->save();

            InwardGatePass::where('bill_no', $request->igp_num)->update([
                'status' => 1
            ]);
            InwardGatePassDetails::where('bill_no', $request->igp_num)->update([
                'status' => 1
            ]);

            return redirect()->back()->with('flash_message', 'Store Stock Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'PURCHASER STOCK')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }


            $Validator = Validator::make($request->all(), [
                'type' => 'required',
                'date' => 'required',
                'bill_no' => 'required',
                'igp_number' => 'required'
            ]);
            if ($Validator->fails()) {
                return redirect()->back()->withErrors($Validator)->withInput();
            }

            if (!isset($request->price)) {
                return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
            }

            if (!isset($request->qty)) {
                return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
            }

            $purchaserStockNumber = PurchaserStock::orderBy('id', 'desc')->first();
            $codes = 1;
            if ($purchaserStockNumber) {
                $codes = $purchaserStockNumber->bill_no + 1;
            }
             $data=$request->all();
             $data['bill_no']=$codes;
            $purchaserStock = PurchaserStock::create($data);
            $count = count($request->product_id);
            $totalAmount = 0;
            $totalQty = 0;

            for ($i = 0; $i < $count; $i++) {
                $purchaserStockDetails = new PurchaserStockDetails();
                $purchaserStockDetails->purchaser_stock_id = $purchaserStock->id;
                $purchaserStockDetails->date = $purchaserStock->date;
                $purchaserStockDetails->type = $purchaserStock->type;
                $purchaserStockDetails->bill_no = $purchaserStock->bill_no;
                $purchaserStockDetails->supplier_id = $purchaserStock->supplier_id;
                $purchaserStockDetails->product_code = $request->code[$i];
                $purchaserStockDetails->product_id = $request->product_id[$i];
                $purchaserStockDetails->product_name = $request->product_name[$i];
                $purchaserStockDetails->unit = $request->unit[$i];
                $purchaserStockDetails->price = $request->price[$i];
                $purchaserStockDetails->qty = $request->qty[$i];
                $purchaserStockDetails->total_amount = $request->total[$i];
                $purchaserStockDetails->comments = $request->comments[$i];
                $purchaserStockDetails->igp_number = $purchaserStock->igp_number;
                $purchaserStockDetails->created_by = Auth::User()->id;
                $purchaserStockDetails->save();

                $totalAmount += $request->total[$i];
                $totalQty += $request->qty[$i];
            }
            $purchaserStock->total_amount = $totalAmount;
            $purchaserStock->total_qty = $totalQty;
            $purchaserStock->save();

            InwardGatePass::where('bill_no', $request->igp_num)->update([
                'status' => 1
            ]);
            InwardGatePassDetails::where('bill_no', $request->igp_num)->update([
                'status' => 1
            ]);

            return redirect()->back()->with('flash_message', 'Store Stock Added Successfully!');
        }
        abort(500);
    }

    public function show($id)
    {
        $purchaserStock = PurchaserStock::with(['purchaser_stock_details' => function ($query) {
            $query->with('product');
        }])
            ->with('supplier')
            ->where('id', $id)
            ->orderBy('id', 'asc')
            ->first();
        return view('purchaser-stock.invoice', compact('purchaserStock'));
    }

    public function GRN($id)
    {
        $purchaserStock = PurchaserStock::with(['purchaser_stock_details' => function ($query) {
            $query->with('product');
        }])
            ->with('supplier')
            ->where('id', $id)
            ->orderBy('id', 'asc')
            ->first();
        return view('purchaser-stock.grn', compact('purchaserStock'));
    }

    public function GetIGPData(Request $request)
    {
        $data = InwardGatePassDetails::where('inward_gatepass_id', $request->igp)->get();
        return json_encode($data);
    }

    public function LoadIGPData(Request $request)
    {
        $inward = InwardGatePass::where('bill_no', $request->bill_no)->first();
        if ($inward) {
            $inwardGatePassDetails = InwardGatePassDetails::with('inward', 'product')->where('bill_no', $request->bill_no)->get();
            return Response::json(['data' => $inwardGatePassDetails]);
        } else {
            return Response::json(['data' => '']);
        }
        abort(500);
    }

    public function editData(Request $request)
    {
        $purchaserStock = PurchaserStock::where('bill_no', $request->bill_no)->first();
        if ($purchaserStock) {
            $purchaserStockDetails = PurchaserStockDetails::with('purchase_stock', 'product')->where('bill_no', $request->bill_no)->get();
            return Response::json(['data' => $purchaserStockDetails]);
        } else {
            return Response::json(['data' => '']);
        }
        abort(500);
    }

    public function LoadNextData(Request $request)
    {
        $purchaserStock = PurchaserStock::where('bill_no', '>', $request->bill_no)->min('bill_no');

        if ($purchaserStock) {
            $purchaserStockDetails = PurchaserStockDetails::with('purchase_stock', 'product')->where('bill_no', $purchaserStock)->get();
            return Response::json(['data' => $purchaserStockDetails]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        $purchaserStock = PurchaserStock::where('bill_no', '<', $request->bill_no)->max('bill_no');

        if ($purchaserStock) {
            $purchaserStockDetails = PurchaserStockDetails::with('purchase_stock', 'product')->where('bill_no', $purchaserStock)->get();
            return Response::json(['data' => $purchaserStockDetails]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function destroy(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PURCHASER STOCK')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        $purchase = PurchaserStock::where('bill_no', $request->delete_bill_no)->first();
        if ($purchase) {
            PurchaserStock::where('bill_no', $request->delete_bill_no)->delete();
            PurchaserStockDetails::where('bill_no', $request->delete_bill_no)->delete();
            return redirect()->back()->with('flash_message', 'Purchaser Stock Deleted Successfully!');
        } else {
            return redirect()->back()->with('error_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }

    public function PrintVoucher(Request $request)
    {
        File::cleanDirectory(base_path() . '/upload/purchaser-stock');
        $bill_no = $request->bill_no;
        $PurchaserStock = PurchaserStock::where('bill_no', $bill_no)->first();
        if ($PurchaserStock) {
            $PurchaserStockDetails = PurchaserStockDetails::with(['purchase_stock' => function ($qry) {
                $qry->with('supplier:id,party_name');
            }])
                ->with('product:id,product_name,uom,product_code')
                ->where('bill_no', $bill_no)
                ->orderBy('id', 'asc')
                ->get();

            $pdf = PDF::loadView('purchaser-stock.invoice', compact('PurchaserStockDetails'));
            $fileName =  'purchaser-stock' . $bill_no . '.pdf';
            $pdf->save(base_path('upload/purchaser-stock/' . $fileName));
            return $fileName;
        } else{
            return false;
        }
    }
}
