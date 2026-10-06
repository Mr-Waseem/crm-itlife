@extends('app')
@section('head')
    <title>Suppliers</title>
    <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!--  Select 2 library start-->
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!--  Select 2 library end-->
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Suppliers
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Suppliers</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> ADD SUPPLIER INFORMATION</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'supplier', 'class' => 'form-horizontal', 'id' => 'customer-form']) !!}
                                    {!! Form::hidden('idd', null, ['id' => 'idd']) !!}
                                    {!! Form::hidden('shop_id', 2, ['id' => 'shop_id']) !!}
                                    <!--{!! Form::hidden('role', 'Supplier', ['id' => 'role']) !!}-->
                                    {!! Form::hidden('account_type', 'SUPPLIER', ['id' => 'account_type']) !!}
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Name <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('party_name', null, [
                                                'id' => 'party_name',
                                                'class' => 'form-control',
                                                'autofocus' => 'autofocus',
                                                'required' => 'required',
                                                'placeholder' => 'Supplier Information',
                                                'tabindex' => '1',
                                            ]) !!}
                                            <span id="party_name_err" class="text-danger"></span>
                                            @error('party_name')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Address <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('address', null, [
                                                'id' => 'address',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                                'tabindex' => '2',
                                            ]) !!}
                                            <span class="text-danger" id="address_err"></span>
                                            @error('address')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Email <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('email', null, [
                                                'id' => 'email',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                                'tabindex' => '3',
                                            ]) !!}
                                            <span class="text-danger" id="email_err"></span>
                                            @error('email')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Password <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::password('password', [
                                                'id' => 'password',
                                                'class' => 'form-control',
                                                'tabindex' => '4',
                                            ]) !!}
                                            <span class="text-danger" id="password_err"></span>
                                            @error('password')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Phone <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('phone', null, [
                                                'id' => 'phone',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                                'tabindex' => '5',
                                                'onkeypress'=>"return onlyNumberKey(event)"
                                            ]) !!}
                                            <span class="text-danger" id="phone_err"></span>
                                            @error('phone')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">City <span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('city', null, [
                                                'id' => 'city',
                                                'class' => 'form-control',
                                                'tabindex' => '6',
                                            ]) !!}
                                            <span class="text-danger" id="city_err"></span>
                                            @error('city')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">N.T.N <span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('ntn', null, [
                                                'id' => 'ntn',
                                                'class' => 'form-control',
                                                'tabindex' => '7',
                                            ]) !!}
                                            <span class="text-danger" id="ntn_err"></span>
                                            @error('ntn')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">S.T.R.N <span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('strn', null, [
                                                'id' => 'strn',
                                                'class' => 'form-control',
                                                'tabindex' => '8',
                                            ]) !!}
                                            <span class="text-danger" id="strn_err"></span>
                                            @error('strn')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Status <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::select('status', $status, null, [
                                                'id' => 'status',
                                                'class' => 'form-control select2',
                                                'required' => 'required',
                                                'tabindex' => '9',
                                            ]) !!}
                                            <span class="text-danger" id="status_err"></span>
                                            @error('status')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Type <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::select('type', $type, null, [
                                                'id' => 'type',
                                                'class' => 'form-control select2',
                                                'required' => 'required',
                                                'tabindex' => '10',
                                            ]) !!}
                                            <span class="text-danger" id="type_err"></span>
                                            @error('type')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Level <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::select('account_group_id3', $accountGroupLevel3, null, [
                                                'id' => 'account_group_id3',
                                                'class' => 'form-control select2',
                                                'required' => 'required',
                                                'tabindex' => '11',
                                            ]) !!}
                                            <span class="text-danger" id="account_group_id3_err"></span>
                                            @error('account_group_id3')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="text-xs-right bt-1 pt-10 mt-2">
                                        <button type="button" class="btn btn-info"
                                            onclick="FormSubmit()">Submit</button>
                                        <button type="reset" class="btn btn-primary reset_btn">Reset</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> LIST SUPPLIER INFORMATION</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
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
                                                    <th>Name</th>
                                                    <th>Code</th>
                                                    <th>Phone</th>
                                                    <th>City</th>
                                                    <th>Address</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Name</th>
                                                    <th>Code</th>
                                                    <th>Phone</th>
                                                    <th>City</th>
                                                    <th>Address</th>
                                                    <th>Actions</th>
                                                </tr>
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
    <script type="text/javascript">
        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ URL::to('supplier') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'party_name',
                        name: 'party_name'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'city',
                        name: 'city'
                    },
                    {
                        data: 'address',
                        name: 'address'
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


    <!-- Move to Next Field -->
    <script>
        $(document).ready(function() {
            //*************** Reset Button *******************

            $('.reset_btn').click(function() {
                $('#status').val(null).select2();
                $('#type').val(null).select2();
            });

            //*************** End Reset Button *******************
            //*************** Edit Button *******************
            $(document).on('click', '.edit_btn', function() {
                var data = $(this).attr('name');
                $('#idd').val(data.split('_')[0]);
                $('#party_name').val(data.split('_')[1]);
                $('#address').val(data.split('_')[2]);
                $('#phone').val(data.split('_')[3]);
                $('#city').val(data.split('_')[4]);
                $('#ntn').val(data.split('_')[5]);
                $('#strn').val(data.split('_')[6]);
                $('#status').val(data.split('_')[7]).select2();
                $('#type').val(data.split('_')[8]).select2();
                $('#account_group_id3').val(data.split('_')[9]).select2();
                $('#email').val(data.split('_')[10]);
                $(window).scrollTop(0);
            });

            //*************** End Edit Button *******************

            //*************** Switch Next Tabs *******************
            $('#party_name').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    if ($(this).val() != null) {
                        $('#party_name_err').text('');
                    }
                    $("#address").focus();
                }
            });
            $('#address').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    if ($(this).val() != null) {
                        $('#address_err').text('');
                    }
                    $("#email").focus();
                }
            });
            $('#email').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    if ($(this).val() != null) {
                        $('#email_err').text('');
                    }
                    $("#password").focus();
                }
            });
            $('#password').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    if ($(this).val() != null) {
                        $('#password_err').text('');
                    }
                    $("#phone").focus();
                }
            });
            $('#phone').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    if ($(this).val() != null) {
                        $('#phone_err').text('');
                    }
                    $("#city").focus();
                }
            });
            $('#city').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // if ($(this).val() != null) {
                    //     $('#city_err').text('');
                    // }
                    $("#ntn").focus();
                }
            });
            $('#ntn').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // if ($(this).val() != null) {
                    //     $('#ntn_err').text('');
                    // }
                    $("#strn").focus();
                }
            });
            $('#strn').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // if ($(this).val() != null) {
                    //     $('#strn_err').text('');
                    // }
                    $("#status").select2('open');
                }
            });
            $('#status').change(function(event) {
                if ($(this).val() != null) {
                    $('#status_err').text('');
                }
                $('#status').select2('close');
                $("#type").select2("open");
            });
            $('#type').change(function(event) {
                if ($(this).val() != null) {
                    $('#type_err').text('');
                }
                $('#type').select2('close');
                $('#account_group_id3').select2('open');
            });
            $('#account_group_id3').change(function(event) {
                if ($(this).val() != null) {
                    $('#account_group_id3_err').text('');
                }
                $('#account_group_id3').select2().trigger("select2:close");
            });
            //*************** End Switch Next Tabs *******************
        });

        //*************** Form Submit *******************
        function FormSubmit() {
            var party_name = $('#party_name').val();
            var address = $('#address').val();
            var email = $('#email').val();
            var password = $('#password').val();
            var phone = $('#phone').val();
            var city = $('#city').val();
            var ntn = $('#ntn').val();
            var strn = $('#strn').val();
            var status = $('#status').val();
            var type = $('#type').val();
            var account_group_id3 = $('#account_group_id3').val();

            if (!party_name) {
                $('#party_name_err').text('This field is required');
                return false;
            } 
            else
            if (!address) {
                $('#address_err').text('This field is required');
                return false;
            } 
            else
            if (!email) {
                $('#email_err').text('This field is required');
                return false;
            } 
            else
            if (!password) {
                $('#password_err').text('This field is required');
                return false;
            } 
            else
            if (!phone) {
                $('#phone_err').text('This field is required');
                return false;
            } 
            else
            // if (!city) {
            //     $('#city_err').text('This field is required');
            //     return false;
            // } else
            // if (!ntn) {
            //     $('#ntn_err').text('This field is required');
            //     return false;
            // } else
            // if (!strn) {
            //     $('#strn_err').text('This field is required');
            //     return false;
            // } else
            if (!status) {
                $('#status_err').text('This field is required');
                return false;
            } else
            if (!type) {
                $('#type_err').text('This field is required');
                return false;
            } else
            if (!account_group_id3) {
                $('#account_group_id3_err').text('This field is required');
                return false;
            } else {
                $('#customer-form').submit();
                return true;
            }
        }
        //*************** End Form Submit *******************
    </script>
    <!-- End Here -->






    <!-- Searchable Select2 -->
    <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2, .select2-multiple").select2({
            width: "100%"
        });
    </script>
    <!-- End Searchable Select2 -->

    <!-- Parties | Customers Remove | Swal Notification-->
    <script>
        // Swal Confirmation
        $(document).ready(function() {
            $(document).on('click', '.remove-account', function() {
                let id = $(this).attr('name');
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Are you sure you want to delete this supplier?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    confirmButtonColor: '#28A745',
                    cancelButtonText: 'No, cancel!',
                    cancelButtonColor: '#DC3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = `{{ URL::to('supplier/destroy/${id}') }}`;
                    }
                });
            });
        });
    </script>
    @include('include.toast-messages')
@stop
