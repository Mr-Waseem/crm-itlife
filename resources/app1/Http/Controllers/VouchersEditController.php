<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vouchers;
use App\Models\GeneralVoucher;
use App\Models\Party;
use App\Models\LedgerDetailWise;
use Illuminate\Support\Facades\Session;

class VouchersEditController extends Controller
{
    public function index()
    {
        //$Vouchers = GeneralVoucher::OrderBy('voucher_no')->get();
        $Vouchers = Vouchers::with(['voucher_details' => function ($query) {
            //$query->with('products');
            //$query->with('uoms');
            //$query->with('discount');
        }])->with('parties')->get();
        // return $purchase;
        return view('vouchers.index', Compact('Vouchers'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {

        //$edit = GeneralVoucher::findOrFail($id);
        $parties = Party::OrderBy('party_name', 'asc')->pluck('party_name', 'id')->toArray();
        return view('vouchers.edit', Compact('edit', 'parties'));
        //return $edit;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'date' => 'required',
            'voucher_no' => 'required',
            'narration' => 'required'
        ]);
        $update = GeneralVoucher::findOrFail($id);
        //return $update['id'];
        $update->update($request->all());
        LedgerDetailWise::where('voucher_id', '=', $id)->delete();
        $vouchers = new LedgerDetailWise();
        $vouchers->voucher_id = $update['id'];
        $vouchers->party_id = $request['account_head_id'];
        $vouchers->voucher_no = $request['voucher_no'];
        $vouchers->voucher_type = $request['v_type'];
        $vouchers->date = $request['date'];
        $vouchers->other = $request['narration'];
        $vouchers->debit = $request['debit'];
        $vouchers->credit = $request['credit'];
        $vouchers->save();
        Session::flash('flash_message', 'Voucher Updated Successfully!');
        return redirect('vouchers');
    }

    public function destroy($id)
    {
        $delete = GeneralVoucher::findOrFail($id);
        $delete->delete();
        LedgerDetailWise::where('voucher_id', '=', $id)->delete();
        return "Voucher Delted Successfully!";
    }

    public function print_voucher($id)
    {
        $purchase = Vouchers::with(['voucher_details' => function ($query) {
            //$query->with('products');
            //$query->with('uoms');
            //$query->with('discount');
        }])->with('parties')->where('vouchers.id', '=', $id)->get();
        return $purchase;
    }
}
