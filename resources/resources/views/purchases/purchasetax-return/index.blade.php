@extends('app')
@section('head')
    <title>PurchaseTax Return</title>
    <!--  Select 2 library start-->
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!--  Select 2 library end-->
@stop
@section('content')
@if(isset($data))
<body onload="loadeditSale()">
@endif
    <!-- Sticky Left Side bar -->
    <div class="icon-bar">
        <a href="javascript:void(0);" class="load-previous-record"><i class="fa fa-angle-left"></i></a>
        <a href="javascript:void(0);" class="load-edit-record"><i class="fa fa-repeat"></i></a>
        <a href="javascript:void(0);" class="load-next-record"><i class="fa fa-angle-right"></i></a>
        <a href="javascript:void(0);" class="delete_record_btn"><i class="fa fa-trash-o"></i></a>
        <a href="javascript:void(0);" class="print_record_btn"><i class="fa fa-print"></i></a>
    </div>
    <!-- End Sticky Left Side bar -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>PurchaseTax Return</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">PurchaseTax Return</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <!-- <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Sales Invoice</h6>
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
                                    {!! Form::open(['url' => 'purchasetax-return', 'class' => 'form-horizontal', 'id' => 'sales-voucher-form']) !!}
                                    {!! Form::hidden('update_voucher_id', null, ['id' => 'update_voucher_id']) !!}
                                    {!! Form::hidden('dcn_id1', null, ['id' => 'dcn_id1']) !!}
                                    {{-- {!! Form::hidden('party_id', null, ['id' => 'party_id']) !!} --}}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
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
                                        <div class="col-lg-2 col-md-12 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $data->voucher_no, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                                'tabindex' => '1',
                                            ]) !!}
                                        </div>
                                        @else
                                        <div class="col-lg-2 col-md-12 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No. <span
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
                                        <div class="col-lg-2 col-md-12 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', null, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                                'tabindex' => '1',
                                            ]) !!}
                                        </div>
                                        @else
                                        <div class="col-lg-2 col-md-12 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No. <span
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
                                        <!-- <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                            <label for="warehouse_id"><i class="fa fa-caret-right"></i> Warehouse <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('warehouse_id', $warehouse, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2'
                                            ]) !!}
                                            @error('warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-6 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>Vr No#<span
                                                    class="text-danger">*</span></label>
                                            @if(isset($data))
                                            {!! Form::text('voucher_no', $data->voucher_no, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                            ]) !!}
                                            @else
                                            {!! Form::text('voucher_no', null, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                            ]) !!}
                                            @endif
                                            <span class="text-danger voucher_no_err"></span>
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div> -->
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="dcn_id" style="background-color:#666ee8;color:white;"><i class="fa fa-caret-right"></i>Return Type.<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('sale_return_type', $invoiceType, null, [
                                                'id' => 'sale_return_type',
                                                'class' => 'form-control select2',
                                                'tabindex' => '4',
                                            ]) !!}
                                            <span class="text-danger dcn_id_err"></span>
                                            @error('dcn_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1 d-none InvoiceNo">
                                            <label for="invoice_no"><i class="fa fa-caret-right"></i>Invoice No#<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('invoice_no', null, [
                                                'id' => 'invoice_no',
                                                'class' => 'form-control',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                            ]) !!}
                                            <span class="text-danger invoice_no_err"></span>
                                            @error('invoice_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                     
                                        <!-- <div class="col-lg-4 col-md-6 col-sm-12 mt-1 dcn_no1">
                                            <label for="dcn_id" style="background-color:#666ee8;color:white;"><i class="fa fa-caret-right"></i> DC No(Non Gst).<span
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
                                        </div> -->
                                        <!-- <div class="col-lg-2 col-md-3 col-sm-12 mt-1 dcn_no2">
                                            <label for="dcn_id"><i class="fa fa-caret-right"></i> DC No.<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('dcn_id', null, [
                                                'id' => 'dcn_id',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly',
                                            ]) !!}
                                            @error('dcn_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div> -->
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                            <label for="party_name"><i class="fa fa-caret-right"></i> Party Name<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('party_name', $customers, null, [
                                                'id' => 'party_name',
                                                'class' => 'form-control select2 customer',
                                                'tabindex' => '2',
                                                
                                            ]) !!}
                                            {!! Form::hidden('party_id', null, ['id' => 'party_id']) !!}
                                             <span class="text-danger party_name_err"></span>
                                             {{-- @error('party_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror --}}
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="party_name"><i class="fa fa-caret-right"></i>PO#<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('po_no1', null, [
                                                'id' => 'po_no1',
                                                'class' => 'form-control',
                                                'placeholder' => ' (Search Only)'
                                            ]) !!}
                                           
                                        </div>
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
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1">
                                            <label for="vehicle_no"><i class="fa fa-caret-right"></i> Vehicle No <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('vehicle_no', null, [
                                                'id' => 'vehicle_no',
                                                'class' => 'form-control',
                                                'tabindex' => '3',
                                                'readonly' => 'readonly',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1">
                                            <label for="transport_company"><i class="fa fa-caret-right"></i> Transport
                                                Company<span class="text-danger">*</span></label>
                                            {!! Form::text('transport_company', null, [
                                                'id' => 'transport_company',
                                                'class' => 'form-control',
                                                'tabindex' => '3',
                                                'readonly' => 'readonly',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1">
                                            <label for="driver_name"><i class="fa fa-caret-right"></i> Driver Name<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('driver_name', null, [
                                                'id' => 'driver_name',
                                                'class' => 'form-control',
                                                'tabindex' => '3',
                                                'readonly' => 'readonly',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1">
                                            <label for="builty_no"><i class="fa fa-caret-right"></i> Builty Number<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('builty_no', null, [
                                                'id' => 'builty_no',
                                                'class' => 'form-control',
                                                'tabindex' => '3',
                                                'readonly' => 'readonly',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1">
                                            <label for="freight"><i class="fa fa-caret-right"></i> Freight<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('freight', null, [
                                                'id' => 'freight',
                                                'class' => 'form-control',
                                                'tabindex' => '3',
                                                'readonly' => 'readonly',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1">
                                            <label for="driver_phoneno"><i class="fa fa-caret-right"></i> Driver
                                                PhoneNo<span class="text-danger">*</span></label>
                                            {!! Form::text('driver_phoneno', null, [
                                                'id' => 'driver_phoneno',
                                                'class' => 'form-control',
                                                'tabindex' => '3',
                                                'readonly' => 'readonly',
                                            ]) !!}
                                        </div>
                                       
                                    </div>

                                    <div class="row mt-3 productTab" >
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="table-responsive-md mb-2">
                                                <table class="table">
                                                    <thead>
                                                        <tr class="bg-primary text-left">
                                                            <th class="d-none">Code</th>
                                                            <th style="width:30%;">Product Name</th>
                                                            <th>Pack</th>
                                                            <th>Packing</th>
                                                            <th>Unit</th>
                                                            <th>Sale Qty</th>
                                                            <th>Return Qty</th>
                                                            <th>Rate</th>
                                                            <th>Amount</th>
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
                                                                {!! Form::text('pack1', null, [
                                                                    'id' => 'pack1',
                                                                    'class' => 'form-control',
                                                                    'disabled' => 'disabled',
                                                                    'placeholder' => 'Pack',
                                                                    'tabindex' => '9',
                                                                ]) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('packing1', null, [
                                                                    'id' => 'packing1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Packing',
                                                                    'tabindex' => '10',
                                                                   
                                                                    'disabled' => 'disabled',
                                                                ]) !!}
                                                                <span class="packing_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('unit1', null, [
                                                                    'id' => 'unit1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Unit',
                                                                    'tabindex' => '11',
                                                                   
                                                                    'disabled' => 'disabled',
                                                                ]) !!}
                                                                <span class="unit_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('sale_qty1', null, [
                                                                    'id' => 'sale_qty1',
                                                                    'class' => 'form-control bg-white',
                                                                    'onkeypress' => 'return isNumberKey(event)',
                                                                    'placeholder' => 'Sale Qty'
                                                                ]) !!}
                                                                <span class="sale_qty_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('return_qty1', null, [
                                                                    'id' => 'return_qty1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Return Qty',
                                                                    'onkeypress' => 'return isNumberKey(event)',
                                                                    'onkeyup' => 'QuantityKeyUp($(this).val())',
                                                                ]) !!}
                                                                <span class="return_qty_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('price1', null, [
                                                                    'id' => 'price1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Rate',
                                                                    'onkeypress' => 'return isNumberKey(event)',
                                                                    'onkeyup' => 'PriceKeyUp($(this).val())',
                                                                ]) !!}
                                                                <span class="price_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('total1', null, [
                                                                    'id' => 'total1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Amount',
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
                                                            <!-- <tr>
                                                                <th>PO#</th>
                                                                <th>Product&nbsp;Name</th>
                                                                <th>DemandQty</th>
                                                                <th>Packing</th>
                                                                <th>Unit</th>
                                                                <th>Sale Qty</th>
                                                                <th>Rate</th>
                                                                <th>Excl.val</th>
                                                                <th>ST Rate</th>
                                                                <th>Sale Tax</th>
                                                                <th>Total</th>
                                                            </tr> -->
                                                            <tr>
                                                            <th>Code</th>
                                                            <th>Product</th>
                                                            {{-- <th>IGP.Qty</th> --}}
                                                            <th>Rec.Qty</th>
                                                            <th>Unit</th>
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
                                                                <td colspan="2"><strong>Total</strong></td>
                                                                <td class="bg-primary" id="TotalPack">0</td>
                                                                
                                                                <td></td>
                                                                
                                                                <td></td>
                                                                <td class="bg-primary" id="TotalSaleQty">0</td>
                                                                <td class="bg-success" id="TotalReturnQty">0</td>
                                                                <td></td>
                                                                
                                                                <td class="bg-success" id="TotalAmount">0</td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                    
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                        
                                    </div>

                                    <div class="row">
                                                
                                                <!-- <div class="col-lg-3 col-md-12 col-12">
                                                    <div class="note note-danger">UnPosted By :</div>
                                                </div>
                                                <div class="col-lg-3 col-md-12 col-12">
                                                    <div class="note note-warning">Posted By : {{ Auth::User()->name }}</div>
                                                </div>
                                                <div class="col-lg-3 col-md-12 col-12">
                                                    <div class="note note-info">Updated By : <span class="d-none"
                                                            id="updated_by_name"> {{ Auth::User()->name }}</span></div>
                                                </div> -->
                                                <div class="col-lg-9 col-md-12 col-12">
                                                    <!-- <div class="note note-danger">UnPosted By :</div> -->
                                                </div>
                                                <div class="col-lg-2 col-md-12 col-12">
                                                    <button class="btn btn-primary submit-form" type="button">Save</button>
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
                <form action="{{ URL::to('purchasetax-return/delete-voucher') }}" method="post" id="delete_voucher_form">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Voucher?</p>
                        <input type="hidden" name="delete_voucher_no" id="delete_voucher_no" value="">
                        <input type="hidden" name="delete_warehouseid" id="delete_warehouseid" value="">
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
    <!-- End Delete Record Modal -->
    <!-- Print Record Modal -->
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
    <!-- End Print Record Modal -->
@stop
@section('scripts')
    <script>
        function TotalAmount() {
            var tableData = document.getElementById('GridTable');
            var sum =0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[8].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalAmount').innerText = sum.toLocaleString('en-US');
        }

       function changeSaleQty1(){
        var tableData = document.getElementById('GridTable');
            var sum =0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[5].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalSaleQty').innerText = sum.toLocaleString('en-US');
       }

        function TotalSaleReturn() {
            var tableData = document.getElementById('GridTable');
            var sum =0;
            for (var i = 0; i < tableData.rows.length; i++) {
                var total = parseFloat(tableData.rows[i].cells[6].getElementsByTagName('input')[0].value);
                if(total){
                    sum += parseFloat(tableData.rows[i].cells[6].getElementsByTagName('input')[0].value);
                }
            
            }
            document.getElementById('TotalReturnQty').innerText = sum.toLocaleString('en-US');
        }

        function TotalSaleQty() {
            var tableData = document.getElementById('GridTable');
            var sum =0;
          
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[5].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalSaleQty').innerText = sum.toLocaleString('en-US');
        }

        function TotalPack() {
            var tableData = document.getElementById('GridTable');
            var sum =0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[2].getElementsByTagName('input')[0].value);
                // alert(sum);
            }
            document.getElementById('TotalPack').innerText = sum.toLocaleString('en-US');
        }

        
        function TotalSale() {
            var tableData = document.getElementById('GridTable');
            var sum =0;
          
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[5].getElementsByTagName('input')[0].value);
                // alert(sum);
            }
            document.getElementById('TotalSaleQty').innerText = sum.toLocaleString('en-US');
        }



        function TotalQuantity() {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[2].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalIgpQty').innerText = sum;
        }

        function RetQty() {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                // alert(tableData.rows[i].cells[6].getElementsByTagName('input')[0].value);
                if(tableData.rows[i].cells[6].getElementsByTagName('input')[0].value > 0){
                    sum += parseFloat(tableData.rows[i].cells[6].getElementsByTagName('input')[0].value);
                }
                
            }
            document.getElementById('TotalReturnQty').innerText = sum;
        }

        function TotalExclVolue() {
            var tableData = document.getElementById('GridTable');

            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[7].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalExclVolue').innerText = sum;
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
            document.getElementById('TotalSaleTax').innerText = sum;
        }

        function changerate(row) {
            var ReturnQty = $(row).find("td:eq('6')").find('input').val();
            var rate = $(row).find("td:eq('7')").find('input').val();
            var amount = ReturnQty * rate;
            $(row).find("td:eq('8')").find('input').val(parseFloat(amount));
            // var saleqty = $(row).find("td:eq('5')").find('input').val();
            // var rate = $(row).find("td:eq('6')").find('input').val();
            // var saleTax = parseInt($(row).find("td:eq('8')").find('input').val());
// alert(rate);
//             var exclValue;
//             var stValue;
//             var totalAmount;
//             if (rate == null || rate == 0) {
//                 exclValue = 0
//                 stValue = 0
//                 totalAmount = 0
//             } else {
//                 ///Excl valu
//                 exclValue = rate * saleqty
//                 //st rate
//                 stValue = (rate * saleTax / 100) * saleqty;
//                 //total Amount
//                 totalAmount = stValue + exclValue;
//             }

//             $(row).find("td:eq('7')").find('input').val(parseInt(exclValue));
//             $(row).find("td:eq('9')").find('input').val(parseInt(stValue));
//             $(row).find("td:eq('10')").find('input').val(parseInt(totalAmount));
            TotalAmount();
            RetQty();
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

            $(row).find("td:eq('7')").find('input').val(parseInt(exclValue));
            $(row).find("td:eq('9')").find('input').val(parseInt(stValue));
            $(row).find("td:eq('10')").find('input').val(parseInt(totalAmount));
            TotalAmount();
            RetQty();
            TotalExclVolue();
            TotalSaleTax();
        }

        function changeSaleQty(row) {
            // alert("ddd");
            var sale_qty = $(row).find("td:eq('6')").find('input').val();
            var packing = parseInt($(row).find("td:eq('3')").find('input').val());
            var rate = $(row).find("td:eq('7')").find('input').val();
            // alert(sale_qty);
            var qty = sale_qty / packing;
            var totalAmount=sale_qty*rate;
            
            // $(row).find("td:eq('2')").find('input').val(Math.round(qty));
            if((Math.abs(((sale_qty) - Math.round(qty) * packing))) == '0'){
                $(row).find("td:eq('2')").find('input').val(Math.round(qty));
            }else{
                var final = parseInt(qty)+ 1;
                $(row).find("td:eq('2')").find('input').val(Math.round(final));
            }
            $(row).find("td:eq('8')").find('input').val(parseInt(totalAmount));
            TotalSaleReturn();
            TotalAmount();
            TotalPack();
        }
    </script>
    <!--fetching deliver challange data sat-->
    <script>
        $(document).ready(function() {
            $('#dcn_id').change(function(event) {
                var dcn_id = $(this).val();
                $.ajax({
                    url: "{{ asset('sales-voucher/getdcRecord') }}",
                    type: 'get',
                    data: {
                        dcn_id: dcn_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        var tableHtml = '';
                        var comments;
                        var grandQty = 0;
                        var grandPacking = 0;
                        var grandSaleQty = 0;
                        var grandExlVal = 0;
                        var grandSTRate = 0;
                        var grandTotal = 0;


                        $.each(response.data, function(i, v) {
                            if (v.product_id == null) {
                                var excl_val = v.sale_rate*v.demand_qty;
                                var sale_tax = ((v.product.tax/100)*(v.sale_rate*v.demand_qty));
                                var total = excl_val+sale_tax;

                                grandQty+=parseInt(v.quantity);
                                grandPacking+=parseInt(v.cusproduct.product.packing);
                                grandSaleQty+=parseInt(v.demand_qty);
                                grandExlVal+=excl_val;
                                grandSTRate+=v.product.tax;
                                grandTotal+=total;

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.cusproduct.product_code}
                                <input type='hidden' name='code[]' id='code' value='${v.cusproduct.product_code}' />
                                <input type='hidden' name='challantype' id='challantype' value='CDC' />
                                </td>`;
                                tableHtml += `<td>
                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.cusproduct.id}' />
                                    <input type='text' value='${v.cusproduct.product_name}' class='form-control' readonly />
                                </td>`;

                                tableHtml +=
                                    `<td>
                                        <input type='hidden' name='demandQty[]' id='demandQty' value='${v.demand_qty}' class='form-control' />
                                        <input type='hidden' name='qty[]' id='qty' value='${v.demand_qty}' class='form-control' />
                                        <input type='text' value='${v.demand_qty}' class='form-control' readonly />
                                    </td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.cusproduct.product.packing)}<input type='hidden'  value='${parseInt(v.cusproduct.product.packing)}' class='form-control' style="width:120px;"/>
                                <span class="qty_errr text-danger"></span>
                                </td>`;
                                tableHtml +=
                                    `<td>${v.cusproduct.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.cusproduct.product.uom}' /></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.sale_rate}' class='form-control' style="width:120px;" onkeyup="changerate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
                                <span class="rate_errr text-danger"></span>
                                </td>`;
                                tableHtml +=
                                    `<td><input type='text' value='${v.quantity}' id="sale_qty" name="sale_qty[]" class="form-control" onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" /></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.rate}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.cusproduct.product.tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;
                                tableHtml +=
                                    `<td style="width:130px;">
                                <input type='text' value='${total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                            </td>`;
                                tableHtml += `</tr>`;
                            } else {
                                var excl_val = v.sale_rate*v.demand_qty;
                                var sale_tax = ((v.product.tax/100)*(v.sale_rate*v.demand_qty));
                                var total = excl_val+sale_tax;

                                grandQty+=parseInt(v.demand_qty);
                                grandPacking+=parseInt(v.product.packing);
                                grandSaleQty+=parseInt(v.qty_out);
                                grandExlVal+=excl_val;
                                grandSTRate+=v.product.tax;
                                grandTotal+=total;

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product.code}
                                        <input type='hidden' name='code[]' id='code' value='${v.product.code}' />
                                        <input type='hidden' name='challantype' id='challantype' value='DC' />
                                </td>`;
                                tableHtml += `<td>
                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                    <input type='text' value='${v.product.product_name}' class='form-control' readonly />
                                </td>`;

                                tableHtml +=
                                    `<td>
                                        <input type='hidden' name='demandQty[]' id='demandQty' value='${v.demand_qty}' class='form-control' />
                                        <input type='hidden' name='qty[]' id='qty' value='${v.demand_qty}' class='form-control' />
                                        <input type='text' value='${v.demand_qty}' class='form-control' readonly />
                                    </td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.product.packing)}<input type='hidden' value='${parseInt(v.product.packing)}' class='form-control'/></td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;

                                tableHtml +=
                                    `<td><input type='text' value='${v.qty_out}' id="sale_qty" name="sale_qty[]" class="form-control" onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" readonly/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='rate[]' id='rate' 
                                    value='${v.sale_rate}' class='form-control' style="width:120px;" 
                                    onkeyup="changerate($(this).closest('tr'));" 
                                    onkeypress="return isNumberKey(event);" onchange="EnterKeyBoard($(this).closest('tr'));"/>
                                <span class="rate_errr text-danger"></span>
                                </td>`;
                                
                                tableHtml +=
                                    `<td style="width:130px;">
                                <input type='text' value='${total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                            </td>`;
                                tableHtml += `</tr>`;
                            }

                        });
                        // alert(response.data[0].delivery_challan.voucher_date);
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
                        // $('#TotalRecQty').text(grandPacking);
                        $('#TotalRecQty').text(grandSaleQty);
                        // $('#TotalExclVolue').text(TotalExclVolue);
                        // $('#TotalSaleTax').text(grandSTRate);
                        $('#TotalAmount').text(grandTotal);
                        TotalSaleTax();
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
                    $("#warehouse_id").select2('open');
                }
            });

            $('#warehouse_id').change(function(event) {
                $('#warehouse_id').select2().trigger('select2:close');
                $('#voucher_no').focus();
            });
            $('#voucher_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#sale_return_type").select2('open');
                }
            });

            $('#sale_return_type').change(function(event) {
                var dcn = $(this).val();
                // alert(dcn);
                // if (dcn) {
                    $('#sale_return_type').select2().trigger('select2:close');
                    // alert(dcn);
                    // $('#dcn_id').select2().trigger('select2:close');
                    if(dcn == "INVOICE"){
                        
                        // alert("en");
                        
                        $('.InvoiceNo').removeClass('d-none');
                        // document.getElementById("vehicle_no").disabled = true;
                        $('#invoice_no').focus();
                        $('.productTab').hide();
                        
                    }else{
                        $('.InvoiceNo').addClass('d-none');
                        $("#party_name").select2('open');
                        $('.productTab').show();
                        document.getElementById("vehicle_no").disabled = false;
                        document.getElementById("transport_company").disabled = false;
                        document.getElementById("driver_name").disabled = false;
                        document.getElementById("builty_no").disabled = false;
                        document.getElementById("freight").disabled = false;
                        document.getElementById("driver_phoneno").disabled = false;
                        // $('#remarks').focus();  
                    }
                    
                // }
                
            });

            // $('#sale_return_type').change(function(event) {
            //     var dcn = $(this).val();
            //     if (dcn) {
            //         $('#sale_return_type').select2().trigger('select2:close');
            //         $('#remarks').focus();
            //     }
            // });
            $('#invoice_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#po_no1").focus();
                }
            });
            $('#po_no1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#remarks").focus();
                }
            });
            $('#remarks').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#vehicle_no").focus();
                }
            });
            $('#vehicle_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#transport_company").focus();
                }
            });
            $('#transport_company').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#driver_name").focus();
                }
            });
            $('#driver_name').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#builty_no").focus();
                }
            });
            $('#builty_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#freight").focus();
                }
            });
            $('#freight').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#driver_phoneno").focus();
                }
            });
            $('#driver_phoneno').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#product_id1").select2('open');
                }
            });
            $('#sale_qty1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#return_qty1").focus();
                }
            });
            $('#return_qty1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#price1").select();
                }
            });
           
            // $('#party_name').change(function(event) {
            //     var party_name = $(this).val();
            //     if (party_name) {
            //         $('#party_name').select2().trigger('select2:close');
            //         var party = $(this).val();
            //         $('#party_id').val(party.split('_')[0]);
            //         $('#address').val(party.split('_')[2]);
            //         $('#remarks').focus();

            //     }
            // });
            $('#party_name').change(function() {
            
            var cat_id = $(this).val();
            $('#party_id').val(cat_id);
            // alert(cat_id);
            $.ajax({
                url: "{{ asset('sales-order/getcustomer/product') }}",
                type: 'get',
                data: {
                    cat_id: cat_id
                },
                dataType: 'json',
                success: function(response) {
                    $('#remarks').focus();
                    if (response.company == false) {
                        if (response.products.length > 0) {
                            // $('#unit1').val(response.products[0].product.uom);
                            // $('#packing1').val(parseFloat(response.products[0].product.packing));
                            // $('#s_tax1').val(parseFloat(response.products[0].product.tax));
                            // alert("1");
                            // var option = '';
                            var option = `<option value="" selected>Select Customer Product</option>`;
                            $.each(response.products, function(i, v) {
                                option +=
                                    `<option value="${v.id}_${v.product.uom}_${v.product.packing}_${v.product.tax}_${v.product_name}_${v.product_code}">${v.product_code} - ${v.product_name}</option>`;
                                    // `<option value="${v.id}">${v.product_code} - ${v.product_name}</option>`;

                            });
                            $('#product_id1').html(option);
                        }
                    } else {
                        if (response.products.length > 0) {
                            $('#po_no1').focus();
                            // $('#unit1').val('');
                            // $('#packing1').val('');
                            // $('#s_tax1').val();
                            // alert("11");
                            var option = `<option value="" selected>Select Product</option>`;
                            $.each(response.products, function(i, v) {
                                option +=
                                    `<option value="${v.id}_${v.uom}_${v.packing}_${v.tax}_${v.product_name}_${v.code}">${v.code} - ${v.product_name}</option>`;
                                    // `<option value="${v.id}">${v.code} - ${v.product_name}</option>`;

                            });
                            $('#product_id1').html(option);

                        }
                    }
                }
            });
        });
            // $('#remarks').keydown(function(event) {
            //     var keycode = (event.keyCode ? event.keyCode : event.which);
            //     if (keycode == '13') {
            //         $('#qty').focus();
            //     }
            // });
                    // $('#remarks').keydown(function(event) {
        //             var keycode = (event.keyCode ? event.keyCode : event.which);
        //             // alert("dd")
        //             if (keycode == '13') {
        //                 $('#rate').select();
        //             }
        //         });
            $(document).on('keydown', '#rate', function(event) {
                let row = $(this).closest('tr');
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $(row).find("td:eq('8')").find('input').focus();
                }
            });
            // $('#product_id1').change(function(event) {
            //     var product_id = $(this).val();
            //     if (product_id != null) {
            //         $('#code1').val(product_id.split('_')[1]);
            //         $('#code2').val(product_id.split('_')[1]);
            //         $('#unit1').val(product_id.split('_')[3]);
            //         $('#product_id1').select2().trigger('select2:close');
            //         $('#price1').val(parseInt(product_id.split('_')[4]));
            //         $('#total1').val(parseInt(product_id.split('_')[4]));
            //         $('#qty1').val(1);
            //         $('#qty1').focus();
            //     }
            // });
            $('#product_id1').change(function(event) {
                // var  productID = $(this).val();
                var productID = document.getElementById('product_id1').value.split('_')[0];
                var partyID = document.getElementById('party_name').value;
                //  alert (productID);
                //  alert (partyID);
                var VoucherDate = $('#date').val(); //1700
                $.ajax({
                    url: "{{ URL::to('sales-order/product/keyup') }}",
                    type: 'get',
                    data: {
                        productID: productID, VoucherDate: VoucherDate, partyID: partyID,
                    },
                    dataType: 'json',
                    success: function(response) {

                        if (response.company == false) {
                        // Customer Product
                        // if (response.products.length > 0) {
                            $('#product_id1').select2().trigger('select2:close');
                            $('#code1').val(response.products[0].product.code); //pcs
                            $('#productName').val(response.products[0].product.product_name); //pcs-
                            $('#unit1').val(response.products[0].product.uom); //pcs
                            $('#packing1').val(response.products[0].product.packing); //8300
                            if(response.products[0].product.product_rates[0]){
                                // alert("ddd")
                                $('#s_tax1').val(response.products[0].product.product_rates[0].tax_rate);
                                $('#price1').val(response.products[0].product.product_rates[0].new_rate);
                            }else{
                                $('#s_tax1').val(0);
                                $('#price1').val(0);
                            }
                            
                            $('#sale_qty1').focus();
                        // }
                        }
                        if (response.company == true) {
                        // Original Product
                        // if (response.products.length > 0) {
                            $('#product_id1').select2().trigger('select2:close');
                            $('#code1').val(response.products.code); //pcs
                            $('#productName').val(response.products.product_name); //pcs-
                            $('#unit1').val(response.products.uom); //pcs
                            $('#packing1').val(response.products.packing); //8300

                            if(response.products.product_rates[0]){
                                // alert("ddd")
                                $('#s_tax1').val(response.products.product_rates[0].tax_rate); //1700
                                $('#price1').val(response.products.product_rates[0].new_rate); //1700
                            }else{
                                $('#s_tax1').val(0);
                                $('#price1').val(0);
                            }
                            $('#sale_qty1').focus();
                        // }
                        }
                    

                    // $('#product_id1').select2().trigger('select2:close');
                    // // $('#price1').val(parseInt(product_id.split('_')[4]));
                    // $('#unit1').val(product_id.split('_')[1]);
                    // $('#packing1').val(parseInt(product_id.split('_')[2]));
                    // // $('#s_tax1').val(parseInt(product_id.split('_')[3]));
                    // $('#s_tax1').val(0);
                    // $('#price1').val(parseInt(product_id.split('_')[5]));
                    }

                });
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
            // $('#price1').keydown(function(event) {
            //     var keycode = (event.keyCode ? event.keyCode : event.which);
            //     if (keycode == '13') {
            //         $("#product_id1").select2("open");
            //     }
            // });
            $('#price1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('.product_err').text('');
                    $('.return_qty_err').text('');
                    $('.price_err').text('');


                    var product_id = parseInt($('#product_id1').val());
                    var qty = parseInt($('#return_qty1').val());
                    var price = parseInt($('#price1').val());

                    if (!product_id) {
                        $('#product_id').select2('open');
                        $('.product_err').text('This field is required');
                        return false;
                    } else
                    if (!qty || qty <= 0) {
                        $('#return_qty1').focus();
                        $('.return_qty_err').text('This Qty field is required');
                        return false;
                    } else
                    if (!price || price <= 0) {
                        $('#price1').focus();
                        $('.price_err').text('This Price field is required');
                        return false;
                    } else {
                        AddGridData();
                        TotalSaleReturn();
                        TotalAmount();
                        TotalPack();
                        TotalSale();
                        // $("#product_id1").select2("open");
                    }
                }
            });
        });
    </script>
    <!-- End Focus on next field -->

    <!-- Append New Data on Table -->
    <script>
        //   function TotalPack() {
        //     var tableData = document.getElementById('GridTable');
        //     var sum = 0;
        //     for (var i = 0; i < tableData.rows.length; i++) {
        //         sum += parseFloat(tableData.rows[i].cells[2].getElementsByTagName('input')[0].value);
        //     }
        //     document.getElementById('TotalPack').innerText = sum.toLocaleString('en-US');
        // }
        function loadeditSale(){
            $('.load-edit-record').click();
        }
        function AddGridData() {
            var product_id = parseInt($('#product_id1').val());
            if (!product_id) {
                $('.product_err').text('This field is required');
                $('#product_id1').focus();
                return false;
            } else {
                $('.product_err').text('');
            }
            var qty = parseFloat($('#return_qty1').val());
            if (!qty || qty <= 0) {
                $('.return_qty_err').text('This Quantity field is required');
                $('#return_qty1').focus();
                return false;
            } else {
                $('.return_qty_err').text('');
            }
            var price = parseInt($('#price1').val());
            if (!price || price <= 0) {
                $('.price1_err').text('This Price field is required');
                $('#price1').focus();
                return false;
            } else {
                $('.price1_err').text('');
            }
            var proID = document.getElementById('product_id1').value.split('_')[0];
            var unit = document.getElementById('product_id1').value.split('_')[1];
            var packing = document.getElementById('product_id1').value.split('_')[2];
            var tax = document.getElementById('product_id1').value.split('_')[3];
            var ProductName = document.getElementById('product_id1').value.split('_')[4];
            var ProductCode = document.getElementById('product_id1').value.split('_')[5];
            // var pro_cost = parseInt(document.getElementById('product_id1').value.split('_')[5]);
            var pack = parseInt(document.getElementById('pack1').value);
            var saleQty = parseInt(document.getElementById('sale_qty1').value);
            var returnQty = parseInt(document.getElementById('return_qty1').value);
            var price = parseInt(document.getElementById('price1').value);
            var total = parseInt(document.getElementById('total1').value);
            // alert(ProductCode);
            // var qty = parseInt(document.getElementById('qty1').value);
            // var total = parseInt(document.getElementById('total1').value);
            // var TotalQty = parseInt(document.getElementById('TotalQty').innerHTML);
            // var TotalAmount = parseInt(document.getElementById('TotalAmount').innerHTML);
            // var cost_amount = pro_cost * qty;
            // var sale_amount = price * qty;
            // var grandTotalQty = TotalQty + qty;
            // var grandTotalAmount = TotalAmount + total;
            var tableHtml = `<tr>`;
            tableHtml +=
                `<td>${ProductCode}<input type='hidden' name='code[]' id='code' value='${ProductCode}' /></td>`;
            tableHtml += `<td>
                <input type='hidden' name='product_id[]' id='product_id' value='${proID}' />
                <input type='text' value='${ProductName}' class='form-control' readonly />
            </td>`;
            tableHtml +=
                `<td><input type='text' name='pack[]' id='pack' value='${pack}' class='form-control' readonly /></td>`;
            tableHtml +=
                `<td>${packing} <input type='hidden' value='${packing}' /></td>`;
            tableHtml +=
                `<td>${unit}<input type='hidden' name='unit[]' id='unit' value='${unit}' /></td>`;
            tableHtml +=
                `<td><input type='text' value='${saleQty}' id="sale_qty" name="sale_qty[]" onkeyup="changeSaleQty1($(this).closest('tr'));" class='form-control'  onkeypress="return isNumberKey(event)"/></td>`;
            tableHtml +=
                `<td style="width:120px;"><input type='text' name='return_qty[]' id='return_qty' value='${returnQty}' class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
            <span class="rate_errr text-danger"></span>
            </td>`;
            tableHtml +=`<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${price}' class='form-control' onkeyup="changerate($(this).closest('tr'));" onchange="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
            <span class="rate_errr text-danger"></span>
            </td>`;
            tableHtml +=
                `<td style="width:130px;"><input type='text'  value='${total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                </td>`;
            tableHtml += `</tr>`;
            $('#GridTable').append(tableHtml);
            $('#product_id1').select2('open');
            // $('#TotalQty').html(grandTotalQty);
            // $('#TotalAmount').html(grandTotalAmount);
            $('#code1').val(null);
            $('#unit1').val(null);
            $('#unit2').val(null);
            $('#price1').val(null);
            $('#qty1').val(null);
            $('#total1').val(null);
            $('#product_id1').val(null);
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
                // alert(party_name);
                $('.voucher_no_err').text('');
                // $('.dcn_id_err').text('');
                $('.party_name_err').text('');


                if(!voucher_no)
                {
                    
                    $('.voucher_no_err').text('The voucher no field is required.');
                    return false;
                }else
                // if(!dcn_id)
                // {
                //     alert("2")
                //     $('.dcn_id_err').text('The DC field is required.');
                //     return false;
                // }else
                if(!party_name)
                {
                    // alert("3")
                    $('.party_name_err').text('The party field is required.');
                    return false;
                }else{
                    // alert("4")
                    $('#sales-voucher-form').submit();
                    $('.submit-form').attr('disabled', true);
                }
                // alert(voucher_no)
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
            });
            // End Reset btn feature
        });
    </script>


    <!-- OnChange Qty -->
    <script>
        function changeQty(row) {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[3].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalQty').innerText = sum;
        }

        function EnterKeyBoard(row){
            // alert("enter") 
                    var RowIndex = row.index();
                if(event.keyCode == 13) {
                if(RowIndex = '0')
                 $('tr:eq(' + RowIndex + ')', GridTable).find("td:eq('6')").find('input').select();
                }
                if(NextIndex != '0')
                {
                var NextIndex = RowIndex + 1;
                 $('tr:eq(' + NextIndex + ')', GridTable).find("td:eq('6')").find('input').select();
                }
               
            }

        function DeleteRow(row) {
            $(row).remove();
            TotalPack();
            TotalSaleQty();
            RetQty();
            TotalAmount();
            // TotalExclVolue();
            // TotalSaleTax();
           
        }

        function PriceKeyUp(price) {
            var quantity = document.getElementById('return_qty1').value;
            if (quantity == '') {
                document.getElementById('total1').value = price;
            } else {
                var total = quantity * price;
                document.getElementById('total1').value = total;
            }
        }

        function QuantityKeyUp(quantity) {
            var packing = document.getElementById('packing1').value;
            var pack = quantity / packing;
            // document.getElementById('pack1').value = pack;

            if((Math.abs((parseInt(pack)*packing)-(quantity))) == 0){
                $('#pack1').val(Math.round(pack));
                // document.getElementById('pack1').value = pack;
                
                }else{
                var final = parseInt(pack)+ 1;
                $('#pack1').val(final);
                }

            // alert(pack);
            var price = document.getElementById('price1').value;
            var total = quantity * price;
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
                var warehouseID = parseInt($('#warehouse_id').val());
                $('#delete_voucher_no').val(voucher_no);
                $('#delete_warehouseid').val(warehouseID);
            });
            // Print Record
            $('.print_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                myModal.toggle();

                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                // var billtype = $('#billtype').val();
                var base_url = $('#base_url').val();
                $.ajax({
                    url: "{{ URL::to('purchasetax-return/print/voucher') }}",
                        
                    type: 'get',
                    data: {
                        voucher_no: voucher_no,
                        warehouseID: warehouseID,
                    },
                    beforeSend: function(response) {
                        $('#print-receipt-modal-body').html(
                            '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                        );
                    },
                    success: function(response) {
                        if (response != null && response != 0) {
                            $('#print-receipt-modal-body').html(
                                `<object data="${base_url}/resources/upload/sales-voucher/${response}" type="application/pdf" width="100%" height="800"></object>`
                            );
                        } else {
                            $('#print-receipt-modal-body').html(
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

            
            
            $('#invoice_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var invoice_no = parseInt($('#invoice_no').val());
                    var warehouseID = parseInt($('#warehouse_id').val());
                    // alert(1);
                $.ajax({
                    url: "{{ URL::to('purchasetax-return/load/invoice') }}",
                    type: 'get',
                    data:{invoice_no:invoice_no, warehouseID:warehouseID},
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    // success: function(response) {
                    //     if (response.data != '') {
                    //         var tableHtml = '';
                    //         var totalQty = 0;
                    //         if (response.data[0].salepurchase.challan_type == 'CDC') {
                    //             $.each(response.data, function(i, v) {
                    //                 totalQty += parseInt(v.demandQty);
                    //                 tableHtml += `<tr>`;
                    //                 tableHtml +=`<td>${v.po_no}<input type='hidden' name='po_no[]' id='po_no' value='${v.po_no}' /></td>`;
                    //                 tableHtml += `<td>
                    //                         <input type='hidden' name='product_id[]' id='product_id' value='${v.cusproduct.id}' />
                    //                         <input type='text' value='${v.cusproduct.product_code} - ${v.cusproduct.product_name}' class='form-control' readonly />
                    //                     </td>`;

                    //                 tableHtml +=
                    //                     `<td >${v.demandQty}<input type='hidden' name='demandQty[]' id='demandQty' value='${v.demandQty}' class='form-control'/></td>`;
                    //                 tableHtml +=
                    //                     `<td style="width:120px;">
                    //                         <input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control' style="width:120px;"/>
                    //                         <span class="qty_errr text-danger"></span>
                    //                     </td>`;
                    //                 tableHtml +=
                    //                     `<td>${v.cusproduct.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.cusproduct.product.uom}' /></td>`;
                    //                 tableHtml +=
                    //                     `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' style="width:120px;" onchange="EnterKeyBoard($(this).closest('tr'));" onkeyup="changerate($(this).closest('tr'));"/>
                    //                     <span class="rate_errr text-danger"></span>
                    //                     </td>`;
                    //                 tableHtml +=
                    //                     `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                    //                 tableHtml +=
                    //                     `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.st_rate}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;
                    //                 tableHtml +=
                    //                     `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;
                    //                 tableHtml +=
                    //                     `<td style="width:130px;">
                    //                     <input type='text' value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                    //                 </td>`;
                    //                 tableHtml +=
                    //                 `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                    //                 tableHtml += `</tr>`;

                    //             });
                    //         } else {

                    //             $.each(response.data, function(i, v) {

                    //                 totalQty += parseFloat(v.demandQty);
                    //                 tableHtml += `<tr>`;
                    //                 tableHtml +=
                    //                     `<td>${v.po_no}<input type='hidden' name='po_no[]' id='po_no' value='${v.po_no}' /></td>`;
                    //                 tableHtml += `<td>
                    //                     ${v.product.code}-${v.product.product_name}
                    //                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                    //                </td>`;
                    //                 tableHtml +=
                    //                     `<td>${v.qty}
                    //                         <input type='hidden' name='demandQty[]' id='demandQty' value='${v.demandQty}' class='form-control'/>
                    //                         <input type='hidden' name='qty[]' id='qty' value='${v.qty}' class='form-control'/>
                                            
                    //                     </td>`;
                    //                 tableHtml +=
                    //                     `<td>${parseInt(v.product.packing)} <input type='hidden'  value='${v.product.packing}' /></td>`;
                    //                 tableHtml +=
                    //                     `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                    //                 tableHtml +=
                    //                     `<td>${parseInt(v.sale_qty)}<input type='hidden' value='${parseInt(v.sale_qty)}' id="sale_qty" name="sale_qty[]" class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" readonly/></td>`;
                    //                 tableHtml +=
                    //                     `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' style="width:120px;" onkeyup="changerate($(this).closest('tr'));" onkeydown="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
                    //                <span class="rate_errr text-danger"></span>
                    //                </td>`;
                    //                 tableHtml +=
                    //                     `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                    //                 tableHtml +=
                    //                     `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.st_rate}' class='form-control' style="width:120px;" onkeyup="changetax($(this).closest('tr'));" onkeydown="EnterStRate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                    //                 tableHtml +=
                    //                     `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;

                    //                 tableHtml +=
                    //                     `<td style="width:130px;">
                    //                <input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                    //            </td>`;
                    //                 tableHtml += `</tr>`;

                    //             });
                    //         }
                    //         $('#GridTable').html(tableHtml);
                    //         // $('#TotalIgpQty').text(totalQty);
                    //         $('#updated_by_name').removeClass('d-none');
                    //         // $('.dcn_no1').addClass('d-none');
                    //         // $('.dcn_no2').removeClass('d-none');
                    //         // $('.dcn_no2 input').val(response.data[0].salepurchase.dc
                    //         //     .voucher_no);
                    //         // $('#dcn_id1').val(response.data[0].salepurchase.dc.id);
                    //         // $('#update_voucher_id').val(response.data[0].salepurchase.id);
                    //         // alert(response.data[0].party_id);
                    //         $('#date').val(response.data[0].salepurchase.date);
                    //         $('#po_no1').val(response.data[0].po_no);
                    //         $('#party_id').val(response.data[0].party.id);
                    //         $('#party_name').val(response.data[0].party_id).select2();
                    //         // $('#voucher_no').focus();
                    //         $('#remarks').val(response.data[0].salepurchase.remarks);
                    //         $('#vehicle_no').val(response.data[0].salepurchase.vehicle_no);
                    //         $('#transport_company').val(response.data[0].salepurchase.transport_company);
                    //         $('#driver_name').val(response.data[0].salepurchase.driver_name);
                    //         $('#builty_no').val(response.data[0].salepurchase.builty_no);
                    //         $('#freight').val(response.data[0].salepurchase.freight);
                    //         $('#driver_phoneno').val(response.data[0].salepurchase
                    //             .driver_phoneno);
                    //         // $('#party_name').val(response.data[0].party.id + "_" + response
                    //         //     .data[0].party.party_name + "_" + response.data[0].party
                    //         //     .address).select2();
                    //         // $('#party_name').val(
                    //         //     response.data[0].party.id + "_" + 
                    //         //     response.data[0].party.party_name + "_" + 
                    //         //     response.data[0].party
                    //         //     .address).select2();
                           
                    //         TotalAmount();
                    //         TotalSaleQty();
                    //         RetQty();
                    //         TotalExclVolue();
                    //         TotalSaleTax();
                    //     } else {
                    //         $('#show_err').html(
                    //             '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                    //         );

                    //         $('#GridTable').html(null);
                    //         // $('#TotalIgpQty').text(0);
                    //         // $('#TotalRecQty').text(0);
                    //         $('#TotalExclVolue').text(0);
                    //         $('#TotalSaleTax').text(0);
                    //         $('#TotalAmount').text(0);
                    //         $('#updated_by_name').addClass('d-none');

                    //         $('#update_voucher_id').val(null);
                    //         // $('#voucher_no').focus();
                    //         // $('#dcn_no1').val(null);
                    //         $('#remarks').val(null);
                    //         $('#vehicle_no').val(null);
                    //         $('#transport_company').val(null);
                    //         $('#driver_name').val(null);
                    //         $('#builty_no').val(null);
                    //         $('#freight').val(null);
                    //         $('#driver_phoneno').val(null);
                    //     }
                    // }
                    success: function(response) {
                                if (response.data != '') {
                                    var tableHtml = '';
                                    var totalQty = 0;
                                   
                                    $.each(response.data, function(i, v) {
                                        var comment = '';
                                        if (v.comments != null) {
                                            comment = v.comments;
                                        }

                                        totalQty += parseInt(v.demandQty);
                                        tableHtml += `<tr>`;
                                        tableHtml +=
                                            `<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                        tableHtml += `<td>${v.product.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                                </td>`;
                                        
                                        // tableHtml +=
                                        //     `<td >${v.demandQty}<input type='hidden' name='demandQty[]' id='demandQty' value='${v.demandQty}' class='form-control'/></td>`;
                                        tableHtml +=
                                            `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control' style="width:120px;" readonly='readonly'/>
                                                <span class="qty_errr text-danger"></span>
                                                </td>`;
                                        tableHtml +=
                                            `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                            tableHtml +=
                                            `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' style="width:120px;" onkeypress="return isNumberKey(event);" 
                                            onkeydown="EnterKeyBoard($(this).closest('tr'));"
                                            onkeypress="return isNumberKey(event)"
                                            onkeyup="changerate($(this).closest('tr'));"/>
                                                <span class="rate_errr text-danger"></span>
                                                </td>`;
                                            tableHtml +=
                                            `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                                            tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.st_rate}' class='form-control' style="width:120px;" onkeyup="changetax($(this).closest('tr'));"
                                        onkeydown="EnterStRate($(this).closest('tr'));"
                                
                                        onkeypress="return isNumberKey(event)"
                                        /></td>`;
                                            tableHtml +=
                                            `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;
                                            
                                         tableHtml +=
                                            `<td style="width:130px;">
                                                <input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                                            </td>`;
                                        tableHtml += `</tr>`;
                                      
                                    });
                                    
                                    $('#GridTable').html(tableHtml);
                                    // $('#TotalIgpQty').text(totalQty);
                                    //  TotalSaleTax();
                                    //  TotalAmount();
                                    //  recQty();
                                    // TotalExclVolue();
                                    // $('#updated_by_name').removeClass('d-none');
                                    // $('.grn-no2 input').val(response.data[0].salepurchase.grn
                                    // .voucher_no);
                                    // $('#grn_id1').val(response.data[0].salepurchase.grn
                                    // .id);
                                    // $('.grn-no2').removeClass('d-none');
                                    // $('.grn-no1').addClass('d-none');
                                    $('#date').val(response.data[0].salepurchase.date);
                                        $('#po_no1').val(response.data[0].po_no);
                                        $('#party_id').val(response.data[0].party.id);
                                        $('#party_name').val(response.data[0].party_id).select2();
                                        // $('#voucher_no').focus();
                                        $('#remarks').val(response.data[0].salepurchase.remarks);
                                        $('#vehicle_no').val(response.data[0].salepurchase.vehicle_no);
                                        $('#transport_company').val(response.data[0].salepurchase.transport_company);
                                        $('#driver_name').val(response.data[0].salepurchase.driver_name);
                                        $('#builty_no').val(response.data[0].salepurchase.builty_no);
                                        $('#freight').val(response.data[0].salepurchase.freight);
                                        $('#driver_phoneno').val(response.data[0].salepurchase
                                            .driver_phoneno);
                                    // $('#date').val(response.data[0].salepurchase.date);
                                    // $('#vehicle_no').val(response.data[0].salepurchase.vehicle_no);
                                    // $('#transport_company').val(response.data[0].salepurchase.transport_company);
                                    // $('#driver_name').val(response.data[0].salepurchase.grn.inward_gatepass.driver_name);
                                    // $('#driver_phoneno').val(response.data[0].salepurchase.grn.inward_gatepass.driver_phoneno);
                                    // $('#builty_no').val(response.data[0].salepurchase.grn.inward_gatepass.builty_no);
                                    // $('#supplier_id').val(response.data[0].salepurchase.grn.inward_gatepass.supplier.party_name);
                                    // $('#supplier_id1').val(response.data[0].salepurchase.grn.inward_gatepass.supplier.id);
                                    // // $('#purchaser_id').val(response.data[0].salepurchase.grn.inward_gatepass.purchaser.party_name);
                                    // $('#purchaser_id').val(response.data[0].salepurchase.grn.inward_gatepass.purchaser.party_name);
                                    // $('#purchaser_id1').val(response.data[0].salepurchase.purchaser_id);
                                    // $('#update_voucher_id').val(response.data[0].salepurchase.id);
                                    // $('#remarks').val(response.data[0].salepurchase.remarks);
                                    // $('#credit_to').val(response.data[0].salepurchase.credit_to).select2();
                                    // $('#voucher_no').val(response.data[0].salepurchase.voucher_no);
                                    $('#voucher_no').focus();
                                } else {
                                    $('#show_err').html(
                                        '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                                    );

                                    $('#GridTable').html(null);
                                    $('#TotalRecQty').text(0);
                                    $('#TotalIgpQty').text(0);
                                    $('#TotalExclVolue').text(0);
                                    $('#TotalSaleTax').text(0);
                                    $('#TotalAmount').text(0);
                                    $('#updated_by_name').addClass('d-none');
                                    $('#update_voucher_no').val(null);
                                    $('#date').focus();
                                    $('#voucher_no').focus();
                                    $('#dcn_no1').val(null);
                                    $('#remarks').val(null);
                                    $('#vehicle_no').val(null);
                                    $('#transport_company').val(null);
                                    $('#driver_name').val(null);
                                    $('#builty_no').val(null);
                                    $('#supplier_id').val(null);
                                    $('#purchaser_id').val(null);
                                    $('#driver_phoneno').val(null);
                                }
                    }
                });
                }
            });

            $('#po_no1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var pono = $('#po_no1').val();
                    var warehouseID = parseInt($('#warehouse_id').val());
                    // alert(1);
                $.ajax({
                    url: "{{ URL::to('purchasetax-return/load/pono') }}",
                    type: 'get',
                    data:{pono:pono, warehouseID:warehouseID},
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
                            if (response.data[0].salepurchase.challan_type == 'CDC') {
                                $.each(response.data, function(i, v) {
                                    totalQty += parseInt(v.demandQty);
                                    tableHtml += `<tr>`;
                                    tableHtml +=`<td>${v.po_no}<input type='hidden' name='po_no[]' id='po_no' value='${v.po_no}' /></td>`;
                                    tableHtml += `<td>
                                            <input type='hidden' name='product_id[]' id='product_id' value='${v.cusproduct.id}' />
                                            <input type='text' value='${v.cusproduct.product_code} - ${v.cusproduct.product_name}' class='form-control' readonly />
                                        </td>`;

                                    tableHtml +=
                                        `<td >${v.demandQty}<input type='hidden' name='demandQty[]' id='demandQty' value='${v.demandQty}' class='form-control'/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;">
                                            <input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control' style="width:120px;"/>
                                            <span class="qty_errr text-danger"></span>
                                        </td>`;
                                    tableHtml +=
                                        `<td>${v.cusproduct.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.cusproduct.product.uom}' /></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' style="width:120px;" onchange="EnterKeyBoard($(this).closest('tr'));" onkeyup="changerate($(this).closest('tr'));"/>
                                        <span class="rate_errr text-danger"></span>
                                        </td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.st_rate}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;
                                    tableHtml +=
                                        `<td style="width:130px;">
                                        <input type='text' value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                                    </td>`;
                                    tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;

                                });
                            } else {

                                $.each(response.data, function(i, v) {

                                    totalQty += parseFloat(v.demandQty);
                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                        `<td>${v.po_no}<input type='hidden' name='po_no[]' id='po_no' value='${v.po_no}' /></td>`;
                                    tableHtml += `<td>
                                        ${v.product.code}-${v.product.product_name}
                                       <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                   </td>`;
                                    tableHtml +=
                                        `<td>${v.qty}
                                            <input type='hidden' name='demandQty[]' id='demandQty' value='${v.demandQty}' class='form-control'/>
                                            <input type='hidden' name='qty[]' id='qty' value='${v.qty}' class='form-control'/>
                                            
                                        </td>`;
                                    tableHtml +=
                                        `<td>${parseInt(v.product.packing)} <input type='hidden'  value='${v.product.packing}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=
                                        `<td>${parseInt(v.sale_qty)}<input type='hidden' value='${parseInt(v.sale_qty)}' id="sale_qty" name="sale_qty[]" class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" readonly/></td>`;
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
                            }
                            $('#GridTable').html(tableHtml);
                            $('#TotalIgpQty').text(totalQty);
                            $('#updated_by_name').removeClass('d-none');
                            $('.dcn_no1').addClass('d-none');
                            $('.dcn_no2').removeClass('d-none');
                            $('.dcn_no2 input').val(response.data[0].salepurchase.dc
                                .voucher_no);
                            $('#dcn_id1').val(response.data[0].salepurchase.dc.id);
                            // $('#update_voucher_id').val(response.data[0].salepurchase.id);
                            $('#date').val(response.data[0].salepurchase.date);
                            $('#po_no1').val(response.data[0].po_no);
                            // $('#voucher_no').focus();
                            $('#remarks').val(response.data[0].salepurchase.remarks);
                            $('#vehicle_no').val(response.data[0].salepurchase.dc.vehicle_no);
                            $('#transport_company').val(response.data[0].salepurchase.dc
                                .transport_company);
                            $('#driver_name').val(response.data[0].salepurchase.dc.driver_name);
                            $('#builty_no').val(response.data[0].salepurchase.dc.builty_no);
                            $('#freight').val(response.data[0].salepurchase.dc.freight);
                            $('#driver_phoneno').val(response.data[0].salepurchase.dc
                                .driver_phoneno);
                            // $('#party_name').val(response.data[0].party.id + "_" + response
                            //     .data[0].party.party_name + "_" + response.data[0].party
                            //     .address).select2();
                            // $('#party_name').val(
                            //     response.data[0].party.id + "_" + 
                            //     response.data[0].party.party_name + "_" + 
                            //     response.data[0].party
                            //     .address).select2();
                            $('#party_id').val(response.data[0].party.id);
                            $('#party_name').val(response.data[0].party.id).select2();
                            TotalAmount();
                            TotalSaleQty();
                            RetQty();
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
                            $('#updated_by_name').addClass('d-none');

                            $('#update_voucher_id').val(null);
                            // $('#voucher_no').focus();
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
                }
            });

            // $('#invoice_no').keyup(function() {
   
            // });



            $('.load-edit-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                $.ajax({
                    url: "{{ URL::to('purchasetax-return/load/record') }}",
                    type: 'get',
                    data:{
                        voucher_no:voucher_no,
                        warehouseID:warehouseID
                    },
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
                            if (response.status == 0) {
                    

                                $.each(response.data, function(i, v) {
                                    // alert("1");
                                totalQty += parseFloat(v.demandQty);
                                tableHtml += `<tr>`;
                                       tableHtml +=
                                        `<td>${v.cusproduct.product_code}<input type='hidden' name='code[]' id='code' value='${v.cusproduct.product_code}' /></td>`;
                                    tableHtml 
                                              += `<td>${v.cusproduct.product_name}
                                            <input type='hidden' name='product_id[]' id='product_id' value='${v.cusproduct.product.id}' /> 
                                        </td>`;
                                tableHtml +=
                                    `<td>${v.qty}
                                        <input type='hidden' name='demandQty[]' id='demandQty' value='${v.demandQty}' class='form-control'/>
                                        <input type='hidden' name='qty[]' id='qty' value='${v.qty}' class='form-control'/>
                                        
                                    </td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.cusproduct.product.packing)} <input type='hidden'  value='${v.cusproduct.product.packing}' /></td>`;
                                tableHtml +=
                                    `<td>${v.cusproduct.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.cusproduct.product.uom}' /></td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.sale_qty)}<input type='hidden' value='${parseInt(v.sale_qty)}' id="sale_qty" name="sale_qty[]" class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" readonly/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' style="width:120px;" 
                                    onkeyup="changerate($(this).closest('tr'));" 
                                    onkeydown="EnterKeyBoard($(this).closest('tr'));" 
                                    onkeypress="return isNumberKey(event)"/>
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
                            // $('#TotalIgpQty').text(totalQty.toLocaleString('en-US'));
                            $('#updated_by_name').removeClass('d-none');
                            // $('.dcn_no1').addClass('d-none');
                            // $('.dcn_no2').removeClass('d-none');
                            // $('.dcn_no2 input').val(response.data[0].salepurchase.dc
                            //     .voucher_no);
                                $('#party_name').val(response.data[0].party.id).select2();
                                $('#party_id').val(response.data[0].party.id);
                            // $('#dcn_id1').val(response.data[0].salepurchase.dc.id);
                            $('#update_voucher_id').val(response.data[0].salepurchase.id);
                            $('#date').val(response.data[0].salepurchase.date);
                            $('#voucher_no').val(response.data[0].salepurchase.voucher_no);
                            $('#voucher_no').focus();
                            $('#remarks').val(response.data[0].salepurchase.remarks);
                            $('#vehicle_no').val(response.data[0].salepurchase.dc.vehicle_no);
                            $('#transport_company').val(response.data[0].salepurchase.dc
                                .transport_company);
                            $('#driver_name').val(response.data[0].salepurchase.dc.driver_name);
                            $('#builty_no').val(response.data[0].salepurchase.dc.builty_no);
                            $('#freight').val(response.data[0].salepurchase.dc.freight);
                            $('#driver_phoneno').val(response.data[0].salepurchase.dc
                                .driver_phoneno);
                            
                            $('.ReloadOrder').removeClass('d-none');
                            TotalAmount();
                            recQty();
                            TotalExclVolue();
                            TotalSaleTax();
                            } else {
                                // alert("11");
                                $.each(response.data, function(i, v) {

                                    totalQty += parseFloat(v.demandQty);
                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                        `<td>${v.product.code}</td>`;
                                    tableHtml += `<td>
                                         ${v.product.product_name}
                                       <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                   </td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control'/>
                                            <input type='hidden' name='demandQty[]' id='demandQty' value='${v.demandQty}' class='form-control'/>
                                        </td>`;
                                    // tableHtml +=
                                    //     `<td>${parseInt(v.product.packing)} <input type='hidden'  value='${v.product.packing}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    // tableHtml +=
                                    //     `<td>${parseInt(v.sale_qty)}<input type='hidden' value='${parseInt(v.sale_qty)}' id="sale_qty" name="sale_qty[]" class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" readonly/></td>`;
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

                                    tableHtml +=`<td style="width:130px;"><input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                                            </td>`;
                                    tableHtml +=
                                            `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;

                                });

                                $('#GridTable').html(tableHtml);
                            // $('#TotalIgpQty').text(totalQty.toLocaleString('en-US'));
                            // $('#updated_by_name').removeClass('d-none');
                            // $('.dcn_no1').addClass('d-none');
                            // $('.dcn_no2').removeClass('d-none');
                            // $('.dcn_no2 input').val(response.data[0].salepurchase.dc
                            //     .voucher_no);
                            // $('#dcn_id1').val(response.data[0].salepurchase.dc.id);
                            $('#update_voucher_id').val(response.data[0].salepurchase.id);
                            $('#party_name').val(response.data[0].party.id).select2();
                            $('#party_id').val(response.data[0].party.id);
                            $('#date').val(response.data[0].salepurchase.date);
                            $('#sale_return_type').val(response.data[0].salepurchase.sale_return_type).select2();
                            $('#voucher_no').val(response.data[0].salepurchase.voucher_no);
                            $('#voucher_no').focus();
                            $('#remarks').val(response.data[0].salepurchase.remarks);
                            $('#vehicle_no').val(response.data[0].salepurchase.vehicle_no);
                            $('#transport_company').val(response.data[0].salepurchase.transport_company);
                            $('#driver_name').val(response.data[0].salepurchase.driver_name);
                            $('#builty_no').val(response.data[0].salepurchase.builty_no);
                            $('#freight').val(response.data[0].salepurchase.freight);
                            $('#driver_phoneno').val(response.data[0].salepurchase.driver_phoneno);
                                TotalSaleReturn();
                            TotalAmount();
                            TotalPack();
                            TotalSale();
                            
                            $('.ReloadOrder').removeClass('d-none');
                            // TotalAmount();
                            // recQty();
                            // TotalExclVolue();
                            // TotalSaleTax();
                            if(response.data[0].salepurchase.dc)
                            {
                                $('.dcn_no2 input').val(response.data[0].salepurchase.dc
                                .voucher_no);
                            }else{
                                $('.dcn_no2 input').val("");  
                            }
                            if(response.data[0].salepurchase.sale_return_type == "INVOICE"){
                                $('.InvoiceNo').removeClass('d-none');
                                // document.getElementById("party_name").disabled = true;
                                $('#invoice_no').val(response.data[0].salepurchase.sale_return_invoice_no);
                                $('#invoice_no').focus();
                                $('.productTab').hide();
                            }else{
                                $('.InvoiceNo').addClass('d-none');
                                $("#party_name").select2('open');
                                $('.productTab').show();
                            }
                           
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("vehicle_no").disabled = true;
                                $('#vehicle_no').val(response.data[0].salepurchase.dc.vehicle_no);
                            }else{
                                // $('#vehicle_no').val("");
                                document.getElementById("vehicle_no").disabled = false;
                                $('#vehicle_no').val(response.data[0].salepurchase.vehicle_no);
                            }
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("transport_company").disabled = true;
                                $('#transport_company').val(response.data[0].salepurchase.dc.transport_company);
                            }else{
                                document.getElementById("transport_company").disabled = false;
                                $('#transport_company').val(response.data[0].salepurchase.transport_company);
                            }
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("driver_name").disabled = true;
                                $('#driver_name').val(response.data[0].salepurchase.dc.driver_name);
                            }else{
                                document.getElementById("driver_name").disabled = false;
                                $('#driver_name').val(response.data[0].salepurchase.driver_name);
                            }
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("builty_no").disabled = true;
                                $('#builty_no').val(response.data[0].salepurchase.dc.builty_no);
                            }else{
                                document.getElementById("builty_no").disabled = false;
                                $('#builty_no').val(response.data[0].salepurchase.builty_no);
                            }
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("freight").disabled = true;
                                $('#freight').val(response.data[0].salepurchase.dc.freight);
                            }else{
                                document.getElementById("freight").disabled = false;
                                $('#freight').val(response.data[0].salepurchase.freight);
                            }
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("driver_phoneno").disabled = true;
                                $('#driver_phoneno').val(response.data[0].salepurchase.dc.driver_phoneno);
                            }else{
                                document.getElementById("driver_phoneno").disabled = false;
                                $('#driver_phoneno').val(response.data[0].salepurchase.driver_phoneno);
                            }
                            }
                           
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
                $.ajax({
                    url: "{{ URL::to('purchasetax-return/load/next/record') }}",
                    type: 'get',
                    data:{
                        voucher_no:voucher_no,
                        warehouseID:warehouseID
                    },
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
                            if (response.status == 0) {
                    

                                $.each(response.data, function(i, v) {
                                    // alert("1");
                                totalQty += parseFloat(v.demandQty);
                                tableHtml += `<tr>`;
                                       tableHtml +=
                                        `<td>${v.cusproduct.product_code}<input type='hidden' name='code[]' id='code' value='${v.cusproduct.product_code}' /></td>`;
                                    tableHtml 
                                              += `<td>${v.cusproduct.product_name}
                                            <input type='hidden' name='product_id[]' id='product_id' value='${v.cusproduct.product.id}' /> 
                                        </td>`;
                                tableHtml +=
                                    `<td>${v.qty}
                                        <input type='hidden' name='demandQty[]' id='demandQty' value='${v.demandQty}' class='form-control'/>
                                        <input type='hidden' name='qty[]' id='qty' value='${v.qty}' class='form-control'/>
                                        
                                    </td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.cusproduct.product.packing)} <input type='hidden'  value='${v.cusproduct.product.packing}' /></td>`;
                                tableHtml +=
                                    `<td>${v.cusproduct.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.cusproduct.product.uom}' /></td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.sale_qty)}<input type='hidden' value='${parseInt(v.sale_qty)}' id="sale_qty" name="sale_qty[]" class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" readonly/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' style="width:120px;" 
                                    onkeyup="changerate($(this).closest('tr'));" 
                                    onkeydown="EnterKeyBoard($(this).closest('tr'));" 
                                    onkeypress="return isNumberKey(event)"/>
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
                            // $('#TotalIgpQty').text(totalQty.toLocaleString('en-US'));
                            $('#updated_by_name').removeClass('d-none');
                            // $('.dcn_no1').addClass('d-none');
                            // $('.dcn_no2').removeClass('d-none');
                            // $('.dcn_no2 input').val(response.data[0].salepurchase.dc
                            //     .voucher_no);
                                $('#party_name').val(response.data[0].party.id).select2();
                                $('#party_id').val(response.data[0].party.id);
                            // $('#dcn_id1').val(response.data[0].salepurchase.dc.id);
                            $('#update_voucher_id').val(response.data[0].salepurchase.id);
                            $('#date').val(response.data[0].salepurchase.date);
                            $('#voucher_no').val(response.data[0].salepurchase.voucher_no);
                            $('#voucher_no').focus();
                            $('#remarks').val(response.data[0].salepurchase.remarks);
                            $('#vehicle_no').val(response.data[0].salepurchase.dc.vehicle_no);
                            $('#transport_company').val(response.data[0].salepurchase.dc
                                .transport_company);
                            $('#driver_name').val(response.data[0].salepurchase.dc.driver_name);
                            $('#builty_no').val(response.data[0].salepurchase.dc.builty_no);
                            $('#freight').val(response.data[0].salepurchase.dc.freight);
                            $('#driver_phoneno').val(response.data[0].salepurchase.dc
                                .driver_phoneno);
                            
                            $('.ReloadOrder').removeClass('d-none');
                            TotalAmount();
                            recQty();
                            TotalExclVolue();
                            TotalSaleTax();
                            } else {
                                // alert("11");
                                $.each(response.data, function(i, v) {

                                    totalQty += parseFloat(v.demandQty);
                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                        `<td>${v.product.code}</td>`;
                                    tableHtml += `<td>
                                         ${v.product.product_name}
                                       <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                   </td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control'/>
                                            <input type='hidden' name='demandQty[]' id='demandQty' value='${v.demandQty}' class='form-control'/>
                                        </td>`;
                                    // tableHtml +=
                                    //     `<td>${parseInt(v.product.packing)} <input type='hidden'  value='${v.product.packing}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    // tableHtml +=
                                    //     `<td>${parseInt(v.sale_qty)}<input type='hidden' value='${parseInt(v.sale_qty)}' id="sale_qty" name="sale_qty[]" class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" readonly/></td>`;
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

                                    tableHtml +=`<td style="width:130px;"><input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                                            </td>`;
                                    tableHtml +=
                                            `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;

                                });

                                $('#GridTable').html(tableHtml);
                            // $('#TotalIgpQty').text(totalQty.toLocaleString('en-US'));
                            // $('#updated_by_name').removeClass('d-none');
                            // $('.dcn_no1').addClass('d-none');
                            // $('.dcn_no2').removeClass('d-none');
                            // $('.dcn_no2 input').val(response.data[0].salepurchase.dc
                            //     .voucher_no);
                            // $('#dcn_id1').val(response.data[0].salepurchase.dc.id);
                            $('#update_voucher_id').val(response.data[0].salepurchase.id);
                            $('#party_name').val(response.data[0].party.id).select2();
                            $('#party_id').val(response.data[0].party.id);
                            $('#date').val(response.data[0].salepurchase.date);
                            $('#sale_return_type').val(response.data[0].salepurchase.sale_return_type).select2();
                            $('#voucher_no').val(response.data[0].salepurchase.voucher_no);
                            $('#voucher_no').focus();
                            $('#remarks').val(response.data[0].salepurchase.remarks);
                            $('#vehicle_no').val(response.data[0].salepurchase.vehicle_no);
                            $('#transport_company').val(response.data[0].salepurchase.transport_company);
                            $('#driver_name').val(response.data[0].salepurchase.driver_name);
                            $('#builty_no').val(response.data[0].salepurchase.builty_no);
                            $('#freight').val(response.data[0].salepurchase.freight);
                            $('#driver_phoneno').val(response.data[0].salepurchase.driver_phoneno);
                                TotalSaleReturn();
                            TotalAmount();
                            TotalPack();
                            TotalSale();
                            
                            $('.ReloadOrder').removeClass('d-none');
                            // TotalAmount();
                            // recQty();
                            // TotalExclVolue();
                            // TotalSaleTax();
                            if(response.data[0].salepurchase.dc)
                            {
                                $('.dcn_no2 input').val(response.data[0].salepurchase.dc
                                .voucher_no);
                            }else{
                                $('.dcn_no2 input').val("");  
                            }
                            if(response.data[0].salepurchase.sale_return_type == "INVOICE"){
                                $('.InvoiceNo').removeClass('d-none');
                                // document.getElementById("party_name").disabled = true;
                                $('#invoice_no').val(response.data[0].salepurchase.sale_return_invoice_no);
                                $('#invoice_no').focus();
                                $('.productTab').hide();
                            }else{
                                $('.InvoiceNo').addClass('d-none');
                                $("#party_name").select2('open');
                                $('.productTab').show();
                            }
                           
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("vehicle_no").disabled = true;
                                $('#vehicle_no').val(response.data[0].salepurchase.dc.vehicle_no);
                            }else{
                                // $('#vehicle_no').val("");
                                document.getElementById("vehicle_no").disabled = false;
                                $('#vehicle_no').val(response.data[0].salepurchase.vehicle_no);
                            }
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("transport_company").disabled = true;
                                $('#transport_company').val(response.data[0].salepurchase.dc.transport_company);
                            }else{
                                document.getElementById("transport_company").disabled = false;
                                $('#transport_company').val(response.data[0].salepurchase.transport_company);
                            }
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("driver_name").disabled = true;
                                $('#driver_name').val(response.data[0].salepurchase.dc.driver_name);
                            }else{
                                document.getElementById("driver_name").disabled = false;
                                $('#driver_name').val(response.data[0].salepurchase.driver_name);
                            }
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("builty_no").disabled = true;
                                $('#builty_no').val(response.data[0].salepurchase.dc.builty_no);
                            }else{
                                document.getElementById("builty_no").disabled = false;
                                $('#builty_no').val(response.data[0].salepurchase.builty_no);
                            }
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("freight").disabled = true;
                                $('#freight').val(response.data[0].salepurchase.dc.freight);
                            }else{
                                document.getElementById("freight").disabled = false;
                                $('#freight').val(response.data[0].salepurchase.freight);
                            }
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("driver_phoneno").disabled = true;
                                $('#driver_phoneno').val(response.data[0].salepurchase.dc.driver_phoneno);
                            }else{
                                document.getElementById("driver_phoneno").disabled = false;
                                $('#driver_phoneno').val(response.data[0].salepurchase.driver_phoneno);
                            }
                            }
                           
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
                // alert(voucher_no)
                $.ajax({
                    url: "{{ URL::to('purchasetax-return/load/previous/record') }}",
                    type: 'get',
                    data:{
                        voucher_no:voucher_no, 
                        warehouseID:warehouseID
                    },
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    // success: function(response) {
                    //     // alert("sss");
                    //     if (response.data != '') {
                    //         var tableHtml = '';
                    //         if (response.data[0].salepurchase.challan_type == 'CDC') {
                    //             $.each(response.data, function(i, v) {
                    //                 totalQty += parseInt(v.demandQty);
                    //                 grandSaleQty+=parseInt(v.sale_qty);
                    //                 tableHtml += `<tr>`;
                    //                 tableHtml +=`<td>${v.cusproduct.product.code}<input type='hidden' name='code[]' id='code' value='${v.cusproduct.product.code}' /></td>`;
                    //                 tableHtml += `<td><input type='hidden' name='product_id[]' id='product_id' value='${v.cusproduct.id}' />
                    //                         <input type='text' value='${v.cusproduct.product_name}' class='form-control' readonly />
                    //                     </td>`;
                    //                 tableHtml +=`<td >${v.demandQty}<input type='hidden' name='demandQty[]' id='demandQty' value='${v.demandQty}' class='form-control'/></td>`;
                    //                 tableHtml +=`<td style="width:120px;">
                    //                         <input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control' style="width:120px;"/>
                    //                         <span class="qty_errr text-danger"></span>
                    //                     </td>`;
                    //                 tableHtml +=`<td>${v.cusproduct.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.cusproduct.product.uom}' /></td>`;
                    //                 tableHtml +=`<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' style="width:120px;" onkeyup="changerate($(this).closest('tr'));"/><span class="rate_errr text-danger"></span></td>`;
                    //                 tableHtml +=`<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                    //                 tableHtml +=`<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.st_rate}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;
                    //                 tableHtml +=`<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;
                    //                 tableHtml +=`<td style="width:130px;"><input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" /></td>`;
                    //                 tableHtml +=`<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                    //                 tableHtml += `</tr>`;
                    //             });
                    //         } else {
                    //             $.each(response.data, function(i, v) {
                    //                 // alert(response.data[0].salepurchase.voucher_no);
                    //                 tableHtml += `<tr>`;
                    //                 tableHtml +=`<td>${v.po_no}<input type='hidden' name='po_no[]' id='po_no' value='${v.po_no}' /></td>`;
                    //                 tableHtml += `<td>
                    //                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                    //                    <input type='text' value='${v.product.code} - ${v.product.product_name}' class='form-control' readonly />
                    //                </td>`;
                    //                 tableHtml +=`<td><input type='text' name='pack[]' id='pack' value='${v.qty}' class='form-control' readonly /></td>`;
                    //                 tableHtml +=`<td>${parseInt(v.product.packing)} <input type='hidden'  value='${v.product.packing}' /></td>`;
                    //                 tableHtml +=`<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                    //                 tableHtml +=`<td><input type='text' value='${parseFloat(v.demandQty)}' id="sale_qty" name="sale_qty[]" onkeyup="changeSaleQty1($(this).closest('tr'));" class='form-control'  onkeypress="return isNumberKey(event)"/></td>`;
                    //                 tableHtml +=`<td style="width:120px;"><input type='text' name='return_qty[]' id='return_qty' value='${v.sale_qty}' class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                    //                 tableHtml +=`<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' onkeyup="changerate($(this).closest('tr'));" onchange="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                    //                 tableHtml +=`<td style="width:130px;"><input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" /></td>`;
                    //                 tableHtml +=`<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                    //                 tableHtml += `</tr>`;
                    //                     });
                    //         $('#GridTable').html(tableHtml);
                    //         $('#voucher_no').val(response.data[0].salepurchase.voucher_no);
                    //         TotalSaleReturn();
                    //         TotalAmount();
                    //         TotalPack();
                    //         TotalSale();
                    //         $('#party_name').val(response.data[0].party.id).select2();
                    //         $('#party_id').val(response.data[0].party.id);
                    //         // var option = ``;
                    //         // option +=`<option value="${response.data[0].salepurchase.warehouse.id}">${response.data[0].salepurchase.warehouse.name}</option>`;
                    //         // $('#warehouse_id').html(option);
                    //         $('#update_voucher_id').val(response.data[0].salepurchase.id);
                    //         $('#date').val(response.data[0].salepurchase.date);
                    //          $('#sale_return_type').val(response.data[0].salepurchase.sale_return_type).select2();
                           
                    //         // $('#vehicle_no').val(response.data[0].salepurchase.vehicle_no);
                    //         $('#voucher_no').focus();
                    //         $('#remarks').val(response.data[0].salepurchase.remarks);
                            
                    //         $('#updated_by_name').removeClass('d-none');
                    //         if(response.data[0].salepurchase.dc)
                    //         {
                    //             $('.dcn_no2 input').val(response.data[0].salepurchase.dc
                    //             .voucher_no);
                    //         }else{
                    //             $('.dcn_no2 input').val("");  
                    //         }
                    //         if(response.data[0].salepurchase.sale_return_type == "INVOICE"){
                    //             $('.InvoiceNo').removeClass('d-none');
                    //             // document.getElementById("party_name").disabled = true;
                    //             $('#invoice_no').val(response.data[0].salepurchase.sale_return_invoice_no);
                    //             $('#invoice_no').focus();
                    //             $('.productTab').hide();
                    //         }else{
                    //             $('.InvoiceNo').addClass('d-none');
                    //             $("#party_name").select2('open');
                    //             $('.productTab').show();
                    //         }
                           
                    //         if(response.data[0].salepurchase.dc){
                    //             document.getElementById("vehicle_no").disabled = true;
                    //             $('#vehicle_no').val(response.data[0].salepurchase.dc.vehicle_no);
                    //         }else{
                    //             // $('#vehicle_no').val("");
                    //             document.getElementById("vehicle_no").disabled = false;
                    //             $('#vehicle_no').val(response.data[0].salepurchase.vehicle_no);
                    //         }
                    //         if(response.data[0].salepurchase.dc){
                    //             document.getElementById("transport_company").disabled = true;
                    //             $('#transport_company').val(response.data[0].salepurchase.dc.transport_company);
                    //         }else{
                    //             document.getElementById("transport_company").disabled = false;
                    //             $('#transport_company').val(response.data[0].salepurchase.transport_company);
                    //         }
                    //         if(response.data[0].salepurchase.dc){
                    //             document.getElementById("driver_name").disabled = true;
                    //             $('#driver_name').val(response.data[0].salepurchase.dc.driver_name);
                    //         }else{
                    //             document.getElementById("driver_name").disabled = false;
                    //             $('#driver_name').val(response.data[0].salepurchase.driver_name);
                    //         }
                    //         if(response.data[0].salepurchase.dc){
                    //             document.getElementById("builty_no").disabled = true;
                    //             $('#builty_no').val(response.data[0].salepurchase.dc.builty_no);
                    //         }else{
                    //             document.getElementById("builty_no").disabled = false;
                    //             $('#builty_no').val(response.data[0].salepurchase.builty_no);
                    //         }
                    //         if(response.data[0].salepurchase.dc){
                    //             document.getElementById("freight").disabled = true;
                    //             $('#freight').val(response.data[0].salepurchase.dc.freight);
                    //         }else{
                    //             document.getElementById("freight").disabled = false;
                    //             $('#freight').val(response.data[0].salepurchase.freight);
                    //         }
                    //         if(response.data[0].salepurchase.dc){
                    //             document.getElementById("driver_phoneno").disabled = true;
                    //             $('#driver_phoneno').val(response.data[0].salepurchase.dc.driver_phoneno);
                    //         }else{
                    //             document.getElementById("driver_phoneno").disabled = false;
                    //             $('#driver_phoneno').val(response.data[0].salepurchase.driver_phoneno);
                    //         } 
                    //         }
                    //     } else {
                    //         $('#show_err').html(
                    //             '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                    //         );
                    //         $('#GridTable').html(null);
                    //         $('#TotalIgpQty').text(0);
                    //         $('#TotalRecQty').text(0);
                    //         $('#TotalExclVolue').text(0);
                    //         $('#TotalSaleTax').text(0);
                    //         $('#TotalAmount').text(0);
                    //         $('#updated_by_name').addClass('d-none');
                    //         $('#update_voucher_id').val(null);
                    //         $('#voucher_no').focus();
                    //         $('#dcn_no1').val(null);
                    //         $('#remarks').val(null);
                    //         $('#vehicle_no').val(null);
                    //         $('#transport_company').val(null);
                    //         $('#driver_name').val(null);
                    //         $('#builty_no').val(null);
                    //         $('#freight').val(null);
                    //         $('#driver_phoneno').val(null);
                    //     }    
                    // }
                    success: function(response) {
                        if (response.data != '') {
                            var tableHtml = '';
                            var totalQty = 0;
                            if (response.status == 0) {
                    

                                $.each(response.data, function(i, v) {
                                    // alert("1");
                                totalQty += parseFloat(v.demandQty);
                                tableHtml += `<tr>`;
                                       tableHtml +=
                                        `<td>${v.cusproduct.product_code}<input type='hidden' name='code[]' id='code' value='${v.cusproduct.product_code}' /></td>`;
                                    tableHtml 
                                              += `<td>${v.cusproduct.product_name}
                                            <input type='hidden' name='product_id[]' id='product_id' value='${v.cusproduct.product.id}' /> 
                                        </td>`;
                                tableHtml +=
                                    `<td>${v.qty}
                                        <input type='hidden' name='demandQty[]' id='demandQty' value='${v.demandQty}' class='form-control'/>
                                        <input type='hidden' name='qty[]' id='qty' value='${v.qty}' class='form-control'/>
                                        
                                    </td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.cusproduct.product.packing)} <input type='hidden'  value='${v.cusproduct.product.packing}' /></td>`;
                                tableHtml +=
                                    `<td>${v.cusproduct.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.cusproduct.product.uom}' /></td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.sale_qty)}<input type='hidden' value='${parseInt(v.sale_qty)}' id="sale_qty" name="sale_qty[]" class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" readonly/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' style="width:120px;" 
                                    onkeyup="changerate($(this).closest('tr'));" 
                                    onkeydown="EnterKeyBoard($(this).closest('tr'));" 
                                    onkeypress="return isNumberKey(event)"/>
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
                            // $('#TotalIgpQty').text(totalQty.toLocaleString('en-US'));
                            $('#updated_by_name').removeClass('d-none');
                            // $('.dcn_no1').addClass('d-none');
                            // $('.dcn_no2').removeClass('d-none');
                            // $('.dcn_no2 input').val(response.data[0].salepurchase.dc
                            //     .voucher_no);
                                $('#party_name').val(response.data[0].party.id).select2();
                                $('#party_id').val(response.data[0].party.id);
                            // $('#dcn_id1').val(response.data[0].salepurchase.dc.id);
                            $('#update_voucher_id').val(response.data[0].salepurchase.id);
                            $('#date').val(response.data[0].salepurchase.date);
                            $('#voucher_no').val(response.data[0].salepurchase.voucher_no);
                            $('#voucher_no').focus();
                            $('#remarks').val(response.data[0].salepurchase.remarks);
                            $('#vehicle_no').val(response.data[0].salepurchase.dc.vehicle_no);
                            $('#transport_company').val(response.data[0].salepurchase.dc
                                .transport_company);
                            $('#driver_name').val(response.data[0].salepurchase.dc.driver_name);
                            $('#builty_no').val(response.data[0].salepurchase.dc.builty_no);
                            $('#freight').val(response.data[0].salepurchase.dc.freight);
                            $('#driver_phoneno').val(response.data[0].salepurchase.dc
                                .driver_phoneno);
                            
                            $('.ReloadOrder').removeClass('d-none');
                            TotalAmount();
                            recQty();
                            TotalExclVolue();
                            TotalSaleTax();
                            } else {
                                // alert("11");
                                $.each(response.data, function(i, v) {

                                    totalQty += parseFloat(v.demandQty);
                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                        `<td>${v.product.code}</td>`;
                                    tableHtml += `<td>
                                         ${v.product.product_name}
                                       <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                   </td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control'/>
                                            <input type='hidden' name='demandQty[]' id='demandQty' value='${v.demandQty}' class='form-control'/>
                                        </td>`;
                                    // tableHtml +=
                                    //     `<td>${parseInt(v.product.packing)} <input type='hidden'  value='${v.product.packing}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    // tableHtml +=
                                    //     `<td>${parseInt(v.sale_qty)}<input type='hidden' value='${parseInt(v.sale_qty)}' id="sale_qty" name="sale_qty[]" class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" readonly/></td>`;
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

                                    tableHtml +=`<td style="width:130px;"><input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                                            </td>`;
                                    tableHtml +=
                                            `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;

                                });

                                $('#GridTable').html(tableHtml);
                            // $('#TotalIgpQty').text(totalQty.toLocaleString('en-US'));
                            // $('#updated_by_name').removeClass('d-none');
                            // $('.dcn_no1').addClass('d-none');
                            // $('.dcn_no2').removeClass('d-none');
                            // $('.dcn_no2 input').val(response.data[0].salepurchase.dc
                            //     .voucher_no);
                            // $('#dcn_id1').val(response.data[0].salepurchase.dc.id);
                            $('#update_voucher_id').val(response.data[0].salepurchase.id);
                            $('#party_name').val(response.data[0].party.id).select2();
                            $('#party_id').val(response.data[0].party.id);
                            $('#date').val(response.data[0].salepurchase.date);
                            $('#sale_return_type').val(response.data[0].salepurchase.sale_return_type).select2();
                            $('#voucher_no').val(response.data[0].salepurchase.voucher_no);
                            $('#voucher_no').focus();
                            $('#remarks').val(response.data[0].salepurchase.remarks);
                            $('#vehicle_no').val(response.data[0].salepurchase.vehicle_no);
                            $('#transport_company').val(response.data[0].salepurchase.transport_company);
                            $('#driver_name').val(response.data[0].salepurchase.driver_name);
                            $('#builty_no').val(response.data[0].salepurchase.builty_no);
                            $('#freight').val(response.data[0].salepurchase.freight);
                            $('#driver_phoneno').val(response.data[0].salepurchase.driver_phoneno);
                                TotalSaleReturn();
                            TotalAmount();
                            TotalPack();
                            TotalSale();
                            
                            $('.ReloadOrder').removeClass('d-none');
                            // TotalAmount();
                            // recQty();
                            // TotalExclVolue();
                            // TotalSaleTax();
                            if(response.data[0].salepurchase.dc)
                            {
                                $('.dcn_no2 input').val(response.data[0].salepurchase.dc
                                .voucher_no);
                            }else{
                                $('.dcn_no2 input').val("");  
                            }
                            if(response.data[0].salepurchase.sale_return_type == "INVOICE"){
                                $('.InvoiceNo').removeClass('d-none');
                                // document.getElementById("party_name").disabled = true;
                                $('#invoice_no').val(response.data[0].salepurchase.sale_return_invoice_no);
                                $('#invoice_no').focus();
                                $('.productTab').hide();
                            }else{
                                $('.InvoiceNo').addClass('d-none');
                                $("#party_name").select2('open');
                                $('.productTab').show();
                            }
                           
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("vehicle_no").disabled = true;
                                $('#vehicle_no').val(response.data[0].salepurchase.dc.vehicle_no);
                            }else{
                                // $('#vehicle_no').val("");
                                document.getElementById("vehicle_no").disabled = false;
                                $('#vehicle_no').val(response.data[0].salepurchase.vehicle_no);
                            }
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("transport_company").disabled = true;
                                $('#transport_company').val(response.data[0].salepurchase.dc.transport_company);
                            }else{
                                document.getElementById("transport_company").disabled = false;
                                $('#transport_company').val(response.data[0].salepurchase.transport_company);
                            }
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("driver_name").disabled = true;
                                $('#driver_name').val(response.data[0].salepurchase.dc.driver_name);
                            }else{
                                document.getElementById("driver_name").disabled = false;
                                $('#driver_name').val(response.data[0].salepurchase.driver_name);
                            }
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("builty_no").disabled = true;
                                $('#builty_no').val(response.data[0].salepurchase.dc.builty_no);
                            }else{
                                document.getElementById("builty_no").disabled = false;
                                $('#builty_no').val(response.data[0].salepurchase.builty_no);
                            }
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("freight").disabled = true;
                                $('#freight').val(response.data[0].salepurchase.dc.freight);
                            }else{
                                document.getElementById("freight").disabled = false;
                                $('#freight').val(response.data[0].salepurchase.freight);
                            }
                            if(response.data[0].salepurchase.dc){
                                document.getElementById("driver_phoneno").disabled = true;
                                $('#driver_phoneno').val(response.data[0].salepurchase.dc.driver_phoneno);
                            }else{
                                document.getElementById("driver_phoneno").disabled = false;
                                $('#driver_phoneno').val(response.data[0].salepurchase.driver_phoneno);
                            }
                            }
                           
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

        $('#warehouse_id').change(function() {
                // var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                // alert(warehouseID);
                $.ajax({
                    url: "{{ URL::to('purchasetax-return/warehouse/voucherno') }}",
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
            });
    </script>
    <!-- End Load & Edit Record -->

    @include('include.toast-messages')
@stop
