@extends('app')
@section('head')
    <title>Request Generate</title>
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
                Request Generate
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Request Generate</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <!-- <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Generate New Request</h6>
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
                                    {!! Form::open(['url' => 'request-generate', 'class' => 'form-horizontal', 'id' => 'request-generate-form']) !!}
                                    {!! Form::hidden('idd', null, ['id' => 'idd']) !!}
                                    {!! Form::hidden('warehouse_id', Auth::User()->warehouse_id, ['id' => 'warehouse_id']) !!}
                                    {!! Form::hidden('warehouse_name', $warehouse->name, ['id' => 'warehouse_name']) !!}
                                    {!! Form::hidden('created_by', Auth::User()->id, ['id' => 'created_by']) !!}
                                    {!! Form::hidden('updated_by', Auth::User()->id, ['id' => 'updated_by']) !!}
                                    {!! Form::hidden('status', 0, ['id' => 'status']) !!}
                                    {!! Form::hidden('type', 'Request Generate', ['id' => 'type']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    
                                    <div class="row">
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            <label for="date"><i class="fa fa-caret-right"></i> Voucher Date. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::date('date', date('Y-m-d'), ['id' => 'date', 'class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            <label for="bill_no"><i class="fa fa-caret-right"></i> Voucher No. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('bill_no', $codes, [
                                                'id' => 'bill_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                            ]) !!}
                                            @error('bill_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <label for="supplier_id"><i class="fa fa-caret-right"></i> Supplier. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('supplier_id', $suppliers, null, [
                                                'id' => 'supplier_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                            ]) !!}
                                            @error('supplier_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <label for="address"><i class="fa fa-caret-right"></i> Address.</label>
                                            {!! Form::text('address', null, [
                                                'id' => 'address',
                                                'class' => 'form-control',
                                                'disabled' => 'disabled',
                                                'placeholder' => 'Address',
                                                'tabindex' => '3',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 mt-1">
                                            <label for="purchaser_id"><i class="fa fa-caret-right"></i> Purchaser. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('purchaser_id', $purchasers, null, [
                                                'id' => 'purchaser_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '4',
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
                                                'tabindex' => '3',
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="table-responsive-md mb-2">
                                                <table class="table">
                                                    <thead>
                                                        <tr class="bg-primary text-center">
                                                            <th>Code</th>
                                                            <th style="width:35%;">Product Description</th>
                                                            <th>Unit</th>
                                                            <th>Qty</th>
                                                            <th>Comments</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="bg-secondary">
                                                            <td>
                                                                {!! Form::text('code1', null, [
                                                                    'id' => 'code1',
                                                                    'class' => 'form-control bg-white',
                                                                    'disabled' => 'disabled',
                                                                    'tabindex' => '5',
                                                                ]) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select('product_id1', $products, null, [
                                                                    'id' => 'product_id1',
                                                                    'class' => 'form-control select2',
                                                                    'tabindex' => '6',
                                                                ]) !!}
                                                                <span class="product-err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('unit1', null, [
                                                                    'id' => 'unit1',
                                                                    'class' => 'form-control bg-white',
                                                                    'disabled' => 'disabled',
                                                                    'placeholder' => 'Unit',
                                                                    'tabindex' => '7',
                                                                ]) !!}
                                                                {!! Form::hidden('unit2', null, ['id' => 'unit2']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('qty1', null, [
                                                                    'id' => 'qty1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Qty',
                                                                    'tabindex' => '8',
                                                                    'onkeypress' => 'return isNumberKey(event)'
                                                                ]) !!}
                                                                <span class="qty-err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('comment1', null, [
                                                                    'id' => 'comment1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Comment',
                                                                    'tabindex' => '9',
                                                                ]) !!}
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
                                                                <th>Product Description</th>
                                                                <th>Unit</th>
                                                                <th>Qty</th>
                                                                <th>Comment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3"><strong>Total</strong></td>
                                                                <td class="bg-primary" id="TotalQty">0</td>
                                                                <td></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="text-xs-right bt-1 mt-5">
                                        <br>
                                        <button type="submit" class="btn btn-info">Submit</button>
                                        <button type="reset" class="btn btn-primary reset_btn">Reset</button>
                                    </div> --}}

                                    <div class="row">
                                       
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
                <form action="{{ URL::to('request-generate/destroy') }}" method="post" id="delete_voucher_form">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Voucher?</p>
                        <input type="hidden" name="delete_voucher_id" id="delete_voucher_id" value="">
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
    <!-- Focus on next field -->
    <script>
        $(document).ready(function() {
            $('#date').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#bill_no').focus();
                }
            });
            $('#bill_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#supplier_id").select2('open');
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
                var purchaser_id = $(this).val();
                if (purchaser_id) {
                    $('#remarks').focus();
                    $('#purchaser_id').select2().trigger('select2:close');
                }
            });
            $('#remarks').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#product_id1").select2('open');
                }
            });
            $('#product_id1').change(function(event) {
                var product_id = $(this).val();
                if (product_id != null) {
                    $('#code1').val(product_id.split('_')[1]);
                    $('#unit1').val(product_id.split('_')[3]);
                    $('#unit2').val(product_id.split('_')[3]);
                    $('#product_id1').select2().trigger('select2:close');
                    $('#qty1').focus();
                } else {
                    $('#product_id1').focus();
                }
            });
            $('#qty1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // var qty = parseInt($(this).val());
                    var qty = $(this).val();

                    if (qty <= 0) {
                        $(this).focus();
                        $('.qty-err').html('This field is required & Must be greater than zero');
                    } else {
                        $('.qty-err').html('');
                        $("#comment1").focus();
                    }
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
                $('.product-err').html('This field is required & Must be greater than zero');
                $('#product_id1').focus();
                return false;
            } else {
                $('.product-err').html('');
            }
            // var qty = parseInt($('#qty1').val());
            var qty = $('#qty1').val();
            if (!qty || qty <= 0) {
                $('.qty-err').html('This field is required & Must be greater than zero');
                $('#qty1').focus();
                return false;
            } else {
                $('.qty-err').html('');
            }

            var pro_id = document.getElementById('product_id1').value.split('_')[0];
            var pro_code = document.getElementById('product_id1').value.split('_')[1];
            var pro_name = document.getElementById('product_id1').value.split('_')[2];
            var pro_unit = document.getElementById('product_id1').value.split('_')[3];
            var qty = document.getElementById('qty1').value;
            var comment = document.getElementById('comment1').value;
            var TotalQty = parseInt(document.getElementById('TotalQty').innerHTML);
            var grandTotalQty = TotalQty + parseFloat(qty);

            var tableHtml = `<tr>`;
            tableHtml += `<td>${pro_code}<input type='hidden' name='code[]' id='code' value='${pro_code}' /></td>`;
            tableHtml +=
                `<td>${pro_name}<input type='hidden' name='product_id[]' id='product_id' value='${pro_id}' /></td>`;
            tableHtml += `<td>${pro_unit}<input type='hidden' name='unit[]' id='unit' value='${pro_unit}' /></td>`;
            tableHtml +=
                `<td>${qty}<input type='hidden' name='qty[]' id='qty' value='${qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
            tableHtml += `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
            tableHtml +=
                `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
            tableHtml += `</tr>`;

            $('#GridTable').append(tableHtml);
            $('#code1').val(null);
            $('#product_id1').focus();
            $('#product_id1').val(null).select2('close');
            $('#product_id1').select2('open');
            $('#unit1').val(null);
            $('#unit2').val(null);
            $('#qty1').val(null);
            $('#comment1').val(null);

            $('#TotalQty').html(grandTotalQty);
        }
    </script>
    <!-- End Append New Data on Table -->


    <!-- Searchable Select2 -->
    <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2, .select2-multiple").select2({
            width: "100%"
        });
    </script>
    <!-- End Searchable Select2 -->

    <script>
        $(document).ready(function() {
            // Hide Alert Notification After 3 Seconds
            setInterval(() => {
                $('.alert').hide();
            }, 3000);
            // End Hide Alert Notification After 3 Seconds


            // Form Submit
            $('.submit-form').click(function() {
                $('#request-generate-form').submit();
            });
            // End FOrm Submit

            // Reset btn feature
            $('.reset-btn').click(function() {
                $('#supplier_id').val(null).select2();
                $('#updated_by_name').addClass('d-none');
            });
            // End Reset btn feature
        });
    </script>


    <!-- Main Features -->
    <script>
        $(document).ready(function() {
            // Delete Record
            $('.delete_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('delete-record-modal'), {});
                myModal.toggle();
                var voucher_no = parseInt($('#bill_no').val());
                $('#delete_voucher_id').val(voucher_no);
            });
             // Print Record
             $('.print_record_btn').click(function() {
                // alert("ddd")
                var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                myModal.toggle();

                var voucher_no = parseInt($('#bill_no').val());
                //   alert(voucher_no);
                var base_url = $('#base_url').val();
                $.ajax({
                    url: "{{ URL::to('request-generate/print/voucher') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    beforeSend: function(response) {
                        $('#print-receipt-modal-body').html(
                            '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                        );
                    },
                    success: function(response) {
                       // console.log(response)
                       if (response != null && response!=0) {
                            $('#print-receipt-modal-body').html(
                                `<object data="${base_url}/resources/upload/request-generate/${response}" type="application/pdf" width="100%" height="800"></object>`
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

    <!-- OnChange Qty -->
    <script>
        function changeQty(row) {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                // console.log(tableData.rows[i].cells[3].getElementsByTagName('input')[0].value);
                sum += parseInt(tableData.rows[i].cells[3].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalQty').innerText = sum;
        }

        function DeleteRow(row) {
            var TotalQty = parseInt(document.getElementById('TotalQty').innerText);
            var NewQty = parseInt($(row).find("td:eq('3')").find('input').val());
            document.getElementById('TotalQty').innerText = (TotalQty - NewQty);
            $(row).remove();
        }
    </script>
    <!-- End OnChange Qty -->

    <!-- Load & Edit Record -->
    <script>
        $(document).ready(function() {
            $('.load-edit-record').click(function() {
                var voucher_no = parseInt($('#bill_no').val());
                $.ajax({
                    url: "{{ URL::to('request-generate/load/record') }}?voucher_no=" + voucher_no,
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

                            $.each(response.data.request_generate_details, function(i, v) {
                                var comment = '';
                                if (v.comments != null) {
                                    comment = v.comments;
                                }
                                totalQty += parseInt(v.qty);
                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product_code}<input type='hidden' name='code[]' id='code' value='${v.product_code}' /></td>`;
                                tableHtml +=
                                    `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product_id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}</td>`;
                                tableHtml +=
                                    `<td>${v.qty}<input type='hidden' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${v.comments}<input type='hidden' name='comments[]' id='comments' value='${v.comments}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty);
                            $('#idd').val(response.data.bill_no);
                            $('#date').val(response.data.date);
                            $('#updated_by_name').removeClass('d-none');
                            $('#supplier_id').val(response.data.supplier.id + '_' + response
                                .data.supplier.party_name + '_' + response.data.supplier
                                .address).select2();
                            $('#purchaser_id').val(response.data.purchaser_id).select2();
                            $('#remarks').val(response.data.remarks);
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#idd').val(null);
                            $('#date').val(null);
                            $('#updated_by_name').addClass('d-none');
                            $('#supplier_id').val(null);
                            $('#purchaser_id').val(null).select2();
                        }
                    }
                });
            });
            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#bill_no').val());
                $.ajax({
                    url: "{{ URL::to('request-generate/load/next/record') }}?voucher_no=" +
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

                            $.each(response.data.request_generate_details, function(i, v) {
                                var comment = '';
                                if (v.comments != null) {
                                    comment = v.comments;
                                }
                                totalQty += parseInt(v.qty);
                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product_code}<input type='hidden' name='code[]' id='code' value='${v.product_code}' /></td>`;
                                tableHtml +=
                                    `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product_id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}</td>`;
                                tableHtml +=
                                    `<td>${v.qty}<input type='hidden' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${v.comments}<input type='hidden' name='comments[]' id='comments' value='${v.comments}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty);
                            $('#idd').val(response.data.bill_no);
                            $('#bill_no').val(response.data.bill_no);
                            $('#date').val(response.data.date);
                            $('#updated_by_name').removeClass('d-none');
                            $('#supplier_id').val(response.data.supplier.id + '_' + response
                                .data.supplier.party_name + '_' + response.data.supplier
                                .address).select2();
                            $('#purchaser_id').val(response.data.purchaser_id).select2();
                            $('#remarks').val(response.data.remarks);
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#idd').val(null);
                            $('#date').val(null);
                            $('#updated_by_name').addClass('d-none');
                            $('#supplier_id').val(null);
                            $('#purchaser_id').val(null).select2();
                        }
                    }
                });
            });
            // End Here of Load Next Record
            // Load Previous Record
            $('.load-previous-record').click(function() {
                var voucher_no = parseInt($('#bill_no').val());
                $.ajax({
                    url: "{{ URL::to('request-generate/load/previous/record') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        console.log(response.data);
                        if (response.data != '') {
                            var tableHtml = '';
                            var totalQty = 0;

                            $.each(response.data.request_generate_details, function(i, v) {
                                var comment = '';
                                if (v.comments != null) {
                                    comment = v.comments;
                                }
                                totalQty += parseFloat(v.qty);
                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product_code}<input type='hidden' name='code[]' id='code' value='${v.product_code}' /></td>`;
                                tableHtml +=
                                    `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product_id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}</td>`;
                                tableHtml +=
                                    `<td>${v.qty}<input type='hidden' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${v.comments}<input type='hidden' name='comments[]' id='comments' value='${v.comments}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty);
                            $('#idd').val(response.data.bill_no);
                            $('#bill_no').val(response.data.bill_no);
                            $('#date').val(response.data.date);
                            $('#updated_by_name').removeClass('d-none');
                            $('#supplier_id').val(response.data.supplier.id + '_' + response
                                .data.supplier.party_name + '_' + response.data.supplier
                                .address).select2();
                            $('#purchaser_id').val(response.data.purchaser_id).select2();
                            $('#remarks').val(response.data.remarks);
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#idd').val(null);
                            $('#date').val(null);
                            $('#updated_by_name').addClass('d-none');
                            $('#supplier_id').val(null);
                            $('#purchaser_id').val(null).select2();
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
