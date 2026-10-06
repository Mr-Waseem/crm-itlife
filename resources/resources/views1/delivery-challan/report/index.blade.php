@extends('app')
@section('head')
    <title>DELIVERY CHALLAN REPORT</title>
    <!-- {{-- <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css"> -->
    <!--  Select 2 library start-->
    <!-- <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" /> -->
    <!--  Select 2 library end-->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/datatables/jquery.dataTables2.min.css') }}">
    <!-- Date Range Picker -->
    <link href="{{ URL::asset('dashboard/date-range-picker/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/date-range-picker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('dashboard/date-range-picker/css/daterangepicker.min.css') }}" rel="stylesheet"
        type="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
                DELIVEERY CHALLAN REPORT
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">DELIVERY CHALLAN REPORT</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>&nbsp;DELIVERY CHALLAN REPORT</h6>
                            <!-- <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul> -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'javascript:void(0);', 'class' => 'form-horizontal', 'id' => 'dc-report-form']) !!}
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-1">
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
                                        <label for="type"><i class="fa fa-home"></i> Select DC Type</label>
                                            {!! Form::select('DcType',$type, null, [
                                                'id' => 'DcType',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required'=>'required'
                                            ]) !!}
                                            @error('warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger warehouse_id_err"></span>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-1">
                                            <label for="product_id">Product <i class="fa fa-th-list"></i></label>
                                            {!! Form::select('party_id',$customers,null, [
                                                'id' => 'party_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required'=>'required'
                                            ]) !!}
                                            @error('product_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger product_id_err"></span>
                                        </div>
                                       
                                        <div class="col-lg-4 col-md-4 col-sm-4 report_type_fields">
                                            <input name="report_type" type="radio" class="with-gap report_type"
                                                id="report_type1" value="summary" checked>
                                            <label for="report_type1">Summary</label>

                                            <input name="report_type" type="radio" class="with-gap report_type"
                                                id="report_type2" value="detailed">
                                            <label for="report_type2">Detailed</label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
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
                            <!-- <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul> -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-1" id="report-section"  style="overflow-x:auto;"></div>
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
    <script>
        $('input[name="date"]').daterangepicker({
            minDate: moment().subtract(3, 'years'),
            callback: function(startDate, endDate, period) {
                $(this).val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
            },
            // startDate: '2019-10-01',
            // endDate: '2022-11-04',
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
    <!-- {{-- <script src="{{ URL::asset('dashboard/datatables/jquery.js') }}"></script>
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
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script> -->
    <script src="{{ URL::asset('dashboard/datatables/report/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $('.load-report-btn').click(function() {
                var get_date1 = $('#date').val().split(' - ')[0];
                var [day, month, year] = get_date1.split('/');
                var from_date = year+'/'+month+'/'+day;
                
                var get_date2 = $('#date').val().split(' - ')[1];
                var [day1, month1, year1] = get_date2.split('/');
                var to_date = year1+'/'+month1+'/'+day1;

                var report_type = $('.report_type:checked').val();
                var party_id = parseInt($('#party_id').val());
                var DcType = $('#DcType').val();
                // alert("entr")
                // var warehouse_name = "";
                // if (warehouse_id == 0) {
                //     warehouse_name = "All";
                // } else {
                //     warehouse_name = $('#warehouse_id').val().split('_')[1];
                // }

                //if (report_type=="summary") {
                    //   alert("163")
                    $.ajax({
                        // url: "{{ URL::to('stock-transfer/report') }}",
                        url: "{{ URL::to('delivery-challan/report') }}",
                        type: 'get',
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                            report_type: report_type,
                            party_id: party_id,
                            DcType: DcType
                        },
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#report-section').html('<div class="loader"></div>');
                        },

                        success: function(response) {
                         $('#report-section').html('');
                        //  alert("success")

                         $('#report-section').html('');
                        var QtyIn = 0;
                        var QtyOut = 0;
                        var totalQtyIn = 0;
                        var totalQtyOut = 0;
                        var totalQty = 0;
                        var tableData = ``;
                        // if (report_type == "summary") 
                        //     {
                            
                                tableData +=
                                `<div class="warehouse-name"><i class="fa fa-truck"></i> &nbsp;: ALL PARTIES DC REPORT</div>
                                <table class="table table-striped" id="report-table">
                                    <thead>
                                    <tr class="thead-row">
                                            <th>Sr</th>
                                            <th>Vr.No</th>
                                            <th>Date</th>
                                            <th>SD/OD#</th>
                                            <th>Type</th>
                                            <th>PO.No</th>
                                            <th>PO.Date</th>
                                            <th>P.Code</th>
                                            <th style="width: 30%;">Product Name</th>
                                            <th>Dispatch.Qty</th>
                                            <th>Party.Name</th>
                                            <th>Order.Date</th>
                                            <th>Remarks</th>
                                            <th>Vehicle.No</th>
                                            <th>Transport.C</th>
                                            <th>Driver</th>
                                            <th>Buility# </th>
                                            <th>Driver.Phone</th>
                                            <th>Freight</th> 
                                    </tr>
                                    </thead>
                                    <tbody>`;
                            
                                        $.each(response, function(i, v) {
                                            if (response.length > 0) {
                                            if (v.quantity != null) 
                                        {
                                            totalQty += parseInt(v.sale_qty);

                                            var date = new Date(v.voucher_date);
                                            var Orderdate = new Date(v.order_date);
                                            var Podate = new Date(v.po_date);
                                            tableData +=
                                                `<tr class="tbody-row" style="overflow-x:auto;">`;
                                            tableData +=
                                                `<td>${i+1}</td>`;
                                            tableData +=
                                                `<td>${v.voucher_no}</td>`;
                                            tableData +=
                                                // `<td>${v.voucher_date}</td>`;
                                                `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                            tableData +=
                                                `<td>${v.sale_order_no}</td>`;

                                                if (v.type == 'DCNonGST') {
                                                tableData += `<td>DEMAND</td>`;
                                                } else if (v.type == 'DC') {
                                                tableData += `<td>ORDER</td>`;
                                                }

                                                // tableData +=
                                                // `<td>${v.type}</td>`;
                                                tableData +=
                                                `<td>${v.po_no}</td>`;
                                                tableData +=
                                                `<td>${Podate.getDate()}/${Podate.getMonth() + 1}/${Podate.getFullYear()}</td>`;
                                                
                                                if (v.code) {
                                                    tableData +=`<td>${v.code}</td>`;
                                                }
                                                else {
                                                tableData += `<td></td>`;
                                                }
                                                if (v.product_name) {
                                                    tableData +=`<td>${v.product_name}</td>`;
                                                }
                                                else {
                                                tableData += `<td></td>`;
                                                }
                                                tableData +=
                                                `<td>${Number(v.sale_qty).toLocaleString('en-US')}</td>`;
                                                tableData +=
                                                `<td>${v.party_name}</td>`;
                                                tableData +=
                                                // `<td>${v.voucher_date}</td>`;
                                                `<td>${Orderdate.getDate()}/${Orderdate.getMonth() + 1}/${Orderdate.getFullYear()}</td>`;
                                                tableData +=
                                                `<td>${v.remarks}</td>`;
                                            tableData +=
                                                `<td>${v.vehicle_no}</td>`;
                                                tableData +=
                                                `<td>${v.transport_company}</td>`;
                                                tableData +=
                                                `<td>${v.driver_name}</td>`;
                                                tableData +=
                                                `<td>${v.builty_no}</td>`;
                                                tableData +=
                                                `<td>${v.driver_phoneno}</td>`;
                                                tableData +=
                                                `<td>${v.freight}</td>`;
                                            
                                            tableData += `</tr>`;
                                        }
                                    }
                                        });

                                        tableData += `<tr class="tfoot-row">
                                                
                                                <td class="font-weight-bold">Total</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="font-weight-bold">${totalQty.toLocaleString('en-US')}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>`;
                                        tableData += `
                                    </tbody>
                                </table>`;
                                    $('#report-section').html(tableData);
                                    $('#report-section #report-table').DataTable({
                                        "pageLength": 100,
                                         "ordering": false
                                    });
                                     
                                    // else 
                                    // {
                                    //     $('#report-section').html(
                                    //         "<h4 class='text-danger text-center font-weight-bold'>Record Not Found...</h4>"
                                    //     );
                                    // }
                                    // else 
                                    // {
                                    //     $('#report-section').html(
                                    //         "<h4 class='text-danger text-center font-weight-bold'>Record Not Found...</h4>"
                                    //     );
                                    // }
                            // }
                        }     
                    });
                //}

                // alert($('.dt-button').attr('tabindex'));
                $('.dt-button').removeClass('dt-button').addClass('btn btn-primary');

            });
        });
    </script>
    <!-- End Load Report -->
    @include('include.toast-messages')
@stop
