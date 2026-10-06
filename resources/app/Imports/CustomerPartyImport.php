<?php

namespace App\Imports;

use App\Models\Party;
use App\Models\User;
use App\Models\AccountGroup3;
use Maatwebsite\Excel\Concerns\ToModel;
use Auth;
class CustomerPartyImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if($row[0] != "Customer Name" && $row[0] !=null){
            $accountGroup = AccountGroup3::where('code', $row[10])->first();

            $LastProduct = Party::where('account_group_id3', $accountGroup->id)->Orderby('id', 'desc')->first(); //11
            if($LastProduct){
                $CalculatedCode = $LastProduct->code+1;
            }else{
                $CalculatedCode = 1;
            }

        return new Party([ 
            'shop_id'  => Auth::User()->warehouse_id,
            'account_type' => 'CUSTOMER',
            'code' => $CalculatedCode,
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
            'role'  => "Customer",
            'created_by'  => Auth::User()->id,
            // 'password'  => "HELLO000",
            // 'showpassword'  => "HELLO000",
        ]);
        }
    }
}
