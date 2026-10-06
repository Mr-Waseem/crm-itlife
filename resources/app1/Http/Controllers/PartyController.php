<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\AccountGroup;
use App\Models\AccountGroup2;
use App\Models\AccountGroup3;
use App\Models\Warehouse;
use App\Models\Stock;
use App\Models\VoucherRights;
use App\Models\Vouchers;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as DataTables;
use App\Exports\PartyExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class PartyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $party = Party::with('account_group2:id,name')
                ->with('account_group3:id,name')
                ->whereNotIN('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                // ->whereRole('Account')
                ->OrderBy('code', 'asc')
                ->get();
            return DataTables::of($party)
                ->addIndexColumn()
                ->addColumn('account_group', function ($data) {
                    // return $data->account_group->name;
                    if ($data->account_group) {
                        return $data->account_group->name;
                    }
                })
                ->addColumn('account_group2', function ($data) {
                    // return $data->account_group2->name;
                    if ($data->account_group2) {
                        return $data->account_group2->name;
                    }
                })
                ->addColumn('account_group3', function ($data) {
                    //return $data->account_group3->name;
                    if ($data->account_group3) {
                        return $data->account_group3->name;
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">
                                <button type="button" name="' . $row->id . '_' . $row->code . '_' . $row->party_name . '_' . $row->account_group_id . '_' . $row->account_group_id2 . '_' . $row->account_group_id3 . '" class="btn btn-primary edit_btn btn-sm"><i class="fa fa-pencil"></i></button>&nbsp;
                                <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-account"><i class="fa fa-trash"></i></a>
                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $accountGroups1 = AccountGroup::OrderBy('name', 'asc')
            // ->whereNotIn('id', [1, 2])
            ->pluck('name', 'id')
            ->prepend("Select AccountGroup Group 1", "");
        $accountGroups2 = AccountGroup2::OrderBy('name', 'asc')->pluck('name', 'id')->prepend("Select AccountGroup Group 2", "");
        $accountGroups3 = AccountGroup3::OrderBy('name', 'asc')->pluck('name', 'id')->prepend("Select AccountGroup Group 3", "");
        $Supplier = AccountGroup::pluck('name', 'id');
        $shop = Warehouse::where('id', '!=', 1)->OrderBy('name', 'asc')->pluck('name', 'id')->prepend('Select Shop', '0')->toArray();

        $code = Party::whereRole('Account')->orderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = (int)$code->code + 1;
        }

        return view('parties.index', compact('accountGroups1', 'accountGroups2', 'accountGroups3', 'codes', 'shop', 'Supplier'));
    }

    public function store(Request $request)
    {

        if ($request->idd != null) {
            // return $request;
            $this->validate(
                $request,
                [
                    //'code' => 'required',
                    //'party_name' => 'required',
                    // 'account_group_id' => 'required',
                    // 'account_group_id2' => 'required',
                    // 'account_group_id3' => 'required'
                ]
                //, [
                //     'account_group_id.required' => 'The Account Group field is required',
                //     'account_group_id2.required' => 'The Account Group 2 field is required',
                //     'account_group_id3.required' => 'The Account Group 3 field is required'
                // ]

            );

            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'CHART OF ACCOUNT')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            $party = Party::find($request->idd);
            $party->update($request->all());
            if ($request->group_id != $request->account_group_id3) {
                $ag3code = AccountGroup3::find($request->account_group_id3);
                $ag3sum = Party::where('account_group_id3', $ag3code->id)->get();
                $count3 = $ag3sum->count() + 1;
                $party->code = $ag3code->code . $count3;
                $party->account_type = $ag3code->name;
                $party->save();
            }

            return redirect()->back()->with('flash_message', 'Account Updated Successfully!');
        } else {
            // return $request;
            $this->validate($request, [
                // 'code' => 'required',
                'party_name' => 'required|unique:parties',
                'account_group_id' => 'required',
                'account_group_id2' => 'required',
                'account_group_id3' => 'required'
            ], [
                'account_group_id.required' => 'The Account Group field is required',
                'account_group_id2.required' => 'The Account Group 2 field is required',
                'account_group_id3.required' => 'The Account Group 3 field is required'
            ]);

            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'CHART OF ACCOUNT')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            $ag3code = AccountGroup3::find($request->account_group_id3);
            $ag3sum = Party::where('account_group_id3', $ag3code->id)->get();
            $count3 = $ag3sum->count() + 1;
            $party = Party::create($request->all());
            $party->code = $ag3code->code . $count3;
            $party->account_type = $ag3code->name;
            
            $party->save();
            return redirect()->back()->with('flash_message', "Account Added Successfully!");
        }
        return redirect()->back()->with('error_message', 'Something went wrong');
    }

    public function destroy($id)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'CHART OF ACCOUNT')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        $voucher = Vouchers::where('account_id', $id)->count();
        $stock = Stock::where('party_id', $id)->count();
        if ($voucher > 0) {
            return redirect()->back()->with('error_message', "Journal Voucher Exist !!!");
        }
        if ($stock > 0) {
            return redirect()->back()->with('error_message', "Stock Voucher Exist !!!");
        }
        Party::findOrFail($id)->delete();
        return redirect()->back()->with('flash_message', "Party has been Deleted!");
    }

    public function ExportExcel()
    {
        return Excel::download(new PartyExport, 'chart_of_account.xlsx');
    }

    public function ExportPDF()
    {
        // $party = Party::with('account_group2:id,name')
        //     ->with('account_group3:id,name')
        //     ->whereNotIN('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
        //     ->OrderBy('code', 'asc')
        //     ->get();

        $parties = AccountGroup::with(['account_group_2' => function ($query) {
             $query->OrderBy('code', 'asc');
            $query->with(['account_group_3' => function ($query1) {
                $query1->OrderBy('code', 'asc');
                $query1->with(['parties' => function($que){
                    $que->OrderBy('code', 'asc');
                }]);
                // $query1->with('parties')->whereHas('parties',function($query2){
                //     $query2->whereNotIN('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT']);
                // });
            }]);
        }])
        ->OrderBy('code', 'asc')
        ->get();

        // return view('pdf',compact('parties'));


        $pdf = PDF::loadView('parties.pdf_view', compact('parties'));
        return $pdf->download('chart_of_account.pdf');
    }
}
