@extends('app')
@section('head')
    <title>Banks</title>
    <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Banks
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Banks</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Add New Bank</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'banks', 'class' => 'form-horizontal', 'id' => 'banks-form']) !!}
                                    {!! Form::hidden('idd', null, ['id' => 'idd']) !!}
                                    {!! Form::hidden('role', 'Bank', ['id' => 'role']) !!}
                                    {!! Form::hidden('shop_id', 2, ['id' => 'shop_id']) !!}
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group">
                                                <h5>Code <span class="text-danger">*</span></h5>
                                                {!! Form::text('code', $codes, ['id' => 'code', 'class' => 'form-control']) !!}
                                                @error('code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <span class="code_err text-danger"></span>
                                            </div>
                                            <div class="form-group">
                                                <h5>Name <span class="text-danger">*</span></h5>
                                                {!! Form::text('party_name', null, [
                                                    'id' => 'party_name',
                                                    'class' => 'form-control',
                                                    'autofocus' => 'autofocus',
                                                ]) !!}
                                                @error('party_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <span class="party_name_err text-danger"></span>
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
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> LIST OF BANKS</h6>
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
                ajax: "{{ URL::to('banks') }}",
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
                $('#code').val(data.split('_')[1]);
                $('#party_name').val(data.split('_')[2]);
            });

            $('.reset_btn').click(function() {
                $('#idd').val(null);
                $('#code').val(null).focus();
                $('#party_name').val(null);
            });
        });

        $('#code').keyup(function() {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                var code = $(this).val();
                if (!code) {
                    $('.code_err').html('The Code field is required.');
                    $('#code').focus();
                    return false;
                } else {
                    $('.code_err').text('');
                    $('#party_name').focus();
                }
            }
        });
        $('#party_name').keyup(function() {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                var name = $(this).val();
                if (!name) {
                    $('.party_name_err').html('The Name field is required.');
                    $('#party_name').focus();
                    return false;
                } else {
                    $('.party_name_err').text('');
                    FormSubmit();
                }
            }
        });

        function FormSubmit() {
            var code = $('#code').val();
            var name = $('#party_name').val();

            if (!code) {
                $('.code_err').html('The Code field is required.');
                $('#code').focus();
                return false;
            } else
            if (!name) {
                $('.code_err').text('');
                $('.party_name_err').html('The Name field is required.');
                $('#party_name').focus();
                return false;
            } else {
                $('.code_err').text('');
                $('.party_name_err').text('');
                $('#banks-form').submit();
                return true;
            }
        }
    </script>


    <script>
        // Swal Confirmation
        $(document).ready(function() {
            $(document).on('click', '.remove-bank', function() {
                let id = $(this).attr('id');
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Are you sure you want to delete this record?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    confirmButtonColor: '#28A745',
                    cancelButtonText: 'No, cancel!',
                    cancelButtonColor: '#DC3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = `{{ URL::to('banks/destroy/${id}') }}`;
                    }
                });
            });
        });
    </script>
    @include('include.toast-messages')
@stop
