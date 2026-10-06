@extends('app')
@section('head')
    <title>Sales Invoice</title>
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
            <h1>Sales Invoice</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Sales Invoice</a></li>
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
                                    {!! Form::open(['url' => 'direct-sales', 'class' => 'form-horizontal', 'id' => 'sales-voucher-form']) !!}
                                    {!! Form::hidden('update_voucher_id', null, ['id' => 'update_voucher_id']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
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
                                                    {!! Form::text('voucher_no', $codes, [
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
                                        </div>
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
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1 dcn_no1">
                                            <label for="dcn_id" style="background-color:#666ee8;color:white;"><i class="fa fa-caret-right"></i>Choose Customer.<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('party_id', $customers, null, [
                                                'id' => 'party_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '4',
                                            ]) !!}
                                            <span class="text-danger party_name_err"></span>
                                            @error('dcn_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
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
                                     
                                        <!-- <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                            <label for="warehouse_id"><i class="fa fa-caret-right"></i> Warehouse <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('warehouse_id', $warehouse, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control',
                                                'tabindex' => '9',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            @error('warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div> -->
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="table-responsive-md mb-2">
                                                <table class="table">
                                                    <thead>
                                                        <tr class="bg-primary text-left">
                                                            <th class="d-none">Code</th>
                                                            <th style="width: 40%;">Product Name</th>
                                                            <th>Unit</th>
                                                            <th>Thinckess</th>
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
                                                                {!! Form::hidden('unit2', null, ['id' => 'unit2']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('thickness1', null, [
                                                                    'id' => 'thickness1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Thickness',
                                                                    'onkeypress' => 'return isNumberKey(event)'
                                                                ]) !!}
                                                                <span class="qty_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('qty1', null, [
                                                                    'id' => 'qty1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Qty',
                                                                    'tabindex' => '10',
                                                                    'onkeyup' => 'QuantityKeyUp($(this).val())',
                                                                    'onkeypress' => 'return isNumberKey(event)'
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
                                                                    'onkeypress' => 'return isNumberKey(event)'
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
                                                                <th>Code</th>
                                                                <th style="width:25%;">Product</th>
                                                                <th>Unit</th>
                                                                <th>Thickness</th>
                                                                <th>Qty</th>
                                                                <th>Rate</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="4"><strong>Total</strong></td>
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
                <form action="{{ URL::to('direct-sales/delete-voucher') }}" method="post" id="delete_voucher_form">
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
    <script>
        function TotalAmount() {
            var tableData = document.getElementById('GridTable');

            var sum =0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[6].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalAmount').innerText = sum.toLocaleString('en-US');
        }

        function TotalSaleQty() {
            var tableData = document.getElementById('GridTable');
            var sum =0;
          
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[4].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalQty').innerText = sum.toLocaleString('en-US');
        }


        function changerate(row) {
            // alert(row);
            var saleqty = $(row).find("td:eq('4')").find('input').val();
            var rate = $(row).find("td:eq('5')").find('input').val();
            // var exclValue;
            // var stValue;
            var totalAmount;
            if (rate == null || rate == 0) {
                totalAmount = 0
            } else {
                totalAmount = rate * saleqty
            }
            $(row).find("td:eq('6')").find('input').val(parseFloat(totalAmount));
            TotalSaleQty();
            TotalAmount();
        }

        // function changeQty(row) {

        //     var saleqty = $(row).find("td:eq('4')").find('input').val();
        //     var rate = $(row).find("td:eq('5')").find('input').val();
        //     // var exclValue;
        //     // var stValue;
        //     var totalAmount;
        //     if (rate == null || rate == 0) {
        //         totalAmount = 0
        //     } else {
        //         totalAmount = rate * saleqty
        //         // totalAmount =  exclValue;
        //     }
        //     $(row).find("td:eq('6')").find('input').val(parseFloat(totalAmount));
        //     TotalAmount();

        //     // var tableData = document.getElementById('GridTable');
        //     // var sum = 0;
        //     // for (var i = 0; i < tableData.rows.length; i++) {
        //     //     sum += parseInt(tableData.rows[i].cells[3].getElementsByTagName('input')[0].value);
        //     // }
        //     // document.getElementById('TotalQty').innerText = sum;
        // }

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
            $('#voucher_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#date').focus();
                }
            });

            $('#date').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#party_id").select2('open');
                }
            });
            $('#party_id').change(function(event) {
                var dcn = $(this).val();
                if (dcn) {
                    $('#party_id').select2().trigger('select2:close');
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
                if (product_id) {
                    $('#code1').val(product_id.split('_')[1]);
                    // $('#code2').val(product_id.split('_')[1]);
                    $('#unit1').val(product_id.split('_')[3]);
                    $('#product_id1').select2().trigger('select2:close');
                    $('#price1').val(parseInt(product_id.split('_')[4]));
                    $('#total1').val(parseInt(product_id.split('_')[4]));
                    $('#qty1').val(1);
                    $('#thickness1').focus();
                }
            });
            $('#thickness1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#qty1").select();
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
                        $('#price1').select();
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
    <script>
        function loadeditSale(){
            // var consignment_no = parseInt($('#consignment_no').val());
            // alert(consignment_no);
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
            var pro_code = document.getElementById('product_id1').value.split('_')[1];
            var pro_name = document.getElementById('product_id1').value.split('_')[2];
            var pro_unit = document.getElementById('product_id1').value.split('_')[3];
            var price = parseFloat(document.getElementById('price1').value);
            var qty = parseFloat(document.getElementById('qty1').value);
            var thickness = parseFloat(document.getElementById('thickness1').value);
            var total = parseFloat(document.getElementById('total1').value);
            var tableHtml = `<tr>`;
            tableHtml +=`<td>${pro_code}</td>`;
            var app = @json($Accountsbelow);
            var option = `<option value="${pro_id}" selected>${pro_code}-${pro_name}</option>`;
            $.each(app, function(i, v) {
                    option +=`<option value="${v.id}">${v.code} - ${v.product_name}</option>`;
                });
            tableHtml +=`<td><select class="form-control grades" name="product_id[]" id="product_id">
                ${option}
            </select>
                </td>`;
            tableHtml += `<td>${pro_unit}</td>`;
            tableHtml +=`<td><input type='text' name='thickness[]' id='thickness' value='${thickness}' class='form-control' /></td>`;
            tableHtml +=`<td><input type='text' name='qty[]' id='qty' value='${qty}' class='form-control' onkeyup="changerate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
            tableHtml +=`<td><input type='text' name='price[]' id='price' value='${price}' class='form-control' onkeyup="changerate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
            tableHtml +=`<td><input type='text' name='total[]' id='total' value='${total}' class='form-control' readonly/></td>`;
            tableHtml +=`<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
            tableHtml += `</tr>`;

            $('#GridTable').append(tableHtml);
            $(".grades").select2({
                            placeholder: "Select Account",
                            allowClear: true
                            });
            TotalSaleQty();
            TotalAmount();
            
            $('#code1').val(null);
            $('#unit1').val(null);
            $('#thickness1').val(null);
            $('#unit2').val(null);
            $('#price1').val(null);
            $('#qty1').val(null);
            $('#total1').val(null);


            // $('#TotalQty').html(grandTotalQty);
            // $('#TotalAmount').html(grandTotalAmount);

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
                // var dcn_id = $('#dcn_id').val();
                var party_name = $('#party_id').val();
                
                $('.voucher_no_err').text('');
                // $('.dcn_id_err').text('');
                $('.party_name_err').text('');


                if(!voucher_no)
                {
                    // alert("1")
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


        function EnterKeyBoard(row)
        {
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
            // var TotalAmount = parseInt(document.getElementById('TotalAmount').innerText);
            // var NewAmount = parseInt($(row).find("td:eq('5')").find('input').val());
            // document.getElementById('TotalAmount').innerText = (TotalAmount - NewAmount);

            // var TotalQty = parseInt(document.getElementById('TotalQty').innerText);
            // var NewQty = parseInt($(row).find("td:eq('3')").find('input').val());
            // document.getElementById('TotalQty').innerText = (TotalQty - NewQty);

            $(row).remove();
            TotalSaleQty();
            TotalAmount();
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
                    url: "{{ URL::to('direct-sales/print/voucher') }}?voucher_no=" +
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
            $('.load-edit-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('direct-sales/load/record') }}",
                    type: 'get',
                    data:{voucher_no:voucher_no},
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
                                    tableHtml +=`<td>${v.product.code}</td>`;
                                    // tableHtml += `<td>
                                    //    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                    //    <input type='text' value='${v.product.product_name}' class='form-control' readonly />
                                    //     </td>`;
                                    var app = @json($Accountsbelow);
                                        var option = `<option value="${v.product.id}" selected>${v.product.code} - ${v.product.product_name}</option>`;
                                        $.each(app, function(i, v) {
                                            option +=`<option value="${v.id}">${v.code} - ${v.product_name}</option>`;
                                        });
                                        tableHtml +=`<td><select class="form-control grades" name="product_id[]" id="product_id">
                                        ${option}
                                        </select>
                                            </td>`;

                                    tableHtml +=`<td>${v.product.uom}</td>`;
                                    tableHtml +=`<td>
                                            <input type='text' name='thickness[]' id='thickness' value='${v.thickness}' class='form-control' />
                                        </td>`;
                                    tableHtml +=
                                        `<td><input type='text' value='${v.sale_qty}' id="qty" name="qty[]" class='form-control' onkeyup="changerate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='price[]' id='price' value='${v.rate}' class='form-control' style="width:120px;" onkeyup="changerate($(this).closest('tr'));" onchange="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
                                        </td>`;
                                    tableHtml +=`<td style="width:130px;">
                                        <input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                                        </td>`;
                                    tableHtml += `</tr>`;
                                });
                                $('#GridTable').html(tableHtml);
                                $(".grades").select2({
                            placeholder: "Select Account",
                            allowClear: true
                            });
                                // $('#updated_by_name').removeClass('d-none');
                                $('#update_voucher_id').val(response.data.id);
                                $('#date').val(response.data.date);
                                $('#voucher_no').val(response.data.voucher_no);
                                $('#voucher_no').focus();
                                $('#remarks').val(response.data.remarks);
                                // $('#party_name').val(response.data[0].party.id + "_" + response
                                //     .data[0].party.party_name + "_" + response.data[0].party
                                //     .address).select2();
                                $('#party_id').val(response.data.party.id).select2();
                                $('.ReloadOrder').removeClass('d-none');
                                TotalAmount();
                                TotalSaleQty();  
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
                $.ajax({
                    url: "{{ URL::to('direct-sales/load/next/record') }}",
                    type: 'get',
                    data:{voucher_no:voucher_no},
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
                                    tableHtml +=`<td>${v.product.code}</td>`;
                                    // tableHtml += `<td>
                                    //    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                    //    <input type='text' value='${v.product.product_name}' class='form-control' readonly />
                                    //     </td>`;
                                    var app = @json($Accountsbelow);
                                        var option = `<option value="${v.product.id}" selected>${v.product.code} - ${v.product.product_name}</option>`;
                                        $.each(app, function(i, v) {
                                            option +=`<option value="${v.id}">${v.code} - ${v.product_name}</option>`;
                                        });
                                        tableHtml +=`<td><select class="form-control grades" name="product_id[]" id="product_id">
                                        ${option}
                                        </select>
                                            </td>`;

                                    tableHtml +=`<td>${v.product.uom}</td>`;
                                    tableHtml +=`<td>
                                            <input type='text' name='thickness[]' id='thickness' value='${v.thickness}' class='form-control' />
                                        </td>`;
                                    tableHtml +=
                                        `<td><input type='text' value='${v.sale_qty}' id="qty" name="qty[]" class='form-control' onkeyup="changerate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='price[]' id='price' value='${v.rate}' class='form-control' style="width:120px;" onkeyup="changerate($(this).closest('tr'));" onchange="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
                                        </td>`;
                                    tableHtml +=`<td style="width:130px;">
                                        <input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                                        </td>`;
                                    tableHtml += `</tr>`;
                                });
                                $('#GridTable').html(tableHtml);
                                $(".grades").select2({
                            placeholder: "Select Account",
                            allowClear: true
                            });
                                // $('#updated_by_name').removeClass('d-none');
                                $('#update_voucher_id').val(response.data.id);
                                $('#date').val(response.data.date);
                                $('#voucher_no').val(response.data.voucher_no);
                                $('#voucher_no').focus();
                                $('#remarks').val(response.data.remarks);
                                // $('#party_name').val(response.data[0].party.id + "_" + response
                                //     .data[0].party.party_name + "_" + response.data[0].party
                                //     .address).select2();
                                $('#party_id').val(response.data.party.id).select2();
                                $('.ReloadOrder').removeClass('d-none');
                                TotalAmount();
                                TotalSaleQty();  
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
                // alert(voucher_no)
                $.ajax({
                    url: "{{ URL::to('direct-sales/load/previous/record') }}",
                    type: 'get',
                    data:{voucher_no:voucher_no},
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
                                    tableHtml +=`<td>${v.product.code}</td>`;
                                    // tableHtml += `<td>
                                    //    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                    //    <input type='text' value='${v.product.product_name}' class='form-control' readonly />
                                    //     </td>`;
                                    var app = @json($Accountsbelow);
                                        var option = `<option value="${v.product.id}" selected>${v.product.code} - ${v.product.product_name}</option>`;
                                        $.each(app, function(i, v) {
                                            option +=`<option value="${v.id}">${v.code} - ${v.product_name}</option>`;
                                        });
                                        tableHtml +=`<td><select class="form-control grades" name="product_id[]" id="product_id">
                                        ${option}
                                        </select>
                                            </td>`;

                                    tableHtml +=`<td>${v.product.uom}</td>`;
                                    tableHtml +=`<td>
                                            <input type='text' name='thickness[]' id='thickness' value='${v.thickness}' class='form-control' />
                                        </td>`;
                                    tableHtml +=
                                        `<td><input type='text' value='${v.sale_qty}' id="qty" name="qty[]" class='form-control' onkeyup="changerate($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='price[]' id='price' value='${v.rate}' class='form-control' style="width:120px;" onkeyup="changerate($(this).closest('tr'));" onchange="EnterKeyBoard($(this).closest('tr'));" onkeypress="return isNumberKey(event)"/>
                                        </td>`;
                                    tableHtml +=`<td style="width:130px;">
                                        <input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                                        </td>`;
                                    tableHtml += `</tr>`;
                                });
                                $('#GridTable').html(tableHtml);
                                $(".grades").select2({
                            placeholder: "Select Account",
                            allowClear: true
                            });
                                // $('#updated_by_name').removeClass('d-none');
                                $('#update_voucher_id').val(response.data.id);
                                $('#date').val(response.data.date);
                                $('#voucher_no').val(response.data.voucher_no);
                                $('#voucher_no').focus();
                                $('#remarks').val(response.data.remarks);
                                // $('#party_name').val(response.data[0].party.id + "_" + response
                                //     .data[0].party.party_name + "_" + response.data[0].party
                                //     .address).select2();
                                $('#party_id').val(response.data.party.id).select2();
                                $('.ReloadOrder').removeClass('d-none');
                                TotalAmount();
                                TotalSaleQty();  
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
                        // alert(response.data.salepurchase.dc
                        //         .voucher_no);
                        
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
