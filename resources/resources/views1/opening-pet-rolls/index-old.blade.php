@extends('app')
@section('head')
    <title>Thermoforming Production</title>
<!--  Select 2 library start-->
<link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!--  Select 2 library end-->
@stop
@section('content')
<div class="icon-bar">
        <a href="javascript:void(0);" class="load-previous-record"><i class="fa fa-angle-left"></i></a>
        <a href="javascript:void(0);" class="load-edit-record"><i class="fa fa-repeat"></i></a>
        <a href="javascript:void(0);" class="load-next-record"><i class="fa fa-angle-right"></i></a>
        <a href="javascript:void(0);" class="delete_record_btn"><i class="fa fa-trash-o"></i></a>
        <a href="javascript:void(0);" class="print_record_btn"><i class="fa fa-print"></i></a>
    </div>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Thermoforming Production</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Thermoforming Production</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <!-- <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> ADD Thermoforming Production</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div> -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'opening-pet-rolls', 'class' => 'form-horizontal', 'id' => 'users-form']) !!}
                                    {!! Form::hidden('idd', null, ['id' => 'idd']) !!}
                                    <!-- {!! Form::hidden('pr_id', Auth::User()->id, ['id' => 'biller_id']) !!} -->
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    <div class="row">
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Vr.Date <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::date('date', date('Y-m-d'), [
                                                'id' => 'date',
                                                'class' => 'form-control',
                                                'tabindex' => '0',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Vr.No <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::text('voucher_no', $codes, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'tabindex' => '1',
                                                'required' => 'required',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                'autofocus' => 'autofocus',
                                            ]) !!}
                                            <span class="text-danger" id="voucher_no_err"></span>
                                            @error('voucher_no')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Remarks</label>
                                        </div>
                                        <div class="col-lg-4 col-md-2 col-sm-12">
                                        {!! Form::text('remarks', null, [
                                                'id' => 'remarks',
                                                'class' => 'form-control'
                                            ]) !!}
                                            <span class="text-danger" id="voucher_no_err"></span>
                                            @error('voucher_no')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="row mt-2">
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Choose Product <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                        {!! Form::select('product_id', $product, null, [
                                                'id' => 'product_id',
                                                'class' => 'form-control select2',
                                            ]) !!}
                                            <span class="text-danger" id="consumed_product_id_err"></span>
                                            @error('product_id')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Batch No<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-5 col-sm-12">
                                            {!! Form::text('batchNo', null, [
                                                'id' => 'batchNo',
                                                'class' => 'form-control'
                                            ]) !!}
                                            <span class="text-danger" id="batchNo_err"></span>
                                            @error('batchNo')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Thickness <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-5 col-sm-12">
                                            {!! Form::text('thickness', null, [
                                                'id' => 'thickness',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                'class' => 'form-control'
                                            ]) !!}
                                            <span class="text-danger" id="thickness_err"></span>
                                            @error('thickness')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                    </div>

                                 


                                    <div class="row">
                                        
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Width <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-5 col-sm-12">
                                            {!! Form::text('width', null, [
                                                'id' => 'width',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                'class' => 'form-control'
                                            ]) !!}
                                            <span class="text-danger" id="thickness_err"></span>
                                            @error('thickness')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Color <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-5 col-sm-12">
                                            {!! Form::text('color', null, [
                                                'id' => 'color',
                                                'class' => 'form-control'
                                            ]) !!}
                                            <span class="text-danger" id="color_err"></span>
                                            @error('color')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                       

                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Gross&nbsp;Weight<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::text('gross_weight', null, [
                                                'id' => 'gross_weight',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                'class' => 'form-control'
                                            ]) !!}
                                            <span class="text-danger" id="consumed_err"></span>
                                            @error('gross_weight')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Net&nbsp;Weight<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                        {!! Form::text('net_weight', null, [
                                                'id' => 'net_weight',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                'class' => 'form-control'
                                            ]) !!}
                                            <span class="text-danger" id="consumed_err"></span>
                                            @error('net_weight')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="text-xs-right bt-1 pt-10 mt-5">
                                        <button type="button" class="btn btn-info form_submit">Submit</button>
                                        <button type="reset" class="btn btn-primary reset_btn">Reset</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>


        <!-- Delete Record Modal -->
    <!-- modal -->
    <div class="modal fade" id="delete-record-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash text-danger"></i> Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ URL::to('opening-pet-rolls/delete-voucher') }}" method="post" id="delete_voucher_form">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Voucher?</p>
                        <input type="hidden" name="delete_voucher_id" id="delete_voucher_id" value="">
                    </div>
                    <div class="modal-footer text-right">
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="document.getElementById('delete_voucher_form').submit();">Delete <i
                                class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-secondary btn-sm text-black"
                            data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.modal -->
    <!-- End Delete Record Modal -->
    <!-- Print Record Modal -->
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
    <!-- DataTables Scripts -->
    <script src="{{ URL::asset('dashboard/datatables/jquery.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.validate.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- End DataTables Scripts -->

    <!-- Searchable Select2 -->
    <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2, .select2-multiple").select2({
            width: "100%"
        });
    </script>
    <!-- End Searchable Select2 -->



    <!-- Update Warehouse || Department -->
    <script>
        $(document).ready(function() {

          
            $('#consumed_product_id').change(function() {
            var product_id = parseInt($('#consumed_product_id').val());
            // alert(product_id)
            // alert("ddd")


                    $.ajax({
                        url: "{{ URL::to('thermoforming-production/load-role-data') }}",
                        type: 'get',
                        data: {
                        product_id: product_id,
                        },

                        success: function(response) {
                            if (response != null && response != 0) {
                               $('#color').val(response.color);
                               $('#thickness').val(response.thickness);
                               $('#net_weight').val(response.total_qty);
                               $('#width').val(response.width);
                            } else {
                                
                            }
                        }
                    });
                });

            $(document).on('click', '.edit_btn', function() {
                var data = $(this).attr('name');
                var id = data.split('_')[0];
                var name = data.split('_')[1];
                var address = data.split('_')[2];
                var phone = data.split('_')[3];
                var warehouse_id = data.split('_')[4];
                var email = data.split('_')[5];
                var password = data.split('_')[6];
                var status = data.split('_')[7];
                var role = data.split('_')[8];
                $('#idd').val(id);
                $('#name').val(name);
                $('#address').val(address);
                $('#phone').val(phone);
                $('#warehouse_id').val(warehouse_id).select2();
                $('#email').val(email);
                // $('#email1').val(email);
                $('#status').val(status).select2();
                $('#role').val(role).select2();
                $('#password').val(password);
                // $('#password1').val(password);
                // $('#email').attr('disabled', true);
                // $('#password').attr('disabled', true);
            });
            $('.reset_btn').click(function() {
                $('#idd').val(null);
                $('#email1').val(null);
                $('#password1').val(null);
                $('#warehouse_id').val(null).select2();
                $('#status').val(null).select2();
                $('#role').val(null).select2();
                $('#email').attr('disabled', false);
                $('#password').attr('disabled', false);
            });
            $('.form_submit').click(function() {
                $('#users-form').submit();
             
                // var idd = $('#idd').val();
                // var Voucher = $('#voucher_no').val();
                // var Product = $('#product_id').val();
                // var DyePcs = $('#dye_units').val();
                // var shiftID = $('#shift_id').val();
                // var operatorID = $('#operator_id').val();
                // var pressmanNO = $('#pressman_no').val();
                // var totalSheets = $('#total_sheets').val();
                // var checkSheets = $('#check_sheets').val();
                // var netSheets = $('#net_sheets').val();
                // if (!Voucher) {
                //     $('#voucher_no_err').text('The Voucher field is required');
                // } else
                // if (!Product) {
                //     $('#product_id_err').text('The Product Name field is required');
                // } else
                // if (!pressmanNO) {
                //     $('#pressman_no_err').text('The Press No field is required');
                // } else
                // if (!totalSheets) {
                //     $('#total_sheets_err').text('The Total Sheets field is required');
                // } else
                // if (!checkSheets) {
                //     $('#check_sheets_err').text('The Check Sheets field is required');
                // } else {
                //     $('#users-form').submit();
                // }
            });
        });
    </script>
    <!-- End Update Warehouse || Department -->

    <!-- Focus to next field -->
    <script>
        $(document).ready(function() {
            $('#voucher_no').keydown(function(event) {
                // alert("dd")
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#remarks").focus();
                }
            });

            $('#remarks').keydown(function(event) {
                //  alert("dd")
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#product_id").select2('open');
                }
            });

            $('#product_id').change(function(event) {
                var product = $(this).val();
                if (product != null) {
                    $('#product_id').select2().trigger('select2:close');
                    $('#batchNo').focus();
                }
            });

            $('#batchNo').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#thickness").focus();
                }
            });

            $('#thickness').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#width").focus();
                }
            });

            $('#width').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#color").focus();
                }
            });

            $('#color').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#gross_weight").focus();
                }
            });

            $('#gross_weight').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#net_weight").focus();
                }
            });

        });
    </script>
    <!-- End Focus to next field -->

    <script>
        // Swal Confirmation | Remove User
        $(document).ready(function() {
            $(document).on('click', '.remove-user', function() {
                let id = $(this).attr('name');
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Are you sure you want to delete this user?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    confirmButtonColor: '#28A745',
                    cancelButtonText: 'No, cancel!',
                    cancelButtonColor: '#DC3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = `{{ URL::to('users/destroy/${id}') }}`;
                    }
                });
            });


            $('.load-previous-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('opening-pet-rolls/load/previous/record') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.petstock != '') {      
                            // $('#product_id').val(response.production.product_id + '_' + response
                            //     .production.product.dye_pcs).select2();
                            $('#idd').val(response.petstock.id);
                            $('#date').val(response.petstock.date);
                            $('#voucher_no').val(response.petstock.voucher_no);
                            $('#remarks').val(response.petstock.remarks);
                            $('#product_id').val(response.petstock.product_id).select2();
                            $('#batchNo').val(response.petstock.batchNo);
                            $('#thickness').val(response.petstock.thickness);
                            $('#width').val(response.petstock.width);
                            $('#color').val(response.petstock.color);
                            $('#gross_weight').val(response.petstock.gross_weight);
                            $('#net_weight').val(response.petstock.net_weight);
                            $('#voucher_no').focus();
                            // $('#updated_by_name').removeClass('d-none');
                        }
                    }
                });
            });

            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('opening-pet-rolls/load/next/record') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                    
                        if (response.petstock != '') {
                            // $('#product_id').val(response.production.product_id + '_' + response
                            //     .production.product.dye_pcs).select2();
                            $('#idd').val(response.petstock.id);
                            $('#date').val(response.petstock.date);
                            $('#voucher_no').val(response.petstock.voucher_no);
                            $('#remarks').val(response.petstock.remarks);
                            $('#product_id').val(response.petstock.product_id).select2();
                            $('#batchNo').val(response.petstock.batchNo);
                            $('#thickness').val(response.petstock.thickness);
                            $('#width').val(response.petstock.width);
                            $('#color').val(response.petstock.color);
                            $('#gross_weight').val(response.petstock.gross_weight);
                            $('#net_weight').val(response.petstock.net_weight);
                            $('#voucher_no').focus();
                            // $('#updated_by_name').removeClass('d-none');
                        }
                    }
                });
            });



            $('.load-edit-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('opening-pet-rolls/load/record') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.petstock != '') {
                             // $('#product_id').val(response.production.product_id + '_' + response
                            //     .production.product.dye_pcs).select2();
                            $('#idd').val(response.petstock.id);
                            $('#date').val(response.petstock.date);
                            $('#voucher_no').val(response.petstock.voucher_no);
                            $('#remarks').val(response.petstock.remarks);
                            $('#product_id').val(response.petstock.product_id).select2();
                            $('#batchNo').val(response.petstock.batchNo);
                            $('#thickness').val(response.petstock.thickness);
                            $('#width').val(response.petstock.width);
                            $('#color').val(response.petstock.color);
                            $('#gross_weight').val(response.petstock.gross_weight);
                            $('#net_weight').val(response.petstock.net_weight);
                            $('#voucher_no').focus();
                            // $('#updated_by_name').removeClass('d-none');
                        }
                    }
                });
            });

            $('.delete_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('delete-record-modal'), {});
                myModal.toggle();
                var voucher_no = parseInt($('#voucher_no').val());
                $('#delete_voucher_id').val(voucher_no);
            });
             // Print Record
             $('.print_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                myModal.toggle();

                var voucher_no = parseInt($('#voucher_no').val());
                //   alert(voucher_no);
                var base_url = $('#base_url').val();
                // alert(base_url)
                $.ajax({
                    url: "{{ URL::to('opening-pet-rolls/print/voucher') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    beforeSend: function(response) {
                        $('#print-receipt-modal-body').html(
                            '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                        );
                    },
                    success: function(response) {
                       if (response != null && response!=0) {
                            $('#print-receipt-modal-body').html(
                                `<object data="${base_url}/resources/upload/opening-pet-rolls/${response}" type="application/pdf" width="100%" height="800"></object>`
                            );
                        } else {
                            $('#print-receipt-modal-body').html('<h2 style="color:red;text-align:center;">Voucher Not Exist</h2>');
                        }
                    }
                });
            });

        });
    </script>
    @include('include.toast-messages')
@stop
