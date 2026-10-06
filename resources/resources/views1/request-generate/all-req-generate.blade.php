@extends('app')
@section('head')
    <title>All Request Generate</title>
    <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!--  Select 2 library start-->
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!--  Select 2 library end-->
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                All Request Generate
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">All Request Generate</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
           
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> LIST OF ALL REQUEST GENERATE</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover data-table">
                                            <thead>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>date</th>
                                                    <th>Voucher</th>
                                                    <th>Godown</th>
                                                    <th>Supplier</th>
                                                    <th>Purchaser</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>date</th>
                                                    <th>Voucher</th>
                                                    <th>Godown</th>
                                                    <th>Supplier</th>
                                                    <th>Purchaser</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
     <!-- modal -->
     <div class="modal hide fade" id="print-record-modal" tabindex="-1" role="dialog"
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
 <!-- /.modal -->
 <!-- End Print Record Modal -->
@stop
@section('scripts')
    <script src="{{ URL::asset('dashboard/datatables/jquery.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.validate.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ URL::to('request-generate/all-request-generate') }}",
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
                        data: 'bill_no',
                        name: 'bill_no'
                    },
                    {
                        data: 'warehouse_id',
                        name: 'warehouse_id'
                    },
                    {
                        data: 'supplier_id',
                        name: 'supplier_id'
                    },
                    {
                        data: 'purchaser_id',
                        name: 'purchaser_id'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $(document).on('click', '.print_btn', function() {
                var data = $(this).attr('req_id');
                var voucher_no=data.split('_')[1];
                var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                myModal.toggle();
                var base_url = $('#base_url').val();
                $.ajax({
                    url: "{{ URL::to('request-generate/print/voucher') }}?voucher_no=" +
                    voucher_no,
                    type: 'get',
                    beforeSend: function(response) {
                        $('#print-receipt-modal-body').html(
                            '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                        );
                    },
                    success: function(response) {
                       // console.log(response)
                        if (response != null) {
                            $('#print-receipt-modal-body').html(
                                `<object data="${base_url}/resources/upload/request-generate/${response}" type="application/pdf" width="100%" height="800"></object>`
                            );
                        } else {
                            alert('null');
                        }
                    }
                });
            });
        });
           
    </script>


    <!-- Searchable Select2 -->
    <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2, .select2-multiple").select2({
            width: "100%"
        });
    </script>
    <!-- End Searchable Select2 -->

    @include('include.toast-messages')
@stop
