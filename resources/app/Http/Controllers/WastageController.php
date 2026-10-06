<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wastage;
use App\Models\WastageDetails;
use App\Models\DeliveryChallan;
use App\Models\Product;
use App\Models\Tax;
use App\Models\Discount;
use App\Models\Party;
use App\Models\UOM;
use App\Models\Warehouse;
use App\Models\GeneralVoucher;
use App\Models\SystemLogo;
use App\Models\Setting;
use App\Models\RawMaterialStock;
use App\Models\StockRegisterSpecificItem;
use App\Models\SalePointStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WastageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $sales = Wastage::OrderBy('id', 'desc')
        ->with(['wastage_details' => function ($query) {
            $query->with('discount');
            $query->with('parties');
        }])->with('billers')->get();

        return view('wastage.index', Compact('sales'));
    }

    public function create()
    {
        $code = Wastage::OrderBy('id', 'asc')->get();
        if (count($code) > 0) {
            $codes = (int)$code->last()->invoice_no + 1;
        } else {
            $codes = 1;
        }

        $DeliveryChallan = DeliveryChallan::where('status', '=', 'Pending')->OrderBy('dcn_no', 'asc')->pluck('dcn_no', 'dcn_no')->prepend('Select Challan', '0')->toArray();

        $products = Product::where('catagory_id', '=', '6')->select(DB::raw('CONCAT(`id`, "_", `product_name`) AS `id`, `product_name`'))->OrderBy('id', 'asc')->pluck('product_name', 'id')->prepend('Select Product', '0')->toArray();

        $taxes = Tax::select(DB::raw('CONCAT(`id`, "_", `tax_rate`) AS `tax_rate`, `tax_title`'))->OrderBy('id', 'asc')->pluck('tax_title', 'tax_rate')->toArray();
        $discounts = Discount::select(DB::raw('CONCAT(`id`, "_", `discount`) AS `discount`, `title`'))->OrderBy('id', 'asc')->pluck('title', 'discount')->toArray();
      
        $shop = Auth::User()->shop_id;

        $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            ->where('parties.shop_id', '=', $shop)
            ->Orwhere('parties.account_group_id', '=', '7')
            ->OrderBy('party_name', 'asc')->pluck('party_name', 'parties.id')->prepend('CASH IN HAND', '1');

        $uoms = UOM::select(DB::raw('CONCAT(`id`, "_", `uom`) AS `id`, `uom`'))->OrderBy('id', 'asc')->pluck('uom', 'id')->prepend('KG', '1_KG')->toArray();

        $warehouse = Warehouse::OrderBy('id', 'asc')->pluck('name', 'id');

        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('wastage.create-simple', Compact('customers', 'products', 'codes', 'taxes', 'discounts', 'encrypted_token', 'uoms', 'warehouse'));
    }

    public function store(Request $request)
    {
        $purchase = json_decode($request->get('purchase'), true);

        $purchase['date'] = date('Y-m-d', strtotime($purchase['date']));
        $products = $request->get('product_data');
        $purchaseData = Wastage::create($purchase);
        $sum = "0";
        foreach ($products as $product) {
            $purchaseDetail = new WastageDetails();
            $purchaseDetail->wastage_id = $purchaseData['id'];
            $purchaseDetail->product_id = $product['product_id'];
            $Code = $product['product_code'];
            $purchaseDetail->party_id = $product['party_id'];
            $purchaseDetail->uom_id = $product['uom_id'];
            $purchaseDetail->quantity = $product['quantity'];
            $purchaseDetail->product_cost = $product['product_cost'];
            $purchaseDetail->cost_amount = $product['cost_amount'];
            $purchaseDetail->discount_id = $product['discount_id'];
            $purchaseDetail->warehouse_id = $purchaseData['warehouse_id'];
            $purchaseDetail->biller = $purchaseData['biller'];
            $purchaseDetail->sale_rate = $product['sale_rate'];
            $purchaseDetail->sale_amount = $product['balance'];
            $purchaseDetail->product_cost = $product['product_cost'];
            $purchaseDetail->cost_amount = $product['cost_amount'];
            $sum = $sum + $product['balance'];
            $purchaseDetail->save();

            if (($purchaseData['sale_list']) == "Wastage") {
                $vouchers = new StockRegisterSpecificItem();
                $vouchers->purchase_ret_id = $purchaseData['id'];
                $vouchers->date = $purchaseData['date'];

                $vouchers->product_id = $product['product_id'];
                $vouchers->voucher_type = $purchaseData['sale_list'];
                $vouchers->warehouse_id = $purchaseData['warehouse_id'];
                $vouchers->uom_id = $product['uom_id'];
                $vouchers->sale_quantity = $product['quantity'];
                $vouchers->cost_rate = $product['balance'];
                $vouchers->save();
            }

            if (($purchaseData['sale_list']) == "Adjust") {
                $vouchers = new StockRegisterSpecificItem();
                $vouchers->purchase_ret_id = $purchaseData['id'];
                $vouchers->date = $purchaseData['date'];

                $vouchers->product_id = $product['product_id'];
                $vouchers->voucher_type = $purchaseData['sale_list'];
                $vouchers->warehouse_id = $purchaseData['warehouse_id'];
                $vouchers->uom_id = $product['uom_id'];
                $vouchers->purchase_quantity = $product['quantity'];
                $vouchers->cost_rate = $product['balance'];
                $vouchers->save();
            }


            if (($purchaseData['warehouse_id']) == 1) {
                if (($purchaseData['sale_list']) == "Wastage") {
                    $RawMaterial = new RawMaterialStock();
                    $RawMaterial->sale_return_id = $purchaseData['id'];
                    $RawMaterial->warehouse_id = 1;
                    $RawMaterial->product_id = $product['product_id'];
                    $RawMaterial->uom_id = $product['uom_id'];
                    $RawMaterial->date = $purchaseData['date'];
                    $RawMaterial->cost_amount = $product['balance'];
                    $RawMaterial->stockout = $product['quantity'];
                    $RawMaterial->save();
                }

                if (($purchaseData['sale_list']) == "Adjust") {
                    $RawMaterial = new RawMaterialStock();
                    $RawMaterial->sale_return_id = $purchaseData['id'];
                    $RawMaterial->warehouse_id = 1;
                    $RawMaterial->product_id = $product['product_id'];
                    $RawMaterial->uom_id = $product['uom_id'];
                    $RawMaterial->date = $purchaseData['date'];
                    $RawMaterial->cost_amount = $product['balance'];
                    $RawMaterial->stockin = $product['quantity'];
                    $RawMaterial->save();
                }
            }


            if (($purchaseData['warehouse_id']) != 1) {
                if (($purchaseData['sale_list']) == "Wastage") {
                    $RawMaterial = new SalePointStock();
                    $RawMaterial->wastage_id = $purchaseData['id'];
                    $RawMaterial->warehouse_id = $purchaseData['warehouse_id'];
                    $RawMaterial->product_id = $product['product_id'];
                    $RawMaterial->uom_id = $product['uom_id'];
                    $RawMaterial->vr_no = $purchaseData['invoice_no'];
                    $RawMaterial->date = $purchaseData['date'];
                    $RawMaterial->biller = Auth::User()->id;
                    $RawMaterial->cost_amount = $product['balance'];
                    $RawMaterial->stockout = $product['quantity'];
                    $RawMaterial->save();
                }

                if (($purchaseData['sale_list']) == "Adjust") {
                    $RawMaterial = new SalePointStock();
                    $RawMaterial->wastage_id = $purchaseData['id'];
                    $RawMaterial->warehouse_id = $purchaseData['warehouse_id'];
                    $RawMaterial->product_id = $product['product_id'];
                    $RawMaterial->uom_id = $product['uom_id'];
                    $RawMaterial->vr_no = $purchaseData['invoice_no'];
                    $RawMaterial->date = $purchaseData['date'];
                    $RawMaterial->biller = Auth::User()->id;
                    $RawMaterial->cost_amount = $product['balance'];
                    $RawMaterial->stockin = $product['quantity'];
                    $RawMaterial->save();
                }
            }
        }

        Session::flash('flash_message','Record Added Successfully!');
        return redirect('wastage/create');

        // return $purchaseData['id'];
    }

    public function show($id)
    {
        $newsale_detail = Wastage::with(['wastage_details' => function ($query) {
            $query->with('uoms');
            $query->with(['products' => function ($query) {
                $query->with('publishers');
            }]);
            $query->with('taxes');
            $query->with('discount');
            $query->with('ledger');
        }])->with('parties')->with('billers')
            ->where('wastages.id', '=', $id)
            ->get();


        $ledgers = GeneralVoucher::where('account_head_id', '=', $newsale_detail[0]->parties->id)
            ->whereBetween('general_vouchers.date', ['2019-01-01', $newsale_detail[0]->date])
            ->get();
        $logo = SystemLogo::where('id', '=', 1)->get();
        $company_detail = Setting::where('id', '=', 1)->get();


        return view('wastage.print', compact('newsale_detail', 'company_detail', 'ledgers', 'logo', 'ledgers'));
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
        $delete = Wastage::findOrFail($id);
        $delete->delete();

        WastageDetails::where('wastage_id', '=', $id)->delete();
        RawMaterialStock::where('sale_return_id', '=', $id)->delete();
        StockRegisterSpecificItem::where('purchase_ret_id', '=', $id)->delete();
        return "Wastage has been Deleted Successfully!";
    }
}
