@extends('app')
@section('head')
    <title>Stock Consumption</title>
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
            <h1>Stock Consumption</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Stock Consumption</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>Stock Consumption</h6>
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
                                    @include('errors.validation')
                                    {!! Form::open(['url' => 'stock-consumption', 'class' => 'form-horizontal', 'id' => 'opening-stock-form']) !!}
                                    {!! Form::hidden('update_voucher_id', null, ['id' => 'update_voucher_id']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    <input type="hidden" id="userrole" name="userrole" value="{{Auth::User()->role}}">

                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <label for="date"><i class="fa fa-caret-right"></i> Voucher Date</label>
                                            {!! Form::date('date', date('Y-m-d'), [
                                                'id' => 'date',
                                                'class' => 'form-control',
                                                'autofocus' => 'autofocus',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                       
                                        @if(isset($data))
                                        <input type="hidden" id="userrole" name="userrole" value="{{Auth::User()->role}}">
                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                            <label for="account_id"><i class="fa fa-caret-right"></i>Select Warehouse.
                                                <span class="text-danger">*</span></label>
                                            {!! Form::select('warehouse_id', $warehouse, $data->warehouse_id, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                            ]) !!}
                                        </div>
                                        @if(Auth::User()->role == "Admin")
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $data->voucher_no, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                                'tabindex' => '1',
                                            ]) !!}
                                        </div>
                                        @else
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $data->voucher_no, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                                'tabindex' => '1',
                                            ]) !!}
                                        </div>
                                        @endif
                                        @else
                                        <input type="hidden" id="userrole" name="userrole" value="{{Auth::User()->role}}">
                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                            <label for="account_id"><i class="fa fa-caret-right"></i>Select Warehouse.
                                                <span class="text-danger">*</span></label>
                                            {!! Form::select('warehouse_id', $warehouse, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                            ]) !!}
                                        </div>
                                        @if(Auth::User()->role == "Admin")
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', null, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                                'tabindex' => '1',
                                            ]) !!}
                                        </div>
                                        @else
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $codes, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                                'tabindex' => '1',
                                            ]) !!}
                                        </div>
                                        @endif
                                        @endif
                                        
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1 d-none">
                                            <label for="department_id"><i class="fa fa-caret-right"></i> Godown<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('department_id', $departments, null, [
                                                'id' => 'department_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('department_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            <label for="remarks"><i class="fa fa-caret-right"></i> Remarks<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('remarks', null, [
                                                'id' => 'remarks',
                                                'class' => 'form-control',
                                                'tabindex' => '5',
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
                                                            <th>Code</th>
                                                            <th>Product Name</th>
                                                            <th>Unit</th>
                                                            <th>Packing</th>
                                                            <th>Qty</th>
                                                            <th>Pack.Qty</th>
                                                            <th>Rate</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="bg-secondary">
                                                            <td>
                                                                {!! Form::text('code1', null, [
                                                                    'id' => 'code1',
                                                                    'class' => 'form-control bg-white',
                                                                    'readonly' => 'readonly',
                                                                    'tabindex' => '7',
                                                                ]) !!}
                                                                {!! Form::hidden('code2', null, ['id' => 'code2']) !!}
                                                            </td>
                                                            <!-- @if(Auth::user()->role == "Admin")
                                                            <td style="width: 30%;">
                                                                {!! Form::select('product_id1', $products, null, [
                                                                    'id' => 'product_id1',
                                                                    'class' => 'form-control select2',
                                                                    'tabindex' => '8',
                                                                ]) !!}
                                                                <span class="product_err text-danger"></span>
                                                            </td>

                                                            @else
                                                            <td style="width: 30%;">
                                                                {!! Form::select('product_id1', $StockProduct, null, [
                                                                    'id' => 'product_id1',
                                                                    'class' => 'form-control select2',
                                                                    'tabindex' => '8',
                                                                ]) !!}
                                                                <span class="product_err text-danger"></span>
                                                            </td>
                                                            @endif -->
                                                            <td style="width: 30%;">
                                                                <select id="product_id1" name="product_id1" class="form-control select2">
                                                                    <option value="">Select Product</option>
                                                                    @foreach($StockProduct as $Product)
                                                                    <option value="{{$Product->id}}_{{$Product->code}}_{{$Product->product_name}}_{{$Product->uom}}_{{$Product->product_cost}}_{{$Product->packing}}">{{$Product->code}} - {{$Product->product_name}} - {{$Product->InQty-$Product->OutQty}}</option>
                                                                    @endforeach
                                                                </select>
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
                                                                {!! Form::text('packing1', null, [
                                                                    'id' => 'packing1',
                                                                    'class' => 'form-control bg-white',
                                                                    'disabled' => 'disabled',
                                                                    'placeholder' => 'Packing',
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
                                                                    'onkeypress' => "return isNumberKey(event)",
                                                                ]) !!}
                                                                <span class="qty_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('PackQty1', null, [
                                                                    'id' => 'PackQty1',
                                                                    'class' => 'form-control bg-white',
                                                                    'disabled' => 'disabled',
                                                                    'placeholder' => 'Pack Qty',
                                                                    'tabindex' => '9',
                                                                ]) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('price1', null, [
                                                                    'id' => 'price1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Price',
                                                                    'tabindex' => '11',
                                                                    'onkeyup' => 'PriceKeyUp($(this).val())',
                                                                    'onkeypress' => "return isNumberKey(event)",
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
                                                                <th>Sr#</th>
                                                                <th>Code</th>
                                                                <th style="width: 30%;">Product</th>
                                                                <th>Unit</th>
                                                                <th>Packing</th>
                                                                <th>Qty</th>
                                                                <th>Pack.Qty</th>
                                                                <th>Rate</th>
                                                                <th>Amount</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="5"><strong>Total</strong></td>
                                                                <td class="bg-primary" id="TotalQty">0</td>
                                                                <td class="bg-primary" id="TotalPackQty">0</td>
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
                                        <div class="col-lg-2 col-md-12 col-12">
                                            <button class="btn btn-primary submit-form" type="button">Save</button>
                                            <button class="btn btn-secondary reset-btn" type="reset">Reset</button>
                                        </div>
                                       
                                        <!-- <div class="col-lg-3 col-md-12 col-12">
                                            <div class="note note-warning">Posted By : {{ Auth::User()->name }}</div>
                                        </div>
                                        <div class="col-lg-3 col-md-12 col-12">
                                            <div class="note note-info">Updated By : <span class="d-none"
                                                    id="updated_by_name"> {{ Auth::User()->name }}</span></div>
                                        </div> -->
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
                <form action="{{ URL::to('stock-consumption/delete-voucher') }}" method="post" id="delete_voucher_form">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Voucher?</p>
                        <input type="hidden" name="delete_voucher_no" id="delete_voucher_no" value="">
                        <input type="hidden" name="delete_warehouseID" id="delete_warehouseID" value="">
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
    function godownproducts(WarehouseID){
                // alert(WarehouseID);

                // var WarehouseID = $('#warehouse_id').val();
            // $('#remarks').focus();
            // alert(WarehouseID);
            $.ajax({
                    url: "{{ URL::to('stock-consumption/change/warehouse') }}?WarehouseID=" +
                    WarehouseID,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },

                    success: function(response) {
                            var option = `<option value="" selected>Select Product</option>`;
                                if (response.length > 0) {
                            $.each(response, function(i, v) {
                                option +=
                                    `<option value="${v.id}_${v.code}_${v.product_name}_${v.uom}_${v.product_cost}_${v.packing}">${v.code} - ${v.product_name}</option>`;

                            });
                            $('#product_id1').html(option);
                            
                       
                        }
                        else {
                        var option = '<option value="" selected>Product Not Found</option>';

                        $('#product_id1').html(option);
                    }
                }


          
            });


                
            }
    </script>
    <!-- Searchable Select2 -->
    <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('resources/resources/views/opening-stock/grid.js') }}"></script>
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
                var warehouse_id = $(this).val();
                if (warehouse_id) {
                    $('#warehouse_id').select2().trigger('select2:close');
                    $('#voucher_no').focus();
                    godownproducts(warehouse_id);
                }
            });
            $('#voucher_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#remarks").focus();
                }
            });


            $('#remarks').keydown(function(event) {
                // alert("ddd")
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#product_id1").select2('open');
                }
            });
            $('#product_id1').change(function(event) {
                var product_id = $(this).val();
                if (product_id != null) {
                    $('#code1').val(product_id.split('_')[1]);
                    $('#code2').val(product_id.split('_')[1]);
                    $('#unit1').val(product_id.split('_')[3]);
                    $('#product_id1').select2().trigger('select2:close');
                    $('#price1').val(parseInt(product_id.split('_')[4]));
                    $('#packing1').val(parseInt(product_id.split('_')[5]));
                    $('#PackQty1').val(parseInt(product_id.split('_')[5]));
                    $('#qty1').val(1);
                    $('#qty1').focus();
                }
            });
            $('#qty1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = parseInt($(this).val());
                    // if (qty <= 0) {
                    //     $(this).focus();
                    //     $('.qty_err').text('This field is required & Must be greater than zero');
                    // } else {
                        $('.qty_err').text('');
                        $('#price1').focus();
                    // }
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
        // $('#warehouse_ids').change(function() {
        //     var WarehouseID = $('#warehouse_id').val();
        //     // $('#remarks').focus();
        //     // alert(WarehouseID);
        //     $.ajax({
        //             url: "{{ URL::to('opening-stock/change/warehouse') }}?WarehouseID=" +
        //             WarehouseID,
        //             type: 'get',
        //             dataType: 'json',
        //             beforeSend: function(response) {
        //                 $('#show_err').html(
        //                     '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
        //                 );
        //             },

        //             success: function(response) {
        //                     var option = `<option value="" selected>Select Product</option>`;
        //                         if (response.length > 0) {
        //                     $.each(response, function(i, v) {
        //                         option +=
        //                             `<option value="${v.id}_${v.code}_${v.product_name}_${v.uom}_${v.product_cost}_${v.packing}">${v.code} - ${v.product_name}</option>`;

        //                     });
        //                     $('#product_id1').html(option);
                            
                       
        //                 }
        //                 else {
        //                 var option = '<option value="" selected>Product Not Found</option>';

        //                 $('#product_id1').html(option);
        //             }
        //         }


          
        //     });
            
        // });

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
                $('.qty_err').text('This field is required & Must be greater than zero');
                $('#qty1').focus();
                return false;
            } else {
                $('.qty_err').text('');
            }

            var price = parseFloat($('#price1').val());
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
            // var product_unit_id = document.getElementById('product_id1').value.split('_')[4];
            // var pro_cost = parseInt(document.getElementById('product_id1').value.split('_')[7]);
            var packing = parseInt(document.getElementById('packing1').value);
            var price = parseInt(document.getElementById('price1').value);
            // var qty = parseInt(document.getElementById('qty1').value);
            var Packqty = document.getElementById('PackQty1').value;
            var qty = document.getElementById('qty1').value;
            var total = parseFloat(document.getElementById('total1').value);
            var TotalQty = parseFloat(document.getElementById('TotalQty').innerHTML);
            var TotalPackQty = parseFloat(document.getElementById('TotalPackQty').innerHTML);
            var TotalAmount = parseFloat(document.getElementById('TotalAmount').innerHTML);

            var cost_amount = price * qty;
            var sale_amount = price * qty;
            var grandTotalQty = parseFloat(TotalQty) + parseFloat(qty);
            var grandPackQty = parseFloat(TotalPackQty) + parseFloat(Packqty);
            var grandTotalAmount = parseFloat(TotalAmount) + parseFloat(total);
            var totalRowCount = GridTable.rows.length;
            // alert(totalRowCount)
            var tableHtml = `<tr>`;
            tableHtml +=
                `<td>${totalRowCount+1}</td>`;
            tableHtml +=
                `<td>${pro_code}</td>`;
            tableHtml += `<td>${pro_name}
                            <input type='hidden' name='product_id[]' id='product_id' value='${pro_id}' />
                        </td>`;
            tableHtml += `<td>${pro_unit}</td>`;
            tableHtml += `<td>${packing.toLocaleString('en-US')}</td>`;
            tableHtml +=`<td>${Number(qty).toLocaleString('en-US')}<input type='hidden' name='qty[]' id='qty' value='${qty}' /></td>`;
            tableHtml += `<td>${Packqty.toLocaleString('en-US')}</td>`;
            tableHtml +=`<td>${Number(price).toLocaleString('en-US')}<input type='hidden' name='price[]' id='price' value='${price}' /></td>`;
            tableHtml +=`<td>${Number(total).toLocaleString('en-US')}<input type='hidden' name='total[]' id='total' value='${total}' /></td>`;
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


            $('#TotalQty').html(grandTotalQty.toLocaleString('en-US'));
            $('#TotalPackQty').html(grandPackQty.toLocaleString('en-US'));
            $('#TotalAmount').html(grandTotalAmount.toLocaleString('en-US'));

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
                $('#opening-stock-form').submit();
                $('.submit-form').attr('disabled', true);
            });
            // End FOrm Submit

            // Reset btn feature
            $('.reset-btn').click(function() {
                $('#warehouse_id').val(null).select2();
                $('#department_id').val(null).select2();
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

            var packing = document.getElementById('packing1').value;
            var totalPack = (quantity / packing).toFixed(2);

            // alert(totalPack)
            document.getElementById('PackQty1').value = totalPack;
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
                $('#delete_warehouseID').val(warehouseID);
            });
             // Print Record
             $('.print_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                myModal.toggle();

                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                var base_url = $('#base_url').val();
                $.ajax({
                    url: "{{ URL::to('stock-consumption/print/voucher') }}",
                    data: {voucher_no:voucher_no, warehouseID:warehouseID},
                    type: 'get',
                    beforeSend: function(response) {
                        $('#print-receipt-modal-body').html(
                            '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                        );
                    },
                    success: function(response) {
                        if (response != null) {
                            $('#print-receipt-modal-body').html(
                                `<object data="${base_url}/resources/upload/opening-stock/${response}" type="application/pdf" width="100%" height="800"></object>`
                            );
                        } else {
                            alert('null');
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
                    url: "{{ URL::to('stock-consumption/load/record') }}",
                    type: 'get',
                    data: {voucher_no:voucher_no, warehouseID:warehouseID},
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                
                    success: function(response) {
                        // alert("success")
                        godownproducts(response.data[0].godownstock.warehouse.id);
                        if (response.data != '') {

                            var tableHtml = '';
                            var totalQty = 0;
                            var totalAmount = 0;
                            var sum = 0;
                            var Totalpacking = 0;
                            var Grandpacking = 0;

                            $.each(response.data, function(i, v) {
                                // alert("success")
                                if(v.product){
                                totalQty += parseFloat(v.qty_out);
                                totalAmount += parseInt(v.amount);
                                Totalpacking = v.qty_out/v.product.packing;
                                Grandpacking += Totalpacking;
                                sum += 1;
                                // <input type='text' name='total[]' id='total' value='${v.amount}' />
                                tableHtml += `<tr>`;
                                tableHtml +=`<td>${sum}</td>`;
                                tableHtml +=`<td>${v.product.code}</td>`;
                                tableHtml += `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                tableHtml +=`<td>${v.product.uom}</td>`;
                                tableHtml +=`<td>${v.product.packing}</td>`;
                                tableHtml +=`<td>${Number(v.qty_out).toLocaleString('en-US')}<input type='hidden' name='qty[]' id='qty' value='${v.qty_out}' /></td>`;
                                tableHtml +=`<td>${Totalpacking.toFixed(2)}</td>`;
                                tableHtml +=`<td>${Number(v.sale_rate).toLocaleString('en-US')}<input type='hidden' name='price[]' id='price' value='${v.sale_rate}' /></td>`;
                                tableHtml +=`<td>${Number(v.amount).toLocaleString('en-US')}</td>`;
                                tableHtml +=`<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                                }
                            });
                            
                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty.toLocaleString('en-US'));
                            $('#TotalPackQty').text(Grandpacking.toLocaleString('en-US'));
                            $('#TotalAmount').text(totalAmount.toLocaleString('en-US'));
                            $('#updated_by_name').removeClass('d-none');

                            $('#update_voucher_id').val(response.data[0].godownstock.id);
                            $('#date').val(response.data[0].godownstock.date);
                            $('#voucher_no').val(response.data[0].godownstock.voucher_no);
                            $('#voucher_no').focus();
                            $('#warehouse_id').val(response.data[0].godownstock.warehouse.id)
                                .select2();
                            $('#remarks').val(response.data[0].godownstock.remarks);
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
                            $('#warehouse_id').val(null).select2();
                            $('#warehouse_id').val(null);
                            $('#remarks').val(null);
                        }
                    }
                });
            });


            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                $.ajax({
                    url: "{{ URL::to('stock-consumption/load/next/record') }}",
                    type: 'get',
                    data: {voucher_no:voucher_no, warehouseID:warehouseID},
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        // alert("success")
                        godownproducts(response.data[0].godownstock.warehouse.id);
                        if (response.data != '') {

                            var tableHtml = '';
                            var totalQty = 0;
                            var totalAmount = 0;
                            var sum = 0;
                            var Totalpacking = 0;
                            var Grandpacking = 0;

                            $.each(response.data, function(i, v) {
                                // alert("success")
                                if(v.product){
                                totalQty += parseFloat(v.qty_out);
                                totalAmount += parseInt(v.amount);
                                Totalpacking = v.qty_out/v.product.packing;
                                Grandpacking += Totalpacking;
                                sum += 1;
                                // <input type='text' name='total[]' id='total' value='${v.amount}' />
                                tableHtml += `<tr>`;
                                tableHtml +=`<td>${sum}</td>`;
                                tableHtml +=`<td>${v.product.code}</td>`;
                                tableHtml += `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                tableHtml +=`<td>${v.product.uom}</td>`;
                                tableHtml +=`<td>${v.product.packing}</td>`;
                                tableHtml +=`<td>${Number(v.qty_out).toLocaleString('en-US')}<input type='hidden' name='qty[]' id='qty' value='${v.qty_out}' /></td>`;
                                tableHtml +=`<td>${Totalpacking.toFixed(2)}</td>`;
                                tableHtml +=`<td>${Number(v.sale_rate).toLocaleString('en-US')}<input type='hidden' name='price[]' id='price' value='${v.sale_rate}' /></td>`;
                                tableHtml +=`<td>${Number(v.amount).toLocaleString('en-US')}</td>`;
                                tableHtml +=`<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                                }
                            });
                            
                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty.toLocaleString('en-US'));
                            $('#TotalPackQty').text(Grandpacking.toLocaleString('en-US'));
                            $('#TotalAmount').text(totalAmount.toLocaleString('en-US'));
                            $('#updated_by_name').removeClass('d-none');

                            $('#update_voucher_id').val(response.data[0].godownstock.id);
                            $('#date').val(response.data[0].godownstock.date);
                            $('#voucher_no').val(response.data[0].godownstock.voucher_no);
                            $('#voucher_no').focus();
                            $('#warehouse_id').val(response.data[0].godownstock.warehouse.id)
                                .select2();
                            $('#remarks').val(response.data[0].godownstock.remarks);
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
                            $('#warehouse_id').val(null).select2();
                            $('#warehouse_id').val(null);
                            $('#remarks').val(null);
                        }
                    }
                });
            });
            // End Here of Load Next Record


            // Load Previous Record
            $('.load-previous-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                // alert("ss");
                // var WarehouseID = parseInt($('#warehouse_id').val());
                $.ajax({
                    url: "{{ URL::to('stock-consumption/load/previous/record') }}",
                    type: 'get',
                    data: {voucher_no:voucher_no, warehouseID:warehouseID},
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        // alert("success")
                        godownproducts(response.data[0].godownstock.warehouse.id);
                        if (response.data != '') {

                            var tableHtml = '';
                            var totalQty = 0;
                            var totalAmount = 0;
                            var sum = 0;
                            var Totalpacking = 0;
                            var Grandpacking = 0;

                            $.each(response.data, function(i, v) {
                                // alert("success")
                                if(v.product){
                                totalQty += parseFloat(v.qty_out);
                                totalAmount += parseInt(v.amount);
                                Totalpacking = v.qty_out/v.product.packing;
                                Grandpacking += Totalpacking;
                                sum += 1;
                                // <input type='text' name='total[]' id='total' value='${v.amount}' />
                                tableHtml += `<tr>`;
                                tableHtml +=`<td>${sum}</td>`;
                                tableHtml +=`<td>${v.product.code}</td>`;
                                tableHtml += `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                tableHtml +=`<td>${v.product.uom}</td>`;
                                tableHtml +=`<td>${v.product.packing}</td>`;
                                tableHtml +=`<td>${Number(v.qty_out).toLocaleString('en-US')}<input type='hidden' name='qty[]' id='qty' value='${v.qty_out}' /></td>`;
                                tableHtml +=`<td>${Totalpacking.toFixed(2)}</td>`;
                                tableHtml +=`<td>${Number(v.sale_rate).toLocaleString('en-US')}<input type='hidden' name='price[]' id='price' value='${v.sale_rate}' /></td>`;
                                tableHtml +=`<td>${Number(v.amount).toLocaleString('en-US')}</td>`;
                                tableHtml +=`<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                                }
                            });
                            
                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty.toLocaleString('en-US'));
                            $('#TotalPackQty').text(Grandpacking.toLocaleString('en-US'));
                            $('#TotalAmount').text(totalAmount.toLocaleString('en-US'));
                            $('#updated_by_name').removeClass('d-none');

                            $('#update_voucher_id').val(response.data[0].godownstock.id);
                            $('#date').val(response.data[0].godownstock.date);
                            $('#voucher_no').val(response.data[0].godownstock.voucher_no);
                            $('#voucher_no').focus();
                            $('#warehouse_id').val(response.data[0].godownstock.warehouse.id)
                                .select2();
                            $('#remarks').val(response.data[0].godownstock.remarks);
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
                            $('#warehouse_id').val(null).select2();
                            $('#warehouse_id').val(null);
                            $('#remarks').val(null);
                        }
                    }
                });
            });
            // End Here of Load Previous Record
            $('#warehouse_id').change(function() {
                // var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                // alert(warehouseID);
                $.ajax({
                    url: "{{ URL::to('stock-consumption/warehouse/voucherno') }}",
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
        });
    </script>
    <!-- End Load & Edit Record -->

    @include('include.toast-messages')
@stop
