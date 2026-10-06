<?php

namespace App\Imports;

use App\Models\Party;
use Maatwebsite\Excel\Concerns\ToModel;

class SupplierPartyImport implements ToModel
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
            $accountGroup = AccountGroup3::where('code', $row[10])->first();
        return new Party([ 
            'shop_id'  => Auth::User()->warehouse_id,
            'account_type' => 'SUPPLIER',
            'code' => 3455,
            'account_group_id' => $accountGroup->account_group1_id,
            'account_group_id2' => $accountGroup->account_group2_id,
            'account_group_id3' => $accountGroup->id,
            'show_products' => 0,
            'party_name'  => $row[0],
            'address'  => $row[1],
            'phone'  => $row[4],
            'city'  => $row[5],
            'ntn'  => $row[6],
            'strn'  => $row[7],
            'status'  => $row[8],
            'type'  => $row[9],
            'role'  => "SUPPLIER",
            'created_by'  => Auth::User()->id,
        ]);
        }
    }
}
