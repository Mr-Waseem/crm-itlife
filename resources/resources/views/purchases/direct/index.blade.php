@extends('app')
@section('head')
    <title>Purchase Voucher</title>
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
            <h1>Purchase Voucher</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Purchase Voucher</a></li>
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
                                    {!! Form::open(['url' => 'direct-purchases', 'class' => 'form-horizontal', 'id' => 'sales-voucher-form']) !!}
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
                                            <span class="text-danger warehouse_id_err"></span>
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
                                            <span class="text-danger voucher_no_err"></span>
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
                                            <span class="text-danger voucher_no_err"></span>
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
                                            <span class="text-danger warehouse_id_err"></span>
                                           
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
                                            <span class="text-danger voucher_no_err"></span>
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
                                            <span class="text-danger voucher_no_err"></span>
                                        </div>
                                        @endif
                                        @endif
                                   
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
                                     
                                      
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                            <label for="party_id"><i class="fa fa-caret-right"></i> Party Name<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('party_id', $customers, null, [
                                                'id' => 'party_id',
                                                'class' => 'form-control select2 customer',
                                                'tabindex' => '2',
                                                
                                            ]) !!}
                                             <span class="text-danger party_id_err"></span>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                            <label for="party_id"><i class="fa fa-caret-right"></i>Purchaser Name<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('purchaser_id', $purchasers, null, [
                                                'id' => 'purchaser_id',
                                                'class' => 'form-control select2 customer',
                                                'tabindex' => '2',
                                                
                                            ]) !!}
                                             <span class="text-danger purchaser_id_err"></span>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1">
                                        <label for="grn_id"><i class="fa fa-caret-right"></i>Credit To<span
                                                class="text-danger">*</span></label>
                                        {!! Form::select('credit_to', array('' => 'SELECT TYPE', 'SUPPLIER' => 'SUPPLIER', 'PURCHASER' => 'PURCHASER'), null, [
                                        'id' => 'credit_to',
                                        'class' => 'form-control select2',
                                        'tabindex' => '2',
                                        'required' => 'required',
                                        ]) !!}
                                        <span class="text-danger credit_to_err"></span>
                                        <!-- @error('credit_to_err')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror -->
                                    </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i> Remarks</label>
                                            {!! Form::text('remarks', null, [
                                                'id' => 'remarks',
                                                'class' => 'form-control',
                                                'tabindex' => '5',
                                                'placeholder' => 'Remarks',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1">
                                            <label for="vehicle_no"><i class="fa fa-caret-right"></i> Vehicle No</label>
                                            {!! Form::text('vehicle_no', null, [
                                                'id' => 'vehicle_no',
                                                'class' => 'form-control'
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 mt-1">
                                            <label for="transport_company"><i class="fa fa-caret-right"></i> Transport
                                                Company</label>
                                            {!! Form::text('transport_company', null, [
                                                'id' => 'transport_company',
                                                'class' => 'form-control'
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                            <label for="driver_name"><i class="fa fa-caret-right"></i> Driver Name</label>
                                            {!! Form::text('driver_name', null, [
                                                'id' => 'driver_name',
                                                'class' => 'form-control'
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1">
                                            <label for="builty_no"><i class="fa fa-caret-right"></i> Builty Number</label>
                                            {!! Form::text('builty_no', null, [
                                                'id' => 'builty_no',
                                                'class' => 'form-control'
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1">
                                            <label for="freight"><i class="fa fa-caret-right"></i> Freight</label>
                                            {!! Form::text('freight', null, [
                                                'id' => 'freight',
                                                'class' => 'form-control'
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1">
                                            <label for="driver_phoneno"><i class="fa fa-caret-right"></i> Driver
                                                Phone</label>
                                            {!! Form::text('driver_phoneno', null, [
                                                'id' => 'driver_phoneno',
                                                'class' => 'form-control'
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
                                                            <!-- <th>Sale Qty</th> -->
                                                            <th>Qty</th>
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
                                                            <!-- <td>
                                                                {!! Form::text('sale_qty1', null, [
                                                                    'id' => 'sale_qty1',
                                                                    'class' => 'form-control bg-white',
                                                                    'onkeypress' => 'return isNumberKey(event)',
                                                                    'placeholder' => 'Sale Qty'
                                                                ]) !!}
                                                                <span class="sale_qty_err text-danger"></span>
                                                            </td> -->
                                                            <td>
                                                                {!! Form::text('qty1', null, [
                                                                    'id' => 'qty1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Qty',
                                                                    'onkeypress' => 'return isNumberKey(event)',
                                                                    'onkeyup' => 'QuantityKeyUp($(this).val())',
                                                                ]) !!}
                                                                <span class="qty_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('price1', null, [
                                                                    'id' => 'price1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Rate',
                                                                    'onkeypress' => 'return isNumberKey(event)',
                                                                    'onkeyup' => 'QuantityKeyUp($(this).val())',
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
                                                            <tr>
                                                                <!-- <th>Code</th> -->
                                                                <th style="width:35%;">Product Name</th>
                                                                <th>Pack</th>
                                                                <th>Packing</th>
                                                                <th>Unit</th>
                                                                <!-- <th>Sale Qty</th> -->
                                                                <th>Qty</th>
                                                                <th>Rate</th>
                                                                <th style="width:20%;">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td><strong>Total</strong></td>
                                                                <td class="bg-primary" id="TotalPack">0</td>
                                                                
                                                                <td></td>
                                                                
                                                                <td></td>
                                                                <!-- <td class="bg-primary" id="TotalSaleQty">0</td> -->
                                                                <td class="bg-success" id="TotalQty">0</td>
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
                <form action="{{ URL::to('direct-purchases/delete-voucher') }}" method="post" id="delete_voucher_form">
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
            // alert(sum);
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseFloat(tableData.rows[i].cells[6].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalAmount').innerText = sum.toLocaleString('en-US');
        }


        // function TotalSaleQty() {
        //     alert(333);
        //     var tableData = document.getElementById('GridTable');
        //     var sum =0;
        //     for (var i = 0; i < tableData.rows.length; i++) {
        //         var total = parseFloat(tableData.rows[i].cells[5].getElementsByTagName('input')[0].value);
                
        //         if(total){
        //             sum += parseFloat(tableData.rows[i].cells[5].getElementsByTagName('input')[0].value);
        //         }
            
        //     }
        //     document.getElementById('TotalQty').innerText = sum.toLocaleString('en-US');
        // }

        function TotalSaleQty() {
            var tableData = document.getElementById('GridTable');
            var sum =0;
            
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseFloat(tableData.rows[i].cells[4].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalQty').innerText = sum.toLocaleString('en-US');
        }

        function TotalPack() {
            var tableData = document.getElementById('GridTable');
            var sum =0;
          
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseFloat(tableData.rows[i].cells[1].getElementsByTagName('input')[0].value);
                // alert(sum);
            }
            document.getElementById('TotalPack').innerText = sum.toLocaleString('en-US');
        }

        



       

        function changerate(row) {
           
            var Qty = $(row).find("td:eq('4')").find('input').val();
            var rate = $(row).find("td:eq('5')").find('input').val();
            var amount = Qty * rate;
            $(row).find("td:eq('6')").find('input').val(parseFloat(amount));

            var packing = $(row).find("td:eq('2')").find('input').val();
            var pack = Qty / packing;

            if((Math.abs((parseInt(pack)*packing)-(Qty))) == 0){
                // $('#pack1').val(Math.round(pack));
                $(row).find("td:eq('1')").find('input').val(Math.round(pack));
                // document.getElementById('pack1').value = pack;
                
                }else{
                var final = parseInt(pack)+ 1;
                // $('#pack1').val(final);
                $(row).find("td:eq('1')").find('input').val(final);
                }

            TotalAmount();
            TotalPack();
            TotalSaleQty();
            // TotalSaleTax();
        }

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
                    $("#party_id").select2('open');
                }
            });

            $('#party_id').change(function(event) {
                $('#party_id').select2().trigger('select2:close');
                $("#purchaser_id").select2('open');
            });
            $('#purchaser_id').change(function(event) {
                $('#purchaser_id').select2().trigger('select2:close');
                $("#credit_to").select2('open');
            });

            $('#credit_to').change(function(event) {
                $('#credit_to').select2().trigger('select2:close');
                $('#remarks').focus();
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
                    $("#qty1").focus();
                }
            });
            $('#qty1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#price1").select();
                }
            });
           
            $('#party_id').change(function() {
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
                    // $('#remarks').focus();
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
                            // $('#remarks').focus();
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
                var partyID = document.getElementById('party_id').value;
                //  alert (productID);
                //  alert (partyID);
                var VoucherDate = $('#date').val(); //1700
                $.ajax({
                    url: "{{ URL::to('direct-purchases/product/keyup') }}",
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
                            
                            $('#qty1').select();
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
                            $('#qty1').select();
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
                    var qty = parseFloat($(this).val());
                    if (qty <= 0) {
                        $(this).focus();
                        $('.qty_err').text('This field is required');
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
                    $('.qty_err').text('');
                    $('.price_err').text('');


                    var product_id = parseInt($('#product_id1').val());
                    var qty = parseFloat($('#qty1').val());
                    var price = parseFloat($('#price1').val());

                    if (!product_id) {
                        $('#product_id').select2('open');
                        $('.product_err').text('This field is required');
                        return false;
                    } else
                    if (!qty || qty <= 0) {
                        $('#qty1').focus();
                        $('.qty_err').text('This Qty field is required');
                        return false;
                    } else
                    if (!price || price <= 0) {
                        $('#price1').focus();
                        $('.price_err').text('This Price field is required');
                        return false;
                    } else {
                        AddGridData();
                        TotalSaleQty();
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
            var qty = parseFloat($('#qty1').val());
            if (!qty || qty <= 0) {
                $('.qty_err').text('This Quantity field is required');
                $('#qty1').focus();
                return false;
            } else {
                $('.qty_err').text('');
            }
            var price = parseFloat($('#price1').val());
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
            // var saleQty = parseInt(document.getElementById('sale_qty1').value);
            var Qty = parseFloat(document.getElementById('qty1').value);
            var price = parseFloat(document.getElementById('price1').value);
            var total = parseFloat(document.getElementById('total1').value);
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
            // tableHtml +=
            //     `<td>${ProductCode}<input type='hidden' name='code[]' id='code' value='${ProductCode}' /></td>`;
            tableHtml += `<td>
                <input type='hidden' name='product_id[]' id='product_id' value='${proID}' />
                <input type='text' value='${ProductCode} - ${ProductName}' class='form-control' readonly />
            </td>`;
            tableHtml +=
                `<td><input type='text' name='pack[]' id='pack' value='${pack}' class='form-control' readonly /></td>`;
            tableHtml +=
                `<td>${packing} <input type='hidden' value='${packing}' /></td>`;
            tableHtml +=
                `<td>${unit}<input type='hidden' name='unit[]' id='unit' value='${unit}' /></td>`;
            // tableHtml +=
            //     `<td><input type='text' value='${saleQty}' id="sale_qty" name="sale_qty[]" onkeyup="changeSaleQty1($(this).closest('tr'));" class='form-control'  onkeypress="return isNumberKey(event)"/></td>`;
            tableHtml +=
                `<td><input type='text' name='qty[]' id='qty' value='${Qty}' class='form-control' onkeyup="changerate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
            <span class="qty_errr text-danger"></span>
            </td>`;
            tableHtml +=`<td><input type='text' name='rate[]' id='rate' value='${price}' class='form-control' onkeyup="changerate($(this).closest('tr'));" onchange="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
            <span class="rate_errr text-danger"></span>
            </td>`;
            tableHtml +=
                `<td><input type='text'  value='${total}' name='total[]'  id='total'  class='form-control' readonly='readonly'/>
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
                var warehouse_id = $('#warehouse_id').val();
                // var dcn_id = $('#dcn_id').val();
                var party_id = $('#party_id').val();
                var credit_to = $('#credit_to').val();
                // alert(warehouse_id);
                // $('.voucher_no_err').text('');
                // $('.dcn_id_err').text('');
                // $('.party_id_err').text('');


                if(!warehouse_id)
                {
                    $('.warehouse_id_err').text('The Warehouse is required.');
                    

                    return false;
                }
                else
                if(!voucher_no)
                {
                    // alert(0);
                    $('.voucher_no_err').text('The voucher no field is required.');
                    return false;
                }
                else
                if(!party_id)
                {
                    // alert("3")
                    $('.party_id_err').text('The party field is required.');
                    return false;
                }
                else
                if(!credit_to)
                {
                    $('.credit_to_err').text('The Credit to is required.');
                    return false;
                }
                
                else{
                    // alert("4")
                    $('#sales-voucher-form').submit();
                    $('.submit-form').attr('disabled', true);
                }
                // alert(voucher_no)
            });
            // End FOrm Submit

            // Reset btn feature
            $('.reset-btn').click(function() {
                $('#party_id').val(null).select2();
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
            var TotalAmount = parseInt(document.getElementById('TotalAmount').innerText);
            var NewAmount = parseInt($(row).find("td:eq('5')").find('input').val());
            document.getElementById('TotalAmount').innerText = (TotalAmount - NewAmount);

            var TotalQty = parseInt(document.getElementById('TotalQty').innerText);
            var NewQty = parseInt($(row).find("td:eq('3')").find('input').val());
            document.getElementById('TotalQty').innerText = (TotalQty - NewQty);

            $(row).remove();
        }

        // function PriceKeyUp(price) {
        //     var quantity = document.getElementById('qty1').value;
        //     if (quantity == '') {
        //         document.getElementById('total1').value = price;
        //     } else {
        //         var total = quantity * price;
        //         document.getElementById('total1').value = total;
        //     }
        // }

        function QuantityKeyUp() {
            var quantity = document.getElementById('qty1').value;
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
                $('#delete_warehouse_id').val(warehouseID);
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
                    url: "{{ URL::to('direct-purchases/print/voucher') }}",
                        
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
                                `<object data="${base_url}/resources/upload/purchase/${response}" type="application/pdf" width="100%" height="800"></object>`
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




            $('.load-edit-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                $.ajax({
                    url: "{{ URL::to('direct-purchases/load/record') }}",
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
                          
                                $.each(response.data.sale_purchase_details, function(i, v) {
                                    tableHtml += `<tr>`;
                                    // tableHtml +=`<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                    tableHtml += `<td>
                                       <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                       <input type='text' value='${v.product.code} - ${v.product.product_name}' class='form-control' readonly />
                                   </td>`;
                                    tableHtml +=`<td><input type='text' name='pack[]' id='pack' value='${v.qty}' class='form-control' readonly /></td>`;
                                    tableHtml +=`<td>${parseInt(v.product.packing)} <input type='hidden'  value='${v.product.packing}' /></td>`;
                                    tableHtml +=`<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=`<td><input type='text' name='qty[]' id='qty' value='${parseFloat(v.sale_qty)}' class='form-control' onkeyup="changerate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                    tableHtml +=`<td><input type='text' name='rate[]' id='rate' value='${parseFloat(v.rate)}' class='form-control' onkeyup="changerate($(this).closest('tr'));" onchange="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                    tableHtml +=`<td><input type='text'  value='${parseFloat(v.total)}' name='total[]'  id='total'  class='form-control' readonly='readonly'/></td>`;
                                    tableHtml += `</tr>`;
                                        });
                                $('#GridTable').html(tableHtml);
                                $('#date').focus();
                                $('#update_voucher_id').val(response.data.id);
                                $('#date').val(response.data.date);
                                $('#voucher_no').val(response.data.voucher_no);
                                $('#party_id').val(response.data.party_id).select2();
                                $('#purchaser_id').val(response.data.purchaser_id).select2();
                                $('#credit_to').val(response.data.credit_to).select2();
                                $('#remarks').val(response.data.remarks);
                                $('#vehicle_no').val(response.data.vehicle_no);
                                $('#transport_company').val(response.data.transport_company);
                                $('#driver_name').val(response.data.driver_name);
                                $('#builty_no').val(response.data.builty_no);
                                $('#freight').val(response.data.freight);
                                $('#driver_phoneno').val(response.data.driver_phoneno);
                                TotalSaleQty();
                                TotalAmount();
                                TotalPack();
                                // TotalSale();
                                
                                // $('#updated_by_name').removeClass('d-none');
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
                    url: "{{ URL::to('direct-purchases/load/next/record') }}",
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
                          
                                $.each(response.data.sale_purchase_details, function(i, v) {
                                    tableHtml += `<tr>`;
                                    // tableHtml +=`<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                    tableHtml += `<td>
                                       <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                       <input type='text' value='${v.product.code} - ${v.product.product_name}' class='form-control' readonly />
                                   </td>`;
                                    tableHtml +=`<td><input type='text' name='pack[]' id='pack' value='${v.qty}' class='form-control' readonly /></td>`;
                                    tableHtml +=`<td>${parseInt(v.product.packing)} <input type='hidden'  value='${v.product.packing}' /></td>`;
                                    tableHtml +=`<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=`<td><input type='text' name='qty[]' id='qty' value='${parseFloat(v.sale_qty)}' class='form-control' onkeyup="changerate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                    tableHtml +=`<td><input type='text' name='rate[]' id='rate' value='${parseFloat(v.rate)}' class='form-control' onkeyup="changerate($(this).closest('tr'));" onchange="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                    tableHtml +=`<td><input type='text'  value='${parseFloat(v.total)}' name='total[]'  id='total'  class='form-control' readonly='readonly' /></td>`;
                                    tableHtml += `</tr>`;
                                        });
                                $('#GridTable').html(tableHtml);
                                $('#date').focus();
                                $('#update_voucher_id').val(response.data.id);
                                $('#date').val(response.data.date);
                                $('#voucher_no').val(response.data.voucher_no);
                                $('#party_id').val(response.data.party_id).select2();
                                $('#purchaser_id').val(response.data.purchaser_id).select2();
                                $('#credit_to').val(response.data.credit_to).select2();
                                $('#remarks').val(response.data.remarks);
                                $('#vehicle_no').val(response.data.vehicle_no);
                                $('#transport_company').val(response.data.transport_company);
                                $('#driver_name').val(response.data.driver_name);
                                $('#builty_no').val(response.data.builty_no);
                                $('#freight').val(response.data.freight);
                                $('#driver_phoneno').val(response.data.driver_phoneno);
                                TotalSaleQty();
                                TotalAmount();
                                TotalPack();
                                // TotalSale();
                                
                                // $('#updated_by_name').removeClass('d-none');
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
                    url: "{{ URL::to('direct-purchases/load/previous/record') }}",
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
                          
                                $.each(response.data.sale_purchase_details, function(i, v) {
                                    tableHtml += `<tr>`;
                                    // tableHtml +=`<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                    tableHtml += `<td>
                                       <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                       <input type='text' value='${v.product.code} - ${v.product.product_name}' class='form-control' readonly />
                                   </td>`;
                                    tableHtml +=`<td><input type='text' name='pack[]' id='pack' value='${v.qty}' class='form-control' readonly /></td>`;
                                    tableHtml +=`<td>${parseInt(v.product.packing)} <input type='hidden'  value='${v.product.packing}' /></td>`;
                                    tableHtml +=`<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=`<td><input type='text' name='qty[]' id='qty' value='${parseFloat(v.sale_qty)}' class='form-control' onkeyup="changerate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                    tableHtml +=`<td><input type='text' name='rate[]' id='rate' value='${parseFloat(v.rate)}' class='form-control' onkeyup="changerate($(this).closest('tr'));" onchange="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                    tableHtml +=`<td><input type='text' value='${parseFloat(v.total)}' name='total[]'  id='total'  class='form-control' readonly='readonly' /></td>`;
                                    tableHtml += `</tr>`;
                                        });
                                $('#GridTable').html(tableHtml);
                                $('#date').focus();
                                $('#update_voucher_id').val(response.data.id);
                                $('#date').val(response.data.date);
                                $('#voucher_no').val(response.data.voucher_no);
                                $('#party_id').val(response.data.party_id).select2();
                                $('#purchaser_id').val(response.data.purchaser_id).select2();
                                $('#credit_to').val(response.data.credit_to).select2();
                                $('#remarks').val(response.data.remarks);
                                $('#vehicle_no').val(response.data.vehicle_no);
                                $('#transport_company').val(response.data.transport_company);
                                $('#driver_name').val(response.data.driver_name);
                                $('#builty_no').val(response.data.builty_no);
                                $('#freight').val(response.data.freight);
                                $('#driver_phoneno').val(response.data.driver_phoneno);
                                TotalSaleQty();
                                TotalAmount();
                                TotalPack();
                                // TotalSale();
                                
                                // $('#updated_by_name').removeClass('d-none');
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
                    url: "{{ URL::to('direct-purchases/warehouse/voucherno') }}",
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
