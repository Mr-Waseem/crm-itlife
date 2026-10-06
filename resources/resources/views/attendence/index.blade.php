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
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
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
                                    {{-- {!! Form::hidden('idd', null, ['id' => 'idd']) !!} --}}
                                    {!! Form::hidden('overtime_type', null, ['id' => 'overtime_type']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    <div class="row">
                                        
                                        <div class="col-lg-2 col-md-3 col-sm-12">
                                            <label for="date"><i class="fa fa-caret-right"></i> Date</label>
                                            {!! Form::date('date1', date('Y-m-d'), [
                                                'id' => 'date1',
                                                'class' => 'form-control',
                                                'autofocus' => 'autofocus',
                                            ]) !!}

                                        </div>
                                       
                                        <div class="col-lg-3 col-md-3 col-sm-12">
                                            <label for="date"><i class="fa fa-caret-right"></i> Warehouse</label>
                                            {!! Form::select('shop_id', $warehouse, null, [
                                                'id' => 'shop_id',
                                                'class' => 'form-control select2 warehouse',
                                                'tabindex' => '2',
                                            ]) !!}
                                            <span class="text-danger" id="shop_id_err"></span>
                                            @error('shop_id')
                                            <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                            
                                        </div>
                                       
                                        <div class="col-lg-4 col-md-3 col-sm-12">
                                            <label for="date"><i class="fa fa-caret-right"></i> Employee</label>
                                            <select class ='form-control select2 employee' name="employee_id" id="employee_id" tabindex = '2'>
                                                <option>Select Employee</option>
                                            </select>
                                            {{-- {!! Form::select('employee_id',null,[
                                                'id' => 'employee_id',
                                                'class' => 'form-control select2 employee',
                                                'tabindex' => '2',
                                            ]) !!} --}}
                                            <span class="text-danger" id="employee_id_err"></span>
                                            @error('employee_id')
                                            <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-lg-2 col-md-3 col-sm-12">
                                            <label for="date"><i class="fa fa-caret-right"></i> Overtime</label>
                                            {!! Form::text('overtimeis', null, [
                                                'id' => 'overtimeis',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) !!}

                                        </div>
                                      
                                      
                                    </div>
                                    <div class="row mt-1 d-none">
                                        <div class="col-lg-2 col-md-3 col-sm-12">
                                            <label for="date"><i class="fa fa-caret-right"></i> Time In</label>
                                            <!-- {!! Form::time('time_in', env('ATTENDANCE_TIME'), [
                                                'id' => 'time_in',
                                                'class' => 'form-control',
                                                'placeholder' => 'Time In',
                                                'tabindex' => '2',
                                            ]) !!} -->
                                            {!! Form::time('time_in', null, [
                                                'id' => 'time_in',
                                                'class' => 'form-control',
                                                'placeholder' => 'Time In',
                                                'tabindex' => '2',
                                            ]) !!}
                                            <span class="text-danger" id="time_in_err"></span>
                                            @error('time_in')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12">
                                            <label for="date"><i class="fa fa-caret-right"></i> Time Out</label>
                                            <!-- {!! Form::time('time_out', env('ATTENDANCE_END_TIME'),[
                                                'id' => 'time_out',
                                                'class' => 'form-control',
                                                'placeholder' => 'Time Out',
                                                'required' => 'required',
                                                'tabindex' => '3',
                                            ]) 
                                             !!} -->
                                             {!! Form::time('time_out', null,[
                                                'id' => 'time_out',
                                                'class' => 'form-control',
                                                'placeholder' => 'Time Out',
                                                'required' => 'required',
                                                'tabindex' => '3',
                                            ]) 
                                             !!}
                                            <span class="text-danger" id="time_out_err"></span>
                                            @error('time_out')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12">
                                            <label for="date"><i class="fa fa-caret-right"></i>Working Hours</label>
                                             {!! Form::text('working_hours', null,[
                                                'id' => 'working_hours',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly'
                                            ]) 
                                             !!}
                                            <span class="text-danger" id="working_hours_err"></span>
                                            @error('working_hours')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12">
                                            <label for="date"><i class="fa fa-caret-right"></i> Status</label>
                                            {!! Form::select('status', $status, null, ['id' => 'status',
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
                                        <div class="col-lg-2 col-md-3 col-sm-12 overtimeInput d-none">
                                            <label for="date"><i class="fa fa-caret-right"></i> Over time (Hours)</label>
                                            {!! Form::text('over_time', null, [
                                                'id' => 'over_time',
                                                'class' => 'form-control',
                                                'readonly' => 'readonly',
                                               
                                            ]) !!}
                                            <span class="text-danger" id="over_time_err"></span>
                                            @error('over_time')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12">
                                            <label for="date"><i class="fa fa-caret-right"></i> Extra production</label>
                                            {!! Form::text('extra_production', null, [
                                                'id' => 'extra_production',
                                                'class' => 'form-control',
                                                'placeholder' => 'Extra production',
                                                'onkeypress' => "return isNumberKeyNoPoint(event)",
                                               
                                            ]) !!}
                                            <span class="text-danger" id="extra_production_err"></span>
                                            @error('extra_production')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                   
                                    
                                    <div class="text-xs-right bt-1 pt-10 mt-2 d-none">
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
                                                                <th>Date</th>
                                                                {{-- <th>Warehouse</th>
                                                                <th>Employee Name</th> --}}
                                                                
                                                                <th>Time In</th>
                                                                <th>Time Out</th>
                                                                <th>Working Hours</th>
                                                                <th style="width: 20%;">Status</th>
                                                                <th>Over time (Hours)</th>
                                                                <th>Extra Production</th>
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

<script>
// document.addEventListener('DOMContentLoaded', function() {
//     // Select all input fields in the table
//     const inputs = document.querySelectorAll('#GridTable input[type="text"]');

//     // Loop through each input and add a keydown event listener
//     inputs.forEach((input, index) => {
//         input.addEventListener('keydown', function(event) {
//             if (event.key === 'Enter') {
//                 event.preventDefault(); // Prevent the default form submission

//                 // Example action: log the input value
//                 console.log('Input value:', input.value);
                
//                 // Focus on the next input if available
//                 if (index < inputs.length - 1) {
//                     inputs[index + 1].focus();
//                 } else {
//                     console.log("Last input reached.");
//                 }
//             }
//         });
//     });
// });

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
            // $(document).on('click', '.edit_btn', function() {
            //     $('#party_name').focus();
            //     var data = $(this).attr('name');
            //     $('#idd').val(data.split('_')[0]);
            //     $('#party_name').val(data.split('_')[1]);
            //     $('#address').val(data.split('_')[2]);
            //     $('#phone').val(data.split('_')[3]);
            //     $('#employee_id').val(data.split('_')[4]);
            //     $('#spous_of').val(data.split('_')[5]);
            //     $('#cnic_no').val(data.split('_')[6]);
            //     $('#status').val(data.split('_')[7]);
            //     $('#blood_relative_mbl').val(data.split('_')[8]);
            //     $('#relationship').val(data.split('_')[9]);
            //     $('#joining_date').val(data.split('_')[10]);
            //     $('#dept').val(data.split('_')[11]).select2();
            //     $('#monthly_salary').val(data.split('_')[12]);
            //     $('#basic_salary').val(data.split('_')[13]);
            //     $('#house_rent').val(data.split('_')[14]);
            //     $('#medical_allowance').val(data.split('_')[15]);
            //     $('#attendance_allowance').val(data.split('_')[16]);
            //     $('#paid_leaves').val(data.split('_')[17]);
            //     $('#travelling_allowance').val(data.split('_')[18]);
            //     $('#referred_by_emp_no').val(data.split('_')[19]).select2();
            //     $('#referred_cnic').val(data.split('_')[20]);
            //     $('#employee_id').val(data.split('_')[21]);
            //     $('#personal_mbl_no').val(data.split('_')[22]);
            //     $('#designation_id').val(data.split('_')[23]).select2();
            //     $('#employee_type_id').val(data.split('_')[24]).select2();
            //     $('#email').val(data.split('_')[25]);
            //     document.getElementById("email").disabled = true;
            //     // $('#email').addClass('readonly');
            //     $(window).scrollTop(0);
            // });

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
             $('#date1').keydown(function(event) {
                if (event.which == 13) {
                    $("#shop_id").select2('open');
                }
           });

        $('#shop_id').change(function() {
            var employee_type = $(this).val();
            if (employee_type) {
                $('#shop_id').select2().trigger('select2:close');
                $("#employee_id").select2('open');
            } else {
                $('#shop_id_err').text('The Dept field is required.');
            }
            });

            //  $('#time_out').keydown(function(event) {
            //     if (event.which == 16) {

            //         // var timein = $('#time_in').val();
            //         // var timeout = $('#time_out').val();
            //         // var workingHours = $('#working_hours').val();
            //         // // alert(timeout);
            //         // if (timein && timeout) {
                     
            //             $("#extra_production").focus();
            //         // }else{
            //         //     $("#status").select2('open');
            //         // }
            //     }
            // });

            // $('#employee_id').change(function() {
            //     var employee_type = $(this).val();
            //     if (employee_type) {
            //         $('#employee_id').select2().trigger('select2:close');
            //         // $("#status").select2('open');
            //         $("#time_in").select();
            //     } else {
            //         $('#employee_id_err').text('The Employee field is required.');
            //     }
            // });

            // $('#time_in').keydown(function(event) {
            //     if (event.which == 13) {
            //         if ($(this).val().trim() !== '') { 
            //             $("#time_out").focus();
            //         } else {
            //             $('#time_in_err').text('');
            //             $('#time_in_err').text('The Time In field is required.');
            //         }
            //     }
            // });




            // $('#time_in').keyup(function(event) {
            //         var timein = $('#time_in').val();
            //         var timeout = $('#time_out').val();
            //         var workingHours = $('#working_hours').val();
            //         if (timein && timeout) {
            //             const startParts = timein.split(':');
            //             const endParts = timeout.split(':');
            //             const startDate = new Date();
            //             startDate.setHours(parseInt(startParts[0]), parseInt(startParts[1]), 0, 0);
            //             const endDate = new Date();
            //             endDate.setHours(parseInt(endParts[0]), parseInt(endParts[1]), 0, 0);
            //             let durationMs = endDate - startDate;
            //             // If the duration is negative, it means the end time is on the next day
            //             if (durationMs < 0) {
            //                 durationMs += 24 * 60 * 60 * 1000; // Add 24 hours in milliseconds
            //             }
            //             const totalHours = durationMs / (1000 * 60 * 60); // Convert to hours

            //             var TotalOverTime = totalHours - workingHours;
            //             // alert(TotalOverTime);
            //             $("#over_time").val(TotalOverTime.toFixed(2));
            //         }
            //         //  else {
            //         //     $('#time_out_err').text('The Time Out field is required.');
            //         // }
                
            // });

            // $('#time_out').keyup(function(event) {
            //         var timein = $('#time_in').val();
            //         var timeout = $('#time_out').val();
            //         var workingHours = $('#working_hours').val();
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

            //             var TotalOverTime = totalHours - workingHours;
            //             // alert(TotalOverTime);
            //             $("#over_time").val(TotalOverTime.toFixed(2));
            //             // $("#status").select2('open');
            //         } 
            //         // else {
            //         //     $('#time_out_err').text('The Time Out field is required.');
            //         // }
            //     // }
            // });

            // $('#time_out').keydown(function(event) {
            //     if (event.which == 13) {
            //         var timein = $('#time_in').val();
            //         var timeout = $('#time_out').val();
            //         var workingHours = $('#working_hours').val();
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

            //             var TotalOverTime = totalHours - workingHours;
            //             // alert(TotalOverTime);
            //             $("#over_time").val(TotalOverTime.toFixed(2));
            //             $("#extra_production").focus();
            //         }else{
            //             $("#status").select2('open');
            //         }
            //     }
            // });


            // $('#over_time').keydown(function(event) {
            //     if (event.which == 13) {
            //         $("#extra_production").focus();
            //     }
            // });

            // $('#extra_production').keydown(function(event) {
            //     if (event.which == 13) {
            //         $('#attendence-form').submit();
            //         // if ($(this).val().trim() !== '') {
                        
            //         // } else {
            //         //     $('#extra_production_err').text('');
            //         //     $('#extra_production_err').text('The Extra Production field is required.');
            //         // }
            //     }
            // });

          //*************** End Switch Next Tabs *******************
        });

        //*************** Form Submit *******************
        function FormSubmit() {
            $('#attendence-form').submit();
            return true;
        }
        function FormUpdate() {
            $('#update-attendence-form').submit();
            return true;
        }

        // let previousValue = $('#status').val();

        function changeStatus(row) {
            var status = $(row).find("td:eq(4)").find('select').val();
            if(status != "Present"){
                // alert("dsd");
                        // const timeInputIn = document.getElementById('time_in');
                        // const timeInputout = document.getElementById('time_out');
                        // // Set the time input value to an empty string
                        // timeInputIn.value = '';
                        // timeInputout.value = '';
                        $(row).find("td:eq('1')").find('input').val('');
                        $(row).find("td:eq('2')").find('input').val('');
                        $(row).find("td:eq('3')").find('input').val('');
                        $(row).find("td:eq('5')").find('input').val('');
                        $(row).find("td:eq('6')").find('input').val('');

                        $(row).find("td:eq(1)").find('input').prop('readonly', true);
                        $(row).find("td:eq(2)").find('input').prop('readonly', true);
                        $(row).find("td:eq(5)").find('input').prop('readonly', true);
                        $(row).find("td:eq(6)").find('input').prop('readonly', true);

                        // $(row).find("td:eq('4')").select2().trigger('select2:close');
                        $(row).find("td:eq(4) select").select2("close");
                        $(row).find("td:eq('5')").find('input').focus();

                        // $('#status').select2().trigger('select2:close');
                    }else{
                        $(row).find("td:eq(1)").find('input').prop('readonly', false);
                        $(row).find("td:eq(2)").find('input').prop('readonly', false);
                        $(row).find("td:eq(5)").find('input').prop('readonly', false);
                        $(row).find("td:eq(6)").find('input').prop('readonly', false);
                        $(row).find("td:eq('1')").find('input').focus();
                    }
                    // $(row).find("td:eq('5')").find('input').focus();
                    // $(row).find("td:eq('6')").find('input').disabled();
                    
                //    alert("exclValue");
                //if i remove, it will not work
                // $(row).find("td:eq('10')").find('input').val(parseFloat(exclValue));


        }

        function changeTime(row, event){
            // alert(event);
            var keycode = (event.keyCode ? event.keyCode : event.which);
            //shift key
                if (keycode == '16') {
                    // alert('Shift key is pressed');
                    $(row).find("td:eq('6')").find('input').focus();
                }
            // if(event.shiftKey) {
            // alert('Shift key is pressed');
            // }else{
            //     alert('Shift key isnot pressed'); 
            // }
            var timein = $(row).find("td:eq(1)").find('input').val();
            var timeout = $(row).find("td:eq(2)").find('input').val();
            var workingHours = $(row).find("td:eq(3)").find('input').val();
            // alert(timein);
            // alert(timeout);
            // alert(workingHours);
            // var overtimeType = $('#overtime_type').val();
            var overtimeType = $('#overtime_type').val();
            // alert(overtimeType);
                    if(overtimeType == 1){
                        if (timein && timeout) {
                            const startParts = timein.split(':');
                            const endParts = timeout.split(':');
                            const startDate = new Date();
                            startDate.setHours(parseInt(startParts[0]), parseInt(startParts[1]), 0, 0);
                            const endDate = new Date();
                            endDate.setHours(parseInt(endParts[0]), parseInt(endParts[1]), 0, 0);
                            let durationMs = endDate - startDate;
                            // If the duration is negative, it means the end time is on the next day
                            if (durationMs < 0) {
                                durationMs += 24 * 60 * 60 * 1000; // Add 24 hours in milliseconds
                            }
                            const totalHours = durationMs / (1000 * 60 * 60); // Convert to hours

                            var TotalOverTime = totalHours - workingHours;
                            // alert(TotalOverTime);
                            $(row).find("td:eq('5')").find('input').val(TotalOverTime);
                            // $("#over_time").val(TotalOverTime.toFixed(2));
                        }
                    }
                    
                    //  else {
                    //     $('#time_out_err').text('The Time Out field is required.');
                    // }
            // alert(timein);

            // document.addEventListener('keydown', function(event) {
          
        // });
        }

        // function NotchangeStatus(row){
        //     alert("d");
        // }

        function changeDate(row, event) {
            if (event.which == 13) {
            $(row).find("td:eq('1')").find('input').focus();
            }
        }

            //  $('#GridFirstDate').keydown(function(event) {
            //     if (event.which == 13) {
            //         $(row).find("td:eq('1')").find('input').focus();
            //         // if ($(this).val().trim() !== '') { 
            //         //     $("#time_out").focus();
            //         // } else {
            //         //     $('#time_in_err').text('');
            //         //     $('#time_in_err').text('The Time In field is required.');
            //         // }
            //     }
            // });
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


        // $(document).ready(function() {
        //     // Initialize Select2
        //     $('#mySelect').select2();

        //     // Variable to hold the previous value
        //     let previousValue = $('#mySelect').val();

        //     // Handle change event
        //     $('#mySelect').on('change', function() {
        //         const currentValue = $(this).val();

        //         // Check if the value has changed
        //         if (currentValue === previousValue) {
        //             console.log('Value has not changed!');
        //             // Your custom logic here
        //         } else {
        //             console.log('Value has changed to: ' + currentValue);
        //             previousValue = currentValue; // Update the previous value
        //         }
        //     });
        // });
    </script>
    <script>
     function DeleteRow(row) {
            $(row).remove();
            // TotalGrandAmount();
        }    
    $(document).ready(function() {
    $('.employee').change(function() {
        $("#GridTable tr").remove(); 
        var employee_id = $(this).val();
        var date1 = $('#date1').val();
        $.ajax({
            url: "{{ URL::to('employees-attendance/getEmployeeAttendence') }}",
            type: 'get',
            data: {
                employee_id: employee_id,
                date1: date1
            },
            dataType: 'json',
            success: function(response) {
                //  alert(response.employee[0].overtime);
                
                // if(response.data.length  > 0){
                //     $('#time_in').val(response.data[0].time_in);
                //     $('#time_out').val(response.data[0].time_out);
                // }else{
                    const timeINenv = "{{ env('ATTENDANCE_TIME') }}";
                    const timeOutenv = "{{ env('ATTENDANCE_END_TIME') }}";
                    // alert(myEnvVar);
                //     $('#time_in').val('09:00');
                //     $('#time_out').val(timeOutenv);
                // }
                $('#working_hours').val(response.employee[0].working_hours);
                $('#overtime_type').val(response.employee[0].over_time);
                if(response.employee[0].over_time == 1){
                    $('#overtimeis').val('YES');
                }else{
                    $('#overtimeis').val('NO');
                }
                
                if(response.employee[0].overtime == 1){
                    $('.overtimeInput').removeClass('d-none');
                }
                if(response.employee[0].overtime != 1){
                    $('.overtimeInput').addClass('d-none');
                }
                    var tableHtml1 = '';
                    // alert(tableHtml1);
                    var shop_id = $('#shop_id').val();
                    var employeeID = $('#employee_id').val();
                    // alert(shop_id);
                    // alert(employee_id);
                    
                    tableHtml1 += `<tr>`;
                    tableHtml1 += `<td><input type='date' name='date[]' id='date' value="${date1}" onkeydown="changeDate($(this).closest('tr'), event);" class='form-control'/></td>
                                        <input type='hidden' name='employee_id[]' id="employee_id" value='${employeeID}'/>
                                        <input type='hidden' name='shop_id[]' id="shop_id" value='${shop_id}'/>`;
                   
                        if(response.data.length  > 0){
                        tableHtml1 += `<td><input type='time' name='time_in[]' id='time_in' value="${response.data[0].time_in}" class='form-control' onkeyup="changeTime($(this).closest('tr'), event);"/></td>`;
                        tableHtml1 += `<td><input type='time' name='time_out[]' id='time_out' value="${response.data[0].time_out}" class='form-control' onkeyup="changeTime($(this).closest('tr'), event);"/></td>`;
                        }else{
                        tableHtml1 += `<td><input type='time' name='time_in[]' id='time_in' value="${timeINenv}" class='form-control' onkeyup="changeTime($(this).closest('tr'), event);"/></td>`;
                        tableHtml1 += `<td><input type='time' name='time_out[]' id='time_out' value="${timeOutenv}" class='form-control' onkeyup="changeTime($(this).closest('tr'), event);"/></td>`;
                        }

                        
                        tableHtml1 += `<td><input type='text' name='working_hours[]' id='working_hours' value="${response.employee[0].working_hours}" class='form-control' readonly/></td>`;
                        
                        
                        var app1 = @json($status);
                                // var option1 = `<option value="${v.status}" selected>${v.status}</option>`;
                                var option1 = ``;
                                $.each(app1, function(i, v1) {
                                    option1 +=`<option value="${v1}">${v1}</option>`;
                                });
                                tableHtml1 +=`<td><select class="form-control grades" name="status[]" id="status" onchange="changeStatus($(this).closest('tr'));">
                                    ${option1}
                                </select>
                                    </td>`;
                        // if(response.employee[0].overtime == 1){
                        // tableHtml1 += `<td><input type='text' name='over_time[]' id='over_time' class='form-control' readonly/></td>`;
                        // }else{
                        tableHtml1 += `<td><input type='text' name='over_time[]' id='over_time' class='form-control' readonly/></td>`;
                        // }
                        tableHtml1 += `<td><input type='text' name='extra_production[]' id='extra_production' class='form-control'/></td>`;
                        tableHtml1 += `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));">
                                        <i class="fa fa-trash"></i></button></td>`;
                        tableHtml1 += `</tr>`;
                        // alert(tableHtml1);
                        $('#GridTable').html(tableHtml1);
                       

                       
                        $(".grades").select2({
                            placeholder: "Select Account",
                            // allowClear: true
                            });


                // if (response.data && response.data.length > 0) {
                    // alert(response.data.length);
                if (response.data.length > 0) {
                    // alert("dd");
                    var tableHtml = '';
                    var shop_id = $('#shop_id').val();
                    var employeeID = $('#employee_id').val();
                    $.each(response.data, function(i, v) {
                        // console.log(v.date);
                        tableHtml += `<tr>`;
                        
                        tableHtml += `<td>
                                <input type='date' name='date[]' value='${v.date}' class='form-control' onkeydown="changeDate($(this).closest('tr'), event);"/>
                                 <input type='hidden' name='employee_id[]' id="employee_id" value='${v.employee_id}'/>
                                  <input type='hidden' name='shop_id[]' id="shop_id" value='${shop_id}'/>
                                </td>`;
                               
                        // tableHtml += `<td>
                        //         <select name='status[]' class='form-control'>
                        //             <option value='0' ${v.status == 0 ? 'selected' : ''}>Absent</option>
                        //             <option value='1' ${v.status == 1 ? 'selected' : ''}>Present</option>
                        //             <option value='2' ${v.status == 2 ? 'selected' : ''}>Leave</option>
                        //         </select>
                        //     </td>`;
                        tableHtml += `<td><input type='time' name='time_in[]' id='${v.time_in}' value='${v.time_in}' class='form-control' onkeyup="changeTime($(this).closest('tr'), event);"/></td>`;
                        tableHtml += `<td><input type='time' name='time_out[]' id='${v.time_out}' value='${v.time_out}' class='form-control' onkeyup="changeTime($(this).closest('tr'), event);"/></td>`;
                        tableHtml += `<td><input type='text' name='working_hours[]' id='working_hours' value='${v.working_hours}' class='form-control' readonly/></td>`;
                        var app = @json($status);
                                var option = `<option value="${v.status}" selected>${v.status}</option>`;
                                $.each(app, function(i, v1) {
                                    option +=`<option value="${v1}">${v1}</option>`;
                                });
                                tableHtml +=`<td><select class="form-control grades" name="status[]" id="status" onchange="changeStatus($(this).closest('tr'));">
                                    ${option}
                                </select>
                                    </td>`;
                        
                        // if(response.employee[0].overtime == 1){
                            if(v.over_time){
                                tableHtml += `<td><input type='text' name='over_time[]' id='over_time' value='${v.over_time}' class='form-control' readonly/></td>`;
                            }
                            else{
                                tableHtml += `<td><input type='text' name='over_time[]' id='over_time' class='form-control' readonly/></td>`;
                            }
                            
                        // }
                        // else{
                        //     if(v.over_time){
                        //         tableHtml += `<td><input type='text' name='over_time[]' id='over_time' value='${v.over_time}' class='form-control' readonly/></td>`;
                        //     }
                        //     else{
                        //         tableHtml += `<td><input type='text' name='over_time[]' id='over_time' class='form-control' readonly/></td>`;
                        //     }
                        // }
                        if(v.extra_production){
                            tableHtml += `<td><input type='text' name='extra_production[]' id='${v.extra_production}' value='${v.extra_production}' class='form-control' onkeypress ="return isNumberKeyNoPoint(event)"/></td>`;
                        }else{
                            tableHtml += `<td><input type='text' name='extra_production[]' id='${v.extra_production}' class='form-control' onkeypress ="return isNumberKeyNoPoint(event)"/></td>`;
                        }
                         tableHtml += `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));">
                                        <i class="fa fa-trash"></i></button></td>`;
                        tableHtml += `</tr>`;

                    });
                    // $('#GridTable').html(tableHtml);
                    $('#GridTable').append(tableHtml);
                    $(".grades").select2({
                            placeholder: "Select Account",
                            // allowClear: true
                            });
                    // $('#GridTable').append(tableHtml);
                    //    TotalGrandAmount();
                   
                }

                const firstInput = document.querySelector('#GridTable input[type="date"]');
                        if (firstInput) {
                            firstInput.focus();
                        }

            document.getElementById('GridTable').addEventListener('keydown', function(event) 
            {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent the default action

                const target = event.target;
                const nextElement = getNextInput(target);
                // if (nextElement) {
                //     if (nextElement.tagName === 'SELECT') {
                //         // alert("d");
                //         // nextElement.size = nextElement.options.length.select2("open"); // Open the dropdown
                //         $(nextElement).select2('open');
                //     } else {
                //         // alert("el");
                //         // $(nextElement).select2().trigger('select2:close');
                //         $(nextElement).focus();
                //         // nextElement.focus(); // Focus on the next element
                //     }
                // }

                if (nextElement) {
                    // Check if the target is a Select2 dropdown
                    if ($(target).hasClass('select2')) {
                        alert('select close and open input');
                        $(target).select2('close'); // Close the Select2 dropdown
                        nextElement.focus(); // Move focus to the next element
                    } else if (nextElement.tagName === 'SELECT') {
                        // alert('select');
                        $(nextElement).select2('open'); // Open the Select2 dropdown
                    } else {
                        // alert('input');
                        $(nextElement).focus(); // Focus on the next element
                        // document.getElementById("date").focus()
                        //  nextElement.focus('[type="date"]:enabled, [type="time"]:enabled, select:enabled, input:enabled');
                    }
                }
            }
        });

        function getNextInput(currentElement) {
            // Get all enabled inputs and selects in the table
            const allInputs = Array.from(document.querySelectorAll('#GridTable input:not([disabled]), #GridTable select:not([disabled])'));
            const currentIndex = allInputs.indexOf(currentElement);

            // Move to the next enabled input or select
            const nextIndex = (currentIndex + 1) % allInputs.length; // Wrap around to the start if at the end
            return allInputs[nextIndex] || null; // Return the next element or null if not found
        }
                //  else {
                //     $('#GridTable').html(''); 
                // }
              },
                   
        });
    });
});

$(document).ready(function(){
    $('.warehouse').change(function(){
       var shop_id=$(this).val();
    //    alert(shop_id);
            $.ajax({
            url: "{{ URL::to('employees-attendance/getDeptEmployee') }}",
            type: 'get',
            data: {
                shop_id: shop_id,
            },
            dataType: 'json',
            success: function(response) {
                var option = '';
                option +=`<option value="">Select Employee</option>`;
                if (response.data != '') {
                    $.each(response.data, function(i, v) {
                        option += `<option value="${v.id}">${v.code+'-'+v.party_name}</option>`;
                    });
                } else {
                    option = `<option value="">No Employee Found</option>`;
                }
                $('#employee_id').html(option).select2();
                $('#employee_id').select2('open');
            }
        });
    });
});
</script>
@include('include.toast-messages')
@stop
