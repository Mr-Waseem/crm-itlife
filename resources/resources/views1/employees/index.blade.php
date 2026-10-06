@extends('app')
@section('head')
    <title>Employees</title>
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
                Employees
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Employees</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> ADD EMPLOYEE INFORMATION</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">

                                <div class="col">
                                    {!! Form::open([
                                        'url' => 'addemployees',
                                        'class' => 'form-horizontal',
                                        'id' => 'employee-form',
                                        'enctype' => 'multipart/form-data',
                                    ]) !!}
                                    {!! Form::hidden('idd', null, ['id' => 'idd']) !!}
                                    {!! Form::hidden('shop_id', 2, ['id' => 'shop_id']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Employee Id <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('employee_id', $codes, [
                                                'id' => 'employee_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Employee ID',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            <span id="employee_id_err" class="text-danger"></span>
                                            @error('employee_id')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Name <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('party_name', null, [
                                                'id' => 'party_name',
                                                'class' => 'form-control',
                                                'autofocus' => 'autofocus',
                                                'required' => 'required',
                                                'placeholder' => 'Employee Name',
                                                'tabindex' => '1',
                                            ]) !!}
                                            <span id="party_name_err" class="text-danger"></span>
                                            @error('party_name')
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
                                                'tabindex' => '2',
                                            ]) !!}
                                            <span class="text-danger" id="email_err"></span>
                                            @error('email')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 d-none">
                                            <label class="mt-1">Password <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 d-none">
                                            {!! Form::password('password', [
                                                'id' => 'password',
                                                'class' => 'form-control',
                                                'tabindex' => '3',
                                            ]) !!}
                                            <span class="text-danger" id="password_err"></span>
                                            @error('password')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Designation <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::select('designation_id',$designations, null, [
                                                'id' => 'designation_id',
                                                'class' => 'form-control select2',
                                                'required' => 'required',
                                                'tabindex' => '4',
                                            ]) !!}
                                            <span class="text-danger" id="designation_err"></span>
                                            @error('designation_id')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Employee Types <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::select('employee_type_id',$emp_types, null, [
                                                'id' => 'employee_type_id',
                                                'class' => 'form-control select2',
                                                'required' => 'required',
                                                'tabindex' => '5',
                                            ]) !!}
                                            <span class="text-danger" id="employee_type_err"></span>
                                            @error('employee_type_id')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 mt-1">
                                            <label class="mt-1">Phone <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-1">
                                            {!! Form::text('phone', null, [
                                                'id' => 'phone',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                                'tabindex' => '6',
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                            <span class="text-danger" id="phone_err"></span>
                                            @error('phone')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 mt-1">
                                            <label class="mt-1">Personal Mobile <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-1">
                                            {!! Form::text('personal_mbl_no', null, [
                                                'id' => 'personal_mbl_no',
                                                'class' => 'form-control',
                                                'tabindex' => '7',
                                            ]) !!}
                                            <span class="text-danger" id="personal_mbl_no_err"></span>
                                            @error('personal_mbl_no')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1"> Cnic<span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('cnic_no', null, [
                                                'id' => 'cnic_no',
                                                'class' => 'form-control',
                                                'tabindex' => '8',
                                            ]) !!}
                                            <span class="text-danger" id="cnic_no_err"></span>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Address <span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('address', null, [
                                                'id' => 'address',
                                                'class' => 'form-control',
                                                'tabindex' => '9',
                                            ]) !!}
                                            <span class="text-danger" id="address_err"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Relationship <span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('relationship', null, [
                                                'id' => 'relationship',
                                                'class' => 'form-control',
                                                'tabindex' => '10',
                                            ]) !!}

                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Spous Of <span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('spous_of', null, [
                                                'id' => 'spous_of',
                                                'class' => 'form-control',
                                                'tabindex' => '11',
                                            ]) !!}

                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1"> Blood Relative Mobile<span
                                                    class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('blood_relative_mbl', null, [
                                                'id' => 'blood_relative_mbl',
                                                'class' => 'form-control',
                                                'tabindex' => '12',
                                            ]) !!}

                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1"> Joining Date<span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::date('joining_date', date('Y-m-d'), [
                                                'id' => 'joining_date',
                                                'class' => 'form-control',
                                                'tabindex' => '13',
                                            ]) !!}

                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Dept <span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::select('dept', $dept, null, [
                                                'id' => 'dept',
                                                'class' => 'form-control select2',
                                                'tabindex' => '14',
                                            ]) !!}
                                            <span class="text-danger" id="dept_err"></span>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1"> Monthly Salary<span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('monthly_salary', null, [
                                                'id' => 'monthly_salary',
                                                'class' => 'form-control',
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                            ]) !!}

                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Basics Salary <span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('basic_salary', null, [
                                                'id' => 'basic_salary',
                                                'class' => 'form-control',
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1"> House Rent<span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('house_rent', null, [
                                                'id' => 'house_rent',
                                                'class' => 'form-control',
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">medical allowance <span
                                                    class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('medical_allowance', null, [
                                                'id' => 'medical_allowance',
                                                'class' => 'form-control',
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1"> Attendance Allowance<span
                                                    class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('attendance_allowance', null, [
                                                'id' => 'attendance_allowance',
                                                'class' => 'form-control',
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Paid Leaves <span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('paid_leaves', null, [
                                                'id' => 'paid_leaves',
                                                'class' => 'form-control',
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1"> Travelling Allowance<span
                                                    class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('travelling_allowance', null, [
                                                'id' => 'travelling_allowance',
                                                'class' => 'form-control',
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Referred by Emp no <span
                                                    class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::select('referred_by_emp_no', $employee, null, [
                                                'id' => 'referred_by_emp_no',
                                                'class' => 'form-control select2',
                                                'tabindex' => '22',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1"> Referred Cnic<span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('referred_cnic', null, [
                                                'id' => 'referred_cnic',
                                                'class' => 'form-control',
                                                'tabindex' => '23',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Signature<span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::file('signature', null, [
                                                'id' => 'signature',
                                                'class' => 'form-control',
                                                'tabindex' => '24',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1"> Cnic Front<span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::file('cnic_front_img', null, [
                                                'id' => 'cnic_front_img',
                                                'class' => 'form-control',
                                                'tabindex' => '25',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1"> Cnic Back<span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::file('cnic_back_img', null, [
                                                'id' => 'cnic_back_img',
                                                'class' => 'form-control',
                                                'tabindex' => '26',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Current Picture <span
                                                    class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::file('current_picture', null, [
                                                'id' => 'current_picture',
                                                'class' => 'form-control',
                                                'tabindex' => '27',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1"> Police Report<span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::file('police_report', null, [
                                                'id' => 'police_report',
                                                'class' => 'form-control',
                                                'tabindex' => '28',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Academic File <span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::file('academic_file', null, [
                                                'id' => 'academic_file',
                                                'class' => 'form-control',
                                                'tabindex' => '29',
                                            ]) !!}
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
            <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: -40px;">
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

            //*************** End Reset Button *******************
            //*************** Edit Button *******************
            $(document).on('click', '.edit_btn', function() {
                $('#party_name').focus();
                var data = $(this).attr('name');
                $('#idd').val(data.split('_')[0]);
                $('#party_name').val(data.split('_')[1]);
                $('#address').val(data.split('_')[2]);
                $('#phone').val(data.split('_')[3]);
                $('#employee_id').val(data.split('_')[4]);
                $('#spous_of').val(data.split('_')[5]);
                $('#cnic_no').val(data.split('_')[6]);
                $('#status').val(data.split('_')[7]);
                $('#blood_relative_mbl').val(data.split('_')[8]);
                $('#relationship').val(data.split('_')[9]);
                $('#joining_date').val(data.split('_')[10]);
                $('#dept').val(data.split('_')[11]).select2();
                $('#monthly_salary').val(data.split('_')[12]);
                $('#basic_salary').val(data.split('_')[13]);
                $('#house_rent').val(data.split('_')[14]);
                $('#medical_allowance').val(data.split('_')[15]);
                $('#attendance_allowance').val(data.split('_')[16]);
                $('#paid_leaves').val(data.split('_')[17]);
                $('#travelling_allowance').val(data.split('_')[18]);
                $('#referred_by_emp_no').val(data.split('_')[19]).select2();
                $('#referred_cnic').val(data.split('_')[20]);
                $('#employee_id').val(data.split('_')[21]);
                $('#personal_mbl_no').val(data.split('_')[22]);
                $('#designation_id').val(data.split('_')[23]).select2();
                $('#employee_type_id').val(data.split('_')[24]).select2();
                $('#email').val(data.split('_')[25]);
                document.getElementById("email").disabled = true;
                // $('#email').addClass('readonly');
                $(window).scrollTop(0);
            });

            //*************** End Edit Button *******************

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
            $('#party_name').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    if ($(this).val() != null) {
                        $('#party_name_err').text('');
                    }
                    var idd = $('#idd').val();
                    if(idd){
                        $("#designation_id").select2('open');
                    }else{
                        $("#email").focus();
                    }
                    // alert(idd)
                    
                }
            });
            $('#email').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    if ($(this).val() != null) {
                        $('#email_err').text('');
                    }
                    $("#designation_id").select2('open');
                }
            });
            $('#designation_id').change(function(event) {
                var designation = $(this).val();
                if (designation!=null) {
                    $('#designation_id').select2().trigger('select2:close');
                    $("#employee_type_id").select2('open');
                }else{
                    $('#designation_err').text('The Designation field is required.')
                }
            });
            $('#employee_type_id').change(function(event) {
                var employee_type = $(this).val();
                if (employee_type!=null) {
                    $('#employee_type_id').select2().trigger('select2:close');
                    $("#phone").focus();
                }else{
                    $('#employee_type_err').text('The Employee Type field is required.')
                }
            });
            $('#phone').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    if ($(this).val() != null) {
                        $('#phone_err').text('');
                    }
                    $("#personal_mbl_no").focus();
                }
            });
            $('#personal_mbl_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    if ($(this).val() != null) {
                        $('#personal_mbl_no_err').text('');
                    }
                    $("#cnic_no").focus();
                }
            });
            $('#cnic_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    if ($(this).val() != null) {
                        $('#cnic_no_err').text('');
                    }
                    $("#address").focus();
                }
            });
            $('#address').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // if ($(this).val() != null) {
                    //     $('#city_err').text('');
                    // }
                    $("#relationship").focus();
                }
            });
            $('#relationship').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // if ($(this).val() != null) {
                    //     $('#ntn_err').text('');
                    // }
                    $("#spous_of").focus();
                }
            });
            $('#spous_of').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // if ($(this).val() != null) {
                    //     $('#ntn_err').text('');
                    // }
                    $("#blood_relative_mbl").focus();
                }
            });
            $('#blood_relative_mbl').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // if ($(this).val() != null) {
                    //     $('#strn_err').text('');
                    // }
                    $("#joining_date").focus();
                }
            });
            $('#joining_date').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // if ($(this).val() != null) {
                    //     $('#strn_err').text('');
                    // }
                    $("#dept").select2('open');
                }
            });
            $('#dept').change(function(event) {
                if ($(this).val() != null) {
                    $('#dept_err').text('');
                }
                $('#dept').select2().trigger("select2:close");
                $("#monthly_salary").focus();
            });
            $('#monthly_salary').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#basic_salary").focus();
                }
            });
            $('#basic_salary').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#house_rent").focus();
                }
            });
            $('#house_rent').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#medical_allowance").focus();
                }
            });
            $('#medical_allowance').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#attendance_allowance").focus();
                }
            });
            $('#attendance_allowance').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#paid_leaves").focus();
                }
            });
            $('#paid_leaves').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#travelling_allowance").focus();
                }
            });
            $('#travelling_allowance').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#referred_by_emp_no").select2('open');
                }
            });
            $('#referred_by_emp_no').change(function(event) {
                $('#referred_by_emp_no').select2().trigger("select2:close");
                $("#referred_cnic").focus();
            });
            $('#referred_cnic').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#signature").focus();
                }
            });
            $('#signature').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#cnic_front_img").focus();
                }
            });
            $('#cnic_front_img').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#cnic_back_img").focus();
                }
            });
            $('#cnic_back_img').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#current_picture").focus();
                }
            });
            $('#current_picture').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#police_report").focus();
                }
            });
            $('#police_report').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#academic_file").focus();
                }
            });
            //*************** End Switch Next Tabs *******************
        });

        //*************** Form Submit *******************
        function FormSubmit() {
            $('#employee-form').submit();
            return true;
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

    <!-- Employee Remove | Swal Notification-->
    <script>
        // Swal Confirmation
        $(document).ready(function() {
            $(document).on('click', '.remove-account', function() {
                let id = $(this).attr('name');
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Are you sure you want to delete this employee?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    confirmButtonColor: '#28A745',
                    cancelButtonText: 'No, cancel!',
                    cancelButtonColor: '#DC3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = `{{ URL::to('employees/destroy/${id}') }}`;
                    }
                });
            });
        });
    </script>

    @include('include.toast-messages')
@stop
