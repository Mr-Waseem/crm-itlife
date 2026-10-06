@extends('app')
@section('head')
    <title>BATCH STOCK REPORT</title>
    <!-- {{-- <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"> --}} -->
    <link rel="stylesheet" href="{{ URL::asset('dashboard/datatables/jquery.dataTables2.min.css') }}">
     <!-- <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" /> -->
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
            <h1>BATCH STOCK REPORT</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">BATCH STOCK REPORT</a></li>
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
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="supplier_id">Warehouse <i class="fa fa-home"></i></label>
                                            {!! Form::select('warehouse_id', $warehouse, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                            ]) !!}
                                            @error('shift_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger supplier_id_err"></span>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="supplier_id">Machine <i class="fa fa-home"></i></label>
                                            {!! Form::select('machine_id', $machines, null, [
                                                'id' => 'machine_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                            ]) !!}
                                            @error('shift_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger supplier_id_err"></span>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="supplier_id">Shift <i class="fa fa-home"></i></label>
                                            {!! Form::select('shift_id', $shift, null, [
                                                'id' => 'shift_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                            ]) !!}
                                            @error('shift_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger supplier_id_err"></span>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="purchaser_id">Forman <i class="fa fa-home"></i></label>
                                            {!! Form::select('forman_id', $forman, null, [
                                                'id' => 'forman_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                            ]) !!}
                                            @error('forman_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger purchaser_id_err"></span>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="purchaser_id">Color <i class="fa fa-home"></i></label>
                                            {!! Form::select('color_id', $color, null, [
                                                'id' => 'color_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                            ]) !!}
                                            @error('color_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger purchaser_id_err"></span>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="purchaser_id">Thickness <i class="fa fa-home"></i></label>
                                            {!! Form::text('thickness', null, [
                                                'id' => 'thickness',
                                                'class' => 'form-control',
                                                'tabindex' => '2',
                                            ]) !!}
                                            @error('thickness')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger purchaser_id_err"></span>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 mt-1">
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
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="product_id">Select Type <i class="fa fa-th-list"></i></label>
                                            {!! Form::select('type', $type, null, [
                                                'id' => 'type',
                                                'class' => 'form-control select2',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-4">
                                            <input name="report" type="radio" class="with-gap report_type" id="summary" value="summary" checked>
                                            <label for="summary" >Summary</label>
                                            <input name="report" type="radio" class="with-gap report_type" id="detail" value="detail">
                                            <label for="detail">Detail</label>
                                        </div>
                                        
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            <br />
                                            <button class="btn btn-primary load-report-btn" type="button">Load Report</button>
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
    <script src="{{ URL::asset('dashboard/datatables/report/jquery.dataTables.min.js') }}"></script>

    <script type="text/javascript">
        //    $(document).ready(function() {
            
        //     });
        $(document).ready(function() {

            $('.load-report-btn').click(function() {
                var get_date1 = $('#date').val().split(' - ')[0];
                var [day, month, year] = get_date1.split('/');
                var from_date = year+'/'+month+'/'+day;
                
                var get_date2 = $('#date').val().split(' - ')[1];
                var [day1, month1, year1] = get_date2.split('/');
                var to_date = year1+'/'+month1+'/'+day1;
                // alert("ddd");
                var warehouseID = parseInt($('#warehouse_id').val());
                var machineID = parseInt($('#machine_id').val());
                var shiftID = parseInt($('#shift_id').val());
                var formanID = parseInt($('#forman_id').val());
                var colorID = parseInt($('#color_id').val());
                var thickness = $('#thickness').val();
                var productID = parseInt($('#product_id').val());
                var Receivetype = $('#type').val();
                var report_type = $('.with-gap:checked').val();
                // alert(report_type);

                // if (report_type == 'summary') {
                    // alert("ent");
                    $.ajax({
                        url: `{{ URL::to('batch-stock-report/report') }}`,
                        type: 'get',
                        dataType: 'json',
                        data:{
                            from_date:from_date,
                        to_date:to_date,
                        warehouseID:warehouseID,
                        productID:productID,
                        shiftID:shiftID,
                        machineID:machineID,
                        formanID:formanID,
                        colorID:colorID,
                        thickness:thickness,
                        Receivetype:Receivetype,
                        report_type:report_type,
                        },
                        beforeSend: function(response) {
                            $('#import-report-table').html('<div class="loader"></div>');
                        },
                        success: function(response) 
                        {
                            // if (response.data.length > 0) {
                                // alert("id")
                            if (report_type == 'summary') 
                            {
                                    var tableData = '';
                                    var grossQty = 0;
                                    var netQty = 0;
                                    var transferQty = 0;
                                    // <th style="">Code</th>   
                                    // <th style="width: 30%;">Product Name</th>
                                var tableTag =
                                    `<table class="table table-striped table-responsive" id="report-table">
                                        <thead>
                                            <tr class="thead-row">
                                                    <th>Sr.#</th>
                                                    <th>Date</th>
                                                    <th style="width: 35%;">Voucherr#&nbsp;&&nbsp;Type</th>
                                                    <th style="width:20%;">From&nbsp;Warehouse</th>
                                                    <th style="width:20%;">To&nbsp;Warehouse</th>
                                                    <th>M#</th>
                                                    <th>Shift</th>
                                                    <th>Forman&nbsp;Name</th>
                                                    <th>Opt&nbsp;Name</th>
                                                    <th>Color</th>
                                                    <th>Batch#</th>
                                                    <th>Width</th>
                                                    <th>Thick</th>
                                                    <th>G.Weight</th>
                                                    <th>Net Weight</th>
                                                    <th>Transfer</th>
                                                </tr>
                                            </thead>
                                        <tbody>`;
                                $.each(response.data, function(i, v) {
                                    grossQty += parseFloat(v.gross_weight);
                                    netQty += parseFloat(v.net_weight);
                                    transferQty += parseFloat(v.total_out);
                                    var date = new Date(v.date);
                                    tableData += '<tr class="datatable-tr-settings">';
                                    tableData += `<td>${i+1}</td>`;
                                    tableData +=
                                    `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                        // `<td>${v.date}</td>`;
                                    if(v.p_type == "BATCH STOCK TRANSFER"){
                                        tableData += `<td>${v.voucher_no} - BST</td>`;
                                    }else{
                                        tableData += `<td>${v.voucher_no} - ${v.p_type}</td>`;
                                    }
                                    
                                    if(v.from_warehouse){
                                        tableData += `<td>${v.from_warehouse}</td>`;
                                    }else{
                                        tableData += `<td></td>`;
                                    }
                                    if(v.to_warehouse){
                                        tableData += `<td>${v.to_warehouse}</td>`;
                                    }else{
                                        tableData += `<td></td>`;
                                    }
                                    
                                    tableData += `<td>${v.machine_name}</td>`;
                                    tableData += `<td>${v.shift_name}</td>`;
                                    tableData += `<td>${v.forman}</td>`;
                                    tableData += `<td>${v.operator}</td>`;
                                    tableData += `<td>${v.color}</td>`;
                                    tableData += `<td>${v.batchNo}</td>`;
                                    tableData += `<td>${v.width}</td>`;
                                    tableData += `<td>${v.thickness}</td>`;
                                    tableData += `<td>${v.gross_weight}</td>`;
                                    tableData += `<td>${v.net_weight}</td>`;
                                    // tableData += `<td>${v.code}</td>`;
                                    // tableData += `<td>${v.product_name}</td>`;
                                    tableData += `<td>${v.total_out}</td>`;
                                    tableData += '</tr>';
                                });
                                tableTag += tableData;

                                tableTag += `</tbody>
                                                <tfoot>
                                                    <tr>
                                                    <td class="font-weight-bold" colspan="13">Total</td>
                                                    <td id="summary-table-total-credit" class="font-weight-bold">${grossQty}</td>
                                                    <td id="summary-table-total-credit" class="font-weight-bold">${netQty}</td>
                                                    <td id="summary-table-total-credit" class="font-weight-bold">${transferQty}</td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                            `;
                                
                            
                                $('#import-report-table').html(tableTag);
                                $('#import-report-table #report-table').DataTable({
                                        "pageLength": 100,
                                        "ordering": false
                                    });
                             }else{
                                var tableData = '';
                                    var grossQty = 0;
                                    var netQty = 0;
                                    var transferQty = 0;
                                    // <th style="">Code</th>   
                                    // <th style="width: 30%;">Product Name</th>
                                var tableTag =
                                    `<table class="table table-striped table-responsive" id="report-table" >
                                        <thead>
                                            <tr class="thead-row">
                                                    <th>Sr.#</th>
                                                    <th>Date</th>
                                                    <th style="width: 35%;">Voucherr#&nbsp;&&nbsp;Type</th>
                                                    <th style="width:20%;">From&nbsp;Warehouse</th>
                                                    <th style="width:20%;">To&nbsp;Warehouse</th>
                                                    <th>M#</th>
                                                    <th>Shift</th>
                                                    <th>Forman&nbsp;Name</th>
                                                    <th>Opt&nbsp;Name</th>
                                                    <th>Color</th>
                                                    <th>Batch#</th>
                                                    <th>Width</th>
                                                    <th>Thick</th>
                                                    <th>G.Weight</th>
                                                    <th>Net Weight</th>
                                                    <th>Transfer</th>
                                                </tr>
                                            </thead>
                                        <tbody>
                                        
                                        `;

                                $.each(response.data, function(i, v) {
                                    grossQty += parseFloat(v.gross_weight);
                                    netQty += parseFloat(v.total_qty);
                                    transferQty += parseFloat(v.out_qty);
                                    var date = new Date(v.date);
                                    tableData += '<tr class="datatable-tr-settings">';
                                    tableData += `<td>${i+1}</td>`;
                                    tableData +=
                                    `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                        // `<td>${v.date}</td>`;
                                    if(v.p_type == "BATCH STOCK TRANSFER"){
                                        tableData += `<td>${v.voucher_no} - BST</td>`;
                                    }else{
                                        tableData += `<td>${v.voucher_no} - ${v.p_type}</td>`;
                                    }
                                    
                                    if(v.from_warehouse){
                                        tableData += `<td>${v.from_warehouse.name}</td>`;
                                    }else{
                                        tableData += `<td></td>`;
                                    }
                                    if(v.to_warehouse){
                                        tableData += `<td>${v.to_warehouse.name}</td>`;
                                    }else{
                                        tableData += `<td></td>`;
                                    }
                                    
                                    tableData += `<td>${v.machine.machine_name}</td>`;
                                    tableData += `<td>${v.shift.shift_name}</td>`;
                                    tableData += `<td>${v.forman.party_name}</td>`;
                                    tableData += `<td>${v.operator.party_name}</td>`;
                                    tableData += `<td>${v.color.name}</td>`;
                                    tableData += `<td>${v.batchNo}</td>`;
                                    tableData += `<td>${v.width}</td>`;
                                    tableData += `<td>${v.thickness}</td>`;
                                    tableData += `<td>${v.gross_weight}</td>`;
                                    tableData += `<td>${v.total_qty}</td>`;
                                    // tableData += `<td>${v.code}</td>`;
                                    // tableData += `<td>${v.product_name}</td>`;
                                    tableData += `<td>${v.out_qty}</td>`;
                                    tableData += '</tr>';
                                });
                                tableTag += tableData;

                                tableTag += `</tbody>
                                                <tfoot>
                                                    <tr>
                                                    <td class="font-weight-bold" colspan="13">Total</td>
                                                    <td id="summary-table-total-credit" class="font-weight-bold">${grossQty}</td>
                                                    <td id="summary-table-total-credit" class="font-weight-bold">${netQty}</td>
                                                    <td id="summary-table-total-credit" class="font-weight-bold">${transferQty}</td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                            `;
                                
                            
                                $('#import-report-table').html(tableTag);
                                $('#import-report-table #report-table').DataTable({
                                        "pageLength": 100,
                                        "ordering": false
                                    });
                             }
                        }
                }); 
            });

            $('.print_record_btn').click(function() {

                var get_date1 = $('#date').val().split(' - ')[0];
                var [day, month, year] = get_date1.split('/');
                var from_date = year+'/'+month+'/'+day;
                
                var get_date2 = $('#date').val().split(' - ')[1];
                var [day1, month1, year1] = get_date2.split('/');
                var to_date = year1+'/'+month1+'/'+day1;
                
                var warehouseID = parseInt($('#warehouse_id').val());
                var machineID = parseInt($('#machine_id').val());
                var shiftID = parseInt($('#shift_id').val());
                var formanID = parseInt($('#forman_id').val());
                var colorID = parseInt($('#color_id').val());
                var thickness = $('#thickness').val();
                var productID = parseInt($('#product_id').val());
                var report_type = $('.with-gap:checked').val();
                var Receivetype = $('#type').val();
                
                // alert("ddd");
                // alert(report_type);
                    var base_url = $('#base_url').val();
                    // var report_type = $('.report_type:checked').val();
                    // var supplierID = parseInt($('#supplier_id').val());
                    // if (!supplierID) {
                    // $('.supplier_id_err').text('The Supplier field is required.');
                    // return false;
                    // }
                    // if (!purchaserID) {
                    // $('.purchaser_id_err').text('The Purchaser field is required.');
                    // return false;
                    // }
                    //  alert("dsdds")
                    var myModal = new bootstrap.Modal(document.getElementById('print-record-modals'), {});
                    // alert(myModal)
                    myModal.toggle();

                    $.ajax({
                        url: "{{ URL::to('batch-stock-report/report-print') }}",
                        type: 'get',
                    data: {
                        from_date:from_date,
                        to_date:to_date,
                        warehouseID:warehouseID,
                        productID:productID,
                        shiftID:shiftID,
                        machineID:machineID,
                        formanID:formanID,
                        colorID:colorID,
                        thickness:thickness,
                        report_type:report_type,
                        Receivetype:Receivetype,
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
                                    `<object data="${base_url}/resources/upload/stock/batch/${response}" type="application/pdf" width="100%" height="800"></object>`
                                );
                            } else {
                                $('#print-receipt-modal-body').html(
                                    '<h2 style="color:red;text-align:center;">Voucher Not Exist</h2>'
                                );
                            }
                        }
                    });
            });


          
        });


</script>
@include('include.toast-messages')
@stop
