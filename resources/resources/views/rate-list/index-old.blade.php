@extends('app')
@section('head')
    <title>Rate List</title>
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
                Rate List
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Rate List</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Add New Rate</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'rate-list', 'class' => 'form-horizontal']) !!}
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group">
                                                <h5>Product <span class="text-danger">*</span></h5>
                                                {!! Form::select('pro_id', $products, null, [
                                                    'id' => 'pro_id',
                                                    'class' => 'form-control select2',
                                                    'autofocus' => 'autofocus',
                                                    'required' => 'required',
                                                ]) !!}
                                                @error('pro_id')
                                                    <font color="red">{{ $message }}</font>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <h5>Previous Rate <span class="text-danger">*</span></h5>
                                                {!! Form::text('previous_rate', null, [
                                                    'id' => 'previous_rate',
                                                    'class' => 'form-control',
                                                    'required' => 'required',
                                                ]) !!}
                                                @error('previous_rate')
                                                    <font color="red">{{ $message }}</font>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <h5>New Rate <span class="text-danger">*</span></h5>
                                                {!! Form::text('new_rate', null, [
                                                    'id' => 'new_rate',
                                                    'class' => 'form-control',
                                                    'required' => 'required',
                                                ]) !!}
                                                @error('new_rate')
                                                    <font color="red">{{ $message }}</font>
                                                @enderror
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
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> RATE LIST</h6>
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
                                                    <th>Product.Name</th>
                                                    <th>Previous.Rate</th>
                                                    <th>New.Rate</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Product.Name</th>
                                                    <th>Previous.Rate</th>
                                                    <th>New.Rate</th>
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
                ajax: "{{ URL::to('rate-list') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'pro_id',
                        name: 'pro_id'
                    },
                    {
                        data: 'previous_rate',
                        name: 'previous_rate'
                    },
                    {
                        data: 'new_rate',
                        name: 'new_rate'
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

    <!-- Update AG1 -->
    <script>
        $(document).ready(function() {
            $(document).on('click', '.edit_btn', function() {
                var data = $(this).attr('name');
                var id = data.split('_')[0];
                var code = data.split('_')[1];
                var name = data.split('_')[2];
                $('#idd').val(id);
                $('#code').val(code);
                $('#code1').val(code);
                $('#name').val(name);
            });
            $('#reset_btn').click(function() {
                $('#idd').val(null);
                $('#code').val(null);
                $('#code1').val(null);
                $('#name').val(null);
            });
        });
    </script>


    <!-- Rate Remove | Swal Notification-->
    <script>
        // Swal Confirmation
        $(document).ready(function() {
            $(document).on('click', '.remove-rate', function() {
                let id = $(this).attr('name');
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Are you sure you want to delete this rate?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    confirmButtonColor: '#28A745',
                    cancelButtonText: 'No, cancel!',
                    cancelButtonColor: '#DC3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = `{{ URL::to('rate-list/destroy/${id}') }}`;
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
