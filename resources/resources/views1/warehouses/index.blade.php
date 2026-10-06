@extends('app')
@section('head')
    <title>GoDown Information</title>
    <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    @stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                GoDown Information
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">GoDown</a></li>
            </ol>
        </section>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Add New GoDown</h6>
                            <!-- <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul> -->
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'warehouses', 'class' => 'form-horizontal', 'id' => 'sales-voucher-form']) !!}
                                    {!! Form::hidden('id', null, ['id' => 'id']) !!}
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group">
                                                <h5>Code <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::text('code1', $codes, ['id' => 'code1', 'class' => 'form-control', 'disabled' => 'disabled']) !!}
                                                    {!! Form::hidden('code', $codes, ['id' => 'code', 'class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Name <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::text('name', null, [
                                                        'id' => 'name',
                                                        'class' => 'form-control',
                                                        'autofocus' => 'autofocus',
                                                        'required' => 'required',
                                                    ]) !!}
                                                    <span class="text-danger name_err"></span>
                                                        @error('name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                    
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Phone <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::text('phone', null, ['id' => 'phone', 'class' => 'form-control']) !!}
                                                        <span class="text-danger phone_err"></span>
                                                        @error('phone')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Address <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::text('address', null, ['id' => 'address', 
                                                        'class' => 'form-control']) !!}
                                                        <span class="text-danger address_err"></span>
                                                        @error('address')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Email <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::email('email', null, ['id' => 'email',
                                                         'class' => 'form-control']) !!}
                                                         <span class="text-danger email_err"></span>
                                                        @error('email')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Purchase Account <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::select('purchase_account_id', $PurchaseAccount, null, [
                                                        'id' => 'purchase_account_id', 'class' => 'form-control select2', 
                                                        'required' => 'required'
                                                        ]) !!}
                                                        <span class="text-danger purchase_account_err"></span>
                                                        @error('purchase')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Sale Account <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                {!! Form::select('sale_account_id', $SaleAccount, null, [
                                                        'id' => 'sale_account_id', 'class' => 'form-control select2', 
                                                        'required' => 'required'
                                                        ]) !!}
                                                        <span class="text-danger sale_account_err"></span>
                                                        @error('sale')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>PurchaseTax Account <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::select('purchasetax_account_id', $PurchaseAccount, null, [
                                                        'id' => 'purchasetax_account_id', 'class' => 'form-control select2', 
                                                        'required' => 'required'
                                                        ]) !!}
                                                        <span class="text-danger purchasetax_account_err"></span>
                                                        @error('purchase')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                </div>
                                            </div>
                                           
                                            <div class="form-group">
                                                <h5>SaleTax Account <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                {!! Form::select('saletax_account_id', $SaleAccount, null, [
                                                        'id' => 'saletax_account_id', 'class' => 'form-control select2', 
                                                        'required' => 'required'
                                                        ]) !!}
                                                        <span class="text-danger saletax_account_err"></span>
                                                        @error('sale')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <h5>Tax Account (For Ledger Tax)<span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                {!! Form::select('tax_account_id', $taxaccount, null, [
                                                        'id' => 'tax_account_id', 'class' => 'form-control select2', 
                                                        'required' => 'required'
                                                        ]) !!}
                                                        <span class="text-danger tax_account_err"></span>
                                                        @error('sale')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-xs-right bt-1 pt-10">
                                        <!-- <button type="submit" class="btn btn-info">Submit</button> -->
                                        <button class="btn btn-primary submit-form" type="button">Submit</button>
                                        <button type="reset" class="btn btn-primary reset_btn">Reset</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> Godown List </h6>
                            <!-- <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul> -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover data-table">
                                            <thead>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Code</th>
                                                    <th>Departments</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <th>Sr.</th>
                                                <th>Code</th>
                                                <th>Department</th>
                                                <th>Actions</th>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script src="{{ URL::asset('dashboard/datatables/jquery.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.validate.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.js') }}"></script>
    
    <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2, .select2-multiple").select2({
            width: "100%"
        });
    </script>
    
    <script type="text/javascript">
        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ URL::to('warehouses') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>

    <!-- Update Warehouse || Department -->
    <script>
        $(document).ready(function() {

            $('#name').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#phone").focus();
                }
            });

            $('#phone').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#address").focus();
                }
            });

            $('#address').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#email").focus();
                }
            });

            /////////////
            $('#email').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#purchase_account_id").select2('open');
                }
            });

            

            $('#purchase_account_id').change(function(event) {
                var dcn = $(this).val();
                if (dcn) {
                    $('#purchase_account_id').select2().trigger('select2:close');
                    $("#sale_account_id").select2('open');
                }
            });

            $('#sale_account_id').change(function(event) {
                var dcn = $(this).val();
                if (dcn) {
                    $('#sale_account_id').select2().trigger('select2:close');
                    $("#purchasetax_account_id").select2('open');
                }
            });

            // $('#saletax_account_id').change(function(event) {
            //     var dcn = $(this).val();
            //     if (dcn) {
            //         $('#saletax_account_id').select2().trigger('select2:close');
            //         $("#purchasetax_account_id").select2('open');
            //     }
            // });

            $('#purchasetax_account_id').change(function(event) {
                var dcn = $(this).val();
                if (dcn) {
                    $('#purchasetax_account_id').select2().trigger('select2:close');
                    $("#saletax_account_id").select2('open');
                }
            });

            $('#saletax_account_id').change(function(event) {
                var dcn = $(this).val();
                if (dcn) {
                    $('#saletax_account_id').select2().trigger('select2:close');
                    $("#tax_account_id").select2('open');
                }
            });



               // Form Submit
               $('.submit-form').click(function() { 
                // alert("ente")
                var name = $('#name').val();
                var phone = $('#phone').val();
                var address = $('#address').val();
                var email = $('#email').val();
                var purchaseAccount = $('#purchase_account_id').val();
                var saleAccount = $('#sale_account_id').val();
                var purchaseTaxAccount = $('#purchasetax_account_id').val();
                var saleTaxAccount = $('#saletax_account_id').val();
                var TaxAccount = $('#tax_account_id').val();
                // $('.name_err').text('');
                //  $('.phone_err').text('');
                // $('.party_name_err').text('');


                if(!name)
                {
                    // alert("1")
                    $('.name_err').text('The Name field is required.');
                    return false;
                }else
                if(!phone)
                {
                    // alert("2")
                    $('.phone_err').text('The Phone field is required.');
                    return false;
                }else
                if(!address)
                {
                    // alert("3")
                    $('.address_err').text('The Address field is required.');
                    return false;
                }else
                if(!email)
                {
                    // alert("3")
                    $('.email_err').text('The Email field is required.');
                    return false;
                }else
                if(!purchaseAccount)
                {
                    // alert("3")
                    $('.purchase_account_err').text('Purchase Account field is required.');
                    return false;
                }else
                if(!saleAccount)
                {
                    // alert("3")
                    $('.sale_account_err').text('Sale Account field is required.');
                    return false;
                }
                if(!purchaseTaxAccount)
                {
                    // alert("3")
                    $('.purchasetax_account_err').text('Purchase Tax Account field is required.');
                    return false;
                }
                if(!saleTaxAccount)
                {
                    // alert("3")
                    $('.saletax_account_err').text('Sale Tax Account field is required.');
                    return false;
                }
                if(!TaxAccount)
                {
                    // alert("3")
                    $('.tax_account_err').text('Sale Tax Account field is required.');
                    return false;
                }
                else{
                    // alert("4")
                    $('#sales-voucher-form').submit();
                }
                // alert(voucher_no)
            });
            // End FOrm Submit

            $(document).on('click', '.edit_btn', function() {
                var data = $(this).attr('name');
                var id = data.split('_')[0];
                var code = data.split('_')[1];
                var name = data.split('_')[2];
                var phone = data.split('_')[3];
                var address = data.split('_')[4];
                var email = data.split('_')[5];
                var purchaseAccount = data.split('_')[6];
                var saleAccount = data.split('_')[7];
                var purchaseTaxAccount = data.split('_')[8];
                var saleTaxAccount = data.split('_')[9];
                var TaxAccount = data.split('_')[10];
                // alert(purchaseAccount);
                // alert(saleAccount);
                // alert(purchaseTaxAccount);
                // alert(saleTaxAccount);
                $('#id').val(id);
                $('#code').val(code);
                $('#code1').val(code);
                $('#name').val(name);
                $('#phone').val(phone);
                $('#address').val(address);
                $('#email').val(email);
                $('#purchase_account_id').val(purchaseAccount).select2();
                $('#sale_account_id').val(saleAccount).select2();
                $('#purchasetax_account_id').val(purchaseTaxAccount).select2();
                $('#saletax_account_id').val(saleTaxAccount).select2();
                $('#tax_account_id').val(TaxAccount).select2();
                $("#name").focus();
            });
        });
    </script>

    @include('include.toast-messages')
@stop
