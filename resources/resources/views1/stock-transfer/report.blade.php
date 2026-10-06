@extends('app')
@section('head')
    <title>STOCK TRANSFER REPORT</title>
    {{-- <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
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
            <h1>
                STOCK TRANSFER REPORT
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">STOCK TRANSFER REPORT</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>&nbsp; STOCK TRANSFER REPORT</h6>
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
                                        <div class="col-lg-8 col-md-8 col-sm-12 report_type_fields">
                                            <input name="report_type" type="radio" class="with-gap report_type"
                                                id="report_type1" value="summary" checked>
                                            <label for="report_type1">Summary</label>

                                            <input name="report_type" type="radio" class="with-gap report_type"
                                                id="report_type2" value="detailed">
                                            <label for="report_type2">Detailed</label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-1">
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
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-1">
                                            <label for="warehouse_id">Godown <i class="fa fa-home"></i></label>
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

    <!-- Load Report -->
    {{-- <script src="{{ URL::asset('dashboard/datatables/jquery.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.validate.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ URL::asset('dashboard/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/3.1.3/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/buttons.print.min.js') }}"></script>
    <script src="vendor/datatables/buttons.server-side.js"></script> --}}
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript">
        $(function() {
            $('.load-report-btn').click(function() {
                var from_date = $('#date').val().split(' - ')[0];
                var to_date = $('#date').val().split(' - ')[1];
                var report_type = $('.report_type:checked').val();
                var product_id = parseInt($('#product_id').val());
                var warehouse_id = parseInt($('#warehouse_id').val().split('_')[0]);
                var warehouse_name = "";
                if (warehouse_id == 0) {
                    warehouse_name = "All";
                } else {
                    warehouse_name = $('#warehouse_id').val().split('_')[1];
                }

                if (product_id==0) {
                    // alert(1)
                    $.ajax({
                        url: "{{ URL::to('stock-transfer/report') }}",
                        type: 'get',
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                            report_type: report_type,
                            product_id: product_id,
                            warehouse_id: warehouse_id
                        },
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#report-section').html('<div class="loader"></div>');
                        },
                        success: function(response) {
                            $('#report-section').html('');

                            var QtyIn = 0;
                            var QtyOut = 0;

                            var totalQtyIn = 0;
                            var totalQtyOut = 0;
                            var totalQty = 0;
                            var tableData = ``;

                            if (report_type == "summary") {
                                if (response.length > 0) {
                                    tableData +=
                                    `<div class="warehouse-name">
                                        <i class="fa fa-shopping-cart"></i> &nbsp;: ${warehouse_name}</div>
                                        <table class="table table-striped" id="report-table">
                                        <thead>
                                            <tr class="thead-row">
                                                    <th style="width:70px!important;">Sr</th>
                                                    <th style="width:200px!important;">Description</th>
                                                    <th style="width:100px!important;">In Qty</th>
                                                    <th style="width:100px!important;">Out Qty</th>
                                                    <th style="width:100px!important;">Bal Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;
                                    $.each(response, function(i, v) {
                                        QtyIn += parseInt(v.qty_in);
                                        QtyOut += parseInt(v.qty_out);


                                        totalQtyIn = QtyIn;
                                        totalQtyOut = QtyOut;
                                        totalQty = (QtyIn - QtyOut);



                                        tableData +=
                                            `<tr class="tbody-row">`;
                                        tableData +=
                                            `<td>${i+1}</td>`;
                                        tableData +=
                                            `<td>${v.product_name}</td>`;
                                        tableData +=
                                            `<td>${v.qty_in}</td>`;
                                        tableData +=
                                            `<td>${v.qty_out}</td>`;
                                        tableData +=
                                            `<td>${QtyIn - QtyOut}</td>`;
                                        tableData += `</tr>`;


                                    });

                                    tableData += `<tr class="tfoot-row">
                                            <td>Total</td>
                                            <td></td>
                                            <td class="font-weight-bold">${totalQtyIn}</td>
                                            <td class="font-weight-bold">${totalQtyOut}</td>
                                            <td class="font-weight-bold">${totalQty}</td>
                                        </tr>
                                    `;
                                    tableData += `</tbody></table>`;
                                    $('#report-section').html(tableData);

                                    $('#report-section #report-table').DataTable();
                                } else {
                                    $('#report-section').html(
                                        "<h4 class='text-danger text-center font-weight-bold'>Record Not Found...</h4>"
                                    );
                                }
                            } else if (report_type == "detailed") {
                                if (response.length > 0) {
                                    tableData +=
                                        ` <table class="table table-striped" id="report-table">
                                            <thead>
                                                <tr class="thead-row">
                                                    <th style="width:70px!important;">Sr</th>
                                                    <th style="width:70px!important;">Date</th>
                                                    <th style="width:70px!important;">Vr.Type</th>
                                                    <th style="width:70px!important;">Vr.No</th>
                                                    <th style="width:200px!important;">Description</th>
                                                    <th style="width:100px!important;">In Qty</th>
                                                    <th style="width:100px!important;">Out Qty</th>
                                                    <th style="width:100px!important;">Bal Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;
                                    $.each(response, function(i, v) {
                                        QtyIn += parseInt(v.qty_in);
                                        QtyOut += parseInt(v.qty_out);


                                        totalQtyIn = QtyIn;
                                        totalQtyOut = QtyOut;
                                        totalQty = (QtyIn - QtyOut);




                                        var date = new Date(v.date);
                                        tableData +=
                                            `<tr class="tbody-row">`;
                                        tableData +=
                                            `<td>${i+1}</td>`;
                                        tableData +=
                                            `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                        if (v.type == 'GRN') {
                                            tableData += `<td>GRN</td>`;
                                        } else if (v.type == 'STOCK TRANSFER') {
                                            tableData += `<td>S.TRANSFER</td>`;
                                        } else if (v.type == 'Delivery Challan') {
                                            tableData += `<td>DC</td>`;
                                        }


                                        tableData +=
                                            `<td>${v.voucher_no}</td>`;

                                        if (v.product != null) {
                                            tableData +=
                                                `<td>${v.product.product_name}</td>`;
                                        } else {
                                            tableData +=
                                                `<td> </td>`;
                                        }


                                        tableData +=
                                            `<td>${v.qty_in}</td>`;
                                        tableData +=
                                            `<td>${v.qty_out}</td>`;
                                        tableData +=
                                            `<td>${QtyIn - QtyOut}</td>`;
                                        tableData += `</tr>`;
                                    });

                                    tableData += `<tr class="tfoot-row">
                                            <td>Total</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="font-weight-bold">${totalQtyIn}</td>
                                            <td class="font-weight-bold">${totalQtyOut}</td>
                                            <td class="font-weight-bold">${totalQty}</td>
                                        </tr>
                                    `;
                                    tableData += `</tbody></table>`;
                                    $('#report-section').html(tableData);

                                    $('#report-section #report-table').DataTable();
                                } else {
                                    $('#report-section ').html(
                                        "<h4 class='text-danger text-center font-weight-bold'>Record Not Found...</h4>"
                                    );
                                }
                            }
                        }
                    });
                }else{
                    // alert(1)
                    $.ajax({
                        url: "{{ URL::to('stock-transfer/report') }}",
                        type: 'get',
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                            report_type: report_type,
                            product_id: product_id,
                            warehouse_id: warehouse_id
                        },
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#report-section').html('<div class="loader"></div>');
                        },
                        success: function(response) {
                            $('#report-section').html('');

                            var QtyIn = 0;
                            var QtyOut = 0;

                            var totalQtyIn = 0;
                            var totalQtyOut = 0;
                            var totalQty = 0;
                            var tableData = ``;

                            if (report_type == "summary") {
                                if (response.length > 0) {
                                    tableData +=
                                    `<div class="warehouse-name">
                                        <i class="fa fa-shopping-cart"></i> &nbsp;: ${warehouse_name}</div>
                                        <table class="table table-striped" id="report-table">
                                        <thead>
                                            <tr class="thead-row">
                                                    <th style="width:70px!important;">Sr</th>
                                                    <th style="width:200px!important;">Description</th>
                                                    <th style="width:100px!important;">In Qty</th>
                                                    <th style="width:100px!important;">Out Qty</th>
                                                    <th style="width:100px!important;">Bal Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;
                                    $.each(response, function(i, v) {
                                        QtyIn += parseInt(v.qty_in);
                                        QtyOut += parseInt(v.qty_out);


                                        totalQtyIn = QtyIn;
                                        totalQtyOut = QtyOut;
                                        totalQty = (QtyIn - QtyOut);



                                        tableData +=
                                            `<tr class="tbody-row">`;
                                        tableData +=
                                            `<td>${i+1}</td>`;
                                        tableData +=
                                            `<td>${v.product_name}</td>`;
                                        tableData +=
                                            `<td>${v.qty_in}</td>`;
                                        tableData +=
                                            `<td>${v.qty_out}</td>`;
                                        tableData +=
                                            `<td>${QtyIn - QtyOut}</td>`;
                                        tableData += `</tr>`;


                                    });

                                    tableData += `<tr class="tfoot-row">
                                            <td>Total</td>
                                            <td></td>
                                            <td class="font-weight-bold">${totalQtyIn}</td>
                                            <td class="font-weight-bold">${totalQtyOut}</td>
                                            <td class="font-weight-bold">${totalQty}</td>
                                        </tr>
                                    `;
                                    tableData += `</tbody></table>`;
                                    $('#report-section').html(tableData);

                                    $('#report-section #report-table').DataTable();
                                } else {
                                    $('#report-section').html(
                                        "<h4 class='text-danger text-center font-weight-bold'>Record Not Found...</h4>"
                                    );
                                }
                            } else if (report_type == "detailed") {
                                if (response.length > 0) {
                                    tableData +=
                                        ` <table class="table table-striped" id="report-table">
                                            <thead>
                                                <tr class="thead-row">
                                                    <th style="width:70px!important;">Sr</th>
                                                    <th style="width:70px!important;">Date</th>
                                                    <th style="width:70px!important;">Vr.Type</th>
                                                    <th style="width:70px!important;">Vr.No</th>
                                                    <th style="width:200px!important;">Description</th>
                                                    <th style="width:100px!important;">In Qty</th>
                                                    <th style="width:100px!important;">Out Qty</th>
                                                    <th style="width:100px!important;">Bal Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;
                                    $.each(response, function(i, v) {
                                        QtyIn += parseInt(v.qty_in);
                                        QtyOut += parseInt(v.qty_out);


                                        totalQtyIn = QtyIn;
                                        totalQtyOut = QtyOut;
                                        totalQty = (QtyIn - QtyOut);




                                        var date = new Date(v.date);
                                        tableData +=
                                            `<tr class="tbody-row">`;
                                        tableData +=
                                            `<td>${i+1}</td>`;
                                        tableData +=
                                            `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                        if (v.type == 'GRN') {
                                            tableData += `<td>GRN</td>`;
                                        } else if (v.type == 'STOCK TRANSFER') {
                                            tableData += `<td>S.TRANSFER</td>`;
                                        } else if (v.type == 'Delivery Challan') {
                                            tableData += `<td>DC</td>`;
                                        }


                                        tableData +=
                                            `<td>${v.voucher_no}</td>`;

                                        if (v.product != null) {
                                            tableData +=
                                                `<td>${v.product.product_name}</td>`;
                                        } else {
                                            tableData +=
                                                `<td> </td>`;
                                        }


                                        tableData +=
                                            `<td>${v.qty_in}</td>`;
                                        tableData +=
                                            `<td>${v.qty_out}</td>`;
                                        tableData +=
                                            `<td>${QtyIn - QtyOut}</td>`;
                                        tableData += `</tr>`;
                                    });

                                    tableData += `<tr class="tfoot-row">
                                            <td>Total</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="font-weight-bold">${totalQtyIn}</td>
                                            <td class="font-weight-bold">${totalQtyOut}</td>
                                            <td class="font-weight-bold">${totalQty}</td>
                                        </tr>
                                    `;
                                    tableData += `</tbody></table>`;
                                    $('#report-section').html(tableData);

                                    $('#report-section #report-table').DataTable();
                                } else {
                                    $('#report-section ').html(
                                        "<h4 class='text-danger text-center font-weight-bold'>Record Not Found...</h4>"
                                    );
                                }
                            }
                        }
                    });
                }

                // alert($('.dt-button').attr('tabindex'));
                $('.dt-button').removeClass('dt-button').addClass('btn btn-primary');

            });
        });
    </script>
    <!-- End Load Report -->
@stop
