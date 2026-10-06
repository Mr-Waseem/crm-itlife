@extends('app')
@section('head')
    <title>GRN REPORT</title>
    <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
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
            <h1>
                GRN REPORT
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">GRN REPORT</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>&nbsp; GRN REPORT</h6>
                            <!-- <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul> -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                            
                                <div class="col">
                                    {!! Form::open(['url' => 'javascript:void(0);', 'class' => 'form-horizontal', 'id' => 'stock-ledger-report']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                           
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
                                       
                                        <div class="col-lg-3 col-md-4 col-sm-12 mt-1">
                                            <label for="product_id">Godown <i class="fa fa-th-list"></i></label>
                                            {!! Form::select('warehouse_id', $warehouse, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger warehouse_id_err"></span>
                                        </div>
                                        <div class="col-lg-6 col-md-4 col-sm-12 mt-1">
                                            <label for="supplier_id">Supplier <i class="fa fa-home"></i></label>
                                            {!! Form::select('supplier_id', $supplier, null, [
                                                'id' => 'supplier_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                            ]) !!}
                                            @error('supplier_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger supplier_id_err"></span>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-1">
                                            <label for="purchaser_id">Purchaser <i class="fa fa-home"></i></label>
                                            {!! Form::select('purchaser_id', $purchaser, null, [
                                                'id' => 'purchaser_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                            ]) !!}
                                            @error('purchaser_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger purchaser_id_err"></span>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-12 mt-1">
                                            <label for="product_id">Product <i class="fa fa-th-list"></i></label>
                                            {!! Form::select('product_id', $products, null, [
                                                'id' => 'product_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '3',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('product_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger product_id_err"></span>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-4">
                                            <input name="report" type="radio" class="with-gap report_type" id="summary"  value="summary" checked="">
                                            <label for="summary">Summary</label>
                                            <input name="report" type="radio" class="with-gap report_type" id="detail" value="detail">
                                            <label for="detail">Detail</label>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <br />
                                            <button class="btn btn-primary load-report-btn" type="button">Load
                                                Report</button>
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
    <!-- <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2, .select2-multiple").select2({
            width: "100%"
        });
    </script> -->
    <!-- End Searchable Select2 -->
    <script src="{{ URL::asset('dashboard/toastr/toastr.min.js') }}"></script>
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
    <!-- <script src="{{ URL::asset('dashboard/datatables/jquery.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.validate.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ URL::asset('dashboard/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/3.1.3/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/buttons.print.min.js') }}"></script>
    <script src="vendor/datatables/buttons.server-side.js"></script> -->
    <script src="{{ URL::asset('dashboard/datatables/report/jquery.dataTables.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $('.print_record_btn').click(function() {
                
            var get_date1 = $('#date').val().split(' - ')[0];
            var [day, month, year] = get_date1.split('/');
            var from_date = year+'/'+month+'/'+day;

            var get_date2 = $('#date').val().split(' - ')[1];
            var [day1, month1, year1] = get_date2.split('/');
            var to_date = year1+'/'+month1+'/'+day1;
                // alert(from_date);
                // alert(to_date);
            var base_url = $('#base_url').val();
            var report_type = $('.report_type:checked').val();
            var supplierID = parseInt($('#supplier_id').val());
            var purchaserID = parseInt($('#purchaser_id').val());
            var productID = parseInt($('#product_id').val());
            var WarehouseID = parseInt($('#warehouse_id').val());
            if (!WarehouseID) {
            $('.warehouse_id_err').text('The Warehouse field is required.');
            return false;
            } else {
            $('.warehouse_id_err').text('');
            }
            //  alert("dsdds")
            var myModal = new bootstrap.Modal(document.getElementById('print-record-modals'), {});
            // alert(myModal)
            myModal.toggle();

            $.ajax({
                url: "{{ URL::to('grn-report/report-print') }}",
                type: 'get',
            data: {
                from_date: from_date,
                to_date: to_date,
                report_type: report_type,
                productID:productID,
                supplierID:supplierID,
                purchaserID:purchaserID,
                WarehouseID: WarehouseID
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
                            `<object data="${base_url}/resources/upload/grn/${response}" type="application/pdf" width="100%" height="800"></object>`
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
                var warehouse_id = parseInt($('#warehouse_id').val());
                var supplierID = parseInt($('#supplier_id').val());
                var purchaserID = parseInt($('#purchaser_id').val());
                var productID = parseInt($('#product_id').val());
                var report_type = $('.report_type:checked').val();
                if(!warehouse_id){
                    $('.warehouse_id_err').text('The Godown field is required.');
                     return false;
                }
                $('.warehouse_id').text('');
                $('#import-party-and-date').html('')
                if (report_type =='summary') {
                    
                    $.ajax({
                        url: `{{ URL::to('grn-report/report') }}`,
                        type: 'get',
                        dataType: 'json',
                        data:{
                            from_date:from_date,
                             to_date:to_date,
                             warehouse_id:warehouse_id,
                             productID:productID,
                            supplierID:supplierID,
                            purchaserID:purchaserID,
                             report_type:report_type,
                          
                        },
                        beforeSend: function(response) {
                            $('#import-report-table').html('<div class="loader"></div>');
                        },
                        success: function(response) {
                            console.log(response)
                            var tableData = '';
                            var totalDemand = 0;
                            var totalQtyIn = 0;
                            var totalBalance = 0;

                            var tableTag =
                                `<table class="table table-striped table-responsive" id="report-table" width="100%">
                            <thead>
                                <tr class="thead-row">
                                    <th>Sr.#</th>
                                    <th>Date</th>
                                    <th>Grn#</th>
                                    <th style="width: 30%;">Supplier Name</th>
                                    <th style="width: 30%;">Purchaser Name</th>
                                    <th style="width: 20%;">Demand Qty</th>
                                    <th style="width: 20%;">Receive Qty</th>
                                   
                                </tr>
                            </thead>
                            <tbody>`;

                            $.each(response.data, function(i, v) {
                                totalDemand += parseFloat(v.demand_qty);
                                totalQtyIn += parseFloat(v.total_qty);
                                // totalBalance += parseFloat(v.total_amount);
                                var date = new Date(v.date);
                                tableData += '<tr>';
                                tableData += `<td>${i+1}</td>`;
                                tableData +=
                                    `<td>${date.getDay()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                tableData += `<td>${v.voucher_no}</td>`;
                                tableData += `<td>${v.supplier}</td>`;
                                tableData += `<td>${v.purchaser}</td>`;
                                tableData += `<td>${Number(v.demand_qty).toLocaleString('en-US')}</td>`;
                                tableData += `<td>${Number(v.total_qty).toLocaleString('en-US')}</td>`;
                                // tableData += `<td>${v.total_amount}</td>`;
                                tableData += '</tr>';
                            });

                            tableTag += tableData;
                            tableTag += `</tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="font-weight-bold">Total</td>
                                            <td colspan="4"></td>
                                            <td class="font-weight-bold">${totalDemand.toLocaleString('en-US')}</td>
                                            <td class="font-weight-bold">${totalQtyIn.toLocaleString('en-US')}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            `;
                            // <td class="font-weight-bold">${totalBalance.toFixed(2)}</td>
                            // $('#import-report-table').html(tableTag);
                            $('#import-report-table').html(tableTag);
                            $('#import-report-table #report-table').DataTable({
                                "pageLength": 100,
                                "ordering": false
                                });


                            // $('#summary-report-table_wrapper .dt-buttons button').addClass(
                            //     'btn btn-primary btn-sm');
                            // $('#summary-report-table_wrapper .dt-buttons button')
                            //     .removeClass('dt-button');



                        }
                    });
                } else if(report_type =='detail') {
                    
                    $.ajax({
                        url: `{{ URL::to('grn-report/report') }}`,
                        type: 'get',
                        dataType: 'json',
                        data:{
                            from_date:from_date,
                            to_date:to_date,
                            report_type:report_type,
                            productID:productID,
                            supplierID:supplierID,
                            purchaserID:purchaserID,
                            warehouse_id:warehouse_id,
                          
                        },
                        beforeSend: function(response) {
                            $('#import-report-table').html('<div class="loader"></div>');
                        },
                        success: function(response) {
                            
                            var tableData = '';
                            var totalDemand = 0;
                            var totalQtyIn = 0;
                            var totalBalance = 0;

                            var tableTag =
                                `<table class="table table-striped table-responsive" id="report-table" style="width: 100%;">
                            <thead>
                                <tr class="thead-row">
                                    <th>Sr</th>
                                    <th>Date</th>
                                    <th>Grn#</th>
                                    <th style="width: 20%;">Supplier&nbsp;Name</th>
                                    <th style="width: 20%;">Purchaser&nbsp;Name</th>
                                    <th>Code</th>
                                    <th style="width: 30%;">Product</th>
                                    <th>Unit</th>
                                    <th>Demand Qty</th>
                                    <th>Receive Qty</th>
                                   
                                </tr>
                            </thead>
                            <tbody>`;
                            // <th style="width: 20%;">Godown</th>
                            // <th style="width: 20%;">Price</th>
                            //         <th style="width: 20%;">Amount</th>
                            $.each(response.data, function(i, v) {
                                totalDemand += parseFloat(v.demand_qty);
                                totalQtyIn += parseFloat(v.qty_in);
                                // totalBalance += parseFloat(v.total_amount);
                                //  alert(totalQtyIn)


                                var date = new Date(v.date);
                                tableData += '<tr>';
                                tableData += `<td>${i+1}</td>`;
                                tableData +=
                                    `<td>${date.getDay()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                tableData += `<td>${v.voucher_no}</td>`;
                                // tableData += `<td>${v.warehouse.name}</td>`;
                                if(v.supplier){
                                    tableData += `<td>${v.supplier}</td>`;
                                }else{
                                    tableData += `<td></td>`;
                                }
                                if(v.purchaser){
                                    tableData += `<td>${v.purchaser}</td>`;
                                }else{
                                    tableData += `<td></td>`;
                                }
                                
                                tableData += `<td>${v.code}</td>`;
                                tableData += `<td>${v.product_name}</td>`;
                                tableData += `<td>${v.uom}</td>`;
                                // tableData += `<td>${v.price}</td>`;
                                tableData += `<td>${Number(v.demand_qty).toLocaleString('en-US')}</td>`;
                                tableData += `<td>${Number(v.qty_in).toLocaleString('en-US')}</td>`;
                                // tableData += `<td>${v.total_amount}</td>`;
                                tableData += '</tr>';
                            });

                            tableTag += tableData;
                            tableTag += `</tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="font-weight-bold">Total</td>
                                            <td colspan="7"></td>
                                            <td class="font-weight-bold">${totalDemand.toLocaleString('en-US')}</td>
                                            <td class="font-weight-bold">${totalQtyIn.toLocaleString('en-US')}</td>
                                            
                                        </tr>
                                    </tfoot>
                                </table>
                            `;
                            // <td class="font-weight-bold">${totalBalance.toFixed(2)}</td>
                            $('#import-report-table').html(tableTag);
                            $('#import-report-table #report-table').DataTable({
                                "pageLength": 100,
                                "ordering": false
                                });
                            $('#detailed-report-table_wrapper .dt-buttons button').addClass(
                                'btn btn-primary btn-sm');
                            $('#detailed-report-table_wrapper .dt-buttons button')
                                .removeClass('dt-button');


                            // from_date = (new Date(from_date).getDate() + 1) + '/' +
                            //     (new Date(from_date).getMonth() + 1) + '/' +
                            //     new Date(from_date).getFullYear();
                            // to_date = (new Date(to_date).getDate() + 1) + '/' +
                            //     (new Date(to_date).getMonth() + 1) + '/' +
                            //     new Date(to_date).getFullYear();
                                
                            // $('#import-party-and-date').html(
                            //     `<h3 style="color:black;"><i class="fa fa-home"></i> :
                                    
                            //         <span id="import-selected-date">(${from_date} <i class="fa fa-calendar"></i> ${to_date})</span></h3>
                                    
                            //         <h3 style="color:black;">Purchaser:(${purchaser})</h3>
                            //         `
                            // );
                        }
                    });
                }
            });
        });
    </script>
    <!-- End Load Report -->
@stop
