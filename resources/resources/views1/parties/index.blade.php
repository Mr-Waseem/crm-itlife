@extends('app')
@section('head')
    <title>Chart Of Account</title>
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
                Chart Of Account
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Chart Of Account</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Add New Chart Of Account</h6>
                            <!-- <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul> -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'parties', 'class' => 'form-horizontal', 'id' => 'party-form']) !!}
                                    {!! Form::hidden('idd', null, ['id' => 'idd']) !!}
                                    {!! Form::hidden('shop_id', 2, ['id' => 'shop_id']) !!}
                                    {!! Form::hidden('role', 'Account', ['id' => 'role']) !!}
                                    {!! Form::hidden('created_by', Auth::User()->id, ['id' => 'created_by']) !!}
                                    {!! Form::hidden('updated_by', Auth::User()->id, ['id' => 'updated_by']) !!}
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group d-none">
                                                <h5>Code <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {{-- {!! Form::text('code1', $codes, ['id' => 'code1', 'class' => 'form-control', 'disabled' => 'disabled']) !!} --}}
                                                    {!! Form::text('code', null, ['id' => 'code', 'class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                            {{-- <h5>Group Id <span class="text-danger">*</span></h5> --}}
                                            <div class="form-group d-none">
                                                {!! Form::hidden('group_id', null, [
                                                    'id' => 'group_id',
                                                    'class' => 'form-control',
                                                    'autofocus' => 'autofocus',
                                                    'required' => 'required',
                                                ]) !!}
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <h5>Account Name <span class="text-danger">*</span></h5>
                                                {!! Form::text('party_name', null, [
                                                    'id' => 'party_name',
                                                    'class' => 'form-control',
                                                    'autofocus' => 'autofocus',
                                                    'required' => 'required',
                                                ]) !!}
                                                @error('party_name')
                                                    <p class="invalid-feedback1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <h5>Account Group 1<span class="text-danger">*</span></h5>
                                                {!! Form::select('account_group_id', $accountGroups1, null, [
                                                    'id' => 'account_group_id',
                                                    'class' => 'form-control select2',
                                                    'required' => 'required',
                                                ]) !!}
                                                @error('account_group_id')
                                                    <p class="invalid-feedback1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <h5>Account Group 2<span class="text-danger">*</span></h5>
                                                {!! Form::select('account_group_id2', $accountGroups2, null, [
                                                    'id' => 'account_group_id2',
                                                    'class' => 'form-control select2',
                                                    'required' => 'required',
                                                ]) !!}
                                                @error('account_group_id2')
                                                    <p class="invalid-feedback1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <h5>Account Group 3<span class="text-danger">*</span></h5>
                                                {!! Form::select('account_group_id3', $accountGroups3, null, [
                                                    'id' => 'account_group_id3',
                                                    'class' => 'form-control select2',
                                                    'required' => 'required',
                                                ]) !!}
                                                @error('account_group_id3')
                                                    <p class="invalid-feedback1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="from-group">
                                                <div class="text-xs-right bt-1 pt-10">
                                                    <button type="submit" class="btn btn-info">Submit</button>
                                                    <button type="reset" class="btn btn-primary reset_btn">Reset</button>
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
            <div class="col-lg-8 col-md-8 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> LIST Chart Of Account</h6>
                            <!-- <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul> -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <!-- <a href="{{ route('export-party-excel') }}" class="btn btn-success btn-sm">Excel <i
                                            class="fa fa-file-excel-o"></i></a> -->
                                    <a href="{{ route('export-party-pdf') }}" class="btn btn-sm"
                                        style="background-color: #871010;color:white">Download <i
                                            class="fa fa-file-pdf-o"></i></a>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover data-table">
                                            <thead>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>AG</th>
                                                    <th>AG 2</th>
                                                    <th>AG 3</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>AG</th>
                                                    <th>AG 2</th>
                                                    <th>AG 3</th>
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
    <script src="{{ URL::asset('dashboard/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/3.1.3/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/buttons.print.min.js') }}"></script>
    <script src="vendor/datatables/buttons.server-side.js"></script>
    <script type="text/javascript">
        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ URL::to('parties') }}",
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
                        data: 'party_name',
                        name: 'party_name'
                    },
                    {
                        data: 'account_group',
                        name: 'account_group'
                    },
                    {
                        data: 'account_group2',
                        name: 'account_group2'
                    },
                    {
                        data: 'account_group3',
                        name: 'account_group3'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                lengthMenu: [10, 25, 50, 100, 500, 1000],
                // dom: 'Blfrtip',
                // buttons: [{
                //         extend: 'csv',
                //         exportOptions: {
                //             columns: [0, 1, 2, 3, 4, 5]
                //         }
                //     },
                //     {
                //         extend: 'pdf',
                //         exportOptions: {
                //             columns: [0, 1, 2, 3, 4, 5]
                //         }
                //     }
                // ]
            });

            // $('.table-responsive #DataTables_Table_0_wrapper .dt-buttons button').removeClass(
            //     'dt-button buttons-pdf buttons-html5');
            // $('.table-responsive #DataTables_Table_0_wrapper .dt-buttons button').addClass(
            //     'btn btn-primary btn-sm');
        });
    </script>

    <!-- Account Group 2 Selected Value on Edit -->
    <script>
        $(document).ready(function() {
            $('#party_name').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#account_group_id").select2('open');
                }
            });

            $('#account_group_id').change(function(event) {
                var dcn = $(this).val();
                if (dcn) {
                    $('#account_group_id').select2().trigger('select2:close');
                    $('#account_group_id2').select2('open');
                }
            });

            $('#account_group_id2').change(function(event) {
                var dcn = $(this).val();
                if (dcn) {
                    $('#account_group_id').select2().trigger('select2:close');
                    $('#account_group_id3').select2('open');
                }
            });

            $(document).on('click', '.edit_btn', function() {
                var data = $(this).attr('name');
                $('#idd').val(data.split('_')[0]);
                $('#code').val(data.split('_')[1]);
                $('#code1').val(data.split('_')[1]);
                $('#party_name').val(data.split('_')[2]);
                $('#account_group_id').val(data.split('_')[3]).select2();
                $('#group_id').val(data.split('_')[5]);
                var ag2 = data.split('_')[4];
                var ag3 = data.split('_')[5];
                // Append data into 2nd dropdown when selected AG1
                var ag1 = data.split('_')[3];
                $.ajax({
                    url: "{{ URL::to('get-ag1-data') }}?ag1=" + ag1,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        if (response.length > 0) {
                            var option = '';
                            $.each(response, function(i, v) {
                                if (i == 0) {
                                    option +=
                                        `<option value="${v.id}">${v.name}</option>`;
                                } else {
                                    option +=
                                        `<option value="${v.id}">${v.name}</option>`;
                                }
                            });
                            $('#account_group_id2').html(option);
                            $('#account_group_id2').val(ag2).select2();
                        } else {
                            $('#account_group_id2').html(
                                '<option value="" selected>Record Not Found</option>');
                        }
                    }
                });

                var ag2 = parseInt(data.split('_')[4]);
                $.ajax({
                    url: "{{ URL::to('get-ag2-data') }}?ag2=" + ag2,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        if (response.length > 0) {
                            var option = '';
                            $.each(response, function(i, v) {
                                option += `<option value="${v.id}">${v.name}</option>`;
                            });

                            $('#account_group_id3').html(option);
                            $('#account_group_id3').val(ag3).select2();
                        } else {
                            $('#account_group_id3').html(
                                '<option value="" selected>Record Not Found</option>');
                        }
                    }
                });
                // End Here
            });
        });
    </script>

    <!-- Search Account Group 1 & 2 -->
    <script>
        $(document).ready(function() {
            $('#account_group_id').change(function() {
                var ag1 = $(this).val();
                $.ajax({
                    url: "{{ URL::to('get-ag1-data') }}?ag1=" + ag1,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        if (response.length > 0) {
                            var option =
                                `<option value="" selected>Select Account Group 2</option>`;
                            $.each(response, function(i, v) {
                                if (i == 0) {
                                    option +=
                                        `<option value="${v.id}">${v.name}</option>`;
                                } else {
                                    option +=
                                        `<option value="${v.id}">${v.name}</option>`;
                                }

                                $('#account_group_id2').html(option);
                            });
                        } else {
                            $('#account_group_id2').html(
                                '<option value="" selected>Record Not Found</option>');
                            $('#account_group_id3').html(
                                '<option value="" selected>Record Not Found</option>');
                        }
                    }
                });
            });
            $('#account_group_id2').change(function() {
                var ag2 = $(this).val();
                $.ajax({
                    url: "{{ URL::to('get-ag2-data') }}?ag2=" + ag2,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        if (response.length > 0) {
                            var option =
                                `<option value="" selected>Select Account Group 3</option>`;
                            $.each(response, function(i, v) {
                                if (i == 0) {
                                    option +=
                                        `<option value="${v.id}">${v.name}</option>`;
                                } else {
                                    option +=
                                        `<option value="${v.id}">${v.name}</option>`;
                                }

                                $('#account_group_id3').html(option);
                            });
                        } else {
                            $('#account_group_id3').html(
                                '<option value="" selected>Record Not Found</option>');
                        }
                    }
                });
            });
        });
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

    <!-- Parties Remove | Swal Notification-->
    <script>
        // Swal Confirmation
        $(document).ready(function() {
            $(document).on('click', '.remove-account', function() {
                let id = $(this).attr('name');
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Are you sure you want to delete this account?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    confirmButtonColor: '#28A745',
                    cancelButtonText: 'No, cancel!',
                    cancelButtonColor: '#DC3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = `{{ URL::to('parties/destroy/${id}') }}`;
                    }
                });
            });
        });
    </script>
    @include('include.toast-messages')
@stop
