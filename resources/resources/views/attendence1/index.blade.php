@extends('app')
@section('head')
    <title>Employees Attendence</title>
    <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!--  Select 2 library start-->
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
    <!--  Select 2 library end-->
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Employees Attendence
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Attendence</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> ADD EMPLOYEE ATTENDANCE</h6>
                            <!-- <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul> -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">

                                <div class="col">
                                    {!! Form::open([
                                        'url' => 'employees-attendance/add-attendce',
                                        'class' => 'form-horizontal',
                                        'id' => 'attendence-form',
                                        'enctype' => 'multipart/form-data',
                                    ]) !!}
                                    {{-- {!! Form::hidden('idd', null, ['id' => 'idd']) !!}
                                    {!! Form::hidden('shop_id', 2, ['id' => 'shop_id']) !!} --}}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1"> Date<span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::date('date', date('Y-m-d'), [
                                                'id' => 'date',
                                                'class' => 'form-control',
                                                'tabindex' => '1',
                                                'autofocus' => 'autofocus',
                                            ]) !!}

                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Employees <span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::select('employee_id', $employee, null, [
                                                'id' => 'employee_id',
                                                'class' => 'form-control select2 employee',
                                                'tabindex' => '2',
                                            ]) !!}
                                            <span class="text-danger" id="employee_id_err"></span>
                                        </div>
                                       
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Status <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::select('status', 
                                            ['0' => 'Absent', '1' => 'Present', '2' => 'Leave'], 
                                            null, 
                                            [
                                                'id' => 'status',
                                                'class' => 'form-control select2',
                                                'required' => 'required',
                                                'tabindex' => '3',
                                            ]) 
                                        !!}
                                            <span class="text-danger" id="status_err"></span>
                                            @error('status')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Over time (Hours)<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('over_time', null, [
                                                'id' => 'over_time',
                                                'class' => 'form-control',
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                               
                                            ]) !!}
                                            <span class="text-danger" id="over_time_err"></span>
                                            @error('over_time')
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
                                    {!! Form::open([
                                        'url' => 'employees-attendance/update-attendce',
                                        'class' => 'form-horizontal',
                                        'id' => 'update-attendence-form',
                                        'enctype' => 'multipart/form-data',
                                    ]) !!}
                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="table-responsive mb-2">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 20%;">Date</th>
                                                                <th style="width: 30%;">Employee Name</th>
                                                                <th style="width: 30%;">Status</th>
                                                                <th style="width: 20%;">Over time (Hours)</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        {{-- <tfoot>
                                                            <tr>
                                                                <td colspan="2"><strong>Total</strong></td>
                                                                <td class="bg-primary" id="TotalAmount">0</td>
                                                            </tr>
                                                        </tfoot> --}}
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-xs-right bt-1 pt-10 mt-2">
                                        <button type="button" onclick="FormUpdate()" class="btn btn-info">Update</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- Print Record Modal -->
            <!-- modal -->
            <div class="modal hide fade" id="print-record-modal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash text-danger"></i> Print
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="print-receipt-modal-body"></div>
                        <div class="modal-footer text-right">
                            <button type="button" class="btn btn-danger btn-sm text-black"
                                data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
            <!-- End Print Record Modal -->
            {{-- <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: -40px;">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> LIST CUSTOMER INFORMATION</h6>
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
            </div> --}}
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
                ajax: "{{ URL::to('addemployees') }}",
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


            //*************** Start Print Button *******************
            $(document).on('click', '.print_btn', function() {
                var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                myModal.toggle();

                var id = $(this).attr('name');
                var base_url = $('#base_url').val();
                $.ajax({
                    url: "{{ URL::to('employees/print/record') }}",
                    type: 'get',
                    data: {
                        id: id
                    },
                    beforeSend: function(response) {
                        $('#print-receipt-modal-body').html(
                            '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                        );
                    },
                    success: function(response) {
                        if (response != null && response != 0) {
                            $('#print-receipt-modal-body').html(
                                `<object data="${base_url}/resources/upload/employeeprint/${response}" type="application/pdf" width="100%" height="800"></object>`
                            );
                        } else {
                            $('#print-receipt-modal-body').html(
                                '<h2 style="color:red;text-align:center;">Voucher Not Exist</h2>'
                                );
                        }
                    }
                });
            });

            //*************** End Print Button *******************

            //*************** Switch Next Tabs *******************
            $('#date').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                $("#employee_id").select2('open');
                }
            });
            $('#employee_id').change(function(event) {
                var employee_type = $(this).val();
                if (employee_type!=null) {
                    $('#employee_id').select2().trigger('select2:close');
                    $("#status").select2('open');
                }else{
                    $('#employee_id_err').text('The Employee  field is required.')
                }
            });
            $('#status').change(function(event) {
                var employee_type = $(this).val();
                if (employee_type!=null) {
                    $('#status').select2().trigger('select2:close');
                    $("#over_time").focus();
                }else{
                    $('#status_err').text('The Status field is required.')
                }
            });
           
            $('#status').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    if ($(this).val() != null) {

                        $('#status_err').text('');
                    }
                    $("#over_time").focus();
                }
            });
            $('#over_time').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    if ($(this).val() != null) {
                        // $('#over_time_err').text('');
                        $("#over_time").focus();
                    }
                    $('#attendence-form').submit();
                    // $("#status").focus();
                }
            });
        });

        function FormSubmit() {
            $('#attendence-form').submit();
            return true;
        }
        function FormUpdate() {
            $('#update-attendence-form').submit();
            return true;
        }
    </script>
    <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2, .select2-multiple").select2({
            width: "100%"
        });
    </script>
    <script>
     function DeleteRow(row) {
            $(row).remove();
            // TotalGrandAmount();
        }    
    $(document).ready(function() {
            $('.employee').change(function() {
                var employee_id = $(this).val();
                var date = $('#date').val();
                $.ajax({
                    url: "{{ URL::to('employees-attendance/getEmployeeAttendence') }}",
                    type: 'get',
                    data: {
                        employee_id: employee_id,
                        date: date
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.data && response.data.length > 0) {
                            var tableHtml = '';
                            $.each(response.data, function(i, v) {
                                console.log(v.date);
                                tableHtml += `<tr>`;
                                tableHtml += `<td><input type='date' name='date[]' value='${v.date}' class='form-control' /></td>`;
                                tableHtml += `<td>
                                    <input type='hidden' name='employee_id[]' value='${v.employee_id}'/>
                                    <input type='text'  value='${v.get_partyname.code} - ${v.get_partyname.party_name}' class='form-control' readonly="readonly"/>
                                    </td>`;
                                tableHtml += `<td>
                                                <select name='status[]' class='form-control'>
                                                    <option value='0' ${v.status == 0 ? 'selected' : ''}>Absent</option>
                                                    <option value='1' ${v.status == 1 ? 'selected' : ''}>Present</option>
                                                    <option value='2' ${v.status == 2 ? 'selected' : ''}>Leave</option>
                                                </select>
                                            </td>`;
                                            if(v.over_time){
                                                tableHtml += `<td><input type='text' name='over_time[]' id='${v.over_time}' value='${v.over_time}' class='form-control' onkeypress ="return isNumberKeyNoPoint(event)"/></td>`;
                                            }else{
                                                tableHtml += `<td><input type='text' name='over_time[]' id='${v.over_time}' class='form-control' onkeypress ="return isNumberKeyNoPoint(event)"/></td>`;
                                            }
                                
                                tableHtml += `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));">
                                                <i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;

                            });
                            $('#GridTable').html(tableHtml);
                        // TotalGrandAmount();
                        }
                        else {
                            $('#GridTable').html(''); 
                        }
                    },
                        
                });
            });
        });
    </script>
    @include('include.toast-messages')
@stop
