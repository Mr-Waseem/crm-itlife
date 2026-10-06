<?php

namespace App\Http\Controllers;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Catagory;
use App\Models\Departments;
use App\Models\UOM;
use App\Models\VoucherRights;
use App\Models\Warehouse;
use App\Imports\ProductImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables as DataTables;
use Excel;
use PDF;
use Mail;
class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(){
        return view('products.import');
    }

    public function ImportProducts(Request $request){
        
        $this->validate($request, [
            'product_file' => 'required'
        ]);

        // $path = $request->file('product_file')->getRealPath();
        // $data = Excel::import(new ProductImport, $path);

        $path1 = $request->file('product_file')->store('temp'); 
         $path = storage_path('app').'/'.$path1;  
        $data = Excel::import(new ProductImport, $path);
        // $data1 = Excel::import(new CustomerUserImport, $path,  $data);
        return redirect()->back()->with('flash_message', 'File Imported Successfully!');

        // return $results = Excel::raw($path, Excel::XLSX);
    }

    public function index(Request $request)
    {

        //It will automatically products code correct

        // $warehouses = Warehouse::with('products')->OrderBy('id', 'asc')->get();
        //     foreach($warehouses as $warehouse){
        //          $sum = 0; 
        //         foreach($warehouse->products as $product){
        //             $sum = $sum + 1;
        //             $WarehouseCode=Warehouse::where('id', $product->warehouse_id)->first('code'); //11
        //             $product->code= $WarehouseCode->code.$sum;
        //             $product->product_code= $sum;
        //             $product->save();
        //         }
        //     }
        // return "done";
 
        $usertype = Auth::User()->role;

        if ($request->ajax()) {
          

                
                if($usertype == "Admin"){
                    $products = Product::
                    with('category:id,catagory_name')
                    ->OrderBy('code', 'asc')
                    ->get();
                }else{
                    $products = Product::
                    with('category:id,catagory_name')
                    ->where('warehouse_id', Auth::User()->warehouse_id)
                    ->OrderBy('code', 'asc')
                    ->get();
                }

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('category', function ($data) {
                    if($data->category){
                    return $data->category->catagory_name;
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" 
                    name="' . $row->id . '_' . $row->product_name . '_' . $row->warehouse_id . '_' . 
                    $row->category_id . '_' . $row->uom . '_' . $row->pack_type . '_' . $row->product_cost .
                     '_' . $row->product_price . '_' . $row->tax . '_' . $row->image . '_' . $row->product_type .'_'
                     .$row->department_id.'_'.$row->packing.'_'.$row->weight. '_'.$row->dye_pcs.'"><i class="fa fa-pencil"></i></button>&nbsp;
                                <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-product"><i class="fa fa-trash"></i></a>
                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $code = Product::where('warehouse_id', Auth::User()->warehouse_id)->orderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = (int)$code->product_code + 1;
        }

        $catagories = Catagory::OrderBy('catagory_name', 'asc')->pluck('catagory_name', 'id')->prepend('Select Product Group', '');
        $uoms = UOM::OrderBy('id', 'asc')->pluck('uom', 'uom')->prepend('Select Unit', '');

        if($usertype == "Admin"){
            $warehouses = Warehouse::orderBy('id')->pluck('name', 'id')->prepend('Select Godown', '');
        }else{
            $warehouses = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
        }

        
        $pack_type = array('' => 'SELECT PACKTYPE', 'BAG' => 'BAG', 'DRUM' => 'DRUM', 'CANS' => 'CANS', 'BOTTLES' => 'BOTTLES', 'CARTON' => 'CARTON','ROLL' => 'ROLL', 'PCS' => 'PCS', 'DOZEN' => 'DOZEN', 'TANKER' => 'TANKER', 'PACKET' => 'PACKET');
        $product_type = array('' => 'Select Product Type', 'Normal' => 'Normal Product', 'Finish' => 'Finish Product');
        $dept=Departments::pluck('name','id')->prepend('Select Department','');

        return view('products.index', compact('catagories', 'uoms', 'warehouses', 'pack_type', 'product_type','dept'));
    }

    public function PrintProducts(Request $request){
        // return "hello";
        $Warehouse = $request->Warehouse_ID;
        $warehouseData = Warehouse::where('id', $Warehouse)->first('name');
        // return $warehouseData;
        if($Warehouse != 0){

                $allproducts = Catagory::with(['products' => function ($query) use ($Warehouse) {
                $query->where('warehouse_id', $Warehouse);
                $query->select('category_id', 'code', 'product_name', 'uom', 'pack_type', 'packing');
                $query->OrderBy('code', 'asc');
                // $query->with('warehouse:id,name');
                }])
                ->select('id', 'catagory_code', 'catagory_name')
                ->orderBy('catagory_code', 'asc')
                ->get();
        }else{
            $allproducts = Catagory::with(['products' => function($query) use ($Warehouse){
                // $query->with('warehouse:id,name');
                // $query->where('warehouse_id', $Warehouse);
                $query->select('category_id', 'code', 'product_name', 'uom', 'pack_type', 'packing');
                $query->OrderBy('code', 'asc');
            }])
            //  ->where('id', '<', 40)
            ->select('id', 'catagory_code', 'catagory_name')
                ->orderBy('catagory_code', 'asc')
                ->get();
        } 
        // return $allproducts;
            $pdf = PDF::loadView('products.print', compact('allproducts', 'warehouseData'));
            $fileName =  'products.pdf';
            $pdf->save(base_path('upload/products/' . $fileName));
            return $fileName;

    }

    public function WareHouseProducts(Request $request){
        // return $request;
            return $products = Product::
         //    with('warehouse:id,name')
                 // ->
                //  with('category:id,catagory_name')
                 with('category:id,catagory_name')
                 ->where('warehouse_id', $request->Warehouse_ID)
                 ->OrderBy('code', 'asc')
                 ->get();
        //  }
    }

    public function store(Request $request)
    {

    //     $data = array('name'=>"Virat Gandhi");
   
    //   Mail::send(['text'=>'products.mail'], $data, function($message) {
    //      $message->to('sammarforu@gmail.com', 'Tutorials Point')->subject
    //         ('Laravel Basic Testing Mail');
    //      $message->from('xyz@gmail.com','Virat Gandhi');
    //   });
    //   return "Basic Email Sent. Check your inbox.";

    // 1737
    //     $data = array('name'=>"Virat Gandhi");
    //   Mail::send('products.mail', $data, function($message) {
    //      $message->to('sammarforu@gmail.com', 'Tutorials Point')->subject
    //         ('Laravel HTML Testing Mail');
    //      $message->from('sammarforu@gmail.com','Virat Gandhi');
    //   });
    //   return "HTML Email Sent. Check your inbox.";

        // $details = [
        //     'title' => 'Title: Mail from Talha Farooq',
        //     'body' => 'Body: This is for testing email using smtp'
        // ];

        // Mail::to('sammarforu@gmail.com')->send(new SendMail($details));
        // return "Send";
        //      return "Your email has been sent successfully!!!";
        // return $request;
        // Product::delete()->all();
        // return "done";
       
        //    return $request;
        $this->validate($request, [
            // 'product_code' => 'required',
            'product_name' => 'required',
            'category_id' => 'required',
            'uom' => 'required',
            'pack_type' => 'required',
            'warehouse_id' => 'required',
            'department_id' => 'required',
            'category_id' => 'required',
            'product_cost' => 'required',
            'product_price' => 'required',
            'tax' => 'required',
            'product_type' => 'required'
        ], [
            'warehouse_id.required' => 'The Godown field is required',
            'department_id.required' => 'The Department field is required',
            'category_id.required' => 'The Category field is required',
            'uom.required' => 'The unit field is required',
            'category_id.required' => 'The category field is required',
            'product_type.required' => 'The Product Type field is required'
        ]);
        // return $request;
        if ($request->idd != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PRODUCT INFORMATION')
            ->where('right_name', 'EDIT')
            ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            //copy data
            if ($request->copydata == 1) {
                $data = $request->all();
                $data['created_by'] = Auth::User()->id;
                // if($request->dye_pcs == null)
                // {
                //     $data['dye_pcs'] = 0;
                // }
                $WarehouseCode=Warehouse::where('id',$request->warehouse_id)->first('code');
                $LastProduct=Product::where('warehouse_id',$request->warehouse_id)
                                        ->Orderby('code', 'desc')
                                        ->first();

                  $product = Product::create($data);
                  $CalculatedCode =1;
                    if(($LastProduct)){
                        // if(($LastProduct->product_code) != null)
                        // {
                            $CalculatedCode = $LastProduct->product_code+1;
                            $product->code= $WarehouseCode->code.$CalculatedCode;
                            $product->product_code= $CalculatedCode;
                            $product->save();
                        // }
                    }
                    else{
                        $product->code= $WarehouseCode->code.$CalculatedCode;
                        $product->product_code= $CalculatedCode;
                        $product->save();
                    }
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $fileExtension = $file->getClientOriginalExtension();
    
                    $fileName = uniqid() . '.' . $fileExtension;
                    $file->move('root/upload/products', $fileName);
                    $product->image = $fileName;
                    $product->save();
                }
    
                return redirect()->back()->with('flash_message', $product->code ." - Product Added Successfully");
            }else{
                //  return $request;
                $data = $request->all();
                $data['updated_by'] = Auth::User()->id;
                $edit1 = Product::find($request->idd);
                $edit = Product::find($request->idd);


                 $WarehouseCode=Warehouse::where('id',$request->warehouse_id)->first('code');
                 $LastProduct=Product::where('warehouse_id',$request->warehouse_id)
                                // ->where('category_id',$request->category_id)
                                ->Orderby('code', 'desc')
                                ->first();
                    if($edit->warehouse_id != $request->warehouse_id)
                    {
                        $CalculatedCode =1;
                        if($LastProduct){
                          $CalculatedCode = $LastProduct->product_code+1;
                          $edit->code= $WarehouseCode->code.$CalculatedCode;
                          $edit->product_code= $CalculatedCode;
                          $edit->save();
                        }
                        else{
                            $edit->code= $WarehouseCode->code.$CalculatedCode;
                            $edit->product_code= $CalculatedCode;
                            $edit->save();
                        } 
                    }
                    // if($edit->category_id != $request->category_id)
                    // {
                    //     if($product){
                    //         $edit->code= $product->code+1;
                    //         $edit->save();
                    //         }
                    //     else{
                    //         $productCode = 1;
                    //         $edit->code = $WarehouseCode->code.$CategoryCode->catagory_code.$productCode;
                    //         // $edit->code= $edit->code;
                    //         $edit->save();
                    //     }  
                    // }
                $edit->update($data);
                if ($request->hasFile('image')) {
                    File::delete(base_path('upload/products/' . $edit1->image));
                    $file = $request->file('image');
                    $fileExtension = $file->getClientOriginalExtension();
                    $fileName = uniqid() . '.' . $fileExtension;
                    $file->move(base_path('/upload/products'), $fileName);
                    $edit->image = $fileName;
                    $edit->save();
                }
    
                return redirect()->back()->with('flash_message', $edit->code ." - Product Updated Successfully");
            }
          
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'PRODUCT INFORMATION')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
           
            $data = $request->all();
            $data['created_by'] = Auth::User()->id;
            // if($request->dye_pcs == null)
            // {
            //     $data['dye_pcs'] = 0;
            // }
            
            $WarehouseCode=Warehouse::where('id',$request->warehouse_id)->first('code');
            // $CategoryCode=Catagory::where('id',$request->category_id)->first('catagory_code');
             $productfetched=Product::
            where('warehouse_id',$request->warehouse_id)
            // ->where('category_id',$request->category_id)
            ->Orderby('code', 'desc')
            ->first('product_code');
            // return $productfetched->product_code;
            // 11.23.1
              $product = Product::create($data);
              $CalculatedCode =1;
                if($productfetched){
                    $CalculatedCode = $productfetched->product_code+1;
                }
                // $product->code= $WarehouseCode->code.$CategoryCode->catagory_code.$CalculatedCode;
                $product->code= $WarehouseCode->code.$CalculatedCode;
                $product->product_code= $CalculatedCode;
                $product->save();
            
                //image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileExtension = $file->getClientOriginalExtension();

                $fileName = uniqid() . '.' . $fileExtension;
                $file->move('root/upload/products', $fileName);
                $product->image = $fileName;
                $product->save();
            }

            // Mail::send('mail/SendMail', $request->base_url, function ($message) use($request) {
            //     // $message->from($request->email,$request->first_name);
            //      $message->from('sammarforu@gmail.com', "iT Life");
            //      $message->to('sammarforu@gmail.com')->subject("SPI Mail");
        
            //  });
        //     $email = "sammarforu@gmail.com";
        //     Mail::to($email)->send(new SendMail($email));
        //     return new JsonResponse([
        //         'success' => true,
        //         'message' => 'sended'
        //     ], 200
        // );

            return redirect()->back()->with('flash_message', $product->code ." - Product Added Successfully");
        }

        return redirect()->back()->with('error_message', "Something went wrong!!!");
    }

    public function destroy($id)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PRODUCT INFORMATION')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        $product = Product::findOrFail($id);
        File::delete('root/upload/products/' . $product->image);
        Product::findOrFail($id)->delete();
        return redirect()->back()->with('flash_message', "Product Deleted Successfully");
    }
    public function deptt(Request $request)
    { 
         $dept = Departments::where('warehouse_id', $request->godownId)->get(['id','name']);
         return json_encode($dept);
    }
}