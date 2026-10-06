<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Tax;
use App\Models\Party;
use App\Models\Discount;
use App\Models\Setting;
use App\Models\Ledger;
use App\Models\DeliveryChallan;
use App\Models\SaleTax;
use App\Models\SaleTaxDetails;
use App\Models\GeneralVoucher;
use App\Models\LedgerDetailWise;
use App\Models\UOM;
use App\Models\StockRegisterSpecificItem;
use App\Models\SystemLogo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PDF;
use Carbon\Carbon;

class SalesTaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $sales = SaleTax::OrderBy('id', 'desc')->with(['saletax_details' => function ($query) {
            //$query->with('taxes');
            //$query->with('discount');
            //$query->with('parties');
        }])->with('billers')->get();

        return view('salestax.index', compact('sales'));
    }

    public function create()
    {
        $code = SaleTax::OrderBy('id', 'asc')->get();
        $codes = $code->last()->invoice_no + 1;
    
        $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_code`, "_", `product_name`, "_", `tax`, "_", `uom`, "_", `product_cost`) AS `id`, `product_code`, `product_name`, `tax`, `uom`,`product_cost`'))->OrderBy('id', 'asc')->pluck('product_name', 'id')->prepend('Select Product', '0')->toArray();

        $taxes = Tax::select(DB::raw('CONCAT(`id`, "_", `tax_rate`) AS `tax_rate`, `tax_title`'))->OrderBy('id', 'asc')->pluck('tax_title', 'tax_rate')->toArray();
        $discounts = Discount::select(DB::raw('CONCAT(`id`, "_", `discount`) AS `discount`, `title`'))->OrderBy('id', 'asc')->pluck('title', 'discount')->toArray();
    
        $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            ->where('parties.account_group_id', '=', '1')
            ->orwhere('parties.account_group_id', '=', '7')
            ->OrderBy('party_name', 'asc')->pluck('party_name', 'parties.id')->prepend('Select Customer', '');

        $DeliveryChallan = DeliveryChallan::where('status', '=', 'Pending')->OrderBy('dcn_no', 'asc')->pluck('dcn_no', 'dcn_no')->prepend('Select Challan', '0')->toArray();
        $uoms = UOM::select(DB::raw('CONCAT(`id`, "_", `uom`) AS `id`, `uom`'))->OrderBy('id', 'asc')->pluck('uom', 'id')->toArray();

        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());

        return view('salestax.create', compact('customers', 'products', 'taxes', 'discounts', 'DeliveryChallan', 'codes', 'uoms', 'encrypted_token'));
    }

    public function LoadPreviousData(Request $request)
    {
        // return "st";
        $prevoucher = SalePurchase::where('voucher_no', '<', $request->voucher_no)->whereType('SALESTAX INVOICE')->max('voucher_no');
        $sale = SalePurchase::where('voucher_no', $prevoucher)->whereType('SALESTAX INVOICE')->first();
        if ($sale) {
            if ($sale->challan_type == 'CDC') {
                $edit = SalePurchaseDetail::with(['cusproduct' => function ($re) {
                    $re->with('product:id,product_name,uom,tax,code');
                }])
                    ->with(['salepurchase' => function ($qry) {
                        $qry->with('dc');
                    }])
                    ->with('party:id,party_name,address')
                    ->whereType('SALESTAX INVOICE')
                    ->where('sale_purchase_id', $sale->id)
                    ->get();
                return Response::json(['data' => $edit]);
            } else {
                $edit = SalePurchaseDetail::with('product:id,product_name,uom,tax,code,packing')
                    ->with(['salepurchase' => function ($qry) {
                        $qry->with('dc');
                    }])
                    ->with('party:id,party_name,address')
                    ->whereType('SALESTAX INVOICE')
                    ->where('sale_purchase_id', $sale->id)
                    ->get();

                return Response::json(['data' => $edit]);
            }
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function store(Request $request)
    {
        // return request->all();
        if(!isset($request->product_id1))
		{
			return redirect()->back()->with('failure_message','Please Enter 1 Record');
		}
		if(!isset($request->invoice_no))
		{
			return redirect()->back()->with('failure_message','Please Enter Bill No');
		}
		if(!isset($request->party_id))
		{
			return redirect()->back()->with('failure_message','Please Select Customer');
		}


        $purchaseData = new SaleTax();
        $purchaseData->party_id = $request->party_id;
        $purchaseData->date = Carbon::createFromFormat('d/m/Y', $request->date);
        $purchaseData->sale_type = $request->sale_type;
        $purchaseData->invoice_no = $request->invoice_no;
        $purchaseData->dcn_no = $request->dcn_no;
        $purchaseData->p_order = $request->p_order;
        $purchaseData->remarks = $request->remarks;
        $purchaseData->biller = Auth::User()->id;
        $purchaseData->save();

        $sum = "0";
        $count = count($request->product_id1);
		for ($i = 0; $i < $count; $i++) {
            $purchaseDetail = new SaleTaxDetails();
            $purchaseDetail->sale_id = $purchaseData->id;
            $purchaseDetail->product_id = $request->product_id1[$i];
            $purchaseDetail->party_id = $request->party_id;
            $purchaseDetail->uom_id = $request->uom_id[$i];
            if (($request->lessCommercial) == "true") {
                $purchaseDetail->status = "stockin";
            } else {
                $purchaseDetail->status = "InvoiceOnly";
            }
            $purchaseDetail->quantity = $request->quantity[$i];
            $purchaseDetail->rate = $request->rate[$i];
            $purchaseDetail->stvalue = $request->stvalue[$i];
            $purchaseDetail->taxvalue = $request->taxvalue[$i];
            if(empty($request->extratax[$i]))
            {
                $purchaseDetail->extratax = 0;
            }else{
                $purchaseDetail->extratax = $request->extratax[$i];
            }

            if(empty($request->extraTaxValue[$i]))
            {
                $purchaseDetail->extraTaxValue = 0;
            }else{
                $purchaseDetail->extraTaxValue = $request->extraTaxValue[$i];
            }
            
            
            $purchaseDetail->price = $request->excvalue[$i];
            $purchaseDetail->total = $request->incvalue[$i];
            $sum = $sum + $request->incvalue[$i];
            $purchaseDetail->save();

            // if (($request->lessCommercial) == "true") {
            //     $vouchers = new StockRegisterSpecificItem();
            //     $vouchers->dc_id = $purchaseData->id;
            //     $vouchers->date = $purchaseData->date;
            //     $vouchers->party_id = $request->party_id;
            //     $vouchers->product_id = $request->product_id1[$i];
            //     $vouchers->voucher_type = $purchaseData->sale_type;
            //     $vouchers->uom_id = $request->uom_id[$i];
            //     $vouchers->sale_quantity = $request->quantity[$i];
            //     $vouchers->cost_rate = $request->incvalue[$i];
            //     $vouchers->save();
            // }
        
            // $vouchers = new LedgerDetailWise();
            // $vouchers->dc_id = $purchaseData->id;
            // $vouchers->party_id = $request->party_id;
            // $vouchers->voucher_no = $purchaseData->invoice_no;
            // $vouchers->voucher_type = "SalesTax Invoice";
            // $vouchers->date = $purchaseData->date;
            // $vouchers->product_id = $request->product_id1[$i];
            // $vouchers->quantity = $request->quantity[$i];
            // $vouchers->rate = $request->rate[$i];
            // $vouchers->other = $request->TotalTax[$i];
            // $vouchers->debit = $request->incvalue[$i];
            // $vouchers->save();
        }

        $vouchers = new GeneralVoucher();
        $vouchers->dc_id = $purchaseData->id;
        $vouchers->account_head_id = $purchaseData->party_id;
        $vouchers->date = $purchaseData->date;
        $vouchers->voucher_no = $purchaseData->invoice_no;
        $vouchers->invoice_no = $purchaseData->invoice_no;
        $vouchers->v_type = $purchaseData->sale_type;
        $vouchers->debit = $sum;
        $vouchers->save();

        Session::flash('flash_message', 'Record Successfully Added!');
    
        $url = "salestax/print/".$purchaseData->id;
		$urlindex = "salestax/create";

		return "<script>window.open('".$url."', '_blank')</script>
				<script>window.location.href='".$urlindex."';</script>";
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $purchase = SaleTax::with(['saletax_details' => function ($query) {
            $query->with('products');
            $query->with('uoms');
        }])->with('parties')->where('sale_taxes.id', '=', $id)->get();

        $DeliveryChallan = DeliveryChallan::where('status', '=', 'Pending')->OrderBy('dcn_no', 'asc')->pluck('dcn_no', 'dcn_no')->prepend('Select Challan', '0')->toArray();
        $edit = $purchase[0];
        // return $edit->id;
        $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_name`) AS `id`, `product_name`'))->OrderBy('id', 'asc')->pluck('product_name', 'id')->prepend('Select Product', '0')->toArray();
        $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            ->where('parties.account_group_id', '=', '1')
            ->orwhere('parties.account_group_id', '=', '7')
            ->OrderBy('party_name', 'asc')->pluck('party_name', 'parties.id');
        $uoms = UOM::select(DB::raw('CONCAT(`id`, "_", `uom`) AS `id`, `uom`'))->OrderBy('id', 'asc')->pluck('uom', 'id')->toArray();
        $discounts = Discount::select(DB::raw('CONCAT(`id`, "_", `discount`) AS `discount`, `title`'))->OrderBy('id', 'asc')->pluck('title', 'discount')->toArray();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('salestax.edit', compact('edit', 'products', 'DeliveryChallan', 'customers', 'encrypted_token', 'uoms', 'discounts'));
    }

    public function update(Request $request, $id)
    {   
        if(!isset($request->product_id1))
		{
			return redirect()->back()->with('failure_message','Please Enter 1 Record');
		}
		if(!isset($request->invoice_no))
		{
			return redirect()->back()->with('failure_message','Please Enter Bill No');
		}
		if(!isset($request->party_id))
		{
			return redirect()->back()->with('failure_message','Please Select Customer');
		}


        $objPurchase = SaleTax::findOrFail($id);

        $objPurchase->party_id = $request->party_id;
        $objPurchase->date = date('d/m/Y', strtotime($request->date));
        $objPurchase->sale_type = $request->sale_type;
        $objPurchase->invoice_no = $request->invoice_no;
        $objPurchase->dcn_no = $request->dcn_no;
        $objPurchase->p_order = $request->p_order;
        $objPurchase->remarks = $request->remarks;
        $objPurchase->biller = Auth::User()->id;
        $objPurchase->save();

        SaleTaxDetails::where('sale_id', '=', $id)->delete();
        GeneralVoucher::where('dc_id', '=', $id)->delete();
        LedgerDetailWise::where('dc_id', '=', $id)->delete();
        StockRegisterSpecificItem::where('dc_id', '=', $id)->delete();
 
        $sum = "0";
        $count = count($request->product_id1);
		for ($i = 0; $i < $count; $i++) {
            $purchaseDetail = new SaleTaxDetails();
            $purchaseDetail->sale_id = $objPurchase->id;
            $purchaseDetail->product_id = $request->product_id1[$i];
            $purchaseDetail->party_id = $request->party_id;
            $purchaseDetail->uom_id = $request->uom_id[$i];
            if (($request->lessCommercial) == "true") {
                $purchaseDetail->status = "stockin";
            } else {
                $purchaseDetail->status = "InvoiceOnly";
            }
            $purchaseDetail->quantity = $request->quantity[$i];
            $purchaseDetail->rate = $request->rate[$i];
            $purchaseDetail->stvalue = $request->stvalue[$i];
            $purchaseDetail->taxvalue = $request->taxvalue[$i];
            if(empty($request->extratax[$i]))
            {
                $purchaseDetail->extratax = 0;
            }else{
                $purchaseDetail->extratax = $request->extratax[$i];
            }

            if(empty($request->extraTaxValue[$i]))
            {
                $purchaseDetail->extraTaxValue = 0;
            }else{
                $purchaseDetail->extraTaxValue = $request->extraTaxValue[$i];
            }
            
            
            $purchaseDetail->price = $request->excvalue[$i];
            $purchaseDetail->total = $request->incvalue[$i];
            $sum = $sum + $request->incvalue[$i];
            $purchaseDetail->save();

            if (($request->lessCommercial) == "true") {
                $vouchers = new StockRegisterSpecificItem();
                $vouchers->dc_id = $objPurchase->id;
                $vouchers->date = $objPurchase->date;
                $vouchers->party_id = $request->party_id;
                $vouchers->product_id = $request->product_id1[$i];
                $vouchers->voucher_type = $objPurchase->sale_type;
                $vouchers->uom_id = $request->uom_id[$i];
                $vouchers->sale_quantity = $request->quantity[$i];
                $vouchers->cost_rate = $request->incvalue[$i];
                $vouchers->save();
            }
        
            $vouchers = new LedgerDetailWise();
            $vouchers->dc_id = $objPurchase->id;
            $vouchers->party_id = $request->party_id;
            $vouchers->voucher_no = $objPurchase->invoice_no;
            $vouchers->voucher_type = "SalesTax Invoice";
            $vouchers->date = $objPurchase->date;
            $vouchers->product_id = $request->product_id1[$i];
            $vouchers->quantity = $request->quantity[$i];
            $vouchers->rate = $request->rate[$i];
            $vouchers->other = $request->TotalTax[$i];
            $vouchers->debit = $request->incvalue[$i];
            $vouchers->save();
        }

        $vouchers = new GeneralVoucher();
        $vouchers->dc_id = $objPurchase->id;
        $vouchers->account_head_id = $objPurchase->party_id;
        $vouchers->date = $objPurchase->date;
        $vouchers->voucher_no = $objPurchase->invoice_no;
        $vouchers->invoice_no = $objPurchase->invoice_no;
        $vouchers->v_type = $objPurchase->sale_type;
        $vouchers->debit = $sum;
        $vouchers->save();

        Session::flash('flash_message', 'Record Successfully Updated!');
        return redirect('salestax');
    }

    public function destroy($id)
    {
        $delete = SaleTax::findOrFail($id);
        $delete->delete();
        SaleTaxDetails::where('sale_id', '=', $id)->delete();
        GeneralVoucher::where('dc_id', '=', $id)->delete();
        LedgerDetailWise::where('dc_id', '=', $id)->delete();
        StockRegisterSpecificItem::where('dc_id', '=', $id)->delete();
        return "Sales Tax Invoice Deleted Successfully!";
    }

    public function PartyChange(Request $request)
    {
        $partyID = $request->get('party_ID');
        $data = Party::where('id', '=', $partyID)->get();
        return $data;
    }

    public function productChange(Request $request)
    {
        $productID = $request->get('product_ID');
        $data = Product::where('id', '=', $productID)->get(['products.*']);
        return $data;
    }

    public function print_sale($id)
    {
        $newsale_detail = SaleTax::with(['saletax_details' => function ($query) {
            $query->with('uoms');
            $query->with('products');
        }])->where('sale_taxes.id', '=', $id)
            ->get();
        $ledgers = Ledger::with('ledger_party')->where('party_id', '=', $newsale_detail[0]->parties->id)->get();

        $company_detail = Setting::where('id', '=', 1)->get();
        $logo = SystemLogo::where('id', '=', 1)->get();

        return view('salestax.myc', compact('newsale_detail', 'company_detail', 'ledgers', 'logo','id'));
    }


    public function print_pdf($id)
    {
        $newsale_detail = SaleTax::with(['saletax_details' => function ($query) {
            $query->with(['products' => function ($query) {
            }]);
            //$query->with('taxes');
            //$query->with('discount');
            //$query->with('ledger');
            //$query->with('publishers');
        }]) //->with('parties')->with('billers')
            ->where('sale_taxes.id', '=', $id)
            ->get();
        $ledgers = Ledger::with('ledger_party')->where('party_id', '=', $newsale_detail[0]->parties->id)->get();

        $company_detail = Setting::where('id', '=', 1)->get();
        $logo = SystemLogo::where('id', '=', 1)->get();

        $pdf = PDF::loadView('salestax.printpdf', ['newsale_detail' => $newsale_detail, 'company_detail' => $company_detail, 'logo' => $logo]);
        return $pdf->download('salestax_invoice.pdf');
    }


    public function print_dc($id)
    {
        $newsale_detail = SaleTax::with(['saletax_details' => function ($query) {
            $query->with(['products' => function ($query) {
            }]);
            //$query->with('taxes');
            //$query->with('discount');
            //$query->with('ledger');
            //$query->with('publishers');
        }]) //->with('parties')->with('billers')
            ->where('sale_taxes.id', '=', $id)
            ->get();
        $ledgers = Ledger::with('ledger_party')->where('party_id', '=', $newsale_detail[0]->parties->id)->get();

        $company_detail = Setting::where('id', '=', 1)->get();
        $logo = SystemLogo::where('id', '=', 1)->get();

        return view('salestax.dcn', compact('newsale_detail', 'company_detail', 'ledgers', 'logo'));
    }
}
