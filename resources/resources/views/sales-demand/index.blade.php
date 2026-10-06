@extends('app')
@section('head')
    <title>Sales Demand</title>
    <!--  Select 2 library start-->
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!--  Select 2 library end-->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@stop
@section('content')
</body>
    <!-- Sticky Left Side bar -->
    <div class="icon-bar">
        <a href="javascript:void(0);" class="load-previous-record"><i class="fa fa-angle-left"></i></a>
        <a href="javascript:void(0);" class="load-edit-record"><i class="fa fa-repeat"></i></a>
        <a href="javascript:void(0);" class="load-next-record"><i class="fa fa-angle-right"></i></a>
        <a href="javascript:void(0);" class="delete_record_btn"><i class="fa fa-trash-o"></i></a>
        <a href="javascript:void(0);" class="print_record_btn"><i class="fa fa-print"></i></a>
        <!-- <a href="javascript:void(0);" class="print_customer_product_btn"><i class="fa fa-print"></i></a> -->
    </div>
    <!-- End Sticky Left Side bar -->
    <div class="content-wrapper" onload="myFunction()">

        <section class="content-header">
            <h1>
                Sales Demand
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Sales Demand</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Sales Demand</h6>
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
                                    {!! Form::open(['url' => 'sales-demand', 'class' => 'form-horizontal', 'id' => 'sales-demand-form']) !!}
                                    {!! Form::hidden('update_voucher_id', null, ['id' => 'update_voucher_id']) !!}
                                    {!! Form::hidden('type', 'SALE DEMAND', ['id' => 'type']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                
                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="date"><i class="fa fa-caret-right"></i> Voucher Date</label>
                                            {!! Form::date('voucher_date', date('Y-m-d'), [
                                                'id' => 'voucher_date',
                                                'class' => 'form-control',
                                                'tabindex' => '0',
                                                'required' => 'required',
                                                'autofocus' => 'autofocus',
                                            ]) !!}
                                            <input type="hidden" id="userrole" name="userrole" value="{{Auth::User()->role}}">
                                            @error('voucher_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                         
                                        <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                                <label for="party_name"><i class="fa fa-caret-right"></i>Warehouse<span
                                                        class="text-danger">*</span></label>
                                                {!! Form::select('warehouse_id', $warehouse, null, [
                                                    'id' => 'warehouse_id',
                                                    'class' => 'form-control select2 customer',
                                                    'tabindex' => '3',

                                                    'required' => 'required',
                                                ]) !!}
  
                                            </div>
                                        @if(Auth::User()->role == "Admin")
                                        <div class="col-lg-1 col-md-12 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>Vr#<span
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
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>Vr#<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $codes, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                                'tabindex' => '1',
                                            ]) !!}
                                        </div>
                                        @endif

 
                                            <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                                <label for="party_name"><i class="fa fa-caret-right"></i> Party Name<span
                                                        class="text-danger">*</span></label>
                                                {!! Form::select('party_name', $customers, null, [
                                                    'id' => 'party_name',
                                                    'class' => 'form-control select2',
                                                    'tabindex' => '2',
                                                    'required' => 'required',
                                                ]) !!}
                                                {!! Form::hidden('party_id', null, ['id' => 'party_id']) !!}
                                                <span class="text-danger party_name_err"></span>
                                                @error('party_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                                <label for="address"><i class="fa fa-caret-right"></i> Address<span
                                                        class="text-danger">*</span></label>
                                                {!! Form::text('address', null, [
                                                    'id' => 'address',
                                                    'class' => 'form-control',
                                                    'tabindex' => '3',
                                                    'disabled' => 'disabled',
                                                    'placeholder' => 'Address',
                                                ]) !!}
                                            </div>

                                        <div class="col-lg-2 col-md-8 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i> Remarks<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('remarks', null, [
                                                'id' => 'remarks',
                                                'class' => 'form-control',
                                                'tabindex' => '5',
                                                'placeholder' => 'Remarks',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="payment_mode"><i class="fa fa-caret-right"></i> Payment Mode<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('payment_mode', $payment_mode, null, [
                                                'id' => 'payment_mode',
                                                'class' => 'form-control select2',
                                                'tabindex' => '6',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('payment_mode')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger payment_mode_err"></span>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 mt-1">
                                            <label for="credit_days"><i class="fa fa-caret-right"></i> Credit Days</label>
                                            {!! Form::text('credit_days', null, [
                                                'id' => 'credit_days',
                                                'class' => 'form-control',
                                                'tabindex' => '7',
                                            ]) !!}
                                            {{-- @error('credit_days')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger credit_days_err"></span> --}}
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 mt-1">
                                            <label for="po_date"><i class="fa fa-caret-right"></i> P.O DATE<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::date('po_date', date('Y-m-d'), [
                                                'id' => 'po_date',
                                                'class' => 'form-control',
                                                'tabindex' => '8',
                                            ]) !!}
                                            @error('po_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger po_date_err"></span>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 mt-1">
                                            <label for="po_no"><i class="fa fa-caret-right"></i> P.O.NO</label>
                                            {!! Form::text('po_no', $codes, [
                                                'id' => 'po_no',
                                                'class' => 'form-control',
                                                'tabindex' => '9',
                                            ]) !!}
                                            @error('po_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger po_no_err"></span>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1">
                                            <label for="shipment_term"><i class="fa fa-caret-right"></i> Shipment
                                                Terms<span class="text-danger">*</span></label>
                                            {!! Form::select('shipment_term', $shipment_term, null, [
                                                'id' => 'shipment_term',
                                                'class' => 'form-control select2',
                                                'tabindex' => '10',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('shipment_term')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger shipment_term_err"></span>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1 d-none">
                                            <label for="previous_ledger"><i class="fa fa-caret-right"></i> PREVIOUS
                                                LEDGER</label>
                                            {!! Form::text('previous_ledger', null, [
                                                'id' => 'previous_ledger',
                                                'class' => 'form-control',
                                                'tabindex' => '11',
                                                'disabled' => 'disabled',
                                            ]) !!}

                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1 d-none">
                                            <label for="total_due_sale_order"><i class="fa fa-caret-right"></i> TOTAL DUE
                                                WITH S.ORDER</label>
                                            {!! Form::text('total_due_sale_order', null, [
                                                'id' => 'total_due_sale_order',
                                                'class' => 'form-control',
                                                'tabindex' => '12',
                                                'disabled' => 'disabled',
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
                                                            <th style="width: 30%;">Product Name</th>
                                                            <th>Unit</th>
                                                            <th>Pack.Qty</th>
                                                            <th>Packing</th>
                                                            <th>Order Qty</th>
                                                            <th style="display:none;">Rate</th>
                                                            <th>Delivery Date</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="bg-secondary">
                                                            <td class="d-none">
                                                                {!! Form::text('code1', null, [
                                                                    'id' => 'code1',
                                                                    'class' => 'form-control bg-white',
                                                                    'disabled' => 'disabled',
                                                                    'tabindex' => '13',
                                                                ]) !!}
                                                                {!! Form::hidden('code2', null, ['id' => 'code2']) !!}
                                                            </td>
                                                            <td>
                                                                <!-- {!! Form::select('product_id1', ['' => 'Select Product'], null, [
                                                                    'id' => 'product_id1',
                                                                    'class' => 'form-control select2',
                                                                    'tabindex' => '14',
                                                                ]) !!} -->

                                                                {!! Form::select('product_id1', $products, null, [
                                                                    'id' => 'product_id1',
                                                                    'class' => 'form-control select2',
                                                                    'tabindex' => '14',
                                                                ]) !!}
                                                                <span class="product_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('unit1', null, [
                                                                    'id' => 'unit1',
                                                                    'class' => 'form-control',
                                                                    'disabled' => 'disabled',
                                                                    'placeholder' => 'Unit',
                                                                    'tabindex' => '15',
                                                                ]) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('qty1', null, [
                                                                    'id' => 'qty1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Qty',
                                                                    'tabindex' => '16',
                                                                    'onkeyup' => 'QuantityKeyUp($(this).val())',
                                                                    'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                                ]) !!}
                                                                <span class="qty_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('packing1', null, [
                                                                    'id' => 'packing1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Packing',
                                                                    'disabled' => 'disabled',
                                                                    'tabindex' => '17',
                                                                ]) !!}
                                                                <span class="packing_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('order_qty1', null, [
                                                                    'id' => 'order_qty1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Order Quantity',
                                                                    // 'disabled' => 'disabled',
                                                                    'onkeypress'=>"return isNumberKey(event)",
                                                                    'tabindex' => '18',
                                                                ]) !!}
                                                                <span class="order_qty_err text-danger"></span>
                                                            </td>
                                                            <td style="display:none;">
                                                                {!! Form::text('price1', 0, [
                                                                    'id' => 'price1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Rate',
                                                                    'tabindex' => '19',
                                                                    'onkeyup' => 'PriceKeyUp($(this).val())'
                                                                ]) !!}
                                                                <span class="price_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::date('delivery_date1', date('Y-m-d'), [
                                                                    'id' => 'delivery_date1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Delivery Date',
                                                                    'tabindex' => '24',
                                                                ]) !!}
                                                                <span class="delivery_date_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('remark1', null, [
                                                                    'id' => 'remark1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Remarks',
                                                                    'tabindex' => '29',
                                                                ]) !!}
                                                                <span class="remark1_err text-danger"></span>
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
                                                                <th style="width: 30%;">Product Name</th>
                                                                <th>Unit</th>
                                                                <th>Pack.Qty</th>
                                                                <th>Packing</th>
                                                                <th>Order Qty</th>
                                                                <th style="display: none;">Rate</th>
                                                                <th>Delivery Date</th>
                                                                <th>Remarks</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3"><strong>Total</strong></td>
                                                                <td class="bg-primary" id="TotalQty">0</td>
                                                                <td></td>
                                                                <td class="bg-success" id="TotalOrderQty">0</td>
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
                                            <!-- <div class="note note-warning">Posted By : {{ Auth::User()->name }}</div> -->
                                        </div>
                                        <div class="col-lg-2 col-md-12 col-12 ">
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
                <form action="{{ URL::to('sales-demand/delete-voucher') }}" method="post" id="delete_voucher_form">
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {!! Toastr::message() !!}
</body>
@stop
@section('scripts')
    <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2, .select2-multiple").select2({
            width: "100%"
        });
    </script>
    <script>
        
        $(document).ready(function() {
            $('#voucher_date').keydown(function(event) {
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

                $('#warehouse_id').change(function(event) {
                    var warehouseID = $(this).val();
                    if (warehouseID) {
                        $('#warehouse_id').select2().trigger('select2:close');
                        $("#voucher_no").select();
                        $.ajax({
                    url: "{{ URL::to('sales-demand/warehouse/voucherno') }}",
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
                // alert(party_name)
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
                    $("#payment_mode").select2('open');
                }
            });
            $('#payment_mode').change(function(event) {
                var payment_mode = $(this).val();
                if (payment_mode) {
                    $('#payment_mode').select2().trigger('select2:close');
                    if(payment_mode=='Cash')
                    {
                        $("#credit_days").attr('disabled',true);
                        $("#po_date").focus();
                    }else{
                        $("#credit_days").removeAttr('disabled');
                        $("#credit_days").focus();
                    }
                }
            });
            $('#credit_days').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#po_date").focus();
                }
            });
            $('#po_date').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#po_no").focus();
                }
            });
            $('#po_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#shipment_term").select2('open');
                }
            });
            $('#shipment_term').change(function(event) {
                var shipment_term = $(this).val();
                if (shipment_term) {
                    $('#shipment_term').select2().trigger('select2:close');
                    $("#product_id1").select2('open');
                }
            });

            $('#product_id1').change(function(event) {
                // var productID = $(this).val();
                // var VoucherDate = $('#voucher_date').val(); //1700

                var  productID = document.getElementById('product_id1').value.split('_')[0];
                var  partyID = document.getElementById('party_name').value;
                //  alert (productID);
                //  alert (partyID);
                var VoucherDate = $('#voucher_date').val(); //1700

                $.ajax({
                    url: "{{ URL::to('sales-demand/product/keyup') }}",
                    type: 'get',
                    data: {
                        productID: productID, VoucherDate:VoucherDate, partyID:partyID
                    },
                    dataType: 'json',
                    success: function(response) {
                    //  $('#product_id1').select2().trigger('select2:close');
                    // $('#unit1').val(response.uom); //pcs
                    // $('#packing1').val(response.packing); //8300
                    // $('#s_tax1').val(response.tax); //1700
                    //     if((response.product_rates) == ""){
                    //         $('#price1').val(response.product_price); //1700 
                    // }else{
                    //     $('#price1').val(response.product_rates[0].new_rate); //1700     
                    // }
                    // if(response.uom == "ROLL"){
                    //     $('#qty1').removeAttr("readonly");
                    //     $('#qty1').focus();
                    // }else{
                    //     $('#qty1').prop("readonly", true);
                    // $('#order_qty1').focus();
                    // }
                    if (response.company == false) {
                        // Customer Product
                        // if (response.products.length > 0) {
                            $('#product_id1').select2().trigger('select2:close');
                            $('#code1').val(response.products[0].code); //pcs
                            $('#productName').val(response.products[0].product_name); //pcs-
                            $('#unit1').val(response.products[0].uom); //pcs
                            $('#packing1').val(response.products[0].packing); //8300
                            if(response.products[0].product_rates[0]){
                                // alert("ddd")
                                // $('#s_tax1').val(response.products[0].product.product_rates[0].tax_rate);
                                $('#price1').val(response.products[0].product_rates[0].new_rate);
                            }else{
                                // $('#s_tax1').val(0);
                                $('#price1').val(0);
                            }
                            
                            $('#order_qty1').focus();
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
                                // $('#s_tax1').val(response.products.product_rates[0].tax_rate);
                                $('#price1').val(response.products.product_rates[0].new_rate); //1700
                            }else{
                                // $('#s_tax1').val(0);
                                $('#price1').val(0);
                            }
                            $('#order_qty1').focus();
                        // }
                        }
                    
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
                        // $('#price1').focus();
                        $('#order_qty1').select();
                    }
                }
            });

            // $('#order_qty1').keydown(function(event) {
            //     var keycode = (event.keyCode ? event.keyCode : event.which);
            //     if (keycode == '13') {
            //         var order_qty = parseInt($(this).val());
            //         if (order_qty <= 0) {
            //             $(this).focus();
            //             $('.order_qty_err').text('This field is required & Must be greater than zero');
            //         } else {
            //             $('.order_qty_err').text('');
            //             var packing = parseInt($('#packing1').val());
            //             var qty = order_qty/packing;
            //             $('#qty1').val(Math.round(qty));
            //             $('#delivery_date1').focus();


            //             // $('#remark1').val(parseInt(qty)+'X'+packing+',1x'+((Math.round(qty)*packing)-(order_qty)));
            //             // $('#remark1').val(parseInt(qty)+'X'+packing+',1x'+
            //             //     (Math.abs((order_qty) - Math.round(qty)*packing))
            //             // );

            //             $('#remark1').val(parseInt(qty)+'X'+packing+',1x'+
            //            (Math.abs((parseInt(qty)*packing)-(order_qty))));
            //         }
            //     }
            // });
            $('#order_qty1').keydown(function(event) {
                // alert("dfds")
                var keycode = (event.keyCode ? event.keyCode : event.which);
                var qty1 = $('#qty1').val();
                var order_qty = $(this).val();
                var unit = $('#unit1').val();
                
                if(unit == "ROLL"){
                    if (keycode == '13') {
                        // alert(unit)
                        $('#remark1').val(qty1+'X'+order_qty);
                        $('#delivery_date1').focus();
                    }
                    }else{
                        if (keycode == '13') {
                  
                  if (order_qty <= 0) {
                      $(this).focus();
                      $('.order_qty_err').text('This field is required & Must be greater than zero');
                  } 
                  else {
                      $('.order_qty_err').text('');
                      var packing = parseInt($('#packing1').val());
                      var qty = order_qty/packing;
                      // $('#qty1').val(Math.round(qty));
                     $('#delivery_date1').focus();

                     var totalS = ((Math.abs((parseInt(qty)*packing)-(order_qty))));
                  //    alert(totalS)
                     if(Math.abs((parseInt(qty)*packing)-(order_qty)) == '0') {
                      // alert("dd")
                       $('#remark1').val(parseInt(qty)+'X'+packing);
                     }
                     else{
                       $('#remark1').val(parseInt(qty)+'X'+packing+',1x'+
                      (Math.abs((parseInt(qty)*packing)-(order_qty))));
                     }
                  //   $('#remark1').val(parseInt(qty)+'X'+packing+',1x'+
                  //    (Math.abs((parseInt(qty)*packing)-(order_qty))));
                  //    var total = (parseInt(qty)+'X'+packing+',1x'+
                  //    (Math.abs((parseInt(qty)*packing)-(order_qty))));

                     if((Math.abs((parseInt(qty)*packing)-(order_qty))) == 0){
                      $('#qty1').val(Math.round(qty));
                     }else{
                     var final = parseInt(qty)+ 1;
                     $('#qty1').val(final);
                     }
                  }
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
                    var price = $('#price1').val();

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
                    if (price == '') {
                        $('#price1').focus();
                        $('.price_err').text('This field is required');
                        return false;
                    } else {
                        $('#delivery_date1').focus();

                    }

                }
            });
            $('#delivery_date1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#remark1').focus();
                }

            });
            $('#remark1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    AddGridData()
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

            var price = $('#price1').val();
            if (price == '') {
                $('.price1_err').text('This field is required');
                $('#price1').focus();
                return false;
            } else {
                $('.price1_err').text('');
            }

            var pro_id = document.getElementById('product_id1').value.split('_')[0];
            // var pro_name1 = document.getElementById('product_id1').value.split('_')[1];
            // var pro_name2 = document.getElementById('product_id1').value.split('_')[2];
            // var pro_name3 = document.getElementById('product_id1').value.split('_')[3];
            var pro_name = document.getElementById('product_id1').value.split('_')[4];
            var pro_code = document.getElementById('product_id1').value.split('_')[5];
            // alert(pro_name1)
            // alert(pro_name2)
            // alert(pro_name3)
            // alert(pro_name)
           
            var pro_unit = document.getElementById('product_id1').value.split('_')[1];
            var qty = parseFloat(document.getElementById('qty1').value);
            var packing = document.getElementById('packing1').value;
            var orderQty = parseFloat(document.getElementById('order_qty1').value);
            var price = parseFloat(document.getElementById('price1').value);
            var deliveryDate = document.getElementById('delivery_date1').value;
            var remarks = document.getElementById('remark1').value;
           
            var TotalQty = parseFloat(document.getElementById('TotalQty').innerHTML);
            var TotalOrderQty = parseFloat(document.getElementById('TotalOrderQty').innerHTML);
            
            var grandOrderQty = TotalOrderQty + orderQty;
            var grandTotalQty = TotalQty + qty;
            

            var tableHtml = `<tr>`;
            tableHtml += `<td>${pro_code}</td>`;
            tableHtml += `<td>
                            ${pro_name}
                            <input type='hidden' name='product_id[]' id='product_id' value='${pro_id}' />
                        </td>`;
            tableHtml += `<td>${pro_unit}<input type='hidden' name='unit[]' id='unit' value='${pro_unit}' /></td>`;
            tableHtml +=
                `<td>${qty}<input type='hidden' name='qty[]' id='qty' value='${qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
            tableHtml +=
                `<td>${packing}<input type='hidden' name='packing[]' id='packing' value='${packing}' class='form-control' /></td>`;
            tableHtml +=
                `<td>${orderQty.toFixed(2)}<input type='hidden' name='order_qty[]' id='order_qty' value='${orderQty.toFixed(2)}' class='form-control' onkeyup="changerate($(this).closest('tr'));" /></td>`;
            tableHtml +=
                `<td style="display:none;">${price}<input type='hidden' name='price[]' id='price' value='${price}' class='form-control' /></td>`;
            tableHtml +=
                `<td>${deliveryDate}<input type='hidden' name='delivery_date[]' id='delivery_date' value='${deliveryDate}' class='form-control' /></td>`;
            tableHtml +=
                `<td>${remarks}<input type='hidden' name='remark[]' id='remark' value='${remarks}' class='form-control' /></td>`;
            tableHtml +=
                `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
            tableHtml += `</tr>`;

            $('#GridTable').append(tableHtml);
            $('#code1').val(null);

            $('#unit1').val(null);
            $('#price1').val(0);
            $('#packing1').val(null);
            $('#order_qty1').val(null);
            $('#qty1').val(null);
            $('#remark1').val(null);

            $('#TotalQty').html(grandTotalQty.toFixed(2));
            $('#TotalOrderQty').html(grandOrderQty.toFixed(2));
            $('#product_id1').select2('open');
            $('#product_id1').val(null);
        }

        function TotalPackQTY() {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                if (tableData.rows[i].cells[3].getElementsByTagName('input')[0].value == '') {
                    sum += 0;
                } else {
                    sum += parseFloat(tableData.rows[i].cells[3].getElementsByTagName('input')[0].value);
                }   
            }
            document.getElementById('TotalQty').innerText = sum.toLocaleString('en-US');
        }

        function TotalOrderQTY() {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                if (tableData.rows[i].cells[5].getElementsByTagName('input')[0].value == '') {
                    sum += 0;
                } else {
                    sum += parseFloat(tableData.rows[i].cells[5].getElementsByTagName('input')[0].value);
                }   
            }
            document.getElementById('TotalOrderQty').innerText = sum.toLocaleString('en-US');
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
                var party_name = $('#party_name').val();
                var payment_mode = $('#payment_mode').val();
                var credit_days = $('#credit_days').val();
                var po_no = $('#po_no').val();
                var shipment_term = $('#shipment_term').val();


                $('.voucher_no_err').text('');
                $('.party_name_err').text('');
                $('.payment_mode_err').text('');
                $('.credit_days_err').text('');
                $('.po_no_err').text('');
                $('.shipment_term_err').text('');

                if(!voucher_no)
                {
                    $('.voucher_no_err').text('The voucher no field is required.');
                    return false;
                }else
                if(!party_name)
                {
                    $('.party_name_err').text('The party field is required.');
                    return false;
                }else
                if(!payment_mode)
                {
                    $('.payment_mode_err').text('The payment mode field is required.');
                    return false;
                }
                // else
                // if(!credit_days)
                // {
                //     $('.credit_days_err').text('The credit days field is required.');
                //     return false;
                // }else
                // if(!credit_days)
                // {
                //     $('.po_no_err').text('The po no field is required.');
                //     return false;
                // }
                else
                if(!po_no)
                {
                    $('.po_no_err').text('The PO No field is required.');
                    return false;
                }
                else
                if(!shipment_term)
                {
                    $('.shipment_term_err').text('The shippment field is required.');
                    return false;
                }
                
                else{
                    $('#sales-demand-form').submit();
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

        function DeleteRow(row) {
            var TotalOrderQty = parseInt(document.getElementById('TotalOrderQty').innerText);
            var NewOrderQty = parseInt($(row).find("td:eq('4')").find('input').val());
            document.getElementById('TotalOrderQty').innerText = (TotalOrderQty - NewOrderQty);

            var TotalQty = parseInt(document.getElementById('TotalQty').innerText);
            var NewQty = parseInt($(row).find("td:eq('2')").find('input').val());
            document.getElementById('TotalQty').innerText = (TotalQty - NewQty);

            $(row).remove();
        }

        function PriceKeyUp(price) {
            //St.Value Calculation
            var sTax = document.getElementById('s_tax1').value;
            var orderQty = document.getElementById('order_qty1').value;
            var stValue = (price * sTax / 100) * orderQty;
            document.getElementById('st_value1').value = parseFloat(stValue.toFixed(2));

            //ExclValue Calculation
            var exclValue = price * orderQty;
            document.getElementById('excl_value1').value = exclValue;

            //Incl Value Calculation
            var totalAmount = stValue + exclValue;
            document.getElementById('total1').value = totalAmount;

        }

        function QuantityKeyUp(quantity) {
            //OrderQty Calculation
            var packing = document.getElementById('packing1').value;
            var qty = document.getElementById('qty1').value;
            var orderQty = qty * packing;
            document.getElementById('order_qty1').value = orderQty;

            //St.Value Calculation
            var price = document.getElementById('price1').value;
            var sTax = document.getElementById('s_tax1').value;
            var orderQtyy = document.getElementById('order_qty1').value;
            var stValue = (price * sTax / 100) * orderQtyy;
            document.getElementById('st_value1').value = parseFloat(stValue.toFixed(2));

            //ExclValue Calculation
            var exclValue = price * orderQty;

            document.getElementById('excl_value1').value = exclValue;

            //Incl Value Calculation
            var totalAmount = stValue + exclValue;
            document.getElementById('total1').value = totalAmount;


            // var total = quantity * price;
            // document.getElementById('total1').value = total;
        }
    </script>
    <!-- End OnChange Qty -->

    <script>
        // Change Customer Name

        $(document).ready(function() {
            var cat_id = $('#party_name').val();
            //   alert("ddd")
            $.ajax({
                url: "{{ asset('sales-demand/getcustomer/product') }}",
                type: 'get',
                data: {
                    cat_id: cat_id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.company == false) {
                        if (response.products.length > 0) {
                            $('#unit1').val(response.products[0].product.uom);
                            $('#packing1').val(parseFloat(response.products[0].product.packing));
                            $('#s_tax1').val(parseFloat(response.products[0].product.tax));

                            var option = '';
                            
                            $.each(response.products, function(i, v) {
                                option +=
                                    `<option value="${v.id}_${v.product.uom}_${v.product.packing}_${v.product.tax}_${v.product_name}_${v.code}">${v.code - v.product_name}</option>`;

                            });
                            $('#product_id1').html(option);

                        }
                    }
                    //     else {
                    //     var option = '<option value="" selected>Product Not Found</option>';

                    //     $('#product_id1').html(option);
                    // }
                }
            });
        });

        $('#party_name').change(function() {
            var cat_id = $(this).val();
            // alert(cat_id)
            $.ajax({
                url: "{{ asset('sales-demand/getcustomer/product') }}",
                
                type: 'get',
                data: {
                    cat_id: cat_id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.company == false) {
                        // alert("1")
                        if (response.products.length > 0) {
                            $('#unit1').val(response.products[0].product.uom);
                            $('#packing1').val(parseFloat(response.products[0].product.packing));
                            $('#s_tax1').val(parseFloat(response.products[0].product.tax));

                            
                            var option = `<option value="" selected>Select Customer Product</option>`;
                            $.each(response.products, function(i, v) {
                                option +=
                                    // `<option value="${v.id}_${v.product.uom}_${v.product.packing}_${v.product.tax}_${v.product_name}">${v.product_name}</option>`;
                                    `<option value="${v.product.id}_${v.product.uom}_${v.product.packing}_${v.product.tax}_${v.product_name}_${v.product_code}">${v.product_code} - ${v.product_name}</option>`;
                                    // `<option value="${v.id}_${v.uom}_${v.packing}_${v.tax}_${v.product_name}_${v.code}">${v.product_name}</option>`;
                                    // `<option value="${v.id}">${v.product_name}</option>`;

                            });
                            $('#product_id1').html(option);
                        }
                    } else {
                        // alert("11")
                        if (response.products.length > 0) {
                            $('#unit1').val('');
                            $('#packing1').val('');
                            $('#s_tax1').val();
                            var option = `<option value="" selected>Select Product</option>`;
                            $.each(response.products, function(i, v) {
                                option +=
                                    // `<option value="${v.id}_${v.uom}_${v.packing}_${v.tax}_${v.product_name}">${v.product_name}</option>`;
                                    `<option value="${v.id}_${v.uom}_${v.packing}_${v.tax}_${v.product_name}_${v.code}">${v.code} - ${v.product_name}</option>`;
                                    // `<option value="${v.id}_${v.id}_${v.id}_${v.id}_${v.product_name}_${v.product_code}">${v.product_name}</option>`;
                                    // `<option value="${v.id}">${v.product_name}</option>`;

                            });
                            $('#product_id1').html(option);

                        }
                    }
                }
            });
        });
    </script>
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
                var base_url = $('#base_url').val();
                $.ajax({
                    url: "{{ URL::to('sales-demand/print/voucher') }}",
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
                                `<object data="${base_url}/resources/upload/sales-demand/${response}" type="application/pdf" width="100%" height="800"></object>`
                            );
                        } else {
                            $('#print-receipt-modal-body').html(
                                '<h2 style="color:red;text-align:center;">Voucher Not Exist</h2>'
                            );
                            // alert('Voucher Not Exist');
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
                // alert(warehouseID);
                $.ajax({
                    url: "{{ URL::to('sales-demand/load/record') }}",
                    data: {voucher_no:voucher_no, warehouseID:warehouseID},
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            var tableHtml = '';
                                
                                $.each(response.data, function(i, v) {
                                    tableHtml += `<tr>`;
                                    if (response.status == 0) {
                                        var option = `<option value="" selected>Select Customer Product</option>`;
                                        $.each(response.products, function(i, v) {
                                        option +=`<option value="${v.id}_${v.product.uom}_${v.product.packing}_${v.product.tax}_${v.product_name}_${v.product_code}">${v.product_code} - ${v.product_name}</option>`;
                                        });
                                        $('#product_id1').html(option);
                                    tableHtml +=
                                        `<td>${v.product.customer_product.product_code}</td>`;
                                    tableHtml +=
                                        `<td>${v.product.customer_product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                    } else if (response.status == 1) {
                                    tableHtml +=
                                        `<td>${v.product.code}</td>`;
                                    tableHtml +=
                                        `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                    }
                                    tableHtml +=
                                        `<td>${v.product.uom}</td></td>`;
                                    tableHtml +=
                                        `<td>${parseFloat(v.qty)}<input type='hidden' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                    tableHtml +=
                                        `<td>${parseFloat(v.packing)}<input type='hidden' name='packing[]' id='price' value='${parseInt(v.packing)}' class='form-control' /></td>`;
                                    tableHtml +=
                                        `<td>${parseFloat(v.order_qty)}<input type='hidden' name='order_qty[]' id='order_qty' value='${parseInt(v.order_qty)}' class='form-control' /></td>`;
                                    tableHtml +=
                                        `<td style="display:none;">${parseFloat(v.sale_rate)}<input type='hidden' name='price[]' id='price' value='${parseFloat(v.sale_rate)}' class='form-control' /></td>`;
                                        var date = new Date(v.delivery_date);
                                    tableHtml +=
                                        `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}<input type='hidden' name='delivery_date[]' id='delivery_date' value='${v.delivery_date}' class='form-control' /></td>`;
                                    tableHtml +=
                                        `<td>${v.remark}<input type='hidden' name='remark[]' id='remark' value='${v.remark}' class='form-control' /></td>`;
                                    tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;
                                });
                            $('#GridTable').html(tableHtml);
                            TotalPackQTY();
                            TotalOrderQTY();
                            $('#updated_by_name').removeClass('d-none');
                            $('#voucher_no').val(response.data[0].sale_order.voucher_no);
                            $('#update_voucher_id').val(response.data[0].sale_order.id);
                            $('#voucher_date').val(response.data[0].sale_order.voucher_date);
                            $('#voucher_no').val(response.data[0].sale_order.voucher_no);
                            $('#voucher_no').focus();
                            $('#party_name').val(response.data[0].party_id)
                                .select2();
                            $('#party_id').val(response.data[0].sale_order.party_id);
                            $('#address').val(response.data[0].sale_order.party.address);
                            $('#remarks').val(response.data[0].sale_order.remarks);
                            $('#credit_days').val(response.data[0].sale_order.credit_days);
                            $('#payment_mode').val(response.data[0].sale_order.payment_mode)
                                .select2();
                            $('#po_date').val(response.data[0].sale_order.po_date);
                            $('#po_no').val(response.data[0].sale_order.po_no);
                            $('#shipment_term').val(response.data[0].sale_order.shipment_term)
                                .select2();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_voucher_id').val(null);
                            $('#voucher_no').focus();
                            $('#party_name').val(null).select2();
                            $('#party_id').val(null);
                            $('#address').val(null);
                            $('#remarks').val(null);
                            $('#credit_days').val(null);
                            $('#payment_mode').val(null);
                            $('#po_date').val(null);
                            $('#po_no').val(null);
                            $('#shipment_term').val(null);
                        }
                    }
                });
            });


            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                $.ajax({
                    url: "{{ URL::to('sales-demand/load/next/record') }}",
                    data: {warehouseID:warehouseID, voucher_no:voucher_no},
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            var tableHtml = '';
                                
                                $.each(response.data, function(i, v) {
                                    tableHtml += `<tr>`;
                                    if (response.status == 0) {
                                        var option = `<option value="" selected>Select Customer Product</option>`;
                                        $.each(response.products, function(i, v) {
                                        option +=`<option value="${v.id}_${v.product.uom}_${v.product.packing}_${v.product.tax}_${v.product_name}_${v.product_code}">${v.product_code} - ${v.product_name}</option>`;
                                        });
                                        $('#product_id1').html(option);
                                    tableHtml +=`<td>${v.product.customer_product.product_code}</td>`;
                                    tableHtml +=`<td>${v.product.customer_product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                    } else if (response.status == 1) {
                                    tableHtml +=
                                        `<td>${v.product.code}</td>`;
                                    tableHtml +=
                                        `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                    }
                                    tableHtml +=
                                        `<td>${v.product.uom}</td></td>`;
                                    tableHtml +=
                                        `<td>${parseFloat(v.qty)}<input type='hidden' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                    tableHtml +=
                                        `<td>${parseFloat(v.packing)}<input type='hidden' name='packing[]' id='price' value='${parseInt(v.packing)}' class='form-control' /></td>`;
                                    tableHtml +=
                                        `<td>${parseFloat(v.order_qty)}<input type='hidden' name='order_qty[]' id='order_qty' value='${parseInt(v.order_qty)}' class='form-control' /></td>`;
                                    tableHtml +=
                                        `<td style="display:none;">${parseFloat(v.sale_rate)}<input type='hidden' name='price[]' id='price' value='${parseFloat(v.sale_rate)}' class='form-control' /></td>`;
                                        var date = new Date(v.delivery_date);
                                    tableHtml +=
                                        `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}<input type='hidden' name='delivery_date[]' id='delivery_date' value='${v.delivery_date}' class='form-control' /></td>`;
                                    tableHtml +=
                                        `<td>${v.remark}<input type='hidden' name='remark[]' id='remark' value='${v.remark}' class='form-control' /></td>`;
                                    tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;
                                });
                            $('#GridTable').html(tableHtml);
                            TotalPackQTY();
                            TotalOrderQTY();
                            $('#updated_by_name').removeClass('d-none');
                            $('#voucher_no').val(response.data[0].sale_order.voucher_no);
                            $('#update_voucher_id').val(response.data[0].sale_order.id);
                            $('#voucher_date').val(response.data[0].sale_order.voucher_date);
                            $('#voucher_no').val(response.data[0].sale_order.voucher_no);
                            $('#voucher_no').focus();
                            $('#party_name').val(response.data[0].party_id)
                                .select2();
                            $('#party_id').val(response.data[0].sale_order.party_id);
                            $('#address').val(response.data[0].sale_order.party.address);
                            $('#remarks').val(response.data[0].sale_order.remarks);
                            $('#credit_days').val(response.data[0].sale_order.credit_days);
                            $('#payment_mode').val(response.data[0].sale_order.payment_mode)
                                .select2();
                            $('#po_date').val(response.data[0].sale_order.po_date);
                            $('#po_no').val(response.data[0].sale_order.po_no);
                            $('#shipment_term').val(response.data[0].sale_order.shipment_term)
                                .select2();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_voucher_id').val(null);
                            $('#voucher_no').focus();
                            $('#party_name').val(null).select2();
                            $('#party_id').val(null);
                            $('#address').val(null);
                            $('#remarks').val(null);
                            $('#credit_days').val(null);
                            $('#payment_mode').val(null);
                            $('#po_date').val(null);
                            $('#po_no').val(null);
                            $('#shipment_term').val(null);
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
                    url: "{{ URL::to('sales-demand/load/previous/record') }}",
                    data: {voucher_no:voucher_no, warehouseID:warehouseID},
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            var tableHtml = '';
                               
                                $.each(response.data, function(i, v) {
                                    tableHtml += `<tr>`;
                                    if (response.status == 0) {
                                        var option = `<option value="" selected>Select Customer Product</option>`;
                                        $.each(response.products, function(i, v) {
                                        option +=`<option value="${v.id}_${v.product.uom}_${v.product.packing}_${v.product.tax}_${v.product_name}_${v.product_code}">${v.product_code} - ${v.product_name}</option>`;
                                        });
                                        $('#product_id1').html(option);
                                    tableHtml +=
                                        `<td>${v.product.customer_product.product_code}</td>`;
                                    tableHtml +=
                                        `<td>${v.product.customer_product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                    } else if (response.status == 1) {
                                    tableHtml +=
                                        `<td>${v.product.code}</td>`;
                                    tableHtml +=
                                        `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                    }
                                    tableHtml +=
                                        `<td>${v.product.uom}</td></td>`;
                                    tableHtml +=
                                        `<td>${parseFloat(v.qty)}<input type='hidden' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                    tableHtml +=
                                        `<td>${parseFloat(v.packing)}<input type='hidden' name='packing[]' id='price' value='${parseInt(v.packing)}' class='form-control' /></td>`;
                                    tableHtml +=
                                        `<td>${parseFloat(v.order_qty)}<input type='hidden' name='order_qty[]' id='order_qty' value='${parseInt(v.order_qty)}' class='form-control' /></td>`;
                                    tableHtml +=
                                        `<td style="display:none;">${parseFloat(v.sale_rate)}<input type='hidden' name='price[]' id='price' value='${parseFloat(v.sale_rate)}' class='form-control' /></td>`;
                                        var date = new Date(v.delivery_date);
                                    tableHtml +=
                                        `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}<input type='hidden' name='delivery_date[]' id='delivery_date' value='${v.delivery_date}' class='form-control' /></td>`;
                                    tableHtml +=
                                        `<td>${v.remark}<input type='hidden' name='remark[]' id='remark' value='${v.remark}' class='form-control' /></td>`;
                                    tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;
                                });
                            $('#GridTable').html(tableHtml);
                            TotalPackQTY();
                            TotalOrderQTY();
                            $('#updated_by_name').removeClass('d-none');
                            $('#voucher_no').val(response.data[0].sale_order.voucher_no);
                            $('#update_voucher_id').val(response.data[0].sale_order.id);
                            $('#voucher_date').val(response.data[0].sale_order.voucher_date);
                            $('#voucher_no').val(response.data[0].sale_order.voucher_no);
                            $('#voucher_no').focus();
                            $('#party_name').val(response.data[0].party_id)
                                .select2();
                            $('#party_id').val(response.data[0].sale_order.party_id);
                            $('#address').val(response.data[0].sale_order.party.address);
                            $('#remarks').val(response.data[0].sale_order.remarks);
                            $('#credit_days').val(response.data[0].sale_order.credit_days);
                            $('#payment_mode').val(response.data[0].sale_order.payment_mode)
                                .select2();
                            $('#po_date').val(response.data[0].sale_order.po_date);
                            $('#po_no').val(response.data[0].sale_order.po_no);
                            $('#shipment_term').val(response.data[0].sale_order.shipment_term)
                                .select2();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_voucher_id').val(null);
                            $('#voucher_no').focus();
                            $('#party_name').val(null).select2();
                            $('#party_id').val(null);
                            $('#address').val(null);
                            $('#remarks').val(null);
                            $('#credit_days').val(null);
                            $('#payment_mode').val(null);
                            $('#po_date').val(null);
                            $('#po_no').val(null);
                            $('#shipment_term').val(null);
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
