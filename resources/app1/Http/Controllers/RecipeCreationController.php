<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\RecipeCreation;
use App\Models\Party;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\RecipeCreationDetails;
use App\Models\Role;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class RecipeCreationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $party = Party::with('user')->whereRole('Supplier')->OrderBy('code', 'asc')->get();

            return DataTables::of($party)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if($row->user){
                        $useremail=$row->user->email;
                    }
                    else {
                        $useremail = '';
                    }
                    $btn = '<div class="btn-group">
                                <button type="button" name="' . $row->id . '_' . $row->party_name . '_' . $row->address . '_' . $row->phone . '_' . $row->city . '_' . $row->ntn . '_' . $row->strn . '_' . $row->status . '_' . $row->type . '_' . $row->account_group_id3 . '_' .$useremail.'" class="btn btn-primary edit_btn btn-sm"><i class="fa fa-pencil"></i></button>&nbsp;
                                <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-account"><i class="fa fa-trash"></i></a>
                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        // $code = RecipeCreation::where('warehouse_id', Auth::User()->warehouse_id)->orderBy('id', 'desc')->first();
        
        $code = RecipeCreation::orderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->voucher_no + 1;
        }
        // return $finish_gooods = Product::select(DB::raw('CONCAT(`id`, "_", `code`, "_", `product_name`, "_", `uom`) AS `id`, `product_code`, `product_name`, `uom`'))
         $finish_gooods = Product::select(DB::raw('CONCAT(`id`, "_", `code`, "_", `product_name`, "_", `uom`) AS `id`, CONCAT(`code`,"-",`product_name`) `product_name`'))
            ->where('product_type', 'Finish')
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('code', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Recipe', '');

            // $finish_gooods = Product::select(DB::raw('CONCAT(`id`, "_", `product_name`) AS `id`, CONCAT(`product_name`,"-",`pack_type`,"-",`uom`) `product_name`'))
            // ->where('product_type', 'Finish')
            // ->OrderBy('product_name', 'asc')
            // ->pluck('product_name', 'id')
            // ->prepend('Select Recipe', '');


        //  $products = Product::where('product_type', 'Normal')
        //     // ->where('warehouse_id', Auth::User()->warehouse_id)
        //     ->OrderBy('product_name', 'asc')
        //     ->pluck('product_name', 'id')
        //     ->prepend('Select Product', '');

            $products = Product::select(DB::raw('CONCAT(`id`) AS `id`, CONCAT(`code`,"-",`product_name`) `product_name`'))
            ->where('product_type', 'Normal')
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('code', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');
        $warehouse = Warehouse::pluck('name', 'id')->prepend('Select Warehouse', '');

        return view('recipe-creation.index', compact('codes', 'finish_gooods', 'products', 'warehouse'));
    }

    public function store(Request $request)
    {
       
        $Validator = Validator::make($request->all(), [
            'voucher_no' => 'required',
            'date' => 'required',
            'warehouse_id' => 'required',
            'product_id2' => 'required'
        ], [
            'product_id2.required' => 'The product field is required.'
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator);
        }


        if (!isset($request->voucher_no)) {
            return redirect()->back()->with('failure_message', 'Please Enter Voucher No');
        }
        if (!isset($request->product_id)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }
        if (!isset($request->rate)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }

        if ($request->update_voucher_id != null) {
            $recipeCreation = RecipeCreation::find($request->update_voucher_id);

            $data = $request->all();
            $data['unit_id'] = 1;
            $data['product_id'] = explode('_', $request->product_id2)[0];
            $data['updated_by'] = Auth::User()->id;
            $recipeCreation->update($data);

            RecipeCreationDetails::where('recipe_creation_id', $recipeCreation->id)->delete();
            $count = count($request->rate);
            for ($i = 0; $i < $count; $i++) {
                $recipe_create_details = new RecipeCreationDetails();
                $recipe_create_details->recipe_creation_id = $recipeCreation->id;
                $recipe_create_details->voucher_no = $recipeCreation->voucher_no;
                $recipe_create_details->product_id = $request->product_id[$i];
                $recipe_create_details->unit_id = 1;
                $recipe_create_details->quantity = $request->qty[$i];
                $recipe_create_details->wastage = $request->wastage[$i];
                $recipe_create_details->warehouse_id = $request->warehouse_id;
                $recipe_create_details->rate = $request->rate[$i];
                $recipe_create_details->amount = $request->total[$i];
                $recipe_create_details->created_by = Auth::User()->id;
                $recipe_create_details->updated_by = Auth::User()->id;
                $recipe_create_details->save();
            }

            return redirect()->back()->with('flash_message', 'Recipe Updated Successfully!');
        } else {

            // $code = RecipeCreation::where('warehouse_id', Auth::User()->warehouse_id)->orderBy('id', 'desc')->first();
            $code = RecipeCreation::orderBy('id', 'desc')->first();
            $codes = 1;
            if ($code) {
                $codes = $code->voucher_no + 1;
            }

            $data = $request->all();
            $data['voucher_no'] = $codes;
            $data['unit_id'] = 1;
            $data['product_id'] = explode('_', $request->product_id2)[0];
            $data['created_by'] = Auth::User()->id;
            $recipeCreation = RecipeCreation::create($data);

            $totalCost = 0;
            $count = count($request->rate);
            for ($i = 0; $i < $count; $i++) {
                $recipe_create_details = new RecipeCreationDetails();
                $recipe_create_details->recipe_creation_id = $recipeCreation->id;
                $recipe_create_details->voucher_no = $recipeCreation->voucher_no;
                $recipe_create_details->product_id = $request->product_id[$i];
                $recipe_create_details->warehouse_id = $request->warehouse_id;
                $recipe_create_details->unit_id = 1;
                $recipe_create_details->quantity = $request->qty[$i];
                $recipe_create_details->wastage = $request->wastage[$i];
                $recipe_create_details->rate = $request->rate[$i];
                $recipe_create_details->amount = $request->total[$i];
                $recipe_create_details->created_by = Auth::User()->id;
                $recipe_create_details->save();
            }

            return redirect()->back()->with('flash_message', 'Recipe Created Successfully!');
        }
    }

    public function editData(Request $request)
    {
        $edit = RecipeCreation::where('voucher_no', $request->voucher_no)->first();
        // $edit = RecipeCreation::where('voucher_no', $request->voucher_no)->where('warehouse_id', Auth::User()->warehouse_id)->first();

        if ($edit) {
            $edit = RecipeCreationDetails::with('product:id,product_name,uom,code')
                ->with(['recipe_creation' => function ($query) {
                    $query->with('product');
                }])
                ->where('recipe_creation_id', $edit->id)
                // ->where('warehouse_id', Auth::User()->warehouse_id)
                ->get();

            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        $recipeCreationVoucherNo = RecipeCreation::where('voucher_no', '<', $request->voucher_no)
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->max('voucher_no');

        if ($recipeCreationVoucherNo) {
            $data = RecipeCreationDetails::with('product:id,product_name,uom,code')
                ->with(['recipe_creation' => function ($query) {
                    $query->with('product');
                }])
                ->where('voucher_no', $recipeCreationVoucherNo)
                // ->where('warehouse_id', Auth::User()->warehouse_id)
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $recipeCreationVoucherNo = RecipeCreation::where('voucher_no', '>', $request->voucher_no)
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->min('voucher_no');

        if ($recipeCreationVoucherNo) {
            $data = RecipeCreationDetails::with('product:id,product_name,uom,code')
                ->with(['recipe_creation' => function ($query) {
                    $query->with('product');
                }])
                ->where('voucher_no', $recipeCreationVoucherNo)
                // ->where('warehouse_id', Auth::User()->warehouse_id)
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function DeleteVoucher(Request $request)
    {
        // $redipeCreation = RecipeCreation::where('voucher_no', $request->delete_voucher_no)->where('warehouse_id', Auth::User()->warehouse_id)->first();
        $redipeCreation = RecipeCreation::where('voucher_no', $request->delete_voucher_no)->first();
        if ($redipeCreation) {
            RecipeCreation::findOrFail($redipeCreation->id)->delete();
            RecipeCreationDetails::where('recipe_creation_id', $redipeCreation->id)->where('warehouse_id', Auth::User()->warehouse_id)->delete();

            return redirect()->back()->with('flash_message', 'Recipe Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }
    public function PrintVoucher(Request $request)
    {
        File::cleanDirectory(base_path() . '/upload/recipe-creation');
        $voucher_no = $request->voucher_no;
        // $RecipeCreation = RecipeCreation::where('voucher_no', $voucher_no)->where('warehouse_id', Auth::User()->warehouse_id)->first();
        $RecipeCreation = RecipeCreation::where('voucher_no', $voucher_no)->first();
        if ($RecipeCreation) {
            $recipecreationdetails = RecipeCreationDetails::with(['recipe_creation' => function ($qry) {
                $qry->with('product:id,product_name,uom,code');
                $qry->with('generated_by:id,name');
            }])
                ->with('product:id,product_name,uom,code')
                ->where('recipe_creation_id', $RecipeCreation->id)
                // ->where('warehouse_id', Auth::User()->warehouse_id)
                ->orderBy('id', 'asc')
                ->get();

            $pdf = PDF::loadView('recipe-creation.invoice', compact('recipecreationdetails'));
            $fileName =  'recipe-creation' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/recipe-creation/' . $fileName));
            return $fileName;
        } else {
            return false;
        }
    }
    public function ProductRecord(Request $request)
    {
        $Product = Product::select('id', 'code', 'product_name', 'uom', 'product_price', 'product_cost')
        ->where('id', $request->product_id)
        ->OrderBy('code', 'asc')
        ->get();
        return json_encode($Product);
    }
    public function WarehouseProduct(Request $request)
    { 
         $WarehouseProduct = Product::where('warehouse_id', $request->warehouse_id) 
         ->where('product_type', 'Finish')
         ->OrderBy('code', 'asc')
         ->get();
         return json_encode($WarehouseProduct);
    }
}
