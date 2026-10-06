@extends('app')
@section('head')
    <title>GRN</title>
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
                GRN
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">GRN</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <!-- <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> GRN</h6>
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
                                    {!! Form::open(['url' => 'grn', 'class' => 'form-horizontal', 'id' => 'grn-voucher-form']) !!}
                                    {!! Form::hidden('created_by', Auth::User()->id, ['id' => 'created_by']) !!}
                                    {!! Form::hidden('update_voucher_no', null, ['id' => 'update_voucher_no']) !!}
                                    {!! Form::hidden('inward', null, ['id' => 'inward']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    

                                    <div class="row">
                                        
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Vr.No <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $codes, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'autofocus' => 'autofocus',
                                                'required' => 'required',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)'
                                            ]) !!}
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1">
                                            <label for="voucher_date"><i class="fa fa-caret-right"></i> Vr. Date</label>
                                            {!! Form::date('date', date('Y-m-d'), [
                                                'id' => 'date',
                                                'class' => 'form-control',
                                                'tabindex' => '1',
                                                
                                                'required' => 'required',
                                            ]) !!}
                                            @error('voucher_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1 inward-gatepass-no1">
                                            <label for="inward_gatepass_id"><i class="fa fa-caret-right"></i>IGP<span class="text-danger">*</span></label>
                                            {!! Form::select('inward_gatepass_id', $inward_gatepasses, null, [
                                                'id' => 'inward_gatepass_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('inward_gatepass_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1 inward-gatepass-no2 d-none">
                                            <label for="inward_gatepass_no"><i class="fa fa-caret-right"></i> Inward
                                                GatePass No<span class="text-danger">*</span></label>
                                            {!! Form::text('inward_gatepass_id', null, [
                                                'id' => 'inward_gatepass_id',
                                                'class' => 'form-control',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            @error('inward_gatepass_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-6 col-sm-12 mt-1 ReloadOrder d-none">
                                                <label for="grn_no"><i class="fa fa-caret-right"></i>Reload<span
                                                    class="text-danger">*</span></label>
                                                    <a href="#"><div class="input-group">
                                                    <div class="input-group-addon bg-primary border-primary" onclick="refresh();">
                                                        <i class="fa fa-th-list"></i>
                                                    </div></a>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-3 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i> Remarks
                                                <span class="text-danger">*</span></label>
                                            {!! Form::text('remarks', null, [
                                                'id' => 'remarks',
                                                'class' => 'form-control',
                                                'tabindex' => '3',
                                            ]) !!}
                                            @error('remarks')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                            <label for="vehicle_no"><i class="fa fa-caret-right"></i> Vehicle No <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('vehicle_no', null, [
                                                'id' => 'vehicle_no',
                                                'class' => 'form-control',
                                                'tabindex' => '2',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            @error('vehicle_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                            <label for="transport_company"><i class="fa fa-caret-right"></i> Transport
                                                Company<span class="text-danger">*</span></label>
                                            {!! Form::text('transport_company', null, [
                                                'id' => 'transport_company',
                                                'class' => 'form-control',
                                                'tabindex' => '3',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            @error('transport_company')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                            <label for="driver_name"><i class="fa fa-caret-right"></i> Driver Name<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('driver_name', null, [
                                                'id' => 'driver_name',
                                                'class' => 'form-control',
                                                'tabindex' => '4',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            @error('driver_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                            <label for="builty_no"><i class="fa fa-caret-right"></i> Builty Number<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('builty_no', null, [
                                                'id' => 'builty_no',
                                                'class' => 'form-control',
                                                'tabindex' => '5',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            @error('builty_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                            <label for="supplier_id"><i class="fa fa-caret-right"></i> Supplier<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('supplier_id',null, [
                                                'id' => 'supplier_id',
                                                'class' => 'form-control ',
                                                'tabindex' => '6',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            @error('supplier_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                            <label for="purchaser_id"><i class="fa fa-caret-right"></i> Purchaser<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('purchaser_id',null,[
                                                'id' => 'purchaser_id',
                                                'class' => 'form-control',
                                                'tabindex' => '7',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            @error('purchaser_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                            <label for="driver_phoneno"><i class="fa fa-caret-right"></i> Driver
                                                PhoneNo<span class="text-danger">*</span></label>
                                            {!! Form::text('driver_phoneno', null, [
                                                'id' => 'driver_phoneno',
                                                'class' => 'form-control',
                                                'tabindex' => '8',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            @error('driver_phoneno')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                            <label for="to_warehouse_id"><i class="fa fa-caret-right"></i>Warehouse<span class="text-danger">*</span></label>
                                            {!! Form::select('to_warehouse_id',$warehouse ,null, [
                                                'id' => 'to_warehouse_id',
                                                'class' => 'form-control',
                                                'tabindex' => '9',
                                                'disabled' => 'disabled',
                                               
                                            ]) !!}
                                            @error('to_warehouse_id')
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
                                                            {{-- <th>Total</th> --}}
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
                                                                <span class="product-err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('unit1', null, [
                                                                    'id' => 'unit1',
                                                                    'class' => 'form-control bg-white',
                                                                    'disabled' => 'disabled',
                                                                    'placeholder' => 'Unit',
                                                                    'tabindex' => '11',
                                                                ]) !!}
                                                                {!! Form::hidden('unit2', null, ['id' => 'unit2']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('price1', null, [
                                                                    'id' => 'price1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Demand Quantity',
                                                                    'tabindex' => '12',
                                                                    'onkeyup' => 'PriceKeyUp($(this).val())',
                                                                ]) !!}
                                                                <span class="price-err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('qty1', null, [
                                                                    'id' => 'qty1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Qty',
                                                                    'tabindex' => '13',
                                                                    'onkeyup' => 'QuantityKeyUp($(this).val())',
                                                                ]) !!}
                                                                <span class="qty-err text-danger"></span>
                                                            </td>
                                                            {{-- <td>
                                                                {!! Form::text('total1', null, [
                                                                    'id' => 'total1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Total',
                                                                    'tabindex' => '14',
                                                                ]) !!}
                                                                <span class="total-err text-danger"></span>
                                                            </td> --}}
                                                            <td>
                                                                {!! Form::text('comment1', null, [
                                                                    'id' => 'comment1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Comment',
                                                                    'tabindex' => '15',
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
                                                                <th>Demand Qty</th>
                                                                <th>Qty</th>
                                                                {{-- <th>Total</th> --}}
                                                                <th>Comment</th>
                                                                {{-- <th>Action</th> --}}
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3"><strong>Total</strong></td>
                                                                {{-- <td class="bg-info" id="TotalPrice">0</td> --}} 
                                                                <td class="bg-primary" id="TotalQty">0</td>
                                                                {{-- <td class="bg-success" id="TotalAmount">0</td> --}}
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
                <form action="{{ URL::to('grn/destroy') }}" method="post" id="delete_voucher_form">
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
    <!-- Focus on next field -->
    <script>
        $(document).ready(function() {

            
            $('#voucher_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#date").focus();
                }
            });
            $('#date').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#inward_gatepass_id").select2('open');
                }
            });

            $('#remarks').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#qty").select();
                }
            });
            // $('#to_warehouse_id').change(function(event){
            //     var to_warehouse_id = $(this).val();
            //     if(to_warehouse_id){
            //         $('#to_warehouse_id').select2().trigger('select2:close');
            //         $('#inward_gatepass_id').select2('open');
            //     }
            // });
            $('#inward_gatepass_id').change(function(event) {
                var inward_gatepass_id = $(this).val();
                if (inward_gatepass_id) {
                    $('#inward_gatepass_id').select2().trigger('select2:close');
                    var inward_gatepass_id = parseInt($(this).val());

                    if (inward_gatepass_id) {
                        $.ajax({
                            url: "{{ URL::to('grn/load/igp/record') }}?inward_gatepass_id=" +
                                inward_gatepass_id,
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
                                    // var totalPrice = 0;
                                    // var totalAmount = 0;


                                    $.each(response.data, function(i, v) {
                                        var comment = '';
                                        if (v.comments != null) {
                                            comment = v.comments;
                                        }

                                        totalQty += parseFloat(v.qty);
                                        // totalPrice += parseInt(v.price);
                                        // totalAmount += parseInt(v.total_amount);


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
                                            `<td >${v.qty}<input type='hidden' name='demandQty[]' id='demandQty' value='${v.qty}' class='form-control'/></td>`;
                                        tableHtml +=
                                            `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control' style="width:120px;" 
                                            onkeydown="EnterKeyBoard($(this).closest('tr'));"
                                            onkeypress="return isNumberKey(event)"
                                            /></td>`;
                                        // tableHtml +=
                                        //     `<td style="width:130px;">
                                        //         <input type='text'  value='0' class='form-control' disabled style="width:130px;" />
                                        //         <input type='hidden' name='total[]' id='total' value='0'  />
                                        //     </td>`;
                                        tableHtml +=
                                            `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
                                        // tableHtml +=
                                        //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                        tableHtml += `</tr>`;

                                    });

                                    $('#GridTable').html(tableHtml);
                                    $('#TotalQty').text(totalQty);
                                    // $('#TotalPrice').text(totalPrice);
                                    // $('#TotalAmount').text(totalAmount);
                                    $('#updated_by_name').removeClass('d-none');

                                    $('#date').val(response.data[0].inward.date);
                                    $('#vehicle_no').val(response.data[0].inward.vehicle_no);
                                    $('#transport_company').val(response.data[0].inward.transport_company);
                                    $('#driver_name').val(response.data[0].inward.driver_name);
                                    $('#driver_phoneno').val(response.data[0].inward.driver_phoneno);
                                    $('#builty_no').val(response.data[0].inward.builty_no);
                                    $('#supplier_id').val(response.data[0].inward.supplier.party_name);
                                    $('#purchaser_id').val(response.data[0].inward.purchaser.party_name);
                                    $('#remarks').focus();
                                    TotalAmount();
                                } else {
                                    $('#show_err').html(
                                        '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                                    );

                                    $('#GridTable').html(null);
                                    $('#TotalQty').text(0);
                                    $('#TotalPrice').text(0);
                                    $('#TotalAmount').text(0);
                                    $('#updated_by_name').addClass('d-none');

                                    $('#update_voucher_no').val(null);
                                    let date = new Date()
                                    $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                        .getMonth()) + 1) + '-' + date.getDate());

                                    $('#date').focus();
                                }
                            }
                        });
                    } else {
                        $('#GridTable').html(null);
                        $('#TotalQty').text(0);
                        $('#TotalPrice').text(0);
                        $('#TotalAmount').text(0);
                        $('#updated_by_name').addClass('d-none');

                        $('#update_voucher_no').val(null);
                        let date = new Date()
                        $('#date').val(date.getFullYear() + '-' + (parseInt(date
                            .getMonth()) + 1) + '-' + date.getDate());
                        $('#date').focus();
                    }
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
            $('#remarks').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#price').focus();
                }
            });
            $('#price1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var price = parseFloat($(this).val());
                    if (price == 0 || price == '') {
                        $('.price-err').text('This field is required & Must be greater than zero');
                        $(this).focus();
                    } else {
                        $('.price-err').text('');
                        $('#qty1').focus();
                    }
                }
            });
            $('#qty1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = parseFloat($(this).val());
                    if (qty <= 0) {
                        $(this).focus();
                        $('.qty-err').text('This field is required & Must be greater than zero');
                    } else {
                        $('.qty-err').text('');
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
            function EnterKeyBoard(row){
                var RowIndex = row.index();
                if(event.keyCode == 13) {
                var NextIndex = RowIndex + 1;
                 $('tr:eq(' + NextIndex + ')', GridTable).find("td:eq('4')").find('input').select();
                }  
            }


            function refresh(){
                var inward_gatepass_id = $("#inward").val();
                // alert(inward_gatepass_id);
                if (inward_gatepass_id) {
                    // $('#inward_gatepass_id').select2().trigger('select2:close');
                    // var inward_gatepass_id = parseInt($(this).val());

                    if (inward_gatepass_id) {
                        $.ajax({
                            url: "{{ URL::to('grn/load/igp/record') }}?inward_gatepass_id=" +
                                inward_gatepass_id,
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
                                    // var totalPrice = 0;
                                    // var totalAmount = 0;


                                    $.each(response.data, function(i, v) {
                                        var comment = '';
                                        if (v.comments != null) {
                                            comment = v.comments;
                                        }

                                        totalQty += parseFloat(v.qty);
                                        // totalPrice += parseInt(v.price);
                                        // totalAmount += parseInt(v.total_amount);


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
                                            `<td >${v.qty}<input type='hidden' name='demandQty[]' id='demandQty' value='${v.qty}' class='form-control'/></td>`;
                                        tableHtml +=
                                            `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control' style="width:120px;" 
                                            onkeydown="EnterKeyBoard($(this).closest('tr'));"
                                            onkeypress="return isNumberKey(event)"
                                            /></td>`;
                                        // tableHtml +=
                                        //     `<td style="width:130px;">
                                        //         <input type='text'  value='0' class='form-control' disabled style="width:130px;" />
                                        //         <input type='hidden' name='total[]' id='total' value='0'  />
                                        //     </td>`;
                                        tableHtml +=
                                            `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
                                        // tableHtml +=
                                        //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                        tableHtml += `</tr>`;

                                    });

                                    $('#GridTable').html(tableHtml);
                                    $('#TotalQty').text(totalQty);
                                    // $('#TotalPrice').text(totalPrice);
                                    // $('#TotalAmount').text(totalAmount);
                                    $('#updated_by_name').removeClass('d-none');

                                    $('#date').val(response.data[0].inward.date);
                                    $('#vehicle_no').val(response.data[0].inward.vehicle_no);
                                    $('#transport_company').val(response.data[0].inward.transport_company);
                                    $('#driver_name').val(response.data[0].inward.driver_name);
                                    $('#driver_phoneno').val(response.data[0].inward.driver_phoneno);
                                    $('#builty_no').val(response.data[0].inward.builty_no);
                                    $('#supplier_id').val(response.data[0].inward.supplier.party_name);
                                    $('#purchaser_id').val(response.data[0].inward.purchaser.party_name);
                                    $('#remarks').focus();
                                    TotalAmount();
                                } else {
                                    $('#show_err').html(
                                        '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                                    );

                                    $('#GridTable').html(null);
                                    $('#TotalQty').text(0);
                                    $('#TotalPrice').text(0);
                                    $('#TotalAmount').text(0);
                                    $('#updated_by_name').addClass('d-none');

                                    $('#update_voucher_no').val(null);
                                    let date = new Date()
                                    $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                        .getMonth()) + 1) + '-' + date.getDate());

                                    $('#date').focus();
                                }
                            }
                        });
                    } else {
                        $('#GridTable').html(null);
                        $('#TotalQty').text(0);
                        $('#TotalPrice').text(0);
                        $('#TotalAmount').text(0);
                        $('#updated_by_name').addClass('d-none');

                        $('#update_voucher_no').val(null);
                        let date = new Date()
                        $('#date').val(date.getFullYear() + '-' + (parseInt(date
                            .getMonth()) + 1) + '-' + date.getDate());
                        $('#date').focus();
                    }
                }
            
        }

        function TotalDemandQty() {
            var tableData = document.getElementById('GridTable');
            var sum =0;
          
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[3].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalQty').innerText = sum.toLocaleString('en-US');
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

            var price = parseFloat($('#price1').val());
            if (!price || price <= 0) {
                $('.price-err').text('This field is required & Must be greater than zero');
                $('#price1').focus();
                return false;
            } else {
                $('.price-err').text('');
            }

            var qty = parseFloat($('#qty1').val());
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
            var price = parseFloat(document.getElementById('price1').value);
            var qty = parseFloat(document.getElementById('qty1').value);
            var total = parseFloat(document.getElementById('total1').value);
            var comment = document.getElementById('comment1').value;
            var TotalPrice = parseFloat(document.getElementById('TotalPrice').innerHTML);
            var TotalQty = parseFloat(document.getElementById('TotalQty').innerHTML);
            var TotalAmount = parseFloat(document.getElementById('TotalAmount').innerHTML);
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
                `<td>${qty}<input type='hidden' name='qty[]' id='qty' value='${qty}' class='form-control' /></td>`;
            tableHtml +=
                `<td>${total}<input type='hidden' name='total[]' id='total' value='${total}' class='form-control' /></td>`;
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
              //  $('#grn-voucher-form').submit();
                var count=0;
                var tableData = document.getElementById('GridTable');
              for (var i = 0; i < tableData.rows.length; i++) {
                var qty=tableData.rows[i].cells[4].getElementsByTagName('input')[0].value;
                if(qty=='' || qty==0)
                {
                    count++;
                    break;
                }
            }
            if(count==0)
            {
                $('#grn-voucher-form').submit();
                $('.submit-form').attr('disabled', true);
            }else{
                $('#show_err').html(
                    '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> The Qty field is required!</div>'
                );
            }
            });
            // End FOrm Submit

            // Reset btn feature
            $('.reset-btn').click(function() {
                $('#supplier_id').val(null);
                $('#purchaser_id').val(null);
                $('#status').val(0);
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
        function TotalAmount(row) {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[5].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalAmount').innerText = sum;
        }
        function changePrice(row){
            // alert(1);
            var price = $(row).find("td:eq('3')").find('input').val();
            var qty = parseInt($(row).find("td:eq('4')").find('input').val());
            var cal=0;
            if(price==null || parseFloat(price)==0)
            {   
                cal=0;
            }else{
                cal=price*qty;
            }
            
            $(row).find("td:eq('5')").find('input').val(cal);
            TotalAmount();
        }
        function DeleteRow(row) {
            var TotalAmount = parseFloat(document.getElementById('TotalAmount').innerText);
            var NewAmount = parseFloat($(row).find("td:eq('5')").find('input').val());
            document.getElementById('TotalAmount').innerText = (TotalAmount - NewAmount);

            var TotalQty = parseFloat(document.getElementById('TotalQty').innerText);
            var NewQty = parseFloat($(row).find("td:eq('4')").find('input').val());
            document.getElementById('TotalQty').innerText = (TotalQty - NewQty);

            var TotalPrice = parseFloat(document.getElementById('TotalPrice').innerText);
            var NewPrice = parseFloat($(row).find("td:eq('3')").find('input').val());
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
                var voucher_no = parseInt($('#voucher_no').val());
                $('#delete_voucher_no').val(voucher_no);
            });
            //print record
            $('.print_record_btn').click(function() {
                    var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                    myModal.toggle();

                    var voucher_no = parseInt($('#voucher_no').val());
                    var base_url = $('#base_url').val();
                    $.ajax({
                        url: "{{ URL::to('grn/print/voucher') }}?voucher_no=" +
                            voucher_no,
                        type: 'get',
                        beforeSend: function(response) {
                            $('#print-receipt-modal-body').html(
                                '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                            );
                        },
                        success: function(response) {
                            
                            if (response) {
                                console.log(response);
                                $('#print-receipt-modal-body').html(
                                    `<object data="${base_url}/resources/upload/grn/${response}" type="application/pdf" width="100%" height="800"></object>`
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
                    url: "{{ URL::to('grn/load/record') }}?voucher_no=" + voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    //for edit manually
                    // success: function(response) {
                    //     $('.ReloadOrder').removeClass('d-none');
                    //         $('#updated_by_name').removeClass('d-none');
                    //         $('#update_voucher_no').val(129); //grn voucher no 
                    //         $('.inward-gatepass-no2 input').val(158); //igp no
                    //         $('#inward').val(1); //godownstock inward_gatepass_id
                    //         $('.inward-gatepass-no2').removeClass('d-none');
                    //         $('.inward-gatepass-no1').addClass('d-none');
                    // }

                    success: function(response) {
                        $('.ReloadOrder').removeClass('d-none');
                        TotalDemandQty();
                            $('#updated_by_name').removeClass('d-none');
                            $('#date').val(response.data[0].godownstock.date);
                            $('#voucher_no').val(response.data[0].voucher_no);
                            $('#update_voucher_no').val(response.data[0].voucher_no);
                            $('#date').focus();
                            $('.inward-gatepass-no2 input').val(response.data[0].godownstock.inward_gatepass
                                .bill_no); 
                            // alert(response.data[0].godownstock.inward_gatepass_id);
                            $('.inward-gatepass-no2').removeClass('d-none');
                            $('.inward-gatepass-no1').addClass('d-none');
                            // $('.submit-form').addClass('d-none');
                            $('#vehicle_no').val(response.data[0].godownstock.inward_gatepass.vehicle_no);
                            $('#transport_company').val(response.data[0].godownstock.inward_gatepass.transport_company);
                            $('#driver_name').val(response.data[0].godownstock.inward_gatepass.driver_name);
                            $('#driver_phoneno').val(response.data[0].godownstock.inward_gatepass.driver_phoneno);
                            $('#builty_no').val(response.data[0].godownstock.inward_gatepass.builty_no);
                            $('#supplier_id').val(response.data[0].godownstock.inward_gatepass.supplier.party_name);
                            $('#purchaser_id').val(response.data[0].godownstock.inward_gatepass.purchaser.party_name);
                            $('#to_warehouse_id').val(response.data[0].godownstock.to_warehouse_id).select2();
                            $('#inward').val(response.data[0].godownstock.inward_gatepass_id);
                            $('#remarks').val(response.data[0].godownstock.remarks);
                            
                        if (response.data != '') {
                            var tableHtml = '';
                            $.each(response.data, function(i, v) {
                                var comment = '';
                                if (v.remarks != null) {
                                    comment = v.remarks;
                                }
                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                tableHtml += `<td>${v.product.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=
                                            `<td >${v.demand_qty}<input type='hidden' name='demandQty[]' id='demandQty' value='${v.demand_qty}' class='form-control'/></td>`;
                                        tableHtml +=
                                            `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty_in}' class='form-control' style="width:120px;"/></td>`;
                                tableHtml +=
                                    `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
                                // tableHtml +=
                                //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
     
                            });

                            $('#GridTable').html(tableHtml);
                            
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('.ReloadOrder').addClass('d-none');
                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalPrice').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_voucher_no').val(null);
                            let date = new Date()
                            $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                .getMonth()) + 1) + '-' + date.getDate());

                             $('#date').focus();

                            $('.inward-gatepass-no2 input').val(null);
                            $('.inward-gatepass-no2').addClass('d-none');
                            $('.inward-gatepass-no1').removeClass('d-none');
                            $('.submit-form').removeClass('d-none');
                        }
                    }
                });
            });

            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('grn/load/next/record') }}?voucher_no=" + voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        $('.ReloadOrder').removeClass('d-none');
                        TotalDemandQty();
                            // $('#TotalPrice').text(totalPrice);
                            // $('#TotalAmount').text(totalAmount);
                            $('#updated_by_name').removeClass('d-none');
                            $('#date').val(response.data[0].godownstock.date);
                            $('#voucher_no').val(response.data[0].voucher_no);
                            $('#update_voucher_no').val(response.data[0].voucher_no);
                            $('#date').focus();
                            $('.inward-gatepass-no2 input').val(response.data[0].godownstock.inward_gatepass
                                .bill_no);
                            $('#inward_gatepass_id').val(response.data[0].godownstock.inward_gatepass_id);
                            $('.inward-gatepass-no2').removeClass('d-none');
                            $('.inward-gatepass-no1').addClass('d-none');
                            // $('.submit-form').addClass('d-none');
                            $('#vehicle_no').val(response.data[0].godownstock.inward_gatepass.vehicle_no);
                            $('#transport_company').val(response.data[0].godownstock.inward_gatepass.transport_company);
                            $('#driver_name').val(response.data[0].godownstock.inward_gatepass.driver_name);
                            $('#driver_phoneno').val(response.data[0].godownstock.inward_gatepass.driver_phoneno);
                            $('#builty_no').val(response.data[0].godownstock.inward_gatepass.builty_no);
                            $('#supplier_id').val(response.data[0].godownstock.inward_gatepass.supplier.party_name);
                            $('#purchaser_id').val(response.data[0].godownstock.inward_gatepass.purchaser.party_name);
                            $('#to_warehouse_id').val(response.data[0].godownstock.to_warehouse_id).select2();
                            $('#inward').val(response.data[0].godownstock.inward_gatepass_id);
                            $('#remarks').val(response.data[0].godownstock.remarks);
                            
                        if (response.data != '') {
                            var tableHtml = '';
                            var totalQty = 0;
                            var totalPrice = 0;
                            var totalAmount = 0;


                            $.each(response.data, function(i, v) {
                                var comment = '';
                                if (v.remarks != null) {
                                    comment = v.remarks;
                                }

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                tableHtml += `<td>${v.product.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=
                                            `<td >${v.demand_qty}<input type='hidden' name='demandQty[]' id='demandQty' value='${v.demand_qty}' class='form-control'/></td>`;
                                        tableHtml +=
                                            `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty_in}' class='form-control' style="width:120px;"/></td>`;
                                tableHtml +=
                                    `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
                                // tableHtml +=
                                //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
     
                            });

                            $('#GridTable').html(tableHtml);
                            
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('.ReloadOrder').addClass('d-none');
                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalPrice').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_voucher_no').val(null);
                            let date = new Date()
                            $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                .getMonth()) + 1) + '-' + date.getDate());

                            $('#date').focus();

                            $('.inward-gatepass-no2 input').val(null);
                            $('.inward-gatepass-no2').addClass('d-none');
                            $('.inward-gatepass-no1').removeClass('d-none');
                            $('.submit-form').removeClass('d-none');
                        }
                    }
                });
            });
            // End Here of Load Next Record


            // Load Previous Record
            $('.load-previous-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                 
                $.ajax({
                    url: "{{ URL::to('grn/load/previous/record') }}?voucher_no=" + voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        $('.ReloadOrder').removeClass('d-none');
                        TotalDemandQty();
                            // $('#TotalQty').text(totalQty);
                            // $('#TotalPrice').text(totalPrice);
                            // $('#TotalAmount').text(totalAmount);
                            $('#updated_by_name').removeClass('d-none');

                            $('#date').val(response.data[0].godownstock.date);
                            $('#voucher_no').val(response.data[0].godownstock.voucher_no);
                            $('#update_voucher_no').val(response.data[0].voucher_no);
                            // $('#voucher_no').val(response.data[0].godownstock.voucher_no);
                            $('#date').focus();
                            $('.inward-gatepass-no2 input').val(response.data[0].godownstock.inward_gatepass
                                .bill_no);
                            //  $('#inward_gatepass_id').val(response.data[0].godownstock.inward_gatepass
                            //     .id);
                            $('.inward-gatepass-no2').removeClass('d-none');
                            $('.inward-gatepass-no1').addClass('d-none');
                            // $('.submit-form').addClass('d-none');
                            $('#vehicle_no').val(response.data[0].godownstock.inward_gatepass.vehicle_no);
                            $('#transport_company').val(response.data[0].godownstock.inward_gatepass.transport_company);
                            $('#driver_name').val(response.data[0].godownstock.inward_gatepass.driver_name);
                            $('#driver_phoneno').val(response.data[0].godownstock.inward_gatepass.driver_phoneno);
                            $('#builty_no').val(response.data[0].godownstock.inward_gatepass.builty_no);
                            $('#supplier_id').val(response.data[0].godownstock.inward_gatepass.supplier.party_name);
                            $('#purchaser_id').val(response.data[0].godownstock.inward_gatepass.purchaser.party_name);
                            $('#to_warehouse_id').val(response.data[0].godownstock.to_warehouse_id).select2();
                            $('#inward').val(response.data[0].godownstock.inward_gatepass_id);
                            $('#remarks').val(response.data[0].godownstock.remarks);
                            
                        if (response.data != '') {
                            var tableHtml = '';
                            var totalQty = 0;
                            var totalPrice = 0;
                            var totalAmount = 0;


                            $.each(response.data, function(i, v) {
                                var comment = '';
                                if (v.remarks != null) {
                                    comment = v.remarks;
                                }
                                // totalPrice += parseInt(v.rate);
                                // totalAmount += parseInt(v.amount);

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                tableHtml += `<td>${v.product.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                    tableHtml +=
                                            `<td >${v.demand_qty}<input type='hidden' name='demandQty[]' id='demandQty' value='${v.demand_qty}' class='form-control'/></td>`;
                                        tableHtml +=
                                            `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty_in}' class='form-control' style="width:120px;"/></td>`;
                                tableHtml +=
                                    `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
                                // tableHtml +=
                                //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
     
                            });

                            $('#GridTable').html(tableHtml);
                           
                            
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('.ReloadOrder').addClass('d-none');
                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalPrice').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_voucher_no').val(null);
                            let date = new Date()
                            $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                .getMonth()) + 1) + '-' + date.getDate());

                            $('#date').focus();

                            $('.inward-gatepass-no2 input').val(null);
                            $('.inward-gatepass-no2').addClass('d-none');
                            $('.inward-gatepass-no1').removeClass('d-none');
                            $('.submit-form').removeClass('d-none');
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
