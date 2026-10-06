<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\CustomerProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Imports\CustomerProductImport;
use Excel;
class CustomerProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(){
        return view('customer-products.import');
    }

    public function ImportProducts(Request $request){
        
        $this->validate($request, [
            'product_file' => 'required'
        ]);
        // $path = $request->file('product_file')->getRealPath();
        // dd($path);
        // $data = Excel::import(new CustomerProductImport, $path);
        $path1 = $request->file('product_file')->store('temp'); 
         $path = storage_path('app').'/'.$path1;  
        $data = Excel::import(new CustomerProductImport, $path);
        // $data1 = Excel::import(new CustomerUserImport, $path,  $data);
        return redirect()->back()->with('flash_message', 'File Imported Successfully!');

        // return $results = Excel::raw($path, Excel::XLSX);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customerProduct = CustomerProduct::with('customer:id,party_name')
                ->with('product:id,product_name')
                ->OrderBy('id', 'asc')
                ->get();

            return DataTables::of($customerProduct)
                ->addIndexColumn()
                ->editColumn('customer_id', function ($data) {
                    if($data->customer){
                    return $data->customer->party_name;
                    }
                })
                ->editColumn('product_id', function ($data) {
                    if($data->product){
                    return $data->product->product_name;
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->product_code . '_' . $row->product_name . '_' . $row->customer_id . '_' . $row->product_id . '"><i class="fa fa-pencil"></i></button>&nbsp;
                                <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-product"><i class="fa fa-trash"></i></a>
                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $customer = Party::select(DB::raw('`id`,CONCAT(`code`,"-",`party_name`) AS `party_name`'))->where('role', 'Customer')->pluck('party_name', 'id')->prepend('Select Customer', '');
    //    $products = Product::select(DB::raw('`id`,CONCAT(`product_code`,"-",`product_name`,"-",`packing`) as `product_name`'))
       $products = Product::select(DB::raw('`id`,CONCAT(`code`," ",`product_name`,"-",`packing`) as `product_name`'))
        //  ->where('warehouse_id', 11)
         ->pluck('product_name', 'id')
        ->prepend('Select Product', '');
        return view('customer-products.index', compact('customer', 'products'));
    }

    public function store(Request $request)
    {
        // return "ddd";
        $this->validate(
            $request,
            [
                'product_code' => 'required',
                'product_name' => 'required',
                // 'customer_id' => 'required',
                // 'product_id' => 'required',
            ],
            // [
            //     'customer_id.required' => 'The Customer field is required',
            //     'product_id.required' => 'The Product field is required',
            // ]
        );
        if ($request->idd != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'CUSTOMER PRODUCTS')
            ->where('right_name', 'EDIT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
            $customerProduct = CustomerProduct::find($request->idd);
            $customerProduct->update($request->all());
            return redirect()->back()->with('flash_message', " Customer Product Updated Successfully");
        } else {

            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'CUSTOMER PRODUCTS')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            CustomerProduct::create($request->all());
            return redirect()->back()->with('flash_message', " Customer Product Added Successfully");
        }
    }public function show(){
        $data = Party::Orderby('id', 'asc')->delete();
        return "Done";
    }

    public function destroy($id)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'CUSTOMER PRODUCTS')
                ->where('right_name', 'DELETE')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            CustomerProduct::find($id)->delete();
            return redirect()->back()->with('flash_message', " Customer Product Deleted Successfully");
    }
}
