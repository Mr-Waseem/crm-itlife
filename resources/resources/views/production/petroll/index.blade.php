@extends('app')
@section('head')
    <title>Pet Roll Production</title>
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
    <body onload="LoadLastRecipe()">
    <!-- <body> -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
            Pet Roll Production
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Create Pet Roll Production</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <!-- <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Create Production</h6>
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
                                    {!! Form::open(['url' => 'petroll-production', 'class' => 'form-horizontal', 'id' => 'production-form']) !!}
                                    {!! Form::hidden('update_voucher_id', null, ['id' => 'update_voucher_id']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
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
                                        <div class="col-lg-1 col-md-6 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>Vr#<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $codes, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)"
                                            ]) !!}
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-5 col-md-6 col-sm-12 mt-1">
                                            <label for="product_id2"><i class="fa fa-caret-right"></i> Select Product<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('product_id2', $recipe_products, null, [
                                                'id' => 'product_id2',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('product_id2')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>G.Weight Value<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('gweight_value', null, [
                                                'id' => 'gweight_value',
                                                'class' => 'form-control',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                            @error('gweight_value')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-1 col-md-6 col-sm-12 mt-1">
                                            <label for="unit_id2"><i class="fa fa-caret-right"></i> Unit<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('unit_id2', null, [
                                                'id' => 'unit_id2',
                                                'class' => 'form-control bg-white',
                                                'disabled' => 'disabled',
                                                'tabindex' => '3',
                                            ]) !!}
                                            {!! Form::hidden('unit_id3', null, ['id' => 'unit_id3']) !!}
                                        </div>
                                        {{-- <div class="col-lg-4 col-md-12 col-sm-12 mt-1"></div> --}}
                                    
                                  
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="total_qty"><i class="fa fa-caret-right"></i> Quantity (Net Wght)<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('total_qty', null, [
                                                'id' => 'total_qty',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Quantity',
                                                'onkeyup' => 'TotalQtyChange($(this).val())',
                                                'onkeypress'=>"return isNumberKey(event)",
                                                'autofocus' => 'autofocus',
                                            ]) !!}
                                            @error('total_qty')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="total_qty"><i class="fa fa-caret-right"></i>Gross Weight<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('gross_weight', null, [
                                                'id' => 'gross_weight',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Quantity',
                                                'onkeypress'=>"return isNumberKey(event)"
                                            ]) !!}
                                            @error('total_qty')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        {{-- <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="total_rate1"><i class="fa fa-caret-right"></i> Cost Rate<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('total_rate1', null, [
                                                'id' => 'total_rate1',
                                                'class' => 'form-control',
                                                'tabindex' => '5',
                                                'placeholder' => 'Cost Rate',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            {!! Form::hidden('total_rate', null, ['id' => 'total_rate']) !!}
                                            {!! Form::hidden('actual_total_rate', null, ['id' => 'actual_total_rate']) !!}
                                            @error('total_rate')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div> --}}
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="total_amount1"><i class="fa fa-caret-right"></i> Total Cost Amount<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('total_amount1', null, [
                                                'id' => 'total_amount1',
                                                'class' => 'form-control',
                                                'tabindex' => '6',
                                                'placeholder' => 'Total Cost Amount',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            {!! Form::hidden('total_amount', null, ['id' => 'total_amount']) !!}
                                            @error('total_amount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i> Remarks<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('remarks', null, [
                                                'id' => 'remarks',
                                                'class' => 'form-control',
                                                'tabindex' => '4',
                                            ]) !!}
                                            
                                        </div>

                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i>Color<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('color_id', $color, null, [
                                                'id' => 'color_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '4',
                                            ]) !!}
                                            
                                        </div>
                                    
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="unit_id2"><i class="fa fa-caret-right"></i> Machine<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('machine_id', $machines, null, [
                                                'id' => 'machine_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '3',
                                                'onkeypress'=>"return isNumberKey(event)"
                                            ]) !!}
                                        </div>
                                    
                                  
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="total_qty"><i class="fa fa-caret-right"></i> Shift<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('shift_id', $shift, null, [
                                                'id' => 'shift_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '4',
                                                'onkeypress'=>"return isNumberKey(event)"
                                            ]) !!}
                                            @error('total_qty')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="total_amount1"><i class="fa fa-caret-right"></i>Forman<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('forman_id', $forman, null, [
                                                'id' => 'forman_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '6',
                                            ]) !!}
                                            @error('total_amount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="total_amount1"><i class="fa fa-caret-right"></i>Operator<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('operator_id', $operator, null, [
                                                'id' => 'operator_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '6',
                                            ]) !!}
                                            @error('total_amount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="total_amount1"><i class="fa fa-caret-right"></i>Thickness<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('thickness', null, [
                                                'id' => 'thickness',
                                                'class' => 'form-control',
                                                'tabindex' => '6',
                                            ]) !!}
                                            @error('total_amount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="total_amount1"><i class="fa fa-caret-right"></i>Width<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('width', null, [
                                                'id' => 'width',
                                                'class' => 'form-control',
                                                'tabindex' => '6',
                                            ]) !!}
                                            @error('total_amount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    
                                    </div>
                                
                                    <br/>
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
    <div class="modal fade" id="delete-record-modal" tabindex="-1" role="dialogproduction-form"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash text-danger"></i> Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ URL::to('petroll-production/delete-voucher') }}" method="post" id="delete_voucher_form">
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
</body>   
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
                    $("#product_id2").select2('open');
                }
                if (keycode == '9') {
                    $('#production-form').submit();
                    $('.submit-form').attr('disabled', true);
                }
            });

            $('#remarks').keydown(function(event) {
                //  $('#operator_id').select2().trigger('select2:close');
                var keycode = (event.keyCode ? event.keyCode : event.which);
                 if (keycode == '13') {
                    $('#color_id').select2('open');
                }
                if (keycode == '9') {
                    $('#production-form').submit();
                    $('.submit-form').attr('disabled', true);
                }
                 
            });
            $('#color_id').change(function(event) {
                 $('#color_id').select2().trigger('select2:close');
                     $("#machine_id").select2('open');
            });

            // $('#color').keydown(function(event) {
            //     var keycode = (event.keyCode ? event.keyCode : event.which);
            //     if (keycode == '13') {
            //         $("#machine_id").select2('open');
            //     }
            // });
            $('#machine_id').change(function(event) {
                 $('#machine_id').select2().trigger('select2:close');
                     $("#shift_id").select2('open');
            });
            $('#shift_id').change(function(event) {
                 $('#shift_id').select2().trigger('select2:close');
                     $("#forman_id").select2('open');
            });
            $('#forman_id').change(function(event) {
                 $('#forman_id').select2().trigger('select2:close');
                     $("#operator_id").select2('open');
            });
            $('#operator_id').change(function(event) {
                 $('#operator_id').select2().trigger('select2:close');
                 $('#thickness').focus();
            });

            $('#thickness').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                 if (keycode == '13') {
                    $('#width').focus();
                }
                if (keycode == '9') {
                    $('#production-form').submit();
                    $('.submit-form').attr('disabled', true);
                }
            });

            $('#width').keydown(function(event) {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    // alert("dd")
                    if (keycode == '13') {
                        $('#production-form').submit();
                        $('.submit-form').attr('disabled', true);
                        // $('#qty').focus();
                        // $('#qty').select();
                    }
                    if (keycode == '9') {
                    $('#production-form').submit();
                    $('.submit-form').attr('disabled', true);
                }
                });


            $('#product_id2').change(function(event) {
                var ProductID = $(this).val();
                // var RecipeYesno = $('#recipe_yesno').val();
                
                // if(RecipeYesno != 0){
                    // alert(RecipeYesno);
                    if (ProductID) {
                    $('#recipe_id').select2().trigger('select2:close');
                    $.ajax({
                        url: "{{ URL::to('petroll-production/recipeshow/recipename') }}",
                        data: {ProductID:ProductID},
                        type: 'get',
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#show_err').html(
                                '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait! Recipe Loading...</div>'
                            );
                        },
                        
                        success: function(response) {
                        //     if(RecipeYesno != 0){
                        //         if (response.data != '') {
                        //             var option = '';
                        //             option +=`<option value="">Select Recipe</option>`;
                        //             $.each(response.data, function(i, v) {
                        //                 option +=`<option value="${v.id}">${v.recipe_name}</option>`;
                        //             });
                        //             $('#recipe_id').html(option);
                        //             $("#recipe_id").select2('open');
                        //         } else 
                        //         {
                        //             var option = '';
                        //             option +=`<option value="">No Recipies Found</option>`;
                        //             $('#recipe_id').html(option);
                        //             $("#recipe_id").select2('open');
                        //         }
                        // }
                        
                        // else{
                            $('#product_id2').select2().trigger('select2:close');
                            $('#unit_id2').val(response.data.uom);
                            $('#unit_id3').val(response.data.uom);
                            // gweight_value
                            $("#gweight_value").select();
                            // $("#total_qty").select();
                        // }
                    }
                    });
                }
                // }else{
                //     // alert(4);
                //     $('#product_id2').select2().trigger('select2:close');
                //     $("#total_qty").select();
                // }
               
            });

            $('#recipe_id').change(function(event) {
                var RecipeID = $(this).val();
                if (RecipeID) {
                    $('#recipe_id').select2().trigger('select2:close');
                    $.ajax({
                        url: "{{ URL::to('petroll-production/recipe/products') }}?RecipeID=" +
                        RecipeID,
                        type: 'get',
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#show_err').html(
                                '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait! Recipe Loading...</div>'
                            );
                        },
                        success: function(response) {
                            if (response.data != '') {
                                var tableHtml = '';
                                var totalQty = 0;
                                var totalRate = 0;
                                var totalAmount = 0;

                                $.each(response.data, function(i, v) {
                                    totalQty += parseFloat(v.quantity);
                                    totalRate += parseFloat(v.rate);
                                    totalAmount += parseFloat(v.amount);

                                    tableHtml += `<tr>`;
                                    tableHtml +=
                                        `<td>${v.product.code}</td>`;
                                    tableHtml +=
                                        `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom_id}' /></td>`;
                                    tableHtml +=
                                        `<td>${v.wastage}<input type='hidden' name='status[]' id='status' value='${v.wastage}'/></td>`;
                                    tableHtml +=
                                        `<td>${v.quantity}<input type='hidden' name='recipe_qty[]' id='recipe_qty' value='${v.quantity}'/></td>`;
                                    tableHtml +=
                                        `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.quantity}' class='form-control' 
                                        onkeyup="changenewQty($(this).closest('tr'));" 
                                        onkeydown="EnterKeyBoard($(this).closest('tr'));"
                                        onkeypress="return isNumberKey(event)"/>
                                            <span class="qty_err text-danger"></span>
                                            </td>`;
                                    tableHtml +=
                                        `<td style="width:120px;">${v.quantity}<input type='hidden' name='showqty[]' id='showqty' value='${v.quantity}'/></td>`;
                                    tableHtml +=
                                        `<td style="width:140px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' readonly='readonly'/></td>`;
                                    tableHtml +=
                                        `<td style="width:150px;"><input type='text' name='total[]' id='total' value='${v.amount}' class='form-control' readonly='readonly'/></td>`;
                                    // tableHtml +=
                                    //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;
                                });

                                $('#GridTable').html(tableHtml);
                                $('#TotalQty').text(parseFloat(totalQty).toFixed(4));
                                $('#TotalAmount').text(parseFloat(totalAmount).toFixed(4));
                                $('#updated_by_name').removeClass('d-none');


                                // $('#total_qty').val(0);
                                $('#actual_total_rate').val(parseFloat(totalRate).toFixed(4));
                                $('#total_rate1').val(parseFloat(totalRate).toFixed(4));
                                $('#total_rate').val(parseFloat(totalRate).toFixed(4));
                                $('#total_amount1').val(parseFloat(totalAmount).toFixed(4));
                                $('#total_amount').val(parseFloat(totalAmount).toFixed(4));
                                $('#unit_id2').val(response.data[0].recipe_creation.product
                                    .uom);
                                $('#unit_id3').val(response.data[0].recipe_creation.product
                                    .uom_id);
                                $('#total_qty').select();    
                            } else {
                                $('#show_err').html(
                                    '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                                );

                                $('#GridTable').html(null);
                                $('#TotalQty').text(0);
                                $('#TotalAmount').text(0);
                                $('#updated_by_name').addClass('d-none');

                                // $('#total_qty').val(0);
                                $('#total_rate1').val(0);
                                $('#total_rate').val(0);
                                $('#total_amount1').val(0);
                                $('#total_amount').val(0);
                                $('#unit_id2').val(null);
                                $('#unit_id3').val(null);
                            }
                        }
                    });
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
                    $('#total1').val(parseInt(product_id.split('_')[4]));
                    $('#qty1').val(1);
                    $('#qty1').focus();
                }
            });

            

            $('#gweight_value').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#total_qty').focus();
                }
                if (keycode == '9') {
                    $('#production-form').submit();
                    $('.submit-form').attr('disabled', true);
                }
            });

            $('#total_qty').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#gross_weight').focus();
                }
                //tab buttton form submit
                if (keycode == '9') {
                    $('#production-form').submit();
                    $('.submit-form').attr('disabled', true);
                }
            });

            $('#gross_weight').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#remarks').focus();
                }
                if (keycode == '9') {
                    $('#production-form').submit();
                    $('.submit-form').attr('disabled', true);
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
                if (keycode == '9') {
                    $('#production-form').submit();
                    $('.submit-form').attr('disabled', true);
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
                if (keycode == '9') {
                    $('#production-form').submit();
                    $('.submit-form').attr('disabled', true);
                }
            });
        });
    </script>
    <!-- End Focus on next field -->

    <!-- Append New Data on Table -->
    <script>
              function EnterKeyBoard(row){ 
                var RowIndex = row.index();
                if(event.keyCode == 13) {     
                    var NextIndex = RowIndex + 1;
                    $('tr:eq(' + NextIndex + ')', GridTable).find("td:eq('5')").find('input').focus().select();
             }
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
            var pro_name = document.getElementById('product_id1').value.split('_')[2];
            var pro_unit = document.getElementById('product_id1').value.split('_')[3];
            var pro_cost = parseInt(document.getElementById('product_id1').value.split('_')[5]);
            var rate = parseInt(document.getElementById('price1').value);
            var qty = parseInt(document.getElementById('qty1').value);
            var total = parseInt(document.getElementById('total1').value);
            var TotalQty = parseInt(document.getElementById('TotalQty').innerHTML);
            var TotalAmount = parseInt(document.getElementById('TotalAmount').innerHTML);

            var cost_amount = pro_cost * qty;
            var sale_amount = pro_cost * qty;

            var grandTotalQty = TotalQty + qty;
            var grandTotalAmount = TotalAmount + total;


            var tableHtml = `<tr>`;
            tableHtml += `<td>
                            ${pro_name}
                            <input type='hidden' name='product_id[]' id='product_id' value='${pro_id}' />
                        </td>`;
            tableHtml += `<td>${pro_unit}</td>`;
            tableHtml +=
                `<td style="display:none;">${qty}</td>`;
            tableHtml +=
                `<td>${qty}<input type='hidden' name='qty[]' id='qty' value='${qty}' /></td>`;
            tableHtml +=
                `<td style="display:none;">${rate}</td>`;
            tableHtml +=
                `<td>${rate}<input type='hidden' name='rate[]' id='rate' value='${rate}' /></td>`;
            tableHtml +=
                `<td>${total}<input type='hidden' name='total[]' id='total' value='${total}' /></td>`;
            tableHtml +=
                `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
            tableHtml += `</tr>`;

            $('#GridTable').append(tableHtml);
            $('#code1').val(null);

            $('#unit1').val(null);
            $('#price1').val(null);
            $('#qty1').val(null);
            $('#total1').val(null);


            $('#TotalQty').html(grandTotalQty);
            $('#TotalAmount').html(grandTotalAmount);

            $('#product_id1').select2('open');
            $('#product_id1').val(null);
        }


        function TotalQtyChange(qty) {
            if(qty==''||qty==0){
                
            }else{
                // alert(qty);
            var gweightVal = $('#gweight_value').val();
            var totalGross = parseFloat(qty) + parseFloat(gweightVal);
            // alert(totalGross);
            
            $('#gross_weight').val(totalGross)
            var cost_rate = $('#actual_total_rate').val();
            var total_rate1 = $('#total_rate1').val();
            var grandTotalQty = 0;
            var grandTotalAmount = 0;
            $.each($("#GridTable tr"), function(index, row) {
                var columns = $(row).find("td");
                var quantity = $(columns[4]).find('input').val();
                var rate = $(columns[7]).find('input').val();
                var Totalqty = qty * quantity;
                

                // var TotalRate = rate * Totalqty;
                var TotalAmount = Totalqty * rate;
             
                $(columns[6]).html(
                    `<input type='text' name='showqty[]' id='showqty' value='${parseFloat(Totalqty).toFixed(4)}' style="width:120px;" class='form-control' readonly='readonly' onkeypress="return isNumberKey(event)"/>`
                );
                // $(columns[5]).html(
                //     `<input type='text' name='rate[]' id='rate' value='${parseFloat(TotalRate).toFixed(4)}' style="width:120px;" class='form-control'  readonly='readonly'/>`
                // );
                $(columns[8]).html(
                    `<input type='text' name='total[]' id='total' value='${parseFloat(TotalAmount).toFixed(4)}'  style="width:120px;" class='form-control' readonly='readonly'/>`
                );

                grandTotalQty += Totalqty;
                grandTotalAmount += TotalAmount;
            });

            // $('#TotalQty').text(grandTotalQty);
            $('#TotalAmount').text(parseFloat(grandTotalAmount).toFixed(4));


            // var total_amount;
            // total_amount = qty*total_rate1;
            // $('#total_amount1').val(parseFloat(total_amount).toFixed(4));
            // $('#total_amount').val(parseFloat(total_amount).toFixed(4));
            TotalQty();
            TotalAmount()
            
        }
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
                $('#production-form').submit();
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
         function TotalAmount() {
            var tableData = document.getElementById('GridTable');

            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseFloat(tableData.rows[i].cells[7].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalAmount').innerText = parseFloat(sum).toFixed(4);
              $('#total_amount1').val(parseFloat(sum).toFixed(4));
            $('#total_amount').val(parseFloat(sum).toFixed(4));
        }

        function TotalQty() {
            var tableData = document.getElementById('GridTable');
            
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                if (tableData.rows[i].cells[3].getElementsByTagName('input')[0].value == '') {
                    sum += 0;
                } else {
                    sum += parseFloat(tableData.rows[i].cells[4].getElementsByTagName('input')[0].value);
                }   
            }
            document.getElementById('TotalQty').innerText = parseFloat(sum).toFixed(4);
        }
        function changeQty(row) {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseFloat(tableData.rows[i].cells[4].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalQty').innerText = parseFloat(sum).toFixed(4);
        }

        function DeleteRow(row) {
            var TotalAmount = parseFloat(document.getElementById('TotalAmount').innerText);
            var NewAmount = parseFloat($(row).find("td:eq('6')").find('input').val());
            document.getElementById('TotalAmount').innerText = (TotalAmount - NewAmount);

            var TotalQty = parseFloat(document.getElementById('TotalQty').innerText);
            var NewQty = parseFloat($(row).find("td:eq('3')").find('input').val());
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
            document.getElementById('total1').value =parseFloat(total).toFixed(4);
        }
        function changenewQty(row){
           
            var quantity = $('#total_qty').val();
            // alert(quantity)
            var Newqty = $(row).find("td:eq('5')").find('input').val();
            // alert(Newqty)
            if (qty == '' || parseInt(qty) == 0) {
                $('.qty_errr').text('Please Enter Qty');
                $(row).find("td:eq('5')").find('input').focus();
                TotalAmount();
                TotalQty();
                return false;
            }
            var consumedqty=quantity*Newqty;
            $(row).find("td:eq('6')").find('input').val(parseFloat(consumedqty).toFixed(4));
            var rate = $(row).find("td:eq('7')").find('input').val();
             
            var totalAmount =consumedqty*rate;
            $(row).find("td:eq('8')").find('input').val(parseFloat(totalAmount).toFixed(4));
            TotalAmount();
            TotalQty();
        }

        function LoadLastRecipe(){
                // alert("ddd");
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('petroll-production/load/previous/record') }}?voucher_no=" +
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


                            // $.each(response.data, function(i, v) {
                            //     totalQty += parseFloat(v.quantity);
                            //     totalAmount += parseFloat(v.amount);

                            //     tableHtml += `<tr>`;
                            //     tableHtml +=
                            //         `<td>${v.product.code}</td>`;
                            //     tableHtml +=
                            //         `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                            //     tableHtml +=
                            //         `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom_id}' /></td>`;
                            //         tableHtml +=
                            //             `<td>${v.status}<input type='hidden' name='status[]' id='status' value='${v.status}'/></td>`;
                            //         tableHtml +=
                            //             `<td>${v.recipe_qty}<input type='hidden' name='recipe_qty[]' id='recipe_qty' value='${v.recipe_qty}' class='form-control'/></td>`;
                            //         tableHtml +=
                            //             `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.quantity}' class='form-control' 
                            //             onkeyup="changenewQty($(this).closest('tr'));" 
                            //             onkeydown="EnterKeyBoard($(this).closest('tr'));" 
                            //             onkeypress="return isNumberKey(event)"/>
                            //                 <span class="qty_err text-danger"></span>
                            //                 </td>`;
                            //         tableHtml +=
                            //             `<td style="width:120px;">${v.showqty}<input type='hidden' name='showqty[]' id='showqty' value='${v.showqty}'/></td>`;
                            //             tableHtml +=
                            //             `<td style="width:140px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' readonly='readonly'/></td>`;
                            //         tableHtml +=
                            //             `<td style="width:150px;"><input type='text' name='total[]' id='total' value='${v.amount}' class='form-control' readonly='readonly'/></td>`;
                            //     // tableHtml +=
                            //     //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                            //     tableHtml += `</tr>`;
                            // });

                            // $('#GridTable').html(tableHtml);
                            // $('#TotalQty').text(parseFloat(totalQty).toFixed(4));
                            // $('#TotalAmount').text(parseFloat(totalAmount).toFixed(4));
                            $('#updated_by_name').removeClass('d-none');

                            // $('#voucher_no').val(response.data[0].production.voucher_no);
                            $('#total_qty').focus();
                            // $('#update_voucher_id').val(response.data[0].production.id);
                            $('#date').val(response.data[0].production.date);
                            $('#product_id2').val(
                                    response.data[0].production.product.id)
                                .select2();
                            // $('#recipe_id').val(
                            //         response.data[0].production.recipe_creation.id)
                            //     .select2();
                            $('#unit_id2').val(response.data[0].production.product.uom);
                            $('#unit_id3').val(response.data[0].production.product.uom_id);
                            $('#gweight_value').val(response.data[0].production.gweight_value);
                            $('#total_qty').val(response.data[0].production.total_qty);
                            $('#gross_weight').val(response.data[0].production.gross_weight);
                            $('#total_rate1').val(response.data[0].production.total_rate);
                            $('#total_rate').val(response.data[0].production.total_rate);
                            $('#remarks').val(response.data[0].production.remarks);
                            $('#color_id').val(response.data[0].production.color_id).select2();
                            $('#machine_id').val(response.data[0].production.machine_id).select2();
                            $('#shift_id').val(response.data[0].production.shift_id).select2();
                            $('#forman_id').val(response.data[0].production.forman_id).select2();
                            $('#operator_id').val(response.data[0].production.operator_id).select2();
                            $('#thickness').val(response.data[0].production.thickness);
                            $('#width').val(response.data[0].production.width);
                            $('#total_amount1').val(response.data[0].production.total_amount);
                            $('#total_amount').val(response.data[0].production.total_amount);
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
                            $('#unit_id3').val(null);
                            $('#total_qty').val(0);
                            $('#total_rate1').val(null);
                            $('#total_rate').val(null);
                            $('#total_amount1').val(null);
                            $('#total_amount').val(null);
                        }
                    }
                });
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
                    url: "{{ URL::to('petroll-production/print/voucher') }}?voucher_no=" +
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
                                `<object data="${base_url}/resources/upload/production/${response}" type="application/pdf" width="100%" height="800"></object>`
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
                    url: "{{ URL::to('petroll-production/load/record') }}?voucher_no=" + voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {

                            // var tableHtml = '';
                            // var totalQty = 0;
                            // var totalAmount = 0;


                            // $.each(response.data, function(i, v) {
                            //     totalQty += parseFloat(v.quantity);
                            //     totalAmount += parseFloat(v.amount);

                            //     tableHtml += `<tr>`;
                            //     tableHtml +=
                            //         `<td>${v.product.code}</td>`;
                            //     tableHtml +=
                            //         `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                            //     tableHtml +=
                            //         `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom_id}' /></td>`;
                            //         tableHtml +=
                            //             `<td>${v.status}<input type='hidden' name='status[]' id='status' value='${v.status}'/></td>`;
                            //         tableHtml +=
                            //             `<td>${v.recipe_qty}<input type='hidden' name='recipe_qty[]' id='recipe_qty' value='${v.recipe_qty}' class='form-control'/></td>`;
                            //         tableHtml +=
                            //             `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.quantity}' class='form-control' 
                            //             onkeyup="changenewQty($(this).closest('tr'));" 
                            //             onkeydown="EnterKeyBoard($(this).closest('tr'));"
                            //             onkeypress="return isNumberKey(event)"/>
                            //                 <span class="qty_err text-danger"></span>
                            //                 </td>`;
                            //         tableHtml +=
                            //             `<td style="width:120px;">${v.showqty}<input type='hidden' name='showqty[]' id='showqty' value='${v.showqty}'/></td>`;
                            //             tableHtml +=
                            //             `<td style="width:140px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' readonly='readonly'/></td>`;
                            //         tableHtml +=
                            //             `<td style="width:150px;"><input type='text' name='total[]' id='total' value='${v.amount}' class='form-control' readonly='readonly'/></td>`;
                            //     // tableHtml +=
                            //     //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                            //     tableHtml += `</tr>`;
                            // });

                            // $('#GridTable').html(tableHtml);
                            // $('#TotalQty').text(parseFloat(totalQty).toFixed(4));
                            // $('#TotalAmount').text(parseFloat(totalAmount).toFixed(4));
                            // $('#updated_by_name').removeClass('d-none');

                            $('#voucher_no').val(response.data[0].production.voucher_no);
                            $('#voucher_no').focus();
                            $('#update_voucher_id').val(response.data[0].production.id);
                            $('#date').val(response.data[0].production.date);
                            $('#product_id2').val(
                                    response.data[0].production.product.id)
                                .select2();
                            // $('#recipe_id').val(
                            //         response.data[0].production.recipe_creation.id)
                            //     .select2();
                            $('#unit_id2').val(response.data[0].production.product.uom);
                            $('#unit_id3').val(response.data[0].production.product.uom_id);
                            $('#gweight_value').val(response.data[0].production.gweight_value);
                            $('#total_qty').val(response.data[0].production.total_qty);
                            $('#gross_weight').val(response.data[0].production.gross_weight);
                            $('#total_rate1').val(response.data[0].production.total_rate);
                            $('#total_rate').val(response.data[0].production.total_rate);
                            $('#remarks').val(response.data[0].production.remarks);
                            $('#color_id').val(response.data[0].production.color_id).select2();
                            $('#machine_id').val(response.data[0].production.machine_id).select2();
                            $('#shift_id').val(response.data[0].production.shift_id).select2();
                            $('#forman_id').val(response.data[0].production.forman_id).select2();
                            $('#operator_id').val(response.data[0].production.operator_id).select2();
                            $('#thickness').val(response.data[0].production.thickness);
                            $('#width').val(response.data[0].production.width);
                            $('#total_amount1').val(response.data[0].production.total_amount);
                            $('#total_amount').val(response.data[0].production.total_amount);
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
                            $('#unit_id3').val(null);
                            $('#total_qty').val(0);
                            $('#total_rate1').val(null);
                            $('#total_rate').val(null);
                            $('#total_amount1').val(null);
                            $('#total_amount').val(null);
                        }
                    }
                });
            });


            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('petroll-production/load/next/record') }}?voucher_no=" +
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

                            // var tableHtml = '';
                            // var totalQty = 0;
                            // var totalAmount = 0;


                            // $.each(response.data, function(i, v) {
                            //     totalQty += parseFloat(v.quantity);
                            //     totalAmount += parseFloat(v.amount);

                            //     tableHtml += `<tr>`;
                            //     tableHtml +=
                            //         `<td>${v.product.code}</td>`;
                            //     tableHtml +=
                            //         `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                            //     tableHtml +=
                            //         `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom_id}' /></td>`;
                            //         tableHtml +=
                            //             `<td>${v.status}<input type='hidden' name='status[]' id='status' value='${v.status}'/></td>`;
                            //         tableHtml +=
                            //             `<td>${v.recipe_qty}<input type='hidden' name='recipe_qty[]' id='recipe_qty' value='${v.recipe_qty}' class='form-control'/></td>`;
                            //         tableHtml +=
                            //             `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.quantity}' class='form-control' 
                            //             onkeyup="changenewQty($(this).closest('tr'));"
                            //             onkeydown="EnterKeyBoard($(this).closest('tr'));" 
                            //             onkeypress="return isNumberKey(event)"/>
                            //                 <span class="qty_err text-danger"></span>
                            //                 </td>`;
                            //         tableHtml +=
                            //             `<td style="width:120px;">${v.showqty}<input type='hidden' name='showqty[]' id='showqty' value='${v.showqty}'/></td>`;
                            //             tableHtml +=
                            //             `<td style="width:140px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' readonly='readonly'/></td>`;
                            //         tableHtml +=
                            //             `<td style="width:150px;"><input type='text' name='total[]' id='total' value='${v.amount}' class='form-control' readonly='readonly'/></td>`;
                            //     // tableHtml +=
                            //     //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                            //     tableHtml += `</tr>`;
                            // });

                            // $('#GridTable').html(tableHtml);
                            // $('#TotalQty').text(parseFloat(totalQty).toFixed(4));
                            // $('#TotalAmount').text(parseFloat(totalAmount).toFixed(4));
                            // $('#updated_by_name').removeClass('d-none');

                            $('#voucher_no').val(response.data[0].production.voucher_no);
                            $('#voucher_no').focus();
                            $('#update_voucher_id').val(response.data[0].production.id);
                            $('#date').val(response.data[0].production.date);
                            $('#product_id2').val(
                                    response.data[0].production.product.id)
                                .select2();
                                // $('#recipe_id').val(
                                //     response.data[0].production.recipe_creation.id)
                                // .select2();
                            $('#unit_id2').val(response.data[0].production.product.uom);
                            $('#unit_id3').val(response.data[0].production.product.uom_id);
                            $('#gweight_value').val(response.data[0].production.gweight_value);
                            $('#total_qty').val(response.data[0].production.total_qty);
                            $('#gross_weight').val(response.data[0].production.gross_weight);
                            $('#total_rate1').val(response.data[0].production.total_rate);
                            $('#total_rate').val(response.data[0].production.total_rate);
                            $('#remarks').val(response.data[0].production.remarks);
                            $('#color_id').val(response.data[0].production.color_id).select2();
                            $('#machine_id').val(response.data[0].production.machine_id).select2();
                            $('#shift_id').val(response.data[0].production.shift_id).select2();
                            $('#forman_id').val(response.data[0].production.forman_id).select2();
                            $('#operator_id').val(response.data[0].production.operator_id).select2();
                            $('#thickness').val(response.data[0].production.thickness);
                            $('#width').val(response.data[0].production.width);
                            $('#total_amount1').val(response.data[0].production.total_amount);
                            $('#total_amount').val(response.data[0].production.total_amount);
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
                            $('#unit_id3').val(null);
                            $('#total_qty').val(0);
                            $('#total_rate1').val(null);
                            $('#total_rate').val(null);
                            $('#total_amount1').val(null);
                            $('#total_amount').val(null);
                        }
                    }
                });
            });
            // End Here of Load Next Record
           

            // Load Previous Record
            $('.load-previous-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('petroll-production/load/previous/record') }}?voucher_no=" +
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


                            // $.each(response.data, function(i, v) {
                            //     totalQty += parseFloat(v.quantity);
                            //     totalAmount += parseFloat(v.amount);

                            //     tableHtml += `<tr>`;
                            //     tableHtml +=
                            //         `<td>${v.product.code}</td>`;
                            //     tableHtml +=
                            //         `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                            //     tableHtml +=
                            //         `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom_id}' /></td>`;
                            //         tableHtml +=
                            //             `<td>${v.status}<input type='hidden' name='status[]' id='status' value='${v.status}'/></td>`;
                            //         tableHtml +=
                            //             `<td>${v.recipe_qty}<input type='hidden' name='recipe_qty[]' id='recipe_qty' value='${v.recipe_qty}' class='form-control'/></td>`;
                            //         tableHtml +=
                            //             `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.quantity}' class='form-control' 
                            //             onkeyup="changenewQty($(this).closest('tr'));" 
                            //             onkeydown="EnterKeyBoard($(this).closest('tr'));" 
                            //             onkeypress="return isNumberKey(event)"/>
                            //                 <span class="qty_err text-danger"></span>
                            //                 </td>`;
                            //         tableHtml +=
                            //             `<td style="width:120px;">${v.showqty}<input type='hidden' name='showqty[]' id='showqty' value='${v.showqty}'/></td>`;
                            //             tableHtml +=
                            //             `<td style="width:140px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' readonly='readonly'/></td>`;
                            //         tableHtml +=
                            //             `<td style="width:150px;"><input type='text' name='total[]' id='total' value='${v.amount}' class='form-control' readonly='readonly'/></td>`;
                            //     // tableHtml +=
                            //     //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                            //     tableHtml += `</tr>`;
                            // });

                            // $('#GridTable').html(tableHtml);
                            // $('#TotalQty').text(parseFloat(totalQty).toFixed(4));
                            // $('#TotalAmount').text(parseFloat(totalAmount).toFixed(4));
                            // $('#updated_by_name').removeClass('d-none');

                            $('#voucher_no').val(response.data[0].production.voucher_no);
                            $('#voucher_no').focus();
                            $('#update_voucher_id').val(response.data[0].production.id);
                            $('#date').val(response.data[0].production.date);
                            $('#product_id2').val(
                                    response.data[0].production.product.id)
                                .select2();
                            // $('#recipe_id').val(
                            //         response.data[0].production.recipe_creation.id)
                            //     .select2();
                            $('#unit_id2').val(response.data[0].production.product.uom);
                            $('#unit_id3').val(response.data[0].production.product.uom_id);
                            $('#gweight_value').val(response.data[0].production.gweight_value);
                            $('#total_qty').val(response.data[0].production.total_qty);
                            $('#gross_weight').val(response.data[0].production.gross_weight);
                            $('#total_rate1').val(response.data[0].production.total_rate);
                            $('#total_rate').val(response.data[0].production.total_rate);
                            $('#remarks').val(response.data[0].production.remarks);
                            $('#color_id').val(response.data[0].production.color_id).select2();
                            $('#machine_id').val(response.data[0].production.machine_id).select2();
                            $('#shift_id').val(response.data[0].production.shift_id).select2();
                            $('#forman_id').val(response.data[0].production.forman_id).select2();
                            $('#operator_id').val(response.data[0].production.operator_id).select2();
                            $('#thickness').val(response.data[0].production.thickness);
                            $('#width').val(response.data[0].production.width);
                            $('#total_amount1').val(response.data[0].production.total_amount);
                            $('#total_amount').val(response.data[0].production.total_amount);
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
                            $('#unit_id3').val(null);
                            $('#total_qty').val(0);
                            $('#total_rate1').val(null);
                            $('#total_rate').val(null);
                            $('#total_amount1').val(null);
                            $('#total_amount').val(null);
                        }
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
