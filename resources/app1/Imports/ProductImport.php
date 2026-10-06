<?php

namespace App\Imports;

use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Departments;
use App\Models\Catagory;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Auth;
class ProductImport implements ToModel, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function rules(): array
    {
        return [
            '1' => 'unique:products,product_name'
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
        if($row[0] != "Product Name" && $row[0] !=null){
            
            $Warehouse = Warehouse::where('code', $row[1])->first(); //11
            $DepartmentID = Departments::where('code', $row[2])->first('id'); //11
            $CategoryID = Catagory::where('catagory_code', $row[3])->first('id'); //11
            // $LastProduct = Product::where('warehouse_id', $row[1])->Orderby('id', 'desc')->first('product_code'); //11
            $LastProduct = Product::where('warehouse_id', $Warehouse->id)->Orderby('id', 'desc')->first('product_code'); //11
            if($LastProduct){
                $CalculatedCode = $LastProduct->product_code+1;
            }else{
                $CalculatedCode = 1;
            }
            // return "dd";
            return new Product([ 
                // 'code'  => $row[0],
                'code'  => $Warehouse->code.$CalculatedCode,
                'product_code'  => $CalculatedCode,
                'product_name'  => $row[0],
                'warehouse_id'  => $Warehouse->id,
                'department_id'  => $DepartmentID->id,
                'category_id'  => $CategoryID->id,
                'product_type'  => $row[4],
                'uom'  => $row[5],
                'pack_type'  => $row[6],
                'product_cost'  => $row[7],
                'product_price'  => $row[8],
                'tax'  => $row[9],
                'packing'  => $row[10],
                'weight'  => $row[11],
                'dye_pcs'  => $row[12],
                'created_by'  => Auth::User()->id,
               
                // 'password'  => "HELLO000",
                // 'showpassword'  => "HELLO000",
            ]);
            }
        
    }
}
