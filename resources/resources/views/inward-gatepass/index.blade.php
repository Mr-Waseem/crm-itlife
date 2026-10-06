@extends('app')
@section('head')
<title>Inward GatePass</title>
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
            Inward GatePass
        </h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
            <li class="breadcrumb-item active"><a href="#">Inward GatePass</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <section class="content">
                <div class="box">
                    <!-- <div class="box-header with-border">
                        <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Inward GatePass</h6>
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
                                {!! Form::open(['url' => 'inward-gatepass', 'class' => 'form-horizontal', 'id' =>
                                'inward-gatepass-form']) !!}
                                {!! Form::hidden('created_by', Auth::User()->id, ['id' => 'created_by']) !!}
                                {!! Form::hidden('status', 0, ['id' => 'status']) !!}
                                {!! Form::hidden('update_bill_no', null, ['id' => 'update_bill_no']) !!}
                                {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                

                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                        <label for="date"><i class="fa fa-caret-right"></i> Vr. Date</label>
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
                                    <div class="col-lg-1 col-md-4 col-sm-12 mt-1">
                                        <label for="bill_no"><i class="fa fa-caret-right"></i> Vr.No <span
                                                class="text-danger">*</span></label>
                                        {!! Form::text('bill_no', $codes, [
                                        'id' => 'bill_no',
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'onkeypress' => 'return isNumberKeyNoPoint(event)'
                                        ]) !!}
                                        @error('bill_no')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                        <label for="warehouse_id"><i class="fa fa-caret-right"></i> Warehouse<span
                                                class="text-danger">*</span></label>
                                        {!! Form::select('warehouse_id', $warehouse, null, [
                                        'id' => 'warehouse_id',
                                        'class' => 'form-control select2',
                                        'tabindex' => '10',
                                        'required' => 'required',
                                        ]) !!}
                                        @error('warehouse_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-12 mt-1 req_gen_no1">
                                        <label for="req_gen_id"><i class="fa fa-caret-right"></i>Request Number#<span
                                                class="text-danger">*</span></label>
                                        {!! Form::select('req_gen_id', $requestgenerate, null, [
                                        'id' => 'req_gen_id',
                                        'class' => 'form-control select2',
                                        'tabindex' => '2',
                                        'required' => 'required',
                                        ]) !!}
                                        @error('req_gen_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-12 mt-1 mt-1 req_gen_no2 d-none">
                                        <label for="req_gen_id"><i class="fa fa-caret-right"></i> Request#<span
                                                class="text-danger">*</span></label>
                                        {!! Form::text('req_gen_id_edit', null, [
                                        'id' => 'req_gen_id_edit',
                                        'class' => 'form-control',
                                        'tabindex' => '2',
                                        'readonly' => 'readonly',
                                        ]) !!}
                                        @error('req_gen_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-1 col-md-6 col-sm-12 mt-1 SelectRequest">
                                        <label for="grn_no"><i class="fa fa-caret-right"></i>Select<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group saleDemand">
                                            <!-- <input type="text" class="form-control" id="grn_voucher_no" disabled
                                                placeholder="No:"> -->
                                            <div class="input-group-addon bg-primary border-primary grn-modal-btn"
                                                data-toggle="modal" data-target=".grn-numbers-modal">
                                                <i class="fa fa-th-list"></i>
                                            </div>
                                            @error('transaction_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="modal fade grn-numbers-modal" tabindex="-1" role="dialog"
                                            aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary"
                                                        style="border-top-left-radius: 0px;border-top-right-radius:0px;">
                                                        <h4 class="modal-title text-white font-weight-bold"
                                                            id="myLargeModalLabel">
                                                            Pending Requests
                                                        </h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="close-modal">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-bordered table-hover data-table"
                                                                style="width: 100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Select</th>
                                                                        <th>Sr#</th>
                                                                        <th>Req#</th>
                                                                        <th>Code</th>
                                                                        <th style="width: 30%;">Product Name</th>
                                                                        <th>Unit</th>
                                                                        <th>Demand Qty</th>
                                                                        <th>Balance</th>
                                                                        <th>Comment</th>
                                                                       
                                                                    </tr>
                                                                </thead>
                                                                <!-- <tbody id="GridSaleDemand"></tbody> -->
                                                            </table>
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-12">
                                                                    <button class="btn btn-primary select-checkboxes" type="button" style="float:right;">Transfer</button>
                                                                    <!-- <button class="btn btn-secondary reset-btn" type="reset">Reset</button> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                    <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                        <label for="vehicle_no"><i class="fa fa-caret-right"></i>Vehicle#<span
                                                class="text-danger">*</span></label>
                                        {!! Form::text('vehicle_no', null, [
                                        'id' => 'vehicle_no',
                                        'class' => 'form-control',
                                        'tabindex' => '3',
                                        'required' => 'required',
                                        ]) !!}
                                        <span class="text-danger vehicle_no_err"></span>
                                    </div>
                                    
                                    <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                        <label for="transport_company"><i class="fa fa-caret-right"></i> Transport
                                            Company<span class="text-danger">*</span></label>
                                        {!! Form::text('transport_company', null, [
                                        'id' => 'transport_company',
                                        'class' => 'form-control',
                                        'tabindex' => '4',
                                        'required' => 'required',
                                        ]) !!}
                                        <span class="text-danger transport_company_err"></span>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                        <label for="driver_name"><i class="fa fa-caret-right"></i> Driver Name<span
                                                class="text-danger">*</span></label>
                                        {!! Form::text('driver_name', null, [
                                        'id' => 'driver_name',
                                        'class' => 'form-control',
                                        'tabindex' => '5',
                                        'required' => 'required',
                                        ]) !!}
                                        <span class="text-danger driver_name_err"></span>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                        <label for="builty_no"><i class="fa fa-caret-right"></i>Builty#<span
                                                class="text-danger">*</span></label>
                                        {!! Form::text('builty_no', null, [
                                        'id' => 'builty_no',
                                        'class' => 'form-control',
                                        'tabindex' => '6',
                                        'required' => 'required',
                                        ]) !!}
                                        <span class="text-danger builty_no_err"></span>
                                    </div>
                                    <!-- <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                        <label for="supplier_id"><i class="fa fa-caret-right"></i> Supplier<span
                                                class="text-danger">*</span></label>
                                        {!! Form::select('supplier_id', $suppliers, null, [
                                        'id' => 'supplier_id',
                                        'class' => 'form-control select2',
                                        'tabindex' => '7',
                                        'required' => 'required',
                                        'readonly' => 'readonly',
                                        ]) !!}
                                        @error('supplier_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                        <label for="purchaser_id"><i class="fa fa-caret-right"></i> Purchaser<span
                                                class="text-danger">*</span></label>
                                        {!! Form::select('purchaser_id', $purchasers, null, [
                                        'id' => 'purchaser_id',
                                        'class' => 'form-control select2',
                                        'tabindex' => '8',
                                        'required' => 'required',
                                        ]) !!}
                                        @error('purchaser_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> -->
                                    <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                        <label for="supplier_id"><i class="fa fa-caret-right"></i> Supplier<span
                                                class="text-danger">*</span></label>
                                        {!! Form::hidden('supplier_id', null, [
                                        'id' => 'supplier_id',
                                        'class' => 'form-control',
                                        ]) !!}
                                        {!! Form::text('supplier_name', null, [
                                        'id' => 'supplier_name',
                                        'class' => 'form-control',
                                        'readonly' => 'readonly',
                                        ]) !!}
                                        @error('supplier_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                        <label for="purchaser_id"><i class="fa fa-caret-right"></i> Purchaser<span
                                                class="text-danger">*</span></label>
                                                {!! Form::hidden('purchaser_id', null, [
                                        'id' => 'purchaser_id',
                                        'class' => 'form-control',
                                        ]) !!}
                                        {!! Form::text('purchaser_name', null, [
                                        'id' => 'purchaser_name',
                                        'class' => 'form-control',
                                        'readonly' => 'readonly',
                                        ]) !!}
                                        @error('purchaser_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                        <label for="driver_phoneno"><i class="fa fa-caret-right"></i> Driver
                                            PhoneNo<span class="text-danger">*</span></label>
                                        {!! Form::text('driver_phoneno', null, [
                                        'id' => 'driver_phoneno',
                                        'class' => 'form-control',
                                        'tabindex' => '9',
                                        'required' => 'required',
                                        ]) !!}
                                        <span class="text-danger driver_phoneno_err"></span>
                                    </div>
                                    
                                    <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                        <label for="Reqdate"><i class="fa fa-caret-right"></i> Request date</label>
                                        {!! Form::date('Reqdate', null, [
                                        'id' => 'Reqdate',
                                        'class' => 'form-control',
                                        'tabindex' => '11',
                                        'disabled' => 'disabled',
                                        ]) !!}
                                        
                                    </div>
                                </div>

                                {{-- <div class="row mt-3">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="table-responsive-md mb-2">
                                            <table class="table">
                                                <thead>
                                                    <tr class="bg-primary text-center">
                                                        <!-- <th>Request#</th> -->
                                                        <th>Code</th>
                                                        <th>Product</th>
                                                        <th>Unit</th>
                                                        <th>Qty</th>
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
                                                        {{-- <td>
                                                            {!! Form::text('price1', null, [
                                                            'id' => 'price1',
                                                            'class' => 'form-control bg-white',
                                                            'placeholder' => 'Price',
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
                                                        </td>
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
                                </div> --}}
                                <div class="row mt-3">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="table-responsive mb-2">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Request#</th>
                                                            <th>Code</th>
                                                            <th>Product</th>
                                                            <th>Unit</th>
                                                            <th>DemandQty</th>
                                                            <th>Qty</th>
                                                            <th>Balance</th>
                                                            <th>Comment</th>
                                                            <th>Dlt</th>
                                                            {{-- <th>Action</th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody id="GridTable"></tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="4"><strong>Total</strong></td>
                                                            <td class="bg-primary" id="DemandQty">0</td>
                                                            <td class="bg-primary" id="TotalQty">0</td>
                                                            <td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    <!-- <div class="col-lg-3 col-md-12 col-12">
                                        <div class="note note-danger">UnPosted By :</div>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-12">
                                        <div class="note note-warning">Posted By : {{ Auth::User()->name }}</div>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-12">
                                        <div class="note note-info">Updated By : <span class="d-none"
                                                id="updated_by_name"> {{ Auth::User()->name }}</span></div>
                                    </div> -->
                                    <div class="col-lg-9 col-md-12 col-12">
                                        
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-12">
                                        <button class="btn btn-primary btn-lg submit-form" type="button">Save</button>
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
            <form action="{{ URL::to('inward-gatepass/destroy') }}" method="post" id="delete_voucher_form">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to delete this Voucher?</p>
                    <input type="hidden" name="delete_bill_no" id="delete_bill_no" value="">
                    <!-- <input type="hidden" name="balance_del[]" id="balance_del" value=""> -->
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

<script src="{{ URL::asset('dashboard/datatables/jquery.js') }}"></script>
<script src="{{ URL::asset('dashboard/datatables/jquery.validate.js') }}"></script>
<script src="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript">
$(document).on('click', '.saleDemand', function() {
    //    alert("dd")
    // $('.data-table tr').empty();
    // $('.data-table').DataTable().destroy();
        // var UpdateID = document.getElementById('update_voucher_id').value;
        // alert(UpdateID);
        // var party = document.getElementById('party_name');
        // var partyid = party.value.split('_')[0];
        //  alert(partyid)
        var ReqID = document.getElementById('req_gen_id').value;
        // alert(ReqID);
        var TableDatas = new Array();
        var allDatas = new Array();
        var sum = 0;
        // $('#GridTable tr').each(function(row, tr){
        //     sum += 1;
        //     TableDatas[row]={
        //         "request_detail_id" : $(tr).find('td:eq(12)').text(),
        //     }  
        // });
        //     //It will check checkboxes dynamically
        //     for (var i = 0; i < sum; i++) {
        //         allDatas[i]= TableDatas[row]['request_detail_id'];
        //     }


            // $('.data-table').dataTable().fnDestroy();
            // $('.data-table').DataTable().fnReload();
            var table = $('.data-table').DataTable({
                
                processing: true,
                serverSide: true,
                // stateSave: true,
                bDestroy: true,
                // ajax: "{{ URL::to('delivery-challan-non-gst/load-sale-demands') }}?partyid=" +
                // partyid,
                ajax: "{{ URL::to('inward-gatepass/load-requests-data') }}?ReqID=" +
                ReqID,
                // success: function(response) {  
                //     if (response.data != '') {
                //         var tableHtml = '';

                //         $.each(response.data, function(i, v) {
                //             tableHtml += `<tr>`;
                //             tableHtml += `<td>122</td>`;
                //             tableHtml += `</tr>`;
                //         });

                //         $('#GridSaleDemand').html(tableHtml);
                //      } 
                // }
                columns: [
                    {
                        data: 'selectValue',
                        name: 'selectValue'
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    
                    {
                        data: 'request_no',
                        name: 'request_no'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
                    {
                        data: 'unit',
                        name: 'unit'
                    },
                    {
                        data: 'demandqty',
                        name: 'demandqty'
                    },
                    {
                        data: 'balance',
                        name: 'balance'
                    },
                    {
                        data: 'comment',
                        name: 'comment'
                    },
                    
                    // {
                    //     data: 'date',
                    //     name: 'date'
                    // },
                    // {
                    //     data: 'product_id',
                    //     name: 'product_id'
                    // },
                    // {
                    //     data: 'po_no',
                    //     name: 'po_no'
                    // },
                    // {
                    //     data: 'po_date',
                    //     name: 'po_date'
                    // },
                    // {
                    //     data: 'order_qty',
                    //     name: 'order_qty'
                    // }
                    // ,
                    // {
                    //     data: 'balance',
                    //     name: 'balance'
                    // }
                ]
            });
            // table.reload();
            // // // $('.data-table').DataTable().reload();
            // // $('.data-table').dataTable().fnDestroy();
            // $('.data-table').DataTable().ajax.fnReload();
});

</script>
<!-- Focus on next field -->
<script>

    // $(document).on('click', '.select-grnno', function() {
$(document).on('click', '.select-checkboxes', function() {
                var someObj = {};
                someObj.fruitsGranted = [];
                $("input:checkbox").each(function() {
                    if ($(this).is(":checked")) {
                        someObj.fruitsGranted.push($(this).attr("id"));
                        someObj.fruitsGranted.checked;
                        
                    }
                });
                
    // $('.grn-numbers-modal').close();
     $('#close-modal').click();
    // var OrderDetailId = $(this).attr('id').split('_')[0];
    var ReqID = someObj.fruitsGranted;
// alert(ReqID);
    // var voucher_no = $(this).attr('id').split('_')[1];
    // var req_gen_id = parseInt($(this).val());
    // alert(req_gen_id);
    // alert(req_gen_id);
                 //alert(req_gen_id);
                $.ajax({
                    // url: "{{ URL::to('inward-gatepass/requestgenerate/record') }}?req_gen_id=" + req_gen_id,
                    url: "{{ URL::to('inward-gatepass/requestgenerate/record') }}",
                    type: 'get',
                    data: {
                        ReqID: ReqID
                    },
                    dataType: 'json',
                    beforeSend: function(response) {
                        
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            var tableHtml = '';
                            // var totalQty = 0;

                            $.each(response.data, function(i, v) {
                                var comment = '';
                                if (v.comments != null) {
                                    comment = v.comments;
                                }

                                // totalQty += parseInt(v.qty);

                                tableHtml += `<tr>`;
                                tableHtml += `<td>${v.bill_no}
                                                    <input type='hidden' name='request_no[]' id='request_no' value='${v.bill_no}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.product.code}
                                    <input type='hidden' name='request_detail_id[]' id='request_detail_id' value='${v.id}' />
                                    <input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                tableHtml += `<td>${v.product.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                                    <input type='hidden' name='product_name[]' id='product_name' value='${v.product.product_name}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                               
                                tableHtml +=
                                    `<td>${Number(v.qty).toLocaleString('en-US')}<input type='hidden' name='qtyforshow[]' id='qtyforshow' value='${v.qty}' class='form-control'/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='' class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));", 
                                        onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));"  style="width:120px;"/></td>`;
                                    var total =0;
                                    $.each(v.gatepass_details, function( index, value ) {
                                        total = parseInt(total) + parseInt(value.qty);
                                    });
                                        var stock = v.qty - total;
                                   
                                    tableHtml +=
                                    `<td><input type='text' name='balance[]' id='balance' value='${Number(stock).toLocaleString('en-US')}' class="form-control" readonly/></td>`;
                                    tableHtml +=
                                    `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
                                    tableHtml +=
                                        `<td style="display:none;"><input type='text' name='balanceForCalculation[]' id='balanceForCalculation' value='${stock}' class="form-control" readonly/></td>`;
                                        tableHtml +=
                                        `<td style="display: none;">${v.id}</td>`;
                                    tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            // $('#GridTable').html(tableHtml);
                            $('#GridTable').append(tableHtml);
                            TotalDemandQty();
                            TotalQty();
                            // $('#TotalQty').text(totalQty.toLocaleString('en-US'));
                            $('#updated_by_name').removeClass('d-none');
                            $('#vehicle_no').focus();
                            // $('#supplier_id').val(response.data[0].request_generate.supplier_id)
                            //     .select2();
                            // $('#purchaser_id').val(response.data[0].request_generate.purchaser_id)
                            //     .select2();

                            $('#supplier_id').val(response.data[0].request_generate.supplier_id);
                            $('#supplier_name').val(response.data[0].request_generate.supplier.party_name);
                            $('#purchaser_id').val(response.data[0].request_generate.purchaser_id);
                            $('#purchaser_name').val(response.data[0].request_generate.purchaser.party_name);

                            $('#warehouse_id').val(response.data[0].request_generate.warehouse_id)
                                .select2();
                            $('#Reqdate').val(response.data[0].request_generate.date); 
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            // $('#TotalQty').text(0);
                            // $('#TotalPrice').text(0);
                            // $('#TotalAmount').text(0);
                            // $('#updated_by_name').addClass('d-none');

                            $('#update_bill_no').val(null);
                            let date = new Date()
                            $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                .getMonth()) + 1) + '-' + date.getDate());
                            $('#vehicle_no').val(null);
                            $('#transport_company').val(null);
                            $('#driver_name').val(null);
                            $('#builty_no').val(null);
                            $('#driver_phoneno').val(null);

                            $('#supplier_id').val(0).select2();
                            $('#purchaser_id').val(null).select2();
                            $('#bill_no').focus();
                        }
                    }
                });
    // $.ajax({
    //     url: "{{ URL::to('delivery-challan-non-gst/load-data') }}",
    //     type: 'get',
    //     data: {
    //         OrderDetailId: OrderDetailId
    //     },
    //     dataType: 'json',
    //     beforeSend: function() {
    //         $('#print-receipt-modal-body').html(
    //             '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
    //         );
    //     },
    //     success: function(response) {
    //         $('#print-receipt-modal-body').html(null);
    //         if (response.data != '') {
    //             var tableHtml = '';
    //             var totalQty = 0;
    //             var Totalsaleqty = 0;
    //             var Totaldispatchqty = 0;
    //             var TotalBalance = 0;
    //             var RemainingPack = 0;
    //             var RemainderPack = 0;

    //             $.each(response.data, function(i, v) {

    //                                 totalQty += parseInt(v.qty);
    //                                 Totalsaleqty += parseInt(v.order_qty);
    //                                 RemainingPack = parseInt(v.remaing_qty) / parseInt(v.packing);
    //                                 tableHtml += `<tr>`;
    //                                 tableHtml +=
    //                                     `<td>${v.product.code}<input type='hidden' name='order_detail_id[]' id='order_detail_id' value='${v.id}' /></td>`;
    //                                 tableHtml +=
    //                                     `<td>${v.product.product_name}
    //                                         <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
    //                                         <input type='hidden' name='sale_rate[]' id='sale_rate' value='${v.sale_rate}' />
    //                                         <input type='hidden' name='sale_order_no1[]' id='sale_order_no1' value='${v.voucher_no}' />
    //                                     </td>`;
    //                                     tableHtml +=
    //                                     `<td>${v.sale_order.po_no}<input type='hidden' name='po_no[]' id='po_no' value='${v.sale_order.po_no}' /></td>`;
    //                                 tableHtml +=
    //                                     `<td>${v.product.uom}<input type='hidden' name='unit' id='unit' value='${v.product.uom}' /></td>`;
    //                                 tableHtml +=
    //                                     `<td>${parseInt(v.qty)}<input type='hidden' name='demandqty[]' id='demandqty' value='${v.qty}' /></td>`;
    //                                     tableHtml +=
    //                                     `<td>${parseInt(v.order_qty)}<input type='hidden' name='demandPCS[]' id='demandPCS' value='${v.order_qty}' /></td>`;
                                       
    //                                     var total =0;
    //                                 $.each(v.dc_details2, function( index, value ) {
    //                                     total = parseInt(total) + parseInt(value.sale_qty);
    //                                 });
    //                                     var stock = v.order_qty - total;

    //                                     DCPack = parseInt(stock) / parseInt(v.packing);
    //                                     // alert(DCPack)
    //                                 if((Math.abs((parseInt(DCPack)*v.packing)-(v.remaing_qty))) == 0){
    //                                     var remarks = parseInt(DCPack)+'X'+parseInt(v.packing);
    //                                 // alert('1')
    //                                     tableHtml +=
    //                                     `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${DCPack}' class='form-control' readonly/></td>`;
                                        
    //                                 }else{
    //                                     // alert('12')
    //                                     var DCPack1 = parseInt(DCPack)+ 1;
    //                                     var remarks = parseInt(DCPack)+'X'+parseInt(v.packing)+',1x'+
    //                                     (Math.abs((parseInt(DCPack)*v.packing)-(v.remaing_qty)));
    //                                     tableHtml +=
    //                                     `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${DCPack1}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" readonly /></td>`;
                                        
    //                                 }
    //                                     tableHtml +=
    //                                     `<td>${parseInt(v.packing)}<input type='hidden' name='packing[]' id='packing' value='${v.packing}' /></td>`;
                                
                                       
    //                                     if(stock < 0){
    //                                     tableHtml +=
    //                                     // `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='0' class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="return isNumberKey(event)" style="background: red; color: white;"/></td>`;
    //                                     `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='0' 
    //                                     class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));", 
    //                                     onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));" 
    //                                     style="background: red; color: white;"/></td>`;
    //                                 }else{
    //                                     tableHtml +=
    //                                     // `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' value='${stock}' class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));" onkeypress="PressEnter($(this).closest('tr'));"/></td>`;
    //                                     `<td style="width:120px;"><input type='text' name='sale_qty[]' id='sale_qty' 
    //                                     value='${stock}' class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));", 
    //                                     onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));"/></td>`;
    //                                 }
    //                                 tableHtml +=
    //                                     `<td><input type='text' name='comment[]' id='comment' value='${remarks}' class="form-control" readonly/></td>`;
    //                                     TotalBalance += stock;
    //                                     tableHtml +=
    //                                     `<td><input type='text' name='balance[]' id='balance' value='${stock}' class="form-control" readonly/></td>`;
    //                                     tableHtml +=
    //                                     `<td style="display:none;"><input type='text' name='balanceForCalculation[]' id='balanceForCalculation' value='${stock}' class="form-control" readonly/></td>`;
    //                                     tableHtml +=
    //                                     `<td style="display: none;">${v.id}</td>`;
    //                                 tableHtml +=
    //                                     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
    //                                 tableHtml += `</tr>`;
    //                             });
    //                             // $('#party_name').val(response.data[0].party.id + "_" + response
    //                             //     .data[0].party.party_name + "_" + response.data[0].party
    //                             //     .address).select2();
    //                             // $('#party_id').val(response.data[0].party.id);
    //                             $('#GridTable').html(tableHtml);
    //                             $('#remarks').focus();
    //                             $('#address').val(response.data[0].party.address);
    //                             $('#order_date').val(response.data[0].voucher_date);
    //                             $('#po_date').val(response.data[0].sale_order.po_date);
    //                             $('#po_date1').val(response.data[0].sale_order.po_date);
    //                             $('#po_no').val(response.data[0].sale_order.po_no);
    //                             $('#po_no1').val(response.data[0].sale_order.po_no);
                                
    //                             $('#TotalQty').text(totalQty);
    //                             $('#Totalsaleqty').text(Totalsaleqty);
    //                             $('#Totaldispatchqty').text(TotalBalance);
    //                             $('#TotalBalance').text(TotalBalance);
    //                             $('#TotalQty').text(totalQty);
    //                             $('#TotalNetWeight').text(totalNetWeight);
    //                             $('#TotalAmount').text(totalAmount);

    //                             $('#party_name').val(response.data[0].stock.parties.id + '_' + response
    //                                 .data[0].stock.parties.party_name + '_' + response.data[0].stock
    //                                 .parties.address).select2();
    //                             $('#party_id').val(response.data[0].stock.parties.id);
    //                             $('#address').val(response.data[0].stock.parties.address);
    //                             $('#remarks').val(response.data[0].stock.remarks);
    //         } 
    //         // else {
    //         //     $('#show_err').html(
    //         //         '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
    //         //     );

    //         //     $('#GridTable').html(null);
    //         //     $('#TotalQty').text(0);
    //         //     $('#TotalNetWeight').text(0);
    //         //     $('#TotalAmount').text(0);
    //         //     $('#updated_by_name').addClass('d-none');

    //         //     $('#update_voucher_id').val(null);
    //         //     $('#voucher_no').focus();
    //         //     $('#party_name').val(null).select2();
    //         //     $('#party_id').val(null);
    //         //     $('#address').val(null);
    //         //     $('#remarks').val(null);
    //         // }
    //     }
    // })
});

    $(document).ready(function() {
            $('#date').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#bill_no').focus();
                }
            });

            $('#bill_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#warehouse_id').select2('open');
                }
            });

            // $('#bill_no').keydown(function(event) {
            //     var keycode = (event.keyCode ? event.keyCode : event.which);
            //     if (keycode == '13') {
            //         $('#req_gen_id').select2('open');
            //     }
            // });


            $('#req_gen_id').change(function(event) {
                var req_gen_id = $(this).val();
                if (req_gen_id !=null) {
                    $('#vehicle_no').focus();
                    $('#req_gen_id').select2().trigger('select2:close');
                }else {
                    $('#req_gen_id').focus();
                }
            });
            $('#vehicle_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#transport_company").focus();
                }
            });
            $('#transport_company').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#driver_name").focus();
                }
            });
            $('#driver_name').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#builty_no").focus();
                }
            });
            $('#driver_name').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#builty_no").focus();
                }
            });
            $('#builty_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#driver_phoneno").focus();
                }
            });
           
            $('#driver_phoneno').keydown(function(event) {
            
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#qty').focus();
                }else {
                    $('#driver_phoneno').focus();
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
            $('#price1').keydown(function(event) {
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
            $('#qty1').keydown(function(event) {
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
            $('#comment1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                   // AddGridData();
                }
            });

            // $('#warehouse_id').change(function(event) {
            //     var warehouse_id = $(this).val();
            //     if (warehouse_id !=null) {
            //         $('#warehouse_id').select2().trigger('warehouse_id:close') 
            //         $('#qty').focus();
            //     }else {
            //         $('#warehouse_id').focus();
            //     }
            // });


            $('#warehouse_id').change(function(event) {
                var warehouseID = $(this).val();
                if (warehouseID) {
                    $('#warehouse_id').select2().trigger('warehouse_id:close') 
                 
                    $.ajax({
                        url: "{{ URL::to('inward-gatepass/warehouse-requests') }}?warehouseID=" +
                        warehouseID,
                        // Route::get('recipeshow/recipename', 'RecipeNames');
                        type: 'get',
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#show_err').html(
                                '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait! Recipe Loading...</div>'
                            );
                        },
                        success: function(response) {
                            if (response.data != '') {
                                // alert("enter")
                                // $.each(response.data, function(i, v) {
                                 
                                // });
                                var option = '';
                                option +=`<option value="">Select Request</option>`;
                            $.each(response.data, function(i, v) {
                                option +=
                                    `<option value="${v.id}">${v.bill_no}</option>`;
                            });
                            $('#req_gen_id').html(option);
                            $("#req_gen_id").select2('open');
   
                            } else {
                                var option = '';
                                option +=`<option value="">No Requests Found</option>`;
                            $('#req_gen_id').html(option);
                            var updateID = $('#update_bill_no').val();
                            // alert(updateID)
                            if(updateID){
                                $("#vehicle_no").focus(); 
                            }else{
                                $("#req_gen_id").select2('open'); 
                            }
                            
                            }
                        }
                    });
                }
            });


        });
</script>
<!-- End Focus on next field -->

<!-- Append New Data on Table -->
<script>

    function refresh(){
        var ReqID = document.getElementById('req_gen_id_edit').value;
        if (ReqID) {
            $.ajax({
                url: "{{ URL::to('inward-gatepass/load-edit-requests-data') }}?ReqID=" +
                ReqID,
                type: 'get',
                dataType: 'json',
                beforeSend: function(response) {
                    $('#show_err').html(
                        '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                    );
                },
                success: function(response) {
                    
                    // console.log(response.data);
                    // alert("dfds");
                    $("#GridTable tr").remove(); 
                    if (response.data != '') {
                        var tableHtml = '';
                        
                        // $('#TotalPrice').text(totalPrice);
                        // $('#TotalAmount').text(totalAmount);
                        // $('#updated_by_name').removeClass('d-none');

                        // $('#date').val(response.data[0].inward.date);
                        // $('#vehicle_no').val(response.data[0].inward.vehicle_no);
                        // $('#transport_company').val(response.data[0].inward.transport_company);
                        // $('#driver_name').val(response.data[0].inward.driver_name);
                        // $('#driver_phoneno').val(response.data[0].inward.driver_phoneno);
                        // $('#builty_no').val(response.data[0].inward.builty_no);
                        $('#supplier_id').val(response.data[0].supplier.id);
                        $('#supplier_name').val(response.data[0].supplier.party_name);
                        $('#purchaser_name').val(response.data[0].purchaser.party_name);
                        $('#purchaser_id').val(response.data[0].purchaser.id);
                        $.each(response.data[0].request_generate_details, function(i, v) {
                                var comment = '';
                                if (v.comments != null) {
                                    comment = v.comments;
                                }

                                

                                tableHtml += `<tr>`;
                                tableHtml += `<td>${v.bill_no}
                                                    <input type='hidden' name='request_no[]' id='request_no' value='${v.bill_no}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.product.code}
                                    
                                    <input type='hidden' name='request_detail_id[]' id='request_detail_id' value='${v.id}' />
                                    <input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                tableHtml += `<td>${v.product.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product_id}' />
                                                    <input type='hidden' name='product_name[]' id='product_name' value='${v.product.product_name}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                               
                                tableHtml +=
                                    `<td>${Number(v.qty).toLocaleString('en-US')}<input type='hidden' name='qtyforshow[]' id='qtyforshow' value='${v.qty}' class='form-control'/></td>`;
                                    tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeSaleQtyEdit($(this).closest('tr'));", 
                                        onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));"  style="width:120px;"/></td>`;
                                    var total =0;
                                    // alert("d");
                                    // $.each(v.product.gatepass_details, function( index, value ) {
                                    //     total = parseInt(total) + parseInt(value.qty);
                                    //     // alert(value.qty);
                                    // });
                                        // var stock = v.qtyshow - total;
                                        var stock = 2 - 2;
                                        // alert(stock);

                                        tableHtml +=
                                    `<td><input type='text' name='balance[]' id='balance' value='${Number(stock).toLocaleString('en-US')}' class="form-control" readonly/></td>`;
                                    tableHtml +=
                                    `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
                                    tableHtml +=
                                        `<td style="display:none;"><input type='text' name='balanceForCalculation[]' id='balanceForCalculation' value='${stock}' class="form-control" readonly/></td>`;
                                    // tableHtml +=
                                    // `<td style="display:none;"><input type='text' name='DispatchQtyForFormula' id='DispatchQtyForFormula' value='${v.sale_qty}' class="form-control" readonly/></td>`;
                                    tableHtml +=
                                        `<td style="display:none;"><input type='text' name='DispatchQtyForFormula' id='DispatchQtyForFormula' value='${v.qty}' class="form-control" readonly/></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });
                            $('#GridTable').html(tableHtml);
                            $('#bill_no').focus();
                            TotalDemandQty();
                            TotalQty();
                        // $('#remarks').focus();
                        // TotalAmount();
                    }
                }
            });
        }   
    }

    function TotalDemandQty() {
        var tableData = document.getElementById('GridTable');
        var sum = 0;
        for (var i = 0; i < tableData.rows.length; i++) {
            if (tableData.rows[i].cells[4].getElementsByTagName('input')[0].value == '') {
                sum += 0;
            } else {
                sum += parseFloat(tableData.rows[i].cells[4].getElementsByTagName('input')[0].value);
                // alert(sum);
            }   
        }
        document.getElementById('DemandQty').innerText = sum.toLocaleString('en-US');
    }

    
    function TotalQty() {
        var tableData = document.getElementById('GridTable');
        var sum = 0;
        for (var i = 0; i < tableData.rows.length; i++) {
            if (tableData.rows[i].cells[5].getElementsByTagName('input')[0].value == '') {
                sum += 0;
            } else {
                sum += parseFloat(tableData.rows[i].cells[5].getElementsByTagName('input')[0].value);
                // alert(sum);
            }   
        }
        document.getElementById('TotalQty').innerText = sum.toLocaleString('en-US');
    }

function EnterKeyBoard(row){ 
                var RowIndex = row.index();
                if(event.keyCode == 13) {     
                    if(RowIndex = '0')
                    {
                    $('tr:eq(' + RowIndex + ')', GridTable).find("td:eq('7')").find('input').focus().select();
                    }
                    if(NextIndex != '0')
                    {
                    var NextIndex = RowIndex + 1;
                    $('tr:eq(' + NextIndex + ')', GridTable).find("td:eq('7')").find('input').focus().select();
                    }
                }
            }

            function changeSaleQty(row) {
                
                // var unit = $(row).find("td:eq('3')").find('input').val();
                // alert(row)
                var sale_qty = $(row).find("td:eq('5')").find('input').val();
                var stock = $(row).find("td:eq('8')").find('input').val();
                // alert(sale_qty);
                // alert(stock);
                // var DCPack = $(row).find("td:eq('6')").find('input').val();

                var RemainingStock = stock-sale_qty;
                // alert(RemainingStock);
                // $(row).find("td:eq('5')").find('text').Math.round(RemainingStock);
                $(row).find("td:eq('6')").find('input').val(Math.round(RemainingStock));
                // TotalSaleQty();
            }

            function changeSaleQtyEdit(row) {
                var sale_qty = $(row).find("td:eq('9')").find('input').val();
                var stock = $(row).find("td:eq('8')").find('input').val();
                var Current = $(row).find("td:eq('5')").find('input').val();

                if(Current <= 0 ){
                e.preventdefault();
            }
            // alert(Current)              
            var RemainingStock = parseInt(stock)+parseInt(sale_qty)-parseInt(Current);

                // var RemainingStock = stock-sale_qty;
                $(row).find("td:eq('6')").find('input').val(Math.round(RemainingStock));
                // TotalSaleQty();
                TotalDemandQty();
                TotalQty();
            
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

            // var pro_id = document.getElementById('product_id1').value.split('_')[0];
            // var pro_code = document.getElementById('product_id1').value.split('_')[1];
            // var pro_name = document.getElementById('product_id1').value.split('_')[2];
            // var pro_unit = document.getElementById('product_id1').value.split('_')[3];
            // var price = parseInt(document.getElementById('price1').value);
            // var qty = parseInt(document.getElementById('qty1').value);
            // var total = parseInt(document.getElementById('total1').value);
            // var comment = document.getElementById('comment1').value;
            // var TotalPrice = parseInt(document.getElementById('TotalPrice').innerHTML);
            // var TotalQty = parseInt(document.getElementById('TotalQty').innerHTML);
            // var TotalAmount = parseInt(document.getElementById('TotalAmount').innerHTML);
            // var grandTotalQty = TotalQty + qty;
            // var grandTotalPrice = TotalPrice + price;
            // var grandTotalAmount = TotalAmount + total;


            // var tableHtml = `<tr>`;
            // tableHtml += `<td>${pro_code}<input type='hidden' name='code[]' id='code' value='${pro_code}' /></td>`;
            // tableHtml += `<td>
            //                 ${pro_name}
            //                 <input type='hidden' name='product_id[]' id='product_id' value='${pro_id}' />
            //                 <input type='hidden' name='product_name[]' id='product_name' value='${pro_name}' />
            //             </td>`;
            // tableHtml += `<td>${pro_unit}<input type='hidden' name='unit[]' id='unit' value='${pro_unit}' /></td>`;
            // tableHtml +=
            //     `<td>${price}<input type='hidden' name='price[]' id='price' value='${price}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
            // tableHtml +=
            //     `<td>${qty}<input type='hidden' name='qty[]' id='qty' value='${qty}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
            // tableHtml +=
            //     `<td>${total}<input type='hidden' name='total[]' id='total' value='${total}' class='form-control' onkeyup="changeQty($(this).closest('tr'));" /></td>`;
            // tableHtml += `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
            // tableHtml +=
            //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
            // tableHtml += `</tr>`;

            // $('#GridTable').append(tableHtml);
            // $('#code1').val(null);
            // $('#product_id1').val(null).select2('open');

            // $('#unit1').val(null);
            // $('#unit2').val(null);
            // $('#price1').val(null);
            // $('#qty1').val(null);
            // $('#total1').val(null);
            // $('#comment1').val(null);

            // $('#TotalQty').html(grandTotalQty);
            // $('#TotalPrice').html(grandTotalPrice);
            // $('#TotalAmount').html(grandTotalAmount);
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

                    var vehicle_no = $('#vehicle_no').val();
                    var transport_company = $('#transport_company').val();
                    var driver_name = $('#driver_name').val();
                    var builty_no = $('#builty_no').val();
                    var driver_phoneno = $('#driver_phoneno').val();

                    if(!vehicle_no)
                    {
                        $('.vehicle_no_err').text('The vehicle no field is required.');
                        return false;
                    }else
                    if(!transport_company)
                    {
                        $('.transport_company_err').text('The Transport field is required.');
                        return false;
                    }else
                    if(!driver_name)
                    {
                        $('.driver_name_err').text('The Driver Name field is required.');
                        return false;
                    }else
                    if(!builty_no)
                    {
                        $('.builty_no_err').text('The Builty no field is required.');
                        return false;
                    }else
                    if(!driver_phoneno)
                    {
                        $('.driver_phoneno_err').text('The Driver Phone field is required.');
                        return false;
                    }
                    else{
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
                            $('#inward-gatepass-form').submit();
                            $('.submit-form').attr('disabled', true);
                        }else{
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> The Qty field is required!</div>'
                            );
                        }
                    }

                        
                            
            });
            // End FOrm Submit

            // Reset btn feature
            $('.reset-btn').click(function() {
                $('#supplier_id').val(null).select2();
                $('#purchaser_id').val(null).select2();
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
        
      

        function DeleteRow(row) {
            // var TotalQty = parseInt(document.getElementById('TotalQty').innerText);
            // var NewQty = parseInt($(row).find("td:eq('3')").find('input').val());
            // document.getElementById('TotalQty').innerText = (TotalQty - NewQty);
            
            $(row).remove();
            TotalDemandQty();
            TotalQty();
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
                // var balance = $('#balance').val();
                // alert(balance);
                // $('#inward-gatepass-form').submit();
                $('#delete_bill_no').val(voucher_no);
                // $('#balance_del').val(balance);
            });
            // Print Record
            $('.print_record_btn').click(function() {
                    var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                    myModal.toggle();

                    var bill_no = parseInt($('#bill_no').val());
                    var base_url = $('#base_url').val();
                    $.ajax({
                        url: "{{ URL::to('inward-gatepass/print/voucher') }}?bill_no=" +
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
                                    `<object data="${base_url}/resources/upload/inward-gatepass/${response}" type="application/pdf" width="100%" height="800"></object>`
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
            ///load request generate recode
            $('#req_gen_ids').change(function() {
                var req_gen_id = parseInt($(this).val());
                 //alert(req_gen_id);
                $.ajax({
                    url: "{{ URL::to('inward-gatepass/requestgenerate/record') }}?req_gen_id=" + req_gen_id,
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

                            $.each(response.data, function(i, v) {
                                var comment = '';
                                if (v.comments != null) {
                                    comment = v.comments;
                                }

                                totalQty += parseInt(v.qty);

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.product.code}
                                    <input type='hidden' name='request_detail_id[]' id='request_detail_id' value='${v.id}' />
                                    <input type='hidden' name='code[]' id='code' value='${v.product.code}' /></td>`;
                                tableHtml += `<td>${v.product.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' />
                                                    <input type='hidden' name='product_name[]' id='product_name' value='${v.product.product_name}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='unit[]' id='unit' value='${v.product.uom}' /></td>`;
                               
                                tableHtml +=
                                    `<td>${Number(v.qty).toLocaleString('en-US')}<input type='hidden' name='qtyforshow[]' id='qtyforshow' value='${v.qty}' class='form-control'/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='' class='form-control' onkeyup="changeSaleQty($(this).closest('tr'));", 
                                        onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));"  style="width:120px;"/></td>`;
                                    var total =0;
                                    $.each(v.gatepass_details, function( index, value ) {
                                        total = parseInt(total) + parseInt(value.qty);
                                    });
                                        var stock = v.qty - total;
                                   
                                    tableHtml +=
                                    `<td><input type='text' name='balance[]' id='balance' value='${Number(stock).toLocaleString('en-US')}' class="form-control" readonly/></td>`;
                                    tableHtml +=
                                    `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
                                    tableHtml +=
                                        `<td style="display:none;"><input type='text' name='balanceForCalculation[]' id='balanceForCalculation' value='${stock}' class="form-control" readonly/></td>`;
                                        tableHtml +=
                                        `<td style="display: none;">${v.id}</td>`;
                                    // tableHtml +=
                                //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(totalQty.toLocaleString('en-US'));
                            $('#updated_by_name').removeClass('d-none');
                            // $('#supplier_id').val(response.data[0].request_generate.supplier_id)
                            //     .select2();
                            // $('#purchaser_id').val(response.data[0].request_generate.purchaser_id)
                            //     .select2();

                            $('#supplier_id').val(response.data[0].request_generate.supplier_id);
                            $('#supplier_name').val(response.data[0].request_generate.supplier.party_name);
                            $('#purchaser_id').val(response.data[0].request_generate.purchaser_id);
                            $('#purchaser_name').val(response.data[0].request_generate.purchaser.party_name);

                            $('#warehouse_id').val(response.data[0].request_generate.warehouse_id)
                                .select2();
                            $('#Reqdate').val(response.data[0].request_generate.date); 
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#TotalPrice').text(0);
                            $('#TotalAmount').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_bill_no').val(null);
                            let date = new Date()
                            $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                .getMonth()) + 1) + '-' + date.getDate());
                            $('#vehicle_no').val(null);
                            $('#transport_company').val(null);
                            $('#driver_name').val(null);
                            $('#builty_no').val(null);
                            $('#driver_phoneno').val(null);

                            $('#supplier_id').val(0).select2();
                            $('#purchaser_id').val(null).select2();
                            $('#bill_no').focus();
                        }
                    }
                });
            });

            $('.load-edit-record').click(function() {
                var bill_no = parseInt($('#bill_no').val());
                $.ajax({
                    url: "{{ URL::to('inward-gatepass/load/record') }}?bill_no=" + bill_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        $('.ReloadOrder').removeClass('d-none');
                        if (response.data != '') {
                            var tableHtml = '';
                            // var totalQty = 0;


                            $.each(response.data, function(i, v) {
                                var comment = '';
                                if (v.comments != null) {
                                    comment = v.comments;
                                }

                                // totalQty += parseInt(v.qtyshow);
                                

                                tableHtml += `<tr>`;
                                tableHtml += `<td>${v.request_no}
                                                    <input type='hidden' name='request_no[]' id='request_no' value='${v.request_no}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.product_code}
                                    
                                    <input type='hidden' name='request_detail_id[]' id='request_detail_id' value='${v.request_detail_id}' />
                                    <input type='hidden' name='code[]' id='code' value='${v.product_code}' /></td>`;
                                tableHtml += `<td>${v.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product_id}' />
                                                    <input type='hidden' name='product_name[]' id='product_name' value='${v.product_name}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.unit}<input type='hidden' name='unit[]' id='unit' value='${v.unit}' /></td>`;
                               
                                tableHtml +=
                                    `<td>${Number(v.qtyshow).toLocaleString('en-US')}<input type='hidden' name='qtyforshow[]' id='qtyforshow' value='${v.qtyshow}' class='form-control'/></td>`;
                                    tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeSaleQtyEdit($(this).closest('tr'));", 
                                        onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));"  style="width:120px;"/></td>`;
                                    var total =0;
                                    // alert("d");
                                    $.each(v.product.gatepass_details, function( index, value ) {
                                        total = parseInt(total) + parseInt(value.qty);
                                        // alert(value.qty);
                                    });
                                        var stock = v.qtyshow - total;
                                        // alert(stock);

                                        tableHtml +=
                                    `<td><input type='text' name='balance[]' id='balance' value='${Number(stock).toLocaleString('en-US')}' class="form-control" readonly/></td>`;
                                    tableHtml +=
                                    `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
                                    tableHtml +=
                                        `<td style="display:none;"><input type='text' name='balanceForCalculation[]' id='balanceForCalculation' value='${stock}' class="form-control" readonly/></td>`;
                                    // tableHtml +=
                                    // `<td style="display:none;"><input type='text' name='DispatchQtyForFormula' id='DispatchQtyForFormula' value='${v.sale_qty}' class="form-control" readonly/></td>`;
                                    tableHtml +=
                                        `<td style="display:none;"><input type='text' name='DispatchQtyForFormula' id='DispatchQtyForFormula' value='${v.qty}' class="form-control" readonly/></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            // $('#TotalQty').text(totalQty.toLocaleString('en-US'));
                            TotalDemandQty();
                            TotalQty();
                            $('.SelectRequest').addClass('d-none');
                            // $('.SelectRequest').addClass('d-none');
                            $('#updated_by_name').removeClass('d-none');
                            $('.req_gen_no2 input').val(response.data[0].inward.request_generate.bill_no);
                            // $('.req_gen_no2').removeClass('d-none');
                            $('.req_gen_no1').addClass('d-none');
                            $('#update_bill_no').val(response.data[0].inward.bill_no);
                            $('#date').val(response.data[0].inward.date);
                            $('#vehicle_no').val(response.data[0].inward.vehicle_no);
                            $('#transport_company').val(response.data[0].inward
                                .transport_company);
                            $('#driver_name').val(response.data[0].inward.driver_name);
                            $('#builty_no').val(response.data[0].inward.builty_no);
                            $('#Reqdate').val(response.data[0].inward.request_generate.date);
                            // $('#supplier_id').val(response.data[0].inward.supplier_id)
                            //     .select2();
                            // $('#purchaser_id').val(response.data[0].inward.purchaser_id)
                            //     .select2();
                            
                                $('#supplier_id').val(response.data[0].inward.supplier_id);
                            $('#supplier_name').val(response.data[0].inward.supplier.party_name);
                            $('#purchaser_id').val(response.data[0].inward.purchaser_id);
                            $('#purchaser_name').val(response.data[0].inward.purchaser.party_name);

                            $('#driver_phoneno').val(response.data[0].inward.driver_phoneno);
                            $('#warehouse_id').val(response.data[0].inward.warehouse_id).select2();
                            // $('#req_gen_id').val(response.data[0].inward.req_gen_id).select2();
                            $('#req_gen_id_edit').val(response.data[0].inward.req_gen_id);
                           
                            $('#bill_no').focus();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );

                            $('#GridTable').html(null);
                            $('#TotalQty').text(0);
                            $('#updated_by_name').addClass('d-none');

                            $('#update_bill_no').val(null);
                            let date = new Date()
                            $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                .getMonth()) + 1) + '-' + date.getDate());
                            $('#vehicle_no').val(null);
                            $('#transport_company').val(null);
                            $('#driver_name').val(null);
                            $('#builty_no').val(null);
                            $('#driver_phoneno').val(null);

                            $('#supplier_id').val(0).select2();
                            $('#purchaser_id').val(null).select2();
                            $('#req_gen_id').val(null).select2();
                            $('#bill_no').focus();
                        }
                    }
                });
            });
            // Load Next Record
            $('.load-next-record').click(function() {
                var bill_no = parseInt($('#bill_no').val());
                $.ajax({
                    url: "{{ URL::to('inward-gatepass/load/next/record') }}?bill_no=" + bill_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        $('.ReloadOrder').removeClass('d-none');
                        if (response.data != '') {
                            var tableHtml = '';
                            // var totalQty = 0;
                            $.each(response.data, function(i, v) {
                                var comment = '';
                                if (v.comments != null) {
                                    comment = v.comments;
                                }
                                // totalQty += parseInt(v.qtyshow);
                                tableHtml += `<tr>`;
                                tableHtml += `<td>${v.request_no}<input type='hidden' name='request_no[]' id='request_no' value='${v.request_no}' /></td>`;
                                tableHtml +=
                                    `<td>${v.product_code}<input type='hidden' name='request_detail_id[]' id='request_detail_id' value='${v.request_detail_id}' />
                                    <input type='hidden' name='code[]' id='code' value='${v.product_code}' /></td>`;
                                tableHtml += `<td>${v.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product_id}' />
                                                    <input type='hidden' name='product_name[]' id='product_name' value='${v.product_name}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.unit}<input type='hidden' name='unit[]' id='unit' value='${v.unit}' /></td>`;
                                tableHtml +=
                                    `<td>${Number(v.qtyshow).toLocaleString('en-US')}<input type='hidden' name='qtyforshow[]' id='qtyforshow' value='${v.qtyshow}' class='form-control'/></td>`;
                                    tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeSaleQtyEdit($(this).closest('tr'));", 
                                        onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));"  style="width:120px;"/></td>`;
                                    var total =0;
                                    // alert("d");
                                    $.each(v.product.gatepass_details, function( index, value ) {
                                        total = parseInt(total) + parseInt(value.qty);
                                        // alert(value.qty);
                                    });
                                        var stock = v.qtyshow - total;
                                        // alert(stock);
                                        tableHtml +=
                                    `<td><input type='text' name='balance[]' id='balance' value='${Number(stock).toLocaleString('en-US')}' class="form-control" readonly/></td>`;
                                    tableHtml +=
                                    `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
                                    tableHtml +=
                                        `<td style="display:none;"><input type='text' name='balanceForCalculation[]' id='balanceForCalculation' value='${stock}' class="form-control" readonly/></td>`;
                                    // tableHtml +=
                                    // `<td style="display:none;"><input type='text' name='DispatchQtyForFormula' id='DispatchQtyForFormula' value='${v.sale_qty}' class="form-control" readonly/></td>`;
                                    tableHtml +=
                                        `<td style="display:none;"><input type='text' name='DispatchQtyForFormula' id='DispatchQtyForFormula' value='${v.qty}' class="form-control" readonly/></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            // $('#TotalQty').text(totalQty.toLocaleString('en-US'));
                            TotalDemandQty();
                            TotalQty();
                            $('.SelectRequest').addClass('d-none');
                            $('#updated_by_name').removeClass('d-none');
                            $('.req_gen_no2 input').val(response.data[0].inward.request_generate.bill_no);
                            // $('.req_gen_no2').removeClass('d-none');
                            $('.req_gen_no1').addClass('d-none');
                            $('#update_bill_no').val(response.data[0].inward.bill_no);
                            $('#date').val(response.data[0].inward.date);
                            $('#vehicle_no').val(response.data[0].inward.vehicle_no);
                            $('#transport_company').val(response.data[0].inward
                                .transport_company);
                            $('#driver_name').val(response.data[0].inward.driver_name);
                            $('#builty_no').val(response.data[0].inward.builty_no);
                            $('#Reqdate').val(response.data[0].inward.request_generate.date);
                            // $('#supplier_id').val(response.data[0].inward.supplier_id)
                            //     .select2();
                            // $('#purchaser_id').val(response.data[0].inward.purchaser_id)
                            //     .select2();

                            $('#supplier_id').val(response.data[0].inward.supplier_id);
                            $('#supplier_name').val(response.data[0].inward.supplier.party_name);
                            $('#purchaser_id').val(response.data[0].inward.purchaser_id);
                            $('#purchaser_name').val(response.data[0].inward.purchaser.party_name);

                            $('#driver_phoneno').val(response.data[0].inward.driver_phoneno);
                            $('#warehouse_id').val(response.data[0].inward.warehouse_id).select2();
                            // $('#req_gen_id').val(response.data[0].inward.req_gen_id).select2();
                            $('#req_gen_id_edit').val(response.data[0].inward.req_gen_id);
                            $('#bill_no').val(response.data[0].inward.bill_no);
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

                            $('#update_bill_no').val(null);
                            let date = new Date()
                            $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                .getMonth()) + 1) + '-' + date.getDate());
                            $('#vehicle_no').val(null);
                            $('#transport_company').val(null);
                            $('#driver_name').val(null);
                            $('#builty_no').val(null);
                            $('#driver_phoneno').val(null);

                            $('#supplier_id').val(0).select2();
                            $('#purchaser_id').val(null).select2();
                            $('#req_gen_id').val(null).select2();
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
                    url: "{{ URL::to('inward-gatepass/load/previous/record') }}?bill_no=" +
                        bill_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        $('.ReloadOrder').removeClass('d-none');
                            $('.req_gen_no2 input').val(response.data[0].inward.request_generate.bill_no + '-' + response.data[0].inward.request_generate.warehouse_name);
                            // $('.req_gen_no2').removeClass('d-none');
                            $('.SelectRequest').addClass('d-none');
                            $('.req_gen_no1').addClass('d-none');
                            $('#update_bill_no').val(response.data[0].inward.bill_no);
                            $('#date').val(response.data[0].inward.date);
                            $('#vehicle_no').val(response.data[0].inward.vehicle_no);
                            $('#transport_company').val(response.data[0].inward
                                .transport_company);
                            $('#driver_name').val(response.data[0].inward.driver_name);
                            $('#builty_no').val(response.data[0].inward.builty_no);
                            $('#Reqdate').val(response.data[0].inward.request_generate.date);
                            // $('#supplier_id').val(response.data[0].inward.supplier_id)
                            //     .select2();
                            // $('#purchaser_id').val(response.data[0].inward.purchaser_id)
                            //     .select2();


                            $('#supplier_id').val(response.data[0].inward.supplier_id);
                            $('#supplier_name').val(response.data[0].inward.supplier.party_name);
                            $('#purchaser_id').val(response.data[0].inward.purchaser_id);
                            $('#purchaser_name').val(response.data[0].inward.purchaser.party_name);

                            $('#driver_phoneno').val(response.data[0].inward.driver_phoneno);
                            $('#warehouse_id').val(response.data[0].inward.warehouse_id).select2();
                            // $('#req_gen_id').val(response.data[0].inward.req_gen_id).select2();
                            $('#req_gen_id_edit').val(response.data[0].inward.req_gen_id);

                            // $('#req_gen_id').val(response.data[0].inward.id +
                            //         '_' +
                            //         response.data[0].inward.id + '_' +
                            //         response
                            //         .data[0].inward.id).select2();
                           
                            $('#bill_no').val(response.data[0].inward.bill_no);
                            $('#bill_no').focus();
                        if (response.data != '') {
                            var tableHtml = '';
                            // var totalQty = 0;
                            $.each(response.data, function(i, v) {
                                var comment = '';
                                if (v.comments != null) {
                                    comment = v.comments;
                                }

                                // totalQty += parseFloat(v.qtyshow);


                                tableHtml += `<tr>`;
                                tableHtml += `<td>${v.request_no}
                                                    <input type='hidden' name='request_no[]' id='request_no' value='${v.request_no}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.product_code}
                                    <input type='hidden' name='request_detail_id[]' id='request_detail_id' value='${v.request_detail_id}' />
                                    <input type='hidden' name='code[]' id='code' value='${v.product_code}' /></td>`;
                                tableHtml += `<td>${v.product_name}
                                                    <input type='hidden' name='product_id[]' id='product_id' value='${v.product_id}' />
                                                    <input type='hidden' name='product_name[]' id='product_name' value='${v.product_name}' />
                                                </td>`;
                                tableHtml +=
                                    `<td>${v.unit}<input type='hidden' name='unit[]' id='unit' value='${v.unit}' /></td>`;
                               
                                tableHtml +=
                                    `<td>${Number(v.qtyshow).toLocaleString('en-US')}<input type='hidden' name='qtyforshow[]' id='qtyforshow' value='${v.qtyshow}' class='form-control'/></td>`;
                                tableHtml +=
                                    `<td style="width:120px;"><input type='text' name='qty[]' id='qty' value='${v.qty}' class='form-control' onkeyup="changeSaleQtyEdit($(this).closest('tr'));", 
                                        onkeypress="return onlyNumberKey(event)", onkeydown="EnterKeyBoard($(this).closest('tr'));"  style="width:120px;"/></td>`;
                                    var total =0;
                                    // alert("d");
                                    $.each(v.product.gatepass_details, function( index, value ) {
                                        total = parseInt(total) + parseInt(value.qty);
                                        // alert(value.qty);
                                    });
                                        var stock = v.qtyshow - total;
                                        // alert(stock);

                                        tableHtml +=
                                    `<td><input type='text' name='balance[]' id='balance' value='${Number(stock).toLocaleString('en-US')}' class="form-control" readonly/></td>`;
                                    tableHtml +=
                                    `<td>${comment}<input type='hidden' name='comments[]' id='comments' value='${comment}' /></td>`;
                                    tableHtml +=
                                        `<td style="display:none;"><input type='text' name='balanceForCalculation[]' id='balanceForCalculation' value='${stock}' class="form-control" readonly/></td>`;
                                    // tableHtml +=
                                    // `<td style="display:none;"><input type='text' name='DispatchQtyForFormula' id='DispatchQtyForFormula' value='${v.sale_qty}' class="form-control" readonly/></td>`;
                                    tableHtml +=
                                        `<td style="display:none;"><input type='text' name='DispatchQtyForFormula' id='DispatchQtyForFormula' value='${v.qty}' class="form-control" readonly/></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            // $('#TotalQty').text(Number(totalQty).toLocaleString('en-US'));
                            TotalDemandQty();
                            TotalQty();
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

                            $('#update_bill_no').val(null);
                            let date = new Date()
                            $('#date').val(date.getFullYear() + '-' + (parseInt(date
                                .getMonth()) + 1) + '-' + date.getDate());
                            $('#vehicle_no').val(null);
                            $('#transport_company').val(null);
                            $('#driver_name').val(null);
                            $('#builty_no').val(null);
                            $('#driver_phoneno').val(null);

                            $('#supplier_id').val(0).select2();
                            $('#purchaser_id').val(null).select2();
                            $('#req_gen_id').val(null).select2();
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