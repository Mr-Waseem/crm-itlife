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
use App\Models\DeliveryChallan;
use App\Models\GeneralVoucher;
use App\Models\StockRegisterSpecificItem;
use App\Models\LedgerDetailWise;
use App\Models\SystemLogo;
use App\Models\UOM;
use App\Models\Warehouse;
use App\Models\SalePointStock;
use App\Models\RawMaterialStock;
use App\Models\CashBook;
use App\Models\RecipeCreationDetails;
use DB;
use Illuminate\Support\Facades\Auth;

class CreditSaleController extends Controller
{
    public function index()
    {
        $sales = Sales::OrderBy('id', 'desc')->with(['sale_details' => function ($query) {
            //$query->with('taxes');
            $query->with('discount');
            $query->with('parties');
        }])->with('billers')->get();
        //return $sales;
        return view('credit-sale.index', Compact('sales'));
    }

    public function create()
    {
        $code = Sales::OrderBy('id', 'asc')->get();
        $codes = (int)$code->last()->invoice_no + 1;
        //return $codes;
        $DeliveryChallan = DeliveryChallan::where('status', '=', 'Pending')->OrderBy('dcn_no', 'asc')->pluck('dcn_no', 'dcn_no')->prepend('Select Challan', '0')->toArray();
        // return $DeliveryChallan;
        $products = Product::where('catagory_id', '=', '6')->select(DB::raw('CONCAT(`id`, "_", `product_name`) AS `id`, `product_name`'))->OrderBy('id', 'asc')->pluck('product_name', 'id')->prepend('Select Product', '0')->toArray();
        //return $products;
        $taxes = Tax::select(DB::raw('CONCAT(`id`, "_", `tax_rate`) AS `tax_rate`, `tax_title`'))->OrderBy('id', 'asc')->pluck('tax_title', 'tax_rate')->toArray();
        $discounts = Discount::select(DB::raw('CONCAT(`id`, "_", `discount`) AS `discount`, `title`'))->OrderBy('id', 'asc')->pluck('title', 'discount')->toArray();
        //$discounts = Discount::OrderBy('id', 'asc')->pluck('title', 'id')->prepend('Select Discount', '0')->toArray();
        $shop = Auth::User()->shop_id;
        // return $shop;
        $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            ->where('parties.shop_id', '=', $shop)
            ->Orwhere('parties.account_group_id', '=', '1')

            ->OrderBy('party_name', 'asc')->pluck('party_name', 'parties.id');
        //return $customers;
        $uoms = UOM::select(DB::raw('CONCAT(`id`, "_", `uom`) AS `id`, `uom`'))->OrderBy('id', 'asc')->pluck('uom', 'id')->prepend('KG', '1_KG')->toArray();
        //return $uoms;
        $warehouse = Warehouse::where('id', '=', Auth::User()->shop_id)->get();
        //return $warehouse;
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        //return $products;
        return view('credit-sale.create-simple', Compact('customers', 'products', 'codes', 'taxes', 'discounts', 'DeliveryChallan', 'encrypted_token', 'uoms', 'warehouse'));
    }

    public function store(Request $request)
    {
        $purchase = json_decode($request->get('purchase'), true);
        //return $purchase;
        $purchase['date'] = date('Y-m-d', strtotime($purchase['date']));
        $products = $request->get('product_data');
        //return $purchase;
        $purchaseData = Sales::create($purchase);
        //$purchaseData->created_at = Carbon::now();
        //  if($purchaseData->dcn_no !="0"){
        //  $project = DeliveryChallan::where("dcn_no", $purchaseData->dcn_no)->first();
        //  $project->status = "Added";
        //  $project->save();
        // }
        $sum = "0";
        $tot = "0";
        $Code = "0";
        foreach ($products as $product) {
            // return $product;
            $purchaseDetail = new SaleDetail();
            $purchaseDetail->sale_id = $purchaseData['id'];
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

            //return "bel";

            $vouchers = new StockRegisterSpecificItem();
            $vouchers->direct_transferID = $purchaseData['id'];
            $vouchers->date = $purchaseData['date'];
            $vouchers->party_id = $product['party_id'];
            $vouchers->product_id = $product['product_id'];
            $vouchers->recipe_id = $product['product_id'];
            $vouchers->voucher_type = $purchaseData['sale_list'];
            $vouchers->warehouse_id = $purchaseData['warehouse_id'];
            $vouchers->uom_id = $product['uom_id'];
            $vouchers->sale_quantity = $product['quantity'];
            $vouchers->cost_rate = $product['balance'];
            $vouchers->save();

            // $stock = new StockRegisterSpecificItem();
            // $stock->stock_transferID = $purchaseData['id'];
            // $stock->date = $purchaseData['date'];
            // $stock->product_id = $product['product_id'];
            // $stock->recipe_id = $product['product_id'];
            // $stock->voucher_type = "Stock Transfer";
            // $stock->warehouse_id = $purchaseData['to_warehouse_id'];
            // $stock->uom_id = $product['uom_id'];
            // $stock->sale_quantity = $product['quantity'];
            // $stock->cost_rate = $product['balance'];
            // $stock->save();

            if ($Code == 3 || $Code == 4 || $Code == 11 || $Code == 12 || $Code == 17 || $Code == 18 || $Code == 19 || $Code == 20 || $Code == 21 || $Code == 35 || $Code == 36 || $Code == 37 || $Code == 38) {
                $stockless = RecipeCreationDetails::where('product_code', '=', $Code)->get();
                //return $stockless;
                foreach ($stockless as $stock) {
                    $RawMaterial = new RawMaterialStock();
                    $RawMaterial->sale_id = $purchaseData['id'];
                    $RawMaterial->warehouse_id = $purchaseData['warehouse_id'];
                    $RawMaterial->product_id = $stock['product_id'];
                    $RawMaterial->uom_id = $product['uom_id'];
                    $RawMaterial->date = $purchaseData['date'];
                    $RawMaterial->voucher_no = $purchaseData['invoice_no'];
                    $RawMaterial->cost_amount = $product['balance'];
                    $totalqty = $stock['quantity'] * $product['quantity'];
                    $RawMaterial->stockout = $totalqty;
                    $RawMaterial->save();
                }
            } else {
                $RawMaterial = new RawMaterialStock();
                $RawMaterial->sale_id = $purchaseData['id'];
                $RawMaterial->warehouse_id = $purchaseData['warehouse_id'];
                $RawMaterial->product_id = $product['product_id'];
                $RawMaterial->uom_id = $product['uom_id'];
                $RawMaterial->date = $purchaseData['date'];
                $RawMaterial->voucher_no = $purchaseData['invoice_no'];
                $RawMaterial->cost_amount = $product['balance'];
                $RawMaterial->stockout = $product['quantity'];
                $RawMaterial->save();
            }

            if (($purchaseData['sale_type']) == "Credit Sale") {
                $vouchers = new LedgerDetailWise();
                $vouchers->sale_id = $purchaseData['id'];
                $vouchers->party_id = $product['party_id'];
                $vouchers->voucher_no = $purchaseData['invoice_no'];
                $vouchers->voucher_type = $purchaseData['sale_type'];
                $vouchers->date = $purchaseData['date'];
                $vouchers->other = $purchaseData['particulars'];
                $vouchers->product_id = $product['product_id'];
                $vouchers->quantity = $product['quantity'];
                $vouchers->rate = $product['sale_rate'];
                $vouchers->debit = $product['balance'];
                $vouchers->save();
            }
        }

        if (($purchaseData['sale_type']) == "Cash Sale") {
            // $vouchers = new GeneralVoucher();
            // $vouchers->sale_id = $purchaseData['id'];
            // $vouchers->account_head_id = $purchaseData['party_id'];
            // $vouchers->date = $purchaseData['date'];
            // $vouchers->voucher_no = $purchaseData['invoice_no'];
            // $vouchers->v_type = $purchaseData['sale_type'];
            // $vouchers->credit = $sum;
            // $vouchers->save();

            $cash = new CashBook();
            $cash->date = $purchaseData['date'];
            $cash->vr_no = $purchaseData['invoice_no'];
            $cash->vr_type = $purchaseData['sale_type'];
            $cash->biller_id = Auth::User()->id;
            $cash->shop_id = $purchaseData['warehouse_id'];
            $cash->party_id = $purchaseData['party_id'];
            $cash->sale_id = $purchaseData['id'];
            $cash->in = $sum;
            $cash->save();
        }
        if (($purchaseData['sale_type']) == "Credit Sale") {
            $vouchers = new GeneralVoucher();
            $vouchers->sale_id = $purchaseData['id'];
            $vouchers->account_head_id = $purchaseData['party_id'];
            $vouchers->date = $purchaseData['date'];
            $vouchers->voucher_no = $purchaseData['invoice_no'];
            $vouchers->v_type = $purchaseData['sale_type'];
            $vouchers->debit = $sum;
            $vouchers->save();
        }
        //return $purchase['ReceiveAmount'];
        if (($purchase['ReceiveAmount']) != "0") {
            //return "ready";
            $cash = new CashBook();
            $cash->date = $purchaseData['date'];
            $cash->vr_no = $purchaseData['invoice_no'];
            $cash->vr_type = "Payment on Credit Bill";
            $cash->biller_id = Auth::User()->id;
            $cash->shop_id = $purchaseData['warehouse_id'];
            $cash->party_id = $purchaseData['party_id'];
            $cash->sale_id = $purchaseData['id'];
            $cash->in = $purchase['ReceiveAmount'];
            $cash->save();

            $vouchers = new GeneralVoucher();
            $vouchers->sale_id = $purchaseData['id'];
            $vouchers->account_head_id = $purchaseData['party_id'];
            $vouchers->date = $purchaseData['date'];
            $vouchers->voucher_no = $purchaseData['invoice_no'];
            $vouchers->v_type = "Payment on Credit Bill";
            $vouchers->credit = $purchase['ReceiveAmount'];
            $vouchers->save();

            $vouchers = new LedgerDetailWise();
            $vouchers->sale_id = $purchaseData['id'];
            $vouchers->party_id = $product['party_id'];
            $vouchers->voucher_no = $purchaseData['invoice_no'];
            $vouchers->voucher_type = "Payment on Credit Bill";
            $vouchers->date = $purchaseData['date'];
            // $vouchers->product_id = $product['product_id'];
            // $vouchers->quantity = $product['quantity'];
            $vouchers->rate = $product['sale_rate'];
            $vouchers->credit = $purchase['ReceiveAmount'];
            $vouchers->save();
        }
        //return "inserted";
        return $purchaseData['id'];
    }

    public function show($id)
    {
        $newsale_detail = Sales::with(['sale_details' => function ($query) {
            $query->with('uoms');
            $query->with(['products' => function ($query) {
                $query->with('publishers');
            }]);
            $query->with('taxes');
            $query->with('discount');
            $query->with('ledger');
            //$query->with('publishers');
        }])->with('parties')->with('billers')
            ->where('sales.id', '=', $id)
            ->get();
        //return $newsale_detail;

        $ledgers = GeneralVoucher::where('account_head_id', '=', $newsale_detail[0]->parties->id)
            ->whereBetween('general_vouchers.date', ['2019-01-01', $newsale_detail[0]->date])
            ->get();
        $logo = SystemLogo::where('id', '=', 1)->get();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('sales.thermal-print', Compact('newsale_detail', 'company_detail', 'ledgers', 'logo', 'ledgers'));
    }

    public function edit($id)
    {
        $purchase = Sales::with(['sale_details' => function ($query) {
            $query->with('products');
            $query->with('uoms');
            $query->with('discount');
        }])->with('parties')->where('sales.id', '=', $id)->get();
        //return $purchase;
        $DeliveryChallan = DeliveryChallan::where('status', '=', 'Pending')->OrderBy('dcn_no', 'asc')->pluck('dcn_no', 'dcn_no')->prepend('Select Challan', '0')->toArray();
        //$customer = Party::OrderBy('id', 'asc')->pluck('party_name', 'id');
        $edit = $purchase[0];
        //return $edit;
        $products = Product::where('catagory_id', '=', '6')->select(DB::raw('CONCAT(`id`, "_", `product_name`) AS `id`, `product_name`'))->OrderBy('id', 'asc')->pluck('product_name', 'id')->prepend('Select Product', '0')->toArray();
        //$taxes = Tax::select(DB::raw('CONCAT(`id`, "_", `tax_rate`) AS `tax_rate`, `tax_title`'))->OrderBy('id', 'asc')->pluck('tax_title', 'tax_rate')->toArray();

        //$taxes = Tax::select(DB::raw('CONCAT(`id`, "_", `tax_rate`) AS `tax_rate`, `tax_title`'))->OrderBy('tax_title', 'asc')->pluck('tax_title', 'tax_rate')->prepend('Select Tax', '0.00')->toArray();
        $customers = Party::OrderBy('party_name', 'asc')->pluck('Party_name', 'id')->prepend('Select Customer', '0')->toArray();
        $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            ->where('parties.account_group_id', '=', '1')
            ->Orwhere('parties.account_group_id', '=', '7')
            ->OrderBy('party_name', 'asc')->pluck('party_name', 'parties.id');
        $uoms = UOM::select(DB::raw('CONCAT(`id`, "_", `uom`) AS `id`, `uom`'))->OrderBy('id', 'asc')->pluck('uom', 'id')->toArray();
        $discounts = Discount::select(DB::raw('CONCAT(`id`, "_", `discount`) AS `discount`, `title`'))->OrderBy('id', 'asc')->pluck('title', 'discount')->toArray();
        $warehouse = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('credit-sale.edit', Compact('edit', 'products', 'DeliveryChallan', 'customers', 'encrypted_token', 'uoms', 'discounts', 'warehouse'));
    }

    public function update(Request $request, $id)
    {
        $objPurchase = Sales::findOrFail($id);

        $purchase = json_decode($request->get('purchase'), true);

        $objPurchase->date = date('Y-m-d', strtotime($purchase['date']));
        $objPurchase->invoice_no = $purchase['invoice_no'];
        $objPurchase->localExport = $purchase['localExport'];
        $objPurchase->biller = $purchase['biller'];
        $objPurchase->sale_type = $purchase['sale_type'];
        $objPurchase->sale_list = $purchase['sale_list'];
        $objPurchase->sample_description = $purchase['sample_description'];
        //$objPurchase->dcn_no = $purchase['dcn_no'];
        $objPurchase->warehouse_id = $purchase['warehouse_id'];
        $objPurchase->party_id = $purchase['party_id'];
        $objPurchase->due_date = $purchase['due_date'];
        $objPurchase->particulars = $purchase['particulars'];
        //return $objPurchase;
        $objPurchase->save();

        SaleDetail::where('sale_id', '=', $id)->delete();
        GeneralVoucher::where('sale_id', '=', $id)->delete();
        LedgerDetailWise::where('sale_id', '=', $id)->delete();
        StockRegisterSpecificItem::where('sale_id', '=', $id)->delete();
        RawMaterialStock::where('sale_id', '=', $id)->delete();
        CashBook::where('sale_id', '=', $id)->delete();
        $products = $request->get('product_data');

        $sum = 0;
        foreach ($products as $product) {
            $purchaseDetail = new SaleDetail();
            $purchaseDetail->sale_id = $objPurchase['id'];
            $purchaseDetail->product_id = $product['product_id'];
            $purchaseDetail->party_id = $product['party_id'];
            $purchaseDetail->uom_id = $product['uom_id'];
            $purchaseDetail->quantity = $product['quantity'];
            $purchaseDetail->product_cost = $product['product_cost'];
            $purchaseDetail->cost_amount = $product['cost_amount'];
            $purchaseDetail->discount_id = $product['discount_id'];
            $purchaseDetail->biller = $objPurchase['biller'];
            $purchaseDetail->warehouse_id = $objPurchase['warehouse_id'];
            $purchaseDetail->sale_rate = $product['sale_rate'];
            $purchaseDetail->sale_amount = $product['balance'];
            $sum = $sum + $product['balance'];
            $purchaseDetail->save();

            $RawMaterial = new RawMaterialStock();
            $RawMaterial->sale_id = $objPurchase['id'];
            $RawMaterial->warehouse_id = $objPurchase['warehouse_id'];
            $RawMaterial->product_id = $product['product_id'];
            $RawMaterial->uom_id = $product['uom_id'];
            $RawMaterial->cost_amount = $product['balance'];
            $RawMaterial->stockout = $product['quantity'];
            $RawMaterial->save();

            $vouchers = new StockRegisterSpecificItem();
            $vouchers->sale_id = $objPurchase['id'];
            $vouchers->date = $objPurchase['date'];
            $vouchers->party_id = $product['party_id'];
            $vouchers->product_id = $product['product_id'];
            $vouchers->voucher_type = $objPurchase['sale_list'];
            $vouchers->warehouse_id = $objPurchase['warehouse_id'];
            $vouchers->uom_id = $product['uom_id'];
            $vouchers->sale_quantity = $product['quantity'];
            $vouchers->cost_rate = $product['balance'];
            $vouchers->save();


            if (($objPurchase['sale_type']) == "Credit Sale") {
                $vouchers = new LedgerDetailWise();
                $vouchers->sale_id = $objPurchase['id'];
                $vouchers->party_id = $product['party_id'];
                $vouchers->voucher_no = $objPurchase['invoice_no'];
                $vouchers->voucher_type = $objPurchase['sale_type'];
                $vouchers->date = $objPurchase['date'];
                $vouchers->other = $objPurchase['particulars'];
                $vouchers->product_id = $product['product_id'];
                $vouchers->quantity = $product['quantity'];
                $vouchers->rate = $product['sale_rate'];
                $vouchers->debit = $product['balance'];
                $vouchers->save();
            }
        }

        // }
        // if(($objPurchase['sale_type']) =="Cash Sale"){
        //     $vouchers = new GeneralVoucher();
        //     $vouchers->sale_id = $objPurchase['id'];
        //     $vouchers->account_head_id = $objPurchase['party_id'];
        //     $vouchers->date = $objPurchase['date'];
        //     $vouchers->voucher_no = $objPurchase['invoice_no'];
        //     $vouchers->v_type = $objPurchase['sale_type'];
        //     $vouchers->debit = $sum;
        //     $vouchers->save();

        //     $cash = new CashBook();
        //     $cash->date = $objPurchase['date'];
        //     $cash->vr_no = $objPurchase['invoice_no'];
        //     $cash->vr_type = $objPurchase['sale_type'];
        //     $cash->party_id = $objPurchase['party_id'];
        //     $cash->sale_id = $objPurchase['id'];
        //     $cash->in = $sum;
        //     $cash->save();
        //  }
        if (($objPurchase['sale_type']) == "Credit Sale") {
            $vouchers = new GeneralVoucher();
            $vouchers->sale_id = $objPurchase['id'];
            $vouchers->account_head_id = $objPurchase['party_id'];
            $vouchers->date = $objPurchase['date'];
            $vouchers->voucher_no = $objPurchase['invoice_no'];
            $vouchers->v_type = $objPurchase['sale_type'];
            $vouchers->credit = $sum;
            $vouchers->save();
        }
        //return $products;
        return $objPurchase['id'];
    }

    public function destroy($id)
    {
        $delete = Sales::findOrFail($id);
        $delete->delete();
        SaleDetail::where('sale_id', '=', $id)->delete();
        GeneralVoucher::where('sale_id', '=', $id)->delete();
        LedgerDetailWise::where('sale_id', '=', $id)->delete();
        StockRegisterSpecificItem::where('sale_id', '=', $id)->delete();
        SalePointStock::where('sale_id', '=', $id)->delete();
        RawMaterialStock::where('sale_id', '=', $id)->delete();
        CashBook::where('sale_id', '=', $id)->delete();

        // Ledger::where('sale_id', '=', $id)->delete();       
        return "Sale has been Deleted Successfully!";
    }
}
