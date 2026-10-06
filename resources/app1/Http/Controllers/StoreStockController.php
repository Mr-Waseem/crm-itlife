<?php

namespace App\Http\Controllers;

use App\Models\InwardGatePass;
use App\Models\InwardGatePassDetails;
use App\Models\Party;
use App\Models\Product;
use App\Models\StoreStock;
use App\Models\StoreStockDetails;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use DataTables;

class StoreStockController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $storeStock = StoreStock::with('supplier')
                ->with('created_by_user:id,name')
                ->orderBy('id', 'desc')
                ->get();
            return DataTables::of($storeStock)
                ->addIndexColumn()
                ->addColumn('date', function ($data) {
                    return date('d/m/Y', strtotime($data->date));
                })
                ->addColumn('supplier', function ($data) {
                    if ($data->supplier_id == 0) {
                        return "No Supplier Recommended";
                    } else {
                        return $data->supplier->party_name;
                    }
                })
                ->addColumn('igp_number', function ($data) {
                    return $data->igp_number;
                })
                ->addColumn('created_by_user', function ($data) {
                    return $data->created_by_user->name;
                })
                ->addColumn('print', function ($row) {
                    $btn = '<a href="store-stock/' . $row->id . '" class="btn btn-info btn-sm" target="_blank"><i class="fa fa-print"></i></a>&nbsp;
                            <a href="store-stock/grn/' . $row->id . '" class="btn btn-success btn-sm" target="_blank">GRN</a>';
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="store-stock/' . $row->id . '/edit" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>&nbsp;
                                        <a href="store-stock/destroy/' . $row->id . '" onclick="return confirm(`Are you sure you want to delete this record?`)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                        ';

                    return $btn;
                })
                ->rawColumns(['print', 'action'])
                ->make(true);
        }
        return view('store-stock.index');
    }

    public function create()
    {
        $storeStockNumber = StoreStock::orderBy('id', 'desc')->first();
        $codes = 1;
        if ($storeStockNumber) {
            $codes = $storeStockNumber->bill_no + 1;
        }
        $suppliers = Party::where('account_type', 'SUPPLIER')->pluck('party_name', 'id')->prepend("No Supplier", 0);
        $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_code`, "_", `product_name`, "_", `uom`) AS `id`, `product_code`, `product_name`, `uom`'))
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '')
            ->toArray();
        $inwardGatePassNumbers = InwardGatePass::where('status',0)->pluck('bill_no','id')->prepend('Select IGP','');


        return view('store-stock.create', compact('codes', 'suppliers', 'products','inwardGatePassNumbers'));
    }

    public function store(Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'code1' => 'required',
            'igp_number'=>'required'
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->with('failure_message', 'Please Enter at least 1 Product');
        }
        $storeStock = StoreStock::create($request->all());
        $count = count($request->product_id);
        $totalAmount = 0;
        $totalQty = 0;
        for ($i = 0; $i < $count; $i++) {
            $storeStockDetails = new StoreStockDetails();
            $storeStockDetails->store_stock_id = $storeStock->id;
            $storeStockDetails->date = $storeStock->date;
            $storeStockDetails->supplier_id = $storeStock->supplier_id;
            $storeStockDetails->product_code = $request->code1[$i];
            $storeStockDetails->product_id = $request->product_id[$i];
            $storeStockDetails->product_name = $request->product_name[$i];
            $storeStockDetails->unit = $request->unit1[$i];
            $storeStockDetails->price = $request->price1[$i];
            $storeStockDetails->qty = $request->qty1[$i];
            $storeStockDetails->total_amount = $request->total1[$i];
            $storeStockDetails->comments = $request->comments[$i];
            $storeStockDetails->igp_number = $storeStock->igp_number;
            $storeStockDetails->created_by = Auth::User()->id;
            $storeStockDetails->save();

            $totalAmount += $request->total1[$i];
            $totalQty += $request->qty1[$i];
        }
        $storeStock->total_amount = $totalAmount;
        $storeStock->total_qty = $totalQty;
        $storeStock->save();

        InwardGatePass::find($request->igp_number)->update([
            'status'=>1
        ]);
        InwardGatePassDetails::where('inward_gatepass_id',$request->igp_number)->update([
            'status'=>1
        ]);

        return redirect()->back()->with(Toastr::success('Store Stock Added Successfully!'));
    }

    public function show($id)
    {
        $storeStock = StoreStock::with(['store_stock_details' => function ($query) {
            $query->with('product');
        }])
            ->with('supplier')
            ->where('id', $id)
            ->orderBy('id', 'asc')
            ->first();
        return view('store-stock.invoice', compact('storeStock'));
    }

    public function GRN($id)
    {
        $storeStock = StoreStock::with(['store_stock_details' => function ($query) {
            $query->with('product');
        }])
            ->with('supplier')
            ->where('id', $id)
            ->orderBy('id', 'asc')
            ->first();
        return view('store-stock.grn', compact('storeStock'));
    }

    public function GetIGPData(Request $request){
        $data = InwardGatePassDetails::where('inward_gatepass_id',$request->igp)->get();
        return json_encode($data);
    }
}
