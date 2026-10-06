@extends('app')
@section('head')
    <title>PACKING REPORT</title>
    <!-- {{-- <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"> --}} -->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/datatables/jquery.dataTables2.min.css') }}">
     <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
     <!-- <link href="{{ URL::asset('dashboard/date-range-picker/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" /> -->
     <link href="{{ URL::asset('dashboard/date-range-picker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" /> -->
    <link href="{{ URL::asset('dashboard/date-range-picker/css/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        @media only screen and (min-width: 768px) {
            .report_type_fields {
                margin-top: 40px;
            }
        }

        @media only screen and (max-width: 768px) {
            .report_type_fields {
                margin-top: 10px;
            }
        }

        /* Loader */
        .loader {
            margin: 0px auto;
            border: 7px solid #f3f3f3;
            border-radius: 50%;
            border-top: 7px solid #3498db;
            border-right: 7px solid green;
            border-bottom: 7px solid red;
            width: 50px;
            height: 50px;
            -webkit-animation: spin 2s linear infinite;
            /* Safari */
            animation: spin 2s linear infinite;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* End Loader */

        .warehouse-name {
            background-color: #C0EDF1;
            border-left: 5px solid #58D0DA;
            padding: .5rem;
            color: black;
            font-size: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        #report-table thead .thead-row {
            padding: .15rem;
        }

        #report-table thead .thead-row,
        #report-table tbody .tfoot-row {
            background-color: #666EE7;
            color: white;
        }

        #report-table tbody .thead-row th,
        #report-table tbody .tbody-row td,
        #report-table tbody .tfoot-row td {
            border: 1px solid white;
            padding: .5rem;
        }
    </style>
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>PACKING REPORT</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">PACKING REPORT</a></li>
            </ol>
        </section>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <!-- <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>&nbsp; STOCK REPORT</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div> -->
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'javascript:void(0);', 'class' => 'form-horizontal', 'id' => 'stock-ledger-report']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 col-sm-12 mt-1">
                                            <label for="date"><i class="fa fa-calendar-o"></i> As On</label>
                                            {!! Form::text('date', date('d/m/Y') . ' - ' . date('d/m/Y'), [
                                                'id' => 'date',
                                                'class' => 'form-control',
                                                'tabindex' => '0',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-1">
                                            <label for="employee_id">Employee Name <i class="fa fa-home"></i></label>
                                            {!! Form::select('employee_id', $employee, null, [
                                                'id' => 'employee_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('employee_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger employee_id_err"></span>
                                        </div>
                                        <div class="col-lg-5 col-md-4 col-sm-12 mt-1">
                                            <label for="product_id">Product <i class="fa fa-th-list"></i></label>
                                            {!! Form::select('product_id', $products, null, [
                                                'id' => 'product_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                            ]) !!}

                                            @error('product_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger product_id_err"></span>
                                        </div>
                                        <!-- <div class="col-lg-4 col-md-4 col-sm-12 mt-1 d-none">
                                            <label for="product_id">Product <i class="fa fa-th-list"></i></label>
                                            
                                            @if(Auth::User()->role == "Admin")
                                            1
                                            {!! Form::select('product_id', $products, null, [
                                                'id' => 'product_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                            ]) !!}
                                            @else
                                            2
                                            {!! Form::select('product_id', $SingleWarehouseproducts, null, [
                                                'id' => 'product_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                            ]) !!}
                                            @endif
                                            @error('product_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger product_id_err"></span>
                                        </div> -->
                                        <div class="col-lg-4 col-md-8 col-sm-12 report_type_fields">
                                            <input name="report_type" type="radio" class="with-gap report_type"
                                                id="report_type1" value="summary" checked>
                                            <label for="report_type1">Summary</label>

                                            <input name="report_type" type="radio" class="with-gap report_type"
                                                id="report_type2" value="detailed">
                                            <label for="report_type2">Detailed</label>
                                        </div>
                                       
                                        
                                       
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <br />
                                            <button class="btn btn-primary load_report_btn" type="button">Load Report</button>
                                            <button class="btn btn-primary btn-md print_record_btn" type="button"><i class="fa fa-print" style="color: white;"></i></button>
                                            <!-- <button class="btn btn-secondary reset-btn" type="reset">Reset</button> -->
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        
        <div class="row" style="margin-top: -10px;">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <!-- <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i>&nbsp; Report</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div> -->
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-1" id="report-section"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        
    </div>

    <!-- Print Record Modal -->
    <div class="modal hide fade" id="print-record-modals" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash text-danger"></i> Print</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="print-receipt-modal-body"></div>
                    <div class="modal-footer text-right">
                        <button type="button" class="btn btn-danger btn-sm text-black" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
    </div>
        <!-- End Print Record Modal -->
@stop
@section('scripts')
    <!-- Searchable Select2 -->
    <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2, .select2-multiple").select2({
            width: "100%"
        });
    </script>
    <!-- End Searchable Select2 -->
    <!-- Toastr Message Script -->
    <script src="{{ URL::asset('dashboard/toastr/toastr.min.js') }}"></script>
    {!! Toastr::message() !!}
    <!-- End Toastr Message Script -->
    <!-- Date Range Picker -->
    <script src="{{ URL::asset('dashboard/date-range-picker/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/date-range-picker/js/bootstrap-datepicker.min.js') }}" type="text/javascript">
    </script>
    <script>
        $(".date-picker").datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: !0,
            format: "dd/mm/yyyy",
            todayHighlight: true
        });
    </script>
    <script src="{{ URL::asset('dashboard/date-range-picker/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('dashboard/date-range-picker/js/knockout-3.4.2.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('dashboard/date-range-picker/js/daterangepicker.min.js') }}" type="text/javascript">
    </script>
    <script>
        $('input[name="date"]').daterangepicker({
            minDate: moment().subtract(2, 'years'),
            callback: function(startDate, endDate, period) {
                $(this).val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
            },
            startDate: '2019-10-01',
            endDate: '2022-11-04',
            maxDate: moment().add(1, 'years')
        });
    </script>
    <!-- End Here -->

    <script>
        $(document).ready(function() {
            //Reset btn
            $('.reset-btn').click(function() {
                $('#product_id').val(null).select2();
                $('#warehouse_id').val(null).select2();

                $('#select2-product_id-container').attr('title', 'Select Product').text('Select Product');
                $('#select2-warehouse_id-container').attr('title', 'All').text('All');
            });
            // End Here
        });
    </script>
    <!-- Datatable link, it will change ascending order of table -->
    <!-- <script src="{{ URL::asset('dashboard/datatables/report/jquery.dataTables.min.js') }}"></script> -->

    <script type="text/javascript">
        //    $(document).ready(function() {
            
        //     });
    $(function() {

        $('.print_record_btn').click(function() {
            //  alert("dsdds")
                    var myModal = new bootstrap.Modal(document.getElementById('print-record-modals'), {});
                    // alert(myModal)
                    myModal.toggle();

                    var base_url = $('#base_url').val();
                    // alert(base_url)

                    var get_date1 = $('#date').val().split(' - ')[0];
            var [day, month, year] = get_date1.split('/');
            var from_date = year+'/'+month+'/'+day;
            
            
            var get_date2 = $('#date').val().split(' - ')[1];
            var [day1, month1, year1] = get_date2.split('/');
            var to_date = year1+'/'+month1+'/'+day1;

            var report_type = $('.report_type:checked').val();
            var product_id = parseInt($('#product_id').val());
            var employee_id = parseInt($('#employee_id').val());
            // alert(warehouse_id)
            // var warehouse_name = "";
            // if (warehouse_id == 0) {
            //     warehouse_name = "All Warehouses";
            // } else {
            //     warehouse_name = $('#warehouse_id').val().split('_')[1];
            // }

                    $.ajax({
                        // url: "{{ URL::to('products/print/voucher') }}",
                        url: "{{ URL::to('packing-production-report/print') }}",
                        type: 'get',
                        data: {
                        from_date: from_date,
                        to_date: to_date,
                        report_type: report_type,
                        product_id: product_id,
                        employee_id: employee_id
                        },
                        beforeSend: function(response) {
                            $('#print-receipt-modal-body').html(
                                '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                            );
                        },
                        success: function(response) {
                            if (response != null && response != 0) {
                                //  alert("dd")
                                $('#print-receipt-modal-body').html(
                                    `<object data="${base_url}/resources/upload/production/packing/${response}" type="application/pdf" width="100%" height="800"></object>`
                                );
                            } else {
                                $('#print-receipt-modal-body').html(
                                    '<h2 style="color:red;text-align:center;">Voucher Not Exist</h2>'
                                );
                            }
                        }
                    });
                });

        
        $('.load_report_btn').click(function() {
            
            var get_date1 = $('#date').val().split(' - ')[0];
            var [day, month, year] = get_date1.split('/');
            var from_date = year+'/'+month+'/'+day;
            
            var get_date2 = $('#date').val().split(' - ')[1];
            var [day1, month1, year1] = get_date2.split('/');
            var to_date = year1+'/'+month1+'/'+day1;

            var report_type = $('.report_type:checked').val();
            // alert(report_type)
            var product_id = parseInt($('#product_id').val());
            var employee_id = parseInt($('#employee_id').val());
                $.ajax({
                    url: "{{ URL::to('packing-production-report') }}",
                    type: 'get',
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                        report_type: report_type,
                        product_id: product_id,
                        employee_id: employee_id
                    },
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#report-section').html('<div class="loader"></div>');
                    },
                    success: function(response) {
                        // alert("Dddd")
                        $('#report-section').html('');


                        var tableData = ``;
                        if (report_type == "summary") {
                            if (response.production.length > 0) {
                                tableData +=
                                `<table class="table table-striped table-responsive" id="report-table">
                                    <thead>
                                        <tr class="thead-row">
                                                <th style="width: 20%;">Product Code</th>
                                                <th style="width: 30%;">Product.Name</th>
                                                <th>Packing</th>
                                                <th style="width: 20%;">Quantity</th>
                                                <th style="width: 30%;">Pieces</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                                        var totalQty = 0;
                                        var totalPcs = 0;

                                    $.each(response.production, function(i, v) {
                                       
                                        if((v.qty) !== null){
                                        totalQty += parseInt(v.qty);
                                         }else{
                                        totalQty=0;
                                        }
                                        if((v.pcs) !== null){
                                        totalPcs += parseInt(v.pcs);
                                         }else{
                                        totalPcs=0;
                                        }

                                        var date = new Date(v.date);
                                    tableData +=
                                        `<tr class="tbody-row">`;
                                        tableData +=
                                        `<td>${v.code}</td>`;
                                        tableData +=
                                        `<td>${v.product_name}</td>`;
                                        tableData +=
                                        `<td>${v.packing}</td>`;
                                        tableData +=
                                        `<td>${v.qty}</td>`;
                                        tableData +=
                                        `<td>${v.pcs}</td>`;
                                    tableData += `</tr>`;
                                });
                                tableData += `<tr class="tfoot-row">
                                        <td colspan="3">Total</td>
                                    
                                        <td class="font-weight-bold">${totalQty}</td>
                                        <td class="font-weight-bold">${totalPcs}</td>
                                        
                                    
                                     
                                    </tr>
                                `;
                                tableData += `</tbody></table>`;
                                $('#report-section').html(tableData);
                                $('#report-section #report-table').DataTable();
                            } 
                            else {
                                $('#report-section').html(
                                    "<h4 class='text-danger text-center font-weight-bold'>Records Not Founddd...</h4>"
                                );
                            }
                             
                        }
                        else if (report_type == "detailed") {
                            if (response.production.length > 0) {
                                tableData +=
                                `<table class="table table-striped table-responsive" id="report-table">
                                    <thead>
                                        <tr class="thead-row">
                                                <th style="width: 20%;">Date</th>
                                                <th>Vr.No</th>
                                                <th style="width: 30%;">Product.Name</th>
                                                <th>Packing</th>
                                                <th>Quantity</th>
                                                <th>Pieces</th>
                                                <th style="width: 30%;">Employee&nbsp;Code&nbsp;Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                                        var totalQty = 0;
                                        var totalPcs = 0;

                                    $.each(response.production, function(i, v) {
                                       
                                        if((v.qty) !== null){
                                        totalQty += parseInt(v.qty);
                                         }else{
                                        totalQty=0;
                                        }
                                        if((v.pcs) !== null){
                                        totalPcs += parseInt(v.pcs);
                                         }else{
                                        totalPcs=0;
                                        }

                                        var date = new Date(v.date);
                                    tableData +=
                                        `<tr class="tbody-row">`;
                                        tableData +=
                                        `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                        tableData +=
                                        `<td>${v.voucher_no}</td>`;
                                        tableData +=
                                        `<td>${v.packing.product.product_name}</td>`;
                                        tableData +=
                                        `<td>${v.packing.product.packing}</td>`;
                                        tableData +=
                                        `<td>${v.qty}</td>`;
                                        tableData +=
                                        `<td>${v.pcs}</td>`;
                                        tableData +=
                                        `<td>${v.employee.code} - ${v.employee.party_name}</td>`;
                                    tableData += `</tr>`;
                                });
                                tableData += `<tr class="tfoot-row">
                                        <td colspan="4">Total</td>
                                    
                                        <td class="font-weight-bold">${totalQty}</td>
                                        <td class="font-weight-bold">${totalPcs}</td>
                                        <td class="font-weight-bold"></td>
                                    
                                     
                                    </tr>
                                `;
                                tableData += `</tbody></table>`;
                                $('#report-section').html(tableData);
                                $('#report-section #report-table').DataTable();
                            } 
                            else {
                                $('#report-section').html(
                                    "<h4 class='text-danger text-center font-weight-bold'>Records Not Founddd...</h4>"
                                );
                            }
                        }
                                 
                    }
                });
            $('.dt-button').removeClass('dt-button').addClass('btn btn-primary');
        });

        
    });


</script>
@include('include.toast-messages')
@stop
