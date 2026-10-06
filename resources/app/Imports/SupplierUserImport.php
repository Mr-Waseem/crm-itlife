<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Auth;
class SupplierUserImport implements ToModel, WithValidation
{
    // use Importable;
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
        if($row[0] != "CustomerName" && $row[0] !=null){
            return new User([ 
                'name'  => $row[0],
                'email'  => $row[2],
                'password'  => bcrypt($row[3]),
                'showpassword'  => $row[3],
                'biller_id'  => Auth::user()->id,
                'phone'  => $row[5],
                'address'  => $row[1],
                'status'  => $row[8],
                'role'  => "Normal User",
                'type'  => "SUPPLIER",
                // 'party_id'  => $datas->party_id,
                // 'password'  => "HELLO000",
                // 'showpassword'  => "HELLO000",
            ]);
        }
    }


}
