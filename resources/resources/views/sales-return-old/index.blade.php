@extends('app')
@section('head')
    <title>Sales Return Invoice</title>
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
                Sales Return Invoice
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Sales Return Invoice</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Sales Return Invoice</h6>
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
                                    {!! Form::open(['url' => 'sales-return', 'class' => 'form-horizontal', 'id' => 'sales-return-form']) !!}
                                    {!! Form::hidden('update_voucher_id', null, ['id' => 'update_voucher_id']) !!}
                                    {!! Form::hidden('type', 'Sales Return', ['id' => 'type']) !!}
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
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="date"><i class="fa fa-caret-right"></i> Voucher Date</label>
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
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No#<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $codes, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                                'autofocus' => 'autofocus',
                                            ]) !!}
                                            <span class="text-danger voucher_no_err"></span>
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
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
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
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
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="dcn_no"><i class="fa fa-caret-right"></i> DC No.<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('dcn_no', $DeliveryChallan, null, [
                                                'id' => 'dcn_no',
                                                'class' => 'form-control select2',
                                                'tabindex' => '4',
                                            ]) !!}
                                            <span class="text-danger dcn_no_err"></span>
                                            {!! Form::hidden('dcn_no1', null, ['id' => 'dcn_no1']) !!}
                                            {!! Form::text('dcn_no2', null, [
                                                'id' => 'dcn_no2',
                                                'class' => 'form-control',
                                                'style' => 'display:none',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            @error('dcn_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="transaction_type"><i class="fa fa-caret-right"></i> Type<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('transaction_type', $transaction_type, null, [
                                                'id' => 'transaction_type',
                                                'class' => 'form-control select2',
                                                'tabindex' => '5',
                                            ]) !!}
                                            <span class="text-danger transaction_type_err"></span>
                                            @error('transaction_type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i> Remarks<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('remarks', null, [
                                                'id' => 'remarks',
                                                'class' => 'form-control',
                                                'tabindex' => '6',
                                                'placeholder' => 'Remarks',
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
                                                            <th>Product</th>
                                                            <th>Unit</th>
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
                                                                {!! Form::text('unit1', null, [
                                                                    'id' => 'unit1',
                                                                    'class' => 'form-control bg-white',
                                                                    'disabled' => 'disabled',
                                                                    'placeholder' => 'Unit',
                                                                    'tabindex' => '9',
                                                                ]) !!}
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
                                                                <th class="d-none">Code</th>
                                                                <th>Product</th>
                                                                <th>Unit</th>
                                                                <th>Qty</th>
                                                                <th>Rate</th>
                                                                <th>Amount</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2"><strong>Total</strong></td>
                                                                <td class="bg-primary" id="TotalQty">0</td>
                                                                <td></td>
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
                <form action="{{ URL::to('sales-return/delete-voucher') }}" method="post" id="delete_voucher_form">
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

    <!-- Focus on next field -->
    <script>
        $(document).ready(function() {
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
                    $('#dcn_no').select2('open');
                }
            });
            $('#dcn_no').change(function(event) {
                var dcn_no = $(this).val();
                if (dcn_no) {
                    $('#dcn_no').select2().trigger('select2:close');
                    $('#transaction_type').select2('open');
                }
            });
            $('#transaction_type').change(function(event) {
                var transaction_type = $(this).val();
                if (transaction_type) {
                    $('#transaction_type').select2().trigger('select2:close');
                    $('#remarks').focus();
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
                    $('#unit1').val(product_id.split('_')[2]);
                    $('#product_id1').select2().trigger('select2:close');
                    $('#price1').val(parseInt(product_id.split('_')[3]));
                    $('#total1').val(parseInt(product_id.split('_')[3]));
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

            var pro_id = document.getElementById('product_id1').value.split('_')[0];
            var pro_name = document.getElementById('product_id1').value.split('_')[1];
            var pro_unit = document.getElementById('product_id1').value.split('_')[2];
            var pro_cost = parseInt(document.getElementById('product_id1').value.split('_')[4]);
            var price = parseInt(document.getElementById('price1').value);
            var qty = parseInt(document.getElementById('qty1').value);
            var total = parseInt(document.getElementById('total1').value);
            var TotalQty = parseInt(document.getElementById('TotalQty').innerHTML);
            var TotalAmount = parseInt(document.getElementById('TotalAmount').innerHTML);

            var cost_amount = pro_cost * qty;
            var sale_amount = price * qty;

            var grandTotalQty = TotalQty + qty;
            var grandTotalAmount = TotalAmount + total;


            var tableHtml = `<tr>`;
            tableHtml += `<td>
                            ${pro_name}
                            <input type='hidden' name='product_id[]' id='product_id' value='${pro_id}' />
                            <input type='hidden' name='product_name[]' id='product_name' value='${pro_name}' />
                            <input type='hidden' name='product_cost[]' id='product_cost' value='${pro_cost}' />
                            <input type='hidden' name='product_unit_id[]' id='product_unit_id' value='1' />
                            <input type='hidden' name='discount_id[]' id='discount_id' value='0' />
                        </td>`;
            tableHtml += `<td>${pro_unit}</td>`;
            tableHtml +=
                `<td>${qty}<input type='hidden' name='qty[]' id='qty' value='${qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
            tableHtml +=
                `<td>${price}<input type='hidden' name='price[]' id='price' value='${price}' class='form-control' /></td>`;
            tableHtml +=
                `<td>${total}<input type='hidden' name='total[]' id='total' value='${total}' class='form-control' /></td>`;
            tableHtml +=
                `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
            tableHtml += `</tr>`;

            $('#GridTable').append(tableHtml);
            $('#code1').val(null);

            $('#unit1').val(null);
            $('#unit2').val(null);
            $('#price1').val(null);
            $('#qty1').val(null);
            $('#total1').val(null);


            $('#TotalQty').html(grandTotalQty);
            $('#TotalAmount').html(grandTotalAmount);

            $('#product_id1').select2('open');
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
                var party_name = $('#party_name').val();
                var dcn_no = $('#dcn_no').val();
                var transaction_type = $('#transaction_type').val();

                $('.voucher_no_err').text('');
                $('.party_name_err').text('');
                $('.dcn_no_err').text('');
                $('.transaction_type_err').text('');


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
                if(!dcn_no)
                {
                    $('.dcn_no_err').text('The DC field is required.');
                    return false;
                }else
                if(!transaction_type)
                {
                    $('.transaction_type_err').text('The Transaction field is required.');
                    return false;
                }else{
                    $('#sales-return-form').submit();
                }
            });
            // End FOrm Submit

            // Reset btn feature
            $('.reset-btn').click(function() {
                $('#party_name').val(null).select2();
                $('#transaction_type').val(null).select2();
                $('#dcn_no').select2().next().show();
                $('#dcn_no1').val(null);
                $('#dcn_no2').val(null).css('display', 'none');
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
            var TotalAmount = parseInt(document.getElementById('TotalAmount').innerText);
            var NewAmount = parseInt($(row).find("td:eq('5')").find('input').val());
            document.getElementById('TotalAmount').innerText = (TotalAmount - NewAmount);

            var TotalQty = parseInt(document.getElementById('TotalQty').innerText);
            var NewQty = parseInt($(row).find("td:eq('3')").find('input').val());
            document.getElementById('TotalQty').innerText = (TotalQty - NewQty);

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
                        url: "{{ URL::to('sales-return/print/voucher') }}?voucher_no=" +
                            voucher_no,
                        type: 'get',
                        beforeSend: function(response) {
                            $('#print-receipt-modal-body').html(
                                '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                            );
                        },
                        success: function(response) {
                            if (response != null && response!=0) {
                                $('#print-receipt-modal-body').html(
                                    `<object data="${base_url}/root/upload/sales-return/${response}" type="application/pdf" width="100%" height="800"></object>`
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
                    url: "{{ URL::to('sales-return/load/record') }}?voucher_no=" + voucher_no,
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
                            var totalAmount = 0;


                            $.each(response.data, function(i, v) {
                                totalQty += parseInt(v.qty_in);
                                totalAmount += parseInt(v.sale_amount);

                                tableHtml += `<tr>`;
                                tableHtml += `<td>
                                                ${v.products.product_name}
                                                <input type='hidden' name='product_id[]' id='product_id' value='${v.products.id}' />
                                                <input type='hidden' name='product_name[]' id='product_name' value='${v.products.product_name}' />
                                                <input type='hidden' name='product_cost[]' id='product_cost' value='${v.products.product_cost}' />
                                                <input type='hidden' name='product_unit_id[]' id='product_unit_id' value='1' />
                                                <input type='hidden' name='discount_id[]' id='discount_id' value='0' />
                                            </td>`;
                                tableHtml +=
                                    `<td>${v.products.uom}</td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.qty_in)}<input type='hidden' name='qty[]' id='qty' value='${v.qty_in}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.sale_rate)}<input type='hidden' name='price[]' id='price' value='${parseInt(v.sale_rate)}' class='form-control' /></td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.sale_amount)}<input type='hidden' name='total[]' id='total' value='${parseInt(v.sale_amount)}' class='form-control' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty);
                            $('#TotalAmount').text(totalAmount);
                            $('#updated_by_name').removeClass('d-none');

                            $('#update_voucher_id').val(response.data[0].stock.id);
                            $('#date').val(response.data[0].stock.date);
                            $('#voucher_no').val(response.data[0].stock.voucher_no);
                            $('#voucher_no').focus();
                            $('#party_name').val(response.data[0].stock.parties.id + '_' +
                                response.data[0].stock.parties.party_name + '_' + response
                                .data[0].stock.parties.address).select2();
                            $('#party_id').val(response.data[0].stock.party_id);
                            $('#address').val(response.data[0].stock.parties.address);
                            $('#transaction_type').val(response.data[0].stock.transaction_type)
                                .select2();
                            $('#dcn_no').select2().next().hide();
                            $('#dcn_no1').val(response.data[0].stock.dcn_no);
                            $('#dcn_no2').val(response.data[0].stock.dcn_no).css('display',
                                'block');
                            $('#remarks').val(response.data[0].stock.remarks);
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
                            $('#transaction_type').val(null).select2();
                            $('#dcn_no').select2().next().show();
                            $('#dcn_no1').val(null);
                            $('#dcn_no2').val(null).css('display', 'none');
                            $('#remarks').val(null);
                        }
                    }
                });
            });


            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('sales-return/load/next/record') }}?voucher_no=" + voucher_no,
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
                            var totalAmount = 0;


                            $.each(response.data, function(i, v) {
                                totalQty += parseInt(v.qty_in);
                                totalAmount += parseInt(v.sale_amount);

                                tableHtml += `<tr>`;
                                tableHtml += `<td>
                                                ${v.products.product_name}
                                                <input type='hidden' name='product_id[]' id='product_id' value='${v.products.id}' />
                                                <input type='hidden' name='product_name[]' id='product_name' value='${v.products.product_name}' />
                                                <input type='hidden' name='product_cost[]' id='product_cost' value='${v.products.product_cost}' />
                                                <input type='hidden' name='product_unit_id[]' id='product_unit_id' value='1' />
                                                <input type='hidden' name='discount_id[]' id='discount_id' value='0' />
                                            </td>`;
                                tableHtml +=
                                    `<td>${v.products.uom}</td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.qty_in)}<input type='hidden' name='qty[]' id='qty' value='${v.qty_in}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.sale_rate)}<input type='hidden' name='price[]' id='price' value='${parseInt(v.sale_rate)}' class='form-control' /></td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.sale_amount)}<input type='hidden' name='total[]' id='total' value='${parseInt(v.sale_amount)}' class='form-control' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty);
                            $('#TotalAmount').text(totalAmount);
                            $('#updated_by_name').removeClass('d-none');

                            $('#update_voucher_id').val(response.data[0].stock.id);
                            $('#date').val(response.data[0].stock.date);
                            $('#voucher_no').val(response.data[0].stock.voucher_no);
                            $('#voucher_no').focus();
                            $('#party_name').val(response.data[0].stock.parties.id + '_' +
                                response.data[0].stock.parties.party_name + '_' + response
                                .data[0].stock.parties.address).select2();
                            $('#party_id').val(response.data[0].stock.party_id);
                            $('#address').val(response.data[0].stock.parties.address);
                            $('#transaction_type').val(response.data[0].stock.transaction_type)
                                .select2();
                            $('#dcn_no').select2().next().hide();
                            $('#dcn_no1').val(response.data[0].stock.dcn_no);
                            $('#dcn_no2').val(response.data[0].stock.dcn_no).css('display',
                                'block');
                            $('#remarks').val(response.data[0].stock.remarks);
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
                            $('#transaction_type').val(null).select2();
                            $('#dcn_no').select2().next().show();
                            $('#dcn_no1').val(null);
                            $('#dcn_no2').val(null).css('display', 'none');
                            $('#remarks').val(null);
                        }
                    }
                });
            });
            // End Here of Load Next Record


            // Load Previous Record
            $('.load-previous-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('sales-return/load/previous/record') }}?voucher_no=" +
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
                            var totalAmount = 0;


                            $.each(response.data, function(i, v) {
                                totalQty += parseInt(v.qty_in);
                                totalAmount += parseInt(v.sale_amount);

                                tableHtml += `<tr>`;
                                tableHtml += `<td>
                                                ${v.products.product_name}
                                                <input type='hidden' name='product_id[]' id='product_id' value='${v.products.id}' />
                                                <input type='hidden' name='product_name[]' id='product_name' value='${v.products.product_name}' />
                                                <input type='hidden' name='product_cost[]' id='product_cost' value='${v.products.product_cost}' />
                                                <input type='hidden' name='product_unit_id[]' id='product_unit_id' value='1' />
                                                <input type='hidden' name='discount_id[]' id='discount_id' value='0' />
                                            </td>`;
                                tableHtml +=
                                    `<td>${v.products.uom}</td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.qty_in)}<input type='hidden' name='qty[]' id='qty' value='${v.qty_in}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.sale_rate)}<input type='hidden' name='price[]' id='price' value='${parseInt(v.sale_rate)}' class='form-control' /></td>`;
                                tableHtml +=
                                    `<td>${parseInt(v.sale_amount)}<input type='hidden' name='total[]' id='total' value='${parseInt(v.sale_amount)}' class='form-control' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty);
                            $('#TotalAmount').text(totalAmount);
                            $('#updated_by_name').removeClass('d-none');

                            $('#update_voucher_id').val(response.data[0].stock.id);
                            $('#date').val(response.data[0].stock.date);
                            $('#voucher_no').val(response.data[0].stock.voucher_no);
                            $('#voucher_no').focus();
                            $('#party_name').val(response.data[0].stock.parties.id + '_' +
                                response.data[0].stock.parties.party_name + '_' + response
                                .data[0].stock.parties.address).select2();
                            $('#party_id').val(response.data[0].stock.party_id);
                            $('#address').val(response.data[0].stock.parties.address);
                            $('#transaction_type').val(response.data[0].stock.transaction_type)
                                .select2();
                            $('#dcn_no').select2().next().hide();
                            $('#dcn_no1').val(response.data[0].stock.dcn_no);
                            $('#dcn_no2').val(response.data[0].stock.dcn_no).css('display',
                                'block');
                            $('#remarks').val(response.data[0].stock.remarks);
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
                            $('#transaction_type').val(null).select2();
                            $('#dcn_no').select2().next().show();
                            $('#dcn_no1').val(null);
                            $('#dcn_no2').val(null).css('display', 'none');
                            $('#remarks').val(null);
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
