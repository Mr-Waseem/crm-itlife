@extends('app')
@section('head')
    <title>SalesTax Report</title>
    <!-- {{-- <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"> --}} -->
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
            /* width: 100%; */
        }

        #report-table thead .thead-row,
        #report-table tbody .tfoot-row {
            background-color: #666EE7;
            color: white;
            /* width: 100%; */
        }

        #report-table tbody .thead-row th,
        #report-table tbody .tbody-row td,
        #report-table tbody .tfoot-row td {
            border: 1px solid white;
            padding: .5rem;
            /* width: 100%; */
        }
    </style>
@stop
@section('content')
<div class="content-wrapper">
        <section class="content-header">
            <h1>
            SALESTAX REPORT
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">SALESTAX REPORT</a></li>
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
                                            <!-- {!! Form::text('date', date('Y-m-d') . ' - ' . date('Y-m-d'), [
                                                'id' => 'date',
                                                'class' => 'form-control',
                                                'tabindex' => '0',
                                                'required' => 'required',
                                            ]) !!} -->

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
                                        <div class="col-lg-3 col-md-3 col-sm-12 mt-1">
                                            <label for="warehouse_id">Godown <i class="fa fa-home"></i></label>
                                            @if(Auth::User()->role == "Admin")
                                            {!! Form::select('warehouse_id', $warehouse, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                            ]) !!}
                                            @else
                                            {!! Form::select('warehouse_id', $warehouse, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                            ]) !!}
                                            @endif
                                            <span class="text-danger warehouse_id_err"></span>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 mt-1">
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
                                        <div class="col-lg-3 col-md-4 col-sm-12 mt-1">
                                            <label for="customer_id">Sale Type <i class="fa fa-user"></i></label>
                                            {!! Form::select('sale_type', $salestype, null, [
                                                'id' => 'sale_type',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 report_type_fields">
                                        <input name="report_type" type="radio" class="with-gap report_type" id="report_type1"
                                                value="summary" checked>
                                            <label for="report_type1">Summary</label>
                                        <input name="report_type" type="radio" class="with-gap report_type" id="report_type2"
                                                value="detailed" > &emsp13;&emsp13;
                                            <label for="report_type2">Detailed</label>
                                           
                                           
                                            
                                            @error('report_type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <br />
                                            <!-- <button class="btn btn-primary load-report-btn" type="button">Load Report</button> -->
                                            <button class="btn btn-primary print_record_btn" type="button">Load Report</button>
                                            <!-- <button class="btn btn-primary btn-md print_record_btn" type="button"><i class="fa fa-print" style="color: white;"></i></button> -->
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
    <script src="{{ URL::asset('dashboard/datatables/report/jquery.dataTables.min.js') }}"></script>

    <script type="text/javascript">
        //    $(document).ready(function() {
            
        //     });
        $(document).ready(function() {

            $('.print_record_btn').click(function() {

                    var get_date1 = $('#date').val().split(' - ')[0];
                    var [day, month, year] = get_date1.split('/');
                    var from_date = year+'/'+month+'/'+day;
                    
                    var get_date2 = $('#date').val().split(' - ')[1];
                    var [day1, month1, year1] = get_date2.split('/');
                    var to_date = year1+'/'+month1+'/'+day1;

                    var base_url = $('#base_url').val();
                    var report_type = $('.report_type:checked').val();
                    var customer_id = parseInt($('#customer_id').val());
                    var warehouseID = parseInt($('#warehouse_id').val());
                    var productID = parseInt($('#product_id').val());
                    var saleType = $('#sale_type').val();
                    // if (!Customerid) {
                    // $('.customer_id_err').text('The customer field is required.');
                    //     return false;
                    // } else {
                    //     $('.customer_id_err').text('');
                    // }
                //  alert("dsdds");
                    var myModal = new bootstrap.Modal(document.getElementById('print-record-modals'), {});
                    // alert(myModal)
                    myModal.toggle();

                    $.ajax({
                        url: "{{ URL::to('salestax-report/print/report') }}",
                        type: 'get',
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                        report_type: report_type,
                        customer_id: customer_id,
                        saleType: saleType,
                        productID: productID,
                        warehouseID: warehouseID
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
                                    `<object data="${base_url}/resources/upload/sales-voucher/${response}" type="application/pdf" width="100%" height="800"></object>`
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
                var get_date1 = $('#date').val().split(' - ')[0];
                var [day, month, year] = get_date1.split('/');
                var from_date = year+'/'+month+'/'+day;
                
                var get_date2 = $('#date').val().split(' - ')[1];
                var [day1, month1, year1] = get_date2.split('/');
                var to_date = year1+'/'+month1+'/'+day1;

                var customer_id = parseInt($('#customer_id').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                var productID = parseInt($('#product_id').val());
                var saleType = $('#sale_type').val();
                var report_type = $('.with-gap:checked').val();
                    $.ajax({
                        url: `{{ URL::to('sales-report') }}`,
                        type: 'get',
                        data: {
                            from_date: from_date, 
                            to_date: to_date, 
                            customer_id: customer_id, 
                            report_type: report_type, 
                            saleType: saleType, 
                            productID: productID, 
                            warehouseID: warehouseID
                        },
                        dataType: 'json',
                        beforeSend: function(response) {
                            $('#import-report-table').html('<div class="loader"></div>');
                        },
                        success: function(response) 
                        {
                            // if (response.data.length > 0) {
                                // alert("id");
                            var tableData = '';
                            var totalDebit = 0;
                            var totalCredit = 0;
                            var totalbalance = 0;
                            if (report_type == 'summary') 
                            {
                                var tableTag =
                                    `<table class="table table-striped table-responsive" id="report-table" >
                                        <thead>
                                            <tr class="thead-row">
                                                <th style="width: 5%;">Sr.#</th>
                                                <th style="width: 10%;">Date</th>
                                                <th style="width: 10%;">Vr.No</th>
                                                <th style="width: 40%;">Account Name</th>
                                                <th style="width: 10%;">Qty</th>
                                                <th style="width: 10%;">Total</th>
                                                <th style="width: 10%;">Discount</th>
                                                <th style="width: 10%;">Extra.Chrgs</th>
                                                <th style="width: 10%;">Extra.Disc</th>
                                                <th style="width: 15%;">Amount</th>
                                                </tr>
                                            </thead>
                                        <tbody>`;
                                        var totalQty = 0;
                                        var totalTotal = 0;
                                        var totalDiscount = 0;
                                        var totalExtraChrgs = 0;
                                        var totalExtraDis = 0;
                                        var totalAmount1 = 0;
                                    $.each(response.data, function(i, v) {
                                       
                                        var date = new Date(v.date);
                                        tableData += '<tr class="datatable-tr-settings">';
                                        tableData += `<td>${i+1}</td>`;
                                        tableData +=
                                        `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                        tableData += `<td>${v.voucher_no}</td>`;
                                        tableData += `<td>${v.party_name}</td>`;
                                        if(v.qty > 0){
                                            totalQty += parseFloat(v.qty);
                                            tableData += `<td>${Number(v.qty).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData += `<td></td>`;
                                        }
                                        var totalamnt = parseFloat(v.total) + parseFloat(v.discount);
                                        if(totalamnt > 0){
                                            totalTotal += parseFloat(totalamnt);
                                            tableData += `<td>${Number(totalamnt).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData += `<td></td>`;
                                        }
                                        if(v.discount > 0){
                                            totalDiscount += parseFloat(v.discount);
                                            tableData += `<td>${Number(v.discount).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData += `<td></td>`;
                                        }
                                        var extraChrgAmount = 0;
                                        if(v.extra_charges > 0){
                                            extraChrgAmount = v.extra_charges;
                                            totalExtraChrgs += parseFloat(extraChrgAmount);
                                            tableData += `<td>${Number(v.extra_charges).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData += `<td></td>`;
                                        }
                                        
                                        var extraDisAmount = 0;
                                        if(v.extra_discount > 0){
                                            extraDisAmount = v.extra_discount;
                                            totalExtraDis += parseFloat(extraDisAmount);
                                            tableData += `<td>${Number(v.extra_discount).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData += `<td></td>`;
                                        }
                                        var totalbalance = parseFloat(v.total) + parseFloat(extraChrgAmount) - parseFloat(extraDisAmount);
                                        // alert(totalbalance);
                                        if(totalbalance > 0){
                                            totalAmount1 += parseFloat(totalbalance);
                                            tableData += `<td>${Number(totalbalance).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData += `<td></td>`;
                                        }
                                        tableData += '</tr>';
                                    });
                                        tableTag += tableData;
                                        // var grandbalance = totalDebit-totalCredit+totalopening;
                                        // if(totalDebit-totalCredit != '0'){
                                            tableTag += `</tbody>
                                                        <tfoot>
                                                            <tr>
                                                            <td class="font-weight-bold">Total</td>
                                                            <td colspan="3"></td>
                                                            <td id="summary-table-total-debit" class="font-weight-bold">${totalQty.toLocaleString('en-US')}</td>
                                                            <td id="summary-table-total-credit" class="font-weight-bold">${totalTotal.toLocaleString('en-US')}</td>
                                                            <td id="summary-table-total-credit" class="font-weight-bold">${totalDiscount.toLocaleString('en-US')}</td>
                                                            <td id="summary-table-total-credit" class="font-weight-bold">${totalExtraChrgs.toLocaleString('en-US')}</td>
                                                            <td id="summary-table-total-credit" class="font-weight-bold">${totalExtraDis.toLocaleString('en-US')}</td>
                                                            <td id="summary-table-total-credit" class="font-weight-bold">${totalAmount1.toLocaleString('en-US')}</td>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                    `;

                                        // }
                                        $('#import-report-table').html(tableTag);
                                        $('#import-report-table #report-table').DataTable({
                                            "pageLength": 100,
                                                // "order": [[ 1, "asc" ]],
                                                "ordering": false 
                                        });
                            }
                                //detail
                                else
                            {
                                // <th>Sr.#</th>
                                var tableTag =
                                    `<table class="table table-striped table-responsive" id="report-table" >
                                        <thead>
                                            <tr class="thead-row">
                                                    
                                                    <th style="width: 5%;">Date</th>
                                                    <th style="width: 5%;">Vr#</th>
                                                    <th style="width: 40%;">Account Name</th>
                                                    <th style="width: 40%;">Product Name</th>
                                                    <th style="width: 5%;">Thick</th>
                                                    <th style="width: 5%;">Qty</th>
                                                    <th style="width: 5%;">Rate</th>
                                                    <th style="width: 10%;">Total</th>
                                                    <th style="width: 5%;">Disc</th>
                                                    <th style="width: 15%;">Amount</th>
                                                </tr>
                                            </thead>
                                        <tbody>`;
                                  var grandqty1 = 0; var qrandtotal1 = 0; var granddiscount1 =0; var grandAmont1 = 0;
                                    $.each(response.data, function(i, v1) {
                                        var totalqty = 0; 
                                        var totalTotal = 0; 
                                        // var totalExtraCh = 0; 
                                        var totalExtraDis = 0; 
                                        var totalAmount = 0;
                                    $.each(v1.sale_purchase_details, function(i, v) {
                                        var date = new Date(v.date);
                                        tableData += '<tr class="datatable-tr-settings">';
                                        // tableData += `<td>${i+1}</td>`;
                                        tableData +=
                                        `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                        tableData += `<td><a href="{{asset('issuance-return')}}/${v.voucher_id}" target="__blank"><u style="color:blue;">${v.voucher_no}</a></td>`;
                                                
                                        tableData += `<td>${v1.party.party_name}</td>`;
                                        tableData += `<td>${v.product.code}-${v.product.product_name}</td>`;
                                        if(v.thickness > 0){
                                            tableData += `<td>${Number(v.thickness).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData += `<td></td>`;
                                        }
                                        if(v.qty > 0){
                                            totalqty +=  parseFloat(v.qty);
                                            tableData += `<td>${Number(v.qty).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData += `<td></td>`;
                                        }
                                        if(v.rate > 0){
                                            tableData += `<td>${Number(v.rate).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData += `<td></td>`;
                                        }
                                       var totalamnt = v.qty*v.rate;
                                        
                                       totalTotal +=  parseFloat(totalamnt);
                                        tableData += `<td>${Number(totalamnt).toLocaleString('en-US')}</td>`;
                                        if(v.discount > 0){
                                            totalExtraDis +=  parseFloat(v.discount);
                                            tableData += `<td>${Number(v.discount).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData += `<td></td>`;
                                        }
                                        if(v.total > 0){
                                            totalAmount +=  parseFloat(v.total);
                                        }else{
                                            totalAmount +=  0;
                                        }
                                        tableData += `<td>${Number(v.total).toLocaleString('en-US')}</td>`;
                                    });
                                    tableData += '<tr>';
                                        tableData += `<th>Total</th>`;
                                        // tableData += `<th></th>`;
                                        tableData += `<th></th>`;
                                        tableData += `<th></th>`;
                                        tableData += `<th></th>`;
                                        tableData += `<th></th>`;
                                        tableData += `<th>${Number(totalqty).toLocaleString('en-US')}</th>`;
                                        tableData += `<th></th>`;
                                        tableData += `<th>${Number(totalTotal).toLocaleString('en-US')}</th>`;
                                        tableData += `<th>${Number(totalExtraDis).toLocaleString('en-US')}</th>`;
                                        tableData += `<th>${Number(totalAmount).toLocaleString('en-US')}</th>`;
                                        tableData += '</tr>';
                                        grandqty1 += parseFloat(totalqty); 
                                        qrandtotal1 += parseFloat(totalTotal); 
                                        granddiscount1 += parseFloat(totalExtraDis); 
                                        var grandExDiscount = 0;
                                        if(v1.extra_discount > 0){
                                            var grandExDiscount = v1.extra_discount;
                                        }

                                        var grandExCharges = 0;
                                        if(v1.extra_charges > 0){
                                            var grandExCharges = v1.extra_charges;
                                        }
                                        tableData += '<tr>';
                                        tableData += `<th>Ext.Charges</th>`;
                                        tableData += `<th>${Number(grandExCharges).toLocaleString('en-US')}</th>`;
                                        tableData += `<th>Ext.Discount</th>`;
                                        tableData += `<th>${Number(grandExDiscount).toLocaleString('en-US')}</th>`;
                                        // tableData += `<th></th>`;
                                        tableData += `<th></th>`;
                                        tableData += `<th></th>`;
                                        tableData += `<th></th>`;
                                        tableData += `<th></th>`;
                                        tableData += `<th></th>`;
                                         var grandvalue = parseFloat(totalAmount) + parseFloat(grandExCharges) - parseFloat(grandExDiscount)
                                         tableData += `<th style="font-size: 16px;">${Number(grandvalue).toLocaleString('en-US')}</th>`;
                                        tableData += '</tr>';
                                        grandAmont1 += parseFloat(grandvalue); 
                                    });
                                    tableData += '<tr>';
                                        tableData += `<th>Total Sale</th>`;
                                        // tableData += `<th></th>`;
                                        tableData += `<th></th>`;
                                        tableData += `<th></th>`;
                                        tableData += `<th></th>`;
                                        tableData += `<th></th>`;
                                        tableData += `<th>${Number(grandqty1).toLocaleString('en-US')}</th>`;
                                        tableData += `<th></th>`;
                                        tableData += `<th>${Number(qrandtotal1).toLocaleString('en-US')}</th>`;
                                        tableData += `<th>${Number(granddiscount1).toLocaleString('en-US')}</th>`;
                                        tableData += `<th>${Number(grandAmont1).toLocaleString('en-US')}</th>`;
                                        tableData += '</tr>';
                                        tableTag += tableData;
                                        $('#import-report-table').html(tableTag);
                                        $('#import-report-table #report-table').DataTable({
                                            "pageLength": 100,
                                                // "order": [[ 1, "asc" ]],
                                                "ordering": false 
                                    });
                            }
                        } 
                    });
            });
        });
</script>
@include('include.toast-messages')
@stop
