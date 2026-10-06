<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\GeneralVoucher;
use App\Models\LedgerDetailWise;
use App\Models\Vouchers;
use App\Models\CashBook;
use App\Models\VoucherRights;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\File;

class JournalVoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $codes = 1;
        $code = Vouchers::where('v_type', 'Journal Voucher')
        ->where('warehouse_id', Auth::User()->warehouse_id)
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
            CONCAT(`code`, "-", `party_name`) AS `party_name`'
            ))
            ->whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party/Account', '');
            $Accountsbelow = Party::whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            // ->pluck('party_name', 'id')
            ->get(['id', 'party_name', 'code']);
        return view('journal-voucher.index', compact('codes', 'Accounts', 'warehouse', 'Accountsbelow'));
    }

    public function edit($jvmawb)
    {
        //  return $jvmawb;
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
            CONCAT(`code`, "-", `party_name`) AS `party_name`'
            ))
            ->whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party/Account', '');
            $Accountsbelow = Party::whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            // ->pluck('party_name', 'id')
            ->get(['id', 'party_name', 'code']);
        return view('journal-voucher.index', compact('data','Accounts', 'warehouse', 'Accountsbelow'));
    }

    public function store(Request $request)
    {
    //    return $request;
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
                ->where('voucher_name', 'JOURNAL VOUCHER')
                ->where('right_name', 'EDIT')
                ->count();
            if ($voucherRight == 0) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }


            $voucherData = Vouchers::where('voucher_no', $request->update_voucher_no)
            ->where('warehouse_id', $request->warehouse_id)
                ->where('v_type', 'Journal Voucher')
                ->first();

            $voucherData = Vouchers::find($voucherData->id);
            $voucherData->update($request->all());

            GeneralVoucher::where('voucher_id', $voucherData->id)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('v_type', 'Journal Voucher')->delete();
            // LedgerDetailWise::where('voucher_id', $voucherData->id)->where('voucher_type', 'Journal Voucher')->delete();
            // CashBook::where('jv_id', $voucherData->id)->delete();

            $count = count($request->debit);
            $totalDebit = 0;
            $totalCredit = 0;
            for ($i = 0; $i < $count; $i++) {
                $purchaseDetail = new GeneralVoucher();
                $purchaseDetail->voucher_id = $voucherData['id'];
                $purchaseDetail->account_head_id = $request->party_id[$i];
                // $purchaseDetail->other_head_id = $request->party_id[$i];
                $purchaseDetail->date = $voucherData['voucher_date'];
                $purchaseDetail->voucher_no = $voucherData['voucher_no'];
                $purchaseDetail->v_type = $voucherData['v_type'];
                $purchaseDetail->warehouse_id =  $request->warehouse_id;
                $purchaseDetail->narration = $request->narration[$i] ?? "";
                // $purchaseDetail->debit = $request->debit[$i] ?? 0;
                $purchaseDetail->debit = $request->debit[$i];
                // $purchaseDetail->credit = $request->credit[$i] ?? 0;
                $purchaseDetail->credit = $request->credit[$i];
                $purchaseDetail->save();
            }

            return redirect()->back()->with('flash_message', 'Journal Voucher Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'JOURNAL VOUCHER')
                ->where('right_name', 'ADD')
                ->count();
            if ($voucherRight == 0) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            // return $request;
            $codes = 1;
            $code = Vouchers::where('v_type', 'Journal Voucher')
            ->where('warehouse_id', $request->warehouse_id)
            ->OrderBy('id', 'desc')->first();
            if ($code) {
                $codes = (int)$code->voucher_no + 1;
            }
            $data=$request->all();
            $data['voucher_no']=$codes;
            
            $voucherData = Vouchers::create($data);
            $count = count($request->debit);
            for ($i = 0; $i < $count; $i++) {
                $purchaseDetail = new GeneralVoucher();
                $purchaseDetail->voucher_id = $voucherData['id'];
                $purchaseDetail->account_head_id = $request->party_id[$i];
                // $purchaseDetail->other_head_id = $request->party_id[$i];
                $purchaseDetail->date =$voucherData['voucher_date'];
                $purchaseDetail->voucher_no = $voucherData['voucher_no'];
                $purchaseDetail->v_type = $voucherData['v_type'];
                $purchaseDetail->warehouse_id =  $request->warehouse_id;
                 $purchaseDetail->narration = $request->narration[$i] ?? "";
                // $purchaseDetail->debit = $request->debit[$i] ?? 0;
                $purchaseDetail->debit = $request->debit[$i];
                $purchaseDetail->credit = $request->credit[$i];
                $purchaseDetail->save();
            }
            return redirect()->back()->with('flash_message', 'Journal Voucher Added Successfully!');
        }
        abort(500);
    }

    public function editData(Request $request)
    {
        $edit = Vouchers::where('voucher_no', $request->voucher_no)
        ->where('warehouse_id', '=', $request->warehouseID)
            ->where('v_type', 'Journal Voucher')
            ->first();

        if ($edit) {
            $edit = GeneralVoucher::with('parties')
                ->where('v_type', 'Journal Voucher')
                ->where('voucher_no', $request->voucher_no)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->get();

                $Accountsbelow = Party::whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->OrderBy('party_name', 'asc')
                // ->pluck('party_name', 'id')
                ->get(['id', 'party_name', 'code']);

            return Response::json(['data' => $edit, 'Accountsbelow' => $Accountsbelow]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function Warehouse_voucherNo(Request $request){
        // return $request;
        $codes = 1;
        $code = Vouchers::where('v_type', 'Journal Voucher')
        ->where('warehouse_id', $request->warehouseID)
        ->OrderBy('id', 'desc')->first();
        if ($code) {
            $codes = (int)$code->voucher_no + 1;
        }
        return Response::json(['codes' => $codes]);
    }

    public function LoadPreviousData(Request $request)
    {
        // return $request;
        $journal_voucher = Vouchers::where('voucher_no', '<', $request->voucher_no)
            ->where('warehouse_id', '=', $request->warehouseID)
            ->where('v_type', 'Journal Voucher')
            ->max('voucher_no');

        if ($journal_voucher) {
             $data = GeneralVoucher::with('parties')
                ->where('v_type', 'Journal Voucher')
                ->where('voucher_no', $journal_voucher)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->get();

                $Accountsbelow = Party::whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->OrderBy('party_name', 'asc')
                // ->pluck('party_name', 'id')
                ->get(['id', 'party_name', 'code']);

            return Response::json(['data' => $data, 'Accountsbelow' => $Accountsbelow]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $journal_voucher = Vouchers::where('voucher_no', '>', $request->voucher_no)
        ->where('warehouse_id', '=', $request->warehouseID)
            ->where('v_type', 'Journal Voucher')
            ->min('voucher_no');

        if ($journal_voucher) {
            $data = GeneralVoucher::with('parties')
                ->where('v_type', 'Journal Voucher')
                ->where('voucher_no', $journal_voucher)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->get();

                $Accountsbelow = Party::whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->OrderBy('party_name', 'asc')
                // ->pluck('party_name', 'id')
                ->get(['id', 'party_name', 'code']);

            return Response::json(['data' => $data, 'Accountsbelow' => $Accountsbelow]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function DeleteVoucher(Request $request)
    {
        // return $request;
         $voucher = Vouchers::where('voucher_no', $request->delete_voucher_no)
        ->where('warehouse_id', '=', $request->delete_warehouseID)
        ->where('v_type', 'Journal Voucher')->first();
        if ($voucher) {
            Vouchers::where('id', $voucher->id)->delete();
            GeneralVoucher::where('voucher_id', $voucher->id)
            ->where('warehouse_id', '=', $request->delete_warehouseID)
            ->where('v_type', 'Journal Voucher')->delete();
            return redirect()->back()->with('flash_message', 'General Voucher has been Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }
    public function PrintVoucher(Request $request)
    {
        File::cleanDirectory(base_path() . '/upload/journal-voucher');


        $voucher_no = $request->voucher_no;
        $vouchers = Vouchers::where('voucher_no', $voucher_no)
        ->where('warehouse_id', '=', $request->warehouseID)
        ->where('v_type', 'Journal Voucher')->first();
        if($vouchers){
        // $generalVoucher = GeneralVoucher::with(['voucher' => function ($query) {
        //     $query->with('parties:id,party_name,address');
        // }])
        //     ->with('parties:id,party_name,code')
        //     ->where('v_type', 'Journal Voucher')
        //     ->where('voucher_no', $voucher_no)
        //     ->where('warehouse_id', '=', $request->warehouseID)
        //     ->get();


            $generalVoucher = Vouchers::with(['voucher_details' => function($query){
                // $query->where('debit', '!=', 0);
                $query->where('v_type', '=', "Journal Voucher");
            }])
                ->with('parties:id,code,party_name', 'warehouse:id,name', 'billers:id,name')
                ->where('v_type', 'Journal Voucher')
                // ->where('warehouse_id', Auth::User()->warehouse_id)
                ->where('id', $vouchers->id)
                // ->where('voucher_no', $vouchers->voucher_no)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->get();

        $pdf = PDF::loadView('journal-voucher.invoice', compact('generalVoucher'));
        $fileName =  'Journal-Voucher' . $voucher_no . '.pdf';
        $pdf->save(base_path('upload/journal-voucher/' . $fileName));
        return $fileName;
    }else{
        return false;
    }
    }
}