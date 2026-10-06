<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\GeneralVoucher;
use App\Models\LedgerDetailWise;
use App\Models\Banks;
use App\Models\VoucherRights;
use App\Models\Vouchers;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\File;


class BankReceiptController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $codes = 1;
        $code = Vouchers::where('v_type', 'Bank Receipt')
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
        // $banks = Party::where('account_type', 'BANK')
        //     ->OrderBy('party_name', 'asc')
        //     ->pluck('party_name', 'id')
        //     ->prepend('Select Bank Account Name', '');
        $banks = Party::where('account_group_id3','2')->pluck('party_name', 'id')
            ->prepend('Select Bank Account Name', '');
            
        // $Accounts = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))
        //     ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
        //     // ->where('account_type', 'BANK')
        //     ->OrderBy('party_name', 'asc')
        //     ->pluck('party_name', 'id')
        //     ->prepend('Select Party/Account', '');

            $Accounts = Party::
        // select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))
        select(DB::raw(
            'CONCAT(`id`, "_", `party_name`, "_", `code`) AS `id`,
            CONCAT(`code`, "-", `party_name`) AS `party_name`'
            ))
            ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party/Account', '');

        return view('bank-receipts.index', compact('codes', 'Accounts', 'banks', 'warehouse'));
    }

    public function Warehouse_voucherNo(Request $request){
        // return $request;
        $codes = 1;
        $code = Vouchers::where('v_type', 'Bank Receipt')
        ->where('warehouse_id', $request->warehouseID)
        ->OrderBy('id', 'desc')->first();
        if ($code) {
            $codes = (int)$code->voucher_no + 1;
        }
        return Response::json(['codes' => $codes]);
    }

    public function store(Request $request)
    {
        //  return $request;
        $Validator = Validator::make($request->all(), [
            'voucher_date' => 'required',
            'voucher_no' => 'required',
            'account_id' => 'required'
        ], [
            'voucher_date.required' => 'The Voucher Date field is required.',
            'voucher_no.required' => 'The Voucher No field is required.',
            'account_id.required' => 'The Bank Account Name field is required.'
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
                ->where('voucher_name', 'BANK RECEIPT VOUCHER')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            $data = $request->all();
            // $data['account_id'] = explode('_', $request->account_id)[0];
            // return $data['account_id'] = $request->account_id;
            $data['warehouse_id'] = $request->warehouse_id;

            $voucherData = Vouchers::where('voucher_no', $request->update_voucher_no)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('v_type', 'Bank Receipt')
            ->first();

            $voucherData = Vouchers::find($voucherData->id);
            $voucherData->update($request->all());

            GeneralVoucher::where('voucher_no', $request->update_voucher_no)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('v_type', 'Bank Receipt')->delete();
            // LedgerDetailWise::where('voucher_no', $request->update_voucher_no)->where('v_type', 'Bank Receipt')->delete();
            
            $totalCredit = 0;
            $count = count($request->amount);
            for ($i = 0; $i < $count; $i++) {
                // Top account | Debit account | Bank receive account

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $voucherData['id'];
                $generalVoucher->account_head_id = $voucherData['account_id'];
                $generalVoucher->other_head_id = $request->party_id[$i];
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->date = $voucherData['voucher_date'];
                $generalVoucher->voucher_no = $voucherData['voucher_no'];
                $generalVoucher->cheque_no = $request->cheque_no[$i];
                $generalVoucher->cheque_date = $request->cheque_date[$i];
                $generalVoucher->v_type = $request->v_type;
                $generalVoucher->narration = $request->narration[$i];
                $generalVoucher->bank_id = $voucherData['account_id'];
                $generalVoucher->debit = $request->amount[$i];
                $generalVoucher->save();

                // Below account | Credit account | Bank paid account
                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $voucherData['id'];
                $generalVoucher->account_head_id = $request->party_id[$i];
                $generalVoucher->other_head_id = $voucherData['account_id'];
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->date = $voucherData['voucher_date'];
                $generalVoucher->voucher_no = $voucherData['voucher_no'];
                $generalVoucher->cheque_no = $request->cheque_no[$i];
                $generalVoucher->cheque_date = $request->cheque_date[$i];
                $generalVoucher->v_type = $request->v_type;
                $generalVoucher->narration = $request->narration[$i];
                $generalVoucher->bank_id = $voucherData['account_id'];
                $generalVoucher->credit = $request->amount[$i];
                $generalVoucher->save();

              

                // $totalCredit += $request->amount[$i];
            }

            // $voucherData->total_credit += $totalCredit;
            // $voucherData->save();

            return redirect()->back()->with('flash_message', 'Voucher Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'BANK RECEIPT VOUCHER')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            $codes = 1;
           $code = Vouchers::where('v_type', 'Bank Receipt')
           ->where('warehouse_id', $request->warehouse_id)
           ->OrderBy('id', 'desc')->first();
          if ($code) {
            $codes = (int)$code->voucher_no + 1;
        }
             $data=$request->all();
             $data['voucher_no']=$codes;
            //  $data['account_id'] = explode('_', $request->account_id)[0];
            $data['warehouse_id'] = $request->warehouse_id;
            $voucherData = Vouchers::create($data);
            // return "ui";
            $totalCredit = 0;
            $count = count($request->amount);
            for ($i = 0; $i < $count; $i++) {
                // Top account | Debit account | Bank receive account

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $voucherData['id'];
                $generalVoucher->account_head_id = $voucherData['account_id'];
                $generalVoucher->other_head_id = $request->party_id[$i];
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->date = $voucherData['voucher_date'];
                $generalVoucher->voucher_no = $voucherData['voucher_no'];
                $generalVoucher->cheque_no = $request->cheque_no[$i];
                $generalVoucher->cheque_date = $request->cheque_date[$i];
                $generalVoucher->v_type = $request->v_type;
                $generalVoucher->narration = $request->narration[$i];
                $generalVoucher->bank_id = $voucherData['account_id'];
                $generalVoucher->debit = $request->amount[$i];
                $generalVoucher->save();


                // Below account | Credit account | Bank paid account
                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $voucherData['id'];
                $generalVoucher->account_head_id = $request->party_id[$i];
                $generalVoucher->other_head_id = $voucherData['account_id'];
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->date = $voucherData['voucher_date'];
                $generalVoucher->voucher_no = $voucherData['voucher_no'];
                $generalVoucher->cheque_no = $request->cheque_no[$i];
                $generalVoucher->cheque_date = $request->cheque_date[$i];
                $generalVoucher->v_type = $request->v_type;
                $generalVoucher->narration = $request->narration[$i];
                $generalVoucher->bank_id = $voucherData['account_id'];
                $generalVoucher->credit = $request->amount[$i];
                $generalVoucher->save();


                // $totalCredit += $request->amount[$i];
            }

            // $voucherData->total_credit += $totalCredit;
            // $voucherData->save();


            return redirect()->back()->with('flash_message', 'Voucher Added Successfully!');
        }
        abort(500);
    }

    public function editData(Request $request)
    {
        $edit = Vouchers::where('warehouse_id', '=', $request->warehouseID)
            ->where('voucher_no', $request->voucher_no)
            ->where('v_type', 'Bank Receipt')
            ->first();

        if ($edit) {
            $edit = GeneralVoucher::with('parties')
                ->where('v_type', 'Bank Receipt')
                ->where('credit','!=',0)
                ->where('voucher_no', $request->voucher_no)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->get();

            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $BankReceipt = Vouchers::where('warehouse_id', '=', $request->warehouseID)
            ->where('voucher_no', '>', $request->voucher_no)
            ->where('v_type', 'Bank Receipt')
            ->min('voucher_no');

        if ($BankReceipt) {
            $data = GeneralVoucher::with('parties')
                ->where('v_type', 'Bank Receipt')
                ->where('credit','!=',0)
                ->where('voucher_no', $BankReceipt)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        $BankReceipt = Vouchers::where('warehouse_id', '=', $request->warehouseID)
            ->where('voucher_no', '<', $request->voucher_no)
            ->where('v_type', 'Bank Receipt')
            ->max('voucher_no');

        if ($BankReceipt) {
            $data = GeneralVoucher::with('parties')
                ->where('v_type', 'Bank Receipt')
                ->where('credit','!=',0)
                ->where('voucher_no', $BankReceipt)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function DeleteVoucher(Request $request)
    {
        $voucher = Vouchers::where('voucher_no', $request->delete_voucher_no)
        ->where('warehouse_id', '=', $request->delete_warehouseID)
        ->where('v_type', 'Bank Receipt')->first();
        if ($voucher) {
            Vouchers::where('warehouse_id', '=', $request->delete_warehouseID)
            ->where('voucher_no', $request->delete_voucher_no)->where('v_type', 'Bank Receipt')->delete();
            GeneralVoucher::where('voucher_id', $voucher->id)
            ->where('warehouse_id', '=', $request->delete_warehouseID)
            ->where('v_type', 'Bank Receipt')->delete();
            LedgerDetailWise::where('voucher_id', $voucher->id)
            ->where('warehouse_id', '=', $request->delete_warehouseID)
            ->where('voucher_type', 'Bank Receipt')->delete();
            return redirect()->back()->with('flash_message', 'Bank Receipt Voucher has been Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }
    public function PrintVoucher(Request $request)
    {
        File::cleanDirectory(base_path() . '/upload/bank-receipt');
        $voucher_no = $request->voucher_no;
        $vouchers = Vouchers::where('voucher_no', $voucher_no)
        ->where('warehouse_id', '=', $request->warehouseID)
        ->where('v_type', 'Bank Receipt')->first();
        if ($vouchers) {
        // $generalVoucher = GeneralVoucher::with(['voucher' => function ($query) {
        //     $query->with('parties:id,party_name,address');
        // }])
        //     ->with('parties:id,party_name,code')
        //     ->where('v_type', 'Bank Receipt')
        //     ->where('voucher_no', $voucher->voucher_no)
        //     ->where('warehouse_id', '=', $request->warehouseID)
        //     ->where('credit', '!=', 0)
        //     ->get();


            $generalVoucher = Vouchers::with(['voucher_details' => function($query){
                $query->where('credit', '!=', 0);
                $query->where('v_type', '=', "Bank Receipt");
            }])
                ->with('parties:id,code,party_name', 'warehouse:id,name', 'billers:id,name')
                ->where('v_type', 'Bank Receipt')
                // ->where('warehouse_id', Auth::User()->warehouse_id)
                ->where('id', $vouchers->id)
                // ->where('voucher_no', $vouchers->voucher_no)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->get();

        $pdf = PDF::loadView('bank-receipts.invoice', compact('generalVoucher'));
        $fileName =  'Bank-Receipt-Voucher' . $voucher_no . '.pdf';
        $pdf->save(base_path('upload/bank-receipt/' . $fileName));
        return $fileName;
    }else{
        return false;
    }
}
}