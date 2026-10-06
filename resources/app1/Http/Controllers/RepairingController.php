<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Tax;
use App\Models\Party;
use App\Models\Discount;
use App\Models\Sales;
use App\Models\SaleDetail;
use App\Models\Setting;
use App\Models\Ledger;
use App\Models\Repairing;
use App\Models\RepairingDetail;
use DB;

class RepairingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sales = Repairing::OrderBy('id', 'dsc')->with(['repairing_details' => function ($query) {
            $query->with('products');
        }])->with('parties')->get();
        //return $sales;
        return view('repairing.index', Compact('sales'));
    }

    public function create()
    {
        $products = Product::OrderBy('product_name', 'asc')->pluck('product_name', 'id')->prepend('Select Product', '0')->toArray();
        //$taxes = Tax::select(DB::raw('CONCAT(`id`, "_", `tax_rate`) AS `tax_rate`, `tax_title`'))->OrderBy('id', 'asc')->pluck('tax_title', 'tax_rate')->toArray();
        //$discounts = Discount::select(DB::raw('CONCAT(`id`, "_", `discount`) AS `discount`, `title`'))->OrderBy('id', 'asc')->pluck('title', 'discount')->toArray();
        $reference = Repairing::count();
        $reference = $reference + 1;
        $customers = Party::OrderBy('id', 'asc')->pluck('party_name', 'id');
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('repairing.create', Compact('customers', 'products', 'reference', 'encrypted_token'));
    }

    public function store(Request $request)
    {

        $purchase = json_decode($request->get('purchase'), true);
        $purchase['date'] = date('Y-m-d', strtotime($purchase['date']));
        $products = $request->get('product_data');
        $purchaseData = Repairing::create($purchase);

        foreach ($products as $product) {
            $purchaseDetail = new RepairingDetail();
            $purchaseDetail->repairing_id = $purchaseData['id'];
            $purchaseDetail->product_id = $product['product_id'];
            $purchaseDetail->party_id = $purchaseData['party_id'];
            //$purchaseDetail->discount = $product['discount'];
            $purchaseDetail->quantity = $product['quantity'];
            $purchaseDetail->charges = $product['charges'];
            $purchaseDetail->save();
        }
        return "inserted";
    }

    public function show($id)
    {
        $newsale_detail = Repairing::with(['repairing_details' => function ($query) {
            $query->with('products');
            $query->with('parties');
        }])
            ->where('repairings.id', '=', $id)
            ->get();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('repairing.details', Compact('newsale_detail', 'company_detail'));
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
        //
    }

    public function print_repair($id)
    {
        $newsale_detail = Repairing::with(['repairing_details' => function ($query) {
            $query->with('products');
            $query->with('parties');
        }])
            ->where('repairings.id', '=', $id)
            ->get();
        $ledgers = Ledger::with('ledger_party')->where('party_id', '=', $newsale_detail[0]->parties->id)->get();
        //return $ledgers;
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('repairing.print', Compact('newsale_detail', 'company_detail', 'ledgers'));
    }
}
