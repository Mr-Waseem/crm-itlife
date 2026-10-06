@extends('app')
@section('head')
<title>Customer Delivery Challan</title>
<!--  Select 2 library start-->
<link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<!--  Select 2 library end-->
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
        <h1>
            Customer Delivery Challan
        </h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
            <li class="breadcrumb-item active"><a href="#">Customer Delivery Challan</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <section class="content">
                <div class="box">
                    <div class="box-header with-border">
                        <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Customer Delivery Challan</h6>
                        <ul class="box-controls pull-right">
                            <li><a class="box-btn-fullscreen" href="#"></a></li>
                        </ul>
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
                                {!! Form::open(['url' => 'customer-delivery-challan', 'class' => 'form-horizontal', 'id' =>
                                'delivery-challan-form']) !!}
                                {!! Form::hidden('update_voucher_id', null, ['id' => 'update_voucher_id']) !!}
                                {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                <div class="row">
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
                                </div>

                                <div class="row">
                                   
                                    <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                        <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No#<span
                                                class="text-danger">*</span></label>
                                        {!! Form::text('voucher_no', $codes, [
                                        'id' => 'voucher_no',
                                        'class' => 'form-control',
                                        'tabindex' => '0',
                                        'required' => 'required',
                                      
                                        ]) !!}
                                        @error('voucher_no')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                        <label for="date"><i class="fa fa-caret-right"></i> Voucher Date</label>
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
                                    <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                        <label for="party_name"><i class="fa fa-caret-right"></i> Party Name<span
                                                class="text-danger">*</span></label>
                                        {!! Form::select('party_name', $customers, null, [
                                        'id' => 'party_name',
                                        'class' => 'form-control select2 customer',
                                        'tabindex' => '2',
                                        'required' => 'required',
                                        ]) !!}
                                        {!! Form::hidden('party_id', null, ['id' => 'party_id']) !!}
                                        @error('party_name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
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
                                    <div class="col-lg-3 col-md-2 col-sm-12 mt-1">
                                        <label for="sale_order_no"><i class="fa fa-caret-right"></i> Sale Order No.<span
                                                class="text-danger">*</span></label>
                                        {!! Form::select('sale_order_no', $salerOrderNo, null, [
                                        'id' => 'sale_order_no',
                                        'class' => 'form-control select2',
                                        'tabindex' => '4',
                                        ]) !!}
                                        @error('sale_order_no')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-12 mt-1">
                                        <label for="order_date"><i class="fa fa-caret-right"></i> Order Date<span
                                                class="text-danger">*</span></label>
                                        {!! Form::date('order_date', date('Y-m-d'), [
                                        'id' => 'order_date',
                                        'class' => 'form-control',
                                        'tabindex' => '5',
                                        ]) !!}
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                        <label for="remarks"><i class="fa fa-caret-right"></i> Remarks<span
                                                class="text-danger">*</span></label>
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
                                        @error('driver_phoneno')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                        <label for="freight"><i class="fa fa-caret-right"></i> Freight<span class="text-danger">*</span></label>
                                        {!! Form::text('freight', null, [
                                            'id' => 'freight',
                                            'class' => 'form-control',
                                            'tabindex' => '12',
                                        ]) !!}
                                        @error('freight')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
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
                                                            <th>Product</th>
                                                            <th>Unit</th>
                                                            <th>Qty</th>
                                                            <th>Packing</th>
                                                            <th>Sale Qty</th>
                                                            <th>Remarks</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="GridTable"></tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="2"><strong>Total</strong></td>
                                                            <td class="bg-primary" id="TotalQty">0</td>
                                                            <td></td>
                                                            <td class="bg-success" id="Totalsaleqty">0</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
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
            <form action="{{ URL::to('customer-delivery-challan/delete-voucher') }}" method="post" id="delete_voucher_form">
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
<!-- modal -->
<div class="modal hide fade" id="print-record-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
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
@stop
@section('scripts')
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
    function Qtychange(qty){
        if(qty=='')
        {
            document.getElementById('sale_qty1').value=0;
        }else{
            var packing=document.getElementById('packing1').value;
            var sale_qty=parseInt(packing)*parseInt(qty);
            document.getElementById('sale_qty1').value=sale_qty;
        }
    }
    //fetch recod Customers product
        $(document).ready(function() {
                $('#party_name').change(function(event){
                    var party = $(this).val();
                    $('#party_id').val(party.split('_')[0]);
                    var customer_id = parseInt($('#party_id').val());
                        $.ajax({
                            type: 'get',
                            dataType: 'json',
                            url: "{{ asset('customer-delivery-challan/getCustomerProduct')}}",
                            type: 'get',
                            data:{customer_id:customer_id},
                            dataType: 'json',
                            success: function(response) {
                    
                            if (response.length > 0) {
                                console.log(response);
                                var option;
                                $.each(response, function(i, v) {
                                    $('#unit1').val(v.product.uom);
                                    $('#packing1').val(v.product.packing);
                                    option += `<option value="${v.id}_${v.product.uom}_${v.product.packing}_${v.product_name}">${v.product_name}</option>`;
                                });
                                $('#product_id1').html(option);
                            } else {
                                var option = '<option value="" selected>Product Not Found</option>';
                                $('#product_id1').html(option);
                            }
                        }
                    });
                });
        });

        $(document).ready(function() {
            $('#voucher_date').keydown(function(event) {
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
                    $('#sale_order_no').select2('open');
                }
            });
            $('#sale_order_no').change(function(event) {
                var sale_order_no = $(this).val();
                if (sale_order_no) {
                    $('#sale_order_no').select2().trigger('select2:close');
                    $('#order_date').focus();
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
                if (keycode == '13') {        
                    $("#qty1").focus();
                }
            });
            $('#qty1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#comment1").focus();
                }
            });
            $('#comment1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                        AddGridData();  
                }
            });
            $('#product_id1').change(function(event) {
                var product_id = $(this).val();
                if (product_id != null) {
                    $('#unit1').val(product_id.split('_')[1]);
                    $('#product_id1').select2().trigger('select2:close');
                    $('#qty1').val(1);
                    $('#qty1').focus();
                    $('#packing1').val(product_id.split('_')[2]);
                  
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
            var unit = product.value.split('_')[1];
            var packing = parseInt(product.value.split('_')[2]);
            var pro_name = product.value.split('_')[3];
            var qty = parseInt(document.getElementById('qty1').value);
            var comment=document.getElementById('comment1').value;
            var saleqty = packing*qty;

            var TotalQty = parseInt(document.getElementById('TotalQty').innerHTML);
            var Totalsaleqty = parseInt(document.getElementById('Totalsaleqty').innerHTML);

            var grandTotalQty = TotalQty + qty;
            var grandTotalsaleqty = Totalsaleqty + saleqty;

            var tableHtml = `<tr>`;
            tableHtml +=
                `<td>${pro_name}<input type='hidden' name='product_id[]' id='product_id' value='${parseInt(pro_id)}' /></td>`;
            tableHtml +=
                `<td>${unit}</td>`;
            tableHtml +=
                `<td>${qty}<input type='hidden' name='qty[]' id='qty' value='${qty}' /></td>`;
            tableHtml +=
                `<td>${packing}<input type='hidden' name='packing[]' id='packing' value='${packing}' /></td>`;
            tableHtml +=
                `<td>${saleqty}<input type='hidden' name='sale_qty[]' id='sale_qty' value='${saleqty}' /></td>`;
            tableHtml +=
                `<td>${comment}<input type='hidden' name='comment[]' id='comment' value='${comment}' /></td>`;
            tableHtml +=
                `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
            tableHtml += `</tr>`;

            $('#GridTable').append(tableHtml);
            $('#TotalQty').html(grandTotalQty);
            $('#Totalsaleqty').html(grandTotalsaleqty);


            
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
    $(document).ready(function() {
            // Hide Alert Notification After 3 Seconds
            setInterval(() => {
                $('.alert').hide();
            }, 3000);
            // End Hide Alert Notification After 3 Seconds


            // Form Submit
            $('.submit-form').click(function() {
                $('#delivery-challan-form').submit();
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
            var NewsaleQty = parseInt($(row).find("td:eq('4')").find('input').val());
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
                        url: "{{ URL::to('customer-delivery-challan/print/voucher') }}?voucher_no=" +
                            voucher_no,
                        type: 'get',
                        beforeSend: function(response) {
                            $('#print-receipt-modal-body').html(
                                '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                            );
                        },
                        success: function(response) {
                            console.log(response);
                            if (response != null && response!=0) {
                                $('#print-receipt-modal-body').html(
                                    `<object data="${base_url}/root/upload/customer-delivery-challan/${response}" type="application/pdf" width="100%" height="800"></object>`
                                );
                            } else {
                                $('#print-receipt-modal-body').html('<h2 style="color:red;text-align:center;">Voucher Not Exist</h2>');
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
                $.ajax({
                    url: "{{ URL::to('customer-delivery-challan/load/record') }}?voucher_no=" + voucher_no,
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
                            var totalQty = 0;
                            var totalsale_qty= 0;


                            $.each(response.data, function(i, v) {
                                totalQty += parseInt(v.quantity);
                                totalsale_qty += parseInt(v.sale_qty);

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.cusproduct.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.cusproduct.id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.cusproduct.product.uom}</td>`;
                                tableHtml +=
                                    `<td>${v.quantity}<input type='hidden' name='qty[]' id='qty' value='${v.quantity}' /></td>`;
                                tableHtml +=
                                    `<td>${v.packing}<input type='hidden' name='packing[]' id='packing' value='${v.packing}' /></td>`;
                                tableHtml +=
                                    `<td>${v.sale_qty}<input type='hidden' name='sale_qty[]' id='sale_qty' value='${v.sale_qty}' /></td>`;
                                tableHtml +=
                                    `<td>${v.comments}<input type='hidden' name='comment[]' id='comment' value='${v.comments}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty);
                            $('#Totalsaleqty').text(totalsale_qty);
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
                            $('#sale_order_no').val(response.data[0].delivery_challan
                                .sale_order_no).select2();
                            $('#address').val(response.data[0].delivery_challan.party.address);
                            $('#remarks').val(response.data[0].delivery_challan.remarks);
                            $('#vehicle_no').val(response.data[0].delivery_challan.vehicle_no);
                            $('#driver_name').val(response.data[0].delivery_challan.driver_name);
                            $('#builty_no').val(response.data[0].delivery_challan.builty_no);
                            $('#driver_phoneno').val(response.data[0].delivery_challan.driver_phoneno);
                            $('#transport_company').val(response.data[0].delivery_challan.transport_company);
                            $('#freight').val(response.data[0].delivery_challan.freight);
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalNetWeight').text(0);
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
                        }
                    }
                });
            });


            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('customer-delivery-challan/load/next/record') }}?voucher_no=" +
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
                            var tableHtml = '';
                            var totalQty = 0;
                            var totalsale_qty= 0;


                            $.each(response.data, function(i, v) {
                                totalQty += parseInt(v.quantity);
                                totalsale_qty += parseInt(v.sale_qty);

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.cusproduct.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.cusproduct.id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.cusproduct.product.uom}</td>`;
                                tableHtml +=
                                    `<td>${v.quantity}<input type='hidden' name='qty[]' id='qty' value='${v.quantity}' /></td>`;
                                tableHtml +=
                                    `<td>${v.packing}<input type='hidden' name='packing[]' id='packing' value='${v.packing}' /></td>`;
                                tableHtml +=
                                    `<td>${v.sale_qty}<input type='hidden' name='sale_qty[]' id='sale_qty' value='${v.sale_qty}' /></td>`;
                                tableHtml +=
                                    `<td>${v.comments}<input type='hidden' name='comment[]' id='comment' value='${v.comments}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty);
                            $('#Totalsaleqty').text(totalsale_qty);
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
                            $('#sale_order_no').val(response.data[0].delivery_challan
                                .sale_order_no).select2();
                            $('#address').val(response.data[0].delivery_challan.party.address);
                            $('#remarks').val(response.data[0].delivery_challan.remarks);
                            $('#vehicle_no').val(response.data[0].delivery_challan.vehicle_no);
                            $('#driver_name').val(response.data[0].delivery_challan.driver_name);
                            $('#builty_no').val(response.data[0].delivery_challan.builty_no);
                            $('#driver_phoneno').val(response.data[0].delivery_challan.driver_phoneno);
                            $('#transport_company').val(response.data[0].delivery_challan.transport_company);
                            $('#freight').val(response.data[0].delivery_challan.freight);
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalNetWeight').text(0);
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
                        }
                    }
                });
            });
            // End Here of Load Next Record


            // Load Previous Record
            $('.load-previous-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('customer-delivery-challan/load/previous/record') }}?voucher_no=" +
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
                            var tableHtml = '';
                            var totalQty = 0;
                            var totalsale_qty= 0;


                            $.each(response.data, function(i, v) {
                                totalQty += parseInt(v.quantity);
                                totalsale_qty += parseInt(v.sale_qty);

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.cusproduct.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.cusproduct.id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.cusproduct.product.uom}</td>`;
                                tableHtml +=
                                    `<td>${v.quantity}<input type='hidden' name='qty[]' id='qty' value='${v.quantity}' /></td>`;
                                tableHtml +=
                                    `<td>${v.packing}<input type='hidden' name='packing[]' id='packing' value='${v.packing}' /></td>`;
                                tableHtml +=
                                    `<td>${v.sale_qty}<input type='hidden' name='sale_qty[]' id='sale_qty' value='${v.sale_qty}' /></td>`;
                                tableHtml +=
                                    `<td>${v.comments}<input type='hidden' name='comment[]' id='comment' value='${v.comments}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty);
                            $('#Totalsaleqty').text(totalsale_qty);
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
                            $('#sale_order_no').val(response.data[0].delivery_challan
                                .sale_order_no).select2();
                            $('#address').val(response.data[0].delivery_challan.party.address);
                            $('#remarks').val(response.data[0].delivery_challan.remarks);
                            $('#vehicle_no').val(response.data[0].delivery_challan.vehicle_no);
                            $('#driver_name').val(response.data[0].delivery_challan.driver_name);
                            $('#builty_no').val(response.data[0].delivery_challan.builty_no);
                            $('#driver_phoneno').val(response.data[0].delivery_challan.driver_phoneno);
                            $('#transport_company').val(response.data[0].delivery_challan.transport_company);
                            $('#freight').val(response.data[0].delivery_challan.freight);
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalNetWeight').text(0);
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
                        }
                    }
                });
            });
            // End Here of Load Previous Record
        });
</script>
<!-- End Load & Edit Record -->

@include('include.toast-messages')
@stop