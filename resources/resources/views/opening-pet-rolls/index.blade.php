@extends('app')
@section('head')
    <title>Opening Pet Rolls</title>
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
            <h1>Opening Pet Rolls</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Opening Pet Rolls</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>Opening Pet Rolls</h6>
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
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> {{ Session::get('failure_message') }}
                                        </div>
                                    @endif
                                    <div id="show_err"></div>
                                    @include('errors.validation')
                                    {!! Form::open(['url' => 'opening-pet-rolls', 'class' => 'form-horizontal', 'id' => 'opening-stock-form']) !!}
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
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @if(Auth::user()->role == "Admin")
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                            <label for="warehouse_id"><i class="fa fa-caret-right"></i> Godown<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('warehouse_id', $warehouses, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @else
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                            <label for="warehouse_id"><i class="fa fa-caret-right"></i> Godown<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('warehouse_id', $singlewarehouses, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                                'required' => 'required'
                                            ]) !!}
                                            @error('warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
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
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-1">
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
                                                            <!-- <th>Code</th> -->
                                                            <th>Product</th>
                                                            <th>Batch.No</th>
                                                            <th>Thickness</th>
                                                            <th>Width</th>
                                                            <th>Color</th>
                                                            <th>Gross.Wgt</th>
                                                            <th>Net.Wgt</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="bg-secondary">
                                                            <!-- <td>
                                                                {!! Form::text('code1', null, [
                                                                    'id' => 'code1',
                                                                    'class' => 'form-control bg-white',
                                                                    'readonly' => 'readonly',
                                                                    'tabindex' => '7',
                                                                ]) !!}
                                                                {!! Form::hidden('code2', null, ['id' => 'code2']) !!}
                                                            </td> -->
                                                            @if(Auth::user()->role == "Admin")
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
                                                                {!! Form::select('product_id1', $warehouseproducts, null, [
                                                                    'id' => 'product_id1',
                                                                    'class' => 'form-control select2',
                                                                    'tabindex' => '8',
                                                                ]) !!}
                                                                <span class="product_err text-danger"></span>
                                                            </td>
                                                            @endif
                                                            <td>
                                                                {!! Form::text('batchNo1', null, [
                                                                    'id' => 'batchNo1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Batch No',
                                                                    'tabindex' => '9',
                                                                ]) !!}
                                                                <span class="batchNo1_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('thickness1', null, [
                                                                    'id' => 'thickness1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Thickness',
                                                                    'onkeypress' => "return onlyNumberKey(event)",
                                                                ]) !!}
                                                                <span class="thickness1_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('width1', null, [
                                                                    'id' => 'width1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Width',
                                                                    'tabindex' => '10',
                                                                    'onkeyup' => 'QuantityKeyUp($(this).val())',
                                                                    'onkeypress' => "return onlyNumberKey(event)",
                                                                ]) !!}
                                                                <span class="width1_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::select('color1', $color, null, [
                                                                    'id' => 'color1',
                                                                    'class' => 'form-control select2'
                                                                ]) !!}
                                                                <span class="color1_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('gross_weight1', null, [
                                                                    'id' => 'gross_weight1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Gross Weight',
                                                                    'onkeypress' => "return onlyNumberKey(event)",
                                                                ]) !!}
                                                                <span class="gross_weight1_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('net_weight1', null, [
                                                                    'id' => 'net_weight1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Net Weight',
                                                                    'onkeypress' => "return onlyNumberKey(event)",
                                                                ]) !!}
                                                                <span class="gross_weight1_err text-danger"></span>
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
                                                                <!-- <th>Code</th> -->
                                                                <th style="width: 30%;">Product</th>
                                                                <th>Batch.No</th>
                                                            <th>Thickness</th>
                                                            <th>Width</th>
                                                            <th>Color</th>
                                                            <th>Gross.Wgt</th>
                                                            <th>Net.Wgt</th>
                                                            <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="4"><strong>Total</strong></td>
                                                                <!-- <td class="bg-primary" id="TotalQty">0</td> -->
                                                                <td></td>
                                                                <td></td>
                                                                
                                                                <td class="bg-success" id="TotalGross">0</td>
                                                                <td class="bg-success" id="TotalNet">0</td>
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
                <form action="{{ URL::to('opening-pet-rolls/delete-voucher') }}" method="post" id="delete_voucher_form">
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

           
            $('#voucher_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#warehouse_id").select2('open');
                }
            });
            $('#warehouse_id').change(function(event) {
                var warehouse_id = $(this).val();
                if (warehouse_id) {
                    $('#warehouse_id').select2().trigger('select2:close');
                    $('#remarks').focus();
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
                    // $('#code1').val(product_id.split('_')[1]);
                    // $('#code2').val(product_id.split('_')[1]);
                    // $('#unit1').val(product_id.split('_')[3]);
                    $('#product_id1').select2().trigger('select2:close');
                    // $('#price1').val(parseInt(product_id.split('_')[4]));
                    // $('#packing1').val(parseInt(product_id.split('_')[5]));
                    // $('#PackQty1').val(parseInt(product_id.split('_')[5]));
                    // $('#qty1').val(1);
                    $('#batchNo1').focus();
                }
            });
            $('#batchNo1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = $(this).val();
                    if (qty <= 0) {
                        $(this).focus();
                        $('.batchNo1_err').text('This field is required & Must be greater than zero');
                    } else {
                        $('#thickness1').focus();
                    }
                }
            });

            $('#thickness1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = $(this).val();
                    if (qty <= 0) {
                        $(this).focus();
                        $('.thickness1_err').text('This field is required & Must be greater than zero');
                    } else {
                        $('#width1').focus();
                    }
                }
            });

            $('#width1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = $(this).val();
                    if (qty <= 0) {
                        $(this).focus();
                        $('.width1_err').text('This field is required & Must be greater than zero');
                    } else {
                        // $('#color1').focus();
                        $("#color1").select2('open');
                    }
                }
            });

            $('#color1').change(function(event) {
                var color1 = $(this).val();
                if (color1 != null) {
                    // $('#code1').val(product_id.split('_')[1]);
                    // $('#code2').val(product_id.split('_')[1]);
                    // $('#unit1').val(product_id.split('_')[3]);
                    $('#color1').select2().trigger('select2:close');
                    // $('#price1').val(parseInt(product_id.split('_')[4]));
                    // $('#packing1').val(parseInt(product_id.split('_')[5]));
                    // $('#PackQty1').val(parseInt(product_id.split('_')[5]));
                    // $('#qty1').val(1);
                    $('#gross_weight1').focus();
                }
            });

            // $('#color1').keydown(function(event) {
            //     var keycode = (event.keyCode ? event.keyCode : event.which);
            //     if (keycode == '13') {
            //         var qty = $(this).val();
            //         if (qty <= 0) {
            //             $(this).focus();
            //             $('.color1_err').text('This field is required & Must be greater than zero');
            //         } else {
            //             $('#gross_weight1').focus();
            //         }
            //     }
            // });

            $('#gross_weight1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = $(this).val();
                    if (qty <= 0) {
                        $(this).focus();
                        $('.gross_weight1_err').text('This field is required & Must be greater than zero');
                    } else {
                        $('#net_weight1').focus();
                    }
                }
            });



            $('#net_weight1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('.product_id1_err').text('');
                    $('.qty_err').text('');
                    $('.price_err').text('');


                    var product_id = $('#product_id1').val();

                    var price = $('#net_weight1').val();

                    if (!product_id) {
                        $('#product_id').select2('open');
                        $('.product_id1_err').text('This field is required');
                        return false;
                    } else
                    if (!price || price <= 0) {
                        $('#net_weight1').focus();
                        $('.net_weight1_err').text('This field is required & Must be greater than zero');
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
        $('#warehouse_id').change(function() {
            var WarehouseID = $('#warehouse_id').val();
            // $('#remarks').focus();
            // alert(WarehouseID)
            $.ajax({
                    url: "{{ URL::to('opening-pet-rolls/change/warehouse') }}?WarehouseID=" +
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
            
        });

        function AddGridData() {
            
            var product_id = $('#product_id1').val();
           
            var pro_id = document.getElementById('product_id1').value.split('_')[0];
            var pro_code = document.getElementById('product_id1').value.split('_')[1];
            var pro_name = document.getElementById('product_id1').value.split('_')[2];
            // var pro_unit = document.getElementById('product_id1').value.split('_')[3];
            // var product_unit_id = document.getElementById('product_id1').value.split('_')[4];
            // var pro_cost = parseInt(document.getElementById('product_id1').value.split('_')[7]);
            var batchNo = document.getElementById('batchNo1').value;
            var thickness = document.getElementById('thickness1').value;
            var width1 = document.getElementById('width1').value;
            var colorID = document.getElementById('color1').value.split('_')[0];
            var colorName = document.getElementById('color1').value.split('_')[1];
            var gross_weight = document.getElementById('gross_weight1').value;
            var net_weight = document.getElementById('net_weight1').value;
            // alert(net_weight)
            // var TotalQty = parseInt(document.getElementById('TotalQty').innerHTML);
             var TotalGrossVal = parseInt(document.getElementById('TotalGross').innerHTML);
             var TotalNetVal = parseInt(document.getElementById('TotalNet').innerHTML);

            // var cost_amount = price * qty;
            // var sale_amount = price * qty;
             var grandGrossWeight = parseFloat(TotalGrossVal) + parseFloat(gross_weight);
             var grandNetWeight = parseFloat(TotalNetVal) + parseFloat(net_weight);
            //  alert(grandGrossWeight)
            //  alert(grandNetWeight)
            // var grandTotalAmount = parseInt(TotalAmount) + parseInt(total);
            var totalRowCount = GridTable.rows.length;
            // alert(totalRowCount)
            var tableHtml = `<tr>`;
            tableHtml +=
                `<td>${totalRowCount+1}</td>`;
            // tableHtml +=
            //     `<td>${pro_code}<input type='hidden' name='code[]' id='code' value='${pro_code}' /></td>`;
            tableHtml += `<td>
                            ${pro_name}
                            <input type='hidden' name='product_id[]' id='product_id' value='${pro_id}' />
                        </td>`;
            tableHtml += `<td>${batchNo}<input type='hidden' name='batchNo[]' id='batchNo' value='${batchNo}' /></td>`;
            tableHtml += `<td>${thickness.toLocaleString('en-US')}<input type='hidden' name='thickness[]' id='thickness' value='${thickness}' /></td>`;
            tableHtml +=`<td>${width1.toLocaleString('en-US')}<input type='hidden' name='width[]' id='width' value='${width1}' /></td>`;
            tableHtml += `<td>${colorName.toLocaleString('en-US')}<input type='hidden' name='color[]' id='color' value='${colorID}' /></td>`;
            tableHtml +=`<td>${gross_weight.toLocaleString('en-US')}<input type='hidden' name='gross_weight[]' id='gross_weight' value='${gross_weight}' /></td>`;
            tableHtml +=`<td>${net_weight.toLocaleString('en-US')}<input type='hidden' name='net_weight[]' id='net_weight' value='${net_weight}' /></td>`;
            tableHtml +=
                `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
            tableHtml += `</tr>`;
            $('#GridTable').append(tableHtml);
            $('#batchNo1').val(null);
            $('#thickness1').val(null);
            $('#width1').val(null);
            $('#color1').val(null);
            $('#gross_weight1').val(null);
            $('#net_weight1').val(null);


            // $('#TotalQty').html(grandTotalQty.toLocaleString('en-US'));
            $('#TotalGross').html(grandGrossWeight.toLocaleString('en-US'));
            $('#TotalNet').html(grandNetWeight.toLocaleString('en-US'));

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
            var TotalAmount = parseInt(document.getElementById('TotalGross').innerText);
            var NewAmount = parseInt($(row).find("td:eq('6')").find('input').val());
            document.getElementById('TotalGross').innerText = (TotalAmount - NewAmount);

            var TotalQty = parseInt(document.getElementById('TotalNet').innerText);
            var NewQty = parseInt($(row).find("td:eq('7')").find('input').val());
            document.getElementById('TotalNet').innerText = (TotalQty - NewQty);

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
                $('#delete_voucher_no').val(voucher_no);
            });
             // Print Record
             $('.print_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                myModal.toggle();

                var voucher_no = parseInt($('#voucher_no').val());
                var base_url = $('#base_url').val();
                $.ajax({
                    url: "{{ URL::to('opening-pet-rolls/print/voucher') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    beforeSend: function(response) {
                        $('#print-receipt-modal-body').html(
                            '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                        );
                    },
                    success: function(response) {
                        if (response != null) {
                            $('#print-receipt-modal-body').html(
                                `<object data="${base_url}/resources/upload/opening-pet-rolls/${response}" type="application/pdf" width="100%" height="800"></object>`
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
                $.ajax({
                    url: "{{ URL::to('opening-pet-rolls/load/record') }}?voucher_no=" + voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                
                    success: function(response) {
                        // alert("success")
                        if (response.petstock != '') {

                            var tableHtml = '';
                            var sum = 0;
                            var TotalGross = 0;
                            var TotalNet = 0;


                            $.each(response.petstock[0].opening_pet_rolls, function(i, v) {
                                // alert("loop")
                                sum += 1;
                                TotalGross += parseFloat(v.gross_weight);
                                TotalNet += parseFloat(v.net_weight);
                                // <input type='text' name='total[]' id='total' value='${v.amount}' />

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${sum}</td>`;
                               tableHtml +=
                                    `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                              
                                tableHtml +=
                                    `<td>${v.batchNo}<input type='hidden' name='batchNo[]' id='batchNo' value='${v.batchNo}' /></td>`;
                                tableHtml +=
                                    `<td>${v.thickness}<input type='hidden' name='thickness[]' id='thickness' value='${v.thickness}' /></td>`;
                                tableHtml +=
                                    `<td>${v.width}<input type='hidden' name='width[]' id='width' value='${v.width}' /></td>`;
                                   
                                    tableHtml +=
                                    `<td>${v.color.name}<input type='hidden' name='color[]' id='color' value='${v.color.id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.gross_weight}<input type='hidden' name='gross_weight[]' id='gross_weight' value='${v.gross_weight}' /></td>`;
                                tableHtml +=
                                 `<td>${v.net_weight}<input type='hidden' name='net_weight[]' id='net_weight' value='${v.net_weight}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalGross').text(TotalGross.toLocaleString('en-US'));
                            $('#TotalNet').text(TotalNet.toLocaleString('en-US'));
                            $('#update_voucher_id').val(response.petstock[0].id);
                            $('#date').val(response.petstock[0].date);
                            $('#voucher_no').val(response.petstock[0].voucher_no);
                            $('#warehouse_id').val(response.petstock[0].to_warehouse_id)
                                .select2();
                            // $('#warehouse_id').val(response.data[0].godownstock.id);
                            $('#remarks').val(response.petstock[0].remarks);
                            $('#voucher_no').focus();

                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );


                        }
                    }
                });
            });


            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('opening-pet-rolls/load/next/record') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        // alert("success")
                        if (response.petstock != '') {

                            var tableHtml = '';
                            var sum = 0;
                            var TotalGross = 0;
                            var TotalNet = 0;


                            $.each(response.petstock[0].opening_pet_rolls, function(i, v) {
                                // alert("loop")
                                sum += 1;
                                TotalGross += parseFloat(v.gross_weight);
                                TotalNet += parseFloat(v.net_weight);
                                // <input type='text' name='total[]' id='total' value='${v.amount}' />

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${sum}</td>`;
                               tableHtml +=
                                    `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                              
                                tableHtml +=
                                    `<td>${v.batchNo}<input type='hidden' name='batchNo[]' id='batchNo' value='${v.batchNo}' /></td>`;
                                tableHtml +=
                                    `<td>${v.thickness}<input type='hidden' name='thickness[]' id='thickness' value='${v.thickness}' /></td>`;
                                tableHtml +=
                                    `<td>${v.width}<input type='hidden' name='width[]' id='width' value='${v.width}' /></td>`;
                                   
                                    tableHtml +=
                                    `<td>${v.color.name}<input type='hidden' name='color[]' id='color' value='${v.color.id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.gross_weight}<input type='hidden' name='gross_weight[]' id='gross_weight' value='${v.gross_weight}' /></td>`;
                                tableHtml +=
                                 `<td>${v.net_weight}<input type='hidden' name='net_weight[]' id='net_weight' value='${v.net_weight}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalGross').text(TotalGross.toLocaleString('en-US'));
                            $('#TotalNet').text(TotalNet.toLocaleString('en-US'));
                            $('#update_voucher_id').val(response.petstock[0].id);
                            $('#date').val(response.petstock[0].date);
                            $('#voucher_no').val(response.petstock[0].voucher_no);
                            $('#warehouse_id').val(response.petstock[0].to_warehouse_id)
                                .select2();
                            // $('#warehouse_id').val(response.data[0].godownstock.id);
                            $('#remarks').val(response.petstock[0].remarks);
                            $('#voucher_no').focus();

                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );


                        }
                    }
                });
            });
            // End Here of Load Next Record


            // Load Previous Record
            $('.load-previous-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('opening-pet-rolls/load/previous/record') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        // alert("success")
                        if (response.petstock != '') {

                            var tableHtml = '';
                            var sum = 0;
                            var TotalGross = 0;
                            var TotalNet = 0;


                            $.each(response.petstock[0].opening_pet_rolls, function(i, v) {
                                // alert("loop")
                                sum += 1;
                                TotalGross += parseFloat(v.gross_weight);
                                TotalNet += parseFloat(v.net_weight);
                                // <input type='text' name='total[]' id='total' value='${v.amount}' />

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${sum}</td>`;
                               tableHtml +=
                                    `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                              
                                tableHtml +=
                                    `<td>${v.batchNo}<input type='hidden' name='batchNo[]' id='batchNo' value='${v.batchNo}' /></td>`;
                                tableHtml +=
                                    `<td>${v.thickness}<input type='hidden' name='thickness[]' id='thickness' value='${v.thickness}' /></td>`;
                                tableHtml +=
                                    `<td>${v.width}<input type='hidden' name='width[]' id='width' value='${v.width}' /></td>`;
                                   
                                tableHtml +=
                                    `<td>${v.color.name}<input type='hidden' name='color[]' id='color' value='${v.color.id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.gross_weight}<input type='hidden' name='gross_weight[]' id='gross_weight' value='${v.gross_weight}' /></td>`;
                                tableHtml +=
                                 `<td>${v.net_weight}<input type='hidden' name='net_weight[]' id='net_weight' value='${v.net_weight}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalGross').text(TotalGross.toLocaleString('en-US'));
                            $('#TotalNet').text(TotalNet.toLocaleString('en-US'));
                            $('#update_voucher_id').val(response.petstock[0].id);
                            $('#date').val(response.petstock[0].date);
                            $('#voucher_no').val(response.petstock[0].voucher_no);
                            $('#warehouse_id').val(response.petstock[0].to_warehouse_id)
                                .select2();
                            // $('#warehouse_id').val(response.data[0].godownstock.id);
                            $('#remarks').val(response.petstock[0].remarks);
                            $('#voucher_no').focus();

                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );


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
