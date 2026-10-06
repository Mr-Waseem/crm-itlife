@extends('app')
@section('head')
    <title>Purchaser Stock</title>
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
                Purchaser Stock
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Purchaser Stock</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Purchaser Stock</h6>
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
                                    {!! Form::open(['url' => 'purchaser-stock', 'class' => 'form-horizontal', 'id' => 'purchaser-stock-form']) !!}
                                    {!! Form::hidden('created_by', Auth::User()->id, ['id' => 'created_by']) !!}
                                    {!! Form::hidden('type', 'PURCHASE', ['id' => 'type']) !!}
                                    {!! Form::hidden('igp_num', null, ['id' => 'igp_num']) !!}
                                    {!! Form::hidden('update_id', null, ['id' => 'update_id']) !!}
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
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                            <label for="date"><i class="fa fa-caret-right"></i> Vr. Date</label>
                                            {!! Form::date('date', date('Y-m-d'), [
                                                'id' => 'date',
                                                'class' => 'form-control',
                                                'tabindex' => '0',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                            <label for="bill_no"><i class="fa fa-caret-right"></i> Vr.No <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('bill_no', $codes, [
                                                'id' => 'bill_no',
                                                'class' => 'form-control',
                                                'tabindex' => '1',
                                                'autofocus' => 'autofocus',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('bill_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                            <label for="igp_number"><i class="fa fa-caret-right"></i> Inward GatePass #
                                                <span class="text-danger">*</span></label>
                                            {!! Form::select('igp_number', $inwardGatePassNumbers, null, [
                                                'id' => 'igp_number',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('igp_number')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                            <label for="supplier_id"><i class="fa fa-caret-right"></i> Supplier <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('supplier_id', $suppliers, null, [
                                                'id' => 'supplier_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('supplier_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="table-responsive-md mb-2">
                                                <table class="table">
                                                    <thead>
                                                        <tr class="bg-primary text-center">
                                                            <th>Code</th>
                                                            <th>Product</th>
                                                            <th>Unit</th>
                                                            <th>Price</th>
                                                            <th>Qty</th>
                                                            <th>Total</th>
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
                                                                    'tabindex' => '8',
                                                                ]) !!}
                                                                {!! Form::hidden('code2', null, ['id' => 'code2']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select('product_id1', $products, null, [
                                                                    'id' => 'product_id1',
                                                                    'class' => 'form-control select2',
                                                                    'tabindex' => '9',
                                                                ]) !!}
                                                                <span class="product-err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('unit1', null, [
                                                                    'id' => 'unit1',
                                                                    'class' => 'form-control bg-white',
                                                                    'disabled' => 'disabled',
                                                                    'placeholder' => 'Unit',
                                                                    'tabindex' => '10',
                                                                ]) !!}
                                                                {!! Form::hidden('unit2', null, ['id' => 'unit2']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('price1', null, [
                                                                    'id' => 'price1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Price',
                                                                    'tabindex' => '11',
                                                                    'onkeyup' => 'PriceKeyUp($(this).val())',
                                                                ]) !!}
                                                                <span class="price-err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('qty1', null, [
                                                                    'id' => 'qty1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Qty',
                                                                    'tabindex' => '12',
                                                                    'onkeyup' => 'QuantityKeyUp($(this).val())',
                                                                ]) !!}
                                                                <span class="qty-err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('total1', null, [
                                                                    'id' => 'total1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Total',
                                                                    'tabindex' => '13',
                                                                ]) !!}
                                                                <span class="total-err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('comment1', null, [
                                                                    'id' => 'comment1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Comment',
                                                                    'tabindex' => '14',
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
                                                                <th>Product</th>
                                                                <th>Unit</th>
                                                                <th>Price</th>
                                                                <th>Qty</th>
                                                                <th>Total</th>
                                                                <th>Comment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3"><strong>Total</strong></td>
                                                                <td class="bg-info" id="TotalPrice">0</td>
                                                                <td class="bg-primary" id="TotalQty">0</td>
                                                                <td class="bg-success" id="TotalAmount">0</td>
                                                                <td></td>
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
                <form action="{{ URL::to('purchaser-stock/destroy') }}" method="post" id="delete_voucher_form">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Voucher?</p>
                        <input type="hidden" name="delete_bill_no" id="delete_bill_no" value="">
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
            $('#bill_no').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#igp_number").select2('open');
                }
            });

            $('#igp_number').change(function(event) {
                var igp_number = $(this).val();
                if (igp_number) {
                    $('#igp_number').select2('close');

                    // Load Selected IGP Number Record
                    var bill_no = parseInt(igp_number);
                    $.ajax({
                        url: "{{ URL::to('purchaser-stock/load/igp/record') }}?bill_no=" + bill_no,
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
                                var totalPrice = 0;
                                var totalAmount = 0;


                                $.each(response.data, function(i, v) {
                                    var comment = '';
                                    if (v.comments != null) {
                                        comment = v.comments;
                                    }

                                    totalQty += parseInt(v.qty);
                                    totalPrice += parseInt(v.price);
                                    totalAmount += parseInt(v.total_amount);


                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                        `<td>${v.product_code}<input type='hidden' name='code[]' id='code' value='${v.product_code}' /></td>`;
                                    tableHtml += `<td>${v.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product_id}' />
                                                    <input type='hidden' name='product_name[]' id='product_name' value='${v.product_name}' />
                                                </td>`;
                                    tableHtml +=
                                        `<td>${v.unit}<input type='hidden' name='unit[]' id='unit' value='${v.unit}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.price}<input type='hidden' name='price[]' id='price' value='${v.price}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                    tableHtml +=
                                        `<td>${v.qty}<input type='hidden' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                    tableHtml +=
                                        `<td>${v.total_amount}<input type='hidden' name='total[]' id='total' value='${v.total_amount}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                    tableHtml +=
                                        `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
                                    tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;
                                });

                                $('#GridTable').html(tableHtml);
                                $('#TotalQty').text(totalQty);
                                $('#TotalPrice').text(totalPrice);
                                $('#TotalAmount').text(totalAmount);
                                $('#bill_no').focus();
                                $('#igp_num').val(igp_number);
                            } else {
                                $('#show_err').html(
                                    '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                                );

                                $('#GridTable').html(null);
                                $('#TotalQty').text(0);
                                $('#TotalPrice').text(0);
                                $('#TotalAmount').text(0);
                                $('#bill_no').focus();
                                $('#igp_num').val(null);
                            }
                        }
                    });
                    // End Load Selected IGP Number Record
                }
            });

            $('#supplier_id').change(function(event) {
                var supplier = $(this).val();
                if (supplier) {
                    $('#supplier_id').select2('close');
                    $('#product_id1').select2('open');
                }
            });

            $('#product_id1').change(function(event) {
                var product_id = $(this).val();
                if (product_id != null) {
                    $('#code1').val(product_id.split('_')[1]);
                    $('#code2').val(product_id.split('_')[1]);
                    $('#unit1').val(product_id.split('_')[3]);
                    $('#unit2').val(product_id.split('_')[3]);
                    $('#product_id1').select2().trigger('select2:close');
                    $('#price1').focus();
                    $('#qty1').val(1);
                } else {
                    $('#product_id1').focus();
                }
            });
            $('#price1').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var price = parseInt($(this).val());
                    if (price == 0 || price == '') {
                        $('.price-err').text('This field is required & Must be greater than zero');
                        $(this).focus();
                    } else {
                        $('.price-err').text('');
                        $('#qty1').focus();
                    }
                }
            });
            $('#qty1').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = parseInt($(this).val());
                    if (qty <= 0) {
                        $(this).focus();
                        $('.qty-err').text('This field is required & Must be greater than zero');
                    } else {
                        $('.qty-err').text('');
                        $("#comment1").focus();
                    }
                }
            });
            $('#comment1').keypress(function(event) {
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
                $('.product-err').text('This field is required');
                $('#product_id1').focus();
                return false;
            } else {
                $('.product-err').text('');
            }

            var price = parseInt($('#price1').val());
            if (!price || price <= 0) {
                $('.price-err').text('This field is required & Must be greater than zero');
                $('#price1').focus();
                return false;
            } else {
                $('.price-err').text('');
            }

            var qty = parseInt($('#qty1').val());
            if (!qty || qty <= 0) {
                $('.qty-err').text('This field is required & Must be greater than zero');
                $('#qty1').focus();
                return false;
            } else {
                $('.qty-err').text('');
            }

            var pro_id = document.getElementById('product_id1').value.split('_')[0];
            var pro_code = document.getElementById('product_id1').value.split('_')[1];
            var pro_name = document.getElementById('product_id1').value.split('_')[2];
            var pro_unit = document.getElementById('product_id1').value.split('_')[3];
            var price = parseInt(document.getElementById('price1').value);
            var qty = parseInt(document.getElementById('qty1').value);
            var total = parseInt(document.getElementById('total1').value);
            var comment = document.getElementById('comment1').value;
            var TotalPrice = parseInt(document.getElementById('TotalPrice').innerHTML);
            var TotalQty = parseInt(document.getElementById('TotalQty').innerHTML);
            var TotalAmount = parseInt(document.getElementById('TotalAmount').innerHTML);
            var grandTotalQty = TotalQty + qty;
            var grandTotalPrice = TotalPrice + price;
            var grandTotalAmount = TotalAmount + total;


            var tableHtml = `<tr>`;
            tableHtml += `<td>${pro_code}<input type='hidden' name='code[]' id='code' value='${pro_code}' /></td>`;
            tableHtml += `<td>
                            ${pro_name}
                            <input type='hidden' name='product_id[]' id='product_id' value='${pro_id}' />
                            <input type='hidden' name='product_name[]' id='product_name' value='${pro_name}' />
                        </td>`;
            tableHtml += `<td>${pro_unit}<input type='hidden' name='unit[]' id='unit' value='${pro_unit}' /></td>`;
            tableHtml +=
                `<td>${price}<input type='hidden' name='price[]' id='price' value='${price}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
            tableHtml +=
                `<td>${qty}<input type='hidden' name='qty[]' id='qty' value='${qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
            tableHtml +=
                `<td>${total}<input type='hidden' name='total[]' id='total' value='${total}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
            tableHtml += `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
            tableHtml +=
                `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
            tableHtml += `</tr>`;

            $('#GridTable').append(tableHtml);
            $('#code1').val(null);
            $('#product_id1').val(null).select2('open');

            $('#unit1').val(null);
            $('#unit2').val(null);
            $('#price1').val(null);
            $('#qty1').val(null);
            $('#total1').val(null);
            $('#comment1').val(null);

            $('#TotalQty').html(grandTotalQty);
            $('#TotalPrice').html(grandTotalPrice);
            $('#TotalAmount').html(grandTotalAmount);
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
                $('#purchaser-stock-form').submit();
            });
            // End FOrm Submit

            // Reset btn feature
            $('.reset-btn').click(function() {
                $('#update_id').val(null);
                $('#igp_number').val(null).select2();
                $('#supplier_id').val(0).select2();
                $('#status').val(0);
                $('#igp_num').val(null);
                $('#updated_by_name').addClass('d-none');

                $('#GridTable').html(null);
                $('#TotalQty').text(0);
                $('#TotalPrice').text(0);
                $('#TotalAmount').text(0);
                $('#bill_no').focus();
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
            var TotalAmount = parseInt(document.getElementById('TotalAmount').innerText);
            var NewAmount = parseInt($(row).find("td:eq('5')").find('input').val());
            document.getElementById('TotalAmount').innerText = (TotalAmount - NewAmount);

            var TotalQty = parseInt(document.getElementById('TotalQty').innerText);
            var NewQty = parseInt($(row).find("td:eq('4')").find('input').val());
            document.getElementById('TotalQty').innerText = (TotalQty - NewQty);

            var TotalPrice = parseInt(document.getElementById('TotalPrice').innerText);
            var NewPrice = parseInt($(row).find("td:eq('3')").find('input').val());
            document.getElementById('TotalPrice').innerText = (TotalPrice - NewPrice);

            $(row).remove();
        }

        function PriceKeyUp(price) {
            var quantity = document.getElementById('qty1').value;
            if (quantity == '') {
                document.getElementById('total1').value = price;
            } else {
                var total = quantity * price;
                document.getElementById('total1').value = total;
            }
        }

        function QuantityKeyUp(quantity) {
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
                var voucher_no = parseInt($('#bill_no').val());
                $('#delete_bill_no').val(voucher_no);
            });
            // Print Record
            $('.print_record_btn').click(function() {
                    var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                    myModal.toggle();

                    var bill_no = parseInt($('#bill_no').val());
                    var base_url = $('#base_url').val();
                    $.ajax({
                        url: "{{ URL::to('purchaser-stock/print/voucher') }}?bill_no=" +
                            bill_no,
                        type: 'get',
                        beforeSend: function(response) {
                            $('#print-receipt-modal-body').html(
                                '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                            );
                        },
                        success: function(response) {
                            if (response != null && response!=0) {
                                $('#print-receipt-modal-body').html(
                                    `<object data="${base_url}/root/upload/purchaser-stock/${response}" type="application/pdf" width="100%" height="800"></object>`
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
            // Load Entered Record
            $('.load-edit-record').click(function() {
                var bill_no = parseInt($('#bill_no').val());
                $.ajax({
                    url: "{{ URL::to('purchaser-stock/load/record') }}?bill_no=" + bill_no,
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
                            var totalPrice = 0;
                            var totalAmount = 0;


                            $.each(response.data, function(i, v) {
                                var comment = '';
                                if (v.comments != null) {
                                    comment = v.comments;
                                }

                                totalQty += parseInt(v.qty);
                                totalPrice += parseInt(v.price);
                                totalAmount += parseInt(v.total_amount);


                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product_code}<input type='hidden' name='code[]' id='code' value='${v.product_code}' /></td>`;
                                tableHtml += `<td>${v.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product_id}' />
                                                    <input type='hidden' name='product_name[]' id='product_name' value='${v.product_name}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.unit}<input type='hidden' name='unit[]' id='unit' value='${v.unit}' /></td>`;
                                tableHtml +=
                                    `<td>${v.price}<input type='hidden' name='price[]' id='price' value='${v.price}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${v.qty}<input type='hidden' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${v.total_amount}<input type='hidden' name='total[]' id='total' value='${v.total_amount}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty);
                            $('#TotalPrice').text(totalPrice);
                            $('#TotalAmount').text(totalAmount);
                            $('#updated_by_name').removeClass('d-none');

                            $('#update_id').val(response.data[0].purchase_stock.id);
                            $('#date').val(response.data[0].purchase_stock.date);
                            $('#supplier_id').val(response.data[0].purchase_stock.supplier_id)
                                .select2();
                            $('#bill_no').val(response.data[0].purchase_stock.bill_no);
                            $('#bill_no').focus();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalPrice').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_id').val(null);
                            let date = new Date()
                            $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                .getMonth()) + 1) + '-' + date.getDate());
                            $('#supplier_id').val(0).select2();
                            $('#bill_no').focus();
                        }
                    }
                });
            });
            // End Here

            // Load Next Record
            $('.load-next-record').click(function() {
                var bill_no = parseInt($('#bill_no').val());
                $.ajax({
                    url: "{{ URL::to('purchaser-stock/load/next/record') }}?bill_no=" + bill_no,
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
                            var totalPrice = 0;
                            var totalAmount = 0;


                            $.each(response.data, function(i, v) {
                                var comment = '';
                                if (v.comments != null) {
                                    comment = v.comments;
                                }

                                totalQty += parseInt(v.qty);
                                totalPrice += parseInt(v.price);
                                totalAmount += parseInt(v.total_amount);


                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product_code}<input type='hidden' name='code[]' id='code' value='${v.product_code}' /></td>`;
                                tableHtml += `<td>${v.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product_id}' />
                                                    <input type='hidden' name='product_name[]' id='product_name' value='${v.product_name}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.unit}<input type='hidden' name='unit[]' id='unit' value='${v.unit}' /></td>`;
                                tableHtml +=
                                    `<td>${v.price}<input type='hidden' name='price[]' id='price' value='${v.price}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${v.qty}<input type='hidden' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${v.total_amount}<input type='hidden' name='total[]' id='total' value='${v.total_amount}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty);
                            $('#TotalPrice').text(totalPrice);
                            $('#TotalAmount').text(totalAmount);
                            $('#updated_by_name').removeClass('d-none');

                            $('#update_id').val(response.data[0].purchase_stock.id);
                            $('#date').val(response.data[0].purchase_stock.date);
                            $('#supplier_id').val(response.data[0].purchase_stock.supplier_id)
                                .select2();
                                $('#bill_no').val(response.data[0].purchase_stock.bill_no);
                            $('#bill_no').focus();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalPrice').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_id').val(null);
                            let date = new Date()
                            $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                .getMonth()) + 1) + '-' + date.getDate());
                            $('#supplier_id').val(0).select2();
                            $('#bill_no').focus();
                        }
                    }
                });
            });
            // End Here of Load Next Record
            // Load Previous Record
            $('.load-previous-record').click(function() {
                var bill_no = parseInt($('#bill_no').val());
                $.ajax({
                    url: "{{ URL::to('purchaser-stock/load/previous/record') }}?bill_no=" +
                        bill_no,
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
                            var totalPrice = 0;
                            var totalAmount = 0;


                            $.each(response.data, function(i, v) {
                                var comment = '';
                                if (v.comments != null) {
                                    comment = v.comments;
                                }

                                totalQty += parseInt(v.qty);
                                totalPrice += parseInt(v.price);
                                totalAmount += parseInt(v.total_amount);


                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product_code}<input type='hidden' name='code[]' id='code' value='${v.product_code}' /></td>`;
                                tableHtml += `<td>${v.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product_id}' />
                                                    <input type='hidden' name='product_name[]' id='product_name' value='${v.product_name}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.unit}<input type='hidden' name='unit[]' id='unit' value='${v.unit}' /></td>`;
                                tableHtml +=
                                    `<td>${v.price}<input type='hidden' name='price[]' id='price' value='${v.price}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${v.qty}<input type='hidden' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${v.total_amount}<input type='hidden' name='total[]' id='total' value='${v.total_amount}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty);
                            $('#TotalPrice').text(totalPrice);
                            $('#TotalAmount').text(totalAmount);
                            $('#updated_by_name').removeClass('d-none');

                            $('#update_id').val(response.data[0].purchase_stock.id);
                            $('#date').val(response.data[0].purchase_stock.date);
                            $('#supplier_id').val(response.data[0].purchase_stock.supplier_id)
                                .select2();
                                $('#bill_no').val(response.data[0].purchase_stock.bill_no);
                            $('#bill_no').focus();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalPrice').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_id').val(null);
                            let date = new Date()
                            $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                .getMonth()) + 1) + '-' + date.getDate());
                            $('#supplier_id').val(0).select2();
                            $('#bill_no').focus();
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
