<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Author']);
        Role::create(['name' => 'Editor']);
        Role::create(['name' => 'Center']);
        Role::create(['name' => 'Driver']);
        Role::create(['name' => 'Supervisor']);
    }
}