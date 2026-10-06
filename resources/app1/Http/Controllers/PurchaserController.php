<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Party;
use App\Models\AccountGroup;
use Illuminate\Http\Request;
use App\Models\AccountGroup2;
use App\Models\AccountGroup3;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as DataTables;

class PurchaserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $party = Party::with('user')->whereRole('Purchaser')->OrderBy('code', 'asc')->get();
            return DataTables::of($party)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->user) {
                        $useremail = $row->user->email;
                    } else {
                        $useremail = '';
                    }
                    $btn = '<div class="btn-group">
                    <button type="button" name="' . $row->id . '_' . $row->party_name . '_' . $row->address . '_' . $row->phone . '_' . $row->city . '_' . $row->ntn . '_' . $row->strn . '_' . $row->status . '_' . $row->type . '_' . $row->account_group_id3 . '_' .$useremail. '" class="btn btn-primary edit_btn btn-sm"><i class="fa fa-pencil"></i></button>&nbsp;
                                <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-account"><i class="fa fa-trash"></i></a>
                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $status = array('' => 'Select Status', '1' => 'Active', '0' => 'Deactive');
        $type = array('' => 'Select Type', 'Registered' => 'Registered', 'Un Registered' => 'Un Registered');

        $code = Party::whereRole('Purchaser')->orderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = (int)$code->code + 1;
        }
        $accountGroupLevel3 = AccountGroup3::pluck('name', 'id')->prepend('Select Level', '');

        return view('purchasers.index', compact('codes', 'status', 'type', 'accountGroupLevel3'));
    }


    public function store(Request $request)
    {
        //  return $request;
        if ($request->idd != null) {
            $this->validate($request, [
                'party_name' => 'required',
               
                // 'account_group_id' => 'required',
                // 'account_group_id2' => 'required',
                // 'account_group_id3' => 'required'
            ], [
                
                'account_group_id3.required' => 'The Account Group 3 field is required'
            ]);
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'PURCHASER')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            // return $accountGroupLevel3 = AccountGroup3::find($request->account_group_id3);
            // $data['account_group_id'] = $accountGroupLevel3->account_group1_id;
            // $data['account_group_id2'] = $accountGroupLevel3->account_group2_id;
            // $data['created_by'] = Auth::User()->id;
            $data['updated_by'] = Auth::User()->id;
            $data = $request->all();
           
            $party = Party::find($request->idd);
            $party->update($data);
            $user=User::where('party_id',$party->id)->first();
            $userupdate=User::find($user->id);
            $userupdate->update($request->all());
            $userupdate->name=$request->party_name;
            $userupdate->password=bcrypt($request->password);
            $userupdate->save();

            // $user->phone=$request->phone;
            // $user->type=$request->account_type;
            // $user->party_id=$party->id;
            // $user->status=$request->status;
            // $user->role='Normal User';
            // $user->type='SHIPPER';
            return redirect()->back()->with('flash_message', 'Purchaser Updated Successfully!');
        } else {
            // return "dd";
            $this->validate($request, [
                'party_name' => 'required',
                // 'email' => 'required|unique:users,email',
                //  'account_group_id3' => 'required'
            ], [
                // 'account_group_id3.required' => 'The Account Group 3 field is required'
            ]);
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                 ->where('voucher_name', 'PURCHASER')
                 ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            // return "ddd";
            // $accountGroupLevel3 = AccountGroup3::find($request->account_group_id3);
           $customersum = Party::orderBy('id', 'desc')->first();
           if($customersum == null){
            $count = 1;
            }else{
                $count = $customersum->code+1;
            }
            $count = $customersum->count() + 1;
            $party = Party::create($request->all());
            $party->code = $count;
            // $party->account_group_id = $accountGroupLevel3->account_group1_id;
            // $party->account_group_id2 = $accountGroupLevel3->account_group2_id;
            $party->role = 'PURCHASER';
            $party->type = 'Normal User';
            $party->created_by = Auth::User()->id;
            $party->save();

            $user= new User();
            $user->name=$request->party_name;
            $user->address=$request->address;
            $user->email=$request->email;
            $user->password=bcrypt($request->password);
            $user->phone=$request->phone;
            $user->type=$request->account_type;
            $user->party_id=$party->id;
            $user->status=$request->status;
            $user->role='Normal User';
            $user->type='PURCHASER';
            $user->warehouse_id=Auth::User()->warehouse_id;
            $user->save();

            return redirect()->back()->with('flash_message', 'Purchaser Added Successfully!');
        }
        return redirect()->back()->with('error_message', 'Something went wrong');
    }
    // public function store(Request $request)
    // {
    //     return "edd"
    //     if ($request->idd != null) {
    //         $this->validate($request, [
    //             'party_name' => 'required',


    //         ], [

    //             'account_group_id3.required' => 'The Account Group 3 field is required'
    //         ]);
    //         $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
    //             ->where('voucher_name', 'PURCHASER')
    //             ->where('right_name', 'EDIT')
    //             ->first();
    //         if (!$voucherRight) {
    //             return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    //         }


    //         $accountGroupLevel3 = AccountGroup3::find($request->account_group_id3);
    //         $data['account_group_id'] = $accountGroupLevel3->account_group1_id;
    //         $data['account_group_id2'] = $accountGroupLevel3->account_group2_id;
    //         $data['created_by'] = Auth::User()->id;
    //         $data['updated_by'] = Auth::User()->id;
    //         $data = $request->all();

    //         $party = Party::find($request->idd);
    //         $party->update($data);
    //         $user = User::where('party_id', $party->id)->first();
    //         if ($user) {
    //             $userupdate = User::find($user->id);
    //             $userupdate->update($request->all());
    //             $userupdate->password = bcrypt($request->password);
    //             $userupdate->name = $request->party_name;
    //             $userupdate->save();
    //         } else {
    //             $user = new User();
    //             $user->name = $request->party_name;
    //             $user->address = $request->address;
    //             $user->email = $request->email;
    //             $user->password = bcrypt($request->password);
    //             $user->phone = $request->phone;
    //             $user->type = $request->account_type;
    //             $user->party_id = $party->id;
    //             $user->status = $request->status;
    //             $user->role = 'Normal User';
    //             $user->warehouse_id = Auth::User()->warehouse_id;
    //             $user->save();
    //         }
    //         return redirect()->back()->with('flash_message', 'Purchaser Updated Successfully!');
    //     } else {
    //         //return $request;
    //         $this->validate($request, [
    //             'party_name' => 'required',
    //             'email' => 'required|unique:users,email',
    //             'account_group_id3' => 'required'
    //         ], [
    //             'account_group_id3.required' => 'The Account Group 3 field is required'
    //         ]);
    //         $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
    //             ->where('voucher_name', 'PURCHASER')
    //             ->where('right_name', 'ADD')
    //             ->first();
    //         if (!$voucherRight) {
    //             return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    //         }
    //         $accountGroupLevel3 = AccountGroup3::find($request->account_group_id3);
    //         $customersum = Party::where('account_group_id3', $accountGroupLevel3->id)->get();
    //         $count = $customersum->count() + 1;
    //         $party = Party::create($request->all());
    //         $party->code = $accountGroupLevel3->code . $count;
    //         $party->account_group_id = $accountGroupLevel3->account_group1_id;
    //         $party->account_group_id2 = $accountGroupLevel3->account_group2_id;
    //         $party->role = 'Purchaser';
    //         $party->created_by = Auth::User()->id;
    //         $party->save();


    //         $user = new User();
    //         $user->name = $request->party_name;
    //         $user->address = $request->address;
    //         $user->email = $request->email;
    //         $user->password = bcrypt($request->password);
    //         $user->phone = $request->phone;
    //         $user->type = $request->account_type;
    //         $user->party_id = $party->id;
    //         $user->status = $request->status;
    //         $user->role = 'Normal User';
    //         $user->warehouse_id = Auth::User()->warehouse_id;
    //         $user->save();

    //         return redirect()->back()->with('flash_message', 'Purchaser Added Successfully!');
    //     }
    //     return redirect()->back()->with('error_message', 'Something went wrong');
    // }

    public function destroy($id)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PURCHASER')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        $party = Party::find($id);
        if ($party) {
            Party::findOrFail($id)->delete();
            User::where('party_id', $party->id)->delete();
            return redirect()->back()->with('flash_message', 'Purchaser has been deleted!');
        } else {
            return redirect()->back()->with('error_message', 'Something went wrong');
        }
    }
}
