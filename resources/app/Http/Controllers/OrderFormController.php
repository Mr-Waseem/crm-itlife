<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderForm;
use App\Models\OrderFormDetails;
use App\Models\Product;
use App\Models\Party;
use App\Models\Warehouse;
use App\Models\UOM;
use App\Models\SystemLogo;
use DB;
use Illuminate\Support\Facades\Session;

class OrderFormController extends Controller
{
    public function index()
    {
        $data = OrderForm::OrderBy('id', 'desc')->get();
        return view('order-form.index');
        return $data;
    }

    public function find(Request $request)
    {
        $this->validate($request, [
            // 'account_id' => 'required|unique:parties',
            'search' => 'required'
        ]);

        $search = $request->get('search');
        $sales = OrderForm::where('voucher_no', 'LIKE', '%' . $search . '%')
            //->orWhere('date','LIKE','%'.$search.'%')
            ->OrderBy('id', 'dsc')->with('billers')->get();
        //return $sales;
        return view('order-form.index', Compact('sales'));
    }

    public function create()
    {
        $code = OrderForm::OrderBy('id', 'asc')->get();

        //return $code;
        if (count($code) > 0) {
            $codes = (int)$code->last()->voucher_no + 1;
        } else {
            $codes = 1;
        }


        //$codes = $code->last()->voucher_no + 1;


        // $products = Product::OrderBy('product_name', 'asc')->pluck('product_name', 'product_name')->prepend('Select Product', '0')->toArray();
        $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_name`) AS `id`, `product_name`'))->OrderBy('id', 'asc')->pluck('product_name', 'id')->prepend('Select Product', '0')->toArray();

        //$suppliers = Supplier::OrderBy('id', 'asc')->pluck('name', 'id');
        $Account = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            ->where('parties.account_group_id', '=', '1')
            //->Orwhere('parties.account_group_id', '=', '3')
            //->Orwhere('parties.account_group_id', '=', '7')
            ->OrderBy('party_name', 'asc')->pluck('party_name', 'parties.id')->prepend('Select Customer', '');
        //return $Account;
        //->prepend('CASH IN HAND', '1');
        $warehouse = Warehouse::OrderBy('name', 'asc')
            ->where('id', '=', 1)
            ->pluck('name', 'id')->toArray();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        $uoms = UOM::select(DB::raw('CONCAT(`id`, "_", `uom`) AS `id`, `uom`'))->OrderBy('id', 'asc')->pluck('uom', 'id')->prepend('PCS', '2_PCS')->toArray();
        return view('order-form.create-simple', Compact('Account', 'products', 'codes', 'encrypted_token', 'uoms', 'warehouse'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            // 'account_id' => 'required|unique:parties',
            'account_id' => 'required',
            'product_id' => 'required'
        ]);
        //return $request->all();
        //return $request->all();
        $quotation = OrderForm::create($request->all());
        //return $quotation;
        $count = Count($request->product_id);
        //return $count; 
        for ($i = 0; $i < $count; $i++) {
            $detail = new OrderFormDetails();
            $detail->order_id = $quotation['id'];
            $detail->account_id = $quotation['account_id'];
            $detail->product_id = $request->product_id[$i];
            $detail->quantity = $request->quantity[$i];
            $detail->price = $request->price[$i];
            $detail->packing_id = $request->packing_id[$i];
            $detail->terms = $request->terms[$i];
            $detail->v_type = $quotation['v_type'];
            $detail->shop_id = $quotation['shop_id'];
            $detail->biller_id = $quotation['biller_id'];
            $detail->save();
        }

        Session::flash('flash_message', 'Record successfully added!');
        return redirect('order-form/create');
    }

    public function show($id)
    {
        $details = OrderForm::with(['order_details' => function ($query) {
            $query->with('products');
            $query->with('uom');
        }])->with('customer')->with('billers')
            ->where('id', '=', $id)->get();
        $logo = SystemLogo::where('id', '=', 1)->get();
        return view('order-form.print', Compact('details', 'logo'));
        return $details;
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
        $delete = OrderForm::findOrFail($id);
        $delete->delete();
        OrderFormDetails::where('order_id', '=', $id)->delete();
        // LedgerDetailWise::where('purchase_milk_id', '=', $id)->delete();       
        return "Order Form has been Deleted Successfully!";
    }
}
