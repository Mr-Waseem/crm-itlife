<?php

namespace App\Http\Controllers;

use App\Http\Requests\RateListStoreValidate;
use App\Models\Catagory;
use App\Models\Product;
use App\Models\RateList;
use App\Models\RateListDetails;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Validation\Rule;

class RateListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $products = Product::where('product_type', 'Finish')
        ->orderby('product_code', 'asc')
        ->get();
        $voucher_no = 1;
        $rate_list = RateList::orderBy('id', 'desc')->first();
        if ($rate_list) {
            $voucher_no = $rate_list->voucher_no + 1;
        }

        return view('rate-list.index', compact('products', 'voucher_no'));
    }

    public function store(RateListStoreValidate $request)
    {
        //return $request->all();
        if ($request->idd != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'RATE LIST')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            
           $rate = RateList::find($request->idd);
            if (!$rate) {
                return redirect()->back()->with('error_message', 'Voucher Not Exist!');
            }
            for ($i = 0; $i < count($request->pro_id); $i++) {
                // Product::find($request->pro_id[$i])->update([
                //     'product_price' => $request->new_rate[$i] != null ? $request->new_rate[$i] : 0,
                //     // 'packing' => $request->packing[$i],
                //     // 'remarks' => $request->remarks[$i]
                // ]);
                $product = Product::where('id',$request->pro_id[$i])->first();
                $product->product_price = $request->new_rate[$i] != null ? $request->new_rate[$i] : 0;
                $product->tax = $request->tax_rate;
                $product->save();
            }
            
            $rate_list = RateList::find($request->idd)->update([
                'voucher_no' => $request->voucher_no,
                'voucher_date' => $request->voucher_date,
                'tax_rate' => $request->tax_rate,
                'updated_by' => Auth::user()->id
            ]);
            
            RateListDetails::where('rate_list_id', $request->idd)->delete();
            // return "ok";
            for ($i = 0; $i < count($request->pro_id); $i++) {
                RateListDetails::create([
                    'rate_list_id' => $request->idd,
                    'product_id' => $request->pro_id[$i],
                    'previous_rate' => $request->previous_rate1[$i] != null ? $request->previous_rate1[$i] : 0,
                    'new_rate' => $request->new_rate[$i] != null ? $request->new_rate[$i] : 0,
                    // 'packing' => $request->packing[$i] != null ? $request->packing[$i] : 0,
                    // 'remarks' => $request->remarks[$i] != null ? $request->remarks[$i] : null,
                    // 'remarks' => $request->remarks[$i],
                    'voucher_date' => $rate->voucher_date,
                    'tax_rate' => $request->tax_rate,
                ]);
            }

            return redirect()->back()->with('flash_message', 'Rate Voucher Updated Successfully');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'RATE LIST')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            for ($i = 0; $i < count($request->pro_id); $i++) {
                $product = Product::where('id',$request->pro_id[$i])->first();
                $product->product_price = $request->new_rate[$i] != null ? $request->new_rate[$i] : 0;
                $product->tax = $request->tax_rate;
                $product->save();
            }

            $rate_list = RateList::create($request->only('voucher_no', 'voucher_date', 'tax_rate'));
            for ($i = 0; $i < count($request->pro_id); $i++) {
                RateListDetails::create([
                    'rate_list_id' => $rate_list->id,
                    'product_id' => $request->pro_id[$i],
                    'previous_rate' => $request->previous_rate1[$i] != null ? $request->previous_rate1[$i] : 0,
                    'new_rate' => $request->new_rate[$i] != null ? $request->new_rate[$i] : 0,
                    // 'packing' => $request->packing[$i] != null ? $request->packing[$i] : 0,
                    // 'remarks' => $request->remarks[$i] != null ? $request->remarks[$i] : null,
                    'voucher_date' => $rate_list->voucher_date,
                    'tax_rate' => $rate_list->tax_rate,
                ]);
            }

            return redirect()->back()->with('flash_message', 'Rate Voucher Added Successfully');
        }
    }

    public function editData(Request $request)
    {
        $edit = RateList::with(['rate_list_details' => function ($query) {
            $query->with('product:id,code,product_name,product_price,packing,remarks');
        }])->where('voucher_no', $request->voucher_no)->first();

        if ($edit) {
            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        $voucher_no = RateList::where('voucher_no', '<', $request->voucher_no)->max('voucher_no');
        if ($voucher_no != 0 || $voucher_no != null) {
            $data = RateList::with(['rate_list_details' => function ($query) {
                $query->with('product:id,code,product_name,product_price,packing,remarks');
            }])->where('voucher_no', $voucher_no)->first();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $voucher_no = RateList::where('voucher_no', '>', $request->voucher_no)->min('voucher_no');
        if ($voucher_no != 0 || $voucher_no != null) {
        // if ($voucher_no > 0) {
            $data = RateList::with(['rate_list_details' => function ($query) {
                $query->with('product:id,code,product_name,product_price,packing,remarks');
            }])->where('voucher_no', $voucher_no)->first();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function PrintVoucher(Request $request)
    {
        File::cleanDirectory(base_path() . '/upload/rate-list');
        $rate_list = RateList::where('voucher_no', $request->voucher_no)->first();
        if ($rate_list) {
            $data = RateList::with(['rate_list_details' => function ($query) {
                $query->with('product:id,product_code,product_name,product_price,packing,remarks');
            }])->where('voucher_no', $request->voucher_no)->first();



            $pdf = PDF::loadView('rate-list.invoice', compact('data'));
            $fileName =  'rate-list-' . $request->voucher_no . '.pdf';
            $pdf->save(base_path('upload/rate-list/' . $fileName));
            return $fileName;
        } else {
            return false;
        }
    }


    public function DeleteVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'RATE LIST')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        $rate = RateList::where('voucher_no', $request->delete_voucher_id)->first();
        if ($rate) {
            $rate->rate_list_details()->delete();
            $rate->delete();
            return redirect()->back()->with('flash_message', 'Rate Voucher Deleted Successfully');
        } else {
            return redirect()->back()->with('error_message', 'Something went wrong');
        }
    }

    public function Report(Request $request)
    {
        $orderByTypes = array('' => 'Select Order Type', 1 => 'Order By Code', 2 => 'Order By Name');
        $categories = Catagory::pluck('catagory_name', 'id')->prepend('Select Category', '');
        $products = Product::whereProductType('Finish')->pluck('product_name', 'id')->prepend('Select Product', '');
        return view('rate-list.report', compact('orderByTypes', 'categories', 'products'));
    }

    public function OrderByReport(Request $request)
    {
        $this->validate($request, [
            'order_by' => ['required', Rule::in([1, 2])]
        ]);

        $orderBy = '';
        if (intval($request->order_by) == 1) {
            $orderBy = 'code';
        }
        if (intval($request->order_by) == 2) {
            $orderBy = 'product_name';
        }

        $data = Product::select('id', 'code', 'product_name')
            ->with(['rate_list_details' => function ($query) {
                $query->select('id', 'rate_list_id', 'product_id', 'previous_rate', 'new_rate', 'remarks');
                $query->with('rate_list:id,voucher_no,voucher_date');
            }])
            ->whereProductType('Finish')
            ->orderBy($orderBy)
            ->get();

        $pdf = PDF::loadView('rate-list.order_by_report', compact('data'));
        return $pdf->download('rate-list-report.pdf');
    }

    public function CategoryProductsReport(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required'
        ]);

        $category = Catagory::findOrFail($request->category_id);
        if ($category) {
            $data = Product::select('id', 'code', 'product_name')
                ->with(['rate_list_details' => function ($query) {
                    $query->select('id', 'rate_list_id', 'product_id', 'previous_rate', 'new_rate', 'remarks');
                    $query->with('rate_list:id,voucher_no,voucher_date');
                }])
                ->whereCategoryId($request->category_id)
                ->get();

            $pdf = PDF::loadView('rate-list.category_products_report', compact('data'));
            return $pdf->download('rate-list-category-products-report.pdf');
        }
        abort(404);
    }

    public function SingleProductReport(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required'
        ]);

        $product = Product::findOrFail($request->product_id);
        if ($product) {
            $data = Product::select('id', 'code', 'product_name')
                ->with(['rate_list_details' => function ($query) {
                    $query->select('id', 'rate_list_id', 'product_id', 'previous_rate', 'new_rate', 'remarks');
                    $query->with('rate_list:id,voucher_no,voucher_date');
                }])
                ->whereId($request->product_id)
                ->get();

            $pdf = PDF::loadView('rate-list.single_product_report', compact('data'));
            return $pdf->download('rate-list-single-products-report.pdf');
        }
        abort(404);
    }
}
