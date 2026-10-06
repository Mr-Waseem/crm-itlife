@extends('app')
@section('head')
    <title>CUSTOMER BALANCE REPORT</title>
    <link href="{{ URL::asset('dashboard/toastr/toastr.min.css') }}" rel="stylesheet">
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
    </style>
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                CUSTOMER BALANCE
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">CUSTOMER BALANCE</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>&nbsp; CUSTOMER BALANCE REPORT</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'javascript:void(0);', 'class' => 'form-horizontal', 'id' => 'stock-ledger-report']) !!}
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
                                            <label for="warehouse_id">Branch <i class="fa fa-home"></i></label>
                                            {!! Form::select('warehouse_id', $warehouse, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger warehouse_id_err"></span>
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
                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-1">
                                            <table class="table table-bordered d-none" id="summary-report-table">
                                                <thead>
                                                    <tr>
                                                        <th>Sr.#</th>
                                                        <th>Date</th>
                                                        <th>Vr.Type</th>
                                                        <th>Vr.No</th>
                                                        <th>Debit</th>
                                                        <th>Credit</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4">Total</td>
                                                        <td id="summary-table-total-debit"></td>
                                                        <td id="summary-table-total-credit"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <table class="table table-bordered d-none" id="detailed-report-table">
                                                <thead>
                                                    <tr>
                                                        <th>Sr.#</th>
                                                        <th>Date</th>
                                                        <th>Vr.Type</th>
                                                        <th>Vr.No</th>
                                                        <th>Chq.No</th>
                                                        <th>Description</th>
                                                        <th>Debit</th>
                                                        <th>Credit</th>
                                                        <th>Balance</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="6">Total</td>
                                                        <td id="detailed-table-total-debit"></td>
                                                        <td id="detailed-table-total-credit"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
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
            // Hide Alert Notification After 3 Seconds
            setInterval(() => {
                $('.alert').hide();
            }, 3000);
            // End Hide Alert Notification After 3 Seconds


            //Reset btn
            $('.reset-btn').click(function() {
                $('#customer_id').val(null).select2();

                // $('#select2-product_id-container').attr('title', 'Select Product').text('Select Product');
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
        $(function() {
            $('.load-report-btn').click(function() {
                $('#summary-report-table').DataTable().clear().destroy();
                $('#detailed-report-table').DataTable().clear().destroy();

                $('#report-table').removeClass('d-none');
                var from_date = $('#date').val().split(' - ')[0];
                var to_date = $('#date').val().split(' - ')[1];
                var customer_id = parseInt($('#customer_id').val());
                var report_type = $('.with-gap:checked').val();

                if (!customer_id) {
                    $('.customer_id_err').text('The customer field is required.');
                } else {
                    $('.customer_id_err').text('');
                    $('#summary-report-table').removeClass('d-none');
                    $('#detailed-report-table').addClass('d-none');

                    if (report_type == 'summary') {
                        $('#summary-report-table').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ URL::to('customer-reports/ledger') }}?customer_id=" +
                                customer_id + '&from_date=' + from_date + '&to_date=' + to_date +
                                '&report_type=' + report_type,
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                            ],
                            columns: [{
                                    data: 'DT_RowIndex',
                                    name: 'DT_RowIndex',
                                    orderable: false,
                                    searchable: false
                                },
                                {
                                    data: 'voucher_date',
                                    name: 'voucher_date'
                                },
                                {
                                    data: 'v_type',
                                    name: 'v_type'
                                },
                                {
                                    data: 'voucher_no',
                                    name: 'voucher_no'
                                },
                                {
                                    data: 'total_debit',
                                    name: 'total_debit'
                                },
                                {
                                    data: 'total_credit',
                                    name: 'total_credit'
                                }
                            ],
                            "footerCallback": function(row, data) {
                                var api = this.api(),
                                    data;
                                var intVal = function(i) {
                                    return typeof i === 'string' ?
                                        i.replace(/[\$,]/g, '') * 1 :
                                        typeof i === 'number' ?
                                        i : 0;
                                };
                                pDebit = api.column(4, {
                                        page: 'current'
                                    })
                                    .data().reduce(function(a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0)
                                $(api.column(4).footer()).html(pDebit);



                                pCredit = api.column(5, {
                                        page: 'current'
                                    })
                                    .data().reduce(function(a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0)
                                $(api.column(5).footer()).html(pCredit);
                            }
                        });
                    } else
                    if (report_type == 'detailed') {
                        $('#summary-report-table').addClass('d-none');
                        $('#detailed-report-table').removeClass('d-none');


                        $('#detailed-report-table').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ URL::to('customer-reports/ledger') }}?customer_id=" +
                                customer_id + '&from_date=' + from_date + '&to_date=' + to_date +
                                '&report_type=' + report_type,
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                            ],
                            columns: [{
                                    data: 'DT_RowIndex',
                                    name: 'DT_RowIndex',
                                    orderable: false,
                                    searchable: false
                                },
                                {
                                    data: 'date',
                                    name: 'date'
                                },
                                {
                                    data: 'v_type',
                                    name: 'v_type'
                                },
                                {
                                    data: 'voucher_no',
                                    name: 'voucher_no'
                                },
                                {
                                    data: 'cheque_no',
                                    name: 'cheque_no'
                                },
                                {
                                    data: 'narration',
                                    name: 'narration'
                                },
                                {
                                    data: 'debit',
                                    name: 'debit'
                                },
                                {
                                    data: 'credit',
                                    name: 'credit'
                                },
                                {
                                    data: 'balance',
                                    name: 'balance'
                                }
                            ],
                            "footerCallback": function(row, data) {
                                var api = this.api(),
                                    data;
                                var intVal = function(i) {
                                    return typeof i === 'string' ?
                                        i.replace(/[\$,]/g, '') * 1 :
                                        typeof i === 'number' ?
                                        i : 0;
                                };
                                pDebit = api.column(6, {
                                        page: 'current'
                                    })
                                    .data().reduce(function(a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0)
                                $(api.column(6).footer()).html(pDebit);



                                pCredit = api.column(7, {
                                        page: 'current'
                                    })
                                    .data().reduce(function(a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0)
                                $(api.column(7).footer()).html(pCredit);


                                pAmount = api.column(8, {
                                        page: 'current'
                                    })
                                    .data().reduce(function(a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0)
                                $(api.column(8).footer()).html(pAmount);
                            }
                        });
                    }

                }

                // Show Buttons after load report
                $('.dt-button').removeClass('dt-button').addClass('btn btn-primary');
            });
        });
    </script>
    <!-- End Load Report -->
@stop
