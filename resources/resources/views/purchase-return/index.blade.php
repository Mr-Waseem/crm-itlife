@extends('app')
@section('head')
<title>Purchase Return Voucher</title>
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
            Purchase Return Voucher
        </h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
            <li class="breadcrumb-item active"><a href="#">Purchase Return Voucher</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <section class="content">
                <div class="box">
                    <div class="box-header with-border">
                        <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Purchase Return Voucher</h6>
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
                                {!! Form::open(['url' => 'purchase-return', 'class' => 'form-horizontal', 'id' =>
                                'purchase-return-form']) !!}
                                {!! Form::hidden('update_voucher_id', null, ['id' => 'update_voucher_id']) !!}
                                {!! Form::hidden('type', 'Purchase Return', ['id' => 'type']) !!}
                                {!! Form::hidden('warehouse_id', Auth::User()->warehouse_id, ['id' => 'warehouse_id']) !!}
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
                                        'required' => 'required',
                                        'autofocus' => 'autofocus',
                                        ]) !!}
                                        @error('voucher_no')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                        <label for="voucher_no"><i class="fa fa-caret-right"></i>Invoice#<span
                                                class="text-danger">*</span></label>
                                        {!! Form::text('sale_return_invoice_no', null, [
                                        'id' => 'sale_return_invoice_no',
                                        'class' => 'form-control',
                                        'required' => 'required'
                                        ]) !!}
                                        @error('sale_return_invoice_no')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                        <label for="party_name"><i class="fa fa-caret-right"></i> Party Name<span
                                                class="text-danger">*</span></label>
                                        {!! Form::select('party_name', $customers, null, [
                                        'id' => 'party_name',
                                        'class' => 'form-control select2',
                                        'tabindex' => '2',
                                        'required' => 'required',
                                        ]) !!}
                                        {!! Form::hidden('party_id', null, ['id' => 'party_id']) !!}
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
                                    <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                        <label for="purchaser_id"><i class="fa fa-caret-right"></i>Purchaser<span
                                                class="text-danger">*</span></label>
                                        {!! Form::select('purchaser_id', $purchasers, null, [
                                        'id' => 'purchaser_id',
                                        'class' => 'form-control select2',
                                        'tabindex' => '6',
                                        ]) !!}
                                        @error('purchaser_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                        <label for="transaction_type"><i class="fa fa-caret-right"></i>Credit To<span
                                                class="text-danger">*</span></label>
                                        {!! Form::select('credit_to', $transaction_type, null, [
                                        'id' => 'credit_to',
                                        'class' => 'form-control select2',
                                        'tabindex' => '4',
                                        ]) !!}
                                        @error('credit_to')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                   
                                    <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                        <label for="remarks"><i class="fa fa-caret-right"></i> Remarks<span
                                                class="text-danger">*</span></label>
                                        {!! Form::text('remarks', null, [
                                        'id' => 'remarks',
                                        'class' => 'form-control',
                                        'tabindex' => '7',
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
                                                        <!-- <th>GoDown</th> -->
                                                        <!-- <th class="d-none">Code</th> -->
                                                        <th style="width: 30%;">Product Name</th>
                                                        <th>Unit</th>
                                                        <th>Qty</th>
                                                        <th>Packing</th>
                                                        <th>Net.Weight</th>
                                                        <th>Rate</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="bg-secondary">
                                                        <!-- <td>
                                                            {!! Form::select('warehouse1', $warehouse, null, [
                                                            'id' => 'warehouse1',
                                                            'class' => 'form-control select2',
                                                            'tabindex' => '8',
                                                            ]) !!}
                                                            <span class="warehouse1_err text-danger"></span>
                                                        </td> -->
                                                        <!-- <td class="d-none">
                                                            {!! Form::text('code1', null, [
                                                            'id' => 'code1',
                                                            'class' => 'form-control bg-white',
                                                            'disabled' => 'disabled',
                                                            'tabindex' => '9',
                                                            ]) !!}
                                                            {!! Form::hidden('code2', null, ['id' => 'code2']) !!}
                                                        </td> -->
                                                        <td>
                                                            {!! Form::select('product_id1', $products, null, [
                                                            'id' => 'product_id1',
                                                            'class' => 'form-control select2',
                                                            'tabindex' => '10',
                                                            ]) !!}
                                                            <span class="product_err text-danger"></span>
                                                        </td>
                                                        <td>
                                                            {!! Form::text('unit1', null, [
                                                            'id' => 'unit1',
                                                            'class' => 'form-control bg-white',
                                                            'disabled' => 'disabled',
                                                            'placeholder' => 'Unit',
                                                            'tabindex' => '11',
                                                            ]) !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('qty1', null, [
                                                            'id' => 'qty1',
                                                            'class' => 'form-control',
                                                            'placeholder' => 'Qty',
                                                            'tabindex' => '12',
                                                            'onkeyup' => 'QuantityKeyUp($(this).val())',
                                                            ]) !!}
                                                            <span class="qty_err text-danger"></span>
                                                        </td>
                                                        <td>
                                                            {!! Form::text('packing1', null, [
                                                            'id' => 'packing1',
                                                            'class' => 'form-control',
                                                            'placeholder' => 'Packing',
                                                            'tabindex' => '13',
                                                            ]) !!}
                                                            <span class="packing_err text-danger"></span>
                                                        </td>
                                                        <td>
                                                            {!! Form::text('net_weight1', null, [
                                                            'id' => 'net_weight1',
                                                            'class' => 'form-control',
                                                            'placeholder' => 'Net weight',
                                                            'tabindex' => '14',
                                                            ]) !!}
                                                            <span class="net_weight1_err text-danger"></span>
                                                        </td>
                                                        <td>
                                                            {!! Form::text('price1', null, [
                                                            'id' => 'price1',
                                                            'class' => 'form-control bg-white',
                                                            'placeholder' => 'Rate',
                                                            'tabindex' => '15',
                                                            'onkeyup' => 'PriceKeyUp($(this).val())',
                                                            ]) !!}
                                                            <span class="price_err text-danger"></span>
                                                        </td>
                                                        <td>
                                                            {!! Form::text('total1', null, [
                                                            'id' => 'total1',
                                                            'class' => 'form-control bg-white',
                                                            'placeholder' => 'Total',
                                                            'tabindex' => '16',
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
                                                            <th style="width: 30%;">Product Name</th>
                                                            <th>Qty</th>
                                                            <th>Unit</th>
                                                            <th>Rate</th>
                                                            <th style="width: 30%;">Amount</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="GridTable"></tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="2"><strong>Total</strong></td>
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
            <form action="{{ URL::to('purchase-return/delete-voucher') }}" method="post" id="delete_voucher_form">
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
<div class="modal hide fade" id="print-record-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
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
                    $("#sale_return_invoice_no").select();
                }
            });
            $('#sale_return_invoice_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // $("#party_name").select2('open');
                    $("#remarks").select();
                    var voucher_no = parseInt($('#sale_return_invoice_no').val());
                    var warehouseID = parseInt($('#warehouse_id').val());
                    $.ajax({
                                url: "{{ URL::to('purchase-return/load/invoice') }}",
                                data: {voucher_no:voucher_no, warehouseID:warehouseID},
                                type: 'get',
                                dataType: 'json',
                                beforeSend: function(response) {
                                    $('#show_err').html(
                                        '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                                    );
                                },
                                success: function(response) {
                                    // alert(response.data[0].credit_to);
                                if (response.data != '') {
                                    var tableHtml = '';
                                    var totalQty = 0;
                                    $.each(response.data[0].sale_purchase_details, function(i, v) {
                                        // alert(1);
                                        var comment = '';
                                        if (v.comments != null) {
                                            comment = v.comments;
                                        }
                                        
                                        tableHtml += `<tr>`;
                                        tableHtml +=
                                            `<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                        tableHtml += `<td>${v.product.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                                </td>`;
                                        
                                        tableHtml +=
                                            `<input type='hidden' name='demandQty[]' id='demandQty' value='${v.demandQty}' class='form-control'/>`;
                                        tableHtml +=
                                            `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' onkeyup="changerate($(this).closest('tr'));" class='form-control' style="width:120px;"/>
                                                <span class="qty_errr text-danger"></span>
                                                </td>`;
                                        tableHtml +=
                                            `<td>${v.product.uom}</td>`;
                                            tableHtml +=
                                            `<td style="width:120px;"><input type='text' name='rate[]' id='rate' value='${v.rate}' class='form-control' style="width:120px;" onkeypress="return isNumberKey(event);" onkeyup="changerate($(this).closest('tr'));"/>
                                                <span class="rate_errr text-danger"></span>
                                                </td>`;
                                            tableHtml +=
                                            `<td style="width:120px;"><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                                            tableHtml +=
                                            `<td style="width:120px; display:none;"><input type='text' name='st_rate[]' id='st_rate' value='0' class='form-control' style="width:120px;" onkeyup="changetax($(this).closest('tr'));"
                                            onkeyup="EnterStRate($(this).closest('tr'));"
                                            onchange="EnterKeyBoard($(this).closest('tr'));"
                                            onkeypress="return isNumberKey(event)"
                                            /></td>`;
                                            tableHtml +=
                                            `<td style="width:120px; display:none;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;
                                            
                                         tableHtml +=
                                            `<td style="width:130px; display:none;">
                                                <input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" />
                                            </td>`;
                                            tableHtml +=
                                            `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                        tableHtml += `</tr>`;
                                      
                                    });
                                    
                                    $('#GridTable').html(tableHtml);
                                    // $('#TotalIgpQty').text(totalQty);
                                    
                                    //  TotalSaleTax();
                                     TotalAmount();
                                     TotalQty();
                                    // TotalExclVolue();
                                    // $('#updated_by_name').removeClass('d-none');
                                    // $('.grn-no2 input').val(response.data[0].salepurchase.grn.voucher_no + "-" 
                                    // + response.data[0].salepurchase.grn.inward_gatepass.supplier.party_name);
                                    // $('#grn_id1').val(response.data[0].salepurchase.grn
                                    // .id);
                                    // $('.grn-no2').removeClass('d-none');
                                    // $('.grn-no1').addClass('d-none');
                                    $('.ReloadOrder').removeClass('d-none');
                                    $('#date').val(response.data[0].date);
                                    $('#party_id').val(response.data[0].party_id);
                                    // $('#party_name').val(response.data[0].party.id + "-" + response.data[0].party.party_name "-" + response.data[0].party.address).select2();
                                    $('#party_name').val(response.data[0].party.id + "_" + response.data[0].party.party_name + "_" + response.data[0].party.address).select2();
                                    $('#purchaser_id').val(response.data[0].purchaser_id).select2();
                                    $('#remarks').val(response.data[0].remarks);
                                    $('#credit_to').val(response.data[0].credit_to).select2();
                                    // $('#vehicle_no').val(response.data[0].vehicle_no);
                                    // $('#transport_company').val(response.data[0].transport_company);
                                    // $('#driver_name').val(response.data[0].driver_name);
                                    // $('#driver_phoneno').val(response.data[0].driver_phoneno);
                                    // $('#builty_no').val(response.data[0].builty_no);
                                    // $('#supplier_id1').val(response.data[0].supplier.id);
                                    // $('#purchaser_id1').val(response.data[0].purchaser_id);
                                    // $('#update_voucher_id').val(response.data[0].salepurchase.id);
                                    
                                } else {
                                    $('#show_err').html(
                                        '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                                    );
                                    $('.ReloadOrder').addClass('d-none');
                                    $('#GridTable').html(null);
                                    $('#TotalRecQty').text(0);
                                    $('#TotalIgpQty').text(0);
                                    $('#TotalExclVolue').text(0);
                                    $('#TotalSaleTax').text(0);
                                    $('#TotalAmount').text(0);
                                    $('#updated_by_name').addClass('d-none');
                                    $('#update_voucher_no').val(null);
                                    $('#date').focus();
                                    $('#voucher_no').focus();
                                    $('#dcn_no1').val(null);
                                    $('#remarks').val(null);
                                    $('#vehicle_no').val(null);
                                    $('#transport_company').val(null);
                                    $('#driver_name').val(null);
                                    $('#builty_no').val(null);
                                    $('#supplier_id').val(null);
                                    $('#purchaser_id').val(null);
                                    $('#driver_phoneno').val(null);
                                }
                            }
                });
                }
            });
            
            $('#party_name').change(function(event) {
                var party_name = $(this).val();
                if (party_name) {
                    $('#party_name').select2().trigger('select2:close');
                    var party = $(this).val();
                    $('#party_id').val(party.split('_')[0]);
                    $('#address').val(party.split('_')[2]);
                    $('#purchaser_id').select2('open');
                }
            });
            $('#purchaser_id').change(function(event) {
                var transaction_type = $(this).val();
                if (transaction_type) {
                    $('#purchaser_id').select2().trigger('select2:close');
                    $('#credit_to').select2('open');
                }
            });
            // $('#supplier_id').change(function(event) {
            //     var supplier_id = $(this).val();
            //     if (supplier_id) {
            //         $('#supplier_id').select2().trigger('select2:close');
            //         $('#purchaser_id').select2('open');
            //     }
            // });
            $('#credit_to').change(function(event) {
                var purchaser_id = $(this).val();
                if (purchaser_id) {
                    $('#credit_to').select2().trigger('select2:close');
                    $('#remarks').focus();
                }
            });
            $('#remarks').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#product_id1").select2('open');
                }
            });
            // $('#warehouse1').change(function(event) {
            //     var warehouse = $(this).val();
            //     if (warehouse != null) {
            //         $('#warehouse1').select2().trigger('select2:close');
            //         $('#product_id1').select2('open');
            //     }
            // });
            $('#product_id1').change(function(event) {
                var product_id = $(this).val();
                if (product_id != null) {
                    $('#unit1').val(product_id.split('_')[2]);
                    $('#packing1').val(product_id.split('_')[5]);
                    $('#net_weight1').val(product_id.split('_')[6]);
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
                        $('#packing1').focus();
                    }
                }
            });
            $('#packing1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#net_weight1").focus();
                }
            });
            $('#net_weight1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#price1").focus();
                }
            });
            $('#price1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('.product_id1_err').text('');
                    $('.qty_err').text('');
                    $('.price_err').text('');
                    $('.packing_err').text('');
                    $('.net_weight1').text('');


                    var product_id = parseInt($('#product_id1').val());
                    var warehouse = parseInt($('#warehouse1').val());
                    var packing = $('#packing1').val();
                    var net_weight = $('#net_weight1').val();
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
                    } else
                    if (!warehouse) {
                        $('#warehouse1').select2('open');
                        $('.warehouse1_err').text('This field is required');
                        return false;
                    } else
                    if (!packing) {
                        $('#packing1').focus();
                        $('.packing_err').text('This field is required');
                        return false;
                    } else
                    if (!net_weight) {
                        $('#net_weight1').focus();
                        $('.net_weight1_err').text('This field is required');
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
            var warehouse = parseInt($('#warehouse1').val());
            if (!warehouse) {
                $('.warehouse1_err').text('This field is required');
                $('#warehouse1').focus();
                return false;
            } else {
                $('.warehouse1_err').text('');
            }


            var product_id = parseInt($('#product_id1').val());
            if (!product_id) {
                $('.product_err').text('This field is required');
                $('#product_id1').focus();
                $('#product_id1').val(null).select2('open');
                return false;
            } else {
                $('.product_err').text('');
            }

            var warehouse = parseInt($('#warehouse1').val());
            if (!warehouse || warehouse <= 0) {
                $('.warehouse1_err').text('This field is required & Must be greater than zero');
                $('#warehouse1').focus();
                $('#warehouse1').val(null).select2('open');
                return false;
            } else {
                $('.warehouse1_err').text('');
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

            var packing = $('#packing1').val();
            if (!packing) {
                $('#packing1').focus();
                $('.packing_err').text('This field is required');
                return false;
            } else {
                $('.packing_err').text('');
            }

            var net_weight = $('#net_weight1').val();
            if (!net_weight) {
                $('#net_weight1').focus();
                $('.net_weight1_err').text('This field is required');
                return false;
            } else {
                $('.net_weight1_err').text('');
            }


            var warehouse_id = parseInt(document.getElementById('warehouse1').value.split('_')[0]);
            var warehouse_name = document.getElementById('warehouse1').value.split('_')[1];
            var pro_id = document.getElementById('product_id1').value.split('_')[0];
            var pro_name = document.getElementById('product_id1').value.split('_')[1];
            var pro_unit = document.getElementById('product_id1').value.split('_')[2];
            var pro_cost = parseInt(document.getElementById('product_id1').value.split('_')[4]);
            var price = parseInt(document.getElementById('price1').value);
            var packing = document.getElementById('packing1').value;
            var net_weight = parseFloat(document.getElementById('net_weight1').value);
            var qty = parseInt(document.getElementById('qty1').value);
            var total = parseInt(document.getElementById('total1').value);
            var TotalQty = parseInt(document.getElementById('TotalQty').innerHTML);
            var TotalAmount = parseInt(document.getElementById('TotalAmount').innerHTML);
            var TotalNetWeight = parseFloat(document.getElementById('TotalNetWeight').innerHTML);

            var cost_amount = pro_cost * qty;
            var sale_amount = price * qty;

            var grandTotalQty = TotalQty + qty;
            var grandTotalAmount = TotalAmount + total;
            var grandTotalNetWeight = TotalNetWeight + net_weight;


            var tableHtml = `<tr>`;
            tableHtml +=
                `<td>${warehouse_name}<input type='hidden' name='warehouse_id[]' id='warehouse_id' value='${warehouse_id}' /></td>`;
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
                `<td>${packing}<input type='hidden' name='packing[]' id='packing' value='${packing}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
            tableHtml +=
                `<td>${net_weight}<input type='hidden' name='net_weight[]' id='net_weight' value='${net_weight}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
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
            $('#packing1').val(null);
            $('#net_weight1').val(null);


            $('#TotalQty').html(grandTotalQty);
            $('#TotalAmount').html(grandTotalAmount);
            $('#TotalNetWeight').html(grandTotalNetWeight);

            $('#warehouse1').select2('open');
            $('#warehouse1').val(null);

            $('#product_id1').val(null).select2();
        }

        function changerate(row){
            var qty =$(row).find("td:eq('2')").find('input').val();
            var rate = $(row).find("td:eq('4')").find('input').val();
            var sTRate = parseInt($(row).find("td:eq('6')").find('input').val());
            var saleTax = parseInt($(row).find("td:eq('7')").find('input').val());
            var exclValue;
            var stValue;
            var totalAmount;
            if(qty=='' || qty==0){
                $('.qty_errr').text('Please Enter Qty');
                $('#qty').focus();
                return  false;
            }
            if(rate==null || rate==0){
                 exclValue=0
                 stValue=0
                totalAmount=0
            }else{
                ///Excl valu
                 exclValue=rate*qty
                   //st rate
                stValue=(rate*sTRate/100)*qty;
                //total Amount
                  totalAmount=stValue + exclValue;
            }
            $(row).find("td:eq('5')").find('input').val(parseInt(exclValue));
            $(row).find("td:eq('7')").find('input').val(parseInt(stValue));
            $(row).find("td:eq('8')").find('input').val(parseInt(totalAmount));
            TotalAmount();
            TotalQty();
            // TotalExclVolue();
            // TotalSaleTax();
        }

        function TotalAmount(){
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[8].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalAmount').innerText = sum.toLocaleString('en-US');
        }
         function TotalQty() {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[2].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalQty').innerText = sum.toLocaleString('en-US');
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
                $('#purchase-return-form').submit();
                $('.submit-form').attr('disabled', true);
            });
            // End FOrm Submit

            // Reset btn feature
            $('.reset-btn').click(function() {
                $('#party_name').val(null).select2();
                $('#transaction_type').val(null).select2();
                $('#update_voucher_id').val(null);
                $('#updated_by_name').addClass('d-none');
            });
            // End Reset btn feature
        });
</script>


<!-- OnChange Qty -->
<script>
    function DeleteRow(row) {
        $(row).remove();
        TotalAmount();
        TotalQty();
    }
</script>
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
                    var warehouseID = parseInt($('#warehouse_id').val());
                    var base_url = $('#base_url').val();
                    $.ajax({
                        url: "{{ URL::to('purchase-return/print/voucher') }}",
                        data: {voucher_no:voucher_no, warehouseID:warehouseID},
                        type: 'get',
                        beforeSend: function(response) {
                            $('#print-receipt-modal-body').html(
                                '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                            );
                        },
                        success: function(response) {
                            if (response != null && response!=0) {
                                $('#print-receipt-modal-body').html(
                                    `<object data="${base_url}/resources/upload/purchase-return/${response}" type="application/pdf" width="100%" height="800"></object>`
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
                var warehouseID = parseInt($('#warehouse_id').val());

                $.ajax({
                    url: "{{ URL::to('purchase-return/load/record') }}",
                    data: {voucher_no:voucher_no, warehouseID:warehouseID},
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
                            $.each(response.data[0].sale_purchase_details, function(i, v) {
                                tableHtml += `<tr>`;
                                tableHtml +=`<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                tableHtml += `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                tableHtml +=`<input type='hidden' name='demandQty[]' id='demandQty' value='${v.qty_in}' class='form-control'/>`;
                                tableHtml +=`<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' onkeyup="changerate($(this).closest('tr'));" class='form-control'/></td>`;
                                tableHtml +=`<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                tableHtml +=`<td><input type='text' name='rate[]' id='rate' value='${v.rate}' onkeyup="changerate($(this).closest('tr'));" class='form-control' onkeypress="return isNumberKey(event);"/></td>`;
                                tableHtml +=`<td><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                                tableHtml +=`<td style="display:none;"><input type='text' name='st_rate[]' id='st_rate' value='0' onkeyup="changetax($(this).closest('tr'));" class='form-control'
                                    onkeyup="EnterStRate($(this).closest('tr'));"
                                    onkeypress="return isNumberKey(event)"
                                    /></td>`;
                                tableHtml +=`<td style="display:none;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;
                                tableHtml +=`<td style="display:none;"><input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" /></td>`;
                                tableHtml +=`<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });
                            $('#GridTable').html(tableHtml);
                            $('#update_voucher_id').val(response.data[0].id);
                            $('#date').val(response.data[0].date);
                            $('#voucher_no').val(response.data[0].voucher_no);
                            $('#sale_return_invoice_no').val(response.data[0].sale_return_invoice_no);
                            $('#party_id').val(response.data[0].party_id);
                            $('#party_name').val(response.data[0].party.id + "_" + response.data[0].party.party_name + "_" + response.data[0].party.address).select2();
                            $('#address').val(response.data[0].party.address);
                            $('#purchaser_id').val(response.data[0].purchaser_id).select2();
                            $('#credit_to').val(response.data[0].credit_to).select2();
                            $('#remarks').val(response.data[0].remarks);
                            $('#voucher_no').focus();
                            TotalAmount();
                            TotalQty();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('.ReloadOrder').addClass('d-none');
                            $('#GridTable').html(null);
                            $('#TotalExclVolue').text(0);
                            $('#TotalSaleTax').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');
                            $('#update_voucher_no').val(null);
                            $('#date').focus();
                            $('#voucher_no').focus();
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
                    url: "{{ URL::to('purchase-return/load/next/record') }}",
                        data: {voucher_no:voucher_no, warehouseID:warehouseID},
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
                            $.each(response.data[0].sale_purchase_details, function(i, v) {
                                tableHtml += `<tr>`;
                                tableHtml +=`<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                tableHtml += `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                tableHtml +=`<input type='hidden' name='demandQty[]' id='demandQty' value='${v.qty_in}' class='form-control'/>`;
                                tableHtml +=`<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' onkeyup="changerate($(this).closest('tr'));" class='form-control'/></td>`;
                                tableHtml +=`<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                tableHtml +=`<td><input type='text' name='rate[]' id='rate' value='${v.rate}' onkeyup="changerate($(this).closest('tr'));" class='form-control' onkeypress="return isNumberKey(event);"/></td>`;
                                tableHtml +=`<td><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control' style="width:120px;"readonly='readonly'/></td>`;
                                tableHtml +=`<td style="display:none;"><input type='text' name='st_rate[]' id='st_rate' value='0' onkeyup="changetax($(this).closest('tr'));" class='form-control'
                                    onkeyup="EnterStRate($(this).closest('tr'));"
                                    onkeypress="return isNumberKey(event)"
                                    /></td>`;
                                tableHtml +=`<td style="display:none;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;
                                tableHtml +=`<td style="display:none;"><input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' style="width:130px;" /></td>`;
                                tableHtml +=`<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });
                            $('#GridTable').html(tableHtml);
                            $('#update_voucher_id').val(response.data[0].id);
                            $('#date').val(response.data[0].date);
                            $('#voucher_no').val(response.data[0].voucher_no);
                            $('#sale_return_invoice_no').val(response.data[0].sale_return_invoice_no);
                            $('#party_id').val(response.data[0].party_id);
                            $('#party_name').val(response.data[0].party.id + "_" + response.data[0].party.party_name + "_" + response.data[0].party.address).select2();
                            $('#address').val(response.data[0].party.address);
                            $('#purchaser_id').val(response.data[0].purchaser_id).select2();
                            $('#credit_to').val(response.data[0].credit_to).select2();
                            $('#remarks').val(response.data[0].remarks);
                            $('#voucher_no').focus();
                            TotalAmount();
                            TotalQty();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('.ReloadOrder').addClass('d-none');
                            $('#GridTable').html(null);
                            $('#TotalExclVolue').text(0);
                            $('#TotalSaleTax').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');
                            $('#update_voucher_no').val(null);
                            $('#date').focus();
                            $('#voucher_no').focus();
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
                $.ajax({
                    url: "{{ URL::to('purchase-return/load/previous/record') }}",
                    type: 'get',
                    data: {voucher_no:voucher_no, warehouseID:warehouseID},
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            var tableHtml = '';
                            $.each(response.data[0].sale_purchase_details, function(i, v) {
                                tableHtml += `<tr>`;
                                tableHtml +=`<td>${v.product.code}<input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                tableHtml += `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                                tableHtml +=`<input type='hidden' name='demandQty[]' id='demandQty' value='${v.qty_in}' class='form-control'/>`;
                                tableHtml +=`<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' onkeyup="changerate($(this).closest('tr'));" class='form-control'/></td>`;
                                tableHtml +=`<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                                tableHtml +=`<td><input type='text' name='rate[]' id='rate' value='${v.rate}' onkeyup="changerate($(this).closest('tr'));" class='form-control' onkeypress="return isNumberKey(event);"/></td>`;
                                tableHtml +=`<td><input type='text' name='excl_val[]' id='excl_val' value='${v.excl_val}' class='form-control' style="width:220px;"readonly='readonly'/></td>`;
                                tableHtml +=`<td style="display:none;"><input type='text' name='st_rate[]' id='st_rate' value='0' onkeyup="changetax($(this).closest('tr'));" class='form-control'
                                    onkeyup="EnterStRate($(this).closest('tr'));"
                                    onkeypress="return isNumberKey(event)"
                                    /></td>`;
                                tableHtml +=`<td style="display:none;"><input type='text' name='sale_tax[]' id='sale_tax' value='${v.sale_tax}' class='form-control' style="width:120px;" readonly='readonly'/></td>`;
                                tableHtml +=`<td style="display:none;"><input type='text'  value='${v.total}' name='total[]'  id='total'  class='form-control' readonly='readonly' /></td>`;
                                tableHtml +=`<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });
                            $('#GridTable').html(tableHtml);
                            $('#update_voucher_id').val(response.data[0].id);
                            $('#date').val(response.data[0].date);
                            $('#voucher_no').val(response.data[0].voucher_no);
                            $('#sale_return_invoice_no').val(response.data[0].sale_return_invoice_no);
                            $('#party_id').val(response.data[0].party_id);
                            $('#party_name').val(response.data[0].party.id + "_" + response.data[0].party.party_name + "_" + response.data[0].party.address).select2();
                            $('#address').val(response.data[0].party.address);
                            $('#purchaser_id').val(response.data[0].purchaser_id).select2();
                            $('#credit_to').val(response.data[0].credit_to).select2();
                            $('#remarks').val(response.data[0].remarks);
                            $('#voucher_no').focus();
                            TotalAmount();
                            TotalQty();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('.ReloadOrder').addClass('d-none');
                            $('#GridTable').html(null);
                            $('#TotalExclVolue').text(0);
                            $('#TotalSaleTax').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');
                            $('#update_voucher_no').val(null);
                            $('#date').focus();
                            $('#voucher_no').focus();
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