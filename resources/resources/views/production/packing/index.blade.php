@extends('app')
@section('head')
    <title>Packing Production</title>
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
            <h1>Packing Production</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Packing Production</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>Packing Production</h6>
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
                                    @include('errors.validation')
                                    {!! Form::open(['url' => 'packing-production', 'class' => 'form-horizontal', 'id' => 'opening-stock-form']) !!}
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
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>Voucher#<span class="text-danger">*</span></label>
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
                                        <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                            <label for="warehouse_id"><i class="fa fa-caret-right"></i>Select Thermoforming Batch#<span
                                                    class="text-danger">*</span></label>
                                                    {!! Form::select('thermo_production_id', $ThermoProduction, null, [
                                                'id' => 'thermo_production_id',
                                                'class' => 'form-control select2',
                                            ]) !!}
                                            @error('warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i>Product Name<span
                                                    class="text-danger">*</span></label>
                                                    {!! Form::text('product_name', null, [
                                                'id' => 'product_name',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-1 col-md-4 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i>Dye Pcs<span
                                                    class="text-danger">*</span></label>
                                                    {!! Form::text('dye_pcs', null, [
                                                'id' => 'dye_pcs',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="date"><i class="fa fa-caret-right"></i>Shift</label>
                                            {!! Form::text('shift', null, [
                                                'id' => 'shift',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>Operator<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('operator', null, [
                                                'id' => 'operator',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="warehouse_id"><i class="fa fa-caret-right"></i>Machine<span
                                                    class="text-danger">*</span></label>
                                                    {!! Form::text('machine', null, [
                                                'id' => 'machine',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                            @error('warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i>Press Man<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('press_man', null, [
                                                'id' => 'press_man',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i>Press No<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('press_no', null, [
                                                'id' => 'press_no',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i>Total Sheets<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('total_sheets', null, [
                                                'id' => 'total_sheets',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="date"><i class="fa fa-caret-right"></i>Check Sheets</label>
                                            {!! Form::text('check_sheets', null, [
                                                'id' => 'check_sheets',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>Wastage<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('wastage', null, [
                                                'id' => 'wastage',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>Net Sheets<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('net_sheets', null, [
                                                'id' => 'net_sheets',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>Net SKU<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('net_sku', null, [
                                                'id' => 'net_sku',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i>Remarks<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('remarks', null, [
                                                'id' => 'remarks',
                                                'class' => 'form-control'
                                            ]) !!}
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                    <!-----------------------Add Product------------------------------------>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                            <label for="warehouse_id"><i class="fa fa-caret-right"></i>Select Product<span
                                                    class="text-danger">*</span></label>
                                                    {!! Form::select('product_id', $product, null, [
                                                'id' => 'product_id',
                                                'class' => 'form-control select2',
                                            ]) !!}
                                            {!! Form::hidden('prod_id', 0, [
                                                'id' => 'prod_id',
                                                'class' => 'form-control',
                                            ]) !!}
                                            
                                            @error('product_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i>Packing<span
                                                    class="text-danger">*</span></label>
                                                    {!! Form::text('packing', null, [
                                                'id' => 'packing',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i>Balance<span
                                                    class="text-danger">*</span></label>
                                                    {!! Form::text('balance', null, [
                                                'id' => 'balance',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                            {!! Form::hidden('balance_for_formula', null, [
                                                'id' => 'balance_for_formula',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
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
                                                            <th>Packing Employee</th>
                                                            <th>Quantity</th>
                                                            <th>Pieces</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="bg-secondary">

                                                            <td style="width: 40%;">
                                                                {!! Form::select('employee_id1', $packing, null, [
                                                                    'id' => 'employee_id1',
                                                                    'class' => 'form-control select2',
                                                                    'tabindex' => '8',
                                                                ]) !!}
                                                                <span class="product_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('qty1', null, [
                                                                    'id' => 'qty1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Qty',
                                                                    'onkeyup' => 'qtychange($(this).val())',
                                                                    'onkeypress' => "return isNumberKeyNoPoint(event)",
                                                                ]) !!}
                                                                <span class="color1_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('pcs1', null, [
                                                                    'id' => 'pcs1',
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Qty',
                                                                    'readonly' => "readonly",
                                                                ]) !!}
                                                                <span class="color1_err text-danger"></span>
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
                                                                <th style="width: 30%;">Employee Name</th>
                                                                <th>Qty</th>
                                                                <th>Pcs</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2"><strong>Total</strong></td>
                                                                <td class="bg-success" id="TotalQty">0</td>
                                                                <td class="bg-success" id="TotalPcs">0</td>
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
                <form action="{{ URL::to('packing-production/delete-voucher') }}" method="post" id="delete_voucher_form">
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
                    $("#thermo_production_id").select2('open');
                }
            });
            $('#thermo_production_id').change(function(event) {
                var warehouse_id = $(this).val();
                if (warehouse_id) {
                    $('#thermo_production_id').select2().trigger('select2:close');
                    $('#remarks').focus();
                }
            });

            $('#remarks').keydown(function(event) {
                // alert("ddd")
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#product_id").select2('open');
                }
            });
            $('#product_id').change(function(event) {      
                var product_id = $(this).val();
                if (product_id != null) {
                    $('#prod_id').val(product_id.split('_')[0]);
                    $('#packing').val(product_id.split('_')[2]);
                    $('#product_id').select2().trigger('select2:close');
                    $("#employee_id1").select2('open');
                }
            });

            $('#employee_id1').change(function(event) {      
                var product_id = $(this).val();
                if (product_id != null) {
                    $('#employee_id1').select2().trigger('select2:close');
                    $('#qty1').focus();
                }
            });

            $('#qty1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // $('#weight').focus();
                    $("#employee_id1").select2('open');
                        AddGridData();
                    
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

        function qtychange(qty){
            // alert("Dd")
            var packing = document.getElementById('packing').value; //4000
            var pieces = packing * qty;
            document.getElementById('pcs1').value = pieces;

            // var NetSku = document.getElementById('net_sku').value;
            var NetSku = document.getElementById('balance_for_formula').value; //37620.00
            var pcs = document.getElementById('pcs1').value; // 4000
            var TotalPieces = parseInt(document.getElementById('TotalPcs').innerHTML);
            var balance = NetSku - TotalPieces - pcs;
            document.getElementById('balance').value = balance;
            //  alert(TotalPieces)
        }

        function AddGridData() {
            
            // var employeeID = $('#employee_id1').val();
            var employeeID = document.getElementById('employee_id1').value.split('_')[0];
            var employeeName = document.getElementById('employee_id1').value.split('_')[1];
            var employeeCode = document.getElementById('employee_id1').value.split('_')[2];
            var qty = document.getElementById('qty1').value;
            var pcs = document.getElementById('pcs1').value;
            // alert(employeeName)
            var TotalQuantity = parseInt(document.getElementById('TotalQty').innerHTML);
            var TotalPieces = parseInt(document.getElementById('TotalPcs').innerHTML);
            var grandquantity = parseFloat(TotalQuantity) + parseFloat(qty);
            var grandpieces = parseFloat(TotalPieces) + parseFloat(pcs);
            var totalRowCount = GridTable.rows.length;
            // alert(totalRowCount)
            var tableHtml = `<tr>`;
            tableHtml +=
                `<td>${totalRowCount+1}<input type='hidden' name='employee_id[]' id='employee_id' value='${employeeID}' /></td>`;
            tableHtml += `<td>${employeeCode} - ${employeeName}</td>`;
            tableHtml += `<td>${qty}
                <input type='hidden' name='qty[]' id='qty' value='${qty}' /></td>`;
            tableHtml += `<td>${pcs}
                <input type='hidden' name='pcs[]' id='pcs' value='${pcs}' /></td>`;
            tableHtml +=
                `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
            tableHtml += `</tr>`;
            $('#GridTable').append(tableHtml);
            // $('#thickness2').val(null);
            // $('#width2').val(null);
            $('#employee_id1').val(null);
            $('#qty1').val(null);
            $('#pcs1').val(null);
            // $('#packing2').val(null);
            // $('#weight2').val(null);
            // $('#TotalQty').html(grandTotalQty.toLocaleString('en-US'));
            $('#TotalQty').html(grandquantity);
            $('#TotalPcs').html(grandpieces);
            // $('#TotalNet').html(grandNetWeight.toLocaleString('en-US'));

            $('#product_id1').select2('open');
            // $('#product_id2').val(null);
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
            var TotalQty = parseInt(document.getElementById('TotalQty').innerText);
            var NewQty = parseInt($(row).find("td:eq('2')").find('input').val());
            document.getElementById('TotalQty').innerText = (TotalQty - NewQty);

            var TotalPcs = parseInt(document.getElementById('TotalPcs').innerText);
            var NewPcs = parseInt($(row).find("td:eq('3')").find('input').val());
            document.getElementById('TotalPcs').innerText = (TotalPcs - NewPcs);

            // var balance = document.getElementById('balance').value
            // var total = parseFloat(balance) + parseFloat(NewAmount);
            // document.getElementById('balance').value = total;

            // var TotalQty = parseInt(document.getElementById('TotalNet').innerText);
            // var NewQty = parseInt($(row).find("td:eq('7')").find('input').val());
            // document.getElementById('TotalNet').innerText = (TotalQty - NewQty);

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
                    url: "{{ URL::to('packing-production/print/voucher') }}?voucher_no=" +
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
                                `<object data="${base_url}/resources/upload/production/packing/${response}" type="application/pdf" width="100%" height="800"></object>`
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

            $('#thermo_production_id').change(function() {
            var product_id = parseInt($('#thermo_production_id').val());
            // alert(product_id)
            //  alert("ddd")
                    $.ajax({
                        url: "{{ URL::to('packing-production/load-thermoforming-production') }}",
                        type: 'get',
                        data: {
                        product_id: product_id,
                        },
                        success: function(response) {
                            if (response != null && response != 0) {
                               
                               $('#product_name').val(response.data.product.product_name);
                               $('#dye_pcs').val(response.data.product.dye_pcs);
                               $('#shift').val(response.data.shift.shift_name);
                               $('#operator').val(response.data.operator.party_name);
                               $('#machine').val(response.data.machine.machine_name);
                               $('#press_man').val(response.data.pressman.party_name);
                               $('#press_no').val(response.data.pressman_no);
                               $('#total_sheets').val(response.data.total_sheets);
                               $('#check_sheets').val(response.data.check_sheets);
                               $('#wastage').val(response.data.wastage);
                               $('#net_sheets').val(response.data.net_sheets);
                               $('#net_sku').val(response.data.net_sku);
                               $('#balance').val(response.data.packing_balance);
                               $('#balance_for_formula').val(response.data.packing_balance);
                            } else {
                                
                            }
                        }
                    });
                });


            $('.load-edit-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('packing-production/load/record') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {          
                        if (response.production.production_details != '') {
                            var tableHtml = '';
                            var sum = 0;
                            var TotalQty = 0;
                            var TotalPcs = 0;
                            $.each(response.production.packing_production, function(i, v) {
                                sum += 1;
                                TotalQty += parseFloat(v.qty);
                                TotalPcs += parseFloat(v.pcs);
                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${sum}<input type='hidden' name='employee_id[]' id='employee_id' value='${v.employee.id}' /></td>`;
                               tableHtml +=
                                    `<td>${v.employee.party_name}</td>`;
                                    tableHtml +=
                                    `<td>${v.qty}<input type='hidden' name='qty[]' id='qty' value='${v.qty}' /></td>`;
                                    tableHtml +=
                                    `<td>${v.pcs}<input type='hidden' name='pcs[]' id='pcs' value='${v.pcs}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(TotalQty);
                            $('#TotalPcs').text(TotalPcs);
                             $('#update_voucher_id').val(response.production.id);
                             $('#date').val(response.production.date);
                            $('#voucher_no').val(response.production.voucher_no);
                            $('#product_name').val(response.production.thermoforming_production.product.product_name);
                            $('#dye_pcs').val(response.production.thermoforming_production.product.dye_pcs);
                            $('#shift').val(response.production.thermoforming_production.shift.shift_name);
                            $('#operator').val(response.production.thermoforming_production.operator.party_name);
                            $('#machine').val(response.production.thermoforming_production.machine.machine_name);
                            $('#press_man').val(response.production.thermoforming_production.pressman.party_name);
                            $('#press_no').val(response.production.thermoforming_production.pressman_no);
                            $('#total_sheets').val(response.production.thermoforming_production.total_sheets);
                            $('#check_sheets').val(response.production.thermoforming_production.check_sheets);
                            $('#wastage').val(response.production.thermoforming_production.wastage);
                            $('#net_sheets').val(response.production.thermoforming_production.net_sheets);
                            $('#net_sku').val(response.production.thermoforming_production.net_sku);
                            $('#remarks').val(response.production.remarks);
                            $('#product_id').val(response.production.product.id + '_' + response
                                .production.product.product_name + '_' + response
                                .production.product.packing).select2();
                            $('#prod_id').val(response.production.product.id);
                            $('#packing').val(response.production.product.packing);
                            $('#thermo_production_id').val(response.production.thermoforming_production.id)
                                .select2();
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
                    url: "{{ URL::to('packing-production/load/next/record') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {          
                        if (response.production.production_details != '') {
                            var tableHtml = '';
                            var sum = 0;
                            var TotalQty = 0;
                            var TotalPcs = 0;
                            $.each(response.production.packing_production, function(i, v) {
                                sum += 1;
                                TotalQty += parseFloat(v.qty);
                                TotalPcs += parseFloat(v.pcs);
                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${sum}<input type='hidden' name='employee_id[]' id='employee_id' value='${v.employee.id}' /></td>`;
                               tableHtml +=
                                    `<td>${v.employee.party_name}</td>`;
                                    tableHtml +=
                                    `<td>${v.qty}<input type='hidden' name='qty[]' id='qty' value='${v.qty}' /></td>`;
                                    tableHtml +=
                                    `<td>${v.pcs}<input type='hidden' name='pcs[]' id='pcs' value='${v.pcs}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(TotalQty);
                            $('#TotalPcs').text(TotalPcs);
                             $('#update_voucher_id').val(response.production.id);
                             $('#date').val(response.production.date);
                            $('#voucher_no').val(response.production.voucher_no);
                            $('#product_name').val(response.production.thermoforming_production.product.product_name);
                            $('#dye_pcs').val(response.production.thermoforming_production.product.dye_pcs);
                            $('#shift').val(response.production.thermoforming_production.shift.shift_name);
                            $('#operator').val(response.production.thermoforming_production.operator.party_name);
                            $('#machine').val(response.production.thermoforming_production.machine.machine_name);
                            $('#press_man').val(response.production.thermoforming_production.pressman.party_name);
                            $('#press_no').val(response.production.thermoforming_production.pressman_no);
                            $('#total_sheets').val(response.production.thermoforming_production.total_sheets);
                            $('#check_sheets').val(response.production.thermoforming_production.check_sheets);
                            $('#wastage').val(response.production.thermoforming_production.wastage);
                            $('#net_sheets').val(response.production.thermoforming_production.net_sheets);
                            $('#net_sku').val(response.production.thermoforming_production.net_sku);
                            $('#remarks').val(response.production.remarks);
                            $('#product_id').val(response.production.product.id + '_' + response
                                .production.product.product_name + '_' + response
                                .production.product.packing).select2();
                            $('#prod_id').val(response.production.product.id);
                            $('#packing').val(response.production.product.packing);
                            $('#thermo_production_id').val(response.production.thermoforming_production.id)
                                .select2();
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
                    url: "{{ URL::to('packing-production/load/previous/record') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {          
                        if (response.production.production_details != '') {
                            var tableHtml = '';
                            var sum = 0;
                            var TotalQty = 0;
                            var TotalPcs = 0;
                            $.each(response.production.packing_production, function(i, v) {
                                sum += 1;
                                TotalQty += parseFloat(v.qty);
                                TotalPcs += parseFloat(v.pcs);
                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${sum}<input type='hidden' name='employee_id[]' id='employee_id' value='${v.employee.id}' /></td>`;
                               tableHtml +=
                                    `<td>${v.employee.party_name}</td>`;
                                    tableHtml +=
                                    `<td>${v.qty}<input type='hidden' name='qty[]' id='qty' value='${v.qty}' /></td>`;
                                    tableHtml +=
                                    `<td>${v.pcs}<input type='hidden' name='pcs[]' id='pcs' value='${v.pcs}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalQty').text(TotalQty);
                            $('#TotalPcs').text(TotalPcs);
                             $('#update_voucher_id').val(response.production.id);
                            $('#date').val(response.production.date);
                            $('#voucher_no').val(response.production.voucher_no);
                            $('#product_name').val(response.production.thermoforming_production.product.product_name);
                            $('#dye_pcs').val(response.production.thermoforming_production.product.dye_pcs);
                            $('#shift').val(response.production.thermoforming_production.shift.shift_name);
                            $('#operator').val(response.production.thermoforming_production.operator.party_name);
                            $('#machine').val(response.production.thermoforming_production.machine.machine_name);
                            $('#press_man').val(response.production.thermoforming_production.pressman.party_name);
                            $('#press_no').val(response.production.thermoforming_production.pressman_no);
                            $('#total_sheets').val(response.production.thermoforming_production.total_sheets);
                            $('#check_sheets').val(response.production.thermoforming_production.check_sheets);
                            $('#wastage').val(response.production.thermoforming_production.wastage);
                            $('#net_sheets').val(response.production.thermoforming_production.net_sheets);
                            $('#net_sku').val(response.production.thermoforming_production.net_sku);
                            $('#remarks').val(response.production.remarks);
                            $('#product_id').val(response.production.product.id + '_' + response
                                .production.product.product_name + '_' + response
                                .production.product.packing).select2();
                            $('#prod_id').val(response.production.product.id);
                            $('#packing').val(response.production.product.packing);
                            $('#thermo_production_id').val(response.production.thermoforming_production.id)
                                .select2();
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
