<?php

namespace Database\Seeders;

use App\Models\Departments;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        Departments::create([
            'code' => '1',
            'name' => 'Department 1',
            'phone' => '03001234567',
            'email' => 'department1@example.com',
            'address' => 'Lahore'
        ]);
        Departments::create([
            'code' => '2',
            'name' => 'Department 2',
            'phone' => '03001234567',
            'email' => 'department2@example.com',
            'address' => 'Lahore'
        ]);
        Departments::create([
            'code' => '3',
            'name' => 'Department 3',
            'phone' => '03001234567',
            'email' => 'department3@example.com',
            'address' => 'Lahore'
        ]);
    }
}