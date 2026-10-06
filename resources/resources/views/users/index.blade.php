@extends('app')
@section('head')
    <title>Users</title>
    <link href="{{ URL::asset('dashboard/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!--  Select 2 library start-->
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!--  Select 2 library end-->
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Users
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Users</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> ADD NEW USER</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'users', 'class' => 'form-horizontal', 'id' => 'users-form']) !!}
                                    {!! Form::hidden('idd', null, ['id' => 'idd']) !!}
                                    {!! Form::hidden('biller_id', Auth::User()->id, ['id' => 'biller_id']) !!}
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Name <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('name', null, [
                                                'id' => 'name',
                                                'class' => 'form-control',
                                                'autofocus' => 'autofocus',
                                                'required' => 'required',
                                                'tabindex' => '1',
                                            ]) !!}
                                            <span id="name_err" class="text-danger"></span>
                                            @error('name')
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
                                            <label class="mt-1">Phone <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('phone', null, [
                                                'id' => 'phone',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                                'tabindex' => '3',
                                            ]) !!}
                                            <span class="text-danger" id="phone_err"></span>
                                            @error('phone')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Godown <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::select('warehouse_id', $warehouses, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'required' => 'required',
                                                'tabindex' => '4',
                                            ]) !!}
                                            <span class="text-danger" id="warehouse_err"></span>
                                            @error('warehouse_id')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Email <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::email('email', null, [
                                                'id' => 'email',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                                'tabindex' => '5',
                                            ]) !!}
                                            {{-- {!! Form::hidden('email1', null, ['id' => 'email1']) !!} --}}
                                            <span class="text-danger" id="email_err"></span>
                                            @error('email')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Password <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('password', null, [
                                                'id' => 'password',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                                'tabindex' => '6',
                                            ]) !!}
                                            {{-- {!! Form::hidden('password1', null, ['id' => 'password1']) !!} --}}
                                            <span class="text-danger" id="password_err"></span>
                                            @error('password')
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
                                                'tabindex' => '7',
                                            ]) !!}
                                            <span class="text-danger" id="status_err"></span>
                                            @error('status')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Role <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::select('role', $roles, null, [
                                                'id' => 'role',
                                                'class' => 'form-control select2',
                                                'required' => 'required',
                                                'tabindex' => '8',
                                            ]) !!}
                                            <span class="text-danger" id="role_err"></span>
                                            @error('role')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
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
            <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: -40px;">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> LIST OF USERS</h6>
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
                                                    <th>Email</th>
                                                    <th>Godown</th>
                                                    <th>Role</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <td>Sr.</td>
                                                    <td>Name</td>
                                                    <td>Email</td>
                                                    <td>Godown</td>
                                                    <td>Role</td>
                                                    <td>Actions</td>
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
    <!-- DataTables Scripts -->
    <script src="{{ URL::asset('dashboard/datatables/jquery.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.validate.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- End DataTables Scripts -->

    <!-- Searchable Select2 -->
    <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2, .select2-multiple").select2({
            width: "100%"
        });
    </script>
    <!-- End Searchable Select2 -->

    <!-- List of All Users -->
    <script type="text/javascript">
        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ URL::to('users') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'warehouse',
                        name: 'warehouse'
                    },
                    {
                        data: 'role',
                        name: 'role'
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
    <!-- End List of All Users -->

    <!-- Update Warehouse || Department -->
    <script>
        $(document).ready(function() {
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
                var idd = $('#idd').val();
                var name = $('#name').val();
                var address = $('#address').val();
                var phone = $('#phone').val();
                var warehouse_id = $('#warehouse_id').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var status = $('#status').val();
                var role = $('#role').val();
                if (!name) {
                    $('#name_err').text('The Name field is required');
                } else
                if (!address) {
                    $('#address_err').text('The Address field is required');
                } else
                if (!phone) {
                    $('#phone_err').text('The Phone field is required');
                } else
                if (!warehouse_id) {
                    $('#warehouse_err').text('The Godown field is required');
                } else
                if (!email) {
                    $('#email_err').text('The Email field is required');
                } else
                if (!password) {
                    $('#password_err').text('The Password field is required');
                } else
                if (!status) {
                    $('#status_err').text('The Status field is required');
                } else
                if (!role) {
                    $('#role_err').text('The Role field is required');
                } else {
                    $('#users-form').submit();
                }
            });
        });
    </script>
    <!-- End Update Warehouse || Department -->

    <!-- Focus to next field -->
    <script>
        $(document).ready(function() {
            $('#name').keypress(function(event) {
                if ($(this).val() != null) {
                    $('#name_err').text('');
                }

                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('#address').focus();
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
        });
    </script>
    @include('include.toast-messages')
@stop
