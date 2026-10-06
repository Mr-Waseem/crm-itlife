<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountGroup;
use App\Models\AccountGroup2;
use App\Models\AccountGroup3;
use App\Models\Party;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as DataTables;

class AccountGroup3Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        //  $warehouses = AccountGroup2::with('account_group_3')->OrderBy('id', 'asc')->get();
        //     foreach($warehouses as $warehouse){
        //          $sum = 0; 
        //         foreach($warehouse->account_group_3 as $group3){
        //             $sum = $sum + 1;
        //             // return $group3;
        //             $group2=AccountGroup2::where('id', $group3->account_group2_id)->first('code'); //11
        //             $group3->code= $group2->code.$sum;
        //             $group3->product_code= $sum;
        //             $group3->save();
        //         }
        //     }
        // return "done";

        if ($request->ajax()) {
            $groups = AccountGroup3::with('account_group2','account_group1')
            ->OrderBy('code', 'asc')
            ->get();
            return DataTables::of($groups)
                ->addIndexColumn()
                ->addColumn('account_group1', function ($data) {
                    return $data->account_group1->name;
                })
                ->addColumn('account_group2', function ($data) {
                    return $data->account_group2->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->code . '_' . $row->name . '_' . $row->account_group1_id . '_' . $row->account_group2_id . '"><i class="fa fa-pencil"></i></button>&nbsp;
                                <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-account"><i class="fa fa-trash"></i></a>
                            </div>';

                    return $btn;
                })
                ->rawColumns(['action', 'account_group2'])
                ->make(true);
        }

        $code = AccountGroup3::OrderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->code + 1;
        }

        $accountGroups1 = AccountGroup::orderBy('id')->pluck('name', 'id');
        $accountGroups2 = AccountGroup2::orderBy('id')->pluck('name', 'id');
        return view('account-group3.index', compact('codes', 'accountGroups1', 'accountGroups2'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
           // 'code' => 'required',
            'name' => 'required',
            'account_group1_id' => 'required',
            'account_group2_id' => 'required'
        ]
        , [
            'account_group1_id.required' => 'The account group 1 field is required',
            'account_group2_id.required' => 'The account group 2 field is required'
        ]
    );
        if ($request->id != null) {
         //return $request;
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'ACCOUNT GROUP 3')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            $accountGroup3 = AccountGroup3::find($request->id);
            // $accountGroup3->update($request->all());
// return $request;
           if($request->group_id !=  $request->account_group2_id){
                $ag2code = AccountGroup2::find($request->account_group2_id);
                 $ag2sum = AccountGroup3::where('account_group2_id', $ag2code->id)->Orderby('code', 'desc')->first();
                if($ag2sum){
                    $count2 = $ag2sum->account_code + 1;
                }else{
                    $count2 = 1;
                }
                
                 $accountGroup3->code =$ag2code->code.$count2;
                 $accountGroup3->account_code = $count2;
                 $accountGroup3->name = $request->name;
                 $accountGroup3->account_group1_id = $request->account_group1_id;
                 $accountGroup3->account_group2_id = $request->account_group2_id;
                $accountGroup3->save();
           }else{
                $accountGroup3->name = $request->name;
                $accountGroup3->save();
           }
            
            return redirect()->back()->with('flash_message', 'Account Group 3 Updated Successfully');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'ACCOUNT GROUP 3')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

             $ag2code = AccountGroup2::find($request->account_group2_id);
            
              $ag2sum = AccountGroup3::where('account_group2_id', $ag2code->id)->orderby('id', 'desc')->first();
              if($ag2sum){
                 $count2 = $ag2sum->account_code + 1;
              }else{
                $count2 = 1;
              }
              
            $accountgp3 = AccountGroup3::create($request->all());
            $accountgp3->code =$ag2code->code.$count2;
            $accountgp3->account_code =$count2;  
            $accountgp3->save();
            return redirect()->back()->with('flash_message', 'Account Group 3 Added Successfully');
        }
        return redirect()->back()->with('error_message', 'Something went wrong');
    }

    public function destroy($id)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'ACCOUNT GROUP 3')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        $party = Party::where('account_group_id3', $id)->count();
        if ($party > 0) {
            return redirect()->back()->with('error_message', 'Please Delete Parties Before...');
        } else {
            AccountGroup3::findOrFail($id)->delete();
            return redirect()->back()->with('flash_message', 'Account Group 3 Deleted Successfully');
        }
    }

    public function GetAg1Data(Request $request)
    {
        $ag1Data = AccountGroup2::select('id', 'name')->where('account_group1_id', $request->ag1)->get();
        return response()->json($ag1Data);
    }

    public function GetAg2Data(Request $request)
    {
        $ag2Data = AccountGroup3::select('id', 'name')->where('account_group2_id', $request->ag2)->get();
        return response()->json($ag2Data);
    }
}