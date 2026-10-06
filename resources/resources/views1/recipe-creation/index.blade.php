@extends('app')
@section('head')
    <title>Create Recipe</title>
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
                Create Recipe
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Create Recipe</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Create Recipe</h6>
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
                                    {!! Form::open(['url' => 'recipe-creation', 'class' => 'form-horizontal', 'id' => 'recipe-creation-form']) !!}
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
                                                'autofocus' => 'autofocus',
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
                                                'onkeypress' => 'return isNumberKey(event)'
                                            ]) !!}
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                            <label for="warehouse_id"><i class="fa fa-caret-right"></i> Warehouse<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('warehouse_id', $warehouse, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '4',
                                            ]) !!}
                                            @error('warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-5 col-md-6 col-sm-12 mt-1">
                                            <label for="product_id2"><i class="fa fa-caret-right"></i> Products<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('product_id2', $finish_gooods, null, [
                                                'id' => 'product_id2',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('product_id2')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                            <label for="unit_id2"><i class="fa fa-caret-right"></i> Recipe Name<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('recipe_name', null, [
                                                'id' => 'recipe_name',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ]) !!}
                                          
                                                <span class="recipe_name_err text-danger"></span>
                                           
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                            <label for="unit_id2"><i class="fa fa-caret-right"></i> Unit<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('unit_id2', null, [
                                                'id' => 'unit_id2',
                                                'class' => 'form-control',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                            <label for="recipe_code"><i class="fa fa-caret-right"></i> Recipe Code<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('recipe_code', null, [
                                                'id' => 'recipe_code',
                                                'class' => 'form-control',
                                                'tabindex' => '3',
                                                'placeholder' => 'Recipe Code',
                                                'readonly' => 'readonly',
                                            ]) !!}
                                            @error('recipe_code')
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
                                                            <th class="d-none">Code</th>
                                                            <th style="width:35%;">Product</th>
                                                            <th>Unit</th>
                                                            <th>Qty</th>
                                                            <th>Wasatge</th>
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
                                                                    'tabindex' => '8',
                                                                ]) !!}
                                                                
                                                            </td>
                                                            <td>
                                                                {!! Form::select('product_id1', $products, null, [
                                                                    'id' => 'product_id1',
                                                                    'class' => 'form-control select2',
                                                                    'tabindex' => '9',
                                                                ]) !!}
                                                                {!! Form::hidden('product_id3', null, ['id' => 'product_id3']) !!}
                                                                <span class="product_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('unit1', null, [
                                                                    'id' => 'unit1',
                                                                    'class' => 'form-control bg-white',
                                                                    'disabled' => 'disabled',
                                                                    'placeholder' => 'Unit',
                                                                    'tabindex' => '10',
                                                                ]) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('qty1', null, [
                                                                    'id' => 'qty1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Qty',
                                                                    'tabindex' => '11',
                                                                    'onkeyup' => 'QuantityKeyUp($(this).val())',
                                                                    'onkeypress' => 'return isNumberKey(event)'
                                                                ]) !!}
                                                                <span class="qty_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::select(
                                                                    'wastage1',
                                                                    ['' => 'Select Wastage', 'Actual' => 'Actual', 'Wastage' => 'Wastage'],
                                                                    null,
                                                                    [
                                                                        'id' => 'wastage1',
                                                                        'class' => 'form-control select2',
                                                                        'tabindex' => '12',
                                                                    ],
                                                                ) !!}
                                                                <span class="wastage_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('price1', null, [
                                                                    'id' => 'price1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Rate',
                                                                    'tabindex' => '13',
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
                                                                    'tabindex' => '14',
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
                                                                <th>Product</th>
                                                                <th>Unit</th>
                                                                <th>Qty</th>
                                                                <th>Wastage</th>
                                                                <th>Rate</th>
                                                                <th>Amount</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3"><strong>Total</strong></td>
                                                                <td class="bg-primary" id="TotalQty">0</td>
                                                                <td></td>
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
                <form action="{{ URL::to('recipe-creation/delete-voucher') }}" method="post" id="delete_voucher_form">
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


        $(document).ready(function() {
            $('#date').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#voucher_no").focus();
                }
            });
            $('#voucher_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#warehouse_id").select2('open');
                }
            });
          
            $('#product_id2').change(function(event) {
                var product_id = $(this).val();
                if (product_id) {
                    $('#product_id2').select2().trigger('select2:close');
                    $('#unit_id2').val(product_id.split('_')[3]);
                    $('#recipe_code').val(product_id.split('_')[1]);
                    $("#recipe_name").focus();
                    
                }
            });
            $('#recipe_name').keydown(function(event) {
                
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var recipeName = $(this).val();
                    
                    if(recipeName == "") {
                        // alert(recipeName)
                        $(this).focus();
                        $('.recipe_name_err').text('Recipe Name field is required');
                    } else {
                        // $('.qty_err').text('');
                        // $('#price1').focus();
                        // $("#wastage1").select2('open');
                        $("#product_id1").select2('open');
                    }


                    
                }
            });
            
            // $('#warehouse_id').change(function(event) {
            //     var warehouse_id = $(this).val();
            //     if (warehouse_id) {
            //         $('#warehouse_id').select2().trigger('select2:close');
            //         $("#product_id1").select2('open');
            //     }
            // });
            $('#warehouse_id').change(function() {
                var warehouse_id = $(this).val();
                $.ajax({
                    url: "{{ asset('recipe-creation/getWarehouseproduct') }}",
                    type: 'get',
                    data: {
                        warehouse_id: warehouse_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.length > 0) {
                            var option= '<option value="" selected>Select Product</option>';
                            $.each(response, function(i, v) {
                                option +=
                                    `<option value="${v.id}_${v.code}_${v.product_name}_${v.uom}">${v.code}-${v.product_name}</option>`;
                                //Cancatenate k liay
                            });
                            $('#product_id2').html(option);
                        } else {
                            var option = '<option value="" selected>Product Not Found</option>';
                            $('#product_id2').html(option);
                            $('#unit_id2').val('');
                            $('#recipe_code').val('');
                            
                        }
                        $("#product_id2").select2('open');
                    }
                });
                
            });
            $('#product_id1').change(function() {
                var product_id = $(this).val();
                $.ajax({
                    url: "{{ asset('recipe-creation/getProdutRocord') }}",
                    type: 'get',
                    data: {
                        product_id: product_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.length > 0) {
                            $('#product_id1').val(response[0].id);
                            $('#code1').val(response[0].code);
                            $('#product_id3').val(response[0].product_name);
                            $('#unit1').val(response[0].uom);
                            $('#price1').val(response[0].product_price);
                            $('#qty1').focus();

                        }
                    }
                });
            });
            $('#qty1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = parseFloat($(this).val());
                    if (qty <= 0) {
                        $(this).focus();
                        $('.qty_err').text('This field is required & Must be greater than zero');
                    } else {
                        $('.qty_err').text('');
                        $('#price1').focus();
                        $("#wastage1").select2('open');
                    }
                }
            });
            $('#wastage1').change(function(event) {
                var wastage = $(this).val();
                if (wastage) {
                    $('#wastage1').select2().trigger('select2:close');
                    $('#price1').select();
                }
            });
            $('#price1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('.product_id1_err').text('');
                    $('.qty_err').text('');
                    $('.price_err').text('');


                    var product_id = parseInt($('#product_id1').val());
                    var qty = parseFloat($('#qty1').val());
                    var price = parseFloat($('#price1').val());

                    if (!product_id || product_id <= 0) {

                        $('#product_id').select2('open');
                        $('.product_id1_err').text('This field is required');
                        return false;
                    } 
                    else
                    if (!qty || qty <= 0) {
                        // alert(1);
                        $('#qty1').focus();
                        $('.qty_err').text('This field is required & Must be greater than zero');
                        return false;
                    } 
                    else
                    if (!wastage1) {
                        // alert(1);
                        $('#wastage1').focus();
                        $('.wastage_err').text('This field is required');
                        return false;
                    } 
                    else
                    if (!price || price <= 0) {
                        $('#price1').focus();
                        $('.price_err').text('This field is required & Must be greater than zero');
                        return false;
                    } else {

                        AddGridData();
                        $('#wastage1').val('');
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



            var proCode = document.getElementById('code1').value;
            var pro_id = document.getElementById('product_id1').value;
            var pro_name = document.getElementById('product_id3').value;
            var pro_unit = document.getElementById('unit1').value;
            var rate = parseFloat(document.getElementById('price1').value);
            var qty = parseFloat(document.getElementById('qty1').value);
            var wastage = document.getElementById('wastage1').value;
            var total = parseFloat(document.getElementById('total1').value);
            var TotalQty = parseFloat(document.getElementById('TotalQty').innerHTML);
            var TotalAmount = parseFloat(document.getElementById('TotalAmount').innerHTML);

            var grandTotalQty = TotalQty + qty;
            var grandTotalAmount = TotalAmount + total;


            var tableHtml = `<tr>`;
            tableHtml += `<td>${proCode}</td>`;
            tableHtml += `<td>
                            ${pro_name}
                            <input type='hidden' name='product_id[]' id='product_id' value='${pro_id}' />
                        </td>`;
            tableHtml += `<td>${pro_unit}<input type='hidden' name='unit[]' id='unit' value='1' /></td>`;
            tableHtml +=
                `<td>${parseFloat(qty).toFixed(4)}<input type='hidden' name='qty[]' id='qty' value='${parseFloat(qty).toFixed(4)}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
            tableHtml +=
                `<td>${wastage}<input type='hidden' name='wastage[]' id='wastage' value='${wastage}' class='form-control' /></td>`;
            tableHtml +=
                `<td>${parseFloat(rate).toFixed(4)}<input type='hidden' name='rate[]' id='rate' value='${parseFloat(rate).toFixed(4)}' class='form-control' /></td>`;
            tableHtml +=
                `<td>${parseFloat(total).toFixed(4)}<input type='hidden' name='total[]' id='total' value='${parseFloat(total).toFixed(4)}' class='form-control' /></td>`;
            tableHtml +=
                `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
            tableHtml += `</tr>`;

            $('#GridTable').append(tableHtml);
            $('#code1').val(null);

            $('#unit1').val(null);
            $('#price1').val(null);
            $('#qty1').val(null);
            $('#total1').val(null);


            $('#TotalQty').html(grandTotalQty.toFixed(4));
            $('#TotalAmount').html(grandTotalAmount.toFixed(4));

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
                $('#recipe-creation-form').submit();
                $('.submit-form').attr('disabled', true);
            });
            // End FOrm Submit

            // Reset btn feature
            $('.reset-btn').click(function() {
                $('#product_id2').val(null).select2();
                $('#unit_id2').val(null);
                $('#recipe_code').val(null);
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
                sum += parseFloat(tableData.rows[i].cells[3].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalQty').innerText = sum;
        }

        function DeleteRow(row) {
            var TotalAmount = parseFloat(document.getElementById('TotalAmount').innerText);
            var NewAmount = parseFloat($(row).find("td:eq('5')").find('input').val());
            document.getElementById('TotalAmount').innerText = (TotalAmount - NewAmount);

            var TotalQty = parseFloat(document.getElementById('TotalQty').innerText);
            var NewQty = parseFloat($(row).find("td:eq('2')").find('input').val());
            document.getElementById('TotalQty').innerText = (TotalQty - NewQty);

            $(row).remove();
        }

        function PriceKeyUp(price) {
            var quantity = document.getElementById('qty1').value;
            if (quantity == '') {
                document.getElementById('total1').value = price;
            } else {
                var total = quantity * price;
                document.getElementById('total1').value = parseFloat(total.toFixed(4));
            }
        }

        function QuantityKeyUp(quantity) {
            var price = document.getElementById('price1').value;
            var total = quantity * price;
            document.getElementById('total1').value = parseFloat(total.toFixed(4));
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
                    url: "{{ URL::to('recipe-creation/print/voucher') }}?voucher_no=" +
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
                                `<object data="${base_url}/resources/upload/recipe-creation/${response}" type="application/pdf" width="100%" height="800"></object>`
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
                    url: "{{ URL::to('recipe-creation/load/record') }}?voucher_no=" + voucher_no,
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
                                totalQty += parseFloat(v.quantity);
                                totalAmount += parseFloat(v.amount);

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product.code}</td>`;
                                tableHtml +=
                                    `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom_id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.quantity}<input type='hidden' name='qty[]' id='qty' value='${v.quantity}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${v.wastage}<input type='hidden' name='wastage[]' id='wastage' value='${v.wastage}' class='form-control'/></td>`;
                                tableHtml +=
                                    `<td>${v.rate}<input type='hidden' name='rate[]' id='rate' value='${v.rate}' class='form-control' /></td>`;
                                tableHtml +=
                                    `<td>${v.amount}<input type='hidden' name='total[]' id='total' value='${v.amount}' class='form-control' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty.toFixed(4));
                            $('#TotalAmount').text(totalAmount.toFixed(4));
                            $('#updated_by_name').removeClass('d-none');

                            $('#voucher_no').val(response.data[0].recipe_creation.voucher_no);
                            $('#voucher_no').focus();
                            $('#update_voucher_id').val(response.data[0].recipe_creation.id);
                            $('#date').val(response.data[0].recipe_creation.date);
                            $('#product_id2').val(
                                    response.data[0].recipe_creation.product.id +
                                    '_' + response.data[0].recipe_creation.product
                                    .code +
                                    '_' + response.data[0].recipe_creation.product
                                    .product_name + '_' + response.data[0].recipe_creation
                                    .product.uom)
                                .select2();
                                $('#recipe_name').val(response.data[0].recipe_creation.recipe_name);
                            $('#unit_id2').val(response.data[0].recipe_creation.product.uom);
                            $('#warehouse_id').val(response.data[0].recipe_creation
                                .warehouse_id).select2();
                            $('#recipe_code').val(response.data[0].recipe_creation.product
                                .code);
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
                            $('#product_id2').val(null).select2();
                            $('#unit_id2').val(null);
                            $('#recipe_code').val(null);
                        }
                    }
                });
            });


            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('recipe-creation/load/next/record') }}?voucher_no=" +
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
                                totalQty += parseFloat(v.quantity);
                                totalAmount += parseFloat(v.amount);

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product.code}</td>`;
                                tableHtml +=
                                    `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom_id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.quantity}<input type='hidden' name='qty[]' id='qty' value='${v.quantity}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${v.wastage}<input type='hidden' name='wastage[]' id='wastage' value='${v.wastage}' class='form-control'/></td>`;
                                tableHtml +=
                                    `<td>${v.rate}<input type='hidden' name='rate[]' id='rate' value='${v.rate}' class='form-control' /></td>`;
                                tableHtml +=
                                    `<td>${v.amount}<input type='hidden' name='total[]' id='total' value='${v.amount}' class='form-control' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty.toFixed(4));
                            $('#TotalAmount').text(totalAmount.toFixed(4));
                            $('#updated_by_name').removeClass('d-none');

                            $('#voucher_no').val(response.data[0].recipe_creation.voucher_no);
                            $('#voucher_no').focus();
                            $('#update_voucher_id').val(response.data[0].recipe_creation.id);
                            $('#date').val(response.data[0].recipe_creation.date);
                            $('#product_id2').val(
                                    response.data[0].recipe_creation.product.id +
                                    '_' + response.data[0].recipe_creation.product
                                    .code +
                                    '_' + response.data[0].recipe_creation.product
                                    .product_name + '_' + response.data[0].recipe_creation
                                    .product.uom)
                                .select2();
                                $('#recipe_name').val(response.data[0].recipe_creation.recipe_name);
                            $('#unit_id2').val(response.data[0].recipe_creation.product.uom);
                            $('#warehouse_id').val(response.data[0].recipe_creation
                                .warehouse_id).select2();
                            $('#recipe_code').val(response.data[0].recipe_creation.product
                                .code);
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
                            $('#product_id2').val(null).select2();
                            $('#unit_id2').val(null);
                            $('#recipe_code').val(null);
                        }
                    }
                });
            });
            // End Here of Load Next Record


            // Load Previous Record
            $('.load-previous-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('recipe-creation/load/previous/record') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.data != '') {

                            var tableHtml = '';
                            var totalQty = 0;
                            var totalAmount = 0;


                            $.each(response.data, function(i, v) {
                                totalQty += parseFloat(v.quantity);
                                totalAmount += parseFloat(v.amount);

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product.code}</td>`;
                                tableHtml +=
                                    `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom_id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.quantity}<input type='hidden' name='qty[]' id='qty' value='${v.quantity}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
                                tableHtml +=
                                    `<td>${v.wastage}<input type='hidden' name='wastage[]' id='wastage' value='${v.wastage}' class='form-control'/></td>`;
                                tableHtml +=
                                    `<td>${v.rate}<input type='hidden' name='rate[]' id='rate' value='${v.rate}' class='form-control' /></td>`;
                                tableHtml +=
                                    `<td>${v.amount}<input type='hidden' name='total[]' id='total' value='${v.amount}' class='form-control' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty.toFixed(4));
                            $('#TotalAmount').text(totalAmount.toFixed(4));
                            $('#updated_by_name').removeClass('d-none');

                            $('#voucher_no').val(response.data[0].recipe_creation.voucher_no);
                            $('#voucher_no').focus();
                            $('#update_voucher_id').val(response.data[0].recipe_creation.id);
                            $('#date').val(response.data[0].recipe_creation.date);
                            $('#product_id2').val(
                                    response.data[0].recipe_creation.product.id +
                                    '_' + response.data[0].recipe_creation.product
                                    .code +
                                    '_' + response.data[0].recipe_creation.product
                                    .product_name + '_' + response.data[0].recipe_creation
                                    .product.uom)
                                .select2();
                            $('#recipe_name').val(response.data[0].recipe_creation.recipe_name);
                            $('#unit_id2').val(response.data[0].recipe_creation.product.uom);
                            $('#warehouse_id').val(response.data[0].recipe_creation
                                .warehouse_id).select2();
                            $('#recipe_code').val(response.data[0].recipe_creation.product
                                .code);
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
                            $('#product_id2').val(null).select2();
                            $('#unit_id2').val(null);
                            $('#recipe_code').val(null);
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
