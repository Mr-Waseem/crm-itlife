<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $role_admin = Role::where('name', 'Admin')->first();

        $admin = new User();
        $admin->name = 'Admin';
        $admin->email = 'sammarforu@gmail.com';
        $admin->password = bcrypt('sammar');
        $admin->image = 'logo.png';
        $admin->shop_id = '1';
        $admin->biller_id = '1';
        $admin->phone = '03xx xxxxxxx';
        $admin->address = 'LAHORE';
        $admin->department_id = 1;
        $admin->role = 'Admin';
        $admin->status = 1;
        $admin->warehouse_id = 1;
        $admin->save();
        $admin->roles()->attach($role_admin);

        $admin = new User();
        $admin->name = 'Demo';
        $admin->email = 'demo@itlifee.net';
        $admin->password = bcrypt('demo12345');
        $admin->image = 'logo.png';
        $admin->shop_id = '1';
        $admin->biller_id = '1';
        $admin->phone = '03xx xxxxxxx';
        $admin->address = 'LAHORE';
        $admin->department_id = 1;
        $admin->role = 'Admin';
        $admin->status = 1;
        $admin->warehouse_id = 1;
        $admin->save();
        $admin->roles()->attach($role_admin);
    }
}