@extends('app')
@section('head')
    <title>Account Group 3</title>
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
                Account Groups 3
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Account Groups 3</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Add New Account Group 3</h6>
                            <!-- <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul> -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'account-group3', 'class' => 'form-horizontal']) !!}
                                    {!! Form::hidden('id', null, ['id' => 'id']) !!}
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group d-none">
                                                <h5>Code <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::text('code1', $codes, ['id' => 'code1', 'class' => 'form-control', 'disabled' => 'disabled']) !!}
                                                    {!! Form::hidden('code', $codes, ['id' => 'code', 'class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                {{-- <h5>Group Id <span class="text-danger">*</span></h5> --}}
                                                <div class="form-group">
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
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Account Group 1<span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::select('account_group1_id', $accountGroups1, null, [
                                                        'id' => 'account_group1_id',
                                                        'class' => 'form-control select2',
                                                        'required' => 'required',
                                                    ]) !!}
                                                    @error('account_group1_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Account Group 2<span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::select('account_group2_id', $accountGroups2, null, [
                                                        'id' => 'account_group2_id',
                                                        'class' => 'form-control select2',
                                                        'required' => 'required',
                                                    ]) !!}
                                                    @error('account_group2_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-xs-right bt-1 pt-10">
                                        <button type="submit" class="btn btn-info">Submit</button>
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
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> LIST ACCOUNT GROUP 3</h6>
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
                                                    <th>Name</th>
                                                    <th>Account Group1</th>
                                                    <th>Account Group2</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>Account Group1</th>
                                                    <th>Account Group2</th>
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
                ajax: "{{ URL::to('account-group3') }}",
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
                        data: 'account_group1',
                        name: 'account_group1'
                    },
                    {
                        data: 'account_group2',
                        name: 'account_group2'
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

    <!-- Account Group 2 Selected Value on Edit -->
    <script>
        $(document).ready(function() {
            $(document).on('click', '.edit_btn', function() {
                var data = $(this).attr('name');
                $('#id').val(data.split('_')[0]);
                $('#code').val(data.split('_')[1]);
                $('#code1').val(data.split('_')[1]);
                $('#name').val(data.split('_')[2]);
                $('#account_group1_id').val(data.split('_')[3]).select2();
                $('#group_id').val(data.split('_')[4]);
                // $('#account_group1_id').attr('disabled','disabled')
                // $('#account_group2_id').attr('disabled','disabled')

                // Append data into 2nd dropdown when selected AG1
                var ag1 = data.split('_')[3];
                $.ajax({
                    url: "{{ URL::to('get-ag1-data') }}?ag1=" + ag1,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        if (response.length > 0) {
                            var option = ``;
                            $.each(response, function(i, v) {
                                if (v.id == data.split('_')[4]) {
                                    option +=
                                        `<option value="${v.id}" selected>${v.name}</option>`;
                                } else {
                                    option +=
                                        `<option value="${v.id}">${v.name}</option>`;
                                }

                                $('#account_group2_id').html(option);
                            });
                        } else {
                            $('#account_group2_id').html(
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
            $('#account_group1_id').change(function() {
                var ag1 = $(this).val();
                $.ajax({
                    url: "{{ URL::to('get-ag1-data') }}?ag1=" + ag1,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        if (response.length > 0) {
                            var option = ``;
                            $.each(response, function(i, v) {
                                if (i == 0) {
                                    option +=
                                        `<option value="${v.id}" selected>${v.name}</option>`;
                                } else {
                                    option +=
                                        `<option value="${v.id}">${v.name}</option>`;
                                }

                                $('#account_group2_id').html(option);
                            });
                        } else {
                            $('#account_group2_id').html(
                                '<option value="" selected>Record Not Found</option>');
                        }
                    }
                });
            });
        });
    </script>


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
                    text: "Are you sure you want to delete this account group?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    confirmButtonColor: '#28A745',
                    cancelButtonText: 'No, cancel!',
                    cancelButtonColor: '#DC3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = `{{ URL::to('account-group3/destroy/${id}') }}`;
                    }
                });
            });
        });
    </script>
    @include('include.toast-messages')
@stop
