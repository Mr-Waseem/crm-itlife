<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\GeneralVoucher;
use App\Models\LedgerDetailWise;
use App\Models\Vouchers;
use App\Models\CashBook;
use App\Models\Warehouse;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\File;
use Session;
class OpeningBalanceVoucher extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index1(Request $request)
    {
        $codes = 1;
        $code = Vouchers::where('v_type', 'Opening Balance')
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->OrderBy('id', 'desc')->first();
        if ($code) {
            $codes = (int)$code->voucher_no + 1;
        }

        $Accounts = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))
            ->whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party/Account', '');
        return view('opening-balance.index', compact('codes', 'Accounts'));
    }
    public function index(Request $request)
    {
        $codes = 1;
        $code = Vouchers::where('v_type', 'Opening Balance')
        // ->where('warehouse_id', Auth::User()->warehouse_id)
        ->OrderBy('id', 'desc')->first();
        if ($code) {
            $codes = (int)$code->voucher_no + 1;
        }
        $usertype = Auth::User()->role;
        if($usertype == "Admin"){
            $warehouse = Warehouse::OrderBy('id')->pluck('name', 'id')->prepend('Select Warehouse', ''); 
        }else{
            $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)
            ->pluck('name', 'id');
        }

        // $Accounts = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))
        //     ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
        //     ->OrderBy('party_name', 'asc')
        //     ->pluck('party_name', 'id')
        //     ->prepend('Select Party/Account', '');


            $Accounts = Party::
        // select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))
            select(DB::raw(
            'CONCAT(`id`, "_", `party_name`, "_", `code`) AS `id`,
            CONCAT(`code`, "-", `party_name`, "-", `address`) AS `party_name`'
            ))
            ->whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party/Account', '');
        return view('opening-balance.index', compact('codes', 'Accounts', 'warehouse'));
    }


    public function edit($jvmawb)
    {
        $data = Vouchers::whereId($jvmawb)->first();
        $usertype = Auth::User()->role;
        if($usertype == "Admin"){
            $warehouse = Warehouse::OrderBy('id')->pluck('name', 'id')->prepend('Select Warehouse', ''); 
        }else{
            $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)
            ->pluck('name', 'id');
        }

        // $Accounts = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))
        //     ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
        //     ->OrderBy('party_name', 'asc')
        //     ->pluck('party_name', 'id')
        //     ->prepend('Select Party/Account', '');


            $Accounts = Party::
        // select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))
            select(DB::raw(
            'CONCAT(`id`, "_", `party_name`, "_", `code`) AS `id`,
            CONCAT(`code`, "-", `party_name`, "-", `address`) AS `party_name`'
            ))
            ->whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party/Account', '');
        return view('opening-balance.index', compact('data', 'Accounts', 'warehouse'));
    }

    public function Warehouse_voucherNo(Request $request){
        // return $request;
        $codes = 1;
        $code = Vouchers::where('v_type', 'Opening Balance')
        ->where('warehouse_id', $request->warehouseID)
        ->OrderBy('id', 'desc')->first();
        if ($code) {
            $codes = (int)$code->voucher_no + 1;
        }
        return Response::json(['codes' => $codes]);
    }

    public function store(Request $request)
    {
        // return $request;
        $Validator = Validator::make($request->all(), [
            'voucher_date' => 'required',
            'voucher_no' => 'required'
        ], [
            'voucher_date.required' => 'The Voucher Date field is required.',
            'voucher_no.required' => 'The Voucher No field is required.'
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator);
        }

        if (!isset($request->party_id)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }

        if (!isset($request->narration)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }


        if ($request->update_voucher_no != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'OPENING BALANCE')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }


            $voucherData = Vouchers::where('voucher_no', $request->update_voucher_no)
            ->where('warehouse_id', $request->warehouse_id)
                ->where('v_type', 'Opening Balance')
                ->first();

             $voucherData = Vouchers::find($voucherData->id);
            $voucherData->update($request->all());

             GeneralVoucher::where('voucher_id', $voucherData->id)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('v_type', 'Opening Balance')->delete();
            // LedgerDetailWise::where('voucher_id', $voucherData->id)->where('voucher_type', 'Opening Balance')->delete();
            // CashBook::where('jv_id', $voucherData->id)->delete();

            $count = count($request->debit);
            for ($i = 0; $i < $count; $i++) {
                $purchaseDetail = new GeneralVoucher();
                $purchaseDetail->voucher_id = $voucherData['id'];
                $purchaseDetail->account_head_id = $request->party_id[$i];
                $purchaseDetail->other_head_id = $request->party_id[$i];
                $purchaseDetail->date = $voucherData['voucher_date'];
                $purchaseDetail->voucher_no = $voucherData['voucher_no'];
                $purchaseDetail->v_type = $voucherData['v_type'];
                $purchaseDetail->warehouse_id =  $request->warehouse_id;
                $purchaseDetail->narration = $request->narration[$i];
                $purchaseDetail->debit = $request->debit[$i];
                $purchaseDetail->credit = $request->credit[$i];
                $purchaseDetail->save();
            }
            Session::flash('flash_message', 'Opening Balance Voucher Updated Successfully!');
            return redirect('opening-balance');
            // return redirect()->back()->with('flash_message', 'Opening Balance Voucher Updated Successfully!');
        } else {
            
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'OPENING BALANCE')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            $codes = 1;
            $code = Vouchers::where('v_type', 'Opening Balance')
            ->where('warehouse_id', $request->warehouse_id)
            ->OrderBy('id', 'desc')->first();
            if ($code) {
                $codes = (int)$code->voucher_no + 1;
            }
            $data=$request->all();
            $data['voucher_no']=$codes;

            $voucherData = Vouchers::create($request->all());
            $count = count($request->debit);
            for ($i = 0; $i < $count; $i++) {
                $purchaseDetail = new GeneralVoucher();
                $purchaseDetail->voucher_id = $voucherData['id'];
                $purchaseDetail->account_head_id = $request->party_id[$i];
                $purchaseDetail->other_head_id = $request->party_id[$i];
                $purchaseDetail->date = $voucherData['voucher_date'];
                $purchaseDetail->voucher_no = $voucherData['voucher_no'];
                $purchaseDetail->v_type = $voucherData['v_type'];
                $purchaseDetail->warehouse_id =  $request->warehouse_id;
                $purchaseDetail->narration = $request->narration[$i];
                $purchaseDetail->debit = $request->debit[$i];
                $purchaseDetail->credit = $request->credit[$i];
                $purchaseDetail->save();
            }
            Session::flash('flash_message', 'Opening Balance Voucher Added Successfully!');
            return redirect('opening-balance');
            // return redirect()->back()->with('flash_message', 'Opening Balance Voucher Added Successfully!');
        }
        abort(500);
    }

    public function editData(Request $request)
    {
         $edit = Vouchers::where('voucher_no', $request->voucher_no)
        ->where('warehouse_id', '=', $request->warehouseID)
            ->where('v_type', 'Opening Balance')
            ->first();

        if ($edit) {
            $edit = GeneralVoucher::with('parties')
                ->where('v_type', 'Opening Balance')
                ->where('voucher_no', $request->voucher_no)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->get();

            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        $journal_voucher = Vouchers::where('voucher_no', '<', $request->voucher_no)
        ->where('warehouse_id', '=', $request->warehouseID)
            ->where('v_type', 'Opening Balance')
            ->max('voucher_no');

        if ($journal_voucher) {
            $data = GeneralVoucher::with('parties')
                ->where('v_type', 'Opening Balance')
                ->where('voucher_no', $journal_voucher)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $journal_voucher = Vouchers::where('voucher_no', '>', $request->voucher_no)
        ->where('warehouse_id', '=', $request->warehouseID)
            ->where('v_type', 'Opening Balance')
            ->min('voucher_no');

        if ($journal_voucher) {
            $data = GeneralVoucher::with('parties')
                ->where('v_type', 'Opening Balance')
                ->where('voucher_no', $journal_voucher)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function DeleteVoucher(Request $request)
    {
        
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'OPENING BALANCE')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        
        $voucher = Vouchers::where('voucher_no', $request->delete_voucher_no)->where('v_type', 'Opening Balance')->first();
        if ($voucher) {
            // Vouchers::findOrFail($voucher->id)->delete();
            Vouchers::where('id', $voucher->id)->delete();
            GeneralVoucher::where('voucher_id', $voucher->id)->where('v_type', 'Opening Balance')->delete();
            // LedgerDetailWise::where('voucher_id', $voucher->id)->where('voucher_type', 'Opening Balance')->delete();
            // CashBook::where('jv_id', $voucher->id)->delete();
            Session::flash('flash_message', 'Opening Balance Voucher has been Deleted Successfully!');
            return redirect('opening-balance');
            // return redirect()->back()->with('flash_message', 'Opening Balance Voucher has been Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }
    public function PrintVoucher(Request $request)
    {
        File::cleanDirectory(base_path() . '/upload/opening-balance');
        $voucher_no = $request->voucher_no;
         $vr=Vouchers:: where('v_type', 'Opening Balance')
         ->where('warehouse_id', '=', $request->warehouseID)
         ->where('voucher_no', $voucher_no)->first();
         if($vr){

             $openbalance = Vouchers::with(['voucher_details' => function($query){
                $query->with('parties:id,code,party_name');
            }])->where('v_type', 'Opening Balance')
            ->with( 'warehouse:id,name', 'billers:id,name')
            ->where('warehouse_id', '=', $request->warehouseID)
            ->where('voucher_no', $voucher_no)
            ->get();
        // return $openbalance = GeneralVoucher::with('parties:id,code,party_name','voucher:id,voucher_no,voucher_date', 'warehouse:id,name')
        //     ->where('v_type', 'Opening Balance')
        //     ->where('warehouse_id', '=', $request->warehouseID)
        //     ->where('voucher_no', $voucher_no)
        //     ->get();

        $pdf = PDF::loadView('opening-balance.invoice', compact('openbalance'));
        $fileName =  'opening-balance' . $voucher_no . '.pdf';
        $pdf->save(base_path('upload/opening-balance/' . $fileName));
        return $fileName;
    }
}
}