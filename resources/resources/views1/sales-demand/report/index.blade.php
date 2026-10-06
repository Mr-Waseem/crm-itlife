@extends('app')
@section('head')
    <title>ORDER DEMAND REPORT</title>
     <link href="{{ URL::asset('dashboard/date-range-picker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
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
            <h1>
            ORDER DEMAND REPORT
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">ORDER DEMAND REPORT</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>&nbsp; ORDER DEMAND REPORT</h6>
                            <!-- <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul> -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'javascript:void(0);', 'class' => 'form-horizontal', 'id' => 'dc-report-form']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
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
                                        <label for="type"><i class="fa fa-home"></i> Select Order Type</label>
                                            {!! Form::select('OrderType', $type, null, [
                                                'id' => 'OrderType',
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
                                            <label for="product_id">Select Party <i class="fa fa-th-list"></i></label>
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
                                            <button class="btn btn-primary load-report-btn" type="button">Load Report</button>
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
            $('.print_record_btn').click(function() {
                var base_url = $('#base_url').val();
                var get_date1 = $('#date').val().split(' - ')[0];
                var [day, month, year] = get_date1.split('/');
                var from_date = year+'/'+month+'/'+day;
                
                var get_date2 = $('#date').val().split(' - ')[1];
                var [day1, month1, year1] = get_date2.split('/');
                var to_date = year1+'/'+month1+'/'+day1;
                // alert(from_date)
                // alert(to_date)

                var report_type = $('.report_type:checked').val();
                var party_id = parseInt($('#party_id').val());
                var OrderType = $('#OrderType').val();
                // alert("dfds");
            //  alert("dsdds")
            var myModal = new bootstrap.Modal(document.getElementById('print-record-modals'), {});
            // alert(myModal)
            myModal.toggle();

            $.ajax({
                url: "{{ URL::to('order-demand-report/report/print') }}",
                        type: 'get',
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                            report_type: report_type,
                            party_id: party_id,
                            OrderType: OrderType
                            
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
                            `<object data="${base_url}/resources/upload/sales-demand/${response}" type="application/pdf" width="100%" height="800"></object>`
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
                // var from_date = $('#date').val().split(' - ')[0];
                // var to_date = $('#date').val().split(' - ')[1];

                var get_date1 = $('#date').val().split(' - ')[0];
                var [day, month, year] = get_date1.split('/');
                var from_date = year+'/'+month+'/'+day;
                
                var get_date2 = $('#date').val().split(' - ')[1];
                var [day1, month1, year1] = get_date2.split('/');
                var to_date = year1+'/'+month1+'/'+day1;
                // alert(from_date)
                // alert(to_date)

                var report_type = $('.report_type:checked').val();
                var party_id = parseInt($('#party_id').val());
                var OrderType = $('#OrderType').val();
                
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
                        url: "{{ URL::to('order-demand-report') }}",
                        type: 'get',
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                            report_type: report_type,
                            party_id: party_id,
                            OrderType: OrderType
                            
                        },
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#report-section').html('<div class="loader"></div>');
                        },

                        success: function(response) {
                         $('#report-section').html('');
                        

                         $('#report-section').html('');
                        var QtyIn = 0;
                        var QtyOut = 0;
                        var totalQtyIn = 0;
                        var totalQtyOut = 0;
                        var totalQty = 0;
                        var Despatch = 0;
                        var totalDespatch = 0;
                        var tableData = ``;
                        // if (report_type == "summary") 
                        //     {
                            
                                tableData +=
                                `<div class="warehouse-name"><i class="fa fa-truck"></i> &nbsp;: ALL PARTIES ORDER/DEMAND REPORT</div>
                                <table class="table table-striped" id="report-table">
                                    <thead>
                                    <tr class="thead-row">
                                            <th style="width:70px!important;">Sr</th>
                                            <th style="width:200px!important;">Vr.No</th>
                                            <th style="width:100px!important;">Date</th>
                                            <th style="width:100px!important;">Type</th>
                                            <th style="width:100px!important;">Party Name</th>
                                            <th style="width:100px!important;">P.Code</th>
                                            <th style="width:100px!important;">Product&nbsp;Name</th>
                                            <th style="width:100px!important;">PO.Date</th>
                                            <th style="width:100px!important;">PO.#</th>
                                            <th style="width:100px!important;">Order.Qty</th> 
                                            <th style="width:100px!important;">Despatch.Qty</th> 
                                            <th style="width:100px!important;">Balance</th>
                                            <th style="width:100px!important;">Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody>`;
                                    // alert("v.voucher_date")
                                        $.each(response, function(i, v) {
                                            
                                            if (response.length > 0) {
                                        //         alert("ddd");
                                        //     if (v.qty != null) 
                                        // {
                                            // alert(v.voucher_date)
                                            

                                            var date = new Date(v.voucher_date);
                                            var POdate = new Date(v.po_date);
                                            // var Orderdate = new Date(v.order_date);
                                            // var Podate = new Date(v.po_date);
                                            tableData +=
                                                `<tr class="tbody-row" style="overflow-x:auto;">`;
                                            tableData +=
                                                `<td>${i+1}</td>`;
                                            tableData +=
                                                `<td style="width: 0%;">${v.voucher_no}</td>`;
                                            tableData +=
                                                `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                            
                                                if (v.type == 'SALE ORDER') {
                                                tableData += `<td>ORDER</td>`;
                                            } else if (v.type == 'SALE DEMAND') {
                                                tableData += `<td>DEMAND</td>`;
                                            }
                                                tableData +=
                                                `<td style="width: 20%;">${v.party_name}</td>`;
                                                if (report_type == "summary") {
                                                tableData +=
                                                `<td></td>`;
                                                }else if(report_type == "detailed"){
                                                    tableData +=
                                                    `<td>${v.code}</td>`;
                                                }

                                                if (report_type == "summary") {
                                                tableData +=`<td></td>`;
                                                }else if(report_type == "detailed"){
                                                    tableData +=
                                                    `<td style="width: 20%;">${v.product_name}</td>`;
                                                }
                                                
                                                tableData +=
                                                `<td>${POdate.getDate()}/${POdate.getMonth() + 1}/${POdate.getFullYear()}</td>`;
                                                tableData +=
                                                `<td>${v.po_no}</td>`;
                                                
                                                tableData +=
                                                `<td>${Number(v.OrderQty).toLocaleString('en-US')}</td>`;


                                               
                                                tableData +=
                                                `<td>${Number(v.DespatchQty).toLocaleString('en-US')}</td>`;
                                                 Despatch = v.OrderQty-v.DespatchQty;
                                                // totalDespatch = v.OrderQty-v.DespatchQty;
                                                totalQty += parseInt(v.OrderQty);
                                                totalDespatch += parseInt(v.DespatchQty);
                                                tableData +=
                                                `<td>${Number(Despatch).toLocaleString('en-US')}</td>`;
                                                tableData +=
                                                `<td>${v.remarks}</td>`;
                                            tableData += `</tr>`;
                                        // }
                                    }
                                        });
                                        var totalBalance = totalQty-totalDespatch;
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
                                                <td class="font-weight-bold">${totalDespatch.toLocaleString('en-US')}</td>
                                                <td class="font-weight-bold">${Number(totalBalance).toLocaleString('en-US')}</td>
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
                                //     $('#report-section #report-table').DataTable({
                                //     "pageLength": 100,
                                //     "ordering": false
                                // });
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
