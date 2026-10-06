@extends('app')
@section('head')
    <title>Stock Transfer</title>
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
                Stock Transfer
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Stock Transfer</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <!-- <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Stock Transfer</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div> -->
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <!-- @include('errors.validation') -->
                                <div class="col">
                                    @if (Session::has('failure_message'))
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">×</button> {{ Session::get('failure_message') }}
                                        </div>
                                    @endif
                                    <div id="show_err"></div>
                                    {!! Form::open(['url' => 'stock-transfer', 'class' => 'form-horizontal', 'id' => 'stock-transfer-form']) !!}
                                    {!! Form::hidden('update_voucher_id', null, ['id' => 'update_voucher_id']) !!}
                                    {!! Form::hidden('type', 'STOCK TRANSFER', ['id' => 'type']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                  

                                    <div class="row">
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="date"><i class="fa fa-caret-right"></i> Date</label>
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
                                        <div class="col-lg-1 col-md-4 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Bill No#<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $codes, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'tabindex' => '1',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                'required' => 'required',
                                                'autofocus' => 'autofocus',
                                            ]) !!}
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i> Remarks</span></label>
                                            {!! Form::text('remarks', null, [
                                                'id' => 'remarks',
                                                'class' => 'form-control',
                                                'tabindex' => '2',
                                                
                                            ]) !!}
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="from_warehouse_id"><i class="fa fa-caret-right"></i> From <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('from_warehouse_id1', $warehouseFrom->name, [
                                                'id' => 'from_warehouse_id1',
                                                'class' => 'form-control',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            {!! Form::hidden('from_warehouse_id', Auth::User()->warehouse_id, [
                                                'id' => 'from_warehouse_id',
                                                'class' => 'form-control',
                                            ]) !!}
                                            @error('from_warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 mt-1">
                                            <label for="to_warehouse_id"><i class="fa fa-caret-right"></i> TO<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('to_warehouse_id', $warehouseTo, null, [
                                                'id' => 'to_warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '3',
                                            ]) !!}
                                            @error('to_warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="total_amount1"><i class="fa fa-caret-right"></i>Forman<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('party_id', $forman, null, [
                                                'id' => 'party_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '6',
                                            ]) !!}
                                            @error('total_amount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- <div class="row">
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="from_warehouse_id"><i class="fa fa-caret-right"></i> From <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('from_warehouse_id1', $warehouseFrom->name, [
                                                'id' => 'from_warehouse_id1',
                                                'class' => 'form-control',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            {!! Form::hidden('from_warehouse_id', Auth::User()->warehouse_id, [
                                                'id' => 'from_warehouse_id',
                                                'class' => 'form-control',
                                            ]) !!}
                                            @error('from_warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-1">
                                            <label for="to_warehouse_id"><i class="fa fa-caret-right"></i> TO<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('to_warehouse_id', $warehouseTo, null, [
                                                'id' => 'to_warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '3',
                                            ]) !!}
                                            @error('to_warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                    </div> -->

                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="table-responsive-md mb-2">
                                                <table class="table">
                                                    <thead>
                                                        <tr class="bg-primary text-left">
                                                            <th>Code</th>
                                                            <th>Product</th>
                                                            <th>Unit</th>
                                                            <th>Qty</th>
                                                            <th class="d-none">Cost Rate</th>
                                                            <th class="d-none">Cost Amount</th>
                                                            <th>Packing</th>
                                                            <th>Pack</th>
                                                            <!-- <th>Stock</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="bg-secondary">
                                                            <td style="width: 10%;">
                                                                {!! Form::text('code1', null, [
                                                                    'id' => 'code1',
                                                                    'class' => 'form-control bg-white',
                                                                    'disabled' => 'disabled',
                                                                    'tabindex' => '8',
                                                                ]) !!}
                                                                {!! Form::hidden('code2', null, ['id' => 'code2']) !!}
                                                            </td>
                                                            <td style="width: 35%;">
                                                                <!-- {!! Form::select('product_id1', [''=>'Select Product','FINISH PRODUCT'=>$products], null, [
                                                                    'id' => 'product_id1',
                                                                    'class' => 'form-control select2',
                                                                    'tabindex' => '9',
                                                                ]) !!} -->
                                                                <select id="product_id1" name="product_id1" class="form-control select2">
                                                                    <option value="">Select Product</option>
                                                                    @foreach($StockProduct as $Product)
                                                                    <option value="{{$Product->id}}">{{$Product->code}} - {{$Product->product_name}} - {{$Product->InQty-$Product->OutQty}}</option>
                                                                    @endforeach
                                                                </select>

                                                                <!-- {!! Form::select('product_id1', [''=>'Select Product','FINISH PRODUCT'=>$products], null, [
                                                                    'id' => 'product_id1',
                                                                    'class' => 'form-control select2',
                                                                    'tabindex' => '9',
                                                                ]) !!} -->
                                                                 {!! Form::hidden('product_id2', null, ['id' => 'product_id2']) !!}
                                                                <span class="product-err text-danger"></span>
                                                            </td>
                                                            <td style="width: 10%;">
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
                                                                {!! Form::text('qty1', null, [
                                                                    'id' => 'qty1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Qty',
                                                                    'onkeypress'=>"return isNumberKey(event)",
                                                                    'tabindex' => '11',
                                                                    'onkeyup' => 'QuantityKeyUp($(this).val())',
                                                                ]) !!}
                                                                <span class="qty-err text-danger"></span>
                                                            </td>
                                                            <td class="d-none">
                                                                {!! Form::text('price1', null, [
                                                                    'id' => 'price1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Price',
                                                                    'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                                    'tabindex' => '12',
                                                                    'onkeyup' => 'PriceKeyUp($(this).val())',
                                                                ]) !!}
                                                                <span class="price-err text-danger"></span>
                                                            </td>
                                                            <td class="d-none">
                                                                {!! Form::text('total1', null, [
                                                                    'id' => 'total1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Total',
                                                                    'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                                    'tabindex' => '13',
                                                                ]) !!}
                                                                <span class="total-err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('packing1', null, [
                                                                    'id' => 'packing1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'packing',
                                                                    'disabled' => 'disabled',
                                                                ]) !!}
                                                                <span class="total-err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('pack1', null, [
                                                                    'id' => 'pack1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Total',
                                                                    'disabled' => 'disabled',
                                                                ]) !!}
                                                                <span class="total-err text-danger"></span>
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
                                                                <th>Qty</th>
                                                                <th class="d-none">Cost Rate</th>
                                                                <th class="d-none">Cost Amount</th>
                                                                <th>Packing</th>
                                                                <th>Pack</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3"><strong>Total</strong></td>
                                                                <td class="bg-primary" id="TotalQty">0</td>
                                                                <!-- <td class="bg-info" id="TotalPrice"></td> -->
                                                                <td id="TotalPrice"></td>
                                                                <td class="bg-success" id="TotalAmount">0</td>
                                                                <td></td>
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
                <form action="{{ URL::to('stock-transfer/destroy') }}" method="post" id="delete_voucher_form">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Voucher?</p>
                        <input type="hidden" name="delete_invoice_no" id="delete_invoice_no" value="">
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
    <script>
        $(document).ready(function () {
        $('#product_id1').change(function() {
            
        var product_id = $(this).val();
        //alert(product_id);
        $.ajax({
            url: "{{ asset('stock-transfer/getProdut')}}",
            type: 'get',
            data:{product_id:product_id},
            dataType: 'json',
            success: function(response) {
                //console.log(response);
                if (response.length > 0) {
                    $('#product_id1').val(response[0].id);
                    $('#code1').val(response[0].code);
                    $('#product_id2').val(response[0].product_name);
                    $('#unit1').val(response[0].uom);
                    $('#price1').val(response[0].product_price);
                    $('#total1').val(response[0].product_price);
                    if(response[0].packing){
                        $('#packing1').val(response[0].packing);
                    }else{
                        $('#packing1').val(0);
                    }
                    
                    $('#product_id1').select2().trigger('select2:close');
                    $('#qty1').focus();
                    }
        }
        });
    });
});
 </script>       
    <!-- Focus on next field -->
    <script>
        $(document).ready(function() {
            $('#voucher_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#remarks').focus();
                }
            });
            $('#remarks').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#to_warehouse_id").select2('open');
                }
            });
            $('#to_warehouse_id').change(function(event) {
                var to_warehouse_id = $(this).val();
                if (to_warehouse_id) {
                    $('#to_warehouse_id').select2().trigger('select2:close');
                    $('#party_id').select2('open');
                }
            });
            $('#party_id').change(function(event) {
                var to_warehouse_id = $(this).val();
                if (to_warehouse_id) {
                    $('#party_id').select2().trigger('select2:close');
                    $('#product_id1').select2('open');
                }
            });
            // $('#product_id1').change(function(event) {
            //     var product_id = $(this).val();
            //     if (product_id != null) {
            //         $('#product_id1').select2().trigger('select2:close');
            //         // $('#qty1').val(1);
            //         $('#qty1').focus();
            //     }
            // });
            $('#qty1').keydown(function(event) {
                // alert("d")
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = parseFloat($(this).val());
                    // alert(qty);
                    if (qty < 0) {
                        $(this).focus();
                        $('.qty-err').text('This field Must be greater than zero');
                    } else {
                        $('.qty-err').text('');
                        AddGridData();
                    }
                }
            });
        });
    </script>
    <!-- End Focus on next field -->

    <!-- Append New Data on Table -->
    <script>

        function TotalQtyGrid() {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                if (tableData.rows[i].cells[3].getElementsByTagName('input')[0].value == '') {
                    sum += 0;
                } else {
                    sum += parseFloat(tableData.rows[i].cells[3].getElementsByTagName('input')[0].value);
                    // alert(sum);
                }   
            }
            document.getElementById('TotalQty').innerText = sum.toLocaleString('en-US');
        }

        function TotalPackGrid() {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                if (tableData.rows[i].cells[7].getElementsByTagName('input')[0].value == '') {
                    sum += 0;
                } else {
                    sum += parseFloat(tableData.rows[i].cells[7].getElementsByTagName('input')[0].value);
                    // alert(sum);
                }   
            }
            document.getElementById('TotalAmount').innerText = sum.toLocaleString('en-US');
        }

        function AddGridData() {
           
            var product_id = parseInt($('#product_id1').val());
            if (!product_id) {
                $('.product-err').text('This field is required');
                $('#product_id1').focus();
                return false;
            } else {
                $('.product-err').text('');
            }

            var qty = $('#qty1').val();
            if (!qty || qty <= 0) {
                $('.qty-err').text('This field is required & Must be greater than zero');
                $('#qty1').focus();
                return false;
            } else {
                $('.qty-err').text('');
            }
            
            var pro_id = document.getElementById('product_id1').value;
            var pro_code = document.getElementById('code1').value;
            var pro_name = document.getElementById('product_id2').value;
           
            var pro_unit = document.getElementById('unit1').value;
            var price = document.getElementById('price1').value;
            var qty = document.getElementById('qty1').value;
            var total = document.getElementById('total1').value;
           
            var packing = parseInt(document.getElementById('packing1').value);
            var pack = parseFloat(document.getElementById('pack1').value).toFixed(2);
            var TotalPrice = parseFloat(document.getElementById('TotalPrice').innerHTML);
            var TotalQty = parseFloat(document.getElementById('TotalQty').innerHTML);
            var TotalAmount = parseFloat(document.getElementById('TotalAmount').innerHTML);
            // var grandTotalQty = TotalQty + parseFloat(qty);
            // var grandTotalPrice = TotalPrice + price;
            // var grandTotalPrice = TotalPrice + parseFloat(packing);
            // var grandTotalAmount = TotalAmount + total;
            // var grandTotalAmount = TotalAmount + parseFloat(pack);

            // totalQty += parseFloat(v.qty_out);
            // totalPrice += parseFloat(v.rate);
            // totalAmount += parseFloat(v.amount);


            var tableHtml = `<tr>`;
            tableHtml += `<td>${pro_code}<input type='hidden' name='code[]' id='code' value='${pro_code}' /></td>`;
            tableHtml += `<td>
                            ${pro_name}
                            <input type='hidden' name='product_id[]' id='product_id' value='${pro_id}' />
                            <input type='hidden' name='product_name[]' id='product_name' value='${pro_name}' />
                        </td>`;
            tableHtml += `<td>${pro_unit}<input type='hidden' name='unit[]' id='unit' value='${pro_unit}' /></td>`;
            tableHtml +=
                `<td>${qty}<input type='hidden' name='qty[]' id='qty' value='${qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
            tableHtml +=
                `<td class="d-none">${price}<input type='hidden' name='price[]' id='price' value='${price}' class='form-control' /></td>`;
            tableHtml +=
                `<td class="d-none">${total}<input type='hidden' name='total[]' id='total' value='${total}' class='form-control' /></td>`;
            
            tableHtml +=
                `<td>${packing}</td>`;
            tableHtml +=
                `<td>${pack}<input type='hidden' value='${pack}' class='form-control' /></td>`;

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


            // $('#TotalQty').html(grandTotalQty);
            // $('#TotalPrice').html(grandTotalPrice);
            // $('#TotalAmount').html(grandTotalAmount);
            TotalQtyGrid();
            TotalPackGrid();
            $('#product_id1').select2('open');
            $('#product_id1').val(null);
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
                $('#stock-transfer-form').submit();
                $('.submit-form').attr('disabled', true);
            });
            // End FOrm Submit

            // Reset btn feature
            $('.reset-btn').click(function() {
                $('#to_department_id').val(null).select2();
                $('#update_voucher_id').val(null);
                $('#updated_by_name').addClass('d-none');
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

            var TotalPrice = parseInt(document.getElementById('TotalPrice').innerText);
            var NewPrice = parseInt($(row).find("td:eq('4')").find('input').val());
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

            var packing = document.getElementById('packing1').value;
            // alert(packing);
            if(packing > 0){
                var totalpack = quantity / packing;
            // alert(packing);
            document.getElementById('pack1').value = totalpack;
            }else{
                document.getElementById('pack1').value = 0;  
            }
           
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
                $('#delete_invoice_no').val(voucher_no);
            });
             // Print Record
             $('.print_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                myModal.toggle();

                var voucher_no = parseInt($('#voucher_no').val());
                var base_url = $('#base_url').val();
                $.ajax({
                    url: "{{ URL::to('stock-transfer/print/voucher') }}?voucher_no=" +
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
                                `<object data="${base_url}/resources/upload/stock-transfer/${response}" type="application/pdf" width="100%" height="800"></object>`
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
                    url: "{{ URL::to('stock-transfer/load/record') }}?voucher_no=" + voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                             $('#update_voucher_id').val(response.data[0].id);
                            $('#date').val(response.data[0].date);
                            $('#remarks').val(response.data[0].remarks);
                            $('#to_warehouse_id').val(response.data[0].to_warehouse_id)
                                .select2();
                            $('#party_id').val(response.data[0].party_id)
                                 .select2();
                            var tableHtml = '';
                            var totalQty = 0;
                            var totalPrice = 0;
                            var totalAmount = 0;
                          console.log(response.data);
                            $.each(response.data[0].godown_stock_details, function(i, v) {
                                if (v.qty_in==0) {
                                    
                                    // totalQty += parseFloat(v.qty_out);
                                    // totalPrice += parseFloat(v.rate);
                                    // totalAmount += parseFloat(v.amount);

                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                    
                                        `<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                    tableHtml += `<td>
                                                ${v.product.product_name}
                                                <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                                <input type='hidden' name='product_name[]' id='product_name' value='${v.product.product_name}' />
                                            </td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=
                                        `<td>${Number(v.qty_out).toLocaleString('en-US')}<input type='hidden' name='qty[]' id='qty' value='${v.qty_out}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                    tableHtml +=
                                        `<td class="d-none">${v.rate}<input type='hidden' name='price[]' id='price' value='${v.rate}' class='form-control' /></td>`;
                                    tableHtml +=
                                        `<td class="d-none">${v.amount}<input type='hidden' name='total[]' id='total' value='${v.amount}' class='form-control' /></td>`;
                                    if(v.product.packing > 0){
                                        
                                        tableHtml +=`<td>${Number(v.product.packing).toLocaleString('en-US')}</td>`;
                                        var totalpack = v.qty_out / v.product.packing;
                                        
                                        tableHtml +=`<td>${Number(totalpack).toLocaleString('en-US')}<input type='hidden' value='${totalpack}' class='form-control' /></td>`;
    
                                        totalPrice += parseFloat(v.product.packing);
                                            totalAmount += parseFloat(totalpack);
                                        }else{
                                            tableHtml +=`<td>0</td>`;
                                            tableHtml +=`<td>0</td>`;
                                        }
                                    tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;
                                }
                            });

                            $('#GridTable').html(tableHtml);
                            TotalQtyGrid();
                            TotalPackGrid();
                            $('#updated_by_name').removeClass('d-none');

                           

                            $('#voucher_no').focus();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalPrice').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_voucher_id').val(null);
                            let date = new Date()
                            $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                .getMonth()) + 1) + '-' + date.getDate());
                            $('#to_department_id').val(null);
                            $('#to_department_id').select2();
                            $('#voucher_no').focus();
                        }
                    }
                });
            });
            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('stock-transfer/load/next/record') }}?voucher_no=" + voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                             $('#date').val(response.data[0].date);
                            $('#voucher_no').focus();
                            $('#voucher_no').val(response.data[0].voucher_no);
                            $('#update_voucher_id').val(response.data[0].id);
                              if(response.data[0]){
                             $('#to_warehouse_id').val(response.data[0].to_warehouse_id)
                                 .select2();
                            $('#party_id').val(response.data[0].party_id)
                                 .select2();
                             $('#remarks').val(response.data[0].remarks);
                            }
                            var tableHtml = '';
                            var totalQty = 0;
                            var totalPrice = 0;
                            var totalAmount = 0;
                            $.each(response.data[0].godown_stock_details, function(i, v) {
                                if (v.qty_in==0) {
                                    
                                    // totalQty += parseFloat(v.qty_out);
                                    // totalPrice += parseFloat(v.rate);
                                    // totalAmount += parseFloat(v.amount);

                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                    
                                        `<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                    tableHtml += `<td>
                                                ${v.product.product_name}
                                                <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                                <input type='hidden' name='product_name[]' id='product_name' value='${v.product.product_name}' />
                                            </td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=
                                        `<td>${Number(v.qty_out).toLocaleString('en-US')}<input type='hidden' name='qty[]' id='qty' value='${v.qty_out}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                    tableHtml +=
                                        `<td class="d-none">${v.rate}<input type='hidden' name='price[]' id='price' value='${v.rate}' class='form-control' /></td>`;
                                    tableHtml +=
                                        `<td class="d-none">${v.amount}<input type='hidden' name='total[]' id='total' value='${v.amount}' class='form-control' /></td>`;
                                    if(v.product.packing > 0){
                                        
                                        tableHtml +=`<td>${Number(v.product.packing).toLocaleString('en-US')}</td>`;
                                        var totalpack = v.qty_out / v.product.packing;
                                        
                                        tableHtml +=`<td>${Number(totalpack).toLocaleString('en-US')}<input type='hidden' value='${totalpack}' class='form-control' /></td>`;
    
                                        totalPrice += parseFloat(v.product.packing);
                                            totalAmount += parseFloat(totalpack);
                                        }else{
                                            tableHtml +=`<td>0</td>`;
                                            tableHtml +=`<td>0</td>`;
                                        }
                                    tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;
                                }
                            });

                            $('#GridTable').html(tableHtml);
                            // alert("ddd");
                            // $('#TotalQty').text(totalQty.toLocaleString('en-US'));
                            // $('#TotalPrice').text(totalPrice);
                            // $('#TotalAmount').text(totalAmount.toLocaleString('en-US'));
                           
                            TotalQtyGrid();
                            TotalPackGrid();
                            $('#updated_by_name').removeClass('d-none');

                            
                           
                          
                            
                            
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalPrice').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_voucher_id').val(null);
                            let date = new Date()
                            $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                .getMonth()) + 1) + '-' + date.getDate());
                            $('#to_department_id').val(null);
                            $('#to_department_id').select2();
                            $('#voucher_no').focus();
                        }
                    }
                });
            });
            // End Here of Load Next Record
            // Load Previous Record
            $('.load-previous-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('stock-transfer/load/previous/record') }}?voucher_no=" + voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                             $('#date').val(response.data[0].date);
                            $('#voucher_no').focus();
                            $('#voucher_no').val(response.data[0].voucher_no);
                            $('#update_voucher_id').val(response.data[0].id);
                              if(response.data[0]){
                             $('#to_warehouse_id').val(response.data[0].to_warehouse_id)
                                 .select2();
                            $('#party_id').val(response.data[0].party_id)
                                 .select2();
                             $('#remarks').val(response.data[0].remarks);
                            }
                            var tableHtml = '';
                            var totalQty = 0;
                            var totalPrice = 0;
                            var totalAmount = 0;
                            $.each(response.data[0].godown_stock_details, function(i, v) {
                                if (v.qty_in==0) {
                                    
                                    // totalQty += parseFloat(v.qty_out);
                                    // totalPrice += parseFloat(v.rate);
                                    // totalAmount += parseFloat(v.amount);

                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                    
                                        `<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                    tableHtml += `<td>
                                                ${v.product.product_name}
                                                <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                                <input type='hidden' name='product_name[]' id='product_name' value='${v.product.product_name}' />
                                            </td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=
                                        `<td>${Number(v.qty_out).toLocaleString('en-US')}<input type='hidden' name='qty[]' id='qty' value='${v.qty_out}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                    tableHtml +=
                                        `<td class="d-none">${v.rate}<input type='hidden' name='price[]' id='price' value='${v.rate}' class='form-control' /></td>`;
                                    tableHtml +=
                                        `<td class="d-none">${v.amount}<input type='hidden' name='total[]' id='total' value='${v.amount}' class='form-control' /></td>`;
                                    if(v.product.packing > 0){
                                        
                                        tableHtml +=`<td>${Number(v.product.packing).toLocaleString('en-US')}</td>`;
                                        var totalpack = v.qty_out / v.product.packing;
                                        
                                        tableHtml +=`<td>${Number(totalpack).toLocaleString('en-US')}<input type='hidden' value='${totalpack}' class='form-control' /></td>`;
    
                                        totalPrice += parseFloat(v.product.packing);
                                            totalAmount += parseFloat(totalpack);
                                        }else{
                                            tableHtml +=`<td>0</td>`;
                                            tableHtml +=`<td>0</td>`;
                                        }
                                    tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;
                                }
                            });

                            $('#GridTable').html(tableHtml);
                            // alert("ddd");
                            // $('#TotalQty').text(totalQty.toLocaleString('en-US'));
                            // $('#TotalPrice').text(totalPrice);
                            // $('#TotalAmount').text(totalAmount.toLocaleString('en-US'));
                           
                            TotalQtyGrid();
                            TotalPackGrid();
                            $('#updated_by_name').removeClass('d-none');

                            
                           
                          
                            
                            
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalPrice').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_voucher_id').val(null);
                            let date = new Date()
                            $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                .getMonth()) + 1) + '-' + date.getDate());
                            $('#to_department_id').val(null);
                            $('#to_department_id').select2();
                            $('#voucher_no').focus();
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
