<?php

namespace App\Http\Controllers;

use App\Models\PostDateCheque;
use App\Models\Vouchers;
use App\Models\Party;
use App\Models\PostDated;
use App\Models\GeneralVoucher;
use App\Models\LedgerDetailWise;
use DB;
use Illuminate\Http\Request;

class ChequeTransferController extends Controller
{
    public function index()
    {
        //return "hell";
        $Heads = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))->OrderBy('party_name', 'asc')->pluck('party_name', 'id')->toArray();
        $cheques = PostDateCheque::OrderBy('id')->get();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('cheque-transfer.index', Compact('cheques', 'encrypted_token', 'Heads'));
    }

    public function create()
    {

        $code = Vouchers::OrderBy('id', 'asc')->get();
        $codes = $code->last()->voucher_no + 1;

        $Heads = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))->OrderBy('party_name', 'asc')->pluck('party_name', 'id')->toArray();

        $encrypter = app('Illuminate\Encryption\Encrypter');
        // $cheques = PostDated::with(['cheque_details'=> function($query){
        //     $query->with('party');
        //     $query->with('banks');
        // }])->get();
        $cheques = PostDateCheque::with('post_dated')->get();

        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('cheque-transfer.create', Compact('encrypted_token', 'codes', 'Heads', 'cheques'));
    }

    public function store(Request $request)
    {
        $voucher = json_decode($request->get('voucher'), true);
        //return $voucher;
        $voucherData = Vouchers::create($voucher);
        $products = $request->get('product_data');

        // if($voucherData->voucher_no !="0"){
        //     $project = PostDateCheque::where("voucher_no", $voucherData->voucher_no)->first();
        //     $project->status = "Clear";
        //     $project->save();
        // }
        //return $products;
        foreach ($products as $product) {
            if (($product['checkedValue']) == "true") {
                //return "entered";
                $purchaseDetail = new GeneralVoucher();
                $purchaseDetail->voucher_id = $voucherData['voucher_id'];
                $purchaseDetail->account_head_id = $voucherData['account_from_id'];
                $purchaseDetail->bank_id = $product['bank_id'];
                $purchaseDetail->date = $voucherData['voucher_date'];
                $purchaseDetail->voucher_no = $product['voucher_no'];
                $purchaseDetail->cheque_no = $product['cheque_no'];
                $purchaseDetail->v_type = $product['v_type'];
                $purchaseDetail->narration = $product['narration'];
                $purchaseDetail->credit = $product['amount'];
                $purchaseDetail->save();

                $purchaseDetail = new GeneralVoucher();
                $purchaseDetail->voucher_id = $voucherData['voucher_id'];
                $purchaseDetail->account_head_id = $voucherData['account_id'];
                $purchaseDetail->bank_id = $product['bank_id'];
                $purchaseDetail->date = $voucherData['voucher_date'];
                $purchaseDetail->voucher_no = $product['voucher_no'];
                $purchaseDetail->cheque_no = $product['cheque_no'];
                $purchaseDetail->v_type = $product['v_type'];
                $purchaseDetail->narration = $product['narration'];
                $purchaseDetail->debit = $product['amount'];
                $purchaseDetail->save();

                $vouchers = new LedgerDetailWise();
                $vouchers->voucher_id = $voucherData['id'];
                $vouchers->party_id = $voucherData['account_from_id'];
                $vouchers->voucher_no = $product['voucher_no'];
                $vouchers->voucher_type = $product['v_type'];
                $vouchers->date = $voucherData['voucher_date'];
                $vouchers->other = $product['narration'];
                $vouchers->credit = $product['amount'];
                $vouchers->save();

                $vouchers = new LedgerDetailWise();
                $vouchers->voucher_id = $voucherData['id'];
                $vouchers->party_id = $voucherData['account_id'];
                $vouchers->voucher_no = $product['voucher_no'];
                $vouchers->voucher_type = $product['v_type'];
                $vouchers->date = $voucherData['voucher_date'];
                $vouchers->other = $product['narration'];
                $vouchers->debit = $product['amount'];
                $vouchers->save();

                if ($voucherData->voucher_no != "0") {
                    $project = PostDateCheque::where("id", $product['VoucherID'])->first();
                    $project->status = "Clear";
                    $project->save();
                }

                return $voucherData['id'];
            }
            // else{
            //   echo "<alert>Select Cheque First!</alert>";
            // }

            // if(($product['checkedValue']) == false){
            //        return "not entered";
            //    }

        }
        //return $purchaseData['id'];
        //return $voucherData['id'];
    }

    public function show($id)
    {
        //
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
        //
    }
}
