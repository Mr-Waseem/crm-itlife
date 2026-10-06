<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Warehouse;
use App\Models\RightsLevel1;
use App\Models\RightsLevel2;
use App\Models\RightsLevel3;
use App\Models\MenuRights;
use App\Models\Party;
use App\Models\RightNames;
use App\Models\VoucherNames;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('warehouse')->orderBy('id', 'asc')->get();
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('warehouse', function ($data) {
                    if($data->warehouse){
                    return $data->warehouse->name;
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->name . '_' . $row->address . '_' . $row->phone . '_' . $row->warehouse_id . '_' . $row->email . '_' . $row->showpassword . '_' . $row->status . '_' . $row->role . '"><i class="fa fa-pencil"></i></button>&nbsp;
                                    <button type="button" class="btn btn-danger btn-sm remove-user" name="' . $row->id . '"><i class="fa fa-trash"></i></button>
                                    ';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $warehouses = Warehouse::pluck('name', 'id')->prepend('Select Godown', '');
        // $roles = Role::OrderBy('id')->pluck('name', 'name')->prepend('Select Role', '');
        $roles = array('' => 'Select Role', 'Normal User' => 'Normal User', 'Admin' => 'Admin');
        $status = array('' => 'Select Status', '1' => 'Active', '0' => 'In Active');

        return view('users.index', compact('warehouses', 'roles', 'status'));
    }

    public function store(Request $request)
    {
        //return $request;
        if ($request->idd != null) {
            $this->validate($request, [
                'name' => 'required',
                'role' => 'required',
                'warehouse_id' => 'required',
                'status' => 'required'
            ]);

            $role = Role::whereName($request->role)->first();

            $input = $request->all();
            $input['email'] = $input['email'];
            $input['password'] = bcrypt($input['password']);
            $input['showpassword'] = $request->password;
            $input['role'] = $role->name;

            $user = User::find($request->idd);
            $user->update($input);
            $user->roles()->attach($role->id);

            return redirect()->back()->with('flash_message', 'User Updated Successfully');
        } else {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'role' => 'required',
                'warehouse_id' => 'required',
                'status' => 'required'
            ]);

            $role = Role::whereName($request->role)->first();

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $input['showpassword'] = $request->password;
            $input['role'] = $role->name;

            $user = User::create($input);
            $user->roles()->attach($role->id);

            return redirect()->back()->with('flash_message', 'User Created Successfully');
        }
        abort(500);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            User::find($id)->delete();
            return redirect()->back()->with('flash_message', 'User Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'User Not Exist!');
        }
    }

    public function UserRights(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('warehouse')
                // ->where('id', '!=', Auth::User()->id)
                ->orderBy('id', 'asc')
                ->get();
            return DataTables::of($users)
                ->addIndexColumn()
                ->editColumn('role', function ($data) {
                    return "User";
                })
                ->editColumn('warehouse', function ($data) {
                    if ($data->warehouse){
                        return $data->warehouse->name;
                    }
                    
                })
                ->editColumn('status', function ($data) {
                    if ($data->status == 1) {
                        return "<span class='badge badge-pill badge-info'>Active</span>";
                    } else {
                        return "<span class='badge badge-pill badge-danger'>Deactive</span>";
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-expanded="false">Action</button>
                    <div class="dropdown-menu pt-2" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 44px, 0px); top: 0px; left: 0px; will-change: transform;">
                      <a class="dropdown-item" href="user-rights/menus-rights/' . $row->id . '"><i class="fa fa-list-ul"></i> Menu Rights</a>
                      <a class="dropdown-item" href="user-rights/vouchers-rights/' . $row->id . '"><i class="fa fa-file-text"></i> Voucher Rights</a>
                      <a class="dropdown-item d-none" href="#"><i class="fa fa-location-arrow"></i> Other Rights</a>
                    </div>
                  </div>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('users.rights');
    }

    public function UserMenuRightsView($user_id)
    {
        $user = User::find($user_id);
        $MenuRights1 = MenuRights::whereType('Level 1')->where('user_id', $user_id)->get();
        $MenuRights2 = MenuRights::whereType('Level 2')->where('user_id', $user_id)->get();
        $MenuRights3 = MenuRights::whereType('Level 3')->where('user_id', $user_id)->get();

        $rightsLevel1 = RightsLevel1::select('id', 'code', 'title')->orderBy('code')->get();
        $rightsLevel2 = RightsLevel2::select('id', 'code', 'title', 'right_level1_id')->orderBy('code')->get();
        $rightsLevel3 = RightsLevel3::select('id', 'code', 'title', 'right_level2_id')->orderBy('code')->get();

        return view('users.menu-rights', compact('user', 'MenuRights1', 'MenuRights2', 'MenuRights3', 'user_id', 'rightsLevel1', 'rightsLevel2', 'rightsLevel3'));
    }

    public function MenusStore(Request $request)
    {
        $user_id = $request->user_id;
        if (!isset($request->level1_id) && $request->level1_id == null) {
            return redirect()->back()->with('error_message', 'Please Select at least one level');
        }

        MenuRights::where('user_id', $user_id)->delete();

        if (isset($request->level1_id) && $request->level1_id !== null) {
            for ($i = 0; $i < count($request->level1_id); $i++) {
                $menuRight = new MenuRights();
                $menuRight->user_id = $user_id;
                $menuRight->level_id = $request->level1_id[$i];
                $menuRight->type = "Level 1";
                $menuRight->save();
            }
        }


        if (isset($request->level2_id) && $request->level2_id !== null) {
            for ($i = 0; $i < count($request->level2_id); $i++) {
                $menuRight = new MenuRights();
                $menuRight->user_id = $user_id;
                $menuRight->level_id = $request->level2_id[$i];
                $menuRight->type = "Level 2";
                $menuRight->save();
            }
        }
        if (isset($request->level3_id) && $request->level3_id !== null) {
            for ($i = 0; $i < count($request->level3_id); $i++) {
                $menuRight = new MenuRights();
                $menuRight->user_id = $user_id;
                $menuRight->level_id = $request->level3_id[$i];
                $menuRight->type = "Level 3";
                $menuRight->save();
            }
        }

        return redirect('user-rights/menus-rights/' . $user_id)->with('flash_message', 'Menus Access Updated Successfully');
    }

    public function VoucherRightsView($user_id)
    {
        // $voucher_names = VoucherNames::select('id', 'voucher_name')->orderBy('code')->get();


        // $voucher_names = RightsLevel3::select('id', 'title')->orderBy('code')->get();
        $voucher_names = MenuRights::join('rights_level3', 'rights_level3.id', '=', 'menu_rights.level_id')
            ->select('rights_level3.id', 'rights_level3.title', 'rights_level3.type')
            ->where('menu_rights.user_id', $user_id)
            ->where('menu_rights.type', 'Level 3')
            ->get();
        $rightNames = RightNames::select('id', 'name')->orderBy('id')->get();

        $selected_vouchers_names = VoucherRights::select('voucher_name')->distinct()->where('user_id', $user_id)->get();
        $selected_vouchers = VoucherRights::all();
        $user = User::with('warehouse:id,name')->find($user_id);

        return view('users.voucher-rights', compact('user_id', 'voucher_names', 'rightNames', 'selected_vouchers', 'selected_vouchers_names', 'user'));
    }

    public function VoucherRigthStore(Request $request)
    {
        // return $request->all();
        if (!isset($request->voucher_name)) {
            return redirect()->back()->with('error_message', 'Please Enter AtLeast one Entry');
        }
        if (!isset($request->right_name)) {
            return redirect()->back()->with('error_message', 'Please Enter AtLeast one Entry');
        }

        VoucherRights::where('user_id', $request->user_id)->delete();
        for ($i = 0; $i < count($request->right_name); $i++) {
            $voucherRight = new VoucherRights();
            $voucherRight->user_id = $request->user_id;
            $voucherRight->voucher_name = explode('_', $request->right_name[$i])[1];
            $voucherRight->right_name = explode('_', $request->right_name[$i])[0];
            $voucherRight->save();
        }

        return redirect()->back()->with('flash_message', 'Voucher Rights Updated Successfully');
    }
}