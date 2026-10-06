<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Warehouse;
use App\Models\AccountGroup;
use Illuminate\Support\Facades\Session;
use Brian2694\Toastr\Facades\Toastr;

class RoleController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $users = User::with('department')->orderBy('id', 'asc')->get();
        return view('roles.index', compact('users'));
        // if ($request->ajax()) {
        //     $users = User::with('shop')->orderBy('id', 'asc')->get();
        //     return DataTables::of($users)
        //                         ->addIndexColumn()
        //                         ->addColumn('store',function($data){
        //                             return $data->shop->name;
        //                         })
        //                         ->addColumn('action', function($row){
        //                             $btn = '<a href="roles/'.$row->id.'/edit" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>&nbsp;
        //                             <a href="roles/destroy/'.$row->id.'" onclick="return confirm(`Are you sure you want to delete this record?`)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
        //                             ';

        //                             return $btn;
        //                         })
        //                         ->rawColumns(['action'])
        //                         ->make(true);
        // }

        // return view('roles.index');
    }

    public function create()
    {
        $departments = Warehouse::pluck('name', 'id');
        $location = AccountGroup::pluck('name', 'id')->prepend('Select Supplier Location', '')->toArray();

        $roles = Role::OrderBy('id')->pluck('name', 'name');
        $status = array('1' => 'Active','0' => 'In Active');
        return view('roles.create', compact('departments', 'location', 'roles', 'status'));
    }

    public function store(Request $request)
    {
        $user = User::where('email', $request['email'])->first();
        $user->roles()->detach();

        if ($request['role_supervisor']) {
            $user->roles()->attach(Role::where('name', 'Supervisor')->first());
        }

        if ($request['role_center']) {
            $user->roles()->attach(Role::where('name', 'Center')->first());
        }

        if ($request['role_driver']) {
            $user->roles()->attach(Role::where('name', 'Driver')->first());
        }

        if ($request['role_editor']) {
            $user->roles()->attach(Role::where('name', 'Editor')->first());
        }
        if ($request['role_author']) {
            $user->roles()->attach(Role::where('name', 'Author')->first());
        }
        if ($request['role_admin']) {
            $user->roles()->attach(Role::where('name', 'Admin')->first());
        }
    }

    public function addUser(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'department_id' => 'required',
            'role' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:16'
        ],[
            'department_id.required'=>'The department field is required'
        ]);
        $user = User::create($request->all());
        $user->password = bcrypt($request->password);
        $user->save();

        $user = User::where('email', $request->email)->first();
        $user->roles()->detach();

        if ($request['role_supervisor']) {
            $user->roles()->attach(Role::where('name', 'Supervisor')->first());
        }

        if ($request['role_center']) {
            $user->roles()->attach(Role::where('name', 'Center')->first());
        }

        if ($request['role_driver']) {
            $user->roles()->attach(Role::where('name', 'Driver')->first());
        }

        if ($request['role_editor']) {
            $user->roles()->attach(Role::where('name', 'Editor')->first());
        }
        if ($request['role_author']) {
            $user->roles()->attach(Role::where('name', 'Author')->first());
        }
        if ($request['role_admin']) {
            $user->roles()->attach(Role::where('name', 'Admin')->first());
        }

        return redirect()->back()->with(Toastr::success('New User Added Successfully!'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $edit = User::findOrFail($id);
        $departments = Warehouse::pluck('name', 'id');
        $location = AccountGroup::pluck('name', 'id')->prepend('Select Supplier Location', '')->toArray();
        $roles = Role::OrderBy('id')->pluck('name', 'name');
        $status = array('1' => 'Active','0' => 'In Active');
        return view('roles.edit', Compact('edit', 'departments', 'location', 'roles', 'status'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required'
        ]);

        $update = User::findOrFail($id);
        $update->update($request->all());


        $user = User::where('email', $request['email'])->first();
        $user->roles()->detach();

        if ($request['role_supervisor']) {
            $user->roles()->attach(Role::where('name', 'Supervisor')->first());
        }

        if ($request['role_center']) {
            $user->roles()->attach(Role::where('name', 'Center')->first());
        }

        if ($request['role_driver']) {
            $user->roles()->attach(Role::where('name', 'Driver')->first());
        }

        if ($request['role_editor']) {
            $user->roles()->attach(Role::where('name', 'Editor')->first());
        }
        if ($request['role_author']) {
            $user->roles()->attach(Role::where('name', 'Author')->first());
        }
        if ($request['role_admin']) {
            $user->roles()->attach(Role::where('name', 'Admin')->first());
        }


        Session::flash('flash_message', 'User Updated Successfully!');
        return redirect('roles');
    }

    public function destroy($id)
    {
        $delete = User::findOrFail($id);
        $delete->delete();
        return "User Successfully Deleted!";
    }
}
