<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\CashBook;
use App\Models\Vouchers;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\GeneralVoucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Session;
class AdvanceSalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'ADVANCE SALARY')
        ->where('right_name', 'ADD')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }

        $codes = 1;
        $code = Vouchers::where('v_type', 'Advance')
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
        $cashAccount = Party::where('account_group_id3','1')->pluck('party_name', 'id')->prepend('Select Account', '');
             $Accounts = Party::
            select(DB::raw(
                'CONCAT(`id`, "_", `party_name`, "_", `code`) AS `id`,
                CONCAT(`code`, "-", `party_name`) AS `party_name`'
                ))
                ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->where('account_type', "!=", 'AGENT')
                ->where('role','Employee')
                ->OrderBy('party_name', 'asc')
                ->pluck('party_name', 'id')
                ->prepend('Select Party/Account', '');

            $Accountsbelow = Party::whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            // ->pluck('party_name', 'id')
            ->get(['id', 'party_name', 'code']);

        return view('advance-salary.index', compact('cashAccount', 'Accounts', 'codes', 'warehouse', 'Accountsbelow'));
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
        $cashAccount = Party::where('account_group_id3','1')->pluck('party_name', 'id')->prepend('Select Account', '');
             $Accounts = Party::
            select(DB::raw(
                'CONCAT(`id`, "_", `party_name`, "_", `code`) AS `id`,
                CONCAT(`code`, "-", `party_name`) AS `party_name`'
                ))
                ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->where('account_type', "!=", 'AGENT')
                ->where('role','Employee')
                ->OrderBy('party_name', 'asc')
                ->pluck('party_name', 'id')
                ->prepend('Select Party/Account', '');

                $Accountsbelow = Party::whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            // ->pluck('party_name', 'id')
            ->get(['id', 'party_name', 'code']);

        return view('advance-salary.index', compact('cashAccount', 'Accounts', 'data', 'warehouse', 'Accountsbelow'));
    }

    public function Warehouse_voucherNo(Request $request){
        // return $request;
        $codes = 1;
        $code = Vouchers::where('v_type', 'Advance')
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
            'voucher_no' => 'required',
            'account_id' => 'required'
        ], [
            'voucher_date.required' => 'The Voucher Date field is required.',
            'voucher_no.required' => 'The Voucher No field is required.',
            'account_id.required' => 'The Cash Account field is required.'
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator);
        }

        if (!isset($request->narration)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }
        if (!isset($request->amount)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }

        if ($request->update_voucher_no != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'ADVANCE SALARY')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            $data = $request->all();
            $data['account_id'] = explode('_', $request->account_id)[0];
            $data['warehouse_id'] = $request->warehouse_id;

            $voucherData = Vouchers::where('voucher_no', $request->update_voucher_no)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('v_type', 'Advance')->first();
            $voucherData = Vouchers::find($voucherData->id);
            $voucherData->update($data);

            GeneralVoucher::where('voucher_no', $request->update_voucher_no)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('v_type', 'Advance')->delete();
            // CashBook::where('vr_no', $request->update_voucher_no)->where('vr_type', 'Cash Payment')->delete();

            $totalDebit = 0;
            $count = count($request->narration);
            for ($i = 0; $i < $count; $i++) {
                // Top account | Debit account | Cash receive account
                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $voucherData['id'];
                $generalVoucher->account_head_id = $voucherData['account_id'];
                $generalVoucher->other_head_id = $request->party_id[$i];
                $generalVoucher->date = $voucherData['voucher_date'];
                $generalVoucher->voucher_no = $voucherData['voucher_no'];
                $generalVoucher->v_type =  $request->v_type;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->narration = $request->narration[$i];
                $generalVoucher->credit = $request->amount[$i];
                $generalVoucher->save();
                // Below account | Credit account | Cash paid account
                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $voucherData['id'];
                $generalVoucher->account_head_id = $request->party_id[$i];
                $generalVoucher->other_head_id = $voucherData['account_id'];
                $generalVoucher->date = $voucherData['voucher_date'];
                $generalVoucher->voucher_no = $voucherData['voucher_no'];
                $generalVoucher->v_type = $request->v_type;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->narration = $request->narration[$i];
                $generalVoucher->debit = $request->amount[$i];
                $generalVoucher->save();
            }
            // $voucherData->total_debit = $totalDebit;
            // $voucherData->save();
            Session::flash('flash_message', 'Voucher Updated Successfully!');
            return redirect('advance-salary');
            return redirect()->back()->with('flash_message', 'Voucher Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'ADVANCE SALARY')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            $codes = 1;
            $code = Vouchers::where('v_type', 'Advance')
            ->where('warehouse_id', $request->warehouse_id)
            ->OrderBy('id', 'desc')->first();
            if ($code) {
                $codes = (int)$code->voucher_no + 1;
            }
            $data = $request->all();
            $data['voucher_no']=$codes;
            $data['account_id'] = explode('_', $request->account_id)[0];
            $data['warehouse_id'] = $request->warehouse_id;
            $voucherData = Vouchers::create($data);

            $totalDebit = 0;
            $count = count($request->narration);
            for ($i = 0; $i < $count; $i++) {
                // Top account | Debit account | Cash receive account
                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $voucherData['id'];
                $generalVoucher->account_head_id = $voucherData['account_id'];
                $generalVoucher->other_head_id = $request->party_id[$i];
                $generalVoucher->date = $voucherData['voucher_date'];
                $generalVoucher->voucher_no = $voucherData['voucher_no'];
                $generalVoucher->v_type =  $request->v_type;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->narration = $request->narration[$i];
                $generalVoucher->credit = $request->amount[$i];
                $generalVoucher->save();
                // Below account | Credit account | Cash paid account
                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $voucherData['id'];
                $generalVoucher->account_head_id = $request->party_id[$i];
                $generalVoucher->other_head_id = $voucherData['account_id'];
                $generalVoucher->date = $voucherData['voucher_date'];
                $generalVoucher->voucher_no = $voucherData['voucher_no'];
                $generalVoucher->v_type = $request->v_type;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->narration = $request->narration[$i];
                $generalVoucher->debit = $request->amount[$i];
                $generalVoucher->save();
            }
            // $voucherData->total_debit = $totalDebit;
            // $voucherData->save();
            Session::flash('flash_message', 'Voucher Added Successfully!');
            return redirect('advance-salary');
            return redirect()->back()->with('flash_message', 'Voucher Added Successfully!');
        }
    }

    public function editData(Request $request)
    {
        $edit = Vouchers::where('warehouse_id', '=', $request->warehouseID)
        ->where('voucher_no', $request->voucher_no)
            ->where('v_type', 'Advance')
            ->first();

        if ($edit) {
            $edit = GeneralVoucher::with('parties')
                ->where('v_type', 'Advance')
                ->where('voucher_no', $request->voucher_no)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->where('debit', '!=', 0)
                ->get();

                $Accountsbelow = Party::whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->OrderBy('party_name', 'asc')
                // ->pluck('party_name', 'id')
                ->get(['id', 'party_name', 'code']);

            return Response::json(['data' => $edit, 'Accountsbelow' => $Accountsbelow]);

            // return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $CashReceipt = Vouchers::where('warehouse_id', '=', $request->warehouseID)
        ->where('voucher_no', '>', $request->voucher_no)
            ->where('v_type', 'Advance')
            ->min('voucher_no');

        if ($CashReceipt) {
            $data = GeneralVoucher::with('parties')
                ->where('v_type', 'Advance')
                ->where('voucher_no', $CashReceipt)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->where('debit', '!=', 0)
                ->get();

                $Accountsbelow = Party::whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->OrderBy('party_name', 'asc')
                // ->pluck('party_name', 'id')
                ->get(['id', 'party_name', 'code']);

            return Response::json(['data' => $data, 'Accountsbelow' => $Accountsbelow]);

            // return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        $CashReceipt = Vouchers::where('warehouse_id', '=', $request->warehouseID)
        ->where('voucher_no', '<', $request->voucher_no)
            ->where('v_type', 'Advance')
            ->max('voucher_no');

        if ($CashReceipt) {
        return    $data = GeneralVoucher::with('parties')
                ->where('v_type', 'Advance')
                ->where('voucher_no', $CashReceipt)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->where('debit', '!=', 0)
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
        //  return $request;

        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'ADVANCE SALARY')
        ->where('right_name', 'DELETE')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }
        $voucher = Vouchers::where('warehouse_id', '=', $request->delete_warehouseID)
        ->where('voucher_no', $request->delete_voucher_no)
        ->where('v_type', 'Advance')->first();
        if ($voucher) {
            Vouchers::where('id', $voucher->id)->delete();
            GeneralVoucher::where('warehouse_id', '=', $request->delete_warehouseID)
            ->where('voucher_id', $voucher->id)->where('v_type', 'Advance')->delete();
            Session::flash('flash_message', 'Advance Salary has been Deleted Successfully!');
            return redirect('advance-salary');
            return redirect()->back()->with('flash_message', 'Advance  Salary has been Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }
    public function PrintVoucher(Request $request)
    {
        // return $request;
        File::cleanDirectory(base_path() . '/upload/advance-salary');

        $voucher_no = $request->voucher_no;
        $vouchers = Vouchers::where('warehouse_id', '=', $request->warehouseID)
        ->where('voucher_no', $voucher_no)
        ->where('v_type', 'Advance')->first();
        if($vouchers){
        // $generalVoucher = GeneralVoucher::with(['voucher' => function ($query) {
        //     $query->with('parties:id,party_name,address', 'billers:id,name');
        // }])
        //     ->with('parties:id,party_name,code')
        //     ->where('v_type', 'Cash Payment')
        //     ->where('voucher_no', $voucher->voucher_no)
        //     ->where('warehouse_id', '=', $request->warehouseID)
        //     ->where('debit', '!=', 0)
        //     ->get();

        $generalVoucher = Vouchers::with(['voucher_details' => function($query){
            $query->where('debit', '!=', 0);
            $query->where('v_type', '=', "Advance");
        }])
            ->with('parties:id,code,party_name', 'warehouse:id,name', 'billers:id,name')
            ->where('v_type', 'Advance')
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->where('id', $vouchers->id)
            // ->where('voucher_no', $vouchers->voucher_no)
            ->where('warehouse_id', '=', $request->warehouseID)
            ->get();

        $pdf = PDF::loadView('advance-salary.invoice', compact('generalVoucher'));
        $fileName =  'Advance-Salary-' . $voucher_no . '.pdf';
        $pdf->save(base_path('upload/advance-salary/' . $fileName));
        return $fileName;
    }else{
        return false;
    }
    }
}