@extends('app')
@section('head')
    <title>PURCHASER REPORT</title>
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
                PURCHASER REPORT
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">PURCHASER REPORT REPORT</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>&nbsp; PURCHASER REPORT</h6>
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
                                            <label for="purchaser_id">Purchaser <i class="fa fa-user"></i></label>
                                            {!! Form::select('purchaser_id', $purchasers, null, [
                                                'id' => 'purchaser_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('purchaser_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger purchaser_id_err"></span>
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
            //Reset btn
            $('.reset-btn').click(function() {
                $('#purchaser_id').val(null);
                $('#purchaser_id').select2('open');
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
                var purchaser_id = parseInt($('#purchaser_id').val());


                if (purchaser_id == 0) {
                    $.ajax({
                        url: `{{ URL::to('purhcaser-report') }}?from_date=` +
                            from_date +
                            '&to_date=' + to_date + '&purchaser_id=' + purchaser_id,
                        type: 'get',
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#import-report-table').html('<div class="loader"></div>');
                        },
                        success: function(response) {
                            var tableData = '';
                            var totalQtyIn = 0;
                            var totalQtyOut = 0;
                            var totalQty = 0;

                            var tableTag =
                                `<table class="table table-bordered" id="summary-report-table">
                                <thead>
                                        <tr>
                                            <th>Sr.#</th>
                                            <th>Purchaser</th>
                                            <th>In.Qty</th>
                                            <th>Out.Qty</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                <tbody>`;

                            $.each(response.data, function(i, v) {
                                totalQtyIn += parseFloat(v.qty_in);
                                totalQtyOut += parseFloat(v.qty_out);
                                totalQty += totalQtyIn - totalQtyOut;


                                tableData += '<tr>';
                                tableData += `<td>${i+1}</td>`;
                                tableData += `<td>${v.party_name}</td>`;
                                tableData += `<td>${v.qty_in}</td>`;
                                tableData += `<td>${v.qty_out}</td>`;
                                tableData += `<td>${totalQtyIn - totalQtyOut}</td>`;
                                tableData += '</tr>';
                            });
                            tableTag += tableData;
                            tableTag += `</tbody>
                                        <tfoot>
                                            <tr class="font-weight-bold">
                                                <td>Total</td>
                                                <td></td>
                                                <td>${totalQtyIn}</td>
                                                <td>${totalQtyOut}</td>
                                                <td>${totalQty}</td>
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


                            from_date = (new Date(from_date).getDate() + 1) + '/' +
                                (new Date(from_date).getMonth() + 1) + '/' +
                                new Date(from_date).getFullYear();
                            to_date = (new Date(to_date).getDate() + 1) + '/' +
                                (new Date(to_date).getMonth() + 1) + '/' +
                                new Date(to_date).getFullYear();
                            $('#import-party-and-date').html(
                                `<i class="fa fa-user"></i> :
                                <span id="import-party-name">All Purchaser</span>
                                <span id="import-selected-date">(${from_date} <i class="fa fa-calendar"></i> ${to_date})</span>`
                            );
                        }
                    });
                } else {
                    $.ajax({
                        url: `{{ URL::to('purhcaser-report') }}?from_date=` +
                            from_date +
                            '&to_date=' + to_date + '&purchaser_id=' + purchaser_id,
                        type: 'get',
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#import-report-table').html('<div class="loader"></div>');
                        },
                        success: function(response) {
                            // console.log(response);
                            var tableData = '';
                            var totalQtyIn = 0;
                            var totalQtyOut = 0;
                            var totalQty = 0;

                            var tableTag =
                                `<table class="table table-bordered" id="detailed-report-table">
                                <thead>
                                        <tr>
                                            <th>Sr.#</th>
                                            <th>Vr.No</th>
                                            <th>Vr.Date</th>
                                            <th>Supplier</th>
                                            <th>Purchaser</th>
                                            <th>Product</th>
                                            <th>Unit</th>
                                            <th>Order.Qty</th>
                                            <th>Purchase.Qty</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                <tbody>`;

                            $.each(response.data, function(i, v) {
                                totalQtyIn += parseFloat(v.qty);
                                totalQtyOut += parseFloat(v.provided_qty);

                                var date = new Date(v.date);
                                var supplier = "";
                                var purchaser = "";

                                if (v.supplier) {
                                    supplier = v.supplier.party_name;
                                }
                                if (v.purchaser) {
                                    purchaser = v.purchaser.party_name;
                                }


                                tableData += '<tr>';
                                tableData += `<td>${i+1}</td>`;
                                tableData += `<td>${v.bill_no}</td>`;
                                tableData +=
                                    `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                tableData += `<td>${supplier}</td>`;
                                tableData += `<td>${purchaser}</td>`;
                                tableData += `<td>${v.product.product_name}</td>`;
                                tableData += `<td>${v.product.uom}</td>`;
                                tableData += `<td>${v.qty}</td>`;
                                tableData += `<td>${v.provided_qty}</td>`;
                                tableData += `<td>${totalQtyIn - totalQtyOut}</td>`;
                                tableData += '</tr>';
                            });
                            tableTag += tableData;
                            tableTag += `</tbody>
                                        <tfoot>
                                            <tr class="font-weight-bold">
                                                <td>Total</td>
                                                <td colspan="6"></td>
                                                <td>${totalQtyIn}</td>
                                                <td>${totalQtyOut}</td>
                                                <td>${totalQtyIn - totalQtyOut}</td>
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
                                        }
                                    }
                                ]
                            });
                            $('#detailed-report-table_wrapper .dt-buttons button').addClass(
                                'btn btn-primary btn-sm');
                            $('#detailed-report-table_wrapper .dt-buttons button')
                                .removeClass('dt-button');

                            from_date = (new Date(from_date).getDate() + 1) + '/' +
                                (new Date(from_date).getMonth() + 1) + '/' +
                                new Date(from_date).getFullYear();
                            to_date = (new Date(to_date).getDate() + 1) + '/' +
                                (new Date(to_date).getMonth() + 1) + '/' +
                                new Date(to_date).getFullYear();
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
