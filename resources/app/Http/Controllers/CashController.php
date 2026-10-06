<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\CashReceipt;
use App\Models\Vouchers;
use App\Models\Setting;
use App\Models\GeneralVoucher;
use App\Models\LedgerDetailWise;
use App\Models\SystemLogo;
use App\Models\CashBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CashController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $Heads = Party::OrderBy('party_name', 'asc')->pluck('party_name', 'id')->toArray();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        $Vouchers = Vouchers::with('voucher_details')
            ->with('parties')
            ->where('vouchers.v_type', 'Cash Voucher')
            ->orderBy('id', 'desc')
            ->get();

        return view('cash.index', compact('encrypted_token', 'Vouchers', 'Heads'));
    }

    public function create()
    {
        $codes = 0;
        $code = Vouchers::where('v_type', 'Cash Voucher')->OrderBy('id', 'desc')->first();
        if ($code) {
            $codes = (int)$code->voucher_no + 1;
        } else {
            $codes = 1;
        }

        $cashAccount = Party::where('id', 1)->pluck('party_name', 'id');

        $Accounts = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))
            ->OrderBy('party_name', 'asc')
            ->where('id', '!=', 1)
            ->where('party_name', '!=', 'PURCHASE ACCOUNT')
            ->where('party_name', '!=', 'SALE ACCOUNT')
            ->pluck('party_name', 'id')
            ->toArray();

        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());

        return view('cash.create', compact('encrypted_token', 'cashAccount', 'Accounts', 'codes'));
    }

    public function store(Request $request)
    {
        // return explode('_',$request->account_id)[0];
        $voucherData = Vouchers::create($request->all());

        $count = count($request->narration);
        for ($i = 0; $i < $count; $i++) {
            // Top account | Debit account | Cash receive account
            $purchaseDetail = new GeneralVoucher();
            $purchaseDetail->voucher_id = $voucherData->id;
            $purchaseDetail->account_head_id = $voucherData['account_id'];
            $purchaseDetail->date = $request->date[$i];
            $purchaseDetail->voucher_no = $voucherData->voucher_no;
            $purchaseDetail->v_type =  $request->v_type;
            $purchaseDetail->narration = $request->narration[$i];
            $purchaseDetail->debit = $request->debit[$i];
            $purchaseDetail->credit = $request->credit[$i];
            $purchaseDetail->save();
            // Below account | Credit account | Cash paid account
            $purchaseDetail = new GeneralVoucher();
            $purchaseDetail->voucher_id = $voucherData->id;
            $purchaseDetail->account_head_id = $request->head_id[$i];
            $purchaseDetail->date = $request->date[$i];
            $purchaseDetail->voucher_no = $voucherData->voucher_no;
            $purchaseDetail->v_type = $request->v_type;
            $purchaseDetail->narration = $request->narration[$i];
            $purchaseDetail->debit = $request->debit[$i];
            $purchaseDetail->credit = $request->credit[$i];
            $purchaseDetail->save();

            // Top account | Debit account | Cash receive account
            $vouchers = new LedgerDetailWise();
            $vouchers->voucher_id = $voucherData->id;
            $vouchers->party_id = $voucherData->account_id;
            $vouchers->voucher_no = $voucherData->voucher_no;
            $vouchers->voucher_type = "Cash Voucher";
            $vouchers->date = $request->date[$i];
            $vouchers->other = $request->narration[$i];
            $vouchers->debit = $request->debit[$i];
            $vouchers->credit = $request->credit[$i];
            $vouchers->save();
            // Below account | Credit account | Cash paid account
            $vouchers = new LedgerDetailWise();
            $vouchers->voucher_id = $voucherData->id;
            $vouchers->party_id = $request->head_id[$i];
            $vouchers->voucher_no = $voucherData->voucher_no;
            $vouchers->voucher_type = "Cash Voucher";
            $vouchers->date = $request->date[$i];
            $vouchers->other = $request->narration[$i];
            $vouchers->debit = $request->debit[$i];
            $vouchers->credit = $request->credit[$i];
            $vouchers->save();

            $cash = new CashBook();
            $cash->date = $request->date[$i];
            $cash->vr_no =  $voucherData->voucher_no;
            $cash->vr_type = $request->v_type;
            $cash->biller_id = Auth::User()->id;
            $cash->shop_id = $voucherData->shop_id;
            $cash->party_id = $request->head_id[$i];
            $cash->cash_receipt_id = $voucherData->id;
            $cash->in = $request->credit[$i];
            $cash->out = $request->debit[$i];
            $cash->description = $request->narration[$i];
            $cash->save();
        }
        Session::flash('flash_message', 'Record Successfully Added!');
        return redirect('cash/create');
    }

    public function report(Request $request)
    {
        $HeadID = $request->get('head_id');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $GeneralVoucher = CashReceipt::join('parties', 'parties.id', '=', 'cash_receipts.account_head_id')->where('cash_receipts.account_head_id', '=', $HeadID)
            ->whereBetween('date', [$fromDate, $toDate])
            ->OrderBy('cash_receipts.id')->get();

        $company_detail = Setting::where('id', '=', 1)->get();
        return view('cash.report', compact('GeneralVoucher', 'company_detail'));
    }

    public function show($id)
    {
        $newsale_detail = Vouchers::with(['voucher_details' => function ($query) {
            $query->with('parties');
        }])->with('parties')->where('vouchers.id', '=', $id)
            ->get();
        $company_detail = Setting::where('id', '=', 1)->get();
        $logo = SystemLogo::where('id', '=', 1)->get();
        return view('cash.print', compact('newsale_detail', 'company_detail', 'logo'));
    }

    public function edit($id)
    {
        $purchase = Vouchers::with(['voucher_details' => function ($query) {
            $query->with('parties');
        }])->with('parties')->where('vouchers.id', '=', $id)
            ->get();
        $edit = $purchase[0];

        $codes = 0;

        $cashAccount = Party::where('id', 1)->pluck('party_name', 'id');

        $Accounts = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))
            ->OrderBy('party_name', 'asc')
            ->where('id', '!=', 1)
            ->where('party_name', '!=', 'PURCHASE ACCOUNT')
            ->where('party_name', '!=', 'SALE ACCOUNT')
            ->pluck('party_name', 'id')
            ->toArray();

        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('cash.edit', compact('edit', 'codes', 'cashAccount', 'Accounts', 'encrypted_token'));
    }

    public function update(Request $request, $id)
    {
        // return $request->all();
        $voucherData = Vouchers::findOrFail($id);
        $voucherData->account_id = $request->account_id;
        $voucherData->voucher_date = date('Y-m-d', strtotime($request->voucher_date));
        $voucherData->voucher_no = $request->voucher_no;
        $voucherData->save();
        GeneralVoucher::where('voucher_id', '=', $id)->delete();
        LedgerDetailWise::where('voucher_id', '=', $id)->delete();
        CashBook::where('cash_receipt_id', $id)->delete();
        // return 1;
        $count = count($request->narration);
        for ($i = 0; $i < $count; $i++) {
            // Top account | Debit account | Cash receive account
            $purchaseDetail = new GeneralVoucher();
            $purchaseDetail->voucher_id = $voucherData->id;
            $purchaseDetail->account_head_id = $voucherData['account_id'];
            $purchaseDetail->date = $request->date1[$i];
            $purchaseDetail->voucher_no = $voucherData->voucher_no;
            $purchaseDetail->v_type =  $request->v_type;
            $purchaseDetail->narration = $request->narration[$i];
            $purchaseDetail->debit = $request->debit[$i];
            $purchaseDetail->credit = $request->credit[$i];
            $purchaseDetail->save();
            // Below account | Credit account | Cash paid account
            $purchaseDetail = new GeneralVoucher();
            $purchaseDetail->voucher_id = $voucherData->id;
            $purchaseDetail->account_head_id = $request->head_id[$i];
            $purchaseDetail->date = $request->date1[$i];
            $purchaseDetail->voucher_no = $voucherData->voucher_no;
            $purchaseDetail->v_type = $request->v_type;
            $purchaseDetail->narration = $request->narration[$i];
            $purchaseDetail->debit = $request->debit[$i];
            $purchaseDetail->credit = $request->credit[$i];
            $purchaseDetail->save();

            // Top account | Debit account | Cash receive account
            $vouchers = new LedgerDetailWise();
            $vouchers->voucher_id = $voucherData->id;
            $vouchers->party_id = $voucherData->account_id;
            $vouchers->voucher_no = $voucherData->voucher_no;
            $vouchers->voucher_type = "Cash Voucher";
            $vouchers->date = $request->date1[$i];
            $vouchers->other = $request->narration[$i];
            $vouchers->debit = $request->debit[$i];
            $vouchers->credit = $request->credit[$i];
            $vouchers->save();
            // Below account | Credit account | Cash paid account
            $vouchers = new LedgerDetailWise();
            $vouchers->voucher_id = $voucherData->id;
            $vouchers->party_id = $request->head_id[$i];
            $vouchers->voucher_no = $voucherData->voucher_no;
            $vouchers->voucher_type = "Cash Voucher";
            $vouchers->date = $request->date1[$i];
            $vouchers->other = $request->narration[$i];
            $vouchers->debit = $request->debit[$i];
            $vouchers->credit = $request->credit[$i];
            $vouchers->save();

            $cash = new CashBook();
            $cash->date = $request->date1[$i];
            $cash->vr_no =  $voucherData->voucher_no;
            $cash->vr_type = $request->v_type;
            $cash->biller_id = Auth::User()->id;
            $cash->shop_id = $voucherData->shop_id;
            $cash->party_id = $request->head_id[$i];
            $cash->cash_receipt_id = $voucherData->id;
            $cash->in = $request->credit[$i];
            $cash->out = $request->debit[$i];
            $cash->description = $request->narration[$i];
            $cash->save();
        }
        Session::flash('flash_message', 'Record Successfully Updated!');
        return redirect('cash');
    }

    public function destroy($id)
    {
        $delete = Vouchers::findOrFail($id);
        $delete->delete();
        GeneralVoucher::where('voucher_id', '=', $id)->delete();
        LedgerDetailWise::where('voucher_id', '=', $id)->delete();
        CashBook::where('cash_receipt_id', $id)->delete();

        return "Cash Voucher has been Deleted Successfully!";
    }
}
