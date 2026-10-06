<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SaleDetail;
use App\Models\Setting;
use App\Models\StockRegisterSpecificItem;
use App\Models\Warehouse;
use App\Models\RawMaterialStock;
use App\Models\Purchase;
use App\Models\Production;
use App\Models\RawMaterialToSalePoint;
use App\Models\SalePointStock;
use App\Models\Sales;
use App\Models\StockTransfer;
use App\Models\Wastage;
use App\Models\ProductionStock;
use Illuminate\Support\Facades\DB;

class StockRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $products = Product::OrderBy('product_name', 'asc')->pluck('product_name', 'id')->prepend('Select Item', '')->toArray();
        $warehouse = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('stock-register-specific-item.index', Compact('encrypted_token', 'products', 'warehouse'));
    }

    public function report(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);
        $ProductID = $request->get('product_id');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $product = Product::where('id', '=', $ProductID)->get();
        $items = StockRegisterSpecificItem::join('products', 'products.id', '=', 'stock_register_specific_items.product_id')
            ->join('parties', 'parties.id', '=', 'stock_register_specific_items.party_id')
            ->OrderBy('stock_register_specific_items.id', 'asc')
            ->whereBetween('stock_register_specific_items.date', [$fromDate, $toDate])
            ->where('stock_register_specific_items.product_id', '=', $ProductID)->get();

        $company_detail = Setting::where('id', '=', 1)->get();
        return view('stock-register-specific-item.report', Compact('items', 'company_detail', 'product'));
    }

    public function reportFifo(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);
        $ProductID = $request->get('product_id');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $WarehouseID = $request->get('warehouse_id');
        $company_detail = Setting::where('id', '=', 1)->get();
        $product = Product::where('id', '=', $ProductID)->get();

        $StockReport = $request->get('stock_report');
        if ($StockReport == 1) {

            $items = StockRegisterSpecificItem::OrderBy('date', 'asc')->with('recipe_name')->with('parties')->with('products')->with('uoms')->with('shops')
                ->OrderBy('stock_register_specific_items.id', 'asc')
                ->whereDate('stock_register_specific_items.date', '>=', $fromDate)
                ->whereDate('stock_register_specific_items.date', '<=', $toDate)
                ->where('stock_register_specific_items.product_id', '=', $ProductID)
                ->get();

            $openingStock = ProductionStock::where('product_id', '=', $ProductID)
                ->select(DB::raw('SUM(stockin) as Purchase, SUM(stockout) as Sale'))
                ->whereDate('created_at', '>=', '2019-01-01')
                ->whereDate('created_at', '<', $fromDate)
                ->get();

            return view('stock-register-specific-item.stock-register-specific-item-fifo', Compact('items', 'company_detail', 'product', 'fromDate', 'toDate', 'openingStock'));
        }

        if ($StockReport == 2) {
            $items = RawMaterialStock::with('uoms')->with('warehouse')->where('product_id', '=', $ProductID)
                ->whereDate('date', '>=', $fromDate)
                ->whereDate('date', '<=', $toDate)
                ->OrderBy('id', 'asc')->get();

            $openingStock = RawMaterialStock::where('product_id', '=', $ProductID)
                ->whereDate('date', '>=', '2019-01-01')
                ->whereDate('date', '<', $fromDate)
                ->select(DB::raw('SUM(stockin) as stockin, SUM(stockout) as stockout'))
                ->OrderBy('date', 'asc')->get();

            return view('stock-register-specific-item.rawmaterial', Compact('items', 'company_detail', 'product', 'fromDate', 'toDate', 'openingStock'));
        }
    }

    public function updateDate()
    {
        $raw = RawMaterialStock::where('sale_id', '!=', null)->OrderBy('id', 'asc')->get();
        return $raw;
        $raw = RawMaterialStock::OrderBy('id', 'asc')->get();
        foreach ($raw as $data) {
            if ($data->purchase_id != null) {
                $purchase = Purchase::where('id', '=', $data->purchase_id)->get();
                $data->date = $purchase[0]->date;
                $data->save();
            }

            if ($data->production_id != null) {
                $production = Production::where('id', '=', $data->production_id)->get();
                $data->date = $production[0]->date;
                $data->save();
            }

            if ($data->direct_transferID != null) {
                $directTrans = RawMaterialToSalePoint::where('id', '=', $data->direct_transferID)->get();
                $data->date = $directTrans[0]->date;
                $data->save();
            }

            if ($data->sale_id != null) {
                $sale = Sales::where('id', '=', $data->sale_id)->get();
                $data->date = $sale[0]->date;
                $data->save();
            }
        }
    }


    public function updateDateSalePoint()
    {
        $salePoint = SalePointStock::OrderBy('id', 'asc')->get();
        foreach ($salePoint as $data) {
            if ($data->sale_id != null) {
                $sales = Sales::where('id', '=', $data->sale_id)->get();
                $data->vr_no = $sales[0]->invoice_no;
                $data->date = $sales[0]->date;
                $data->biller = $sales[0]->biller;
                $data->save();
            }

            if ($data->direct_transferID != null) {
                $directTrans = RawMaterialToSalePoint::where('id', '=', $data->direct_transferID)->get();
                $data->vr_no = $directTrans[0]->invoice_no;
                $data->date = $directTrans[0]->date;
                $data->biller = $directTrans[0]->biller;
                $data->save();
            }

            if ($data->pro_transfer_id != null) {
                $production = StockTransfer::where('id', '=', $data->pro_transfer_id)->get();
                $data->vr_no = $production[0]->invoice_no;
                $data->date = $production[0]->date;
                $data->biller = $production[0]->biller;
                $data->save();
            }

            if ($data->wastage_id != null) {
                $wastage = Wastage::where('id', '=', $data->wastage_id)->get();
                $data->vr_no = $wastage[0]->invoice_no;
                $data->date = $wastage[0]->date;
                $data->biller = $wastage[0]->biller;
                $data->save();
            }
        }
        return "done";
    }

    public function updateuoms()
    {
        $stock = StockRegisterSpecificItem::all();
        foreach ($stock as $stocks) {
            $saledetail = SaleDetail::where('sale_id', '=', $stocks->sale_id)->get();
            if ($stocks->voucher_type == "Sale Bill") {
                $stocks->uom_id = $saledetail[0]->uom_id;
                $stocks->save();
            }
        }
    }
}
