@extends('app')
@section('head')
    <title>Direct SalesTax Invoice</title>
    <!--  Select 2 library start-->
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!--  Select 2 library end-->
@stop
@section('content')
    @if (isset($data))

        <body onload="loadeditSale()">
    @endif
    <!-- Sticky Left Side bar -->
    <div class="icon-bar">
        <a href="javascript:void(0);" class="load-previous-record"><i class="fa fa-angle-left"></i></a>
        <a href="javascript:void(0);" class="load-edit-record"><i class="fa fa-repeat"></i></a>
        <a href="javascript:void(0);" class="load-next-record"><i class="fa fa-angle-right"></i></a>
        <a href="javascript:void(0);" class="delete_record_btn"><i class="fa fa-trash-o"></i></a>
        <a href="javascript:void(0);" class="print_record_btn"><i class="fa fa-print"></i></a>
        <a href="javascript:void(0);" class="print_dc_btn" title="Print DC"><i class="fa fa-truck"></i></a>
    </div>
    <!-- End Sticky Left Side bar -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                SalesTax Invoice
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">SalesTax Invoice</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <!-- <div class="box-header with-border">
                                <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> SalesTax Invoice</h6>
                                <ul class="box-controls pull-right">
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                </ul>
                            </div> -->
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    @if (Session::has('failure_message'))
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">×</button> {{ Session::get('failure_message') }}
                                        </div>
                                    @endif
                                    <div id="show_err"></div>
                                    {!! Form::open(['url' => 'direct-saletax-invoice', 'class' => 'form-horizontal', 'id' => 'salestax-invoice-form']) !!}
                                    {!! Form::hidden('update_voucher_id', null, ['id' => 'update_voucher_id']) !!}
                                    {!! Form::hidden('dcn_id1', null, ['id' => 'dcn_id1']) !!}
                                    {{-- {!! Form::hidden('party_id', null, ['id' => 'party_id']) !!} --}}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    <!-- <div class="row">
                                            <div class="col-lg-2 col-md-12 col-12">
                                                <button class="btn btn-primary submit-form" type="button">Save</button>
                                                <button class="btn btn-secondary reset-btn" type="reset">Reset</button>
                                            </div>
                                            <div class="col-lg-3 col-md-12 col-12">
                                                <div class="note note-danger">UnPosted By :</div>
                                            </div>
                                            <div class="col-lg-3 col-md-12 col-12">
                                                <div class="note note-warning">Posted By : {{ Auth::User()->name }}</div>
                                            </div>
                                            <div class="col-lg-3 col-md-12 col-12">
                                                <div class="note note-info">Updated By : <span class="d-none"
                                                        id="updated_by_name"> {{ Auth::User()->name }}</span></div>
                                            </div>
                                        </div> -->

                                    <div class="row">

                                       
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="date"><i class="fa fa-caret-right"></i> Voucher Date</label>
                                            {!! Form::date('date', date('Y-m-d'), [
                                                'id' => 'date',
                                                'class' => 'form-control',
                                                'tabindex' => '0',
                                                'required' => 'required',
                                                'autofocus' => 'autofocus',
                                            ]) !!}
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @if(isset($data))
                                        <input type="hidden" id="userrole" name="userrole" value="{{Auth::User()->role}}">
                                        <div class="col-lg-3 col-md-12 col-sm-12 mt-1">
                                            <label for="account_id"><i class="fa fa-caret-right"></i>Select Warehouse.
                                                <span class="text-danger">*</span></label>
                                            {!! Form::select('warehouse_id', $warehouse, $data->warehouse_id, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                            ]) !!}
                                        </div>
                                        @if(Auth::User()->role == "Admin")
                                        <div class="col-lg-1 col-md-12 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>Vr#.<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $data->voucher_no, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                                'tabindex' => '1',
                                            ]) !!}
                                        </div>
                                        @else
                                        <div class="col-lg-1 col-md-12 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>Vr#.<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $data->voucher_no, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                                'tabindex' => '1',
                                            ]) !!}
                                        </div>
                                        @endif
                                        @else
                                        <input type="hidden" id="userrole" name="userrole" value="{{Auth::User()->role}}">
                                        <div class="col-lg-3 col-md-12 col-sm-12 mt-1">
                                            <label for="account_id"><i class="fa fa-caret-right"></i>Select Warehouse.
                                                <span class="text-danger">*</span></label>
                                            {!! Form::select('warehouse_id', $warehouse, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                            ]) !!}
                                        </div>
                                        @if(Auth::User()->role == "Admin")
                                        <div class="col-lg-1 col-md-12 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>Vr#.<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', null, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                                'tabindex' => '1',
                                            ]) !!}
                                        </div>
                                        @else
                                        <div class="col-lg-1 col-md-12 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>Vr#.<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $codes, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                                'tabindex' => '1',
                                            ]) !!}
                                        </div>
                                        @endif
                                        @endif
                                        <div class="col-lg-3 col-md-6 col-sm-12 mt-1 dcn_no1 d-none">
                                            <label for="dcn_id" style="background-color:#666ee8;color:white;"><i
                                                    class="fa fa-caret-right"></i> DC No(Gst).<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('dcn_id', $DeliveryChallan, null, [
                                                'id' => 'dcn_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '4',
                                            ]) !!}
                                            <span class="text-danger dcn_id_err"></span>
                                            @error('dcn_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1 dcn_no2 d-none">
                                            <label for="dcn_id"><i class="fa fa-caret-right"></i> DC No.<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('dcn_id', null, [
                                                'id' => 'dcn_id',
                                                'class' => 'form-control',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            @error('dcn_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                            <label for="party_name"><i class="fa fa-caret-right"></i> Party Name<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('party_name', $customers, null, [
                                                'id' => 'party_name',
                                                'class' => 'form-control select2 customer',
                                                'tabindex' => '2',
                                                // 'disabled' => 'disabled',
                                            ]) !!}
                                            {!! Form::hidden('party_id', null, ['id' => 'party_id']) !!}
                                            {{-- <span class="text-danger party_name_err"></span>
                                            @error('party_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror --}}
                                        </div>
                                        <!-- <div class="col-lg-1 col-md-6 col-sm-12 mt-1 ReloadOrder d-none"> -->
                                            <!-- <label for="grn_no"><i class="fa fa-caret-right"></i>Reload<span
                                                    class="text-danger">*</span></label> -->
                                            <!-- <a href="#"> -->
                                                <!-- <div class="input-group"> -->
                                                    <!-- <div class="input-group-addon bg-primary border-primary"
                                                        onclick="refresh();">
                                                        <i class="fa fa-th-list"></i>
                                                    </div> -->
                                                    <!-- </div> -->
                                            <!-- </a> -->
                                        <!-- </div> -->
                                   
                                    <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                        <label for="remarks"><i class="fa fa-caret-right"></i> Remarks<span
                                                class="text-danger">*</span></label>
                                        {!! Form::text('remarks', null, [
                                            'id' => 'remarks',
                                            'class' => 'form-control',
                                            'tabindex' => '5',
                                            'placeholder' => 'Remarks',
                                        ]) !!}
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                        <label for="vehicle_no"><i class="fa fa-caret-right"></i> Vehicle No <span
                                                class="text-danger">*</span></label>
                                        {!! Form::text('vehicle_no ', null, [
                                            'id' => 'vehicle_no',
                                            'class' => 'form-control',
                                            'tabindex' => '3',
                                            // 'disabled' => 'disabled',
                                        ]) !!}
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                        <label for="transport_company"><i class="fa fa-caret-right"></i> Transport
                                            Company<span class="text-danger">*</span></label>
                                        {!! Form::text('transport_company', null, [
                                            'id' => 'transport_company',
                                            'class' => 'form-control',
                                            'tabindex' => '3',
                                            // 'disabled' => 'disabled',
                                        ]) !!}
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                        <label for="driver_name"><i class="fa fa-caret-right"></i> Driver Name<span
                                                class="text-danger">*</span></label>
                                        {!! Form::text('driver_name', null, [
                                            'id' => 'driver_name',
                                            'class' => 'form-control',
                                            'tabindex' => '3',
                                            // 'disabled' => 'disabled',
                                        ]) !!}
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                        <label for="builty_no"><i class="fa fa-caret-right"></i> PO No<span
                                                class="text-danger">*</span></label>
                                        {!! Form::text('builty_no', null, [
                                            'id' => 'builty_no',
                                            'class' => 'form-control',
                                            'tabindex' => '3',
                                            // 'disabled' => 'disabled',
                                        ]) !!}
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                        <label for="freight"><i class="fa fa-caret-right"></i> Freight<span
                                                class="text-danger">*</span></label>
                                        {!! Form::text('freight', null, [
                                            'id' => 'freight',
                                            'class' => 'form-control',
                                            'tabindex' => '3',
                                            'onkeyup' => 'updateGrandTotal()',
                                            'onkeypress' => 'return isNumberKey(event)',
                                            // 'disabled' => 'disabled',
                                        ]) !!}
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                        <label for="driver_phoneno"><i class="fa fa-caret-right"></i> Driver
                                            PhoneNo<span class="text-danger">*</span></label>
                                        {!! Form::text('driver_phoneno', null, [
                                            'id' => 'driver_phoneno',
                                            'class' => 'form-control',
                                            'tabindex' => '3',
                                            // 'disabled' => 'disabled',
                                        ]) !!}
                                    </div>
                                  
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="table-responsive-md mb-2">
                                            <table class="table">
                                                <thead>
                                                    <tr class="bg-primary text-left">
                                                        <th class="d-none">Code</th>
                                                        <th style="width: 25%;">Product Name</th>
                                                        <th>Packing</th>
                                                        <th>Unit</th>
                                                        <th>Qty</th>
                                                        <th>Rate</th>
                                                        <th>Excl.val</th>
                                                        <th>ST Rate</th>
                                                        <th>Sale Tax</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="bg-secondary">
                                                        <td class="d-none">
                                                            {!! Form::text('code1', null, [
                                                                'id' => 'code1',
                                                                'class' => 'form-control bg-white',
                                                                'disabled' => 'disabled',
                                                                'tabindex' => '7',
                                                            ]) !!}
                                                            {!! Form::hidden('code2', null, ['id' => 'code2']) !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::select('product_id1', $products, null, [
                                                                'id' => 'product_id1',
                                                                'class' => 'form-control select2',
                                                                'tabindex' => '8',
                                                            ]) !!}
                                                            <span class="product_err text-danger"></span>
                                                        </td>
                                                        <td>
                                                            {!! Form::text('packing1', null, [
                                                                'id' => 'packing1',
                                                                'class' => 'form-control bg-white',
                                                                'disabled' => 'disabled',
                                                                'placeholder' => 'packing',
                                                                'tabindex' => '9',
                                                            ]) !!}
                                                            {!! Form::hidden('unit2', null, ['id' => 'unit2']) !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('unit1', null, [
                                                                'id' => 'unit1',
                                                                'class' => 'form-control bg-white',
                                                                'disabled' => 'disabled',
                                                                'placeholder' => 'Unit',
                                                                'tabindex' => '9',
                                                            ]) !!}
                                                            {!! Form::hidden('unit2', null, ['id' => 'unit2']) !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('qty1', null, [
                                                                'id' => 'qty1',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Qty',
                                                                'tabindex' => '10',
                                                                'onkeyup' => 'QuantityKeyUp($(this).val())',
                                                            ]) !!}
                                                            <span class="qty_err text-danger"></span>
                                                        </td>
                                                        <td>
                                                            {!! Form::text('price1', null, [
                                                                'id' => 'price1',
                                                                'class' => 'form-control bg-white',
                                                                'placeholder' => 'Price',
                                                                'tabindex' => '11',
                                                                'onkeyup' => 'PriceKeyUp($(this).val())',
                                                            ]) !!}
                                                            <span class="price_err text-danger"></span>
                                                        </td>
                                                        <td>
                                                            {!! Form::text('excl_val1', null, [
                                                                'id' => 'excl_val1',
                                                                'class' => 'form-control bg-white',
                                                                'placeholder' => 'Excl val',
                                                                'tabindex' => '12',
                                                                // 'onkeyup' => 'PriceKeyUp($(this).val())',
                                                                'disabled' => 'disabled',
                                                            ]) !!}
                                                            <span class="excl_val_err text-danger"></span>
                                                        </td>
                                                        <td>
                                                            {!! Form::text('st_rate1', null, [
                                                                'id' => 'st_rate1',
                                                                'class' => 'form-control bg-white',
                                                                'placeholder' => 'ST Rate',
                                                                'tabindex' => '13',
                                                                'onkeyup' => 'SaleTaxRateKeyUp($(this).val())',
                                                                'disabled' => 'disabled',
                                                            ]) !!}
                                                            <span class="st_err text-danger"></span>
                                                        </td>
                                                        <td>
                                                            {!! Form::text('sale_tax1', null, [
                                                                'id' => 'sale_tax1',
                                                                'class' => 'form-control bg-white',
                                                                'placeholder' => 'Sale Tax',
                                                                'tabindex' => '12',
                                                                // 'onkeyup' => 'SaletaxKeyUp($(this).val())',
                                                            ]) !!}
                                                            <span class="sale_tax_err text-danger"></span>
                                                        </td>
                                                        <td>
                                                            {!! Form::text('total1', null, [
                                                                'id' => 'total1',
                                                                'class' => 'form-control bg-white',
                                                                'placeholder' => 'Total',
                                                                'tabindex' => '12',
                                                                'disabled' => 'disabled',
                                                            ]) !!}
                                                            <span class="total_err text-danger"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="table-responsive mb-2">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Code</th>
                                                            <th>Product&nbsp;Name</th>
                                                            {{-- <th>DemandQty</th> --}}
                                                            <th>Packing</th>
                                                            <th>Unit</th>
                                                            <th>Sale Qty</th>
                                                            <th>Rate</th>
                                                            <th>Excl.val</th>
                                                            <th>ST Rate</th>
                                                            <th>Sale Tax</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="GridTable"></tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td><strong>Total</strong></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="bg-primary" id="TotalRecQty">0</td>
                                                            <td></td>
                                                            <td class="bg-info" id="TotalExclVolue">0</td>

                                                            <td></td>
                                                            <td class="bg-success" id="TotalSaleTax">0</td>
                                                            <td class="bg-success" id="TotalAmount">0</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="9" class="text-right"><strong>Freight</strong></td>
                                                            <td class="bg-warning" id="FreightDisplay">0</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="9" class="text-right"><strong>Grand Total</strong></td>
                                                            <td class="bg-danger text-white" id="GrandTotal">0</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-lg-8 col-md-12 col-12">
                                        <!-- <div class="note note-danger">UnPosted By :</div> -->
                                    </div>
                                    <!-- <div class="col-lg-3 col-md-12 col-12">
                                                <div class="note note-warning">Posted By : {{ Auth::User()->name }}</div>
                                            </div>
                                            <div class="col-lg-3 col-md-12 col-12">
                                                <div class="note note-info">Updated By : <span class="d-none"
                                                        id="updated_by_name"> {{ Auth::User()->name }}</span></div>
                                            </div> -->
                                    <div class="col-lg-3 col-md-12 col-12">
                                        <button class="btn btn-primary submit-form" type="button">Save</button>
                                        <!-- <button class="btn btn-info submit-form1" type="button">Save & Print</button> -->
                                        <button class="btn btn-secondary reset-btn" type="reset">Reset</button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
            </div>
            </section>
        </div>
    </div>
    </div>

    <!-- Delete Record Modal -->
    <!-- modal -->
    <div class="modal fade" id="delete-record-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash text-danger"></i> Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ URL::to('direct-saletax-invoice/delete-voucher') }}" method="post" id="delete_voucher_form">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Voucher?</p>
                        <input type="hidden" name="delete_voucher_no" id="delete_voucher_no" value="">
                        <input type="hidden" name="delete_warehouse_id" id="delete_warehouse_id" value="">
                    </div>
                    <div class="modal-footer text-right">
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="document.getElementById('delete_voucher_form').submit();">Delete <i
                                class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-secondary btn-sm text-black"
                            data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.modal -->
    <!-- End Delete Record Modal -->
    <!-- Print Record Modal -->
    <!-- modal -->
    <div class="modal hide fade" id="print-record-modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash text-danger"></i> Print</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="print-receipt-modal-body"></div>
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-danger btn-sm text-black" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
    <!-- End Print Record Modal -->
    <!-- Print DC Modal -->
    <div class="modal hide fade" id="print-dc-modal" tabindex="-1" role="dialog"
        aria-labelledby="dcModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dcModalLabel"><i class="fa fa-truck text-info"></i> Delivery Challan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="print-dc-modal-body"></div>
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-danger btn-sm text-black" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Print DC Modal -->
@stop
@section('scripts')
    <script>
        function updateGrandTotal() {
            var totalAmountText = document.getElementById('TotalAmount').innerText.replace(/,/g, '');
            var totalAmount = parseFloat(totalAmountText) || 0;
            var freight = parseFloat($('#freight').val()) || 0;
            var grandTotal = totalAmount + freight;
            document.getElementById('FreightDisplay').innerText = freight.toLocaleString('en-US');
            document.getElementById('GrandTotal').innerText = grandTotal.toLocaleString('en-US');
        }
    </script>
    <script>
        function refresh() {

            var dcn_id = $("#dcn_id1").val();
            // alert(dcn_id);
            $.ajax({
                url: "{{ asset('direct-saletax-invoice/getdcRecord') }}",
                type: 'get',
                data: {
                    dcn_id: dcn_id
                },
                dataType: 'json',
                success: function(response) {
                    var tableHtml = '';
                    // var comments;
                    var grandQty = 0;
                    var grandPacking = 0;
                    var grandExlVal = 0;
                    var grandSTRate = 0;
                    var grandTotal = 0;


                    if (response.status == 0) {
                        $.each(response.data, function(i, v) {
                            //  alert("d1");
                            var excl_val = v.sale_rate * v.sale_qty;
                            //  alert(excl_val);
                            // var sale_tax = ((v.product.tax/100)*(v.sale_rate*v.demandqty));
                            // var sale_tax = ((v.rate/100)*(v.sale_rate*v.demand_qty));
                            var sale_tax = ((v.tax_rate / 100) * (v.sale_rate * v.sale_qty));
                            var total = excl_val + sale_tax;

                            grandQty += parseInt(v.qty_out);
                            grandPacking += parseInt(v.product.packing);
                            grandExlVal += excl_val;
                            grandSTRate += sale_tax;
                            grandTotal += total;
                            // alert(sale_tax);
                            // alert(total);
                            tableHtml += `<tr>`;
                            tableHtml +=
                                `<td>${v.customer_product.product_code}
                                        <input type='hidden' name='code[]' id='code' value='${v.customer_product.product_code}' />
                                        <input type='hidden' name='type' id='type' value='DC' />
                                </td>`;
                            tableHtml += `<td>${v.customer_product.product_name}
                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                </td>`;

                            tableHtml +=
                                `<td>${v.demandPCS}
                                        <input type='hidden' name='demandQty[]' id='demandQty' value='${v.demandPCS}' class='form-control' />
                                        <input type='hidden' name='qty[]' id='qty' value='${v.demandPCS}' class='form-control' />
                                        
                                    </td>`;
                            tableHtml +=
                                `<td>${parseInt(v.packing)}<input type='hidden'  value='${parseInt(v.packing)}' class='form-control'/></td>`;
                            tableHtml +=
                                `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;

                            tableHtml +=
                                `<td>${v.sale_qty}<input type='hidden' value='${v.sale_qty}' id="sale_qty" name="sale_qty[]" class="form-control" onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" readonly/></td>`;
                            tableHtml +=
                                `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.sale_rate}' class='form-control' style="width:120px;" onkeyup="changerate($(this).closest('tr'));" 
                                    onkeydown="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
                                <span class="rate_errr text-danger"></span>
                                </td>`;
                            tableHtml +=
                                `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                            tableHtml +=
                                `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.tax_rate}' class='form-control' style="width:120px;" onkeyup="changetax($(this).closest('tr'));" 
                                    onkeydown="EnterStRate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                            tableHtml +=
                                `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;

                            tableHtml +=
                                `<td style="width:130px;">
                                <input type='text' value='${total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                                </td>`;
                            tableHtml += `</tr>`;
                        });
                        $('#GridTable').html(tableHtml);
                        // alert(response.data[0].delivery_challan.party.id);
                        $('#date').val(response.data[0].delivery_challan.voucher_date);
                        $('#vehicle_no').val(response.data[0].delivery_challan.vehicle_no);
                        $('#transport_company').val(response.data[0].delivery_challan
                            .transport_company);
                        $('#driver_name').val(response.data[0].delivery_challan.driver_name);
                        $('#builty_no').val(response.data[0].delivery_challan.builty_no);
                        $('#driver_phoneno').val(response.data[0].delivery_challan
                            .driver_phoneno);
                        $('#freight').val(response.data[0].delivery_challan.freight);
                        $('#party_id').val(response.data[0].delivery_challan.party_id);
                        $('#party_name').val(response.data[0].delivery_challan.party.id + "_" + response.data[0]
                                .delivery_challan.party.party_name + "_" + response.data[0].delivery_challan
                                .party.address)
                            .select2();
                        $('#GridTable').html(tableHtml);
                        $('#TotalIgpQty').text(grandQty);
                        $('#TotalRecQty').text(grandPacking);
                        $('#TotalExclVolue').text(TotalExclVolue);
                        $('#TotalSaleTax').text(grandSTRate);
                        $('#TotalAmount').text(grandTotal);
                        recQty();
                        TotalSaleTax();
                    } else {
                        //  alert("d1");
                        $.each(response.data, function(i, v) {
                            //  alert("d1");
                            var excl_val = v.sale_rate * v.demand_qty;
                            //  alert(excl_val);
                            // var sale_tax = ((v.product.tax/100)*(v.sale_rate*v.demandqty));
                            // var sale_tax = ((v.rate/100)*(v.sale_rate*v.demand_qty));
                            var sale_tax = ((v.rate / 100) * (v.sale_rate * v.demand_qty));
                            var total = excl_val + sale_tax;

                            grandQty += parseInt(v.qty_out);
                            grandPacking += parseInt(v.product.packing);
                            grandExlVal += excl_val;
                            grandSTRate += sale_tax;
                            grandTotal += total;
                            // alert(sale_tax);
                            // alert(total);
                            tableHtml += `<tr>`;
                            tableHtml +=
                                `<td>${v.product.code}
                                        <input type='hidden' name='code[]' id='code' value='${v.product.code}' />
                                        <input type='hidden' name='challantype' id='challantype' value='DC' />
                                </td>`;
                            tableHtml += `<td>${v.product.product_name}
                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                </td>`;

                            tableHtml +=
                                `<td>${v.qty_out}
                                        <input type='hidden' name='demandQty[]' id='demandQty' value='${v.qty_out}' class='form-control' />
                                        <input type='hidden' name='qty[]' id='qty' value='${v.qty_out}' class='form-control' />
                                        
                                    </td>`;
                            tableHtml +=
                                `<td>${parseInt(v.product.packing)}<input type='hidden'  value='${parseInt(v.product.packing)}' class='form-control'/></td>`;
                            tableHtml +=
                                `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;

                            tableHtml +=
                                `<td>${v.demand_qty}<input type='hidden' value='${v.demand_qty}' id="sale_qty" name="sale_qty[]" class="form-control" onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" readonly/></td>`;
                            tableHtml +=
                                `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.sale_rate}' class='form-control' style="width:120px;" onkeyup="changerate($(this).closest('tr'));" 
                                    onkeydown="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
                                <span class="rate_errr text-danger"></span>
                                </td>`;
                            tableHtml +=
                                `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                            tableHtml +=
                                `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.rate}' class='form-control' style="width:120px;" onkeyup="changetax($(this).closest('tr'));" 
                                    onkeydown="EnterStRate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                            tableHtml +=
                                `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;

                            tableHtml +=
                                `<td style="width:130px;">
                                <input type='text' value='${total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                                </td>`;
                            tableHtml += `</tr>`;
                        });
                        $('#GridTable').html(tableHtml);
                        $('#date').val(response.data[0].delivery_challan.voucher_date);
                        $('#vehicle_no').val(response.data[0].delivery_challan.vehicle_no);
                        $('#transport_company').val(response.data[0].delivery_challan
                            .transport_company);
                        $('#driver_name').val(response.data[0].delivery_challan.driver_name);
                        $('#builty_no').val(response.data[0].delivery_challan.builty_no);
                        $('#driver_phoneno').val(response.data[0].delivery_challan
                            .driver_phoneno);
                        $('#freight').val(response.data[0].delivery_challan.freight);
                        $('#party_id').val(response.data[0].delivery_challan.party_id);
                        $('#party_name').val(response.data[0].party.id + "_" + response.data[0]
                                .party.party_name + "_" + response.data[0].party.address)
                            .select2();
                        $('#GridTable').html(tableHtml);
                        $('#TotalIgpQty').text(grandQty);
                        $('#TotalRecQty').text(grandPacking);
                        $('#TotalExclVolue').text(TotalExclVolue);
                        $('#TotalSaleTax').text(grandSTRate);
                        $('#TotalAmount').text(grandTotal);
                        recQty();
                        TotalSaleTax();
                    }



                }
            });



        }


        function TotalAmount() {
            var tableData = document.getElementById('GridTable');

            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[9].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalAmount').innerText = sum.toLocaleString('en-US');
            updateGrandTotal();
        }



        function recQty() {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseFloat(tableData.rows[i].cells[4].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalRecQty').innerText = sum.toLocaleString('en-US');
        }

        function TotalExclVolue() {
            var tableData = document.getElementById('GridTable');

            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[6].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalExclVolue').innerText = sum.toLocaleString('en-US');
        }

        function TotalSaleTax() {
            var tableData = document.getElementById('GridTable');

            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                if (tableData.rows[i].cells[8].getElementsByTagName('input')[0].value == '') {
                    sum += 0;
                } else {
                    sum += parseInt(tableData.rows[i].cells[8].getElementsByTagName('input')[0].value);
                }
            }
            document.getElementById('TotalSaleTax').innerText = sum.toLocaleString('en-US');

        }

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode == 46) {
                // Check if decimal point already exists in the input
                if (evt.target.value.indexOf('.') !== -1)
                    return false;
                else
                    return true;
            }
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }

        function changerate(row) {
            var saleqty = $(row).find("td:eq('5')").find('input').val();
            var rate = $(row).find("td:eq('6')").find('input').val();
            var saleTax = $(row).find("td:eq('8')").find('input').val();
            var exclValue;
            var stValue;
            var totalAmount;
            if (rate == null || rate == 0) {
                exclValue = 0
                stValue = 0
                totalAmount = 0
            } else {
                ///Excl valu
                exclValue = rate * saleqty
                //st rate
                stValue = (rate * saleTax / 100) * saleqty;
                //total Amount
                totalAmount = stValue + exclValue;
            }

            $(row).find("td:eq('7')").find('input').val(parseFloat(exclValue));
            $(row).find("td:eq('9')").find('input').val(parseFloat(stValue));
            $(row).find("td:eq('10')").find('input').val(parseFloat(totalAmount));
            TotalAmount();
            recQty();
            TotalExclVolue();
            TotalSaleTax();
        }

        function changetax(row) {
            var saleqty = $(row).find("td:eq('5')").find('input').val();
            var rate = $(row).find("td:eq('6')").find('input').val();
            var saleTax = $(row).find("td:eq('8')").find('input').val();
            var exclValue;
            var stValue;
            var totalAmount;
            ///Excl valu
            exclValue = rate * saleqty
            //st rate
            stValue = (rate * saleTax / 100) * saleqty;
            //total Amount
            totalAmount = stValue + exclValue;

            if (saleTax == '' || parseInt(saleTax) == 0) {

                $(row).find("td:eq('8')").find('input').val(parseInt(0));
            }

            $(row).find("td:eq('7')").find('input').val(parseFloat(exclValue));
            $(row).find("td:eq('9')").find('input').val(parseFloat(stValue));
            $(row).find("td:eq('10')").find('input').val(parseFloat(totalAmount));
            TotalAmount();
            recQty();
            TotalExclVolue();
            TotalSaleTax();
        }

        // function changeSaleQty(row) {
        //     alert(1)
        //     var sale_qty = $(row).find("td:eq('4')").find('input').val();
        //     alert(sale_qty)
        //     // var packing = parseInt($(row).find("td:eq('3')").find('input').val());
        //     // var qty = sale_qty / packing;
        //     // $(row).find("td:eq('2')").find('input').val(Math.round(qty));
        //     //TotalQuantity();
        // }
    </script>
    <!--fetching deliver challange data sat-->
    <script>
        $(document).ready(function() {
            $('#dcn_id').change(function(event) {
                var dcn_id = $(this).val();
                $.ajax({
                    url: "{{ asset('salestax-invoice/getdcRecord') }}",
                    type: 'get',
                    data: {
                        dcn_id: dcn_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        var tableHtml = '';
                        // var comments;
                        var grandQty = 0;
                        var grandPacking = 0;
                        var grandExlVal = 0;
                        var grandSTRate = 0;
                        var grandTotal = 0;


                        if (response.status == 0) {
                            $.each(response.data, function(i, v) {
                                //  alert("d1");
                                var excl_val = v.sale_rate * v.sale_qty;
                                //  alert(excl_val);
                                // var sale_tax = ((v.product.tax/100)*(v.sale_rate*v.demandqty));
                                // var sale_tax = ((v.rate/100)*(v.sale_rate*v.demand_qty));
                                var sale_tax = ((v.tax_rate / 100) * (v.sale_rate * v
                                    .sale_qty));
                                var total = excl_val + sale_tax;

                                grandQty += parseInt(v.qty_out);
                                grandPacking += parseInt(v.product.packing);
                                grandExlVal += excl_val;
                                grandSTRate += sale_tax;
                                grandTotal += total;
                                // alert(sale_tax);
                                // alert(total);
                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.customer_product.product_code}
                                        <input type='hidden' name='code[]' id='code' value='${v.customer_product.product_code}' />
                                        <input type='hidden' name='type' id='type' value='DC' />
                                </td>`;
                                tableHtml += `<td>${v.customer_product.product_name}
                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                </td>`;

                                tableHtml +=
                                    `<td>${v.demandPCS}
                                        <input type='hidden' name='demandQty[]' id='demandQty' value='${v.demandPCS}' class='form-control' />
                                        <input type='hidden' name='qty[]' id='qty' value='${v.demandPCS}' class='form-control' />
                                        
                                    </td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.packing)}<input type='hidden'  value='${parseInt(v.packing)}' class='form-control'/></td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;

                                tableHtml +=
                                    `<td>${v.sale_qty}<input type='hidden' value='${v.sale_qty}' id="sale_qty" name="sale_qty[]" class="form-control" onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" readonly/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.sale_rate}' class='form-control' style="width:120px;" onkeyup="changerate($(this).closest('tr'));" 
                                    onkeydown="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
                                <span class="rate_errr text-danger"></span>
                                </td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.tax_rate}' class='form-control' style="width:120px;" onkeyup="changetax($(this).closest('tr'));" 
                                    onkeydown="EnterStRate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;

                                tableHtml +=
                                    `<td style="width:130px;">
                                <input type='text' value='${total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                                </td>`;
                                tableHtml += `</tr>`;
                            });
                            $('#GridTable').html(tableHtml);
                            // alert(response.data[0].delivery_challan.party.id);
                            $('#date').val(response.data[0].delivery_challan.voucher_date);
                            $('#vehicle_no').val(response.data[0].delivery_challan.vehicle_no);
                            $('#transport_company').val(response.data[0].delivery_challan
                                .transport_company);
                            $('#driver_name').val(response.data[0].delivery_challan
                            .driver_name);
                            $('#builty_no').val(response.data[0].delivery_challan.builty_no);
                            $('#driver_phoneno').val(response.data[0].delivery_challan
                                .driver_phoneno);
                            $('#freight').val(response.data[0].delivery_challan.freight);
                            $('#party_id').val(response.data[0].delivery_challan.party_id);
                            $('#party_name').val(response.data[0].delivery_challan.party.id +
                                    "_" + response.data[0]
                                    .delivery_challan.party.party_name + "_" + response.data[0]
                                    .delivery_challan.party.address)
                                .select2();
                            $('#GridTable').html(tableHtml);
                            $('#TotalIgpQty').text(grandQty);
                            $('#TotalRecQty').text(grandPacking);
                            $('#TotalExclVolue').text(TotalExclVolue);
                            $('#TotalSaleTax').text(grandSTRate);
                            $('#TotalAmount').text(grandTotal);
                            recQty();
                            TotalSaleTax();
                        } else {
                            //  alert("d1");
                            $.each(response.data, function(i, v) {
                                //  alert("d1");
                                var excl_val = v.sale_rate * v.demand_qty;
                                //  alert(excl_val);
                                // var sale_tax = ((v.product.tax/100)*(v.sale_rate*v.demandqty));
                                // var sale_tax = ((v.rate/100)*(v.sale_rate*v.demand_qty));
                                var sale_tax = ((v.rate / 100) * (v.sale_rate * v
                                    .demand_qty));
                                var total = excl_val + sale_tax;

                                grandQty += parseInt(v.qty_out);
                                grandPacking += parseInt(v.product.packing);
                                grandExlVal += excl_val;
                                grandSTRate += sale_tax;
                                grandTotal += total;
                                // alert(sale_tax);
                                // alert(total);
                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product.code}
                                        <input type='hidden' name='code[]' id='code' value='${v.product.code}' />
                                        <input type='hidden' name='challantype' id='challantype' value='DC' />
                                </td>`;
                                tableHtml += `<td>${v.product.product_name}
                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                </td>`;

                                tableHtml +=
                                    `<td>${v.qty_out}
                                        <input type='hidden' name='demandQty[]' id='demandQty' value='${v.qty_out}' class='form-control' />
                                        <input type='hidden' name='qty[]' id='qty' value='${v.qty_out}' class='form-control' />
                                        
                                    </td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.product.packing)}<input type='hidden'  value='${parseInt(v.product.packing)}' class='form-control'/></td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;

                                tableHtml +=
                                    `<td>${v.demand_qty}<input type='hidden' value='${v.demand_qty}' id="sale_qty" name="sale_qty[]" class="form-control" onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" readonly/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.sale_rate}' class='form-control' style="width:120px;" onkeyup="changerate($(this).closest('tr'));" 
                                    onkeydown="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
                                <span class="rate_errr text-danger"></span>
                                </td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.rate}' class='form-control' style="width:120px;" onkeyup="changetax($(this).closest('tr'));" 
                                    onkeydown="EnterStRate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;

                                tableHtml +=
                                    `<td style="width:130px;">
                                <input type='text' value='${total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                                </td>`;
                                tableHtml += `</tr>`;
                            });
                            $('#GridTable').html(tableHtml);
                            $('#date').val(response.data[0].delivery_challan.voucher_date);
                            $('#vehicle_no').val(response.data[0].delivery_challan.vehicle_no);
                            $('#transport_company').val(response.data[0].delivery_challan
                                .transport_company);
                            $('#driver_name').val(response.data[0].delivery_challan
                            .driver_name);
                            $('#builty_no').val(response.data[0].delivery_challan.builty_no);
                            $('#driver_phoneno').val(response.data[0].delivery_challan
                                .driver_phoneno);
                            $('#freight').val(response.data[0].delivery_challan.freight);
                            $('#party_id').val(response.data[0].delivery_challan.party_id);
                            $('#party_name').val(response.data[0].party.id + "_" + response
                                    .data[0]
                                    .party.party_name + "_" + response.data[0].party.address)
                                .select2();
                            $('#GridTable').html(tableHtml);
                            $('#TotalIgpQty').text(grandQty);
                            $('#TotalRecQty').text(grandPacking);
                            $('#TotalExclVolue').text(TotalExclVolue);
                            $('#TotalSaleTax').text(grandSTRate);
                            $('#TotalAmount').text(grandTotal);
                            recQty();
                            TotalSaleTax();
                        }



                    }
                });
            });
        });
    </script>

    <!--fetching deliver challange data end -->
    <!-- Searchable Select2 -->
    <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2, .select2-multiple").select2({
            width: "100%"
        });
    </script>
    <!-- End Searchable Select2 -->


    <!-- Focus on next field -->
    <script>
        $(document).ready(function() {
            $('#date').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
                    var userRole = $('#userrole').val();
                    // alert(userRole);
                    if(userRole == "Admin"){
                        $("#warehouse_id").select2('open');
                    }else{
                        // $('#warehouse_id').select2().trigger('select2:close');
                        $("#voucher_no").focus();
                    }
                }
            });

            $('#warehouse_id').change(function() {
                var account_id = $(this).val();
                // alert(account_id);
                // if (account_id) {
                    $('#warehouse_id').select2().trigger('select2:close');
                    $("#voucher_no").focus();
                // }
                var warehouseID = $(this).val();
                if (warehouseID) {
                    $('#warehouse_id').select2().trigger('select2:close');
                    $('#voucher_no').focus();
                    $.ajax({
                    url: "{{ URL::to('direct-saletax-invoice/warehouse/voucherno') }}",
                    type: 'get',
                    data: {
                        warehouseID: warehouseID
                    },
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.codes != '') {

                            // $('#GridTable').html(tableHtml);
                            // $('#TotalAmount').text(totalAmount);
                            $('#voucher_no').val(response.codes);
                        }
                    }
                });
                }
            });

            $('#voucher_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#party_name").select2('open');
                }
            });

            $('#party_name').change(function(event) {
                var party_name = $(this).val();
                if (party_name) {
                    $('#party_name').select2().trigger('select2:close');
                    var party = $(this).val();
                    $('#party_id').val(party.split('_')[0]);
                    $('#address').val(party.split('_')[2]);
                    $('#remarks').focus();

                }
            });
            $('#remarks').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#vehicle_no').focus();
                }
            });

            $('#vehicle_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#transport_company').focus();
                }
            });
            $('#transport_company').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#driver_name').focus();
                }
            });
            $('#driver_name').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#builty_no').focus();
                }
            });
            $('#builty_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#freight').focus();
                }
            });
            $('#freight').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#driver_phoneno').focus();
                }
            });
            $('#driver_phoneno').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#product_id1").select2('open');
                }
            });


            // $(document).on('keydown', '#rate', function(event) {
            //     let row = $(this).closest('tr');
            //     var keycode = (event.keyCode ? event.keyCode : event.which);
            //     if (keycode == '13') {
            //         $(row).find("td:eq('8')").find('input').focus();
            //     }
            // });
            $('#product_id1').change(function(event) {
                var product_id = $(this).val();
                // alert(product_id);
                if (product_id != null) {
                    $('#code1').val(product_id.split('_')[1]);
                    $('#code2').val(product_id.split('_')[1]);
                    $('#unit1').val(product_id.split('_')[3]);
                    $('#product_id1').select2().trigger('select2:close');
                    $('#price1').val(parseInt(product_id.split('_')[4]));
                    $('#total1').val(parseInt(product_id.split('_')[4]));
                    $('#packing1').val(parseInt(product_id.split('_')[5]));
                    $('#st_rate1').val(parseInt(product_id.split('_')[6]));
                    // $('#sale_tax1').val(parseInt(product_id.split('_')[6]));
                    $('#qty1').val(1);
                    $('#qty1').select();
                }
            });
            $('#qty1').keydown(function(event) {

                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = parseInt($(this).val());
                    if (qty <= 0) {
                        $(this).focus();
                        $('.qty_err').text('This field is required & Must be greater than zero');
                    } else {
                        $('.qty_err').text('');
                        $('#price1').focus();
                    }
                }
            });
            $('#price1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('.product_id1_err').text('');
                    $('.qty_err').text('');
                    $('.price_err').text('');


                    var product_id = parseInt($('#product_id1').val());
                    var qty = parseInt($('#qty1').val());
                    var price = parseInt($('#price1').val());

                    if (!product_id) {
                        $('#product_id').select2('open');
                        $('.product_id1_err').text('This field is required');
                        return false;
                    } else
                    if (!qty || qty <= 0) {
                        $('#qty1').focus();
                        $('.qty_err').text('This field is required & Must be greater than zero');
                        return false;
                    } else
                    if (!price || price <= 0) {
                        $('#price1').focus();
                        $('.price_err').text('This field is required & Must be greater than zero');
                        return false;
                    } else {
                        $('#sale_tax1').focus();
                       
                    }
                }
            });
            $('#sale_tax1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('.product_id1_err').text('');
                    $('.qty_err').text('');
                    $('.price_err').text('');


                    var product_id = parseInt($('#product_id1').val());
                    var qty = parseInt($('#qty1').val());
                    var price = parseInt($('#price1').val());
                    var tax = parseInt($('#sale_tax1').val());

                    if (!product_id) {
                        $('#product_id').select2('open');
                        $('.product_id1_err').text('This field is required');
                        return false;
                    } else
                    if (!qty || qty <= 0) {
                        $('#qty1').focus();
                        $('.qty_err').text('This field is required & Must be greater than zero');
                        return false;
                    }
                     else
                    if (!price || price <= 0) {
                        $('#price1').focus();
                        $('.price_err').text('This field is required & Must be greater than zero');
                        return false;
                    }
                     else
                    if (!tax || tax <= 0) {
                        $('#sale_tax1').focus();
                        $('.sale_tax_err').text('This field is required & Must be greater than zero');
                        return false;
                    }
                     else {
                        AddGridData();
                    }
                }
            });
        });
    </script>
    <!-- End Focus on next field -->

    <!-- Append New Data on Table -->
    <script>
        function EnterKeyBoard(row) {
            // alert("enter") 
            var RowIndex = row.index();
            if (event.keyCode == 13) {
                // if(RowIndex = '0'){
                //     alert("1");
                //  $('tr:eq(' + RowIndex + ')', GridTable).find("td:eq('6')").find('input').focus().select();
                // }
                // if(NextIndex != '0')
                // {
                // alert("2");
                var NextIndex = RowIndex + 1;
                $('tr:eq(' + NextIndex + ')', GridTable).find("td:eq('6')").find('input').focus().select();
                // }
            }

        }

        function EnterStRate(row) {
            // alert("enter") 
            var RowIndex = row.index();
            if (event.keyCode == 13) {
                if (RowIndex = '0') {
                    $('tr:eq(' + RowIndex + ')', GridTable).find("td:eq('8')").find('input').focus().select();
                }
                if (NextIndex != '0') {
                    var NextIndex = RowIndex + 1;
                    $('tr:eq(' + NextIndex + ')', GridTable).find("td:eq('8')").find('input').focus().select();
                }
            }
        }

        function loadeditSale() {
            // var consignment_no = parseInt($('#consignment_no').val());
            // alert(consignment_no);
            $('.load-edit-record').click();
        }
        var grandQty = 0;
        var grandPacking = 0;
        var grandExlVal = 0;
        var grandSTRate = 0;
        var grandTotal = 0;
        function AddGridData() {

            var product_id = parseInt($('#product_id1').val());
            if (!product_id) {
                $('.product_err').text('This field is required');
                $('#product_id1').focus();
                return false;
            } else {
                $('.product_err').text('');
            }

            var qty = parseInt($('#qty1').val());
            if (!qty || qty <= 0) {
                $('.qty_err').text('This field is required & Must be greater than zero');
                $('#qty1').focus();
                return false;
            } else {
                $('.qty_err').text('');
            }

            var price = parseInt($('#price1').val());
            if (!price || price <= 0) {
                $('.price1_err').text('This field is required & Must be greater than zero');
                $('#price1').focus();
                return false;
            } else {
                $('.price1_err').text('');
            }
            var tax = parseInt($('#sale_tax1').val());
            if (!tax || tax <= 0) {
                $('.sale_tax_err').text('This field is required & Must be greater than zero');
                $('#sale_tax1').focus();
                return false;
            } else {
                $('.sale_tax_err').text('');
            }

            var pro_id = document.getElementById('product_id1').value.split('_')[0];
            var pro_code = document.getElementById('product_id1').value.split('_')[1];
            var pro_name = document.getElementById('product_id1').value.split('_')[2];
            var pro_unit = document.getElementById('product_id1').value.split('_')[3];
            var packing = document.getElementById('product_id1').value.split('_')[6];
            var price = parseInt(document.getElementById('price1').value);
            var qty = parseInt(document.getElementById('qty1').value);
            var excl_val = parseInt(document.getElementById('excl_val1').value);
            var st_rate = parseInt(document.getElementById('st_rate1').value);
            var sale_tax = parseInt(document.getElementById('sale_tax1').value);
            var total = parseInt(document.getElementById('total1').value);
          
            grandQty += qty;
            grandPacking += packing;
            grandExlVal += excl_val;
            grandSTRate += sale_tax;
            grandTotal += total;

            var tableHtml = '';
            tableHtml += `<tr>`;
            tableHtml +=
                `<td>${pro_code}
                    </td>`;
            tableHtml += `<td>${pro_name}
                        <input type='hidden' name='product_id[]' id='product_id' value='${pro_id}' />
                    </td>`;

            tableHtml +=
                `<td>${parseInt(packing)}</td>`;
            tableHtml +=
                `<td>${pro_unit}</td>`;

            tableHtml +=
                `<td><input type='text' value='${qty}' id="sale_qty" name="sale_qty[]" class="form-control" onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
            tableHtml +=
                `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${parseInt(price)}' class='form-control' style="width:120px;" onkeyup="changerate($(this).closest('tr'));" 
                        onkeydown="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
                    <span class="rate_errr text-danger"></span>
                    </td>`;
            tableHtml +=
                `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${parseInt(excl_val)}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
            tableHtml +=
                `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${parseInt(st_rate)}' class='form-control' style="width:120px;" onkeyup="changetax($(this).closest('tr'));" 
                        onkeydown="EnterStRate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
            tableHtml +=
                `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${parseInt(sale_tax)}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;

            tableHtml +=
                `<td style="width:130px;">
                    <input type='text' value='${parseInt(total)}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                    </td>`;
            tableHtml += `</tr>`;

            $('#GridTable').append(tableHtml);

            // Reset fields after adding
            $('#product_id1').val(null).select2('open');
            $('#qty1').val(null);
            $('#price1').val(null);
            $('#total1').val(null);

            // Update totals
           
            $('#TotalRecQty').text(grandQty);
            $('#TotalExclVolue').text(grandExlVal);
            $('#TotalSaleTax').text(parseInt(grandSTRate));
            $('#TotalAmount').text(grandTotal);

            TotalAmount();
            recQty();
            TotalExclVolue();
            TotalSaleTax();
        }
        
    </script>
    <!-- End Append New Data on Table -->

    <script>
        $(document).ready(function() {
            // Hide Alert Notification After 3 Seconds
            setInterval(() => {
                $('.alert').hide();
            }, 3000);
            // End Hide Alert Notification After 3 Seconds




            // Form Submit
            $('.submit-form').click(function() {
                var voucher_no = $('#voucher_no').val();
                var dcn_id = $('#dcn_id').val();
                var party_name = $('#party_name').val();

                $('.voucher_no_err').text('');
                $('.dcn_id_err').text('');
                $('.party_name_err').text('');


                if (!voucher_no) {
                    // alert("vr");
                    $('.voucher_no_err').text('The voucher no field is required.');
                    return false;
                } else
                    // if(!dcn_id)
                    // {
                    //     $('.dcn_id_err').text('The DC field is required.');
                    //     return false;
                    // }else
                    if (!party_name) {
                        alert("party");
                        $('.party_name_err').text('The party field is required.');
                        return false;
                    } else {
                        // $('#salestax-invoice-form').submit();
                        var form = $('#salestax-invoice-form');
                        form.attr('target', '_blank'); // Set target to _blank
                        form.submit();
                        $('.submit-form').attr('disabled', true);
                        location.reload();
                    }
            });
            
            // End FOrm Submit

            // Reset btn feature
            $('.reset-btn').click(function() {
                $('#party_name').val(null).select2();
                $('#transaction_type').val(null).select2();
                $('#dcn_id').select2().next().show();
                $('#dcn_id1').val(null);
                $('#dcn_id2').val(null).css('display', 'none');
                $('#update_voucher_id').val(null);
                $('#updated_by_name').addClass('d-none');
                $('#GridTable').html('');
                $('#TotalQty').html(0);
                $('#TotalAmount').html(0);
                $('#TotalRecQty').html(0);
                $('#TotalExclVolue').html(0);
                $('#TotalSaleTax').html(0);
                $('#FreightDisplay').html(0);
                $('#GrandTotal').html(0);
                $('#freight').val(null);
            });
            // End Reset btn feature
        });
    </script>


    <!-- OnChange Qty -->
    <script>
               function TotalAmount() {
            var tableData = document.getElementById('GridTable');

            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[9].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalAmount').innerText = sum.toLocaleString('en-US');
            updateGrandTotal();
        }



        function recQty() {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseFloat(tableData.rows[i].cells[4].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalRecQty').innerText = sum.toLocaleString('en-US');
        }

        function TotalExclVolue() {
            var tableData = document.getElementById('GridTable');

            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[6].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalExclVolue').innerText = sum.toLocaleString('en-US');
        }

        function TotalSaleTax() {
            var tableData = document.getElementById('GridTable');

            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                if (tableData.rows[i].cells[8].getElementsByTagName('input')[0].value == '') {
                    sum += 0;
                } else {
                    sum += parseInt(tableData.rows[i].cells[8].getElementsByTagName('input')[0].value);
                }
            }
            document.getElementById('TotalSaleTax').innerText = sum.toLocaleString('en-US');

        }

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode == 46) {
                // Check if decimal point already exists in the input
                if (evt.target.value.indexOf('.') !== -1)
                    return false;
                else
                    return true;
            }
            if (charCode > 31 && (charCode < 48 || charCode > 57))changerateQuantityKeyUp($(this).val())
                return false;
            return true;
        }

        function changerate(row) {
            var saleqty = $(row).find("td:eq('4')").find('input').val();
            var rate = $(row).find("td:eq('5')").find('input').val();
            var saleTax = $(row).find("td:eq('7')").find('input').val();
            var exclValue;
            var stValue;
            var totalAmount;
            if (rate == null || rate == 0) {
                exclValue = 0
                stValue = 0
                totalAmount = 0
            } else {
                ///Excl valu
                exclValue = rate * saleqty
                //st rate
                stValue = (rate * saleTax / 100) * saleqty;
                //total Amount
                totalAmount = stValue + exclValue;
            }

            $(row).find("td:eq('6')").find('input').val(parseFloat(exclValue));
            $(row).find("td:eq('8')").find('input').val(parseFloat(stValue));
            $(row).find("td:eq('9')").find('input').val(parseFloat(totalAmount));
            TotalAmount();
            recQty();
            TotalExclVolue();
            TotalSaleTax();
        }

        function changetax(row) {
            var saleqty = $(row).find("td:eq('4')").find('input').val();
            var rate = $(row).find("td:eq('5')").find('input').val();
            var saleTax = $(row).find("td:eq('7')").find('input').val();
            var exclValue;
            var stValue;
            var totalAmount;
            ///Excl valu
            exclValue = rate * saleqty
            //st rate
            stValue = (rate * saleTax / 100) * saleqty;
            //total Amount
            totalAmount = stValue + exclValue;

            if (saleTax == '' || parseInt(saleTax) == 0) {

                $(row).find("td:eq('8')").find('input').val(parseInt(0));
            }

            $(row).find("td:eq('6')").find('input').val(parseFloat(exclValue));
            $(row).find("td:eq('8')").find('input').val(parseFloat(stValue));
            $(row).find("td:eq('9')").find('input').val(parseFloat(totalAmount));
            TotalAmount();
            recQty();
            TotalExclVolue();
            TotalSaleTax();
        }

        function changeSaleQty(row) {
            var saleqty = $(row).find("td:eq('4')").find('input').val();
            var rate = $(row).find("td:eq('5')").find('input').val();
            var saleTax = $(row).find("td:eq('7')").find('input').val();
            var exclValue;
            var stValue;
            var totalAmount;
            if (rate == null || rate == 0) {
                exclValue = 0
                stValue = 0
                totalAmount = 0
            } else {
                ///Excl valu
                exclValue = rate * saleqty
                //st rate
                stValue = (rate * saleTax / 100) * saleqty;
                //total Amount
                totalAmount = stValue + exclValue;
            }

            $(row).find("td:eq('6')").find('input').val(parseFloat(exclValue));
            $(row).find("td:eq('8')").find('input').val(parseFloat(stValue));
            $(row).find("td:eq('9')").find('input').val(parseFloat(totalAmount));
            TotalAmount();
            recQty();
            TotalExclVolue();
            TotalSaleTax();
            TotalAmount();
            recQty();
            TotalExclVolue();
            TotalSaleTax();
        }
        function changeQty(row) {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[3].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalQty').innerText = sum;
        }

        function DeleteRow(row) {
            $(row).remove();
            TotalAmount();
            recQty();
            TotalExclVolue();
            TotalSaleTax();
        }

        function SaletaxKeyUp(tax) {
            var price = document.getElementById('price1').value;
            var quantity = document.getElementById('qty1').value;
            var excl_val = quantity * price;
            var st_rate = (tax / 100) * excl_val;
            var total = excl_val + st_rate;
            document.getElementById('excl_val1').value = excl_val;
            document.getElementById('st_rate1').value = st_rate;
            document.getElementById('total1').value = total;
        }
        function PriceKeyUp(price) {
            var quantity = document.getElementById('qty1').value;
            var tax = document.getElementById('st_rate1').value;
            var excl_val = quantity * price;
            var taxValue = (tax / 100) * excl_val;
            var total = excl_val + taxValue;
            document.getElementById('excl_val1').value = excl_val;
            document.getElementById('sale_tax1').value = taxValue;
            document.getElementById('total1').value = total;
        }

        function QuantityKeyUp(quantity) {
            var price = document.getElementById('price1').value;
            var tax = document.getElementById('st_rate1').value;
            var excl_val = quantity * price;
            var taxValue = (tax / 100) * excl_val;
            var total = excl_val + taxValue;
            document.getElementById('excl_val1').value = excl_val;
            document.getElementById('sale_tax1').value = taxValue;
            document.getElementById('total1').value = total;
          

        }
    </script>
    <!-- End OnChange Qty -->


    <!-- Main Features -->
    <script>
        $(document).ready(function() {
            // Delete Record
            $('.delete_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('delete-record-modal'), {});
                myModal.toggle();
                var voucher_no = parseInt($('#voucher_no').val());
                $('#delete_voucher_no').val(voucher_no);
            });
            // Print Record
            $('.print_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                myModal.toggle();

                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                var base_url = $('#base_url').val();
                $.ajax({
                    url: "{{ URL::to('direct-saletax-invoice/print/voucher') }}",
                    data: {voucher_no:voucher_no, warehouseID:warehouseID},
                    type: 'get',
                    beforeSend: function(response) {
                        $('#print-receipt-modal-body').html(
                            '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                        );
                    },
                    success: function(response) {
                        if (response != null && response != 0) {
                            $('#print-receipt-modal-body').html(
                                `<object data="${base_url}/resources/upload/salestax-invoice/${response}" type="application/pdf" width="100%" height="800"></object>`
                            );
                        } else {
                            $('#print-receipt-modal-body').html(
                                '<h2 style="color:red;text-align:center;">Voucher Not Exist</h2>'
                            );
                        }
                    }
                });
            });
            // Print Delivery Challan
            $('.print_dc_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('print-dc-modal'), {});
                myModal.toggle();

                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                var base_url = $('#base_url').val();
                $.ajax({
                    url: "{{ URL::to('direct-saletax-invoice/print/dc-voucher') }}",
                    data: {voucher_no:voucher_no, warehouseID:warehouseID},
                    type: 'get',
                    beforeSend: function(response) {
                        $('#print-dc-modal-body').html(
                            '<div class="spinner-border text-info" role="status"><span class="sr-only">Loading...</span></div>'
                        );
                    },
                    success: function(response) {
                        if (response != null && response != 0) {
                            $('#print-dc-modal-body').html(
                                `<object data="${base_url}/resources/upload/salestax-invoice/${response}" type="application/pdf" width="100%" height="800"></object>`
                            );
                        } else {
                            $('#print-dc-modal-body').html(
                                '<h2 style="color:red;text-align:center;">Voucher Not Exist</h2>'
                            );
                        }
                    }
                });
            });
        });
    </script>
    <!-- End Main Features -->

    <!-- Load & Edit Record -->
    <script>
        $(document).ready(function() {
            $('.load-edit-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                $.ajax({
                    url: "{{ URL::to('direct-saletax-invoice/load/record') }}",
                    type: 'get',
                    data:{voucher_no:voucher_no, warehouseID:warehouseID},
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            var tableHtml = '';
                            var totalQty = 0;
                                $.each(response.data, function(i, v) {
                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                        `<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                    tableHtml += `<td>
                                        ${v.product.product_name}
                                       <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                   </td>`;
                                    tableHtml +=
                                        `<td>${parseInt(v.product.packing)} <input type='hidden'  value='${v.product.packing}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=
                                        `<td><input type='text' value='${parseInt(v.sale_qty)}' id="sale_qty" name="sale_qty[]" class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' style="width:120px;" onkeyup="changerate($(this).closest('tr'));" onkeydown="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
                                   <span class="rate_errr text-danger"></span>
                                   </td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.st_rate}' class='form-control' style="width:120px;" onkeyup="changetax($(this).closest('tr'));" onkeydown="EnterStRate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;

                                    tableHtml +=
                                        `<td style="width:130px;">
                                   <input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                               </td>`;
                                    tableHtml += `</tr>`;

                                });
                                $('#GridTable').html(tableHtml);
                                $('#TotalIgpQty').text(totalQty.toLocaleString('en-US'));
                                $('#updated_by_name').removeClass('d-none');
                                $('#update_voucher_id').val(response.data[0].salepurchase.id);
                                $('#date').val(response.data[0].salepurchase.date);
                                $('#voucher_no').val(response.data[0].salepurchase.voucher_no);
                                // set warehouse select to saved value for this voucher (safe)
                                try {
                                    var saleWhId = response.data[0].salepurchase.warehouse_id;
                                    if (saleWhId !== null && saleWhId !== undefined) {
                                        var $wh = $('#warehouse_id');
                                        if ($wh.length) {
                                            $wh.val(saleWhId);
                                            if ($wh.hasClass('select2-hidden-accessible') && typeof $wh.select2 === 'function') {
                                                $wh.trigger('change.select2');
                                            } else {
                                                $wh.trigger('change');
                                            }
                                        }
                                    }
                                } catch (e) {
                                    console.warn('Could not set warehouse select safely', e);
                                }
                                $('#voucher_no').focus();
                                $('#remarks').val(response.data[0].salepurchase.remarks);
                                $('#vehicle_no').val(response.data[0].salepurchase
                                    .vehicle_no);
                                $('#transport_company').val(response.data[0].salepurchase
                                    .transport_company);
                                $('#driver_name').val(response.data[0].salepurchase
                                    .driver_name);
                                $('#builty_no').val(response.data[0].salepurchase.builty_no);
                                $('#freight').val(response.data[0].salepurchase.freight);
                                $('#driver_phoneno').val(response.data[0].salepurchase
                                    .driver_phoneno);
                                $('#party_name').val(response.data[0].party.id + "_" + response
                                    .data[0].party.party_name + "_" + response.data[0].party
                                    .address).select2();
                                $('#party_id').val(response.data[0].party.id);
                                $('.ReloadOrder').removeClass('d-none');
                                TotalAmount();
                                recQty();
                                TotalExclVolue();
                                TotalSaleTax();
                            

                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalIgpQty').text(0);
                            $('#TotalRecQty').text(0);
                            $('#TotalExclVolue').text(0);
                            $('#TotalSaleTax').text(0);
                            $('#TotalAmount').text(0);
                            $('#FreightDisplay').text(0);
                            $('#GrandTotal').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_voucher_id').val(null);
                            $('#voucher_no').focus();
                            $('#dcn_no1').val(null);
                            $('#remarks').val(null);
                            $('#vehicle_no').val(null);
                            $('#transport_company').val(null);
                            $('#driver_name').val(null);
                            $('#builty_no').val(null);
                            $('#freight').val(null);
                            $('#driver_phoneno').val(null);
                        }
                    }
                });
            });


            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                // alert(voucher_no)
                $.ajax({
                    url: "{{ URL::to('direct-saletax-invoice/load/next/record') }}",
                    type: 'get',
                    data:{voucher_no:voucher_no, warehouseID:warehouseID},
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            var tableHtml = '';
                            var totalQty = 0;
                                $.each(response.data, function(i, v) {
                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                        `<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                    tableHtml += `<td>
                                        ${v.product.product_name}
                                       <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                   </td>`;
                                    tableHtml +=
                                        `<td>${parseInt(v.product.packing)} <input type='hidden'  value='${v.product.packing}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=
                                        `<td><input type='text' value='${parseInt(v.sale_qty)}' id="sale_qty" name="sale_qty[]" class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' style="width:120px;" onkeyup="changerate($(this).closest('tr'));" onkeydown="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
                                   <span class="rate_errr text-danger"></span>
                                   </td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.st_rate}' class='form-control' style="width:120px;" onkeyup="changetax($(this).closest('tr'));" onkeydown="EnterStRate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;

                                    tableHtml +=
                                        `<td style="width:130px;">
                                   <input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                               </td>`;
                                    tableHtml += `</tr>`;

                                });
                                $('#GridTable').html(tableHtml);
                                $('#TotalIgpQty').text(totalQty.toLocaleString('en-US'));
                                $('#updated_by_name').removeClass('d-none');
                                $('#update_voucher_id').val(response.data[0].salepurchase.id);
                                $('#date').val(response.data[0].salepurchase.date);
                                $('#voucher_no').val(response.data[0].salepurchase.voucher_no);
                                // set warehouse select to saved value for this voucher (safe)
                                try {
                                    var saleWhId = response.data[0].salepurchase.warehouse_id;
                                    if (saleWhId !== null && saleWhId !== undefined) {
                                        var $wh = $('#warehouse_id');
                                        if ($wh.length) {
                                            $wh.val(saleWhId);
                                            if ($wh.hasClass('select2-hidden-accessible') && typeof $wh.select2 === 'function') {
                                                $wh.trigger('change.select2');
                                            } else {
                                                $wh.trigger('change');
                                            }
                                        }
                                    }
                                } catch (e) {
                                    console.warn('Could not set warehouse select safely', e);
                                }
                                $('#voucher_no').focus();
                                $('#remarks').val(response.data[0].salepurchase.remarks);
                                $('#vehicle_no').val(response.data[0].salepurchase
                                    .vehicle_no);
                                $('#transport_company').val(response.data[0].salepurchase
                                    .transport_company);
                                $('#driver_name').val(response.data[0].salepurchase
                                    .driver_name);
                                $('#builty_no').val(response.data[0].salepurchase.builty_no);
                                $('#freight').val(response.data[0].salepurchase.freight);
                                $('#driver_phoneno').val(response.data[0].salepurchase
                                    .driver_phoneno);
                                $('#party_name').val(response.data[0].party.id + "_" + response
                                    .data[0].party.party_name + "_" + response.data[0].party
                                    .address).select2();
                                $('#party_id').val(response.data[0].party.id);
                                $('.ReloadOrder').removeClass('d-none');
                                TotalAmount();
                                recQty();
                                TotalExclVolue();
                                TotalSaleTax();
                            

                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalIgpQty').text(0);
                            $('#TotalRecQty').text(0);
                            $('#TotalExclVolue').text(0);
                            $('#TotalSaleTax').text(0);
                            $('#TotalAmount').text(0);
                            $('#FreightDisplay').text(0);
                            $('#GrandTotal').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_voucher_id').val(null);
                            $('#voucher_no').focus();
                            $('#dcn_no1').val(null);
                            $('#remarks').val(null);
                            $('#vehicle_no').val(null);
                            $('#transport_company').val(null);
                            $('#driver_name').val(null);
                            $('#builty_no').val(null);
                            $('#freight').val(null);
                            $('#driver_phoneno').val(null);
                        }
                    }
                });
            });
            // End Here of Load Next Record


            // Load Previous Record
            $('.load-previous-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                //   alert(voucher_no)
                $.ajax({
                    url: "{{ URL::to('direct-saletax-invoice/load/previous/record') }}",
                    type: 'get',
                    data:{voucher_no:voucher_no, warehouseID:warehouseID},
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                       
                        if (response.data != '') {
                            var tableHtml = '';
                            var totalQty = 0;
                                $.each(response.data, function(i, v) {
                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                        `<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                    tableHtml += `<td>
                                        ${v.product.product_name}
                                       <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                   </td>`;
                                    tableHtml +=
                                        `<td>${parseInt(v.product.packing)} <input type='hidden'  value='${v.product.packing}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=
                                        `<td><input type='text' value='${parseInt(v.sale_qty)}' id="sale_qty" name="sale_qty[]" class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' style="width:120px;" onkeyup="changerate($(this).closest('tr'));" onkeydown="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
                                   <span class="rate_errr text-danger"></span>
                                   </td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.st_rate}' class='form-control' style="width:120px;" onkeyup="changetax($(this).closest('tr'));" onkeydown="EnterStRate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;

                                    tableHtml +=
                                        `<td style="width:130px;">
                                   <input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                               </td>`;
                                    tableHtml += `</tr>`;

                                });
                                $('#GridTable').html(tableHtml);
                                $('#TotalIgpQty').text(totalQty.toLocaleString('en-US'));
                                $('#updated_by_name').removeClass('d-none');
                                $('#update_voucher_id').val(response.data[0].salepurchase.id);
                                $('#date').val(response.data[0].salepurchase.date);
                                $('#voucher_no').val(response.data[0].salepurchase.voucher_no);
                                // set warehouse select to saved value for this voucher (safe)
                                try {
                                    var saleWhId = response.data[0].salepurchase.warehouse_id;
                                    if (saleWhId !== null && saleWhId !== undefined) {
                                        var $wh = $('#warehouse_id');
                                        if ($wh.length) {
                                            $wh.val(saleWhId);
                                            if ($wh.hasClass('select2-hidden-accessible') && typeof $wh.select2 === 'function') {
                                                $wh.trigger('change.select2');
                                            } else {
                                                $wh.trigger('change');
                                            }
                                        }
                                    }
                                } catch (e) {
                                    console.warn('Could not set warehouse select safely', e);
                                }
                                $('#voucher_no').focus();
                                $('#remarks').val(response.data[0].salepurchase.remarks);
                                $('#vehicle_no').val(response.data[0].salepurchase
                                    .vehicle_no);
                                $('#transport_company').val(response.data[0].salepurchase
                                    .transport_company);
                                $('#driver_name').val(response.data[0].salepurchase
                                    .driver_name);
                                $('#builty_no').val(response.data[0].salepurchase.builty_no);
                                $('#freight').val(response.data[0].salepurchase.freight);
                                $('#driver_phoneno').val(response.data[0].salepurchase
                                    .driver_phoneno);
                                $('#party_name').val(response.data[0].party.id + "_" + response
                                    .data[0].party.party_name + "_" + response.data[0].party
                                    .address).select2();
                                $('#party_id').val(response.data[0].party.id);
                                $('.ReloadOrder').removeClass('d-none');
                                TotalAmount();
                                recQty();
                                TotalExclVolue();
                                TotalSaleTax();
                            

                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalIgpQty').text(0);
                            $('#TotalRecQty').text(0);
                            $('#TotalExclVolue').text(0);
                            $('#TotalSaleTax').text(0);
                            $('#TotalAmount').text(0);
                            $('#FreightDisplay').text(0);
                            $('#GrandTotal').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_voucher_id').val(null);
                            $('#voucher_no').focus();
                            $('#dcn_no1').val(null);
                            $('#remarks').val(null);
                            $('#vehicle_no').val(null);
                            $('#transport_company').val(null);
                            $('#driver_name').val(null);
                            $('#builty_no').val(null);
                            $('#freight').val(null);
                            $('#driver_phoneno').val(null);
                        }
                    }
                });
            });
            // End Here of Load Previous Record
        });

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode == 46) {
                // Check if decimal point already exists in the input
                if (evt.target.value.indexOf('.') !== -1)
                    return false;
                else
                    return true;
            }
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }
    </script>
    <!-- End Load & Edit Record -->

    @include('include.toast-messages')
@stop
