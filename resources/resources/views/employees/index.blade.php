@extends('app')
@section('head')
    <title>Employees</title>
    <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('dashboard/datatables/jquery.dataTables2.min.css') }}">
    <!--  Select 2 library start-->
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
         
        html {
            scroll-behavior: smooth;
        }
        #report-table tbody tr {
        height: 30px !important;
        }

        #report-table tbody tr {
        height: 30px !important;
        }

        #report-table tbody tr td{
            height: 20px; 
            padding-top: 0px; 
            padding-bottom: 0px;
            font-family: sans-serif;
            font-size: 14px;
            font-weight: bold;
        }
        #report-table thead .thead-row {
            padding: .15rem;
            width: 100%;
            height: 30px !important;
        }
        
        #report-table thead .thead-row,
        #report-table tbody .tfoot-row {
            background-color: #666EE7;
            color: white;
            width: 100%;
        }
    </style>
    <!--  Select 2 library end-->
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Employees</h1>
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
                            <!-- <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul> -->
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
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Employee Id <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('code', null, [
                                                'id' => 'code',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                                'autofocus' => 'autofocus',
                                                'placeholder' => 'Employee Code'
                                            ]) !!}
                                            <span id="code_err" class="text-danger"></span>
                                           
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Name <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('party_name', null, [
                                                'id' => 'party_name',
                                                'class' => 'form-control',
                                                
                                                'required' => 'required',
                                                'placeholder' => 'Employee Name'
                                            ]) !!}
                                            <!-- <span id="party_name_err" class="text-danger"></span> -->
                                            @error('party_name')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-1 d-none">
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
                                            <label class="mt-1">Address <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('address', null, [
                                                'id' => 'address',
                                                'class' => 'form-control',
                                                'tabindex' => '9',
                                            ]) !!}
                                            <span class="text-danger" id="address_err"></span>
                                            @error('address')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
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
                                            <label class="mt-1">Warehouse <span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::select('shop_id', $dept, null, [
                                                'id' => 'shop_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '14',
                                            ]) !!}
                                            <span class="text-danger" id="shop_err"></span>
                                            @error('shop_id')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
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
                                            @error('monthly_salary')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
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
                                            <label class="mt-1">House Rent<span class="text-danger"></span></label>
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
                                            <label class="mt-1">Mobile Allowance<span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('mobile_allowance', null, [
                                                'id' => 'mobile_allowance',
                                                'class' => 'form-control',
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Eidi<span
                                                    class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('eidi', null, [
                                                'id' => 'eidi',
                                                'class' => 'form-control',
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Other Allowance<span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::text('other_allowance', null, [
                                                'id' => 'other_allowance',
                                                'class' => 'form-control',
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Bonus Type<span
                                                    class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::select('bonus_type', ['' => 'Select Type', '1.00' => '1', '1.5' => '1.5', '2.00' => '2'], null, [
                                                'id' => 'bonus_type',
                                                'class' => 'form-control select2',
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
                                        <!-- <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">In Time / Out Time <span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12">
                                            {!! Form::time('time_in',  env('ATTENDANCE_TIME'), [
                                                'id' => 'time_in',
                                                'class' => 'form-control',
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                            @error('time_in')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12">
                                            {!! Form::time('time_out', env('ATTENDANCE_END_TIME'), [
                                                'id' => 'time_out',
                                                'class' => 'form-control',
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                            ]) !!}
                                            @error('time_out')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div> -->
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Working Hours <span
                                                    class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                        {!! Form::text('working_hours', null, [
                                                'id' => 'working_hours',
                                                'class' => 'form-control'
                                            ]) !!}
                                            @error('working_hours')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">OverTime <span
                                                    class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                        {!! Form::select('over_time', array('0' => 'No', '1' => 'Yes'), null, [
                                                'id' => 'over_time',
                                                'class' => 'form-control select2',
                                                'tabindex' => '22',
                                            ]) !!}
                                            @error('over_time')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1"> Status<span class="text-danger"></span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                         
                                            {!! Form::select('employee_status', array('1' => 'Active', '0' => 'De Active'), null, [
                                                'id' => 'employee_status',
                                                'class' => 'form-control select2',
                                                'tabindex' => '22',
                                            ]) !!}
                                            @error('employee_status')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
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
            <!-- <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: -40px;">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> LIST CUSTOMER INFORMATION</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
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
            </div> -->
        </div>

        <div class="row" style="width: 100%; margin-top: -10px;">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-body">
                            <!-- <div class="row">
                                <div class="col">
                                    <div class="row"> -->
                                        <!-- <div class="col-lg-12 col-md-12 col-sm-12 mt-1" id="import-report-table"> -->
                                        <table class="table table-striped table-responsive" id="report-table">
                                            <thead>
                                                <tr class="thead-row">
                                                    <th>Sr.#</th>
                                                    <th style="width: 30%;">Employee Name</th>
                                                    <th>Code</th>
                                                    <th>Phone</th>
                                                    <th style="width: 40%;">Address</th>
                                                    <th style="width: 20%;">Action</th>
                                                    <!-- <th style="width: 20%;">Receive Qty</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($parties as $party)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$party->party_name}}</td>
                                                    <td>{{$party->code}}</td>
                                                    <td>{{$party->phone}}</td>
                                                    <td>{{$party->address}}</td>
                                                    <td><div class="btn-group">
                                                            <button type="button" name="{{$party->id}}" class="btn btn-primary edit_btn btn-sm"><i class="fa fa-pencil"></i></button>&nbsp;
                                                            <a href="javascript:void(0)" name="{{$party->id}}" class="btn btn-danger btn-sm remove-account"><i class="fa fa-trash"></i></a>&nbsp;
                                                            <button href="javascript:void(0)" name="{{$party->id}}" class="btn btn-info print_btn btn-sm"><i class="fa fa-user"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <!-- <tfoot>
                                                <tr>
                                                    <td class="font-weight-bold">Total</td>
                                                    <td colspan="3"></td>
                                                    <td class="font-weight-bold">4</td>
                                                    <td class="font-weight-bold">3</td>
                                                </tr>
                                            </tfoot> -->
                                        </table>
                                    <!-- </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@stop
@section('scripts')

    <script src="{{ URL::asset('dashboard/datatables/report/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript">
          $('#report-table').DataTable({
                "pageLength": 100,
                "ordering": false
            });
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
                var EmployeeID = $(this).attr('name');
                $.ajax({
                        url: `{{ URL::to('employees/edit/record') }}`,
                        type: 'get',
                        dataType: 'json',
                        data:{
                            EmployeeID:EmployeeID,
                        },
                        beforeSend: function(response) {
                            $('#import-report-table').html('<div class="loader"></div>');
                        },
                        success: function(response) {
                            $('#idd').val(response.id);
                            $('#code').val(response.code);
                            $('#party_name').val(response.party_name);
                            $('#address').val(response.address);
                            $('#phone').val(response.phone);
                            $('#employee_id').val(response.employee_id);
                            $('#spous_of').val(response.spous_of);
                            $('#cnic_no').val(response.cnic_no);
                            $('#status').val(response.status);
                            $('#blood_relative_mbl').val(response.blood_relative_mbl);
                            $('#relationship').val(response.relationship);
                            $('#joining_date').val(response.joining_date);
                            $('#shop_id').val(response.shop_id).select2();
                            $('#monthly_salary').val(response.monthly_salary);
                            $('#basic_salary').val(response.basic_salary);
                            $('#house_rent').val(response.house_rent);
                            $('#medical_allowance').val(response.medical_allowance);
                            $('#attendance_allowance').val(response.attendance_allowance);
                            $('#paid_leaves').val(response.paid_leaves);
                            $('#travelling_allowance').val(response.travelling_allowance);
                            $('#referred_by_emp_no').val(response.referred_by_emp_no).select2();
                            $('#referred_cnic').val(response.referred_cnic);
                            $('#employee_id').val(response.employee_id);
                            $('#personal_mbl_no').val(response.personal_mbl_no);
                            $('#designation_id').val(response.designation_id).select2();
                            $('#employee_type_id').val(response.employee_type_id).select2();
                            $('#email').val(response.user.email);
                            // $('#time_in').val(data.split('_')[26]);
                            // $('#time_out').val(data.split('_')[27]);
                            $('#working_hours').val(response.working_hours);
                            $('#employee_status').val(response.employee_status).select2();
                            $('#over_time').val(response.over_time).select2();
                            $('#mobile_allowance').val(response.mobile_allowance);
                            $('#eidi').val(response.eidi);
                            $('#other_allowance').val(response.other_allowance);
                            $('#bonus_type').val(response.employeehistory.bonus_type).select2();
                            document.getElementById("email").disabled = true;
                            $(window).scrollTop(0);
                         
                        }
                    });

                // $('#idd').val(data.split('_')[0]);
                // $('#party_name').val(data.split('_')[1]);
                // $('#address').val(data.split('_')[2]);
                // $('#phone').val(data.split('_')[3]);
                // $('#employee_id').val(data.split('_')[4]);
                // $('#spous_of').val(data.split('_')[5]);
                // $('#cnic_no').val(data.split('_')[6]);
                // $('#status').val(data.split('_')[7]);
                // $('#blood_relative_mbl').val(data.split('_')[8]);
                // $('#relationship').val(data.split('_')[9]);
                // $('#joining_date').val(data.split('_')[10]);
                // $('#shop_id').val(data.split('_')[11]).select2();
                // $('#monthly_salary').val(data.split('_')[12]);
                // $('#basic_salary').val(data.split('_')[13]);
                // $('#house_rent').val(data.split('_')[14]);
                // $('#medical_allowance').val(data.split('_')[15]);
                // $('#attendance_allowance').val(data.split('_')[16]);
                // $('#paid_leaves').val(data.split('_')[17]);
                // $('#travelling_allowance').val(data.split('_')[18]);
                // $('#referred_by_emp_no').val(data.split('_')[19]).select2();
                // $('#referred_cnic').val(data.split('_')[20]);
                // $('#employee_id').val(data.split('_')[21]);
                // $('#personal_mbl_no').val(data.split('_')[22]);
                // $('#designation_id').val(data.split('_')[23]).select2();
                // $('#employee_type_id').val(data.split('_')[24]).select2();
                // $('#email').val(data.split('_')[25]);
                // // $('#time_in').val(data.split('_')[26]);
                // // $('#time_out').val(data.split('_')[27]);
                // $('#working_hours').val(data.split('_')[26]);
                // $('#employee_status').val(data.split('_')[27]).select2();
                // $('#over_time').val(data.split('_')[28]).select2();
                // $('#mobile_allowance').val(data.split('_')[29]);
                // $('#eidi').val(data.split('_')[30]);
                // $('#other_allowance').val(data.split('_')[31]);
                // $('#bonus_type').val(data.split('_')[32]).select2();
                // document.getElementById("email").disabled = true;
                // $(window).scrollTop(0);
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

            $('#code').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    if ($(this).val() === null) {
                        $('#code_err').text('');
                    }
                    $("#party_name").focus();
                }
            });

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
                        // $("#email").focus();
                        $("#designation_id").select2('open');
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
                    $("#shop_id").select2('open');
                }
            });
            $('#shop_id').change(function(event) {
                if ($(this).val() != null) {
                    $('#shop_err').text('');
                }
                $('#shop_id').select2().trigger("select2:close");
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
                    $("#mobile_allowance").focus();
                    // $("#referred_by_emp_no").select2('open');
                }
            });
            $('#mobile_allowance').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#eidi").focus();
                    // $("#referred_by_emp_no").select2('open');
                }
            });
            $('#eidi').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#other_allowance").focus();
                    // $("#referred_by_emp_no").select2('open');
                }
            });
            $('#other_allowance').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // $("#other_allowance").focus();
                    $("#bonus_type").select2('open');
                }
            });
            $('#bonus_type').change(function(event) {
                $('#bonus_type').select2().trigger("select2:close");
                $("#referred_by_emp_no").select2('open');
            });
            $('#referred_by_emp_no').change(function(event) {
                $('#referred_by_emp_no').select2().trigger("select2:close");
                $("#referred_cnic").focus();
            });
            $('#referred_cnic').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#working_hours").focus();
                }
            });
            // $('#time_in').keydown(function(event) {
            //     var keycode = (event.keyCode ? event.keyCode : event.which);
            //     if (keycode == '13') {
            //         $("#time_out").focus();
            //     }
            // });

            
            // $('#time_out').keydown(function(event) {
            //     var keycode = (event.keyCode ? event.keyCode : event.which);
            //     if (keycode == '13') {
            //         $("#overtime").select2('open');
            //     }
            // });

               $('#working_hours').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#over_time").select2('open');
                }
            });

            $('#over_time').change(function(event) {
                if ($(this).val() != null) {
                    // $('#shop_err').text('');
                }
                $('#over_time').select2().trigger("select2:close");
                $("#employee_status").select2('open');
            });

            // $('#time_in').keyup(function(event) {
            //         var timein = $('#time_in').val();
            //         var timeout = $('#time_out').val();
            //         // alert(timeout);
            //         if (timein && timeout) {
            //             const startParts = timein.split(':');
            //             const endParts = timeout.split(':');
            //             // Create Date objects for today with the specified times
            //             const startDate = new Date();
            //             startDate.setHours(parseInt(startParts[0]), parseInt(startParts[1]), 0, 0);
            //             const endDate = new Date();
            //             endDate.setHours(parseInt(endParts[0]), parseInt(endParts[1]), 0, 0);
            //             // Calculate the duration in milliseconds
            //             let durationMs = endDate - startDate;
            //             // If the duration is negative, it means the end time is on the next day
            //             if (durationMs < 0) {
            //                 durationMs += 24 * 60 * 60 * 1000; // Add 24 hours in milliseconds
            //             }
            //             const totalHours = durationMs / (1000 * 60 * 60); // Convert to hours

            //             // alert();
            //             $("#working_hours").val(totalHours);
            //         } else {
            //             $('#time_out_err').text('The Time Out field is required.');
            //         }
            //     // }
            // });

            // $('#time_out').keyup(function(event) {
            //         var timein = $('#time_in').val();
            //         var timeout = $('#time_out').val();
            //         // alert(timeout);
            //         if (timein && timeout) {
            //             const startParts = timein.split(':');
            //             const endParts = timeout.split(':');
            //             // Create Date objects for today with the specified times
            //             const startDate = new Date();
            //             startDate.setHours(parseInt(startParts[0]), parseInt(startParts[1]), 0, 0);
            //             const endDate = new Date();
            //             endDate.setHours(parseInt(endParts[0]), parseInt(endParts[1]), 0, 0);
            //             // Calculate the duration in milliseconds
            //             let durationMs = endDate - startDate;
            //             // If the duration is negative, it means the end time is on the next day
            //             if (durationMs < 0) {
            //                 durationMs += 24 * 60 * 60 * 1000; // Add 24 hours in milliseconds
            //             }
            //             const totalHours = durationMs / (1000 * 60 * 60); // Convert to hours

            //             // alert();
            //             $("#working_hours").val(totalHours);
            //         } else {
            //             $('#time_out_err').text('The Time Out field is required.');
            //         }
            //     // }
            // });

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
