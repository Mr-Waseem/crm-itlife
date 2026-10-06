<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\AccountGroup;
use App\Models\AccountGroup2;
use App\Models\AccountGroup3;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as DataTables;

class AccountGroup2Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $groups = AccountGroup2::with('account_group1:id,name')
            ->OrderBy('code', 'asc')
            ->get();
            return DataTables::of($groups)
                ->addIndexColumn()
                ->addColumn('account_group', function ($data) {
                    if ($data->account_group1) {
                        return $data->account_group1->name;
                    } else {
                        return "";
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->code . '_' . $row->name . '_' .$row->account_group1_id. '"><i class="fa fa-pencil"></i></button>&nbsp;
                                            <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-account"><i class="fa fa-trash"></i></a>
                                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $code = AccountGroup2::OrderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->code + 1;
        }
        $accountGroups = AccountGroup::orderBy('id')->pluck('name', 'id')->prepend('Select Account Group 1', '');
        return view('account-group2.index', compact('codes', 'accountGroups'));
    }

    public function store(Request $request)
    {
        // return $request;
        $this->validate($request, [
            'name' => 'required',
             'account_group1_id' => 'required'
        ]
        , [
            'account_group1_id.required' => 'The Account Group field is required'
        ]
    );
        if ($request->idd != null) {
            // return "update";
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'ACCOUNT GROUP 2')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            //  return $request;
                    $accountGroup = AccountGroup2::where('id', $request->idd)->first();
                //   return $request;
                  
                // $accountGroup->update($request->all());
                if($request->group_id != $request->account_group1_id){
                      $ag1code = AccountGroup::find($request->account_group1_id); // 1
                      $LastGroupTwo = AccountGroup2::where('account_group1_id', $ag1code->id)
                ->OrderBy('id', 'desc')
                ->first();
                // $count = $ag2sum->count() + 1;
                // $accountGroup->code =$ag1code->code.$count;
                // $accountGroup->save();

                // if($LastGroupTwo){
                    $count = $LastGroupTwo->account_code + 1;
                    $accountGroup->code = $ag1code->code.$count;
                    $accountGroup->account_code = $count;
                    // $accountGroup->name = $request->name;
                    $accountGroup->account_group1_id = $request->account_group1_id;
                    $accountGroup->save();
                // }else{
                //     $count = 1;
                //     $GroupTwo->code = $ag1code->code.$count;
                //     $GroupTwo->account_code = $count;
                //     $GroupTwo->save();
                // }
                }else{
                    $accountGroup->name = $request->name;
                    $accountGroup->save();
               }
                // else{
                //     return "esle";
                //   $data['name'] = $request->name;
                // $data['account_group1_id'] = $request->account_group1_id;
                // $data->save();  
                // }
                // return $request;
                // $data['code'] = $request->name;
                // $data['product_code'] = $request->name;
                
                // // return $data;
                // $data->name = $request->name;
                //     $data->account_group1_id = $request->account_group1_id;
                //     $accountGroup->save();
                // $accountGroup->update($data->all());
                // $accountGroup->update($request->all());
            return redirect()->back()->with('flash_message', 'Account Group 2 Updated Successfully');

           


        } else {
            // return "ddss";
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'ACCOUNT GROUP 2')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
               $ag1code = AccountGroup::where('id', $request->account_group1_id)->first(); //6
                $LastGroupTwo = AccountGroup2::where('account_group1_id', $ag1code->id) // 1
             ->OrderBy('id', 'desc')
             ->first();
            // return $request;
             $GroupTwo = AccountGroup2::create($request->all());
            if($LastGroupTwo){
                //  $LastGroupTwo;
                // return "d";
                 $count = $LastGroupTwo->account_code + 1;
                $accountCode = $ag1code->code;
                // $GroupTwo->code = $ag1code->code+$count;
                $GroupTwo->code = $accountCode. $count ;
                $GroupTwo->account_code = $count;
               
                $GroupTwo->save();
            }else{
                // return "ble";
                $count = 1;
                $GroupTwo->code = $ag1code->code.$count;
               $GroupTwo->account_code = $count;
                $GroupTwo->save();
            }
            
            return redirect()->back()->with('flash_message', 'Account Group 2 Added Successfully');
        }
        return redirect()->back()->with('error_message', 'Something went wrong');
    }

    public function destroy($id)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'ACCOUNT GROUP 2')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        $ag3 = AccountGroup3::where('account_group2_id', $id)->count();
        if ($ag3 > 0) {
            return redirect()->back()->with('error_message', 'Please Delete Account Group 2 Before...');
        } else {
            AccountGroup2::findOrFail($id)->delete();
            return redirect()->back()->with('flash_message', 'Account Group 2 Deleted Successfully');
        }
    }
}
