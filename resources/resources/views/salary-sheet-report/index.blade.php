@extends('app')
@section('head')
    <title>SALARY SHEET REPORT</title>
    <!-- {{-- <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"> --}} -->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/datatables/jquery.dataTables2.min.css') }}">
     <!-- <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" /> -->
    <!-- <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" /> -->
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
            width: 100%;
        }

        #report-table thead .thead-row,
        #report-table tbody .tfoot-row {
            background-color: #666EE7;
            color: white;
            width: 100%;
        }

        #report-table tbody .thead-row th,
        #report-table tbody .tbody-row td,
        #report-table tbody .tfoot-row td {
            border: 1px solid white;
            padding: .5rem;
            width: 100%;
        }
    </style>
@stop
@section('content')
<div class="content-wrapper">
        <section class="content-header">
            <h1>SALARY SHEET REPORT</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">SALARY SHEET REPORT </a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <!-- <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>&nbsp; CUSTOMER LEDGER REPORT</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div> -->
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                            @include('errors.validation')
                                <div class="col">
                                @if (Session::has('failure_message'))
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">×</button> {{ Session::get('failure_message') }}
                                        </div>
                                    @endif
                                    <div id="show_err"></div>
                                    {!! Form::open(['url' => 'javascript:void(0);', 'class' => 'form-horizontal', 'id' => 'salary-sheet-report']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                            <label for="date"><i class="fa fa-calendar-o"></i> As On</label>
                                            {!! Form::month('date', date('Y-m-d'), [
                                                'id' => 'date',
                                                'class' => 'form-control',
                                                'tabindex' => '0',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                            <label for="customer_id">Select Employee <i class="fa fa-user"></i></label>
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
                                        <div class="col-lg-4 col-md-6 col-sm-12 report_type_fields d-none">
                                            <input name="report_type" type="radio" class="with-gap report_type" id="report_type1"
                                                value="summary" checked>
                                            <label for="report_type1">Summary</label>
                                            &emsp13;&emsp13;
                                            <input name="report_type" type="radio" class="with-gap report_type" id="report_type2"
                                                value="detailed">
                                            <label for="report_type2">Detailed</label>
                                            @error('report_type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <br />
                                            <!-- <button class="btn btn-primary load-report-btn" type="button">Load Report</button> -->
                                            <button class="btn btn-primary btn-md print_record_btn" type="button"><i class="fa fa-print" style="color: white;"></i></button>
                                            <button class="btn btn-secondary reset-btn" type="reset">Reset</button>
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
                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-1" id="import-report-table"></div>
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
            <div class="modal-dialog modal-lg" style="min-width:95%;" role="document">
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
    <!-- <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2, .select2-multiple").select2({
            width: "100%"
        });
    </script> -->
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
    <!-- <script>
        $('input[name="date"]').daterangepicker({
            minDate: moment().subtract(2, 'years'),
            // callback: function(startDate, endDate, period) {
            callback: function(startDate) {
                // $(this).val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
                $(this).val(startDate.format('DD/MM/YYYY'));
            },
            startDate: '2019-10-01',
            // endDate: '2022-11-04',
            // maxDate: moment().add(1, 'years')
        });
    </script> -->
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
    <script src="{{ URL::asset('dashboard/datatables/report/jquery.dataTables.min.js') }}"></script>

    <script type="text/javascript">
        //    $(document).ready(function() {
            
        //     });
        $(document).ready(function() {

            $('.print_record_btn').click(function() {

                    var from_date = $('#date').val();
                    var base_url = $('#base_url').val();
                    // var report_type = $('.report_type:checked').val();
                    var Employee_id = parseInt($('#employee_id').val());
                    // alert(Employee_id);
                   
                    //    $('.employee_id_err').text('The Employee field is required.');
                       var myModal = new bootstrap.Modal(document.getElementById('print-record-modals'), {});
                        myModal.toggle();

                    $.ajax({
                        url: "{{ URL::to('salary-sheet-report/salary/pdf') }}",
                        type: 'get',
                     data: {
                        from_date: from_date,
                        // to_date: to_date,
                        // report_type: report_type,
                        Employee_id: Employee_id,
                        // warehouseID: warehouseID
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
                                    `<object data="${base_url}/resources/upload/salary-sheet-report/${response}" type="application/pdf" width="100%" height="800"></object>`
                                );
                            } else {
                                $('#print-receipt-modal-body').html(
                                    '<h2 style="color:red;text-align:center;">Voucher Not Exist</h2>'
                                );
                            }
                        }
                       });
                    
                   
                });


            $('.load-report-btn').click(function() {
                var from_date = $('#date').val();
                // var get_date1 = $('#date').val().split(' - ')[0];
                // var [day, month, year] = get_date1.split('/');
                // var from_date = year+'/'+month+'/'+day;
                
                // var get_date2 = $('#date').val().split(' - ')[1];
                // var [day1, month1, year1] = get_date2.split('/');
                // var to_date = year1+'/'+month1+'/'+day1;
                // alert(from_date);
                // alert(to_date);

                var customer_id = parseInt($('#customer_id').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                var report_type = $('.with-gap:checked').val();


                if (!customer_id) {
                    $('.customer_id_err').text('The Account field is required.');
                    return false;
                } else {
                    $('.customer_id_err').text('');
                }

                if (report_type == 'summary') {
                    $.ajax({
                        // url: `{{ URL::to('cash-book/ledger') }}?from_date=` + from_date +
                        //     '&to_date=' + to_date + '&customer_id=' + customer_id +
                        //     '&report_type=' +
                        //     report_type,
                        // type: 'get',

                        url: "{{ URL::to('cash-book-single/ledger') }}",
                        type: 'get',
                        data: {
                        from_date: from_date,
                        // to_date: to_date,
                        customer_id: customer_id,
                        report_type: report_type,
                        warehouseID: warehouseID
                        },

                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#import-report-table').html('<div class="loader"></div>');
                        },
                        success: function(response) {
                            // if (response.data.length > 0) {
                                // alert("id")
                            var tableData = '';
                            var totalDebit = 0;
                            var totalCredit = 0;
                            var totalbalance = 0;

                            if(response.OpeningcustomerLedger){
                                if((response.OpeningcustomerLedger.debit) !== null){
                                    OpeningDebit = parseInt(response.OpeningcustomerLedger.debit);
                                }else{
                                    OpeningDebit=0;
                                }
                                // alert(OpeningInvalue)
                                if((response.OpeningcustomerLedger.credit) !== null){
                                    OpeningCredit = parseInt(response.OpeningcustomerLedger.credit);
                                }else{
                                    OpeningCredit=0;
                                }
                                }else{
                                    OpeningDebit=0;
                                    OpeningCredit=0;
                                }
                                var totalopening = OpeningDebit - OpeningCredit;

                            var tableTag =
                                `<table class="table table-striped table-responsive" id="report-table" >
                                    <thead>
                                        <tr class="thead-row">
                                                <th>Sr.#</th>
                                                <th>Date</th>
                                                <th>Vr.Type</th>
                                                <th style="width: 20%;">Warehouse</th>
                                                <th style="width: 40%;">Account Name</th>
                                                <th>Debit</th>
                                                <th>Credit</th>
                                                <th>Balance</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                    <tr class="datatable-tr-settings">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    
                                    <td>OPENING BALANCE</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>${totalopening.toLocaleString('en-US')}</td>
                                    </tr>
                                    `;

                            $.each(response.data, function(i, v) {
                                var date = new Date(v.date);
                                tableData += '<tr class="datatable-tr-settings">';
                                tableData += `<td>${i+1}</td>`;
                                tableData +=
                                `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                    // `<td>${v.date}</td>`;
                                // tableData += `<td>${v.v_type} - ${v.voucher_no}</td>`;
                                if(v.v_type == "SALESTAX INVOICE"){
                                        tableData += `<td>STI-${v.voucher_no}</td>`;
                                    }else if(v.v_type == "Cash Receipt"){
                                        tableData += `<td>CR-${v.voucher_no}</td>`;
                                    }else if(v.v_type == "Cash Payment"){
                                        tableData += `<td>CP-${v.voucher_no}</td>`;
                                    }else if(v.v_type == "Bank Receipt")
                                        {
                                        tableData += `<td>BR-${v.voucher_no}</td>`;
                                    }else if(v.v_type == "Bank Payment"){
                                        tableData += `<td>BP-${v.voucher_no}</td>`;
                                    }else if(v.v_type == "Journal Voucher"){
                                        tableData += `<td>JV-${v.voucher_no}</td>`;
                                    }else if(v.v_type == "Opening Balance"){
                                        tableData += `<td>OB-${v.voucher_no}</td>`;
                                    }
                                    else if(v.v_type == "PURCHASE"){
                                        tableData += `<td>PUR-${v.voucher_no}</td>`;
                                    }
                                    else{
                                        tableData += `<td>${v.v_type}-${v.voucher_no}</td>`;
                                        }
                                tableData += `<td>${v.name}</td>`;
                                tableData += `<td>${v.code}-${v.party_name}</td>`;
                                // var debits  = v.debit.toLocaleString('en-US');
                                if(v.debit != null){
                                    tableData += `<td>${Number(v.debit).toLocaleString('en-US')}</td>`;
                                    totalDebit += parseFloat(v.debit);
                                }else{
                                    tableData += `<td></td>`;
                                }
                                
                               
                                // var credits = v.credit;
                                
                                if(v.credit != null){
                                tableData += `<td>${Number(v.credit).toLocaleString('en-US')}</td>`;
                                totalCredit += parseFloat(v.credit);
                                }else{
                                    tableData += `<td></td>`;
                                }
                                // alert(totalopening);
                                // totalbalance = totalCredit-totalDebit+totalopening;
                                totalbalance = totalDebit-totalCredit+totalopening;
                            
                                
                                tableData += `<td>${totalbalance.toLocaleString('en-US')}</td>`;
                                tableData += '</tr>';
                            });
                            tableTag += tableData;
                            // var grandbalance = totalCredit-totalDebit+totalopening;
                            var grandbalance = totalDebit-totalCredit+totalopening;
                            
                            if(totalDebit-totalCredit != '0'){
                            tableTag += `</tbody>
                                            <tfoot>
                                                <tr>
                                                <td class="font-weight-bold">Total</td>
                                                <td colspan="4"></td>
                                                <td id="summary-table-total-debit" class="font-weight-bold">${totalDebit.toLocaleString('en-US')}</td>
                                                <td id="summary-table-total-credit" class="font-weight-bold">${totalCredit.toLocaleString('en-US')}</td>
                                                <td id="summary-table-total-credit" class="font-weight-bold">${grandbalance.toLocaleString('en-US')}</td>
                                            </tr>
                                            </tfoot>
                                        </table>`;
                            }
                            $('#import-report-table').html(tableTag);
                            $('#import-report-table #report-table').DataTable({
                                "pageLength": 100
                            });
                            // $('#summary-report-table_wrapper .dt-buttons button').addClass(
                            //     'btn btn-primary btn-sm');
                            // $('#summary-report-table_wrapper .dt-buttons button')
                            //     .removeClass('dt-button');
                            // } 
                            // else {
                            //     $('#import-report-table').html(
                            //         "<tr><td>Records Not Found...</td></tr>"
                            //     );
                            // }
                        }
                    });
                } else
                if (report_type == 'detailed') {
                    $.ajax({
                        // url: `{{ URL::to('cash-book/ledger') }}?from_date=` + from_date +
                        //     '&to_date=' + to_date + '&customer_id=' + customer_id +
                        //     '&report_type=' +
                        //     report_type,
                        // type: 'get',
                        url: "{{ URL::to('cash-book-single/ledger') }}",
                        type: 'get',
                        data: {
                        from_date: from_date,
                        // to_date: to_date,
                        customer_id: customer_id,
                        report_type: report_type,
                        warehouseID: warehouseID
                        },
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#import-report-table').html('<div class="loader"></div>');
                        },
                        success: function(response) {
                            var tableData = '';
                            var totalQuantity = 0;
                            var totaltaxvalue = 0;
                            var totalDebit = 0;
                            var totalCredit = 0;
                            var totalBalance = 0;
                            if(response.OpeningcustomerLedger){
                                if((response.OpeningcustomerLedger.debit) !== null){
                                    OpeningDebit = parseInt(response.OpeningcustomerLedger.debit);
                                }else{
                                    OpeningDebit=0;
                                }
                                // alert(OpeningInvalue)
                                if((response.OpeningcustomerLedger.credit) !== null){
                                    OpeningCredit = parseInt(response.OpeningcustomerLedger.credit);
                                }else{
                                    OpeningCredit=0;
                                }
                                }else{
                                    OpeningDebit=0;
                                    OpeningCredit=0;
                                }
                                var totalopening = OpeningDebit - OpeningCredit;
                            var tableTag =
                                `<table class="table table-striped table-responsive" id="report-table"">
                                    <thead>
                                        <tr class="thead-row">
                                            <th>Sr.#</th>
                                            <th>Date</th>
                                            <th>Voucher</th>
                                            <th style="width: 20%;">Warehouse</th>
                                            <th style="width: 30%;">Account Name</th>
                                            <th style="width: 40%;">Description</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                            <th>Balance</th>
                                        </tr>
                                        </thead>
                                    <tbody>
                                    <tr class="datatable-tr-settings">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>OPENING BALANCE</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>${totalopening.toLocaleString('en-US')}</td>
                                    </tr>`;
                            $.each(response.data, function(i, v) {
                                // alert(response.status);
                                var chq_no = "";
                                if (v.cheque_no) {
                                    chq_no = v.cheque_no;
                                }
                                var date = new Date(v.date);
                                tableData += '<tr class="datatable-tr-settings">';
                                tableData += `<td>${i+1}</td>`;
                                tableData +=
                                    `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                    if(v.v_type == "SALESTAX INVOICE"){
                                        tableData += `<td>STI-${v.voucher_no}</td>`;
                                    }else if(v.v_type == "Cash Receipt"){
                                        tableData += `<td>CR-${v.voucher_no}</td>`;
                                    }else if(v.v_type == "Cash Payment"){
                                        tableData += `<td>CP-${v.voucher_no}</td>`;
                                    }else if(v.v_type == "Bank Receipt")
                                        {
                                        tableData += `<td>BR-${v.voucher_no}</td>`;
                                    }else if(v.v_type == "Bank Payment"){
                                        tableData += `<td>BP-${v.voucher_no}</td>`;
                                    }else if(v.v_type == "Journal Voucher"){
                                        tableData += `<td>JV-${v.voucher_no}</td>`;
                                    }else if(v.v_type == "Opening Balance"){
                                        tableData += `<td>OB-${v.voucher_no}</td>`;
                                    }
                                    else if(v.v_type == "PURCHASE"){
                                        tableData += `<td>PUR-${v.voucher_no}</td>`;
                                    }
                                    else{
                                        tableData += `<td>${v.v_type}-${v.voucher_no}</td>`;
                                        }
                                    // tableData += `<td>${v.v_type}-${v.voucher_no}</td>`;
                                // tableData += `<td>${v.other_parties.party_name}</td>`;
                             //Customer Product
                                if (response.status == 0) {
                                    if(v.customer_products){
                                        tableData += `<td style="font-size: 12px;">${v.customer_products.product_code} - ${v.customer_products.product_name}</td>`;
                                    }else{
                                        tableData += `<td></td>`;
                                    }
                                }
                                    if(v.warehouse){
                                        tableData += `<td style="font-size: 12px;">${v.warehouse.name}</td>`;
                                    }else{
                                        tableData += `<td></td>`;
                                    }               
                               
                                tableData += `<td>${v.other_parties.code}-${v.other_parties.party_name}</td>`;
                                tableData += `<td>${v.narration}</td>`;
                                if(v.debit != null){
                                    tableData += `<td>${Number(v.debit).toLocaleString('en-US')}</td>`;
                                    totalDebit += parseFloat(v.debit);
                                }else{
                                    tableData += `<td></td>`;
                                }
                                if(v.credit != null){
                                    tableData += `<td>${Number(v.credit).toLocaleString('en-US')}</td>`;
                                    totalCredit += parseFloat(v.credit);
                                }else{
                                    tableData += `<td></td>`;
                                }
                                totalBalance = totalDebit-totalCredit+totalopening;
                                tableData += `<td>${totalBalance.toLocaleString('en-US')}</td>`;
                                tableData += '</tr>';
                            });
                            tableTag += tableData;
                            var grandbalance = totalDebit-totalCredit+totalopening;
                            if(totalDebit-totalCredit != '0'){
                            tableTag += `</tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="6">Total</td>
                                                    <td class="font-weight-bold">${totalDebit.toLocaleString('en-US')}</td>
                                                    <td class="font-weight-bold">${totalCredit.toLocaleString('en-US')}</td>
                                                    <td class="font-weight-bold">${grandbalance.toLocaleString('en-US')}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    `;
                            }
                            $('#import-report-table').html(tableTag);
                            $('#import-report-table #report-table').DataTable({
                                "pageLength": 100,
                                "ordering": false
                            });
                            // $('#detailed-report-table_wrapper .dt-buttons button').addClass(
                            //     'btn btn-primary btn-sm');
                            // $('#detailed-report-table_wrapper .dt-buttons button')
                            //     .removeClass('dt-button');
                        }
                    });
                }
            
            });
        });


</script>
@include('include.toast-messages')
@stop
