<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Auth;

class CustomerUserImport implements ToModel, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function rules(): array
    {
        return [
            '2' => 'unique:users,email'
        ];
    
    }
    
    public function customValidationMessages()
    {
        return [
            '2.unique' => 'Email Already Exist.',
        ];
    }
    public function model(array $row)
    {
       
            if($row[0] != "Customer Name" && $row[0] !=null){
                return new User([ 
                    'name'  => $row[0],
                    'address'  => $row[1],
                    'email'  => $row[2],
                    'password'  => bcrypt($row[3]),
                    'showpassword'  => $row[3],              
                    'phone'  => $row[4],
                    'type' => 'CUSTOMER',
                    'party_id'  => 0,
                    'status'  => $row[8],
                    'role'  => "Normal User",
                    'biller_id'  => Auth::user()->id,
                ]);
            }
        
    }
}
