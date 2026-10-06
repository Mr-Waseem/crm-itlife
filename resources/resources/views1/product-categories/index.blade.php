@extends('app')
@section('head')
    <title>Product Group</title>
    <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Product Group
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Product Group</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Add New Product Group</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'product-group', 'class' => 'form-horizontal', 'id' => 'product-form']) !!}
                                    {!! Form::hidden('idd', null, ['id' => 'idd']) !!}
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group">
                                                <h5>Product Code <span class="text-danger">*</span></h5>
                                                {!! Form::text('catagory_code', $codes, ['id' => 'catagory_code', 'class' => 'form-control']) !!}
                                                @error('catagory_code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <span class="category_code_err text-danger"></span>
                                            </div>
                                            <div class="form-group">
                                                <h5>Product Group <span class="text-danger">*</span></h5>
                                                {!! Form::text('catagory_name', null, [
                                                    'id' => 'catagory_name',
                                                    'class' => 'form-control',
                                                    'autofocus' => 'autofocus',
                                                ]) !!}
                                                @error('catagory_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <span class="category_name_err text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-xs-right bt-1 pt-10">
                                        <button type="button" class="btn btn-info submit_btn" tabindex="9"
                                            onclick="FormSubmit()">Submit</button>
                                        <button type="reset" class="btn btn-primary reset_btn"
                                            tabindex="10">Reset</button>
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
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> LIST OF PRODUCTS GROUPS</h6>
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
                                                    <th>Code</th>
                                                    <th>Name</th>
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
                ajax: "{{ URL::to('product-group') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'catagory_code',
                        name: 'catagory_code'
                    },
                    {
                        data: 'catagory_name',
                        name: 'catagory_name'
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

    <!-- Products Selected Value on Edit -->
    <script>
        $(document).ready(function() {
            $(document).on('click', '.edit_btn', function() {
                var data = $(this).attr('name');
                $('#idd').val(data.split('_')[0]);
                $('#catagory_code').val(data.split('_')[1]);
                $('#catagory_name').val(data.split('_')[2]);
            });

            $('.reset_btn').click(function() {
                $('#idd').val(null);
                $('#department_id').val(null).select2();
            });
        });

        $('#catagory_code').keyup(function() {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                var catagory_code = $(this).val();
                if (!catagory_code) {
                    $('.catagory_code_err').html('The Code field is required.');
                    $('#catagory_code').focus();
                    return false;
                } else {
                    $('.catagory_code_err').text('');
                    $('#catagory_name').focus();
                }
            }
        });
        $('#catagory_name').keyup(function() {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                var catagory_name = $(this).val();
                if (!catagory_name) {
                    $('.category_name_err').html('The Product Group field is required.');
                    $('#catagory_name').focus();
                    return false;
                } else {
                    $('.catagory_name_err').text('');
                    FormSubmit();
                }
            }
        });

        function FormSubmit() {
            var catagory_code = $('#catagory_code').val();
            var catagory_name = $('#catagory_name').val();

            if (!catagory_code) {
                $('.catagory_code_err').html('The Code field is required.');
                $('#catagory_code').focus();
                return false;
            } else
            if (!catagory_name) {
                $('.category_name_err').html('The Product Group field is required.');
                $('#catagory_name').focus();
                $('.catagory_code_err').text('');
                return false;
            } else {
                $('.catagory_code_err').text('');
                $('.catagory_name_err').text('');
                $('#product-form').submit();
                return true;
            }
        }
    </script>

    @include('include.toast-messages')
@stop
