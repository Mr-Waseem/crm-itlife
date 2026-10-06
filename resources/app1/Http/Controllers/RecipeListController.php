<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\AccountGroup3;
use App\Models\RecipeCreation;
use DB;
class RecipeListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //  return $reques;
        // if ($request->ajax()) {
        //     return $warehouse_id = $request->warehouse_id;

        //         if($warehouse_id==0){
        //         return "1";


        //         // return Response::json($stockReportSummaryWise);
        //   }else{
        //     return "single";
        //   }
        //   }
          $warehouse = Warehouse::select(DB::raw('CONCAT(`id`,"_",`name`) as id,name'))->orderBy('name')->pluck('name', 'id')->prepend('All', 0);
        return view('recipe-creation.recipe-list.index', Compact('warehouse'));
    }

    public function LoadList(Request $request){
        // return $request;
        $warehouse = $request->warehouse_id;
        // $warehouses = Warehouse::where('id', $warehouse)->first();
        if($warehouse == 0){
            return $data = RecipeCreation::with('product')->with('generated_by')->with('warehouse')->OrderBy('warehouse_id', 'asc')->get();
        }else{
            return $data = RecipeCreation::with('product')->with('generated_by')->where('warehouse_id',  $warehouse)
                        ->OrderBy('id', 'asc')->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
