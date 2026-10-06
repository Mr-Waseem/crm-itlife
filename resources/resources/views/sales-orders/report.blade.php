@extends('app')
@section('head')
    <title>SALE ORDER REPORT</title>
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
                SALE ORDER REPORT
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">SALE ORDER REPORT</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>&nbsp; SALE ORDER REPORT</h6>
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
                                        @if(Auth::User()->role == 'Admin')
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-1">
                                            <label for="party_id">Party <i class="fa fa-th-list"></i></label>
                                            
                                            {!! Form::select('party_id', $party, null, [
                                                'id' => 'party_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                                'required' => 'required',
                                            ]) !!}
                                            
                                            @error('party_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger party_id_err"></span>
                                        </div>
                                        @endif
                                        @if(Auth::User()->role == 'Normal User')
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-1">
                                            <label for="party_id">Party <i class="fa fa-th-list"></i></label>
                                            <select id="party_id" name="party_id" class="form-control" disabled>
                                                <option value="{{ Auth::User()->party_id }}">{{ Auth::User()->name }}</option>
                                            </select>
                                            @error('party_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger party_id_err"></span>
                                        </div>
                                        @endif
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
        <div class="row" style="margin-top: -30px;">
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
                                        <div class="col-lg-12 col-md-12 col-sm-12" id="import-party-and-date"></div>
                                        <div class="col-lg-12 col-md-12 col-sm-12" id="import-report-table"></div>
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
                $('#warehouse_id').val(null).select2();
                
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
                var party_id = parseInt($('#party_id').val());
                var report_type = $('.report_type:checked').val();
                if(!party_id){
                    $('.party_id_err').text('The Party field is required.');
                     return false;
                }
                $('.party_id').text('');
                $('#import-party-and-date').html('')
                if (report_type =='summary') {
                    
                    $.ajax({
                        url: `{{ URL::to('sales-order/report') }}`,
                        type: 'get',
                        dataType: 'json',
                        data:{
                            from_date:from_date,
                             to_date:to_date,
                             party_id:party_id,
                             report_type:report_type,
                          
                        },
                        beforeSend: function(response) {
                            $('#import-report-table').html('<div class="loader"></div>');
                        },
                        success: function(response) {
                            console.log(response)
                            var tableData = '';
                            var totalQty = 0;
                            var totalBalance = 0;

                            var tableTag =
                                `<table class="table table-bordered" id="summary-report-table">
                            <thead>
                                <tr>
                                    <th>Sr.#</th>
                                    <th>Date</th>
                                    <th>Bill#</th>
                                    <th>Party</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>`;

                            $.each(response.data, function(i, v) {
                                totalQty += parseFloat(v.total_qty);
                                totalBalance += parseFloat(v.total_sale_rate);
                                var date = new Date(v.voucher_date);
                                tableData += '<tr>';
                                tableData += `<td>${i+1}</td>`;
                                tableData +=
                                    `<td>${date.getDay()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                tableData += `<td>${v.voucher_no}</td>`;
                                tableData += `<td>${v.party.party_name}</td>`;
                                tableData += `<td>${v.total_qty}</td>`;
                                tableData += `<td>${v.total_sale_rate}</td>`;
                                tableData += '</tr>';
                            });

                            tableTag += tableData;
                            tableTag += `</tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="font-weight-bold">Total</td>
                                            <td colspan="3"></td>
                                            <td class="font-weight-bold">${totalQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}</td>
                                            <td class="font-weight-bold">${totalBalance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}</td>
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
                                        },
                                        orientation: 'landscape',
                                        pageSize: 'A4',
                                        alignment: "center",
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

                        }
                    });
                } else if(report_type =='detail') {
                    
                    $.ajax({
                        url: `{{ URL::to('sales-order/report') }}`,
                        type: 'get',
                        dataType: 'json',
                        data:{
                            from_date:from_date,
                            to_date:to_date,
                            report_type:report_type,
                            party_id:party_id,
                          
                        },
                        beforeSend: function(response) {
                            $('#import-report-table').html('<div class="loader"></div>');
                        },
                        success: function(response) {
                            
                            var tableData = '';
                            var totalQty = 0;
                            var totalBalance = 0;

                            var tableTag =
                                `<table class="table table-bordered" id="detailed-report-table">
                            <thead>
                                <tr>
                                    <th>Sr.#</th>
                                    <th>Date</th>
                                    <th>Bill#</th>
                                    <th>Party</th>
                                    <th>Product</th>
                                    <th>Unit</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>`;

                            $.each(response.data, function(i, v) {
                                totalQty += parseInt(v.qty);
                                totalBalance += parseInt(v.sale_amount);
                                //  alert(totalQtyIn)


                                var date = new Date(v.voucher_date);
                                tableData += '<tr>';
                                tableData += `<td>${i+1}</td>`;
                                tableData +=
                                    `<td>${date.getDay()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                tableData += `<td>${v.voucher_no}</td>`;
                                tableData += `<td>${v.party.party_name}</td>`;
                                tableData += `<td>${v.product.product_name}</td>`;
                                tableData += `<td>${v.product.uom}</td>`;
                                tableData += `<td>${v.sale_rate}</td>`;
                                tableData += `<td>${v.qty}</td>`;
                                tableData += `<td>${v.sale_amount}</td>`;
                                tableData += '</tr>';
                            });

                            tableTag += tableData;
                            tableTag += `</tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="font-weight-bold">Total</td>
                                            <td colspan="6"></td>
                                            <td class="font-weight-bold">${totalQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') }</td>
                                            <td class="font-weight-bold">${totalBalance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}</td>
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


                            from_date = (new Date(from_date).getDate() + 1) + '/' +
                                (new Date(from_date).getMonth() + 1) + '/' +
                                new Date(from_date).getFullYear();
                            to_date = (new Date(to_date).getDate() + 1) + '/' +
                                (new Date(to_date).getMonth() + 1) + '/' +
                                new Date(to_date).getFullYear();
                                
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
