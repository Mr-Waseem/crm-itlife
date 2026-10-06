@extends('app')
@section('head')
    <title>Purchase Order Voucher</title>
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
                Purchase Order Voucher
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Purchase Order Voucher</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <!-- <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Purchase Order Voucher</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div> -->
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                @include('errors.validation')
                                <div class="col">
                                    @if (Session::has('failure_message'))
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">×</button> {{ Session::get('failure_message') }}
                                        </div>
                                    @endif
                                    <div id="show_err"></div>
                                    {!! Form::open(['url' => 'purchase-order', 'class' => 'form-horizontal', 'id' => 'purchase-order-form']) !!}
                                    {!! Form::hidden('update_voucher_id', null, ['id' => 'update_voucher_id']) !!}
                                    {!! Form::hidden('warehouse_name', $warehouse->name, ['id' => 'warehouse_name']) !!}
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
                                        <div class="col-lg-2 col-md-12 col-sm-12 mt-1">
                                            <label for="date"><i class="fa fa-caret-right"></i> Voucher Date. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::date('date', date('Y-m-d'), [
                                                'id' => 'date',
                                                'class' => 'form-control',
                                                'tabindex' => '0',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-1 col-md-6 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>PO#<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $codes, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'tabindex' => '0',
                                                'autofocus' => 'autofocus',
                                                'required' => 'required',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                            ]) !!}
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="bill_no"><i class="fa fa-caret-right"></i>Request#<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('request_no', null, [
                                                'id' => 'request_no',
                                                'class' => 'form-control request_no_change',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                            ]) !!}
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-lg-4 col-md-12 col-sm-12 mt-1">
                                            <label for="supplier_id"><i class="fa fa-caret-right"></i> Supplier. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('supplier_id', $suppliers, null, [
                                                'id' => 'supplier_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                            ]) !!}
                                            @error('supplier_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-12 col-sm-12 mt-1">
                                            <label for="address"><i class="fa fa-caret-right"></i> Address.</label>
                                            {!! Form::text('address', null, [
                                                'id' => 'address',
                                                'class' => 'form-control',
                                                'disabled' => 'disabled',
                                                'placeholder' => 'Address',
                                                'tabindex' => '2',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 mt-1">
                                            <label for="purchaser_id"><i class="fa fa-caret-right"></i> Purchaser. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('purchaser_id', $purchasers, null, [
                                                'id' => 'purchaser_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '3',
                                            ]) !!}
                                            @error('purchaser_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i> Remarks.</label>
                                            {!! Form::text('remarks', null, [
                                                'id' => 'remarks',
                                                'class' => 'form-control',
                                                'placeholder' => 'Remarks',
                                                'tabindex' => '4',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 mt-1">
                                            <label for="tax_with_holding"><i class="fa fa-caret-right"></i> Tax With
                                                Holding.</label>
                                            {!! Form::text('tax_with_holding', null, [
                                                'id' => 'tax_with_holding',
                                                'class' => 'form-control',
                                                'placeholder' => 'Tax With Holding',
                                                'tabindex' => '5',
                                            
                                            ]) !!}
                                            <span class="taxWithholding_err text-danger"></span>
                                        </div>
                                    </div>

                                    <!-- <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="table-responsive-md mb-2">
                                                <table class="table">
                                                    <thead>
                                                        <tr class="bg-primary text-left">
                                                            <th>Product</th>
                                                            <th>Unit</th>
                                                            <th>Qty</th>
                                                            <th>Rate</th>
                                                            <th>Excl.val</th>
                                                            <th>Sale Tax</th>
                                                            <th>ST Rate</th>
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
                                                                    'tabindex' => '9',
                                                                ]) !!}
                                                                {!! Form::hidden('code2', null, ['id' => 'code2']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select('product_id1', $products, null, [
                                                                    'id' => 'product_id1',
                                                                    'class' => 'form-control select2',
                                                                    'tabindex' => '10',
                                                                ]) !!}
                                                                <span class="product_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('unit1', null, [
                                                                    'id' => 'unit1',
                                                                    'class' => 'form-control',
                                                                    'disabled' => 'disabled',
                                                                    'placeholder' => 'Unit',
                                                                    'tabindex' => '11',
                                                                ]) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('qty1', null, [
                                                                    'id' => 'qty1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Qty',
                                                                    'tabindex' => '12',
                                                                    'onkeyup' => 'QuantityKeyUp($(this).val())',
                                                                ]) !!}
                                                                <span class="qty_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('rate1', null, [
                                                                    'id' => 'rate1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Rate',
                                                                    'tabindex' => '13',
                                                                    'onkeyup' => 'PriceKeyUp($(this).val())',
                                                                ]) !!}
                                                                <span class="rate_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('excl_val1', null, [
                                                                    'id' => 'excl_val1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Excl val',
                                                                    'tabindex' => '14',
                                                                    'readonly' => 'readonly',
                                                                ]) !!}
                                                                <span class="excl_val1_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('sale_tax1', null, [
                                                                    'id' => 'sale_tax1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Sale Tax',
                                                                    'tabindex' => '15',
                                                                    'readonly' => 'readonly',
                                                                ]) !!}
                                                                <span class="sale_tax1_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('st_rate1', null, [
                                                                    'id' => 'st_rate1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'ST Rate ',
                                                                    'tabindex' => '15',
                                                                    'onkeyup' => 'TaxKeyUp($(this).val())',
                                                                ]) !!}
                                                                <span class="st_rate1_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('total1', null, [
                                                                    'id' => 'total1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Total',
                                                                    'tabindex' => '16',
                                                                    'readonly' => 'readonly',
                                                                ]) !!}
                                                                <span class="total_err text-danger"></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="table-responsive mb-2">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Code</th>
                                                                <th>Product</th>
                                                                <th>Unit</th>
                                                                <th>Qty</th>
                                                                <th>Rate</th>
                                                                <th>Excl.val</th>
                                                                <th>ST Rate</th>
                                                                <th>Sale Tax</th>
                                                                
                                                                <th>Total</th>
                                                                <th>Comment</th>
                                                                <!-- <th>Action</th> -->
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3"><strong>Total</strong></td>
                                                                <td class="bg-primary" id="TotalQty">0</td>
                                                                <td></td>
                                                                <td class="bg-info" id="TotalExclVolue">0</td>
                                                                <td></td>
                                                                <td class="bg-success" id="TotalSaleTax">0</td>
                                                                <td class="bg-success" id="TotalAmount">0</td>
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
                <form action="{{ URL::to('purchase-order/destroy') }}" method="post" id="delete_voucher_form">
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

    <!--calculation proccess start -->
    <script>
        function TotalAmount() {
            var tableData = document.getElementById('GridTable');

            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[8].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalAmount').innerText = sum.toLocaleString('en-US');
        }

        function TotalQty() {
            var tableData = document.getElementById('GridTable');
            
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                if (tableData.rows[i].cells[3].getElementsByTagName('input')[0].value == '') {
                    sum += 0;
                } else {
                    sum += parseInt(tableData.rows[i].cells[3].getElementsByTagName('input')[0].value);
                }   
            }
            document.getElementById('TotalQty').innerText = sum.toLocaleString('en-US');
        }

        function TotalExclVolue() {
            var tableData = document.getElementById('GridTable');

            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[5].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalExclVolue').innerText = sum.toLocaleString('en-US');
        }

        function TotalSaleTax() {
            var tableData = document.getElementById('GridTable');

            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                if (tableData.rows[i].cells[7].getElementsByTagName('input')[0].value == '') {
                    sum += 0;
                } else {
                    sum += parseInt(tableData.rows[i].cells[7].getElementsByTagName('input')[0].value);
                }
            }
            document.getElementById('TotalSaleTax').innerText = sum.toLocaleString('en-US');
        }
    </script>
    <!--calculation proccess end -->
    <script>
        $(document).ready(function() {
            $('#voucher_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#request_no").focus();
                }
            });

            $('#request_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    
                    $("#remarks").select();
                    // $("#supplier_id").select2('open');
                }
            });
            $('#supplier_id').change(function(event) {

                var supplier = $(this).val();
                if (supplier) {
                    $('#supplier_id').select2('close');
                    $('#address').val(supplier.split('_')[2]);
                    $("#purchaser_id").select2("open");
                }
            });
            $('#purchaser_id').change(function(event) {
                var purchase = $(this).val();
                if (purchase) {
                    $('#purchaser_id').select2().trigger('select2:close');
                    $("#remarks").focus();
                }
            });
            $('#remarks').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#tax_with_holding").select();
                }
            });
            $('#tax_with_holding').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#product_id1').select2('open');
                }
            });
            $('#product_id1').change(function(event) {
                var product_id = $(this).val();
                if (product_id != null) {
                    $('#unit1').val(product_id.split('_')[3]);
                    $('#product_id1').select2().trigger('select2:close');
                    $('#st_rate1').val(product_id.split('_')[4]);
                    $('#qty1').val(1);
                    $('#qty1').focus();
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
                        $('#rate1').focus();
                    }
                }
            });
            $(document).on('keydown', '#qty', function(event) {
                let row = $(this).closest('tr');
                // console.log($(row).find("td:eq('3')").find('input').val());
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $(row).find("td:eq('4')").find('input').focus();
                }
            });
            $(document).on('keydown', '#rate', function(event) {
                let row = $(this).closest('tr');
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $(row).find("td:eq('7')").find('input').focus();
                }
            });
            $('#rate1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = parseInt($(this).val());
                    if (qty <= 0) {
                        $(this).focus();
                        $('.qty_err').text('This field is required & Must be greater than zero');
                    } else {
                        $('.qty_err').text('');
                        $('#st_rate1').focus();
                    }
                }
            });

            $('#st_rate1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('.product_id1_err').text('');
                    $('.qty_err').text('');

                    var product_id = parseInt($('#product_id1').val());
                    var tax_with_holding = parseInt($('#tax_with_holding').val());
                    var rate = $('#rate1').val();
                    var qty = parseInt($('#qty1').val());
                    var sale_tax = parseInt($('#st_rate1').val());

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
                    if (!rate || rate <= 0) {
                        $('#rate1').focus();
                        $('.rate1_err').text('This field is required & Must be greater than zero');
                        return false;
                    } else
                    if (!tax_with_holding || tax_with_holding <= 0) {
                        $('#tax_with_holding').focus();
                        $('.taxWithholding_err').text('This field is required & Must be greater than zero');
                        return false;
                    } else {
                        AddGridData();
                    }
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
                $('#product_id1').val(null).select2('open');
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

            var rate = parseInt($('#rate1').val());
            if (!rate || rate <= 0) {
                $('.rate1_err').text('This field is required & Must be greater than zero');
                $('#rate1').focus();
                return false;
            } else {
                $('.rate1_err').text('');
            }

            var pro_id = document.getElementById('product_id1').value.split('_')[0];
            var product_code = document.getElementById('product_id1').value.split('_')[1];
            var pro_name = document.getElementById('product_id1').value.split('_')[2];
            var pro_unit = document.getElementById('product_id1').value.split('_')[3];
            var tax = document.getElementById('st_rate1').value;
            // var tax = document.getElementById('product_id1').value.split('_')[4];
            var qty = parseFloat(document.getElementById('qty1').value);
            var rate = parseFloat(document.getElementById('rate1').value);
            var excl_val = parseFloat(document.getElementById('excl_val1').value);
            var saletaxValue = parseFloat(document.getElementById('sale_tax1').value);
            var total = parseFloat(document.getElementById('total1').value);

            var grandTotalAmount = TotalAmount + total;
            var tableHtml = `<tr>`;
            tableHtml +=
                `<td>${product_code} <input type='hidden' name='product_code[]' id='product_code' value='${product_code}' /></td>`;
            tableHtml += `<td>
                            ${pro_name}
                            <input type='hidden' name='product_id[]' id='product_id' value='${pro_id}' />
                        </td>`;
            tableHtml += `<td>${pro_unit}</td>`;
            tableHtml +=
                `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));"/></td>`;
            tableHtml +=
                `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${rate}' class='form-control' onkeyup="changerate($(this).closest('tr'));"/></td>`;
            tableHtml +=
                `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${excl_val}' class='form-control'   readonly/></td>`;
            tableHtml +=
                `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${saletaxValue}' class='form-control'  readonly/></td>`;
            tableHtml +=
                `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${tax}' class='form-control'  onkeyup="changetaxrate($(this).closest('tr'));"//></td>`;
            tableHtml +=
                `<td style="width:120px;"><input type='text' name='total[]' id='total' value='${total}' class='form-control' readonly /></td>`;
            tableHtml +=
                `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
            tableHtml += `</tr>`;

            $('#GridTable').append(tableHtml);
            TotalAmount();
            TotalQty();
            TotalExclVolue();
            TotalSaleTax();
            // $('#qty').focus();
            // var gridTable = document.getElementById("GridTable");
            // gridTable.addEventListener("keydown", function(event) {
            //      if (event.keyCode === 13) {
            //         $('#rate').focus();
            //      }
            // });
            // var gridTable2 = document.getElementById("GridTable");
            // gridTable2.addEventListener("keydown", function(event) {
            //      if (event.keyCode === 13) {
            //         $('#sale_tax').focus();
            //      }
            // });
            $('#qty1').val(null);
            $('#rate1').val(null);
            $('#excl_val1').val(null);
            $('#st_rate1').val(null);
            $('#sale_tax1').val(null);
            $('#total1').val(null);
            $('#product_id1').val(null).select2();
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

                $('#purchase-order-form').submit();
                $('.submit-form').attr('disabled', true);
            });
            // End FOrm Submit

            // Reset btn feature
            $('.reset-btn').click(function() {
                $('#party_name').val(null).select2();
                $('#transaction_type').val(null).select2();
                $('#update_voucher_id').val(null);
                $('#updated_by_name').addClass('d-none');
            });
            // End Reset btn feature
        });
    </script>


    <!-- OnChange Qty -->
    <script>
        function changeQty(row) {
            var qty = $(row).find("td:eq('3')").find('input').val();
            var rate = $(row).find("td:eq('4')").find('input').val();
            var saleTax = parseInt($(row).find("td:eq('7')").find('input').val());
            var exclValue;
            var stValue;
            var totalAmount;
            if (qty == '' || parseInt(qty) == 0) {
                $('.qty_errr').text('Please Enter Qty');
                $(row).find("td:eq('3')").find('input').focus();

                TotalAmount();
                TotalQty();
                TotalExclVolue();
                TotalSaleTax();
                return false;
            }
            if (rate == null || parseInt(rate) == 0) {
                exclValue = 0;
                stValue = 0;
                totalAmount = 0;
            } else {
                ///Excl valu
                exclValue = rate * qty
                //st rate
                stValue = (rate * saleTax / 100) * qty;
                //total Amount
                totalAmount = stValue + exclValue;
            }

            $(row).find("td:eq('5')").find('input').val(parseInt(exclValue));
            $(row).find("td:eq('6')").find('input').val(parseInt(stValue));
            $(row).find("td:eq('8')").find('input').val(parseInt(totalAmount));

            var taxWithHolding = document.getElementById('tax_with_holding').value;
            var per = taxWithHolding / 100 * totalAmount;
            var caltotal = totalAmount - per;

            $(row).find("td:eq('8')").find('input').val(parseInt(caltotal));

            TotalAmount();
            TotalQty();
            TotalExclVolue();
            TotalSaleTax();

        }

        function changerate(row) {
            var qty = $(row).find("td:eq('3')").find('input').val();
            var rate = $(row).find("td:eq('4')").find('input').val();
            var saleTax = parseInt($(row).find("td:eq('7')").find('input').val());
            // alert(qty);
            // alert(rate);
            // alert(saleTax);
            var exclValue;
            var stValue;
            var totalAmount;
            if (qty == '' || parseInt(qty) == 0) {
                $('.qty_errr').text('Please Enter Qty');
                $(row).find("td:eq('3')").find('input').focus();

                TotalAmount();
                TotalQty();
                TotalExclVolue();
                TotalSaleTax();
                return false;
            }
            if (rate == null || parseInt(rate) == 0) {
                exclValue = 0;
                stValue = 0;
                totalAmount = 0;
            } else {
                ///Excl valu
                exclValue = rate * qty
                //st rate
                stValue = (rate * saleTax / 100) * qty;
                //total Amount
                totalAmount = stValue + exclValue;
            }
            $(row).find("td:eq('5')").find('input').val(parseInt(exclValue));
            $(row).find("td:eq('6')").find('input').val(parseInt(stValue));
            $(row).find("td:eq('8')").find('input').val(parseInt(totalAmount));

            var taxWithHolding = document.getElementById('tax_with_holding').value;
            var per = taxWithHolding / 100 * totalAmount;
            var caltotal = totalAmount - per;
            $(row).find("td:eq('8')").find('input').val(parseInt(caltotal));

            TotalAmount();
            TotalQty();
            TotalExclVolue();
            TotalSaleTax();

        }

        function changetaxrate(row) {
            var qty = $(row).find("td:eq('3')").find('input').val();
            
            var rate = $(row).find("td:eq('4')").find('input').val();
            var saleTax = $(row).find("td:eq('6')").find('input').val();
            // alert(qty)
            // alert(rate)
            // alert(saleTax)
            var exclValue;
            var stValue;
            var totalAmount;
            var taxvalue;
            if (qty == '' || parseInt(qty) == 0) {
                $('.qty_errr').text('Please Enter Qty');
                $(row).find("td:eq('3')").find('input').focus();

                TotalAmount();
                TotalQty();
                TotalExclVolue();
                TotalSaleTax();
                return false;
            }
            if (saleTax == '' || parseInt(saleTax) == 0) {
                exclValue = 0;
                stValue = 0;
                totalAmount = 0;
            } else {
                ///Excl valu
                exclValue = rate * qty
                //st rate
                stValue = (rate * parseInt(saleTax) / 100) * qty;
                //total Amount
                totalAmount = stValue + exclValue;
            }

            $(row).find("td:eq('5')").find('input').val(parseInt(exclValue));
            $(row).find("td:eq('7')").find('input').val(parseInt(stValue));
            $(row).find("td:eq('8')").find('input').val(parseInt(totalAmount));

            var taxWithHolding = document.getElementById('tax_with_holding').value;
            var per = taxWithHolding / 100 * totalAmount;
            var caltotal = totalAmount - per;
            $(row).find("td:eq('8')").find('input').val(parseInt(caltotal));

            TotalAmount();
            TotalQty();
            TotalExclVolue();
            TotalSaleTax();
        }

        function DeleteRow(row) {
            $(row).remove();
            TotalAmount();
            TotalQty();
            TotalExclVolue();
            TotalSaleTax();
        }

        // function PriceKeyUp(rate) {
        //     var quantity = document.getElementById('qty1').value;
        //     var stax = document.getElementById('st_rate1').value;
        //     var  exclValue=rate*quantity
        //     var stValue=(rate*stax/100)*quantity;
        //      //total Amount
        //     var totalAmount=stValue + exclValue;
        //     document.getElementById('excl_val1').value = parseInt(exclValue);
        //     document.getElementById('sale_tax1').value = parseInt(stValue);
        //     document.getElementById('total1').value = parseInt(totalAmount);
        //     taxWithHolding();

        // }
        function TaxKeyUp(stax) {
            var quantity = document.getElementById('qty1').value;
            var rate = document.getElementById('rate1').value;
            var exclValue = rate * quantity
            var stValue = (rate * stax / 100) * quantity;
            //total Amount
            var totalAmount = stValue + exclValue;
            document.getElementById('excl_val1').value = parseInt(exclValue);
            document.getElementById('sale_tax1').value = parseInt(stValue);
            document.getElementById('total1').value = parseInt(totalAmount);
            taxWithHolding();
        }

        function taxWithHolding() {
            var total = document.getElementById('total1').value;
            var taxWithHolding = document.getElementById('tax_with_holding').value;
            var per = taxWithHolding / 100 * total;
            var caltotal = total - per;
            document.getElementById('total1').value = parseInt(caltotal);
        }

        function QuantityKeyUp(quantity) {
            var rate = document.getElementById('rate1').value;
            var stax = document.getElementById('st_rate1').value;
            
            var exclValue = rate * quantity
            var stValue = (rate * stax / 100) * quantity;
            //total Amount
            var totalAmount = stValue + exclValue;
            document.getElementById('excl_val1').value = parseInt(exclValue);
            document.getElementById('sale_tax1').value = parseInt(stValue);
            document.getElementById('total1').value = parseInt(totalAmount);
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
                var base_url = $('#base_url').val();
                $.ajax({
                    url: "{{ URL::to('purchase-order/print/voucher') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    beforeSend: function(response) {
                        $('#print-receipt-modal-body').html(
                            '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                        );
                    },
                    success: function(response) {
                        if (response != null && response != 0) {
                            $('#print-receipt-modal-body').html(
                                `<object data="${base_url}/resources/upload/purchase-order/${response}" type="application/pdf" width="100%" height="800"></object>`
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
            $('.request_no_change').keydown(function() {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                var voucher_no = parseInt($('#request_no').val());
                $.ajax({
                    url: "{{ URL::to('purchase-order/load/request') }}?voucher_no=" + voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        // alert("Ddd");
                        if(response.data == "already exist"){
                            // alert("edd");
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Request Already Exist!</div>'
                            );
                            $('#request_no').focus();
                            return false;
                        }
                        if (response.data != '') {
                            var tableHtml = '';

                            $.each(response.data, function(i, v) {

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                tableHtml += `<td>${v.product.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));", onkeypress="return isNumberKey(event)"/>
                                                </td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' onkeyup="changerate($(this).closest('tr'));", onkeypress="return isNumberKey(event)"/>
                                                <span class="rate_errr text-danger"></span>
                                                </td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control' readonly/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.product.tax}' class='form-control' onkeyup="changetaxrate($(this).closest('tr'));" , onkeypress="return isNumberKey(event)"/></td>`;
                               
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' readonly/></td>`;

                                tableHtml +=
                                    `<td style="width:120px;">
                                                <input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly/>
                                            </td>`;
                                tableHtml +=
                                    `<td style="width:120px;">
                                        <input type='text'  value='${v.comments}' name='comments[]'  id='comments'  class='form-control'/>
                                </td>`;
                                // tableHtml +=
                                //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;

                            });

                            $('#GridTable').html(tableHtml);
                            
                            TotalAmount();
                            TotalQty();
                            TotalExclVolue();
                            TotalSaleTax();
                            $('#updated_by_name').removeClass('d-none');
                            $('#date').val(response.data[0].purchaseorder.date);
                            // $('#update_voucher_id').val(response.data[0].purchaseorder.id);
                            $('#purchaser_id').val(response.data[0].purchaseorder.purchaser_id)
                                .select2();
                            $('#remarks').val(response.data[0].purchaseorder.remarks);
                            $('#tax_with_holding').val(response.data[0].purchaseorder
                                .tax_with_holding);
                            $('#address').val(response.data[0].purchaseorder.supplier.address)
                            $('#supplier_id').val(response.data[0].purchaseorder.supplier.id +
                                '_' + response
                                .data[0].purchaseorder.supplier.party_name + '_' + response
                                .data[0].purchaseorder.supplier
                                .address).select2();

                               
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalExclVolue').text(0);
                            $('#TotalSaleTax').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');
                            $('#update_voucher_no').val(null);
                            $('#date').focus();
                            $('#voucher_no').focus();
                            $('#remarks').val(null);
                            $('#supplier_id').val(null);
                            $('#purchaser_id').val(null);
                        }
                    }
                });
            }
            });





















            $('.load-edit-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());

                $.ajax({
                    url: "{{ URL::to('purchase-order/load/record') }}?voucher_no=" + voucher_no,
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
                                tableHtml +=
                                    `<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                tableHtml += `<td>${v.product.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));", onkeypress="return isNumberKey(event)"/>
                                                </td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' onkeyup="changerate($(this).closest('tr'));", onkeypress="return isNumberKey(event)"/>
                                                <span class="rate_errr text-danger"></span>
                                                </td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control' readonly/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.st_rate}' class='form-control' onkeyup="changetaxrate($(this).closest('tr'));" , onkeypress="return isNumberKey(event)"/></td>`;
                               
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' readonly/></td>`;

                                tableHtml +=
                                    `<td style="width:120px;">
                                                <input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly/>
                                            </td>`;
                                tableHtml +=
                                    `<td style="width:120px;">
                                        <input type='text'  value='${v.comments}' name='comments[]'  id='comments'  class='form-control'/>
                                </td>`;
                                // tableHtml +=
                                //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;

                            });

                            $('#GridTable').html(tableHtml);
                            TotalAmount();
                            TotalQty();
                            TotalExclVolue();
                            TotalSaleTax();
                            $('#updated_by_name').removeClass('d-none');
                            $('#date').val(response.data[0].purchaseorder.po_date);
                            $('#update_voucher_id').val(response.data[0].purchaseorder.id);
                            $('#purchaser_id').val(response.data[0].purchaseorder.purchaser_id)
                                .select2();
                            $('#remarks').val(response.data[0].purchaseorder.remarks);
                            $('#tax_with_holding').val(response.data[0].purchaseorder
                                .tax_with_holding);
                            $('#address').val(response.data[0].purchaseorder.supplier.address)
                            $('#supplier_id').val(response.data[0].purchaseorder.supplier.id +
                                '_' + response
                                .data[0].purchaseorder.supplier.party_name + '_' + response
                                .data[0].purchaseorder.supplier
                                .address).select2();
                                $('#voucher_no').val(response.data[0].purchaseorder.po);
                                $('#request_no').val(response.data[0].purchaseorder.request_no);
                                document.getElementById("request_no").readOnly = true;
                            $('#remarks').focus();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalExclVolue').text(0);
                            $('#TotalSaleTax').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');
                            $('#update_voucher_no').val(null);
                            $('#date').focus();
                            $('#voucher_no').focus();
                            $('#remarks').val(null);
                            $('#supplier_id').val(null);
                            $('#purchaser_id').val(null);
                        }
                    }
                });
            });


            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());

                $.ajax({
                    url: "{{ URL::to('purchase-order/load/next/record') }}?voucher_no=" +
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

                            $.each(response.data, function(i, v) {

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                tableHtml += `<td>${v.product.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));", onkeypress="return onlyNumberKey(event)"/>
                                                </td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' onkeyup="changerate($(this).closest('tr'));", onkeypress="return onlyNumberKey(event)"/>
                                                <span class="rate_errr text-danger"></span>
                                                </td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control'readonly /></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.sale_tax}' class='form-control' onkeyup="changetaxrate($(this).closest('tr'));", onkeypress="return onlyNumberKey(event)"/></td>`;
                                
                                    tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' readonly/></td>`;

                                tableHtml +=
                                    `<td style="width:120px;">
                                                <input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly/>
                                            </td>`;

                                            tableHtml +=
                                    `<td style="width:120px;">
                                        <input type='text'  value='${v.comments}' name='comments[]'  id='comments'  class='form-control'/>
                                </td>`;
                                // tableHtml +=
                                //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;

                            });

                            $('#GridTable').html(tableHtml);
                            TotalAmount();
                            TotalQty();
                            TotalExclVolue();
                            TotalSaleTax();
                            $('#updated_by_name').removeClass('d-none');
                            $('#date').val(response.data[0].purchaseorder.po_date);
                            $('#update_voucher_id').val(response.data[0].purchaseorder.id);
                            $('#purchaser_id').val(response.data[0].purchaseorder.purchaser_id)
                                .select2();
                            $('#remarks').val(response.data[0].purchaseorder.remarks);
                            $('#tax_with_holding').val(response.data[0].purchaseorder
                                .tax_with_holding);
                            $('#address').val(response.data[0].purchaseorder.supplier.address)
                            $('#supplier_id').val(response.data[0].purchaseorder.supplier.id +
                                '_' + response
                                .data[0].purchaseorder.supplier.party_name + '_' + response
                                .data[0].purchaseorder.supplier
                                .address).select2();
                            $('#voucher_no').val(response.data[0].purchaseorder.po);
                            $('#request_no').val(response.data[0].purchaseorder.request_no);
                            document.getElementById("request_no").readOnly = true;
                            $('#remarks').focus();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalExclVolue').text(0);
                            $('#TotalSaleTax').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');
                            $('#update_voucher_no').val(null);
                            $('#date').focus();
                            $('#voucher_no').focus();
                            $('#remarks').val(null);
                            $('#supplier_id').val(null);
                            $('#purchaser_id').val(null);
                        }
                    }
                });
            });
            // End Here of Load Next Record


            // Load Previous Record
            $('.load-previous-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());

                $.ajax({
                    url: "{{ URL::to('purchase-order/load/previous/record') }}?voucher_no=" +
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

                            $.each(response.data, function(i, v) {

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                tableHtml += `<td>${v.product.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text ' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));", onkeypress="return onlyNumberKey(event)"/>
                                                </td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' onkeyup="changerate($(this).closest('tr'));", onkeypress="return onlyNumberKey(event)"/>
                                                <span class="rate_errr text-danger"></span>
                                                </td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control' readonly/></td>`;
                                
                                    tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='st_rate[]' id='st_rate' value='${v.st_rate}' class='form-control' onkeyup="changetaxrate($(this).closest('tr'));", onkeypress="return onlyNumberKey(event)"/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' readonly/></td>`;

                                tableHtml +=
                                    `<td style="width:120px;">
                                        <input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly/>
                                    </td>`;
                                tableHtml +=
                                    `<td style="width:120px;">
                                        <input type='text'  value='${v.comments}' name='comments[]'  id='comments'  class='form-control'/>
                                </td>`;
                                // tableHtml +=
                                //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;

                            });

                            $('#GridTable').html(tableHtml);
                            TotalAmount();
                            TotalQty();
                            TotalExclVolue();
                            TotalSaleTax();
                            $('#updated_by_name').removeClass('d-none');
                            $('#date').val(response.data[0].purchaseorder.po_date);
                            $('#update_voucher_id').val(response.data[0].purchaseorder.id);
                            $('#purchaser_id').val(response.data[0].purchaseorder.purchaser_id)
                                .select2();
                            $('#remarks').val(response.data[0].purchaseorder.remarks);
                            $('#tax_with_holding').val(response.data[0].purchaseorder
                                .tax_with_holding);
                            $('#address').val(response.data[0].purchaseorder.supplier.address)
                            $('#supplier_id').val(response.data[0].purchaseorder.supplier.id +
                                '_' + response
                                .data[0].purchaseorder.supplier.party_name + '_' + response
                                .data[0].purchaseorder.supplier
                                .address).select2();
                            $('#voucher_no').val(response.data[0].purchaseorder.po);
                            $('#request_no').val(response.data[0].purchaseorder.request_no);
                            document.getElementById("request_no").readOnly = true;
                            $('#remarks').focus();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalExclVolue').text(0);
                            $('#TotalSaleTax').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');
                            $('#update_voucher_no').val(null);
                            $('#date').focus();
                            $('#voucher_no').focus();
                            $('#remarks').val(null);
                            $('#supplier_id').val(null);
                            $('#purchaser_id').val(null);
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
