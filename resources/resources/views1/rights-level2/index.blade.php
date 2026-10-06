@extends('app')
@section('head')
    <title>Rights Level 2</title>
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
                Rights Level 2
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Rights Level 2</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Add New Rights Level 2</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'right-level2', 'class' => 'form-horizontal', 'id' => 'rights-level2-form']) !!}
                                    {!! Form::hidden('idd', null, ['id' => 'idd']) !!}
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group">
                                                <h5>Code <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::text('code', $codes, [
                                                        'id' => 'code',
                                                        'class' => 'form-control',
                                                        'tabindex' => '0',
                                                    ]) !!}
                                                    <span class="text-danger code_err"></span>
                                                    @error('code')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Title <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::text('title', null, [
                                                        'id' => 'title',
                                                        'class' => 'form-control',
                                                        'required' => 'required',
                                                        'tabindex' => '1',
                                                        'autofocus' => 'autofocus',
                                                    ]) !!}
                                                    <span class="text-danger title_err"></span>
                                                    @error('title')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Right Level 1 <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::select('right_level1_id', $RightsLevel1, null, [
                                                        'id' => 'right_level1_id',
                                                        'class' => 'form-control select2',
                                                        'required' => 'required',
                                                        'tabindex' => '2',
                                                    ]) !!}
                                                    <span class="text-danger right_level1_id_err"></span>
                                                    @error('right_level1_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>URL Link <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::text('url', null, [
                                                        'id' => 'url',
                                                        'class' => 'form-control',
                                                        'required' => 'required',
                                                        'tabindex' => '3',
                                                    ]) !!}
                                                    <span class="text-danger url_err"></span>
                                                    @error('url')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <h5>Status <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::select('status', $status, null, [
                                                        'id' => 'status',
                                                        'class' => 'form-control',
                                                        'required' => 'required',
                                                        'tabindex' => '2',
                                                    ]) !!}
                                                    <span class="text-danger url_err"></span>
                                                    @error('url')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-xs-right bt-1 pt-10">
                                        <button type="button" class="btn btn-info submit_btn">Submit</button>
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
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> LIST OF RIGHTS LEVEL 2</h6>
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
                                                    <th>Title</th>
                                                    <th>Right Level 1</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Code</th>
                                                    <th>Title</th>
                                                    <th>Right Level 1</th>
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
                ajax: "{{ URL::to('right-level2') }}",
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
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'right_level1',
                        name: 'right_level1'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.submit_btn').click(function() {
                $('.code_err').html('');
                $('.title_err').html('');
                $('.right_level1_id_err').html('');
                $('.url_err').html('');


                let code = $('#code').val();
                let title = $('#title').val();
                let right_level1_id = $('#right_level1_id').val();
                let url = $('#url').val();


                if (!code) {
                    $('#code').focus();
                    $('.code_err').html('The Code field is required.');
                } else
                if (!title) {
                    $('#title').focus();
                    $('.title_err').html('The Title field is required.');
                } else
                if (!right_level1_id) {
                    $('#right_level1_id').select2('open');
                    $('.right_level1_id_err').html('The Right Level 1 field is required.');
                } else {
                    $('#rights-level2-form').submit();
                }
            });
        });
    </script>

    <!-- Update Right Level 1 -->
    <script>
        $(document).ready(function() {
            $(document).on('click', '.edit_btn', function() {
                var data = $(this).attr('name');
                var id = data.split('_')[0];
                var code = data.split('_')[1];
                var title = data.split('_')[2];
                var right_level1_id = data.split('_')[3];
                var url = data.split('_')[4];
                var status = data.split('_')[5];
                $('#idd').val(id);
                $('#code').val(code);
                $('#title').val(title);
                $('#right_level1_id').val(right_level1_id).select2();
                $('#url').val(url);
                $('#status').val(status).select2();
            });
            $('#reset_btn').click(function() {
                $('#idd').val(null);
                $('#code').val(null);
                $('#title').val(null).focus();
                $('#right_level1_id').val(null).select2();
                $('#url').val(null);
                $('#status').val(null);
            });
        });
    </script>

    <!-- Right Level 1 Remove | Swal Notification-->
    <script>
        // Swal Confirmation
        $(document).ready(function() {
            $(document).on('click', '.remove-account', function() {
                let id = $(this).attr('name');
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Are you sure you want to delete this level?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    confirmButtonColor: '#28A745',
                    cancelButtonText: 'No, cancel!',
                    cancelButtonColor: '#DC3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = `{{ URL::to('right-level2/destroy/${id}') }}`;
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

    @include('include.toast-messages')
@stop
