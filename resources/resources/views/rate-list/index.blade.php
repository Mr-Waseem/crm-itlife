@extends('app')
@section('head')
    <title>Rate List</title>
    <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!--  Select 2 library start-->
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!--  Select 2 library end-->
    <style>
        .title{
            font-weight: bold;
            color:black;
        }
    </style>
@endsection
@section('content')
    <!-- Sticky Left Side bar -->
    <div class="icon-bar">
        <a href="javascript:void(0);" class="load-previous-record"><i class="fa fa-angle-left"></i></a>
        <a href="javascript:void(0);" class="load-edit-record"><i class="fa fa-repeat"></i></a>
        <a href="javascript:void(0);" class="load-next-record"><i class="fa fa-angle-right"></i></a>
        {{-- <a href="javascript:void(0);" class="delete_record_btn"><i class="fa fa-trash-o"></i></a> --}}
        <a href="javascript:void(0);" class="print_record_btn"><i class="fa fa-print"></i></a>
    </div>
    <!-- End Sticky Left Side bar -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Rate List
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Rate List</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <!-- <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Add New Rate</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div> -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    @if (Session::has('failure_message'))
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">×</button> {{ Session::get('failure_message') }}
                                        </div>
                                    @endif
                                    <div id="show_err"></div>
                                    {!! Form::open(['url' => 'rate-list', 'class' => 'form-horizontal', 'id' => 'rate-list-form']) !!}
                                    <input id="idd" name="idd" type="hidden">
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                            <label for="voucher_date"><i class="fa fa-caret-right"></i> Voucher Date <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::date('voucher_date', date('Y-m-d'), [
                                                'id' => 'voucher_date',
                                                'class' => 'form-control',
                                                'tabindex' => '0',
                                                'required' => 'required',
                                                'autofocus' => 'autofocus'
                                            ]) !!}
                                            @error('voucher_date')
                                                <font color="red">{{ $message }}</font>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                            <label for="tax_rate"><i class="fa fa-caret-right"></i> Tax Rate<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('tax_rate', null, [
                                                'id' => 'tax_rate',
                                                'class' => 'form-control',
                                                'onkeypress'=>"return isNumberKey(event)",
                                            ]) !!}
                                            <span class="text-danger tax_rate_err"></span>
                                            @error('tax_rate')
                                                <font color="red">{{ $message }}</font>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Document No # <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no1', $voucher_no, [
                                                'id' => 'voucher_no1',
                                                'class' => 'form-control',
                                                'tabindex' => '2',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            {!! Form::hidden('voucher_no', $voucher_no, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                            ]) !!}
                                            @error('voucher_no')
                                                <font color="red">{{ $message }}</font>
                                            @enderror
                                        </div>

                                      
                                    </div>
                                    <hr style="margin-bottom: 10px;">
                                    <div class="row font-weight-bold">
                                        <div class="col-lg-2 col-md-6 col-12"><h5 class="title">Code</h5></div>
                                        <div class="col-lg-2 col-md-6 col-12"><h5 class="title">Name</h5></div>
                                        <div class="col-lg-2 col-md-6 col-12"><h5 class="title">Packing</h5></div>
                                        <div class="col-lg-2 col-md-6 col-12"><h5 class="title">Previous Rate</h5></div>
                                        <div class="col-lg-2 col-md-6 col-12"><h5 class="title">New Rate <span class="text-danger">*</span></h5></div>
                                        <div class="col-lg-2 col-md-6 col-12"><h5 class="title">Remarks</h5></div>
                                    </div>
                                    <hr style="margin-top: 0px;">
                                    <div class="row">
                                        <div class="col-12" style="height: 500px;overflow-y:scroll;overflow-x:hidden;"
                                            id="GridTable">
                                            @foreach ($products as $value)
                                            <span id="repeat">
                                                <input type="hidden" name="pro_id[]" id="pro_id"
                                                    value="{{ $value->id }}">
                                                <div class="row @if (!$loop->first) mt-1 @endif">
                                                    <div class="col-lg-2 col-md-6 col-12">
                                                        {!! Form::text('code', $value->code, [
                                                            'id' => 'code',
                                                            'class' => 'form-control',
                                                            'disabled' => 'disabled',
                                                        ]) !!}
                                                    </div>
                                                    <div class="col-lg-2 col-md-6 col-12">
                                                        {!! Form::text('product_name', $value->product_name, [
                                                            'id' => 'product_name',
                                                            'class' => 'form-control',
                                                            'disabled' => 'disabled',
                                                        ]) !!}
                                                    </div>
                                                    <div class="col-lg-2 col-md-6 col-12">
                                                        {!! Form::text('packing[]', $value->packing, [
                                                            'id' => 'packing',
                                                            'class' => 'form-control',
                                                            'disabled' => 'disabled',
                                                        ]) !!}
                                                    </div>
                                                    <div class="col-lg-2 col-md-6 col-12">
                                                        {!! Form::text('previous_rate[]', number_format($value->product_price), [
                                                            'id' => 'previous_rate',
                                                            'class' => 'form-control',
                                                            'disabled' => 'disabled',
                                                        ]) !!}
                                                        {!! Form::hidden('previous_rate1[]', number_format($value->product_price), ['id' => 'previous_rate1']) !!}
                                                    </div>
                                                    <div class="col-lg-2 col-md-6 col-12">
                                                        {!! Form::text('new_rate[]', number_format($value->product_price), [
                                                            'id' => 'new_rate',
                                                            'class' => 'form-control',
                                                            'onchange' => "EnterKeyBoard($(this).closest('#repeat'));",
                                                            'onkeypress'=>"return isNumberKey(event)",
                                                        ]) !!}
                                                    </div>
                                                    <div class="col-lg-2 col-md-6 col-12">
                                                        {!! Form::text('remarks[]', $value->remarks, [
                                                            'id' => 'remarks',
                                                            'class' => 'form-control',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div><br/>
                                    <div class="row">
                                        <div class="col-lg-10 col-md-12 col-12">
                                            <!-- <div class="note note-warning">Posted By : {{ Auth::User()->name }}</div> -->
                                        </div>
                                        <div class="col-lg-2 col-md-12 col-12">
                                            <button class="btn btn-primary submit-form" type="button">Save</button>
                                            <button class="btn btn-secondary reset-btn" type="reset">Reset</button>
                                        </div>
                                        <!-- <div class="col-lg-3 col-md-12 col-12">
                                            <div class="note note-danger">UnPosted By :</div>
                                        </div>
                                        <div class="col-lg-3 col-md-12 col-12">
                                            <div class="note note-warning">Posted By : {{ Auth::User()->name }}</div>
                                        </div>
                                        <div class="col-lg-3 col-md-12 col-12">
                                            <div class="note note-info">Updated By : <span class="d-none"
                                                    id="updated_by_name"> {{ Auth::User()->name }}</span></div>
                                        </div> -->
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
                <form action="{{ URL::to('rate-list/delete-voucher') }}" method="post" id="delete_voucher_form">
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
    <!-- End Delete Record Modal -->
    <!-- Print Record Modal -->
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
    <!-- End Print Record Modal -->
@endsection
@section('scripts')
    <!-- Main Features -->
    <script>
    //     function EnterKeyBoard(row){ 
    //                 RowIndex = row.index();
    //                 // alert(row.index())
    //                 // alert(RowIndex)
                
    //                 // alert("enter")
    //             if(RowIndex = '0'){
    //             alert("1")
    //              $('tr:eq(' + 1 + ')', GridTable).find("#repeat.div('6')").find('input').focus();
    //             }
    //             if(RowIndex != '0')
    //             {
    //                 alert("2")
    //             var NextIndex = RowIndex + 1;
    //              $('tr:eq(' + NextIndex + ')', GridTable).find("td:eq('5')").find('input').focus();
    //             }
               
            
        
    // }
    function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode == 46) {
                // Check if decimal point already exists in the input
                if (evt.target.value.indexOf('.') !== -1)
                return false;
                else
                return true;
            }
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }
        $(document).ready(function() {
            // Delete Record
            $('.delete_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('delete-record-modal'), {});
                myModal.toggle();
                var voucher_no = parseInt($('#voucher_no').val());
                $('#delete_voucher_id').val(voucher_no);
            });
            $('#voucher_date').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#tax_rate").focus();
                }
            });

            $('#tax_rate').keydown(function(event) {
                // alert("dd")
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#new_rate").focus();
                }
            });
            // Print Record
            $('.print_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                myModal.toggle();

                var voucher_no = parseInt($('#voucher_no').val());
                var base_url = $('#base_url').val();
                $.ajax({
                    url: "{{ URL::to('rate-list/print/voucher') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    beforeSend: function(response) {
                        $('#print-receipt-modal-body').html(
                            '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                        );
                    },
                    success: function(response) {
                        // console.log(response)
                        if (response != null && response != 0) {
                            $('#print-receipt-modal-body').html(
                                `<object data="${base_url}/resources/upload/rate-list/${response}" type="application/pdf" width="100%" height="800"></object>`
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



        $('.submit-form').click(function() {
                // alert("dd")
                var voucherDate = $('#voucher_date').val();
                var voucher_no = $('#voucher_no').val();
                var TaxRate = $('#tax_rate').val();

                $('.voucher_date_err').text('');
                $('.voucher_no_err').text('');
                $('.tax_rate_err').text('');

                if(!voucher_no)
                {
                    $('.voucher_no_err').text('The voucher No field is required.');
                    return false;
                }else
                if(!voucherDate)
                {
                    $('.voucher_date_err').text('The Voucher Date field is required.');
                    return false;
                }
                else
                if(!TaxRate)
                {
                    $('.tax_rate_err').text('The Tax Rate field is required.');
                    return false;
                }
                else{
                    $('#rate-list-form').submit();
                    $('.submit-form').attr('disabled', true);
                }
            });
    </script>
    <!-- End Main Features -->

    


    <!-- Load & Edit Record -->
    <script>
        $(document).ready(function() {
            $('.load-edit-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('rate-list/load/record') }}",
                    type: 'get',
                    data: {
                        voucher_no: voucher_no
                    },
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            $('#show_err').html(null);
                            var formHtml = '';
                            $.each(response.data.rate_list_details, function(i, v) {
                                if (i != 0) {
                                    formHtml += `
                                    <input type="hidden" name="pro_id[]" id="pro_id" value="${v.product.id}">
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="code" id="code" class="form-control" value="${v.product.code}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="product_name" id="product_name" class="form-control" value="${v.product.product_name}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="packing[]" id="packing" class="form-control" value="${v.product.packing?v.product.packing:''}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="previous_rate[]" id="previous_rate" class="form-control" value="${v.previous_rate}" disabled />
                                            <input type="hidden" name="previous_rate1[]" id="previous_rate1" value="${v.previous_rate}" />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="new_rate[]" id="new_rate" class="form-control" value="${v.new_rate}" />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="remarks[]" id="remarks" class="form-control" value="${v.product.remarks?v.product.remarks:''}" />
                                        </div>
                                    </div>
                                    `;
                                } else {
                                    formHtml += `
                                    <input type="hidden" name="pro_id[]" id="pro_id" value="${v.product.id}">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="code" id="code" class="form-control" value="${v.product.code}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="product_name" id="product_name" class="form-control" value="${v.product.product_name}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="packing[]" id="packing" class="form-control" value="${v.product.packing?v.product.packing:''}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="previous_rate[]" id="previous_rate" class="form-control" value="${v.previous_rate}" disabled />
                                            <input type="hidden" name="previous_rate1[]" id="previous_rate1" value="${v.previous_rate}" />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="new_rate[]" id="new_rate" class="form-control" value="${v.new_rate}" />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="remarks[]" id="remarks" class="form-control" value="${v.product.remarks?v.product.remarks:''}" />
                                        </div>
                                    </div>
                                    `;
                                }
                            });

                            $('#GridTable').html(formHtml);
                            $('#updated_by_name').removeClass('d-none');
                            $('#voucher_date').val(response.data.voucher_date);
                            $('#voucher_no').val(response.data.voucher_no);
                            $('#voucher_no1').val(response.data.voucher_no);
                            $('#voucher_no').focus();
                            $('#idd').val(response.data.id);
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('#GridTable').html(
                                '<h1 class="text-center text-black font-weight-bold">Voucher Not Exist!</h1>'
                                );
                            $('#updated_by_name').addClass('d-none');
                            $('#voucher_no').focus();
                            setTimeout(() => {
                                $('#show_err').html(null);
                            }, 3000);
                        }
                    }
                });
            });


            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('rate-list/load/next/record') }}",
                    type: 'get',
                    data: {
                        voucher_no: voucher_no
                    },
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            $('#show_err').html(null);
                            var formHtml = '';
                            $.each(response.data.rate_list_details, function(i, v) {
                                if (i != 0) {
                                    formHtml += `
                                    <input type="hidden" name="pro_id[]" id="pro_id" value="${v.product.id}">
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="code" id="code" class="form-control" value="${v.product.code}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="product_name" id="product_name" class="form-control" value="${v.product.product_name}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="packing[]" id="packing" class="form-control" value="${v.product.packing?v.product.packing:''}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="previous_rate[]" id="previous_rate" class="form-control" value="${v.previous_rate}" disabled />
                                            <input type="hidden" name="previous_rate1[]" id="previous_rate1" value="${v.previous_rate}" />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="new_rate[]" id="new_rate" class="form-control" value="${v.new_rate}" />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="remarks[]" id="remarks" class="form-control" value="${v.product.remarks?v.product.remarks:''}" />
                                        </div>
                                    </div>
                                    `;
                                } else {
                                    formHtml += `
                                    <input type="hidden" name="pro_id[]" id="pro_id" value="${v.product.id}">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="code" id="code" class="form-control" value="${v.product.code}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="product_name" id="product_name" class="form-control" value="${v.product.product_name}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="packing[]" id="packing" class="form-control" value="${v.product.packing?v.product.packing:''}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="previous_rate[]" id="previous_rate" class="form-control" value="${v.previous_rate}" disabled />
                                            <input type="hidden" name="previous_rate1[]" id="previous_rate1" value="${v.previous_rate}" />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="new_rate[]" id="new_rate" class="form-control" value="${v.new_rate}" />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="remarks[]" id="remarks" class="form-control" value="${v.product.remarks?v.product.remarks:''}" />
                                        </div>
                                    </div>
                                    `;
                                }
                            });

                            $('#GridTable').html(formHtml);
                            $('#updated_by_name').removeClass('d-none');
                            $('#voucher_date').val(response.data.voucher_date);
                            $('#voucher_no').val(response.data.voucher_no);
                            $('#voucher_no1').val(response.data.voucher_no);
                            $('#voucher_no').focus();
                            $('#idd').val(response.data.id);
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('#GridTable').html(
                                '<h1 class="text-center text-black font-weight-bold">Voucher Not Exist!</h1>'
                                );
                            $('#updated_by_name').addClass('d-none');
                            $('#voucher_no').focus();
                            setTimeout(() => {
                                $('#show_err').html(null);
                            }, 3000);
                        }
                    }
                });
            });
            // End Here of Load Next Record


            // Load Previous Record
            $('.load-previous-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('rate-list/load/previous/record') }}",
                    type: 'get',
                    data: {
                        voucher_no: voucher_no
                    },
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            $('#show_err').html(null);
                            var formHtml = '';
                            $.each(response.data.rate_list_details, function(i, v) {
                                if (i != 0) {
                                    formHtml += `
                                    <input type="hidden" name="pro_id[]" id="pro_id" value="${v.product.id}">
                                    <div class="row mt-1">
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="code" id="code" class="form-control" value="${v.product.code}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="product_name" id="product_name" class="form-control" value="${v.product.product_name}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="packing[]" id="packing" class="form-control" value="${v.product.packing?v.product.packing:''}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="previous_rate[]" id="previous_rate" class="form-control" value="${v.previous_rate}" disabled />
                                            <input type="hidden" name="previous_rate1[]" id="previous_rate1" value="${v.previous_rate}" />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="new_rate[]" id="new_rate" class="form-control" value="${v.new_rate}" />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="remarks[]" id="remarks" class="form-control" value="${v.product.remarks?v.product.remarks:''}" />
                                        </div>
                                    </div>
                                    `;
                                } else {
                                    formHtml += `
                                    <input type="hidden" name="pro_id[]" id="pro_id" value="${v.product.id}">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="code" id="code" class="form-control" value="${v.product.code}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="product_name" id="product_name" class="form-control" value="${v.product.product_name}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="packing[]" id="packing" class="form-control" value="${v.product.packing?v.product.packing:''}" disabled />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="previous_rate[]" id="previous_rate" class="form-control" value="${v.previous_rate}" disabled />
                                            <input type="hidden" name="previous_rate1[]" id="previous_rate1" value="${v.previous_rate}" />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="new_rate[]" id="new_rate" class="form-control" value="${v.new_rate}" />
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-12">
                                            <input type="text" name="remarks[]" id="remarks" class="form-control" value="${v.product.remarks?v.product.remarks:''}" />
                                        </div>
                                    </div>
                                    `;
                                }
                            });
                            $('#GridTable').html(formHtml);
                            $('#updated_by_name').removeClass('d-none');
                            $('#voucher_date').val(response.data.voucher_date);
                            $('#voucher_no').val(response.data.voucher_no);
                            $('#voucher_no1').val(response.data.voucher_no);
                            $('#tax_rate').val(response.data.tax_rate);
                            $('#voucher_no').focus();
                            $('#idd').val(response.data.id);
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('#GridTable').html(
                                '<h1 class="text-center text-black font-weight-bold">Voucher Not Exist!</h1>'
                                );
                            $('#updated_by_name').addClass('d-none');
                            $('#voucher_no').focus();
                            $('#voucher_no').val(1);
                            $('#voucher_no1').val(1);
                            setTimeout(() => {
                                $('#show_err').html(null);
                            }, 3000);
                        }
                    }
                });
            });
            // End Here of Load Previous Record
        });
    </script>
    <!-- End Load & Edit Record -->

    @include('include.toast-messages')
@endsection
