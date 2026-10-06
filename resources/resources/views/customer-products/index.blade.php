@extends('app')
@section('head')
    <title> Customer Products</title>
    <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!--  Select 2 library start-->
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!--  Select 2 library end-->
    <style>
        .show_image>img {
            transition: transform .2s;
            width: 200px;
            height: 200px;
            margin: 0 auto;
        }

        .show_image>img:hover {
            transform: scale(1.5);
        }
    </style>
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Customer Products
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Customer Products</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Add New Customer Product</h6>
                            <!-- <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul> -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open([
                                        'url' => 'customer-products',
                                        'class' => 'form-horizontal',
                                        'id' => 'product-form',
                                        'enctype' => 'multipart/form-data',
                                    ]) !!}
                                    {!! Form::hidden('idd', null, ['id' => 'idd']) !!}
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group">
                                                <h5>Code <span class="text-danger">*</span></h5>
                                                {!! Form::text('product_code', null, [
                                                    'id' => 'product_code',
                                                    'class' => 'form-control',
                                                    'autofocus' => 'autofocus',
                                                ]) !!}
                                                <span class="product_code_err text-danger"></span>
                                                @error('product_code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <h5>Name <span class="text-danger">*</span></h5>
                                                {!! Form::text('product_name', null, [
                                                    'id' => 'product_name',
                                                    'class' => 'form-control',
                                                    'taxindex' => '1',
                                                ]) !!}
                                                <span class="product_name_err text-danger"></span>
                                                @error('product_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group warehouse-box">
                                                <h5>Customer <span class="text-danger">*</span></h5>
                                                {!! Form::select('customer_id', $customer, null, [
                                                    'id' => 'customer_id',
                                                    'class' => 'form-control select2',
                                                    'taxindex' => '2',
                                                ]) !!}
                                                <span class="customer_id_err text-danger"></span>
                                                @error('customer_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group warehouse-box">
                                                <h5>Product <span class="text-danger">*</span></h5>
                                                {!! Form::select('product_id', $products, null, [
                                                    'id' => 'product_id',
                                                    'class' => 'form-control select2',
                                                    'taxindex' => '3',
                                                ]) !!}
                                                <span class="product_id_err text-danger"></span>
                                                @error('product_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-xs-right bt-1 pt-10">
                                        <button type="button" class="btn btn-info submit_btn" tabindex="4"
                                            onclick="FormSubmit()">Submit</button>
                                        <button type="reset" class="btn btn-primary reset_btn"
                                            tabindex="5">Reset</button>
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
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> LIST OF PRODUCTS</h6>
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
                                                    <th>Customer</th>
                                                    <th>Product</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>Customer</th>
                                                    <th>Product</th>
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
                ajax: "{{ URL::to('customer-products') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'product_code',
                        name: 'product_code'
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },

                    {
                        data: 'customer_id',
                        name: 'customer_id'
                    },
                    {
                        data: 'product_id',
                        name: 'product_id'
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
                $('#product_code').val(data.split('_')[1]);
                $('#product_name').val(data.split('_')[2]);
                $('#customer_id').val(data.split('_')[3]).select2();
                $('#product_id').val(data.split('_')[4]).select2();
            });

            $('.reset_btn').click(function() {
                $('#idd').val(null);
                $('#product_code').val(null);
                $('#product_name').val(null);
                $('#customer_id').val(null).select2();
                $('#product_id').val(null).select2();
            });


            // Move to next input field
            $('#product_code').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#product_name").focus();
                }
            });
            $('#product_name').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#customer_id").select2("open");
                }
            });
            $('#customer_id').change(function(event) {
                var customer = $(this).val();
                if (customer) {
                    $('#customer_id').select2().trigger("select2:close");
                    $("#product_id").select2("open");
                }
            });
            $('#product_id').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    FormSubmit();
                }
            });
            // End Here
        });

        function FormSubmit() {
            // alert(1)
            var product_code = $('#product_code').val();
            var product_name = $('#product_name').val();
            var product_id = $('#product_id').val();
            var customer_id = $('#customer_id').val();
            var category_id = $('#category_id').val();

            $('.product_code_err').text('');
            $('.product_name_err').text('');
            $('.customer_id_err').text('');
            $('.product_id_err').text('');

            if (!product_code || product_code == '') {
                // alert()
                $('.product_code_err').html('The Product Code field is required');
                $('#product_code').focus();
                return false;
            } else if (!product_name || product_name == '') {
                $('.product_name_err').html('The Product Name field is required');
                $('#product_name').focus();
                return false;
            } else
            if (!customer_id) {
                $('.customer_id_err').html('The Customer field is required');
                $('#customer_id').select2('open');
                return false;
            } else
            if (!product_id) {
                $('.product_id_err').html('The Product field is required');
                $('#product_id').select2('open');
                return false;
            } else {
                // alert("submit")
                $('#product-form').submit();
                $('.submit_btn').attr('disabled', true);
            }
        }
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

    <!-- Products Remove | Swal Notification-->
    <script>
        // Swal Confirmation
        $(document).ready(function() {
            $(document).on('click', '.remove-product', function() {
                let id = $(this).attr('name');
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Are you sure you want to delete this product?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    confirmButtonColor: '#28A745',
                    cancelButtonText: 'No, cancel!',
                    cancelButtonColor: '#DC3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = `{{ URL::to('customer-products/destroy/${id}') }}`;
                    }
                });
            });
        });
    </script>

    @include('include.toast-messages')
@stop
