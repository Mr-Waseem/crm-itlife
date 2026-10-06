@extends('app')
@section('head')
    <title>Delivery Challan Report</title>
    <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!--  Select 2 library start-->
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!--  Select 2 library end-->

    <!-- Date Range Picker -->
    <link href="{{ URL::asset('dashboard/date-range-picker/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/date-range-picker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/date-range-picker/css/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        @media only screen and (min-width: 768px) {
            .report_type_fields{
                margin-top: 40px;
            }
        }
        @media only screen and (max-width: 768px) {
            .report_type_fields{
                margin-top: 10px;
            }
        }
    </style>
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Delivery Challan Report
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Delivery Challan Report</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Delivery Challan Report</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'javascript:void(0);', 'class' => 'form-horizontal', 'id' => 'sales-report-form']) !!}
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-1">
                                            <label for="date"><i class="fa fa-calendar-o"></i> As On</label>
                                            {!! Form::text('date', date('Y-m-d') .' - '. date('Y-m-d'), [
                                                'id' => 'date',
                                                'class' => 'form-control',
                                                'tabindex' => '0',
                                                'required'=>'required'
                                            ]) !!}
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 report_type_fields">
                                            <input name="group1" type="radio" class="with-gap" id="report_type1" value="summary" checked>
                                            <label for="report_type1">Summary</label>
                                            &emsp13;&emsp13;
                                            <input name="group1" type="radio" class="with-gap" id="report_type2" value="detailed">
                                            <label for="report_type2">Detailed</label>
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-1">
                                            <label for="party_id"><i class="fa fa-user"></i> Customer</label>
                                            {!! Form::select('party_id',$customers,null, [
                                                'id' => 'party_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '1',
                                                'required'=>'required'
                                            ]) !!}
                                            <span class="party-err text-danger"></span>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <br />
                                            <button class="btn btn-primary load-report-btn" type="button">Load Report</button>
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
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> Report</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-1">
                                            <table class="table table-bordered d-none" id="summary-table">
                                                <thead>
                                                    <tr>
                                                        <th>Sr.#</th>
                                                        <th>Date</th>
                                                        <th>Invoice No</th>
                                                        <th>Sale Type</th>
                                                        <th>Department</th>
                                                        <th>Customer</th>
                                                        <th>Qty</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <table class="table table-bordered d-none" id="detailed-table">
                                                <thead>
                                                    <tr>
                                                        <th>Sr.#</th>
                                                        <th>Date</th>
                                                        <th>Invoice No</th>
                                                        <th>Sale Type</th>
                                                        <th>Department</th>
                                                        <th>Customer</th>
                                                        <th>Qty</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
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
    <script src="{{ URL::asset('dashboard/date-range-picker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script>
    $(".date-picker").datepicker({rtl:App.isRTL(),orientation:"left",autoclose:!0,format:"dd/mm/yyyy",todayHighlight:true});
    </script>
    <script src="{{ URL::asset('dashboard/date-range-picker/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('dashboard/date-range-picker/js/knockout-3.4.2.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('dashboard/date-range-picker/js/daterangepicker.min.js') }}" type="text/javascript"></script>
    <script>
    $('input[name="date"]').daterangepicker({
    minDate: moment().subtract(2, 'years'),
    callback: function (startDate, endDate, period) {
        $(this).val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
    },
    startDate:'2019-10-01',
    endDate:'2022-11-04',
    maxDate:moment().add(1, 'years')
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
                $('#party_id').val(null).select2();
                $('#report_type1').attr('checked', true);
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
        $(function() {
            $('.load-report-btn').click(function(){
                $('#detailed-table').DataTable().clear().destroy();
                $('#summary-table').DataTable().clear().destroy();


                var from_date = $('#date').val().split(' - ')[0];
                var to_date = $('#date').val().split(' - ')[1];
                var party_id = parseInt($('#party_id').val());
                var report_type = $('.with-gap:checked').val();

                if(!party_id)
                {
                    $('.party-err').text('The party field is required.');
                }
                else{
                    $('.party-err').text('');

                    if(report_type=='summary')
                    {
                        $('#summary-table').removeClass('d-none');
                        $('#detailed-table').addClass('d-none');
                        $('#detailed-table').DataTable().clear().destroy();

                        $('#summary-table').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ URL::to('sales-voucher/report') }}?party_id="+party_id+'&from_date='+from_date+'&to_date='+to_date+'&report_type='+report_type,
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                            ],
                            columns: [{
                                    data: 'DT_RowIndex',
                                    name: 'DT_RowIndex',
                                    orderable: false,
                                    searchable: false
                                },
                                {
                                    data: 'date',
                                    name: 'date'
                                },
                                {
                                    data: 'invoice_no',
                                    name: 'invoice_no'
                                },
                                {
                                    data: 'sale_type',
                                    name: 'sale_type'
                                },
                                {
                                    data: 'warehouse_id',
                                    name: 'warehouse_id'
                                },
                                {
                                    data: 'party_id',
                                    name: 'party_id'
                                },
                                {
                                    data: 'total_qty',
                                    name: 'total_qty'
                                },
                                {
                                    data: 'total_sale_amount',
                                    name: 'total_sale_amount'
                                }
                            ]
                        });
                    }
                    else
                    if(report_type=='detailed')
                    {
                        $('#detailed-table').removeClass('d-none');
                        $('#summary-table').addClass('d-none');
                        $('#summary-table').DataTable().clear().destroy();

                        $('#detailed-table').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ URL::to('sales-voucher/report') }}?party_id="+party_id+'&from_date='+from_date+'&to_date='+to_date+'&report_type='+report_type,
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                            ],
                            columns: [{
                                    data: 'DT_RowIndex',
                                    name: 'DT_RowIndex',
                                    orderable: false,
                                    searchable: false
                                },
                                {
                                    data: 'date',
                                    name: 'date'
                                },
                                {
                                    data: 'invoice_no',
                                    name: 'invoice_no'
                                },
                                {
                                    data: 'sale_type',
                                    name: 'sale_type'
                                },
                                {
                                    data: 'warehouse_id',
                                    name: 'warehouse_id'
                                },
                                {
                                    data: 'party_id',
                                    name: 'party_id'
                                },
                                {
                                    data: 'quantity',
                                    name: 'quantity'
                                },
                                {
                                    data: 'sale_amount',
                                    name: 'sale_amount'
                                }
                            ]
                        });
                    }
                }

                // alert($('.dt-button').attr('tabindex'));
                $('.dt-button').removeClass('dt-button').addClass('btn btn-primary');

            });
        });
    </script>
    <!-- End Load Report -->
@stop
