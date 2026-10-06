
@extends('app')
    @section('head')
        <title>Delivery Challan (Demand)</title>
        <!--  Select 2 library start-->
        <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        
        <!--  Select 2 library end-->
        <style>
        #GridTable td {
            padding-right: 2px;
            padding-left: 2px;
        }
        </style>
        @stop
    @section('content')
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
                <h1>Delivery Challan (Demand)</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item active"><a href="#">Delivery Challan (Demand)</a></li>
                </ol>
            </section>
            <!-- Main content -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <section class="content">
                        <div class="box">
                            <div class="box-header with-border">
                                <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Delivery Challan (Demand)</h6>
                                <!-- <ul class="box-controls pull-right">
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                </ul> -->
                            </div>
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
                                        {!! Form::open(['url' => 'delivery-challan-non-gst', 'class' => 'form-horizontal', 'id' => 'delivery-challan-form']) !!}
                                        {!! Form::hidden('update_voucher_id', null, ['id' => 'update_voucher_id']) !!}
                                        {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                        

                                        <div class="row">

                                            <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                                <label for="voucher_no"><i class="fa fa-caret-right"></i>Vr#<span
                                                        class="text-danger">*</span></label>
                                                {!! Form::text('voucher_no', $codes, [
                                                    'id' => 'voucher_no',
                                                    'class' => 'form-control',
                                                    'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                    'tabindex' => '0',
                                                    'required' => 'required',
                                                    
                                                ]) !!}
                                                <span class="text-danger voucher_no_err"></span>
                                                @error('voucher_no')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                
                                            </div>
                                            <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                                <label for="date"><i class="fa fa-caret-right"></i> Voucher Date<span
                                                        class="text-danger">*</span></label>
                                                {!! Form::date('voucher_date', date('Y-m-d'), [
                                                    'id' => 'voucher_date',
                                                    'class' => 'form-control',
                                                    'tabindex' => '1',
                                                    'required' => 'required',
                                                    'autofocus' => 'autofocus',
                                                ]) !!}
                                                @error('voucher_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                                <label for="party_name"><i class="fa fa-caret-right"></i> Party Name<span
                                                        class="text-danger">*</span></label>
                                                {!! Form::select('party_name', $customers, null, [
                                                    'id' => 'party_name',
                                                    'class' => 'form-control select2 customer',
                                                    'tabindex' => '3',

                                                    'required' => 'required',
                                                ]) !!}
                                                {!! Form::hidden('party_id', null, ['id' => 'party_id']) !!}
                                                {{-- <span class="text-danger party_name_err"></span>
                                                @error('party_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror --}}
                                            </div>
                                            <!-- <div class="col-lg-3 col-md-2 col-sm-12 mt-1 sale-order1">
                                                <label for="sale_order_no" style="background-color:#666ee8;color:white;"><i
                                                        class="fa fa-caret-right"></i> Sale Demand No.<span
                                                        class="text-danger">*</span></label>
                                                {!! Form::select('sale_order_no', $salerOrderNo, null, [
                                                    'id' => 'sale_order_no',
                                                    'class' => 'form-control select2',
                                                    'tabindex' => '2',
                                                    'style' => 'background-color:#666ee8',
                                                ]) !!}
                                                <span class="text-danger sale_order_no_err"></span>
                                                @error('sale_order_no')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div> -->
                                            <!-- <div class="col-lg-3 col-md-2 col-sm-12 mt-1 sale-order2 d-none">
                                                <label for="sale_order_no"><i class="fa fa-caret-right"></i> Sale Order No.<span
                                                        class="text-danger"sale_order_no>*</span></label>
                                                {!! Form::text('sale_order_no_edit', null, [
                                                    'id' => 'sale_order_no_edit',
                                                    'class' => 'form-control',
                                                    'readonly' => 'readonly'
                                                ]) !!}
                                            </div> -->
                                            <!-- <input type="text" name="sale_order_no" id="sale_order_no"> -->
                                            <!-- <input type="text" name="" id=""> -->
                                    <div class="col-lg-2 col-md-6 col-sm-12 mt-1 SelectsaleDemand">
                                        <label for="grn_no"><i class="fa fa-caret-right"></i> Select Sale Demand.<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group saleDemand">
                                            <input type="text" class="form-control" id="grn_voucher_no" disabled
                                                placeholder="No:">
                                            <div class="input-group-addon bg-primary border-primary grn-modal-btn"
                                                data-toggle="modal" data-target=".grn-numbers-modal">
                                                <i class="fa fa-th-list"></i>
                                            </div>
                                            @error('transaction_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="modal fade grn-numbers-modal" tabindex="-1" role="dialog"
                                            aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary"
                                                        style="border-top-left-radius: 0px;border-top-right-radius:0px;">
                                                        <h4 class="modal-title text-white font-weight-bold"
                                                            id="myLargeModalLabel">
                                                            Pending Sale Demands (Search PO)
                                                        </h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="close-modal">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-bordered table-hover data-table"
                                                                style="width: 100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Select</th>
                                                                        <th>Sr#</th>
                                                                        <th>SD#</th>
                                                                        <th>Date</th>
                                                                        <th style="width: 30%;">Product Name</th>
                                                                        <th>PO#</th>
                                                                        <th>PO Date</th>
                                                                        <th>Order.Qty</th>
                                                                        <th>Balance</th>
                                                                    </tr>
                                                                </thead>
                                                                <!-- <tbody id="GridSaleDemand"></tbody> -->
                                                            </table>
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-12">
                                                                    <button class="btn btn-primary select-checkboxes" type="button" style="float:right;">Transfer</button>
                                                                    <!-- <button class="btn btn-secondary reset-btn" type="reset">Reset</button> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                        <div class="col-lg-1 col-md-6 col-sm-12 mt-1 ReloadOrder d-none">
                                                <label for="grn_no"><i class="fa fa-caret-right"></i>Reload<span
                                                    class="text-danger">*</span></label>
                                                    <a href="#"><div class="input-group">
                                                    <div class="input-group-addon bg-primary border-primary" onclick="refresh();">
                                                        <i class="fa fa-th-list"></i>
                                                    </div></a>
                                            </div>
                                        </div>


                                            <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                                <label for="address"><i class="fa fa-caret-right"></i> Address</label>
                                                {!! Form::text('address', null, [
                                                    'id' => 'address',
                                                    'class' => 'form-control',
                                                    'tabindex' => '4',
                                                    'disabled' => 'disabled',
                                                    'placeholder' => 'Address',
                                                ]) !!}
                                            </div>
                                            
                                          
                                            <div class="col-lg-3 col-md-2 col-sm-12 mt-1">
                                                <label for="order_date"><i class="fa fa-caret-right"></i> Order Date<span
                                                        class="text-danger">*</span></label>
                                                <!-- {!! Form::date('order_date', date('Y-m-d'), [
                                                    'id' => 'order_date',
                                                    'class' => 'form-control',
                                                    'tabindex' => '5',
                                                ]) !!} -->

                                                <input id="order_date" class="form-control" name="order_date" type="date" value="{{ date('Y-m-d') }}" readonly>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                                <label for="remarks"><i class="fa fa-caret-right"></i> Remarks</label>
                                                {!! Form::text('remarks', null, [
                                                    'id' => 'remarks',
                                                    'class' => 'form-control',
                                                    'tabindex' => '6',
                                                    'placeholder' => 'Remarks',
                                                ]) !!}
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                                <label for="vehicle_no"><i class="fa fa-caret-right"></i> Vehicle No <span
                                                        class="text-danger">*</span></label>
                                                {!! Form::text('vehicle_no', null, [
                                                    'id' => 'vehicle_no',
                                                    'class' => 'form-control',
                                                    'tabindex' => '7',
                                                ]) !!}
                                                <span class="text-danger vehicle_no_err"></span>
                                                @error('vehicle_no')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                                <label for="transport_company"><i class="fa fa-caret-right"></i> Transport
                                                    Company<span class="text-danger">*</span></label>
                                                {!! Form::text('transport_company', null, [
                                                    'id' => 'transport_company',
                                                    'class' => 'form-control',
                                                    'tabindex' => '8',
                                                ]) !!}
                                                <span class="text-danger transport_company_err"></span>
                                                @error('transport_company')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                                <label for="driver_name"><i class="fa fa-caret-right"></i> Driver Name<span
                                                        class="text-danger">*</span></label>
                                                {!! Form::text('driver_name', null, [
                                                    'id' => 'driver_name',
                                                    'class' => 'form-control',
                                                    'tabindex' => '9',
                                                ]) !!}
                                                <span class="text-danger driver_name_err"></span>
                                                @error('driver_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                                <label for="builty_no"><i class="fa fa-caret-right"></i> Builty Number<span
                                                        class="text-danger">*</span></label>
                                                {!! Form::text('builty_no', null, [
                                                    'id' => 'builty_no',
                                                    'class' => 'form-control',
                                                    'tabindex' => '10',
                                                ]) !!}
                                                <span class="text-danger builty_no_err"></span>
                                                @error('builty_no')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                                <label for="driver_phoneno"><i class="fa fa-caret-right"></i> Driver
                                                    PhoneNo<span class="text-danger">*</span></label>
                                                {!! Form::text('driver_phoneno', null, [
                                                    'id' => 'driver_phoneno',
                                                    'class' => 'form-control',
                                                    'tabindex' => '11',
                                                ]) !!}
                                                <span class="text-danger driver_phoneno_err"></span>
                                                @error('driver_phoneno')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                                <label for="freight"><i class="fa fa-caret-right"></i> Freight<span
                                                        class="text-danger">*</span></label>
                                                {!! Form::text('freight', null, [
                                                    'id' => 'freight',
                                                    'class' => 'form-control',
                                                    'tabindex' => '12',
                                                ]) !!}
                                                <span class="text-danger freight_err"></span>
                                                @error('freight')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                                <label for="po_date"><i class="fa fa-caret-right"></i>
                                                    P.O DATE <span class="text-danger">*</span>
                                                </label>
                                                <input id="po_date1" class="form-control" name="po_date1" type="date" value="{{ date('Y-m-d') }}" disabled>
                                                <input id="po_date" class="form-control" name="po_date" type="hidden" value="{{ date('Y-m-d') }}">
                                                <span class="text-danger po_date_err"></span>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                                <label for="po_no"><i class="fa fa-caret-right"></i> P.O.NO</label>
                                                <input id="po_no1" class="form-control" name="po_no1" type="text" disabled>
                                                <input id="po_no" class="form-control" name="po_no" type="hidden" value="0">
                                                <span class="text-danger po_no_err"></span>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="table-responsive-md mb-2">
                                                    <table class="table">
                                                        <thead>
                                                            <tr class="bg-primary text-left">
                                                                <th>Product</th>
                                                                <th>Unit</th>
                                                                <th>Qty</th>
                                                                <th>Packing</th>
                                                                <th>Sale Qty</th>
                                                                <th>Remarks</th>
                                                                <!-- <th>Balance</th> -->
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="bg-secondary">
                                                                <td style="width: 220px!important;">
                                                                    {!! Form::select('product_id1', $products, null, [
                                                                        'id' => 'product_id1',
                                                                        'class' => 'form-control select2',
                                                                        'tabindex' => '8',
                                                                    ]) !!}
                                                                    <span class="product_err text-danger"></span>
                                                                </td>

                                                                <td style="width: 80px;">
                                                                    {!! Form::text('unit1', null, [
                                                                        'id' => 'unit1',
                                                                        'class' => 'form-control',
                                                                        'disabled' => 'disabled',
                                                                        'placeholder' => 'Unit',
                                                                        'tabindex' => '10',
                                                                    ]) !!}
                                                                    <span class="unit_err text-danger"></span>
                                                                </td>


                                                                <td style="width: 100px;">
                                                                    {!! Form::text('qty1', null, [
                                                                        'id' => 'qty1',
                                                                        'class' => 'form-control',
                                                                        'placeholder' => 'Qty',
                                                                        'tabindex' => '13',
                                                                        'onkeyup' => 'Qtychange($(this).val())',
                                                                    ]) !!}
                                                                    <span class="qty_err text-danger"></span>
                                                                </td>
                                                                <td style="width: 100px;">
                                                                    {!! Form::text('packing1', null, [
                                                                        'id' => 'packing1',
                                                                        'class' => 'form-control',
                                                                        'placeholder' => 'Packing',
                                                                        'disabled' => 'disabled',
                                                                        'tabindex' => '14',
                                                                    ]) !!}
                                                                    <span class="packing_err text-danger"></span>
                                                                </td>
                                                                <td style="width: 100px;">
                                                                    {!! Form::text('sale_qty1', null, [
                                                                        'id' => 'sale_qty1',
                                                                        'class' => 'form-control',
                                                                        'placeholder' => 'Sale Qty',
                                                                        'tabindex' => '15',
                                                                        'disabled' => 'disabled',
                                                                    ]) !!}
                                                                    <span class="net_weight_err text-danger"></span>
                                                                </td>
                                                                <td style="width: 100px;">
                                                                    {!! Form::text('comment1', null, [
                                                                        'id' => 'comment1',
                                                                        'class' => 'form-control bg-white',
                                                                        'placeholder' => 'Remarks',
                                                                        'tabindex' => '16',
                                                                    ]) !!}
                                                                    <span class="rate_err text-danger"></span>
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
                                                                    <th style="width: 20%;">Product Name</th>
                                                                    <th>PO#</th>
                                                                    <th>Unit</th>
                                                                    <th>Demand<br/>Pack</th>
                                                                    <th>Demand<br/>Pcs</th>
                                                                    <th>DC.Pack</th>
                                                                    <th>UOM</th>
                                                                    <th style="width: 10%;">Dispatch.Qty</th>
                                                                    <th>Remarks</th>
                                                                    <th>Quantity&nbsp;Balance</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="GridTable"></tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="4"><strong>Total</strong></td>
                                                                    <td class="bg-primary" id="TotalQty">0</td>
                                                                    <td class="bg-primary" id="Totalsaleqty">0</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td class="bg-primary" id="Totaldispatchqty">0</td>
                                                                    <td></td>
                                                                    <td class="bg-success" id="TotalBalance">0</td>
                                                                    
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            
                                            <div class="col-lg-9 col-md-12 col-12">
                                                <!-- <div class="note note-danger">UnPosted By :</div> -->
                                            </div>
                                            <!-- <div class="col-lg-3 col-md-12 col-12">
                                                <div class="note note-warning">Posted By : {{ Auth::User()->name }}</div>
                                            </div>
                                            <div class="col-lg-3 col-md-12 col-12">
                                                <div class="note note-info">Updated By : <span class="d-none"
                                                        id="updated_by_name"> {{ Auth::User()->name }}</span></div>
                                            </div> -->
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
                    <form action="{{ URL::to('delivery-challan-non-gst/delete-voucher') }}" method="post" id="delete_voucher_form">
                        @csrf
                        <div class="modal-body">
                            <p>Are you sure you want to delete this Voucher?</p>
                            <input type="hidden" name="delete_voucher_no" id="delete_voucher_no" value="">
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
    <script src="{{ URL::asset('dashboard/datatables/jquery.js') }}"></script>
<script src="{{ URL::asset('dashboard/datatables/jquery.validate.js') }}"></script>
<script src="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript">
$(document).on('click', '.saleDemand', function() {
    //    alert("dd")
    // $('.data-table tr').empty();
    // $('.data-table').DataTable().destroy();
        var UpdateID = document.getElementById('update_voucher_id').value;
        // alert(UpdateID);
        var party = document.getElementById('party_name');
        var partyid = party.value.split('_')[0];
         
        var TableDatas = new Array();
        var allDatas = new Array();
        var sum = 0;
        // alert(partyid)
        // $('#GridTable tr').each(function(row, tr){
        //     sum += 1;
        //     TableDatas[row]={
        //         "order_detail_id" : $(tr).find('td:eq(12)').text(),
        //     }  
        // });
        //     //It will check checkboxes dynamically
        //     for (var i = 0; i < sum; i++) {
        //         allDatas[i]= TableDatas[row]['order_detail_id'];
        //     }


            // $('.data-table').dataTable().fnDestroy();
            // $('.data-table').DataTable().fnReload();
            var table = $('.data-table').DataTable({
                "pageLength": 100,
                // "order": [[ 1, "asc" ]],
                // "ordering": false 
                processing: true,
                serverSide: true,
                bServerSide: true,
                // stateSave: true,
                bDestroy: true,
                // ajax: "{{ URL::to('delivery-challan-non-gst/load-sale-demands') }}?partyid=" +
                // partyid,
                ajax: "{{ URL::to('delivery-challan-non-gst/load-sale-demands') }}?partyid=" +
                partyid,
                // success: function(response) {  
                //     if (response.data != '') {
                //         var tableHtml = '';

                //         $.each(response.data, function(i, v) {
                //             tableHtml += `<tr>`;
                //             tableHtml += `<td>122</td>`;
                //             tableHtml += `</tr>`;
                //         });

                //         $('#GridSaleDemand').html(tableHtml);
                //      } 
                // }
                columns: [
                    {
                        data: 'selectValue',
                        name: 'selectValue'
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'voucher_no',
                        name: 'voucher_no'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'product_id',
                        name: 'product_id'
                    },
                    {
                        data: 'po_no',
                        name: 'po_no'
                    },
                    {
                        data: 'po_date',
                        name: 'po_date'
                    },
                    {
                        data: 'order_qty',
                        name: 'order_qty'
                    }
                    ,
                    {
                        data: 'balance',
                        name: 'balance'
                    }
                ]
            });
            // table.reload();
            // // // $('.data-table').DataTable().reload();
            // // $('.data-table').dataTable().fnDestroy();
            // $('.data-table').DataTable().ajax.fnReload();
});

// $(document).on('click', '.select-grnno', function() {
$(document).on('click', '.select-checkboxes', function() {
                var someObj = {};
                someObj.fruitsGranted = [];
                $("input:checkbox").each(function() {
                    if ($(this).is(":checked")) {
                        someObj.fruitsGranted.push($(this).attr("id"));
                        someObj.fruitsGranted.checked;
                        
                    }
                });
                
    // $('.grn-numbers-modal').close();
     $('#close-modal').click();
    // var OrderDetailId = $(this).attr('id').split('_')[0];
    var OrderDetailId = someObj.fruitsGranted;
    // alert(OrderDetailId)
    // var voucher_no = $(this).attr('id').split('_')[1];

    $.ajax({
        url: "{{ URL::to('delivery-challan-non-gst/load-data') }}",
        type: 'get',
        data: {
            OrderDetailId: OrderDetailId
        },
        dataType: 'json',
        beforeSend: function() {
            $('#print-receipt-modal-body').html(
                '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
            );
        },
        success: function(response) {
            $('#print-receipt-modal-body').html(null);
            if (response.data != '') {
                var tableHtml = '';
                var TotalBalance = 0;
                $.each(response.data, function(i, v) {
                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                        `<td>${v.product.code}<input type='hidden' name='order_detail_id[]' id='order_detail_id' value='${v.id}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.product_name}
                                            <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                            <input type='hidden' name='sale_rate[]' id='sale_rate' value='${v.sale_rate}' />
                                            <input type='hidden' name='sale_order_no1[]' id='sale_order_no1' value='${v.voucher_no}' />
                                        </td>`;
                                        tableHtml +=
                                        `<td>${v.sale_order.po_no}<input type='hidden' name='po_no[]' id='po_no' value='${v.sale_order.po_no}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=
                                        `<td>${parseInt(v.qty)}<input type='hidden' name='demandqty[]' id='demandqty' value='${v.qty}' /></td>`;
                                        tableHtml +=
                                        `<td>${parseInt(v.order_qty)}<input type='hidden' name='demandPCS[]' id='demandPCS' value='${v.order_qty}' /></td>`;
                                       
                                        var total =0;
                                    $.each(v.dc_details2, function( index, value ) {
                                        total = parseInt(total) + parseInt(value.sale_qty);
                                    });
                                        var stock = v.order_qty - total;

                                        DCPack = parseInt(stock) / parseInt(v.packing);
                                        // alert(DCPack)
                                    if((Math.abs((parseInt(DCPack)*v.packing)-(v.remaing_qty))) == 0){
                                        var remarks = parseInt(DCPack)+'X'+parseInt(v.packing);
                                    // alert('1')
                                        tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${DCPack}' class='form-control' readonly/></td>`;
                                        
                                    }else{
                                        // alert('12')
                                        var DCPack1 = parseInt(DCPack)+ 1;
                                        var remarks = parseInt(DCPack)+'X'+parseInt(v.packing)+',1x'+
                                        (Math.abs((parseInt(DCPack)*v.packing)-(v.remaing_qty)));
                                        tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${DCPack1}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" readonly /></td>`;
                                        
                                    }
                                        tableHtml +=
                                        `<td>${parseInt(v.packing)}<input type='hidden' name='packing[]' id='packing' value='${v.packing}' /></td>`;
                                
                                       
                                        if(stock < 0){
                                        tableHtml +=
                                        // `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='0' class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" style="background: red; color: white;"/></td>`;
                                        `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='0' 
                                        class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));", 
                                        onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));" 
                                        style="background: red; color: white;"/></td>`;
                                    }else{
                                        tableHtml +=
                                        // `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='${stock}' class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="PressEnter($(this).closest('tr'));"/></td>`;
                                        `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' 
                                        value='${stock}' class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));", 
                                        onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));"/></td>`;
                                    }
                                    tableHtml +=
                                        `<td><input type='text' name='comment[]' id='comment' value='${remarks}' class="form-control" readonly/></td>`;
                                        TotalBalance += stock;
                                        tableHtml +=
                                        // `<td><input type='text' name='balance[]' id='balance' value='${stock}' class="form-control" readonly/></td>`;
                                        `<td><input type='text' name='balance[]' id='balance' value='0' class="form-control" readonly/></td>`;
                                        tableHtml +=
                                        `<td style="display:none;"><input type='text' name='balanceForCalculation[]' id='balanceForCalculation' value='${stock}' class="form-control" readonly/></td>`;
                                        tableHtml +=
                                        `<td style="display: none;">${v.id}</td>`;
                                    tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;
                                });
                                // $('#party_name').val(response.data[0].party.id + "_" + response
                                //     .data[0].party.party_name + "_" + response.data[0].party
                                //     .address).select2();
                                // $('#party_id').val(response.data[0].party.id);
                                $('#GridTable').html(tableHtml);
                                $('#remarks').focus();
                                $('#address').val(response.data[0].party.address);
                                $('#order_date').val(response.data[0].voucher_date);
                                $('#po_date').val(response.data[0].sale_order.po_date);
                                $('#po_date1').val(response.data[0].sale_order.po_date);
                                $('#po_no').val(response.data[0].sale_order.po_no);
                                $('#po_no1').val(response.data[0].sale_order.po_no);

                                TotalDemandPack();
                                TotalSaleQty();
                                TotalDespatch();
                                TotalqtyBalance();
                                
                                // $('#TotalQty').text(totalQty);
                                // $('#Totalsaleqty').text(Totalsaleqty);
                                // $('#Totaldispatchqty').text(TotalBalance);
                                // $('#TotalBalance').text(TotalBalance);
                                // $('#TotalQty').text(totalQty);
                                // $('#TotalNetWeight').text(totalNetWeight);
                                // $('#TotalAmount').text(totalAmount);

                                $('#party_name').val(response.data[0].stock.parties.id + '_' + response
                                    .data[0].stock.parties.party_name + '_' + response.data[0].stock
                                    .parties.address).select2();
                                $('#party_id').val(response.data[0].stock.parties.id);
                                $('#address').val(response.data[0].stock.parties.address);
                                $('#remarks').val(response.data[0].stock.remarks);
            } 
            // else {
            //     $('#show_err').html(
            //         '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
            //     );

            //     $('#GridTable').html(null);
            //     $('#TotalQty').text(0);
            //     $('#TotalNetWeight').text(0);
            //     $('#TotalAmount').text(0);
            //     $('#updated_by_name').addClass('d-none');

            //     $('#update_voucher_id').val(null);
            //     $('#voucher_no').focus();
            //     $('#party_name').val(null).select2();
            //     $('#party_id').val(null);
            //     $('#address').val(null);
            //     $('#remarks').val(null);
            // }
        }
    })
});

// Select Voucher Number
$(document).on('click', '.saleDemandss', function() {
    var party = document.getElementById('party_name');
    var partyid = party.value.split('_')[0];
    // alert(partyid)
    $.ajax({
        url: "{{ URL::to('delivery-challan-non-gst/load-sale-demands') }}",
        type: 'get',
        data: {
            partyid: partyid
        },
        dataType: 'json',
        beforeSend: function() {
            $('#print-receipt-modal-body').html(
                '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
            );
        },
        success: function(response) {
            $('#print-receipt-modal-body').html(null);
            if (response.data != '') {
                var tableHtml = '';

                $.each(response.data, function(i, v) {
                    tableHtml += `<tr>`;
                    tableHtml += `<td>122</td>`;
                    tableHtml += `</tr>`;
                });

                $('#GridSaleDemand').html(tableHtml);
             } 
        }
    })
});

</script>
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
            function Qtychange(qty) {
                if (qty == '') {
                    document.getElementById('sale_qty1').value = 0;
                } else {
                    var packing = document.getElementById('packing1').value;
                    var sale_qty = parseInt(packing) * parseInt(qty);
                    document.getElementById('sale_qty1').value = sale_qty;
                }
            }

            $(document).ready(function() {
                $('#voucher_no').keydown(function(event) {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
                        $('#voucher_date').focus();
                    }
                });
                $('#voucher_date').keydown(function(event) {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
                        $("#party_name").select2('open');
                    }
                });
                

                // $('#voucher_date').keydown(function(event) {
                //     var keycode = (event.keyCode ? event.keyCode : event.which);
                //     if (keycode == '13') {
                //         $('#remarks').focus();
                //     }
                // });
                $('#sale_order_no').change(function(event) {
                    var sale_order_no = $(this).val();
                    if (sale_order_no) {
                        $('#sale_order_no').select2().trigger('select2:close');
                        // $('#party_name').select2('open');
                        $('#remarks').focus();
                    }
                });
                $('#party_name').change(function(event) {
                    var party_name = $(this).val();
                    if (party_name) {
                        $('#party_name').select2().trigger('select2:close');
                        var party = $(this).val();
                        $('#party_id').val(party.split('_')[0]);
                        $('#address').val(party.split('_')[2]);
                        // $(".saleDemand").trigger('click');
                        // $($('.saleDemand')[0]).click();
                    }
                });
                $('#order_date').keydown(function(event) {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
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
                        $('#driver_phoneno').focus();
                    }
                });
                $('#driver_phoneno').keydown(function(event) {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
                        $('#freight').focus();
                    }
                });

                $('#freight').keydown(function(event) {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    // alert("dd")
                    if (keycode == '13') {
                        $('#sale_qty').focus();
                        $('#sale_qty').select();
                    }
                });

                // $('#sale_qty').keydown(function(event) {
                //     if (keycode == '13') {
                //         alert("dds")
                //     }
                // });

                // function PressEnter(row){
                    
                // if (keycode == '13') {
                //     var RowIndex = row.index();
                // alert("RowIndex")
                // alert(RowIndex)
                //     }

                // }

                //   $('#sale_qty').keydown(function(event) {
                //     alert("ddsds")
                //     if (keycode == '13') {
                //         alert("ddsds")
                //     }
                // });


                // $('#freight').keydown(function(event) {
                //     var keycode = (event.keyCode ? event.keyCode : event.which);
                //     if (keycode == '13') {
                //         $('#product_id1').select2('open');
                //     }
                // });


                $('#product_id1').change(function(event) {
                    var product_id = $(this).val();
                    if (product_id != null) {
                        $('#unit1').val(product_id.split('_')[2]);
                        $('#product_id1').select2().trigger('select2:close');
                        $('#qty1').focus();
                        $('#packing1').val(product_id.split('_')[3]);

                    }
                });
                $('#qty1').keydown(function(event) {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
                        $('#comment1').focus();
                    }
                });
                $('#comment1').keydown(function(event) {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
                        AddGridData();
                    }
                });
                

            });
        </script>
        <!-- End Focus on next field -->

        <!-- Append New Data on Table -->
        <script>
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

                var product = document.getElementById('product_id1');
                var pro_id = parseInt(product.value.split('_')[0]);
                var pro_name = product.value.split('_')[1];
                var unit = product.value.split('_')[2];
                var packing = parseFloat(product.value.split('_')[3]);
                var sale_rate = parseFloat(product.value.split('_')[4]);
                var qty = parseFloat(document.getElementById('qty1').value);
                var comment = document.getElementById('comment1').value;
                var saleqty = packing * qty;

                var TotalQty = parseFloat(document.getElementById('TotalQty').innerHTML);
                var Totalsaleqty = parseFloat(document.getElementById('Totalsaleqty').innerHTML);

                var grandTotalQty = TotalQty + qty;
                var grandTotalsaleqty = Totalsaleqty + saleqty;

                var tableHtml = `<tr>`;
                tableHtml +=
                    `<td>${pro_name}
                        <input type='hidden' name='product_id[]' id='product_id' value='${parseInt(pro_id)}' />
                        <input type='hidden' name='sale_rate[]' id='sale_rate' value='${sale_rate}' />
                    </td>`;
                tableHtml +=
                    `<td>${unit}</td>`;

                tableHtml +=
                    `<td>${qty}<input type='hidden' name='demandqty[]' id='demandqty' value='${qty}' /></td>`;
                tableHtml +=
                    `<td style="width:120px;">
                        <input type='text' name='qty[]' id='qty' value='0' class='form-control' onkeyup="changeQty($(this).closest('tr'));" readonly />
                    </td>`;
                tableHtml +=
                    `<td>${packing}<input type='hidden' name='packing[]' id='packing' value='${packing}' /></td>`;
                tableHtml +=
                    // `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='${saleqty}' class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event);"/></td>`;
                    `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='${saleqty}' 
                        class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));", 
                        onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));"/>
                        </td>`;
                tableHtml +=
                    `<td>${comment}<input type='hidden' name='comment[]' id='comment' value='${comment}' /></td>`;
                tableHtml +=
                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                tableHtml += `</tr>`;

                $('#GridTable').append(tableHtml);
                $('#TotalQty').html(grandTotalQty.toFixed(2));
                $('#Totalsaleqty').html(grandTotalsaleqty.toFixed(2));



                $('#unit1').val(null);
                $('#qty1').val(null);
                $('#packing1').val(null);
                $('#comment1').val(null);
                $('#sale_qty1').val(null);
                $('#product_id1').val(null).select2();
                $('#product_id1').select2('open');
            }
        </script>
        <!-- End Append New Data on Table -->
        <script>
            function TotalDemandPack() {
                var tableData = document.getElementById('GridTable');
                var sum = 0;
                for (var i = 0; i < tableData.rows.length; i++) {
                    sum += parseFloat(tableData.rows[i].cells[4].getElementsByTagName('input')[0].value);
                }
                document.getElementById('TotalQty').innerText = sum.toLocaleString('en-US');
            }

            function TotalSaleQty() {
                var tableData = document.getElementById('GridTable');
                var sum = 0;
                for (var i = 0; i < tableData.rows.length; i++) {
                    sum += parseFloat(tableData.rows[i].cells[5].getElementsByTagName('input')[0].value);
                }
                document.getElementById('Totalsaleqty').innerText = sum.toLocaleString('en-US');
            }

            
            function TotalDespatch() {
                var tableData = document.getElementById('GridTable');
                var sum = 0;
                for (var i = 0; i < tableData.rows.length; i++) {
                    if(tableData.rows[i].cells[8].getElementsByTagName('input')[0].value > 0){
                        sum += parseFloat(tableData.rows[i].cells[8].getElementsByTagName('input')[0].value);
                    }else{
                        sum += 0;
                    }
                    
                }
                if(sum > 0){
                    document.getElementById('Totaldispatchqty').innerText = sum.toLocaleString('en-US');
                }else{
                    document.getElementById('Totaldispatchqty').innerText = 0;
                }
                
            }

            function TotalqtyBalance() {
                var tableData = document.getElementById('GridTable');
                var sum = 0;
                for (var i = 0; i < tableData.rows.length; i++) {
                    sum += parseFloat(tableData.rows[i].cells[10].getElementsByTagName('input')[0].value);
                }
                document.getElementById('TotalBalance').innerText = sum.toLocaleString('en-US');
            }

            

            function changeQty(row) {
                var qty = $(row).find("td:eq('3')").find('input').val();
                var packing = $(row).find("td:eq('4')").find('input').val();

                var saleqty = qty * packing;
                $(row).find("td:eq('5')").find('input').val(saleqty.toFixed(2));

                TotalSaleQty();
            }

            function EnterKeyBoard(row){ 
                var RowIndex = row.index();
                if(event.keyCode == 13) {     
                    if(RowIndex = '0')
                    {
                    $('tr:eq(' + RowIndex + ')', GridTable).find("td:eq('7')").find('input').focus().select();
                    }
                    if(NextIndex != '0')
                    {
                    var NextIndex = RowIndex + 1;
                    $('tr:eq(' + NextIndex + ')', GridTable).find("td:eq('7')").find('input').focus().select();
                    }
                }
            }

            function changeSaleQty(row) {
                // alert("ddd")
                var unit = $(row).find("td:eq('3')").find('input').val();
                var sale_qty = $(row).find("td:eq('8')").find('input').val();
                var stock = $(row).find("td:eq('11')").find('input').val();
                var DCPack = $(row).find("td:eq('6')").find('input').val();

                var RemainingStock = stock-sale_qty;
                $(row).find("td:eq('10')").find('input').val(Math.round(RemainingStock));
               
                if(unit == "ROLL"){
                    // alert(unit)
                    $(row).find("td:eq('9')").find('input').val(parseInt(DCPack) + 'X' + sale_qty);
                }else{
                    
              
                
                var packing = parseInt($(row).find("td:eq('7')").find('input').val());
                var qty = sale_qty / packing;

                if((Math.abs(((sale_qty) - Math.round(qty) * packing))) == 0){
                    $(row).find("td:eq('6')").find('input').val(Math.round(qty));
                    }else{
                    var final = parseInt(qty)+ 1;
                    $(row).find("td:eq('6')").find('input').val(Math.round(final));
                    }
                if(Math.abs(((sale_qty) - Math.round(qty) * packing)) =='0') {
                        $(row).find("td:eq('9')").find('input').val(parseInt(qty) + 'X' + packing);
                 }
                else{
                    $(row).find("td:eq('9')").find('input').val(parseInt(qty) + 'X' + packing + ',1x' +
                (Math.abs(((sale_qty) - parseInt(qty) * packing))));
                }

                }
                TotalDespatch();
                TotalqtyBalance();
            }


            function changeSaleQtyEdit(row) {

                var sale_qty = $(row).find("td:eq('12')").find('input').val();
                var stock = $(row).find("td:eq('11')").find('input').val();
                var Current = $(row).find("td:eq('8')").find('input').val();
                var DCPack = $(row).find("td:eq('6')").find('input').val();
                var unit = $(row).find("td:eq('3')").find('input').val();
                
                var RemainingStock = parseInt(stock)+parseInt(sale_qty)-parseInt(Current);
                // alert(RemainingStock);
                if(RemainingStock > 0){
                    $(row).find("td:eq('10')").find('input').val(Math.round(RemainingStock));
                }else{
                    $(row).find("td:eq('10')").find('input').val(0);
                }
                
                //   alert(unit)
                // alert(sale_qty)
                // alert(stock)
                if(unit == "ROLL"){
                    // alert(unit)
                    $(row).find("td:eq('9')").find('input').val(parseInt(DCPack) + 'X' + Current);
                }else{
               
                //  alert (stock)
                
                var packing = parseInt($(row).find("td:eq('7')").find('input').val());
                var EditQty = parseInt($(row).find("td:eq('8')").find('input').val());
                 
                var qty = EditQty / packing;
            // $(row).find("td:eq('4')").find('input').val(Math.round(qty));
            //     $(row).find("td:eq('7')").find('input').val(parseInt(qty) + 'X' + packing + ',1x' +
            //     (Math.abs(((sale_qty) - Math.round(qty) * packing))));
            // alert(qty);
            if(qty){
                if((Math.abs(((EditQty) - Math.round(qty) * packing))) == '0'){
                    // alert("1");
                    $(row).find("td:eq('6')").find('input').val(Math.round(qty));
                    }else{
                    var final = parseInt(qty)+ 1;
                    
                    $(row).find("td:eq('6')").find('input').val(Math.round(final));
                    }
                    if(Math.abs(((EditQty) - Math.round(qty) * packing)) =='0') {
                    // alert ("1")
                 
                        $(row).find("td:eq('9')").find('input').val(parseInt(qty) + 'X' + packing);
                    }
                    else{
                        // alert ("EditQty")
                    $(row).find("td:eq('9')").find('input').val(parseInt(qty) + 'X' + packing + ',1x' +
                (Math.abs(((EditQty) - parseInt(qty) * packing))));
                    }
            }else{
                $(row).find("td:eq('6')").find('input').val(0);
                $(row).find("td:eq('9')").find('input').val(0);
            }
            }
            TotalDespatch();
            TotalqtyBalance();
            }
        </script>

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
                //    alert(voucher_no)
                    var sale_order_no = $('#sale_order_no').val();
                    var sale_order_no_edit = $('#sale_order_no_edit').val();
                    //  alert(sale_order_no_edit)
                    var party_name = $('#party_name').val();
                    var vehicle_no = $('#vehicle_no').val();
                    var transport_company = $('#transport_company').val();
                    var driver_name = $('#driver_name').val();
                    var builty_no = $('#builty_no').val();
                    var driver_phoneno = $('#driver_phoneno').val();
                    var freight = $('#freight').val();
                    
                    $('.voucher_no_err').text('');
                    // alert(vo)
                    $('.sale_order_no').text('');
                    $('.sale_order_no_edit').text('');
                    $('.party_name_err').text('');
                    $('.vehicle_no_err').text('');
                    $('.transport_company_err').text('');
                    $('.driver_name_err').text('');
                    $('.builty_no_err').text('');
                    $('.driver_phoneno_err').text('');
                    $('.freight_err').text('');
                    $('.sale_qty').text('');
                    // alert("ddds")

                    if(!voucher_no)
                    {
                        $('.voucher_no_err').text('The voucher no field is required.');
                        return false;
                    }else
                    // if(!sale_order_no)
                    // {
                    //     $('.sale_order_no_err').text('The sale order field is required.');
                    //     return false;
                    // }else
                    // if(!sale_order_no_edit)
                    // {
                    //     $('.sale_order_no_edit_err').text('The sale order field is required.');
                    //     return false;
                    // }else
                    
                    if(!party_name)
                    {
                        $('.party_name_err').text('The party field is required.');
                        return false;
                    }else
                    if(!vehicle_no)
                    {
                        $('.vehicle_no_err').text('The vehicle no field is required.');
                        return false;
                    }else
                    if(!transport_company)
                    {
                        $('.transport_company_err').text('The Transport field is required.');
                        return false;
                    }else
                    if(!driver_name)
                    {
                        $('.driver_name_err').text('The Driver Name field is required.');
                        return false;
                    }else
                    if(!builty_no)
                    {
                        $('.builty_no_err').text('The Builty no field is required.');
                        return false;
                    }else
                    if(!driver_phoneno)
                    {
                        $('.driver_phoneno_err').text('The Driver Phone field is required.');
                        return false;
                    }else
                    if(!freight)
                    {
                        $('.freight_err').text('The Freight field is required.');
                        return false;
                    }else{
                        $('#delivery-challan-form').submit();
                        $('.submit-form').attr('disabled', true);
                    }
                });
                // End FOrm Submit

                // Reset btn feature
                $('.reset-btn').click(function() {
                    $('#party_name').val(null).select2();
                    $('#update_voucher_id').val(null);
                    $('#updated_by_name').addClass('d-none');
                    $('#GridTable').html('');
                    $('#TotalQty').html(0);
                    $('#TotalNetWeight').html(0);
                });
                // End Reset btn feature
            });
        </script>


        <!-- Delete Row -->
        <script>
            function DeleteRow(row) {
                var Totalsaleqty = parseInt(document.getElementById('Totalsaleqty').innerText);
                var NewsaleQty = parseInt($(row).find("td:eq('5')").find('input').val());
                document.getElementById('Totalsaleqty').innerText = (Totalsaleqty - NewsaleQty);

                var TotalQty = parseInt(document.getElementById('TotalQty').innerText);
                var NewQty = parseInt($(row).find("td:eq('2')").find('input').val());
                document.getElementById('TotalQty').innerText = (TotalQty - NewQty);

                $(row).remove();
            }
        </script>
        <!-- End Delete Row -->


        <!-- Main Features -->
        <script>
            $(document).ready(function() {
                // Delete Record
                
                $('.delete_record_btn').click(function() {
                    // alert("dsdfsd")
                    var myModal = new bootstrap.Modal(document.getElementById('delete-record-modal'), {});
                    myModal.toggle();
                    var voucher_no = parseInt($('#voucher_no').val());
                    $('#delete_voucher_no').val(voucher_no);
                });
                $('.print_record_btn').click(function() {
                    var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                    myModal.toggle();

                    var voucher_no = parseInt($('#voucher_no').val());
                    var base_url = $('#base_url').val();
                    $.ajax({
                        url: "{{ URL::to('delivery-challan-non-gst/print/voucher') }}?voucher_no=" +
                            voucher_no,
                        type: 'get',
                        beforeSend: function(response) {
                            $('#print-receipt-modal-body').html(
                                '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                            );
                        },
                        success: function(response) {
                            if (response != null && response != 0) {
                                // alert("dd")
                                $('#print-receipt-modal-body').html(
                                    `<object data="${base_url}/resources/upload/delivery-challan-no-gst/${response}" type="application/pdf" width="100%" height="800"></object>`
                                );
                            } else {
                                $('#print-receipt-modal-body').html(
                                    '<h2 style="color:red;text-align:center;">Voucher Not Exist</h2>'
                                );
                            }
                        }
                    });
                });

                $('#sale_order_no').change(function() {
                    //    alert("dd");
                    var sale_order_id = $(this).val();
                    // alert(sale_order_id)
                    $.ajax({
                        url: "{{ URL::to('delivery-challan-non-gst/sale-order/recorde/fetch') }}",
                        type: 'get',
                        dataType: 'json',
                        data: {
                            sale_order_id: sale_order_id
                        },
                        success: function(response) {
                            var tableHtml = '';
                            var totalQty = 0;
                            var Totalsaleqty = 0;
                            var Totaldispatchqty = 0;
                            var TotalBalance = 0;
                            var RemainingPack = 0;
                            var RemainderPack = 0;

                            if (response.status == 0) {
                                $.each(response.data, function(i, v) {
                                    totalQty += parseInt(v.qty);
                                    Totalsaleqty += parseInt(v.order_qty);
                                    Totaldispatchqty += parseInt(v.order_qty);
                                    
                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                        `<td>${v.customer_product.product.product_code}</td>`;
                                    tableHtml +=
                                        `<td>${v.customer_product.product.product_name}
                                            <input type='hidden' name='product_id[]' id='product_id' value='${v.customer_product.product.id}' />
                                            <input type='hidden' name='sale_rate[]' id='sale_rate' value='${v.sale_rate}' />
                                        </td>`;
                                    tableHtml +=
                                        `<td>${v.customer_product.product.uom}</td>`;
                                        // tableHtml +=
                                        // `<td>ddddd</td>`;
                                    tableHtml +=
                                        `<td>${parseInt(v.qty)}<input type='hidden' name='demandqty[]' id='demandqty' value='${v.qty}' /></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${parseInt(v.qty)}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" readonly /></td>`;
                                    
                                        tableHtml +=
                                        `<td>${parseInt(v.packing)}<input type='hidden' name='packing[]' id='packing' value='${v.packing}' /></td>`;
                                    tableHtml +=
                                        // `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='${v.order_qty}' class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" /></td>`;
                                        `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='${v.order_qty}' 
                                            class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));", 
                                            onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));"/>
                                            </td>`;
                                    tableHtml +=
                                        `<td><input type='text' name='comment[]' id='comment' value='${v.remark}' class="form-control" /></td>`;
                                        // tableHtml +=
                                        // `<td><input type='text' name='comment[]' id='comment' value='2444' class="form-control" /></td>`;
                                        tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;
                                });

                                $('#party_name').val(response.data[0].party.id + "_" + response
                                    .data[0].party.party_name + "_" + response.data[0].party
                                    .address).select2();
                                $('#party_id').val(response.data[0].party.id);
                                $('#address').val(response.data[0].party.address);
                                $('#order_date').val(response.data[0].sale_order.voucher_date);
                                $('#po_date').val(response.data[0].sale_order.po_date);
                                $('#po_date1').val(response.data[0].sale_order.po_date);
                                $('#po_no').val(response.data[0].sale_order.po_no);
                                $('#po_no1').val(response.data[0].sale_order.po_no);
                                // $('#sale_order_no').val(response.data[0].sale_order.voucher_no);
                                $('#GridTable').html(tableHtml);
                                $('#TotalQty').text(totalQty);
                                $('#Totalsaleqty').text(Totalsaleqty);

                            }
                            if (response.status == 1) {
// alert("dddddbbbbb")
                                $.each(response.data, function(i, v) {
                                    totalQty += parseInt(v.qty);
                                    Totalsaleqty += parseInt(v.order_qty);
                                    RemainingPack = parseInt(v.remaing_qty) / parseInt(v.packing);
                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                        `<td>${v.product.code}</td>`;
                                    tableHtml +=
                                        `<td>${v.product.product_name}
                                            <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                            <input type='hidden' name='sale_rate[]' id='sale_rate' value='${v.sale_rate}' />
                                        </td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=
                                        `<td>${parseInt(v.qty)}<input type='hidden' name='demandqty[]' id='demandqty' value='${v.qty}' /></td>`;
                                        tableHtml +=
                                        `<td>${parseInt(v.order_qty)}<input type='hidden' name='demandPCS[]' id='demandPCS' value='${v.order_qty}' /></td>`;
                                       
                                        var total =0;
                                    $.each(v.product.dc_details, function( index, value ) {
                                        total = parseInt(total) + parseInt(value.sale_qty);
                                    });
                                        var stock = v.order_qty - total;

                                        DCPack = parseInt(stock) / parseInt(v.packing);
                                        // alert(DCPack)
                                    if((Math.abs((parseInt(DCPack)*v.packing)-(v.remaing_qty))) == 0){
                                        var remarks = parseInt(DCPack)+'X'+parseInt(v.packing);
                                    // alert('1')
                                        tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${DCPack}' class='form-control' readonly/></td>`;
                                        
                                    }else{
                                        // alert('12')
                                        var DCPack1 = parseInt(DCPack)+ 1;
                                        var remarks = parseInt(DCPack)+'X'+parseInt(v.packing)+',1x'+
                                        (Math.abs((parseInt(DCPack)*v.packing)-(v.remaing_qty)));
                                        tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${DCPack1}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" readonly /></td>`;
                                        
                                    }
                                        tableHtml +=
                                        `<td>${parseInt(v.packing)}<input type='hidden' name='packing[]' id='packing' value='${v.packing}' /></td>`;
                                
                                       
                                        if(stock < 0){
                                        tableHtml +=
                                        // `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='0' class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" style="background: red; color: white;"/></td>`;
                                        `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='0' 
                                        class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));", 
                                        onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));" 
                                        style="background: red; color: white;"/></td>`;
                                    }else{
                                        tableHtml +=
                                        // `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='${stock}' class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="PressEnter($(this).closest('tr'));"/></td>`;
                                        `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' 
                                        value='${stock}' class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));", 
                                        onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));"/></td>`;
                                    }
                                    tableHtml +=
                                        `<td><input type='text' name='comment[]' id='comment' value='${remarks}' class="form-control" readonly/></td>`;
                                        TotalBalance += stock;
                                        tableHtml +=
                                        `<td><input type='text' name='balance[]' id='balance' value='${stock}' class="form-control" readonly/></td>`;
                                        tableHtml +=
                                        `<td style="display:none;"><input type='text' name='balanceForCalculation[]' id='balanceForCalculation' value='${stock}' class="form-control" readonly/></td>`;
                                    tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;
                                });
                                $('#party_name').val(response.data[0].party.id + "_" + response
                                    .data[0].party.party_name + "_" + response.data[0].party
                                    .address).select2();
                                $('#party_id').val(response.data[0].party.id);
                                $('#address').val(response.data[0].party.address);
                                $('#order_date').val(response.data[0].voucher_date);
                                $('#po_date').val(response.data[0].sale_order.po_date);
                                $('#po_date1').val(response.data[0].sale_order.po_date);
                                $('#po_no').val(response.data[0].sale_order.po_no);
                                $('#po_no1').val(response.data[0].sale_order.po_no);
                                $('#GridTable').html(tableHtml);
                                $('#TotalQty').text(totalQty);
                                $('#Totalsaleqty').text(Totalsaleqty);
                                $('#Totaldispatchqty').text(TotalBalance);
                                $('#TotalBalance').text(TotalBalance);
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
                    // $(".SelectsaleDemand").hide();
                    $.ajax({
                        url: "{{ URL::to('delivery-challan-non-gst/load/record') }}?voucher_no=" + voucher_no,
                        type: 'get',
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#show_err').html(
                                '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                            );
                        },
                        success: function(response) {
                            if (response.data != '') {
                                TotalDemandPack();
                                TotalSaleQty();
                                TotalDespatch();
                                TotalqtyBalance();
                                $('#updated_by_name').removeClass('d-none');

                                $('#voucher_no').val(response.data[0].delivery_challan.voucher_no);
                                $('#update_voucher_id').val(response.data[0].delivery_challan.id);
                                $('#voucher_date').val(response.data[0].delivery_challan
                                    .voucher_date);
                                $('#voucher_no').val(response.data[0].delivery_challan.voucher_no);
                                $('#voucher_no').focus();
                                $('#party_name').val(response.data[0].delivery_challan.party.id +
                                    '_' +
                                    response.data[0].delivery_challan.party.party_name + '_' +
                                    response
                                    .data[0].delivery_challan.party.address).select2();
                                   
                                $('#party_id').val(response.data[0].delivery_challan.party_id);
                                $('#order_date').val(response.data[0].delivery_challan.order_date);
                                // $('#sale_order_no').val(response.data[0].delivery_challan
                                //     .sale_order_no).select2();
                                $('#address').val(response.data[0].delivery_challan.party.address);
                                $('#remarks').val(response.data[0].delivery_challan.remarks);
                                $('#vehicle_no').val(response.data[0].delivery_challan.vehicle_no);
                                $('#driver_name').val(response.data[0].delivery_challan
                                    .driver_name);
                                $('#builty_no').val(response.data[0].delivery_challan.builty_no);
                                $('#driver_phoneno').val(response.data[0].delivery_challan
                                    .driver_phoneno);
                                $('#transport_company').val(response.data[0].delivery_challan
                                    .transport_company);
                                     
                                $('#freight').val(response.data[0].delivery_challan.freight);
                                if(response.data[0].delivery_challan
                                    .sale_order){
                                        $('.sale-order2 input').val(response.data[0].delivery_challan
                                    .sale_order.voucher_no);
                                    }
                                
                                $('#po_date').val(response.data[0].delivery_challan.po_date);
                                $('#po_date1').val(response.data[0].delivery_challan.po_date);
                                $('#po_no').val(response.data[0].delivery_challan.po_no);
                                $('#po_no1').val(response.data[0].delivery_challan.po_no);
                                $('.sale-order2').removeClass('d-none');
                                $('.sale-order1').addClass('d-none');
                                
                                var tableHtml = '';
                                var TotalBalance = 0;

                                $.each(response.data, function(i, v) {
                                  
                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                        `<td>${v.product.code}<input type='hidden' name='order_detail_id[]' id='order_detail_id' value='${v.order_detail_id}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.product_name}
                                            <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                            <input type='hidden' name='sale_rate[]' id='sale_rate' value='${v.sale_rate}' />
                                        <input type='hidden' name='sale_order_no1[]' id='sale_order_no1' value='${v.voucher_no}' />
                                        </td>`;
                                        
                                            tableHtml +=
                                        `<td>${v.po_no}<input type='hidden' name='po_no[]' id='po_no' value='${v.po_no}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=
                                        `<td>${parseInt(v.demandqty)}<input type='hidden' name='demandqty[]' id='demandqty' value='${v.demandqty}' /></td>`;
                                    tableHtml +=
                                        `<td>${parseInt(v.demandPCS)}<input type='hidden' name='demandPCS[]' id='demandPCS' value='${v.demandPCS}' /></td>`;
                                        tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.quantity}' class='form-control' readonly/></td>`;
                                    tableHtml +=
                                    `<td>${parseInt(v.packing)}<input type='hidden' name='packing[]' id='packing' value='${v.packing}' /></td>`;
                                    tableHtml +=
                                        // `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='${v.sale_qty}'  class='form-control' onkeyup="changeSaleQtyEdit($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                        `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='${v.sale_qty}' 
                                            class='form-control' onkeyup="changeSaleQtyEdit($(this).closest('tr'));", 
                                            onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));"/>
                                            </td>`;
                                            tableHtml +=
                                    `<td><input type='text' name='comment[]' id='comment' value='${v.comments}' class='form-control' readonly/></td>`;
                                        var total =0;
                                        if(v.saleorderdetail != null){
                                            $.each(v.saleorderdetail.dc_details2, function( index, value ) {
                                            total = parseInt(total) + parseInt(value.sale_qty);
                                        });
                                        var stock = v.demandPCS-total;
                                        }else{
                                            var stock = 0;
                                        }
                                            tableHtml +=
                                        `<td><input type='text' name='balance[]' id='balance' value='${stock}' class="form-control" readonly/></td>`;
                                        TotalBalance += parseInt(stock);
                                        tableHtml +=
                                        `<td style="display:none;"><input type='text' name='StockForFormula' id='StockForFormula' value='${stock}' class="form-control" readonly/></td>`;
                                        tableHtml +=
                                        `<td style="display:none;"><input type='text' name='DispatchQtyForFormula' id='DispatchQtyForFormula' value='${v.sale_qty}' class="form-control" readonly/></td>`;
                                    tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;
                                });

                                $('#GridTable').html(tableHtml);
                                

                            } else {
                                $('#show_err').html(
                                    '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                                );

                                $('#GridTable').html(null);
                                $('#TotalQty').text(0);
                                $('#Totalsaleqty').text(0);
                                $('#updated_by_name').addClass('d-none');

                                $('#update_voucher_id').val(null);
                                $('#voucher_no').focus();
                                $('#party_name').val(null).select2();
                                $('#party_id').val(null);
                                $('#address').val(null);
                                $('#remarks').val(null);
                                $('#vehicle_no').val(null);
                                $('#driver_name').val(null);
                                $('#builty_no').val(null);
                                $('#driver_phoneno').val(null);
                                $('#transport_company').val(null);
                                $('#freight').val(null);
                                $('#po_date').val(null);
                                $('#po_date1').val(null);
                                $('#po_no').val(null);
                                $('#po_no1').val(null);
                            }
                        }
                    });
                });


                // Load Next Record
                $('.load-next-record').click(function() {
                    var voucher_no = parseInt($('#voucher_no').val());
                    // $(".SelectsaleDemand").hide();
                    // alert(voucher_no)
                    $.ajax({
                        url: "{{ URL::to('delivery-challan-non-gst/load/next/record') }}?voucher_no=" +
                            voucher_no,
                        type: 'get',
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#show_err').html(
                                '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                            );
                        },
                        success: function(response) {
                            if (response.data != '') {
                                TotalDemandPack();
                                TotalSaleQty();
                                TotalDespatch();
                                TotalqtyBalance();
                                $('#updated_by_name').removeClass('d-none');

                                $('#voucher_no').val(response.data[0].delivery_challan.voucher_no);
                                $('#update_voucher_id').val(response.data[0].delivery_challan.id);
                                $('#voucher_date').val(response.data[0].delivery_challan
                                    .voucher_date);
                                $('#voucher_no').val(response.data[0].delivery_challan.voucher_no);
                                $('#voucher_no').focus();
                                $('#party_name').val(response.data[0].delivery_challan.party.id +
                                    '_' +
                                    response.data[0].delivery_challan.party.party_name + '_' +
                                    response
                                    .data[0].delivery_challan.party.address).select2();
                                   
                                $('#party_id').val(response.data[0].delivery_challan.party_id);
                                $('#order_date').val(response.data[0].delivery_challan.order_date);
                                // $('#sale_order_no').val(response.data[0].delivery_challan
                                //     .sale_order_no).select2();
                                $('#address').val(response.data[0].delivery_challan.party.address);
                                $('#remarks').val(response.data[0].delivery_challan.remarks);
                                $('#vehicle_no').val(response.data[0].delivery_challan.vehicle_no);
                                $('#driver_name').val(response.data[0].delivery_challan
                                    .driver_name);
                                $('#builty_no').val(response.data[0].delivery_challan.builty_no);
                                $('#driver_phoneno').val(response.data[0].delivery_challan
                                    .driver_phoneno);
                                $('#transport_company').val(response.data[0].delivery_challan
                                    .transport_company);
                                     
                                $('#freight').val(response.data[0].delivery_challan.freight);
                                if(response.data[0].delivery_challan
                                    .sale_order){
                                        $('.sale-order2 input').val(response.data[0].delivery_challan
                                    .sale_order.voucher_no);
                                    }
                                
                                $('#po_date').val(response.data[0].delivery_challan.po_date);
                                $('#po_date1').val(response.data[0].delivery_challan.po_date);
                                $('#po_no').val(response.data[0].delivery_challan.po_no);
                                $('#po_no1').val(response.data[0].delivery_challan.po_no);
                                $('.sale-order2').removeClass('d-none');
                                $('.sale-order1').addClass('d-none');
                                var tableHtml = '';
                                var TotalBalance = 0;

                                $.each(response.data, function(i, v) {
                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                        `<td>${v.product.code}<input type='hidden' name='order_detail_id[]' id='order_detail_id' value='${v.order_detail_id}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.product_name}
                                            <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                            <input type='hidden' name='sale_rate[]' id='sale_rate' value='${v.sale_rate}' />
                                        <input type='hidden' name='sale_order_no1[]' id='sale_order_no1' value='${v.voucher_no}' />
                                        </td>`;
                                        
                                            tableHtml +=
                                        `<td>${v.po_no}<input type='hidden' name='po_no[]' id='po_no' value='${v.po_no}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=
                                        `<td>${parseInt(v.demandqty)}<input type='hidden' name='demandqty[]' id='demandqty' value='${v.demandqty}' /></td>`;
                                    tableHtml +=
                                        `<td>${parseInt(v.demandPCS)}<input type='hidden' name='demandPCS[]' id='demandPCS' value='${v.demandPCS}' /></td>`;
                                        tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.quantity}' class='form-control' readonly/></td>`;
                                    tableHtml +=
                                    `<td>${parseInt(v.packing)}<input type='hidden' name='packing[]' id='packing' value='${v.packing}' /></td>`;
                                    tableHtml +=
                                        // `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='${v.sale_qty}'  class='form-control' onkeyup="changeSaleQtyEdit($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                        `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='${v.sale_qty}' 
                                            class='form-control' onkeyup="changeSaleQtyEdit($(this).closest('tr'));", 
                                            onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));"/>
                                            </td>`;
                                            tableHtml +=
                                    `<td><input type='text' name='comment[]' id='comment' value='${v.comments}' class='form-control' readonly/></td>`;
                                        var total =0;
                                        if(v.saleorderdetail != null){
                                            $.each(v.saleorderdetail.dc_details2, function( index, value ) {
                                            total = parseInt(total) + parseInt(value.sale_qty);
                                        });
                                        var stock = v.demandPCS-total;
                                        }else{
                                            var stock = 0;
                                        }
                                            tableHtml +=
                                        `<td><input type='text' name='balance[]' id='balance' value='${stock}' class="form-control" readonly/></td>`;
                                        TotalBalance += parseInt(stock);
                                        tableHtml +=
                                        `<td style="display:none;"><input type='text' name='StockForFormula' id='StockForFormula' value='${stock}' class="form-control" readonly/></td>`;
                                        tableHtml +=
                                        `<td style="display:none;"><input type='text' name='DispatchQtyForFormula' id='DispatchQtyForFormula' value='${v.sale_qty}' class="form-control" readonly/></td>`;
                                    tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;
                                });

                                $('#GridTable').html(tableHtml);
                            } else {
                                $('#show_err').html(
                                    '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                                );

                                $('#GridTable').html(null);
                                $('#TotalQty').text(0);
                                $('#Totalsaleqty').text(0);
                                $('#updated_by_name').addClass('d-none');

                                $('#update_voucher_id').val(null);
                                $('#voucher_no').focus();
                                $('#party_name').val(null).select2();
                                $('#party_id').val(null);
                                $('#address').val(null);
                                $('#remarks').val(null);
                                $('#vehicle_no').val(null);
                                $('#driver_name').val(null);
                                $('#builty_no').val(null);
                                $('#driver_phoneno').val(null);
                                $('#transport_company').val(null);
                                $('#freight').val(null);
                                $('#po_date').val(null);
                                $('#po_date1').val(null);
                                $('#po_no').val(null);
                                $('#po_no1').val(null);
                            }
                        }
                    });
                });
                // End Here of Load Next Record


                // Load Previous Record
                $('.load-previous-record').click(function() {
                    var voucher_no = parseInt($('#voucher_no').val());
                    // SelectsaleDemand
                    // $(".SelectsaleDemand").hide();
                    // alert(voucher_no)
                    $.ajax({
                        url: "{{ URL::to('delivery-challan-non-gst/load/previous/record') }}?voucher_no=" +
                            voucher_no,
                        type: 'get',
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#show_err').html(
                                '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                            );
                        },
                        success: function(response) {
                            if (response.data != '') {
                                TotalDemandPack();
                                TotalSaleQty();
                                TotalDespatch();
                                TotalqtyBalance();
                                $('#updated_by_name').removeClass('d-none');

                                $('#voucher_no').val(response.data[0].delivery_challan.voucher_no);
                                $('#update_voucher_id').val(response.data[0].delivery_challan.id);
                                $('#voucher_date').val(response.data[0].delivery_challan
                                    .voucher_date);
                                $('#voucher_no').val(response.data[0].delivery_challan.voucher_no);
                                $('#voucher_no').focus();
                                $('#party_name').val(response.data[0].delivery_challan.party.id +
                                    '_' +
                                    response.data[0].delivery_challan.party.party_name + '_' +
                                    response
                                    .data[0].delivery_challan.party.address).select2();
                                   
                                $('#party_id').val(response.data[0].delivery_challan.party_id);
                                $('#order_date').val(response.data[0].delivery_challan.order_date);
                                // $('#sale_order_no').val(response.data[0].delivery_challan
                                //     .sale_order_no).select2();
                                $('#address').val(response.data[0].delivery_challan.party.address);
                                $('#remarks').val(response.data[0].delivery_challan.remarks);
                                $('#vehicle_no').val(response.data[0].delivery_challan.vehicle_no);
                                $('#driver_name').val(response.data[0].delivery_challan
                                    .driver_name);
                                $('#builty_no').val(response.data[0].delivery_challan.builty_no);
                                $('#driver_phoneno').val(response.data[0].delivery_challan
                                    .driver_phoneno);
                                $('#transport_company').val(response.data[0].delivery_challan
                                    .transport_company);
                                     
                                $('#freight').val(response.data[0].delivery_challan.freight);
                                if(response.data[0].delivery_challan
                                    .sale_order){
                                        $('.sale-order2 input').val(response.data[0].delivery_challan
                                    .sale_order.voucher_no);
                                    }
                                
                                $('#po_date').val(response.data[0].delivery_challan.po_date);
                                $('#po_date1').val(response.data[0].delivery_challan.po_date);
                                $('#po_no').val(response.data[0].delivery_challan.po_no);
                                $('#po_no1').val(response.data[0].delivery_challan.po_no);
                                $('.sale-order2').removeClass('d-none');
                                $('.sale-order1').addClass('d-none');
                                var tableHtml = '';
                                var TotalBalance = 0;

                                $.each(response.data, function(i, v) {
                                   
                                    

                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                        `<td>${v.product.code}<input type='hidden' name='order_detail_id[]' id='order_detail_id' value='${v.order_detail_id}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.product_name}
                                            <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                            <input type='hidden' name='sale_rate[]' id='sale_rate' value='${v.sale_rate}' />
                                        <input type='hidden' name='sale_order_no1[]' id='sale_order_no1' value='${v.voucher_no}' />
                                        </td>`;
                                        
                                            tableHtml +=
                                        `<td>${v.po_no}<input type='hidden' name='po_no[]' id='po_no' value='${v.po_no}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=
                                        `<td>${parseInt(v.demandqty)}<input type='hidden' name='demandqty[]' id='demandqty' value='${v.demandqty}' /></td>`;
                                    tableHtml +=
                                        `<td>${parseInt(v.demandPCS)}<input type='hidden' name='demandPCS[]' id='demandPCS' value='${v.demandPCS}' /></td>`;
                                        tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.quantity}' class='form-control' readonly/></td>`;
                                    tableHtml +=
                                    `<td>${parseInt(v.packing)}<input type='hidden' name='packing[]' id='packing' value='${v.packing}' /></td>`;
                                    tableHtml +=
                                        // `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='${v.sale_qty}'  class='form-control' onkeyup="changeSaleQtyEdit($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                        `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='${v.sale_qty}' 
                                            class='form-control' onkeyup="changeSaleQtyEdit($(this).closest('tr'));", 
                                            onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));"/>
                                            </td>`;
                                            tableHtml +=
                                    `<td><input type='text' name='comment[]' id='comment' value='${v.comments}' class='form-control' readonly/></td>`;
                                        var total =0;
                                        if(v.saleorderdetail != null){
                                            $.each(v.saleorderdetail.dc_details2, function( index, value ) {
                                            total = parseInt(total) + parseInt(value.sale_qty);
                                        });
                                        var stock = v.demandPCS-total;
                                        }else{
                                            var stock = 0;
                                        }
                                            tableHtml +=
                                        `<td><input type='text' name='balance[]' id='balance' value='${stock}' class="form-control" readonly/></td>`;
                                        TotalBalance += parseInt(stock);
                                        tableHtml +=
                                        `<td style="display:none;"><input type='text' name='StockForFormula' id='StockForFormula' value='${stock}' class="form-control" readonly/></td>`;
                                        tableHtml +=
                                        `<td style="display:none;"><input type='text' name='DispatchQtyForFormula' id='DispatchQtyForFormula' value='${v.sale_qty}' class="form-control" readonly/></td>`;
                                    tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;
                                });

                                $('#GridTable').html(tableHtml);
                               
                                

                            } else {
                                $('#show_err').html(
                                    '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                                );

                                $('#GridTable').html(null);
                                $('#TotalQty').text(0);
                                $('#Totalsaleqty').text(0);
                                $('#updated_by_name').addClass('d-none');

                                $('#update_voucher_id').val(null);
                                $('#voucher_no').focus();
                                $('#party_name').val(null).select2();
                                $('#party_id').val(null);
                                $('#address').val(null);
                                $('#remarks').val(null);
                                $('#vehicle_no').val(null);
                                $('#driver_name').val(null);
                                $('#builty_no').val(null);
                                $('#driver_phoneno').val(null);
                                $('#transport_company').val(null);
                                $('#freight').val(null);
                                $('#po_date').val(null);
                                $('#po_date1').val(null);
                                $('#po_no').val(null);
                                $('#po_no1').val(null);
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
