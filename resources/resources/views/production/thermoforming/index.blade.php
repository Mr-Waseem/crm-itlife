@extends('app')
@section('head')
    <title>Thermoforming Production</title>
@stop
@section('content')
<div class="icon-bar">
        <a href="javascript:void(0);" class="load-previous-record"><i class="fa fa-angle-left"></i></a>
        <a href="javascript:void(0);" class="load-edit-record"><i class="fa fa-repeat"></i></a>
        <a href="javascript:void(0);" class="load-next-record"><i class="fa fa-angle-right"></i></a>
        <a href="javascript:void(0);" class="delete_record_btn"><i class="fa fa-trash-o"></i></a>
        <a href="javascript:void(0);" class="print_record_btn"><i class="fa fa-print"></i></a>
    </div>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>THERMOFORMING PRODUCTION</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Thermoforming Production</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <!-- <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> ADD Thermoforming Production</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div> -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'thermoforming-production', 'class' => 'form-horizontal', 'id' => 'users-form']) !!}
                                    {!! Form::hidden('idd', null, ['id' => 'idd']) !!}
                                    {!! Form::hidden('biller_id', Auth::User()->id, ['id' => 'biller_id']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    <div class="row">
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Vr.Date <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
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
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Vr.No <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::text('voucher_no', $codes, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                'autofocus' => 'autofocus',
                                            ]) !!}
                                            <span class="text-danger" id="voucher_no_err"></span>
                                            @error('voucher_no')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Remarks</label>
                                        </div>
                                        <div class="col-lg-4 col-md-2 col-sm-12">
                                        {!! Form::text('remarks', null, [
                                                'id' => 'remarks',
                                                'class' => 'form-control'
                                            ]) !!}
                                            <span class="text-danger" id="voucher_no_err"></span>
                                            @error('voucher_no')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <!-- <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Make Product <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::select('product_id', $product, null, [
                                                'id' => 'product_id',
                                                'class' => 'form-control select2',
                                                'required' => 'required',
                                                'tabindex' => '4',
                                            ]) !!}
                                            <span class="text-danger" id="product_id_err"></span>
                                            @error('product_id')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Dye Pcs <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::text('dye_units', null, [
                                                'id' => 'dye_units',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly',
                                            ]) !!}
                                            <span class="text-danger" id="dye_units_err"></span>
                                            @error('dye_units')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div> -->
                                    </div>


                                    <div class="row mt-2">
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Consumed Product <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-12 add-production">
                                            {!! Form::select('consumed_product_id', $batchStock, null, [
                                                    'id' => 'consumed_product_id',
                                                    'class' => 'form-control select2',
                                                ]) !!}
                                                <span class="text-danger" id="consumed_product_id_err"></span>
                                                @error('consumed_product_id')
                                                    <p class="invalid-feedback1">{{ $message }}</p>
                                                @enderror
                                        </div>

                                        <!-- <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Consumed Product <span class="text-danger">*</span></label>
                                        </div> -->
                                        <div class="col-lg-5 col-md-5 col-sm-12 edit-production d-none">
                                            {!! Form::text('consumed_product_id_edit', null, [
                                                    'id' => 'consumed_product_id_edit',
                                                    'class' => 'form-control',
                                                    'readonly' => 'readonly',
                                                ]) !!}
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Thickness <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-5 col-sm-12">
                                            {!! Form::text('thickness', null, [
                                                'id' => 'thickness',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly',
                                                'tabindex' => '4',
                                            ]) !!}
                                            <span class="text-danger" id="thickness_err"></span>
                                            @error('thickness')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Net Weight <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-5 col-sm-12">
                                            {!! Form::text('net_weight', null, [
                                                'id' => 'net_weight',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly',
                                            ]) !!}
                                            <span class="text-danger" id="net_weight_err"></span>
                                            @error('net_weight')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Width <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-5 col-sm-12">
                                            {!! Form::text('width', null, [
                                                'id' => 'width',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly',
                                                'tabindex' => '4',
                                            ]) !!}
                                            <span class="text-danger" id="thickness_err"></span>
                                            @error('thickness')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Color <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-5 col-sm-12">
                                            {!! Form::text('color', null, [
                                                'id' => 'color',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly',
                                            ]) !!}
                                            <span class="text-danger" id="color_err"></span>
                                            @error('color')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Consumed<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::text('consumed', null, [
                                                'id' => 'consumed',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                'class' => 'form-control'
                                            ]) !!}
                                            <span class="text-danger consumed_err"></span>
                                           
                                        </div>

                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Balance<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::text('balance_weight', null, [
                                                'id' => 'balance_weight',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                            {!! Form::hidden('balance_weight_formula', null, [
                                                'id' => 'balance_weight_formula',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                            <span class="text-danger" id="balance_err"></span>
                                         
                                        </div>
                                    </div>
                                            <hr/>
                                    <div class="row mt-2">
                                      
                                      <div class="col-lg-1 col-md-1 col-sm-12">
                                          <label class="mt-1">Make Product <span class="text-danger">*</span></label>
                                      </div>
                                      <div class="col-lg-6 col-md-6 col-sm-12">
                                      {!! Form::select('product_id', $product, null, [
                                              'id' => 'product_id',
                                              'class' => 'form-control select2',
                                              'required' => 'required',
                                              'tabindex' => '4',
                                          ]) !!}
                                          <span class="text-danger" id="product_id_err"></span>
                                          @error('product_id')
                                              <p class="invalid-feedback1">{{ $message }}</p>
                                          @enderror
                                      </div>
                                      <div class="col-lg-1 col-md-1 col-sm-12">
                                          <label class="mt-1">Dye Pcs <span class="text-danger">*</span></label>
                                      </div>
                                      <div class="col-lg-2 col-md-2 col-sm-12">
                                      {!! Form::text('dye_units', null, [
                                              'id' => 'dye_units',
                                              'class' => 'form-control',
                                              'readonly' => 'readonly',
                                          ]) !!}
                                          <span class="text-danger" id="dye_units_err"></span>
                                          @error('dye_units')
                                              <p class="invalid-feedback1">{{ $message }}</p>
                                          @enderror
                                      </div>
                                  </div>


                                    <div class="row mt-1">
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Shift <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::select('shift_id', $shift, null, [
                                                'id' => 'shift_id',
                                                'class' => 'form-control select2',
                                                'required' => 'required',
                                                'tabindex' => '4',
                                            ]) !!}
                                            <span class="text-danger" id="shift_id_err"></span>
                                            @error('shift_id')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Operator<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::select('operator_id', $operator, null, [
                                                'id' => 'operator_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                            ]) !!}
                                            <span class="text-danger" id="operator_id_err"></span>
                                            @error('operator_id')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Machine <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::select('machine_id', $machines, null, [
                                                'id' => 'machine_id',
                                                'class' => 'form-control select2',
                                            ]) !!}
                                            <span class="text-danger" id="machine_id_err"></span>
                                            @error('machine_id')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Pressman <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::select('pressman_id',$pressman, null, [
                                                'id' => 'pressman_id',
                                                'class' => 'form-control select2',
                                                'readonly' => 'readonly',
                                            ]) !!}
                                            <span class="text-danger" id="pressman_id_err"></span>
                                            @error('pressman_id')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Press No <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::text('pressman_no', null, [
                                                'id' => 'pressman_no',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                                'tabindex' => '4',
                                            ]) !!}
                                            <span class="text-danger" id="pressman_no_err"></span>
                                            @error('pressman_no')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Total Sheets<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::text('total_sheets', null, [
                                                'id' => 'total_sheets',
                                                'class' => 'form-control',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                            <span class="text-danger" id="total_sheets_err"></span>
                                            @error('total_sheets')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Check Sheets <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::text('check_sheets', null, [
                                                'id' => 'check_sheets',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                'class' => 'form-control'
                                            ]) !!}
                                            <span class="text-danger" id="check_sheets_err"></span>
                                            @error('check_sheets')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Wastage <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::text('wastage', null, [
                                                'id' => 'wastage',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                'class' => 'form-control'
                                            ]) !!}
                                            <span class="text-danger" id="wastage_err"></span>
                                            @error('wastage')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Net Sheets <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::text('net_sheets', null, [
                                                'id' => 'net_sheets',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}
                                            <span class="text-danger" id="net_sheets_err"></span>
                                            @error('net_sheets')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Net SKU<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::text('net_sku', null, [
                                                'id' => 'net_sku',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                            <span class="text-danger" id="net_sku_err"></span>
                                            @error('net_sku')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                     
                                        <!-- <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Color<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::text('color', null, [
                                                'id' => 'color',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly',
                                            ]) !!}
                                            <span class="text-danger" id="color_err"></span>
                                            @error('color')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div> -->
                                    </div>

                               

                                    <div class="text-xs-right bt-1 pt-10 mt-5">
                                        <button type="button" class="btn btn-info form_submit">Submit</button>
                                        <button type="reset" class="btn btn-primary reset_btn">Reset</button>
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
                <form action="{{ URL::to('thermoforming-production/delete-voucher') }}" method="post" id="delete_voucher_form">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Voucher?</p>
                        <input type="hidden" name="delete_voucher_id" id="delete_voucher_id" value="">
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
        $(document).ready(function() {
            $('#consumed_product_id').change(function() {
            var Batchid = parseInt($('#consumed_product_id').val());
            // alert(product_id)
            // alert("ddd")
                    $.ajax({
                        url: "{{ URL::to('thermoforming-production/load-role-data') }}",
                        type: 'get',
                        data: {
                        Batchid: Batchid,
                        },

                        success: function(response) {
                            if (response != null && response != 0) {
                               $('#color').val(response.color.name);
                               $('#thickness').val(response.thickness);
                               $('#net_weight').val(response.total_qty);
                               $('#width').val(response.width);
                               $('#balance_weight').val(response.balance_weight);
                               $('#balance_weight_formula').val(response.balance_weight);
                            } else {
                                
                            }
                        }
                    });
                });

            $(document).on('click', '.edit_btn', function() {
                var data = $(this).attr('name');
                var id = data.split('_')[0];
                var name = data.split('_')[1];
                var address = data.split('_')[2];
                var phone = data.split('_')[3];
                var warehouse_id = data.split('_')[4];
                var email = data.split('_')[5];
                var password = data.split('_')[6];
                var status = data.split('_')[7];
                var role = data.split('_')[8];
                $('#idd').val(id);
                $('#name').val(name);
                $('#address').val(address);
                $('#phone').val(phone);
                $('#warehouse_id').val(warehouse_id).select2();
                $('#email').val(email);
                // $('#email1').val(email);
                $('#status').val(status).select2();
                $('#role').val(role).select2();
                $('#password').val(password);
                // $('#password1').val(password);
                // $('#email').attr('disabled', true);
                // $('#password').attr('disabled', true);
            });
            $('.reset_btn').click(function() {
                $('#idd').val(null);
                $('#email1').val(null);
                $('#password1').val(null);
                $('#warehouse_id').val(null).select2();
                $('#status').val(null).select2();
                $('#role').val(null).select2();
                $('#email').attr('disabled', false);
                $('#password').attr('disabled', false);
            });
            $('.form_submit').click(function() {

                var consumed = parseInt($('#consumed').val());
                // alert(consumed);
                // if(consumed <= 0){
                //     $('#consumed').val(0);
                //     $('#consumed').select();
                // }
                // var NetWeight = $('#net_weight').val();
                // var Balance = NetWeight - consumed;
                var balanceWeight = parseInt($('#balance_weight_formula').val());
                if(consumed > balanceWeight){
                    // alert("dd");
                    $('.consumed_err').text('This field cant exceed Balance.');
                    return false;
                }

             
                var idd = $('#idd').val();
                var Voucher = $('#voucher_no').val();
                var Product = $('#product_id').val();
                var DyePcs = $('#dye_units').val();
                var shiftID = $('#shift_id').val();
                var operatorID = $('#operator_id').val();
                var pressmanNO = $('#pressman_no').val();
                var totalSheets = $('#total_sheets').val();
                var checkSheets = $('#check_sheets').val();
                var netSheets = $('#net_sheets').val();
                // alert("Ddd")
                if (!Voucher) {
                    $('#voucher_no_err').text('The Voucher field is required');
                } else
                if (!Product) {
                    $('#product_id_err').text('The Product Name field is required');
                } else
                if (!pressmanNO) {
                    $('#pressman_no_err').text('The Press No field is required');
                } else
                if (!totalSheets) {
                    $('#total_sheets_err').text('The Total Sheets field is required');
                } else
                if (!checkSheets) {
                    $('#check_sheets_err').text('The Check Sheets field is required');
                } else {
                    $('#users-form').submit();
                    $('.form_submit').attr('disabled', true);
                }
            });
        });
    </script>
    <!-- End Update Warehouse || Department -->

    <!-- Focus to next field -->
    <script>
        $(document).ready(function() {
            $('#voucher_no').keydown(function(event) {
                // alert("dd")
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#remarks").focus();
                }
            });

            $('#remarks').keydown(function(event) {
                // alert("dd")
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#consumed_product_id").select2('open');
                }
            });

            $('#consumed_product_id').change(function(event) {
                var product = $(this).val();
                if (product != null) {
                    $('#consumed_product_id').select2().trigger('select2:close');
                    $('#consumed').focus();
                }
            });

            $('#consumed').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // $('#pressman_no').focus();
                    // var consumed = $(this).val();
                    // // var NetWeight = $('#net_weight').val();
                    // // var Balance = NetWeight - consumed;
                    // var balanceWeight = $('#balance_weight_formula').val();
                    // // alert(balanceWeight);
                    // var Balance = balanceWeight - consumed;
                    // $('#balance_weight').val(Balance);
                    var consumed = parseFloat($(this).val());
                    // var NetWeight = $('#net_weight').val();
                    // var Balance = NetWeight - consumed;
                    var balanceWeight = parseFloat($('#balance_weight_formula').val());
                    // alert(consumed);
                    // alert(balanceWeight);
                    
                    // $('.consumed_err').text('This field cant exceed Balance.');
                    //     return false;
                    if(consumed > balanceWeight){
                        // alert("dd");
                        $('.consumed_err').text('This field cant exceed Balance.');
                        return false;
                    }else{
                        $("#product_id").select2('open');
                    }
                    
                }
            });

            $('#consumed').keyup(function(event) {
                    var consumed = $(this).val();
                        if(consumed <= 0){
                            $('#consumed').val(0);
                            $('#consumed').select();
                        }
                        // var NetWeight = $('#net_weight').val();
                        // var Balance = NetWeight - consumed;
                        var balanceWeight = parseFloat($('#balance_weight_formula').val());
                        if(consumed > balanceWeight){
                            // alert("dd");
                            $('.consumed_err').text('This field cant exceed Balance.');
                            return false;
                        }
                        var Balance = balanceWeight - consumed;
                        $('#balance_weight').val(Balance.toFixed(2));
                        // $("#product_id").select2('open');
            });

            $('#product_id').change(function(event) {
                var product = $(this).val();
               
                if (product != null) {
                    $('#dye_units').val(product.split('_')[1]);
                    $('#product_id').select2().trigger('select2:close');
                    $("#shift_id").select2('open');
                }
            });



            $('#shift_id').change(function(event) {
                var product = $(this).val();
                if (product != null) {
                    $('#shift_id').select2().trigger('select2:close');
                    $("#operator_id").select2('open');
                }
            });

            $('#operator_id').change(function(event) {
                var product = $(this).val();
                if (product != null) {
                    $('#operator_id').select2().trigger('select2:close');
                    $("#machine_id").select2('open');
                }
            });

            $('#machine_id').change(function(event) {
                var product = $(this).val();
                if (product != null) {
                    $('#machine_id').select2().trigger('select2:close');
                    $("#pressman_id").select2('open');
                }
            });

            // $('#pressman_id').change(function(event) {
            //     var keycode = (event.keyCode ? event.keyCode : event.which);
            //     if (keycode == '13') {
            //         $('#pressman_no').focus();
            //     }
            // });



            // $('#pressman_no').change(function(event) {
            //     var keycode = (event.keyCode ? event.keyCode : event.which);
            //     if (keycode == '13') {
            //         $('#total_sheets').focus();
            //     }
            // });

            $('#pressman_id').change(function(event) {
                var product = $(this).val();
                if (product != null) {
                    $('#pressman_id').select2().trigger('select2:close');
                    $('#pressman_no').focus();
                }
            });

            $('#pressman_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#total_sheets').focus();
                }
            });

            $('#total_sheets').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#check_sheets').focus();
                }
            });


            $('#total_sheets').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#check_sheets').focus();
                }
            });
            $('#check_sheets').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#wastage').focus();
                }
            });
            $('#wastage').change(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                // if (keycode == '13') {
                    var wastage = $(this).val();
                    var TotalSheets = $('#total_sheets').val();
                    var NetSheets = TotalSheets - wastage;
                    $('#net_sheets').val(NetSheets);

                    var Dyepcs = $('#dye_units').val();
                    var netSku = NetSheets * Dyepcs;
                    $('#net_sku').val(netSku);
                    $('#consumed').focus();
                // }
            });
            // $('#net_sheets').keydown(function(event) {
            //     var keycode = (event.keyCode ? event.keyCode : event.which);
            //     if (keycode == '13') {
            //         $('#net_sku').focus();
            //     }
            // });
            // $('#net_sku').keydown(function(event) {
            //     var keycode = (event.keyCode ? event.keyCode : event.which);
            //     if (keycode == '13') {
            //         $('#consumed').focus();
            //     }
            // });
        
            // $('#consumed_product_id').change(function(event) {
            //     var keycode = (event.keyCode ? event.keyCode : event.which);
            //     if (keycode == '13') {
            //         $('#thickness').focus();
            //     }
            // });


            $('#thickness').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#net_weight').focus();
                }
            });






            $('#address').keypress(function(event) {
                if ($(this).val() != null) {
                    $('#address_err').text('');
                }

                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#phone').focus();
                }
            });
            $('#phone').keypress(function(event) {
                if ($(this).val() != null) {
                    $('#phone_err').text('');
                }

                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#warehouse_id').select2('open');
                }
            });
            $('#warehouse_id').change(function(event) {
                var warehouse_id = $(this).val();
                if (warehouse_id != null) {
                    if ($(this).val() != null) {
                        $('#warehouse_err').text('');
                    }
                    $('#warehouse_id').select2().trigger('select2:close');
                    $('#email').focus();
                }
            });
            $('#email').keypress(function(event) {
                if ($(this).val() != null) {
                    $('#email_err').text('');
                }

                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#password').focus();
                }
            });
            $('#password').keypress(function(event) {
                if ($(this).val() != null) {
                    $('#password_err').text('');
                }

                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#status').select2('open');
                }
            });
            $('#status').change(function(event) {
                var status = $(this).val();
                if (status != null) {
                    if ($(this).val() != null) {
                        $('#status_err').text('');
                    }
                    $('#status').select2().trigger('select2:close');
                    $('#role').select2('open');
                }
            });
            $('#role').change(function(event) {
                var role = $(this).val();
                if (role != null) {
                    if ($(this).val() != null) {
                        $('#role_err').text('');
                    }
                    $('#role').select2().trigger('select2:close');
                }
            });
        });
    </script>
    <!-- End Focus to next field -->

    <script>
        // Swal Confirmation | Remove User
        $(document).ready(function() {
            $(document).on('click', '.remove-user', function() {
                let id = $(this).attr('name');
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Are you sure you want to delete this user?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    confirmButtonColor: '#28A745',
                    cancelButtonText: 'No, cancel!',
                    cancelButtonColor: '#DC3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = `{{ URL::to('users/destroy/${id}') }}`;
                    }
                });
            });


            $('.load-previous-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('thermoforming-production/load/previous/record') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.production != '') {
                            $('#product_id').val(response.production.product_id).select2();
                            $('#product_id').val(response.production.product_id + '_' + response
                                .production.product.dye_pcs).select2();
                            $('#dye_units').val(response.production.product.dye_pcs);
                            $('#shift_id').val(response.production.shift_id).select2();
                            $('#operator_id').val(response.production.operator_id).select2();
                            $('#machine_id').val(response.production.machine_id).select2();
                            $('#pressman_id').val(response.production.pressman_id).select2();
                            $('#voucher_no').val(response.production.voucher_no);
                            $('#remarks').val(response.production.remarks);
                            $('#pressman_no').val(response.production.pressman_no);
                            $('#total_sheets').val(response.production.total_sheets);
                            $('#check_sheets').val(response.production.check_sheets);
                            $('#wastage').val(response.production.wastage);
                            $('#net_sheets').val(response.production.net_sheets);
                            $('#net_sku').val(response.production.net_sku);
                            $('#consumed').val(response.production.consumed);
                            $('#consumed_product_id').val(response.production.consumed_product_id).select2();
                            $('.edit-production input').val(
                                response.production.roll_production.batchNo+'-'+
                                response.production.roll_production.thickness+'-'+
                                response.production.roll_production.width+'-'+
                                response.production.roll_production.color+'-'+
                                response.production.roll_production.total_qty
                                );
                            $('#thickness').val(response.production.roll_production.thickness);
                            $('#net_weight').val(response.production.roll_production.total_qty);
                            $('#width').val(response.production.roll_production.width);
                            $('#color').val(response.production.roll_production.color);
                            $('#idd').val(response.production.id);
                            $('#voucher_no').focus();
                            // $('.consumed_product_id_edit').removeClass('d-none');
                            $('.add-production').addClass('d-none');
                            $('.edit-production').removeClass('d-none');
                            // $('#updated_by_name').removeClass('d-none');
                        }
                    }
                });
            });

            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('thermoforming-production/load/next/record') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                    
                        if (response.production != '') {
                            // alert("Dddd")
                            $('#product_id').val(response.production.product_id).select2();
                            $('#product_id').val(response.production.product_id + '_' + response
                                .production.product.dye_pcs).select2();
                            $('#dye_units').val(response.production.product.dye_pcs);
                            $('#shift_id').val(response.production.shift_id).select2();
                            $('#operator_id').val(response.production.operator_id).select2();
                            $('#machine_id').val(response.production.machine_id).select2();
                            $('#pressman_id').val(response.production.pressman_id).select2();
                            $('#voucher_no').val(response.production.voucher_no);
                            $('#remarks').val(response.production.remarks);
                            $('#pressman_no').val(response.production.pressman_no);
                            $('#total_sheets').val(response.production.total_sheets);
                            $('#check_sheets').val(response.production.check_sheets);
                            $('#wastage').val(response.production.wastage);
                            $('#net_sheets').val(response.production.net_sheets);
                            $('#net_sku').val(response.production.net_sku);
                            $('#consumed').val(response.production.consumed);
                            $('#consumed_product_id').val(response.production.consumed_product_id).select2();
                            $('#thickness').val(response.production.roll_production.thickness);
                            $('#net_weight').val(response.production.roll_production.total_qty);
                            $('#width').val(response.production.roll_production.width);
                            $('#color').val(response.production.roll_production.color);
                            $('#idd').val(response.production.id);
                            $('#voucher_no').focus();
                            // $('#updated_by_name').removeClass('d-none');
                        }
                    }
                });
            });



            $('.load-edit-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('thermoforming-production/load/record') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.production != '') {
                            // alert("Dddd")
                            $('#product_id').val(response.production.product_id).select2();
                            $('#product_id').val(response.production.product_id + '_' + response
                                .production.product.dye_pcs).select2();
                            $('#dye_units').val(response.production.product.dye_pcs);
                            $('#shift_id').val(response.production.shift_id).select2();
                            $('#operator_id').val(response.production.operator_id).select2();
                            $('#machine_id').val(response.production.machine_id).select2();
                            $('#pressman_id').val(response.production.pressman_id).select2();
                            $('#voucher_no').val(response.production.voucher_no);
                            $('#remarks').val(response.production.remarks);
                            $('#pressman_no').val(response.production.pressman_no);
                            $('#total_sheets').val(response.production.total_sheets);
                            $('#check_sheets').val(response.production.check_sheets);
                            $('#wastage').val(response.production.wastage);
                            $('#net_sheets').val(response.production.net_sheets);
                            $('#net_sku').val(response.production.net_sku);
                            $('#consumed').val(response.production.consumed);
                            $('#consumed_product_id').val(response.production.consumed_product_id).select2();
                            $('#thickness').val(response.production.roll_production.thickness);
                            $('#net_weight').val(response.production.roll_production.total_qty);
                            $('#width').val(response.production.roll_production.width);
                            $('#color').val(response.production.roll_production.color);
                            $('#idd').val(response.production.id);
                            $('#voucher_no').focus();
                            // $('#updated_by_name').removeClass('d-none');
                        }
                    }
                });
            });

            $('.delete_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('delete-record-modal'), {});
                myModal.toggle();
                var voucher_no = parseInt($('#voucher_no').val());
                $('#delete_voucher_id').val(voucher_no);
            });
             // Print Record
             $('.print_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                myModal.toggle();

                var voucher_no = parseInt($('#voucher_no').val());
                //   alert(voucher_no);
                var base_url = $('#base_url').val();
                // alert(base_url)
                $.ajax({
                    url: "{{ URL::to('thermoforming-production/print/voucher') }}?voucher_no=" +
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
                                `<object data="${base_url}/resources/upload/production/thermoforming/${response}" type="application/pdf" width="100%" height="800"></object>`
                            );
                        } else {
                            $('#print-receipt-modal-body').html('<h2 style="color:red;text-align:center;">Voucher Not Exist</h2>');
                        }
                    }
                });
            });

        });
    </script>
    @include('include.toast-messages')
@stop
