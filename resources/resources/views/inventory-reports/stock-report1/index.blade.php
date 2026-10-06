@extends('app')
@section('head')
    <title>QUANTITY REPORT</title>
    <link rel="stylesheet" href="{{ URL::asset('dashboard/datatables/jquery.dataTables2.min.css') }}">
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
            <h1>QUANTITY REPORT</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">QUANTITY REPORT</a></li>
            </ol>
        </section>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
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
                                            {!! Form::select('warehouse_id', $SingleWarehouse, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                            ]) !!}
                                            @endif
                                            @error('warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger warehouse_id_err"></span>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-1">
                                            <label for="product_id">Product <i class="fa fa-th-list"></i></label>
                                            @if(Auth::User()->role == "Admin")
                                            {!! Form::select('product_id', $products, null, [
                                                'id' => 'product_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                            ]) !!}
                                            @else
                                            <!-- {!! Form::select('product_id', $SingleWarehouseproducts, null, [
                                                'id' => 'product_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                            ]) !!} -->
                                            <select id="product_id" name="product_id" class="form-control select2">
                                                <option value="0">Select Product</option>
                                                @foreach($StockProduct as $Product)
                                                <option value="{{$Product->id}}">{{$Product->code}} - {{$Product->product_name}} - {{$Product->InQty-$Product->OutQty}}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                            @error('product_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <span class="text-danger product_id_err"></span>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-12 mt-1">
                                            <label for="warehouse_id">Choose Type<i class="fa fa-home"></i></label>
                                            {!! Form::select('bill_type', $types, null, [
                                                'id' => 'bill_type',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                            ]) !!}
                                            <span class="text-danger warehouse_id_err"></span>
                                        </div>
                                        <div class="col-lg-4 col-md-8 col-sm-12 report_type_fields">
                                            <input name="report_type" type="radio" class="with-gap report_type"
                                                id="report_type1" value="summary" checked>
                                            <label for="report_type1">Summary</label>

                                            <input name="report_type" type="radio" class="with-gap report_type"
                                                id="report_type2" value="detailed">
                                            <label for="report_type2">Detailed</label>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <br />
                                            <button class="btn btn-primary load-report-btn" type="button">Load
                                                Report</button>
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
@stop
@section('scripts')
    <script src="{{ URL::asset('dashboard/toastr/toastr.min.js') }}"></script>
    {!! Toastr::message() !!}
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
    <!-- Datatable link, it will change ascending order of table -->
    <script src="{{ URL::asset('dashboard/datatables/report/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript">
        function myfunction(product_id){
            var base_url = $('#base_url').val();
            var get_date1 = $('#date').val().split(' - ')[0];
            var [day, month, year] = get_date1.split('/');
            var from_date = year+'/'+month+'/'+day;
            var get_date2 = $('#date').val().split(' - ')[1];
            var [day1, month1, year1] = get_date2.split('/');
            var to_date = year1+'/'+month1+'/'+day1;
            // var report_type = $('.report_type:checked').val();
            var report_type = "detailed";
            // var product_id = parseInt($('#product_id').val());
            var warehouse_id = parseInt($('#warehouse_id').val().split('_')[0]);
            if(report_type == "detailed"){
                if(product_id == "0"){
                    $('.product_id_err').text('The Product field is required.');
                    return false; 
                }
            }
            var myModal = new bootstrap.Modal(document.getElementById('print-record-modals'), {});
            // alert(myModal)
            myModal.toggle();
            // alert(warehouse_id)
            // var warehouse_name = "";
            // if (warehouse_id == 0) {
            //     warehouse_name = "All Warehouses";
            // } else {
            //     warehouse_name = $('#warehouse_id').val().split('_')[1];
            // }

            $.ajax({
                // url: "{{ URL::to('products/print/voucher') }}",
                url: "{{ URL::to('inventory1/print/voucher') }}",
                type: 'get',
                data: {
                from_date: from_date,
                to_date: to_date,
                report_type: report_type,
                product_id: product_id,
                warehouse_id: warehouse_id
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
                            `<object data="${base_url}/resources/upload/stock/${response}" type="application/pdf" width="100%" height="800"></object>`
                        );
                    } else {
                        $('#print-receipt-modal-body').html(
                            '<h2 style="color:red;text-align:center;">Voucher Not Exist</h2>'
                        );
                    }
                }
            });
        }
    $(function() {

        $('.print_record_btn').click(function() {
            var myModal = new bootstrap.Modal(document.getElementById('print-record-modals'), {});
            myModal.toggle();
            var base_url = $('#base_url').val();
            var get_date1 = $('#date').val().split(' - ')[0];
            var [day, month, year] = get_date1.split('/');
            var from_date = year+'/'+month+'/'+day;
            var get_date2 = $('#date').val().split(' - ')[1];
            var [day1, month1, year1] = get_date2.split('/');
            var to_date = year1+'/'+month1+'/'+day1;
            var report_type = $('.report_type:checked').val();
            var product_id = parseInt($('#product_id').val());
            var bill_type = $('#bill_type').val();
            var warehouse_id = parseInt($('#warehouse_id').val().split('_')[0]);
            $.ajax({
                // url: "{{ URL::to('products/print/voucher') }}",
                url: "{{ URL::to('inventory1/print/voucher') }}",
                type: 'get',
                data: {
                from_date: from_date,
                to_date: to_date,
                report_type: report_type,
                product_id: product_id,
                warehouse_id: warehouse_id,
                bill_type: bill_type
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
                            `<object data="${base_url}/resources/upload/stock/${response}" type="application/pdf" width="100%" height="800"></object>`
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
            var report_type = $('.report_type:checked').val();
            var product_id = parseInt($('#product_id').val());
            var bill_type = $('#bill_type').val();
            var warehouse_id = parseInt($('#warehouse_id').val().split('_')[0]);
            var warehouse_name = "";
            if (warehouse_id == 0) {
                warehouse_name = "All Warehouses";
            } else {
                warehouse_name = $('#warehouse_id').val().split('_')[1];
            }
                $.ajax({
                    url: "{{ URL::to('inventory1/stock-report1') }}",
                    type: 'get',
                    data: {
                        from_date: from_date,
                        to_date: to_date,
                        report_type: report_type,
                        product_id: product_id,
                        warehouse_id: warehouse_id,
                        bill_type: bill_type
                    },
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#report-section').html('<div class="loader"></div>');
                    },
                    success: function(response) {
                        // alert("Dddd")
                        $('#report-section').html('');
                        var QtyIn = 0;
                        var sum = 0;
                        var OpeningQtyIn = 0;
                        var QtyOut = 0;
                        var OpeningQtyOut = 0;
                        var totalQtyIn = 0;
                        var totalQtyOut = 0;
                        var totalQty = 0;
                        var ProductBalance = 0;
                        var totalOpeningQty = 0;
                        var totalCurrentQty = 0;
                        var balqty = 0;
                        var balqtyin = 0;
                        var balqtyout = 0;
                        var TotalOpeningPack = 0;
                        var TotalReceivePack = 0;
                        var TotalIssuePack = 0;
                        var TotalClosingPack = 0;
                        var grandTotal = 0;
                        var OpeningCotton = 0;
                        var tableData = ``;
                        if (report_type == "summary") {
                                if (response.length > 0) {
                                tableData +=
                                `<div class="warehouse-name">
                                    <i class="fa fa-shopping-cart"></i> &nbsp;: ${warehouse_name} 
                                    </div>
                                    <table class="table table-striped table-responsive" id="report-table">
                                    <thead>
                                        <tr class="thead-row">
                                                <th>Sr</th>
                                                <th>Code</th>
                                                <th>Product&nbsp;Name</th>
                                                <th>Packing</th>
                                                <th style="width: 20%;">Opening&nbsp;Qty</th>
                                                <th>Recieved</th>
                                                <th>Issued</th>
                                                <th style="width: 20%;">Closing Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                                    $.each(response, function(i, v) {
                                    if((v.openingIn) !== null){
                                        OpeningQtyIn = parseFloat(v.openingIn);
                                    }else{
                                        OpeningQtyIn=0;
                                    }
                                    if((v.openingOut) !== null){
                                        OpeningQtyOut = parseFloat(v.openingOut);
                                    }else{
                                        OpeningQtyOut=0;
                                    }
                                    // alert(OpeningQtyIn)
                                    // OpeningQtyOut = parseInt(v.openingOut);
                                    OpeningQty = (OpeningQtyIn - OpeningQtyOut);
                                    totalOpeningQty += OpeningQty;
                                    if((v.qty_in) !== null){
                                        QtyIn = parseFloat(v.qty_in);
                                        totalQtyIn +=  QtyIn;
                                    }else{
                                        QtyIn=0;
                                    }
                                    if((v.qty_out) !== null){
                                        QtyOut = parseFloat(v.qty_out);
                                        totalQtyOut +=  QtyOut;
                                        
                                    }else{
                                        QtyOut=0;
                                    }
                                    if((v.packing) !== null){
                                        packingvalue = v.packing;
                                    }else{
                                        packingvalue = 0;
                                    }
                                    totalQty = (QtyIn - QtyOut);
                                    totalCurrentQty += totalQty;
                                    sum += 1;
                                    tableData +=
                                        `<tr class="tbody-row">`;
                                    tableData +=
                                        `<td>${sum}</td>`;
                                    tableData +=
                                        `<td style="width: 10%;">${v.code}</td>`;
                                        tableData +=
                                        `<td style="width: 35%;"><a href="#" onclick="myfunction('${v.id}');"><u style="color:blue;">${v.product_name}</u></a></td>`;
                                        tableData +=
                                        `<td>${packingvalue}</td>`;
                                    //     tableData +=
                                    //     `<td>${v.openingIn}</td>`;
                                    // tableData +=
                                    //     `<td>${v.openingOut}</td>`;
                                        if(OpeningQty < 0)
                                        {
                                        tableData +=
                                        `<td style="color:red;">${Number(OpeningQty).toLocaleString('en-US')}</td>`;
                                        }else if(OpeningQty > 0){
                                            tableData +=
                                        `<td>${Number(OpeningQty).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData +=
                                        `<td>0</td>`;
                                        }
                                        // if(OpeningQty > 0 && packingvalue > 0)
                                        // {
                                            
                                        //     OpeningPack = OpeningQty/packingvalue;
                                        //     // alert(OpeningQty)
                                        //     TotalOpeningPack +=OpeningPack;
                                        //     tableData +=
                                        // `<td>${OpeningPack.toFixed(2)}</td>`;
                                        // }else{
                                        //     tableData +=
                                        // `<td>0</td>`;
                                        // }
                                        if(QtyIn < 0)
                                            {
                                            tableData +=
                                            `<td style="color:red;">${Number(QtyIn).toLocaleString('en-US')}</td>`;
                                            }
                                            else if(QtyIn > 0){
                                                tableData +=
                                            `<td>${Number(QtyIn).toLocaleString('en-US')}</td>`;
                                            }
                                            else{
                                                tableData +=`<td>0</td>`;
                                        }
                                        // tableData +=
                                        // `<td style="width: 35%;">REC PACK</td>`;

                                        // if(QtyIn != 0)
                                        // if(QtyIn > 0 && packingvalue > 0)
                                        // {
                                        //     OpeningInPack = QtyIn/packingvalue;
                                        //     TotalReceivePack +=OpeningInPack;

                                        //     tableData +=
                                        // `<td>${OpeningInPack.toFixed(2)}</td>`;
                                        // }else{
                                        //     tableData +=
                                        // `<td>0</td>`;
                                        // }
                                        if(QtyOut < 0)
                                        {
                                        tableData +=
                                        `<td style="color:red;">${Number(QtyOut).toLocaleString('en-US')}</td>`;
                                        
                                        }else if(QtyOut > 0){
                                            tableData +=
                                        `<td>${Number(QtyOut).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData +=
                                        `<td>0</td>`;
                                        }
                                        // if(QtyOut != 0)
                                        // if(QtyOut > 0 && packingvalue > 0)
                                        // {
                                        //     OpeningOutPack = QtyOut/packingvalue;
                                        //     TotalIssuePack +=OpeningOutPack;
                                        //     tableData +=
                                        // `<td>${OpeningOutPack.toFixed(2)}</td>`;
                                        // }else{
                                        //     tableData +=
                                        // `<td>0</td>`;
                                        // }
                                        ProductBalance = OpeningQty+totalQty;
                                        if(ProductBalance < 0)
                                        {
                                        tableData +=
                                        `<td style="color:red;">${Number(ProductBalance).toLocaleString('en-US')}</td>`;
                                        }else if(ProductBalance > 0){
                                            tableData +=
                                        `<td>${Number(ProductBalance).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData +=
                                        `<td>0</td>`;
                                        }
                                        // tableData +=
                                        // `<td style="width: 35%;">CLOSING</td>`;

                                        // if(ProductBalance != 0)
                                       
                                        // if(ProductBalance != 0 && packingvalue > 0)
                                        //     {
                                        //         OpeningClosingPack = ProductBalance/packingvalue;
                                        //         TotalClosingPack +=OpeningClosingPack;
                                        //         tableData +=
                                        //     `<td>${OpeningClosingPack.toFixed(2)}</td>`;
                                        //     }else{
                                        //         tableData +=
                                        //     `<td>0</td>`;
                                        //     }
                                    tableData += `</tr>`;
                                });
                                var grandTotal = totalOpeningQty+totalCurrentQty;
                                tableData += `<tr class="tfoot-row">
                                        <td>Total</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="font-weight-bold">${totalOpeningQty.toLocaleString('en-US')}</td>
                                        <td class="font-weight-bold">${totalQtyIn.toLocaleString('en-US')}</td>
                                        <td class="font-weight-bold">${totalQtyOut.toLocaleString('en-US')}</td>
                                        <td class="font-weight-bold">${grandTotal.toLocaleString('en-US')}</td>
                                    </tr>`;
                                tableData += `</tbody></table>`;
                                $('#report-section').html(tableData);
                                $('#report-section #report-table').DataTable({
                                    "pageLength": 100,
                                    "ordering": false
                                });
                            } 
                            else {
                                $('#report-section').html(
                                    "<h4 class='text-danger text-center font-weight-bold'>Records Not Found...</h4>"
                                );
                            }
                        }
                        else if (report_type == "detailed") {
                            //    alert("det")
                            //    alert(response.openingStock)
                                if(response.openingStock){
                                    if((response.openingStock.qty_in) !== null){
                                    OpeningInvalue = parseFloat(response.openingStock.qty_in);
                                    }
                                    else{
                                    OpeningInvalue=0;
                                    }
                                // alert(OpeningInvalue)
                                if((response.openingStock.qty_out) !== null){
                                    OpeningOutvalue = parseFloat(response.openingStock.qty_out);
                                    }
                                    else{
                                    OpeningOutvalue=0;
                                    }
                                }
                                else{
                                    OpeningInvalue=0;
                                    OpeningOutvalue=0;
                                }
                            if(response.openingStock.packing != null){
                                SinglePacking = response.openingStock.packing;
                            }else{
                                SinglePacking=0;
                            }
                            // alert(OpeningInvalue)
                            // alert(OpeningOutvalue)
                            //    alert(response.openingStock.qty_out)
                            // alert(response.openingStock.qty_in)
                            // if (response.stockReportDetailWise.length > 0) {
                                var totalopening = OpeningInvalue - OpeningOutvalue;
                                var totalopenings = totalopening;
                                // .toLocaleString('en-US')
                                    // var totalopening = totalopenings.toLocaleString('en-US')
                                if(totalopenings != 0 && SinglePacking > 0)
                                {
                                    // OpeningCotton = totalopenings*SinglePacking;
                                    OpeningCotton = parseFloat(totalopenings) / parseFloat(SinglePacking);
                                }else{
                                    OpeningCotton = 0;
                                }
                                tableData +=
                                    ` <table class="table table-striped table-responsive" id="report-table">
                                        <thead>
                                            <tr class="thead-row">
                                                <th>Sr</th>
                                                <th>Date</th>
                                                <th>Vr.Type</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th style="width: 25%;">Party Name</th>
                                                <th style="width: 30%;">Product Name</th>
                                                <th>Packing</th>
                                                <th>Rec/Trf</th>
                                                <th>Issue/Trf</th>
                                                <th>Closing Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="tbody-row">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align:left;">OPENING STOCK</td>
                                        <td>${SinglePacking}</td>
                                        <td></td>
                                        <td></td>
                                        <td>${Number(totalopenings).toLocaleString('en-US')}</td>
                                        </tr>`;
                                $.each(response.stockReportDetailWise, function(i, v) {
                                    // QtyIn += parseInt(v.qty_in);
                                    if((v.qty_in) !== null){
                                        QtyIn = parseFloat(v.qty_in);
                                        totalQtyIn +=  QtyIn;
                                    }else{
                                        QtyIn=0;
                                    }
                                    // QtyOut += parseInt(v.qty_out);
                                    if((v.qty_out) !== null){
                                        QtyOut = parseFloat(v.qty_out);
                                        totalQtyOut +=  QtyOut;
                                    }else{
                                        QtyOut=0;
                                    }
                                    if((v.product.packing) !== null){
                                        packingvalue = v.product.packing;
                                    }else{
                                        packingvalue = 0;
                                    }
                                    // totalQtyIn = QtyIn;
                                    // totalQtyOut = QtyOut;
                                    totalQty = (QtyIn - QtyOut);
                                    var date = new Date(v.date);
                                    tableData +=
                                        `<tr class="tbody-row">`;
                                    tableData +=
                                        `<td>${i+1}</td>`;
                                    tableData +=
                                        `<td>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}</td>`;
                                    if (v.type == 'GRN') {
                                        tableData += `<td>GRN-${v.voucher_no}</td>`;
                                    } else if (v.type == 'STOCK TRANSFER') {
                                        tableData += `<td>TRANS-${v.voucher_no}</td>`;
                                    } else if (v.type == 'Delivery Challan') {
                                        tableData += `<td>DC-${v.voucher_no}</td>`;
                                    }
                                    else if (v.type == 'DCNonGST') {
                                        tableData += `<td>DC-D-${v.voucher_no}</td>`;
                                    }
                                    else if (v.type == 'DC') {
                                        tableData += `<td>DC-O-${v.voucher_no}</td>`;
                                    }
                                    else if (v.type == 'OPENING STOCK') {
                                        tableData += `<td>OS-${v.voucher_no}</td>`;
                                    }
                                    else if (v.type == 'SALE') {
                                        tableData += `<td>SALE-${v.voucher_no}</td>`;
                                    }
                                    else if (v.type == 'SALES TAX') {
                                        tableData += `<td>STI-${v.voucher_no}</td>`;
                                    }
                                    else if (v.type == 'PRODUCTION') {
                                        tableData += `<td>PRO-${v.voucher_no}</td>`;
                                    }
                                    else if (v.type == 'THERMOFORMING PRODUCTION') {
                                        tableData += `<td>THERMO.PRO-${v.voucher_no}</td>`;
                                    }
                                    else if (v.type == 'BATCH STOCK TRANSFER') {
                                        tableData += `<td>BST-${v.voucher_no}</td>`;
                                    }
                                    else {
                                        tableData += `<td>${v.type}-${v.voucher_no}</td>`;
                                    }

                                    if(v.type == "PRODUCTION ONE" || v.type == "PRODUCTION"){
                                        tableData +=
                                            `<td></td>`;
                                            tableData +=
                                            `<td></td>`;
                                    }else{
                                        if(v.godownstock){
                                        if(v.godownstock.warehouse_from){
                                        tableData +=
                                            `<td>
                                                ${v.godownstock.warehouse_from.name}
                                            </td>`;
                                        }else{
                                            tableData +=
                                            `<td></td>`;
                                        }

                                        if(v.godownstock.warehouse_to){
                                        tableData +=
                                        `<td>${v.godownstock.warehouse_to.name}</td>`;
                                        }else{
                                            tableData +=
                                            `<td></td>`;
                                        }
                                    }else{
                                        if(v.delivery_challan){
                                        //For DC Stock, DC Data not goes to godownstock table
                                            if(v.delivery_challan.warehouse){
                                                tableData +=
                                                `<td>${v.delivery_challan.warehouse.name}</td>`;
                                                tableData +=`<td></td>`;
                                            }else{
                                                tableData +=`<td></td>`;
                                                tableData +=`<td></td>`;
                                            }
                                            
                                        }else{
                                          tableData +=`<td></td>`;
                                        tableData +=`<td></td>`;  
                                        }
                                        // tableData +=`<td></td>`;
                                        // tableData +=`<td></td>`;   
                                    }
                                    }
                                    if (v.party != null) {
                                        tableData +=
                                            `<td>${v.party.code} - ${v.party.party_name}</td>`;
                                    } else {
                                        tableData +=
                                            `<td> </td>`;
                                    }
                                    if (v.product != null) {
                                        tableData +=
                                            `<td>${v.product.code} - ${v.product.product_name}</td>`;
                                    } else {
                                        tableData +=
                                            `<td> </td>`;
                                    }
                                    if (v.product != null) {
                                        tableData +=
                                            `<td>${packingvalue} </td>`;
                                    } else {
                                        tableData +=
                                            `<td> </td>`;
                                    }
                                    // var qtyin = parseInt(OpeningInvalue) + parseInt(v.qty_in);
                                    // var qtyout = parseInt(OpeningOutvalue) + parseInt(v.qty_out);
                                    // tableData +=
                                    //     `<td>${QtyIn.toLocaleString('en-US')}</td>`;
                                        if(QtyIn < 0)
                                        {
                                        tableData +=`<td style="color:red;">${Number(QtyIn).toLocaleString('en-US')}</td>`;
                                        }else if(QtyIn > 0){
                                            tableData +=`<td>${Number(QtyIn).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData +=`<td></td>`;
                                        }
                                        if(QtyOut < 0)
                                        {
                                        tableData +=
                                        `<td style="color:red;">${Number(QtyOut).toLocaleString('en-US')}</td>`;
                                        }else if(QtyOut){
                                            tableData +=`<td>${Number(QtyOut).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData +=`<td>0</td>`;
                                        }
                                        balqtyin = balqtyin + QtyIn;
                                        balqtyout = balqtyout + QtyOut;
                                        balqty = totalopening + balqtyin - balqtyout;
                                        if(balqty < 0)
                                        {
                                        tableData +=
                                        `<td style="color:red;">${Number(balqty).toLocaleString('en-US')}</td>`;
                                        }else if(balqty > 0){
                                            tableData +=
                                        `<td>${Number(balqty).toLocaleString('en-US')}</td>`;
                                        }else{
                                            tableData +=
                                        `<td>000</td>`;
                                        }
                                        // tableData +=
                                        // `<td>TOTAL</td>`;
                                    // tableData +=
                                    //     `<td>${balqty}</td>`;
                                        // `<td>${QtyIn - QtyOut}</td>`;
                                    tableData += `</tr>`;
                                });
                                    // GrandInQty = parseInt(OpeningInvalue)+parseInt(QtyIn);
                                    GrandOutQty = parseInt(OpeningOutvalue)+parseInt(QtyOut);
                                    GrandDetailTotal = totalopening+totalQtyIn-totalQtyOut;
                                    OpeningClosingPack = TotalReceivePack-TotalIssuePack+OpeningCotton;
                                tableData += `<tr class="tfoot-row">
                                        <td>Total</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="font-weight-bold">${totalQtyIn.toLocaleString('en-US')}</td>
                                        <td class="font-weight-bold">${totalQtyOut.toLocaleString('en-US')}</td>
                                        <td class="font-weight-bold">${GrandDetailTotal.toLocaleString('en-US')}</td>
                                    </tr>`;
                                // <td class="font-weight-bold">${OpeningClosingPack.toLocaleString('en-US')}</td>
                                // .toLocaleString('en-US')
                                tableData += `</tbody></table>`;
                                $('#report-section').html(tableData);

                                $('#report-section #report-table').DataTable({
                                    "pageLength": 100,
                                    "ordering": false
                                });
                            // } else {
                            //     $('#report-section ').html(
                            //         "<h4 class='text-danger text-center font-weight-bold'>Record Not Found...</h4>"
                            //     );
                            // }
                        } 
                    }
                });
            // alert($('.dt-button').attr('tabindex'));
            $('.dt-button').removeClass('dt-button').addClass('btn btn-primary');
        });

        $('#warehouse_id').change(function() {
                var warehouseID = $('#warehouse_id').val().split('_')[0];
                    $.ajax({
                        url: `{{ URL::to('inventory/load-stock-products') }}`,
                        type: 'get',
                        dataType: 'json',
                        data:{
                            warehouseID:warehouseID,
                        },
                        success: function(response) 
                        {
                            if (response.data != '') {
                                var option = '';
                                option +=`<option value="0">All Products</option>`;
                            $.each(response.data, function(i, v) {
                                option +=
                                    `<option value="${v.id}">${v.code} - ${v.product_name}</option>`;
                            });
                            $('#product_id').html(option);
                            $("#product_id").select2('open');
                            }else{
                                var option = '';
                                option +=`<option value="0">No Products Found!</option>`;
                                $('#product_id').html(option);
                            } 
                        }
                }); 
            });
    });
</script>
@include('include.toast-messages')
@stop
