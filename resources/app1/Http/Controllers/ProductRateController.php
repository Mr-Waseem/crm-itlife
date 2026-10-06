<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductRate;
use App\Models\ProductRateDetails;
use App\Models\Product;
use App\Models\Party;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProductRateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sales = ProductRate::OrderBy('id', 'desc')
        ->with(['rate_details' => function ($query) {
        }])
        ->with('party')
        ->with('billers')
        ->get();

        $products = Product::OrderBy('product_name', 'asc')->pluck('product_name', 'id')->toArray();
        $party = Party::with('location')->where('account_type', '=', 'MILK SUPPLIER')->get();
        return view('product-rates.index', Compact('sales', 'products', 'party'));
    }

    public function UpdateMilkRate(Request $request)
    {
        $cost = json_decode($request->get('cost'), true);
        $id = $request->get('product_id');
        $product = ProductRateDetails::where('productrate_id', '=', $id)->first();
        $product->rate = $cost;
        $product->save();
    }

    public function UpdateSupplierMilkRate(Request $request)
    {
        $cost = json_decode($request->get('cost'), true);
        $id = $request->get('product_id');
        $product = Party::where('id', '=', $id)->first();
        $product->fatrate = $cost;
        $product->save();
    }

    public function CustomerChange(Request $request)
    {
        $CustomerID = $request->get('cusID');
        $data = ProductRateDetails::with('product')
            ->where('customer_id', '=', $CustomerID)
            ->OrderBy('id', 'asc')
            ->get();
        return $data;
    }

    public function create()
    {
        $code = ProductRate::OrderBy('id', 'asc')->get();
        $codes = (int)$code->last()->vr_no + 1;
        $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_code`, "_", `product_name`) AS `id`, `product_code`, `product_name`'))->OrderBy('id', 'asc')->pluck('product_name', 'id')->prepend('Select Product', '')->toArray();
        $customer = Party::select(DB::raw('CONCAT(`id`, "_", `code`, "_", `party_name`) AS `id`, `code`, `party_name`'))->OrderBy('id', 'asc')->pluck('party_name', 'id')->prepend('Select Customer', '')->toArray();

        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('product-rates.create', Compact('products', 'codes', 'encrypted_token', 'customer'));
    }

    public function store(Request $request)
    {
        $values = 1;
        $voucher = $request->get('vr_no');
        $data = ProductRate::where('vr_no', '=', $voucher)->get();
        if (count($data) > 0) {
            $recipe = ProductRate::findOrFail($data[0]->id);
            $recipe->update($request->all());
        } else {
            $recipe = ProductRate::create($request->all());
        }

        ProductRateDetails::where('productrate_id', '=', $recipe['id'])->delete();
        $count = count($request->product_id);
       
        for ($i = 0; $i < $count; $i++) {
            $detail = new ProductRateDetails();
            $detail->productrate_id = $recipe['id'];
            $detail->customer_id = $request->customer_id;
            $detail->product_id = $request->product_id[$i];
            $detail->product_code = $request->product_code[$i];
            $detail->rate = $request->product_rate[$i];
            $detail->save();
        }

        Session::flash('flash_message', 'Record successfully added!');
        return redirect('product-rates/create');
    }


    public function LoadVoucher(Request $request)
    {
        $CusID = $request->get('cusID');
        $rates = ProductRate::where('customer_id', '=', $CusID)->get();
        if (count($rates) == 0) {
            $code = ProductRate::OrderBy('id', 'desc')->first();
            $rates[0] = new ProductRate([
                'vr_no' => $code['vr_no'] + 1
            ]);
        }
        return $rates;
    }

    public function show($id)
    {
        $newsale_detail  = ProductRate::with(['rate_details' => function ($query) {
            $query->with('product');
        }])
            ->with('party')->with('billers')
            ->where('id', '=', $id)
            ->get();

        $company_detail = Setting::where('id', '=', 1)->get();
        return view('product-rates.print', Compact('newsale_detail', 'company_detail'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $delete = ProductRate::findOrFail($id);
        $delete->delete();
        ProductRateDetails::where('productrate_id', '=', $id)->delete();
        return "Record has been Deleted Successfully!";
    }
}
