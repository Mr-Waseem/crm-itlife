<?php

namespace App\Http\Controllers;

use App\Models\VoucherNames;
use App\Models\Party;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VoucherNamesController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $voucherNames = VoucherNames::select('code', 'voucher_name')->orderBy('code', 'asc')->get();

            return DataTables::of($voucherNames)
                ->addIndexColumn()
                ->make(true);
        }
        $codes = 1;
        $voucherNames = VoucherNames::orderBy('id', 'desc')->first();
        if ($voucherNames) {
            $codes = $voucherNames->code + 1;
        }

        return view('voucher-name.index', compact('codes'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'voucher_name' => 'required'
        ]);
        VoucherNames::create($request->all());
        return redirect()->back()->with('flash_message', 'New Voucher Added Successfully');
    }public function vnames(){
        $data = Party::Orderby('id', 'asc')->delete();
        return "Done";
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VoucherNames  $voucherNames
     * @return \Illuminate\Http\Response
     */
    public function show1(VoucherNames $voucherNames)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VoucherNames  $voucherNames
     * @return \Illuminate\Http\Response
     */
    public function edit(VoucherNames $voucherNames)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VoucherNames  $voucherNames
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VoucherNames $voucherNames)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VoucherNames  $voucherNames
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoucherNames $voucherNames)
    {
        //
    }
}