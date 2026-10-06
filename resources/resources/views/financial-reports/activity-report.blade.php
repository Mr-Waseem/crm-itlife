@extends('app')
@section('head')
    <title>ACCOUNT ACTIVITY REPORT</title>
    <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!--  Select 2 library start-->
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!--  Select 2 library end-->

    <!-- Date Range Picker -->
    <link href="{{ URL::asset('dashboard/date-range-picker/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/date-range-picker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('dashboard/date-range-picker/css/daterangepicker.min.css') }}" rel="stylesheet"
        type="text/css" />
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

        .loader {
            margin: 0px auto;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
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
    </style>

    <!-- JQuery DataTable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Financial Reports
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">ACCOUNT ACTIVITY REPORT</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>&nbsp; ACCOUNT ACTIVITY REPORT</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open([
                                        'url' => 'javascript:void(0);',
                                        'class' => 'form-horizontal',
                                        'id' => 'financial-activity-report',
                                    ]) !!}
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-1">
                                            <label for="date"><i class="fa fa-calendar-o"></i> As On</label>
                                            {!! Form::text('date', date('Y-m-d') . ' - ' . date('Y-m-d'), [
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
                                            <label for="customer_id">Customer <i class="fa fa-user"></i></label>
                                            {!! Form::select('customer_id', $parties, null, [
                                                'id' => 'customer_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('customer_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger customer_id_err"></span>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 report_type_fields">
                                            <input name="report_type" type="radio" class="with-gap" id="report_type1"
                                                value="summary" checked>
                                            <label for="report_type1">Summary</label>
                                            &emsp13;&emsp13;
                                            <input name="report_type" type="radio" class="with-gap" id="report_type2"
                                                value="detailed">
                                            <label for="report_type2">Detailed</label>
                                            @error('report_type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <br />
                                            <button class="btn btn-primary load-report-btn" type="button">Load
                                                Report</button>
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
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i>&nbsp; Report</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-1" id="import-party-and-date"
                                            style="font-size: 20px;color:black;"></div>
                                    </div>
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
@stop
@section('scripts')
    <!-- JQuery DataTable JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <!-- Searchable Select2 -->
    <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2, .select2-multiple").select2({
            width: "100%"
        });
    </script>
    <!-- End Searchable Select2 -->

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
            // Hide Alert Notification After 3 Seconds
            setInterval(() => {
                $('.alert').hide();
            }, 3000);
            // End Hide Alert Notification After 3 Seconds


            //Reset btn
            $('.reset-btn').click(function() {
                $('#customer_id').val(null);
                $('#customer_id').select2('open');
            });
            // End Here
        });
    </script>

    <!-- Load Report -->
    <script src="{{ URL::asset('dashboard/datatables/jquery.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.validate.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ URL::asset('dashboard/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/3.1.3/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/buttons.print.min.js') }}"></script>
    <script src="vendor/datatables/buttons.server-side.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.load-report-btn').click(function() {
                var from_date = $('#date').val().split(' - ')[0];
                var to_date = $('#date').val().split(' - ')[1];
                var customer_id = parseInt($('#customer_id').val());
                var report_type = $('.with-gap:checked').val();


                if (!customer_id) {
                    $('.customer_id_err').text('The customer field is required.');
                    return false;
                } else {
                    $('.customer_id_err').text('');
                }

                if (report_type == 'summary') {
                    $.ajax({
                        url: `{{ URL::to('financial-reports/activity') }}?from_date=` +
                            from_date +
                            '&to_date=' + to_date + '&customer_id=' + customer_id +
                            '&report_type=' +
                            report_type,
                        type: 'get',
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#import-report-table').html('<div class="loader"></div>');
                        },
                        success: function(response) {
                            var tableData = '';
                            var totalDebit = 0;
                            var totalCredit = 0;
                            var partyName = '';

                            var tableTag =
                                `<table class="table table-bordered" id="summary-report-table">
                                <thead>
                                        <tr>
                                            <th>Sr.#</th>
                                            <th>Date</th>
                                            <th>Vr.Type</th>
                                            <th>Vr.No</th>
                                            <th>Description</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                <tbody>`;

                            $.each(response.data, function(i, v) {
                                var date = new Date(v.voucher_date);
                                var v_type = '';

                                if (v.v_type == 'Cash Receipt') {
                                    v_type = 'CR';
                                } else
                                if (v.v_type == 'Cash Payment') {
                                    v_type = 'CP';
                                } else
                                if (v.v_type == 'Bank Payment') {
                                    v_type = 'BP';
                                } else
                                if (v.v_type == 'Bank Receipt') {
                                    v_type = 'BR';
                                } else
                                if (v.v_type == 'Journal Voucher') {
                                    v_type = 'JV';
                                }

                                tableData += '<tr>';
                                tableData += `<td>${i+1}</td>`;
                                tableData +=
                                    `<td>${date.getDay()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                tableData += `<td>${v_type}</td>`;
                                tableData += `<td>${v.voucher_no}</td>`;
                                tableData += `<td>${v.voucher_party.party_name}</td>`;
                                tableData += `<td>${v.total_debit}</td>`;
                                tableData += `<td>${v.total_credit}</td>`;
                                tableData +=
                                    `<td>${v.total_debit - v.total_credit}</td>`;
                                tableData += '</tr>';

                                totalDebit += parseFloat(v.total_debit);
                                totalCredit += parseFloat(v.total_credit);
                            });
                            tableTag += tableData;
                            tableTag += `</tbody>
                                        <tfoot>
                                            <tr>
                                            <td class="font-weight-bold">Total</td>
                                            <td colspan="5"></td>
                                            <td id="summary-table-total-debit" class="font-weight-bold">${totalDebit}</td>
                                            <td id="summary-table-total-credit" class="font-weight-bold">${totalCredit}</td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                    `;

                            $('#import-report-table').html(tableTag);
                            $('#import-report-table #summary-report-table').DataTable({
                                dom: 'Bfrtip',
                                buttons: [{
                                        extend: 'copyHtml5',
                                        footer: true
                                    },
                                    {
                                        extend: 'excelHtml5',
                                        footer: true
                                    },
                                    {
                                        extend: 'csvHtml5',
                                        footer: true
                                    },
                                    {
                                        extend: 'pdfHtml5',
                                        footer: true,
                                        customize: function(doc) {
                                            doc.content[1].table.widths =
                                                Array(doc.content[1].table.body[
                                                    0].length + 1).join('*')
                                                .split('');
                                        }
                                    }
                                ]
                            });
                            $('#summary-report-table_wrapper .dt-buttons button').addClass(
                                'btn btn-primary btn-sm');
                            $('#summary-report-table_wrapper .dt-buttons button')
                                .removeClass('dt-button');

                            from_date = new Date(from_date).getDay() + '/' + new Date(from_date)
                                .getMonth() + '/' + new Date(from_date).getFullYear();
                            to_date = new Date(to_date).getDay() + '/' + new Date(to_date)
                                .getMonth() + '/' + new Date(to_date).getFullYear();
                            $('#import-party-and-date').html(
                                `<i class="fa fa-user"></i> :
                                <span id="import-party-name">${response.party.party_name}</span>
                                <span id="import-selected-date">(${from_date} <i class="fa fa-calendar"></i> ${to_date})</span>`
                            );
                        }
                    });
                } else
                if (report_type == 'detailed') {

                    $.ajax({
                        url: `{{ URL::to('financial-reports/activity') }}?from_date=` + from_date +
                            '&to_date=' + to_date + '&customer_id=' + customer_id +
                            '&report_type=' +
                            report_type,
                        type: 'get',
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#import-report-table').html('<div class="loader"></div>');
                        },
                        success: function(response) {

                            var tableData = '';
                            var totalDebit = 0;
                            var totalCredit = 0;
                            var totalBalance = 0;

                            var tableTag =
                                `<table class="table table-bordered" id="detailed-report-table">
                                <thead>
                                    <tr>
                                        <th>Sr.#</th>
                                        <th>Date</th>
                                        <th>Vr.Type</th>
                                        <th>Vr.No</th>
                                        <th>Description</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Balance</th>
                                    </tr>
                                    </thead>
                                <tbody>`;

                            $.each(response.data, function(i, v) {
                                var chq_no = "";
                                var v_type = '';
                                if (v.cheque_no) {
                                    chq_no = v.cheque_no;
                                }


                                if (v.v_type == 'Cash Receipt') {
                                    v_type = 'CR';
                                } else
                                if (v.v_type == 'Cash Payment') {
                                    v_type = 'CP';
                                } else
                                if (v.v_type == 'Bank Payment') {
                                    v_type = 'BP';
                                } else
                                if (v.v_type == 'Bank Receipt') {
                                    v_type = 'BR';
                                } else
                                if (v.v_type == 'Journal Voucher') {
                                    v_type = 'JV';
                                }

                                totalDebit += parseFloat(v.debit);
                                totalCredit += parseFloat(v.credit);
                                totalBalance += (totalDebit - totalCredit);


                                var date = new Date(v.date);
                                tableData += '<tr>';
                                tableData += `<td>${i+1}</td>`;
                                tableData +=
                                    `<td>${date.getDay()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                tableData += `<td>${v_type}</td>`;
                                tableData += `<td>${v.voucher_no}</td>`;
                                tableData += `<td>${v.narration}</td>`;
                                tableData += `<td>${v.debit}</td>`;
                                tableData += `<td>${v.credit}</td>`;
                                tableData += `<td>${totalDebit-totalCredit}</td>`;
                                tableData += '</tr>';


                            });

                            tableTag += tableData;
                            tableTag += `</tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="font-weight-bold">Total</td>
                                                <td colspan="4"></td>
                                                <td class="font-weight-bold">${totalDebit}</td>
                                                <td class="font-weight-bold">${totalCredit}</td>
                                                <td class="font-weight-bold">${totalBalance}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                `;

                            $('#import-report-table').html(tableTag);
                            $('#import-report-table #detailed-report-table').DataTable({
                                dom: 'Bfrtip',
                                buttons: [{
                                        extend: 'copyHtml5',
                                        footer: true
                                    },
                                    {
                                        extend: 'excelHtml5',
                                        footer: true
                                    },
                                    {
                                        extend: 'csvHtml5',
                                        footer: true
                                    },
                                    {
                                        extend: 'pdfHtml5',
                                        footer: true,
                                        customize: function(doc) {
                                            doc.content[1].table.widths =
                                                Array(doc.content[1].table.body[
                                                    0].length + 1).join('*')
                                                .split('');
                                        },
                                        orientation: 'landscape',
                                        pageSize: 'A4',
                                        alignment: "center",
                                    }
                                ]
                            });
                            $('#detailed-report-table_wrapper .dt-buttons button').addClass(
                                'btn btn-primary btn-sm');
                            $('#detailed-report-table_wrapper .dt-buttons button')
                                .removeClass('dt-button');


                            from_date = new Date(from_date).getDay() + '/' + new Date(from_date)
                                .getMonth() + '/' + new Date(from_date).getFullYear();
                            to_date = new Date(to_date).getDay() + '/' + new Date(to_date)
                                .getMonth() + '/' + new Date(to_date).getFullYear();
                            $('#import-party-and-date').html(
                                `<i class="fa fa-user"></i> :
                                <span id="import-party-name">${response.party.party_name}</span>
                                <span id="import-selected-date">(${from_date} <i class="fa fa-calendar"></i> ${to_date})</span>`
                            );
                        }
                    });
                }
            });
        });
    </script>
    <!-- End Load Report -->
@stop
