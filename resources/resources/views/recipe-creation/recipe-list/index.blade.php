@extends('app')
@section('head')
    <title>RECIPE LIST</title>
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
                RECIPE LIST
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">RECIPE LIST</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>&nbsp; RECIPE LIST</h6>
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
                
                var warehouse_id = parseInt($('#warehouse_id').val().split('_')[0]);
            
                if (warehouse_id==0) {
                    warehouse_name = "All";
                     $.ajax({
                        // url: "{{ URL::to('stock-transfer/report') }}",
                        url: "{{ URL::to('recipe-list/list/load-list') }}",
                        type: 'get',
                        data: {
                            warehouse_id: warehouse_id
                        },
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#report-section').html('<div class="loader"></div>');
                        },
                        success: function(response) {
                            $('#report-section').html('');

                            var tableData = ``;
                            if (warehouse_id == 0) {
                                if (response.length > 0) {
                                    tableData +=
                                    `<div class="warehouse-name">
                                        <i class="fa fa-shopping-cart"></i> &nbsp;Warehouse Name: ${warehouse_name}</div>
                                        <table class="table table-striped" id="report-table">
                                        <thead>
                                            <tr class="thead-row">
                                                    <th style="width:70px!important;">Sr</th>
                                                    <th style="width:100px!important;">Recipe Code</th>
                                                    <th style="width:200px!important;">Recipe Name</th>
                                                    <th style="width:100px!important;">Warehouse</th>
                                                    <th style="width:100px!important;">Voucher No</th>
                                                    <th style="width:100px!important;">Created By</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;
                                    $.each(response, function(i, v) {

                                        tableData +=
                                            `<tr class="tbody-row">`;
                                        tableData +=
                                            `<td>${i+1}</td>`;
                                        tableData +=
                                            `<td>${v.product.product_code}</td>`;
                                        tableData +=
                                            `<td>${v.product.product_name}</td>`;
                                        tableData +=
                                            `<td>${v.warehouse.name}</td>`;
                                            tableData +=
                                            `<td>${v.voucher_no}</td>`;
                                        tableData +=
                                            `<td>${v.generated_by.name}</td>`;
                                        tableData += `</tr>`;


                                    });

                                    tableData += `</tbody></table>`;
                                    $('#report-section').html(tableData);

                                    $('#report-section #report-table').DataTable();
                                } else {
                                    $('#report-section').html(
                                        "<h4 class='text-danger text-center font-weight-bold'>Record Not Found...</h4>"
                                    );
                                }
                            }
                        }
                    });
                }else{
                    $.ajax({
                        // url: "{{ URL::to('stock-transfer/report') }}",
                        url: "{{ URL::to('recipe-list/list/load-list') }}",
                        type: 'get',
                        data: {
                            warehouse_id: warehouse_id
                        },
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#report-section').html('<div class="loader"></div>');
                        },
                        success: function(response) {
                            $('#report-section').html('');
                            var tableData = ``;
                            if (warehouse_id != 0) {
                                warehouse_name = $('#warehouse_id').val().split('_')[1];
                                if (response.length > 0) {
                                    tableData +=
                                    `<div class="warehouse-name">
                                        <i class="fa fa-shopping-cart"></i> &nbsp;Warehouse Name: ${warehouse_name}</div>
                                        <table class="table table-striped" id="report-table">
                                        <thead>
                                            <tr class="thead-row">
                                                    <th style="width:70px!important;">Sr</th>
                                                    <th style="width:100px!important;">Recipe Code</th>
                                                    <th style="width:200px!important;">Recipe Name</th>
                                                    <th style="width:100px!important;">Voucher No</th>
                                                    <th style="width:100px!important;">Created By</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;
                                    $.each(response, function(i, v) {

                                        tableData +=
                                            `<tr class="tbody-row">`;
                                        tableData +=
                                            `<td>${i+1}</td>`;
                                        tableData +=
                                            `<td>${v.product.product_code}</td>`;
                                        tableData +=
                                            `<td>${v.product.product_name}</td>`;
                                        tableData +=
                                            `<td>${v.voucher_no}</td>`;
                                        tableData +=
                                            `<td>${v.generated_by.name}</td>`;
                                        tableData += `</tr>`;


                                    });

                                    tableData += `</tbody></table>`;
                                    $('#report-section').html(tableData);

                                    $('#report-section #report-table').DataTable();
                                } else {
                                    $('#report-section').html(
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
