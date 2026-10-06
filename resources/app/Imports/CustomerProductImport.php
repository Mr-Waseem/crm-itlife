<?php

namespace App\Imports;

use App\Models\Party;
use App\Models\Product;
use App\Models\CustomerProduct;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Auth;
class CustomerProductImport implements ToModel, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function rules(): array
    {
        return [
            '1' => 'unique:customer_products,product_name'
        ];
    
    }
    
    public function customValidationMessages()
    {
        return [
            '1.unique' => 'Product Already Exist.',
        ];
    }
    public function model(array $row)
    {
        // return $row;
        if($row[1] != "CustomerCode" && $row[1] !=null){
            // return $row;
            $CustomerID = Party::where('code', $row[1])->first();
            $productID = Product::where('code', $row[2])->first();
            if($CustomerID == ""){
                $CustomerID['id'] = 0;
            }
            if($productID == ""){
                $productID['id'] = 0;
            }
            
        return new CustomerProduct([
            // 'customer_id'  => $row[1],
            'customer_id'  => $CustomerID['id'],
            // 'product_id'  => $row[2],
            'product_id'  => $productID['id'],
            'product_code'  => $row[3],
            'product_name'  => $row[4],   
        ]);
    }
    }
}
