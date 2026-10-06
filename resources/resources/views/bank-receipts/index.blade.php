@extends('app')
@section('head')
    <title>Bank Receipts Vouchers</title>
    <!--  Select 2 library start-->
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!--  Select 2 library end-->
@stop
@section('content')
    <!-- Sticky Left Side bar -->
    @if(isset($data))
<body onload="loadeditJV()">
@endif
    <div class="icon-bar">
        <a href="javascript:void(0);" class="load-previous-record"><i class="fa fa-angle-left"></i></a>
        <a href="javascript:void(0);" class="load-edit-record"><i class="fa fa-repeat"></i></a>
        <a href="javascript:void(0);" class="load-next-record"><i class="fa fa-angle-right"></i></a>
        <a href="javascript:void(0);" class="delete_record_btn"><i class="fa fa-trash-o"></i></a>
        <a href="javascript:void(0);" class="print_record_btn"><i class="fa fa-print"></i></a>
    </div>
    <!-- End Sticky Left Side bar -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Bank Receipts Vouchers
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Bank Receipts Vouchers</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Bank Receipts Vouchers</h6>
                            <!-- <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul> -->
                        </div>
                        <!-- /.box-header -->
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
                                    {!! Form::open(['url' => 'bank-receipts', 'class' => 'form-horizontal', 'id' => 'bank-receipt-form']) !!}
                                    {!! Form::hidden('update_voucher_no', null, ['id' => 'update_voucher_no']) !!}
                                    {!! Form::hidden('v_type', 'Bank Receipt', ['id' => 'v_type', 'class' => 'form-control']) !!}
                                    {!! Form::hidden('biller', Auth::User()->id, ['id' => 'created_by']) !!}
                                    {!! Form::hidden('current_date', date('Y-m-d'), ['id' => 'current_date']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    

                                    <div class="row">
                                        <div class="col-lg-2 col-md-12 col-sm-12 mt-1">
                                            <label for="voucher_date"><i class="fa fa-caret-right"></i> Voucher Date. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::date('voucher_date', date('Y-m-d'), [
                                                'id' => 'voucher_date',
                                                'class' => 'form-control',
                                                'tabindex' => '0',
                                                'autofocus' => 'autofocus',
                                            ]) !!}
                                        </div>
                                        @error('voucher_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <input type="hidden" id="userrole" name="userrole" value="{{Auth::User()->role}}">
                                        @if(isset($data))
                                        <input type="hidden" id="userrole" name="userrole" value="{{Auth::User()->role}}">
                                        <div class="col-lg-3 col-md-12 col-sm-12 mt-1">
                                            <label for="account_id"><i class="fa fa-caret-right"></i>Select Warehouse.
                                                <span class="text-danger">*</span></label>
                                            {!! Form::select('warehouse_id', $warehouse, $data->warehouse_id, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                            ]) !!}
                                        </div>
                                        @if(Auth::User()->role == "Admin")
                                        <div class="col-lg-2 col-md-12 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $data->voucher_no, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                                'tabindex' => '1',
                                            ]) !!}
                                        </div>
                                        @else
                                        <div class="col-lg-2 col-md-12 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $data->voucher_no, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                                'tabindex' => '1',
                                            ]) !!}
                                        </div>
                                        @endif
                                        @else
                                        <input type="hidden" id="userrole" name="userrole" value="{{Auth::User()->role}}">
                                        <div class="col-lg-3 col-md-12 col-sm-12 mt-1">
                                            <label for="account_id"><i class="fa fa-caret-right"></i>Select Warehouse.
                                                <span class="text-danger">*</span></label>
                                            {!! Form::select('warehouse_id', $warehouse, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                            ]) !!}
                                        </div>
                                        @if(Auth::User()->role == "Admin")
                                        <div class="col-lg-2 col-md-12 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', null, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                                'tabindex' => '1',
                                            ]) !!}
                                        </div>
                                        @else
                                        <div class="col-lg-2 col-md-12 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $codes, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress' => 'return isNumberKeyNoPoint(event)',
                                                'tabindex' => '1',
                                            ]) !!}
                                        </div>
                                        @endif
                                        @endif
                                        <div class="col-lg-4 col-md-12 col-sm-12 mt-1">
                                            <label for="account_id"><i class="fa fa-caret-right"></i> Bank Account.Name
                                                <span class="text-danger">*</span></label>
                                            {!! Form::select('account_id', $banks, null, [
                                                'id' => 'account_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                            ]) !!}
                                            <span class="bank_err text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="table-responsive mb-2">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="bg-primary text-center">
                                                            <th style="width: 30%;">Party/Account</th>
                                                            <th style="width: 40%;">Description</th>
                                                            <th style="display: none;">Cheque No</th>
                                                            <th style="display: none;">Cheque Date</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="bg-secondary">
                                                            <td>
                                                                {!! Form::select('account_head_id1', $Accounts, null, [
                                                                    'id' => 'account_head_id1',
                                                                    'class' => 'form-control select2',
                                                                    'tabindex' => '3',
                                                                ]) !!}
                                                                <span class="account_head_id1_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('narration1', null, [
                                                                    'id' => 'narration1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Description',
                                                                    'tabindex' => '4',
                                                                ]) !!}
                                                                <span class="narration1_err text-danger"></span>
                                                            </td>
                                                            <td style="display: none;">
                                                                {!! Form::text('cheque_no1', null, [
                                                                    'id' => 'cheque_no1',
                                                                    'class' => 'form-control',
                                                                    'tabindex' => '5',
                                                                ]) !!}
                                                                <span class="cheque_no1_err text-danger"></span>
                                                            </td>
                                                            <td style="display: none;">
                                                                {!! Form::date('cheque_date1', date('Y-m-d'), [
                                                                    'id' => 'cheque_date1',
                                                                    'class' => 'form-control',
                                                                    'tabindex' => '6',
                                                                ]) !!}
                                                                <span class="cheque_date1_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('amount1', null, [
                                                                    'id' => 'amount1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Amount',
                                                                    'onkeyup' => 'TotalGrandAmount()',
                                                                    'tabindex' => '7',
                                                                    'onkeypress' => 'return isNumberKey(event)',
                                                                ]) !!}
                                                                <span class="amount_err text-danger"></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="table-responsive mb-2">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 30%;">Party/Account</th>
                                                                <th style="width: 40%;">Description</th>
                                                                <th style="display: none;">Cheque No</th>
                                                                <th style="display: none;">Cheque Date</th>
                                                                <th>Amount</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2"><strong>Total</strong></td>
                                                                <td class="bg-primary" id="TotalAmount">0</td>
                                                                <td></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        
                                        <div class="col-lg-10 col-md-12 col-12">
                                            <!-- <div class="note note-danger">UnPosted By :</div> -->
                                        </div>
                                        <!-- <div class="col-lg-3 col-md-12 col-12">
                                            <div class="note note-warning">Posted By : {{ Auth::User()->name }}</div>
                                        </div>
                                        <div class="col-lg-3 col-md-12 col-12">
                                            <div class="note note-info">Updated By : <span class="d-none"
                                                    id="updated_by_name"> {{ Auth::User()->name }}</span></div>
                                        </div> -->
                                        <div class="col-lg-2 col-md-12 col-12">
                                            <button class="btn btn-primary submit-form" type="button">Save</button>
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
                <form action="{{ URL::to('bank-receipts/delete-voucher') }}" method="post" id="delete_voucher_form">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Voucher?</p>
                        <input type="hidden" name="delete_voucher_no" id="delete_voucher_no" value="">
                        <input type="hidden" name="delete_warehouseID" id="delete_warehouseID" value="">
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
    <!-- Searchable Select2 -->
    <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2, .select2-multiple").select2({
            width: "100%"
        });
    </script>
    <!-- End Searchable Select2 -->


    <!-- Focus on next field -->
    <script>
        $(document).ready(function() {
            $('#voucher_date').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
                var userRole = $('#userrole').val();
                // alert(userRole);
                if(userRole == "Admin"){
                    $("#warehouse_id").select2('open');
                }else{
                    // $('#warehouse_id').select2().trigger('select2:close');
                    $("#voucher_no").focus();
                }
            }
            });
            $('#warehouse_id').change(function() {
                var account_id = $(this).val();
                // alert(account_id);
                // if (account_id) {
                    $('#warehouse_id').select2().trigger('select2:close');
                    $("#voucher_no").focus();
                // }
            });
            $('#voucher_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#account_id").select2('open');
                }
            });
            $('#account_id').change(function() {
                var account_id = $(this).val();
                if (account_id) {
                    $('#account_id').select2('close');
                    $("#account_head_id1").select2("open");
                }
            });
            $('#account_head_id1').change(function() {
                var account_head_id = $(this).val();
                if (account_head_id) {
                    $('#account_head_id1').select2().trigger('select2:close');
                    $("#narration1").focus();
                }
            });
            $('#narration1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#amount1").focus();
                    // $("#cheque_no1").focus();
                }
            });
            $('#cheque_no1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#cheque_date1").focus();
                }
            });
            $('#cheque_date1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#amount1").focus();
                }
            });
            $('#amount1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $('.account_head_id1_err').text('');
                    $('.narration1_err').text('');
                    $('.cheque_no1_err').text('');
                    $('.amount1_err').text('');


                    var amount = $(this).val();
                    var cheque_no = $('#cheque_no1').val();
                    var narration = $('#narration1').val();
                    var account_head_id = $('#account_head_id1').val();
                    if (!account_head_id) {
                        $("#account_head_id1").select2("open");
                    } 
                    // else
                    // if (!narration) {
                    //     $('.narration1_err').text('This field is required');
                    //     $('#narration1').focus();
                    // } else
                    // if (!cheque_no) {
                    //     $('.cheque_no1_err').text('This field is required');
                    //     $('#cheque_no1').focus();
                    // } 
                    // else
                    if (!amount || amount <= 0) {
                        $('.amount_err').text('This field is required & Must be greater than zero');
                        $('#amount1').focus();
                    } else {
                        $('.account_head_id1_err').text('');
                        $('.narration1_err').text('');
                        $('.cheque_no1_err').text('');
                        $('.amount1_err').text('');
                        AddGridData();
                    }
                }
            });
        });
    </script>
    <!-- End Focus on next field -->

    <!-- Append New Data on Table -->
    <script>
         function loadeditJV(){
            // var consignment_no = parseInt($('#consignment_no').val());
            // alert(consignment_no);
            $('.load-edit-record').click();
        }
        function AddGridData() {
            // alert("dfsd");
            var account_head_id = $('#account_head_id1').val();
            if (!account_head_id) {
                $('.account_head_id1_err').text('This field is required');
                $('#account_head_id1').select2('open');
                return false;
            } else {
                $('.account_head_id1_err').text('');
            }

            // var narration = $('#narration1').val();
            // if (!narration) {
            //     $('.narration1_err').text('This field is required');
            //     $('#narration1').focus();
            //     return false;
            // } else {
            //     $('.narration1_err').text('');
            // }

            // var cheque_no = $('#cheque_no1').val();
            // if (!cheque_no) {
            //     $('.cheque_no1_err').text('This field is required');
            //     $('#cheque_no1').focus();
            //     return false;
            // } else {
            //     $('.cheque_no1_err').text('');
            // }

            var amount = parseInt($('#amount1').val());
            if (!amount || amount <= 0) {
                $('.amount_err').text('This field is required & Must be greater than zero');
                $('#amount1').focus();
                return false;
            } else {
                $('.amount_err').text('');
            }

            var partyId = document.getElementById('account_head_id1').value.split('_')[0];
            var partyName = document.getElementById('account_head_id1').value.split('_')[1];
            var partyCode = document.getElementById('account_head_id1').value.split('_')[2];
            var narration = document.getElementById('narration1').value;
            var cheque_no = document.getElementById('cheque_no1').value;
            var cheque_date = document.getElementById('cheque_date1').value;
            var amount = parseFloat(document.getElementById('amount1').value);
            // var TotalAmount = parseInt(document.getElementById('TotalAmount').innerHTML);
            // var grandTotalAmount = TotalAmount + amount;
            var app = @json($Accountsbelow);
            var tableHtml = `<tr>`;
            var option = `<option value="${partyId}" selected>${partyCode}-${partyName}</option>`;
            $.each(app, function(i, v) {
                    option +=`<option value="${v.id}">${v.code} - ${v.party_name}</option>`;
                });
            tableHtml +=`<td><select class="form-control grades" name="party_id[]" id="party_id">
                ${option}
            </select>
                </td>`;
            tableHtml +=
                `<td><input type='text' name='narration[]' id='narration' value='${narration}' class='form-control'/></td>`;
            tableHtml +=
                `<td style="display: none;"><input type='text' name='cheque_no[]' id='cheque_no' value='${cheque_no}' class='form-control'/></td>`;
            tableHtml +=
                `<td style="display: none;"><input type='date' name='cheque_date[]' id='cheque_date' value='${cheque_date}' class='form-control'/></td>`;
            tableHtml += `<td><input type='text' name='amount[]' id='amount' value='${amount}' onkeyup='TotalGrandAmount()' onkeypress='return isNumberKey(event)' class='form-control'/></td>`;
            tableHtml +=
                `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
            tableHtml += `</tr>`;

            $('#GridTable').append(tableHtml);
            $(".grades").select2({
                            placeholder: "Select Account",
                            allowClear: true
                            });
            TotalGrandAmount();
            $('#account_head_id1').val(null).select2('open');
            $('#select2-account_head_id1-container').text('Select Party/Account');
            $('#select2-account_head_id1-container').attr('title', '');
            $('#cheque_no1').val(null);
            $('#narration1').val(null);

            let date = new Date();
            // $('#cheque_date1').val(date.getFullYear()+'-'+(parseInt(date.getMonth())+1)+'-'+date.getDate());
            $('#amount1').val(null);
            // $('#TotalAmount').text(grandTotalAmount);
        }
    </script>
    <!-- End Append New Data on Table -->

    <script>
        $(document).ready(function() {
            // Hide Alert Notification After 3 Seconds
            setInterval(() => {
                $('.alert').hide();
            }, 3000);
            // End Hide Alert Notification After 3 Seconds


            // Form Submit
            $('.submit-form').click(function() {
                var Bankid = $('#account_id').val();
                if(!Bankid)
                {
                    $('.bank_err').text('The Bank Account is required.');
                    window.scroll({top: 100, left: 100, behavior: "smooth"});
                    return false;
                }
                else{
                    $('#bank-receipt-form').submit();
                    $('.submit-form').attr('disabled', true);
                }
                
            });
            // End FOrm Submit

            // Reset btn feature
            $('.reset-btn').click(function() {
                $('#account_id').val(null).select2();
                $('#select2-account_id-container').text('Select Bank Account Name');
                $('#select2-account_id-container').attr('title', 'Select Bank Account Name');
                $('#updated_by_name').addClass('d-none');
                $('#update_voucher_no').val(null);
            });
            // End Reset btn feature
        });
    </script>


    <!-- Main Features -->
    <script>
        $(document).ready(function() {
            // Delete Record
            $('.delete_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('delete-record-modal'), {});
                myModal.toggle();
                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                $('#delete_voucher_no').val(voucher_no);
                $('#delete_warehouseID').val(warehouseID);
            });
             // Print Record
             $('.print_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                myModal.toggle();

                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                var base_url = $('#base_url').val();
                $.ajax({
                    url: "{{ URL::to('bank-receipts/print/voucher') }}",
                    type: 'get',
                    data: {
                        voucher_no: voucher_no, warehouseID: warehouseID
                        },
                    beforeSend: function(response) {
                        $('#print-receipt-modal-body').html(
                            '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                        );
                    },
                    success: function(response) {
                        if (response != null && response!=0) {
                            $('#print-receipt-modal-body').html(
                                `<object data="${base_url}/resources/upload/bank-receipt/${response}" type="application/pdf" width="100%" height="800"></object>`
                            );
                        } else {
                            $('#print-receipt-modal-body').html('<h2 style="color:red;text-align:center;">Voucher Not Exist</h2>');
                        }
                    }
                });
            });
        });
    </script>
    <!-- End Main Features -->

    <!-- Delete Row -->
    <script>
        function DeleteRow(row) {
            // var TotalAmount = parseInt(document.getElementById('TotalAmount').innerHTML);
            // var NewAmount = parseInt($(row).find("td:eq('4')").find('input').val());
            // document.getElementById('TotalAmount').innerHTML = (TotalAmount - NewAmount);
            $(row).remove();
            TotalGrandAmount();
        }

        function TotalGrandAmount() {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                if (tableData.rows[i].cells[4].getElementsByTagName('input')[0].value == '') {
                    sum += 0;
                } else {
                    sum += parseFloat(tableData.rows[i].cells[4].getElementsByTagName('input')[0].value);
                }   
            }
            document.getElementById('TotalAmount').innerText = sum.toLocaleString('en-US');
        }
    </script>
    <!-- End Delete Row -->

    <!-- Load & Edit Record -->
    <script>
        $(document).ready(function() {
            $('.load-edit-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                $.ajax({
                    url: "{{ URL::to('bank-receipts/load/record') }}",
                    type: 'get',
                    data: {
                    voucher_no: voucher_no, warehouseID: warehouseID
                    },
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            var tableHtml = '';
                            // var totalAmount = 0;
                            var VoucherDate = '';
                            var VoucherNo = 0;
                            var BankAccount = 0;

                            $.each(response.data, function(i, v) {
                                if (v.credit != null) {
                                    tableHtml += `<tr>`;
                                    // tableHtml +=
                                    //     `<td>${v.parties.code} - ${v.parties.party_name}<input type='hidden' name='party_id[]' id='party_id' value='${v.parties.id}' /></td>`;
                                    var option = `<option value="${v.parties.id}" selected>${v.parties.code} - ${v.parties.party_name}</option>`;
                                        $.each(response.Accountsbelow, function(i, v) {
                                            option +=`<option value="${v.id}">${v.code} - ${v.party_name}</option>`;
                                        });
                                        tableHtml +=`<td><select class="form-control grades" name="party_id[]" id="party_id">
                                         ${option}
                                        </select>
                                            </td>`;    
                                    tableHtml +=
                                        `<td><input type='text' name='narration[]' id='narration' value='${v.narration}' class='form-control'/></td>`;
                                    tableHtml +=
                                        `<td style="display: none;"><input type='text' name='cheque_no[]' id='cheque_no' value='${v.cheque_no}' class='form-control'/></td>`;
                                    tableHtml +=
                                        `<td style="display: none;"><input type='date' name='cheque_date[]' id='cheque_date' value='${v.cheque_date}' class='form-control'/></td>`;
                                    tableHtml +=
                                        `<td><input type='text' name='amount[]' id='amount' value='${parseFloat(v.credit).toFixed(2)}' onkeyup='TotalGrandAmount()' onkeypress='return isNumberKey(event)' class='form-control'/></td>`;
                                    tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;

                                    // totalAmount += parseInt(v.credit);
                                    VoucherNo = v.voucher_no;
                                    VoucherDate = v.date;
                                    BankAccount = v.other_head_id;
                                }
                            });

                            $('#GridTable').html(tableHtml);
                            // $('#TotalAmount').text(totalAmount);
                            $(".grades").select2({
                            placeholder: "Select Account",
                            allowClear: true
                            });
                            TotalGrandAmount();
                            $('#update_voucher_no').val(VoucherNo);
                            $('#updated_by_name').removeClass('d-none');
                            $('#voucher_no').val(VoucherNo);
                            $('#voucher_date').val(VoucherDate);
                            $('#account_id').val(BankAccount).select2();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('#GridTable').html(null);
                            $('#TotalAmount').text(0);
                            $('#update_voucher_no').val(null);
                            $('#updated_by_name').addClass('d-none');
                            var current_date = $('#current_date').val();
                            $('#voucher_date').val(current_date);
                            $('#account_id').val(null).select2();
                            $('#select2-account_id-container').text('Select Bank Account Name');
                            $('#select2-account_id-container').attr('title',
                                'Select Bank Account Name');
                        }
                    }
                });
            });



            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                $.ajax({
                    url: "{{ URL::to('bank-receipts/load/next/record') }}",
                    type: 'get',
                    data: {
                    voucher_no: voucher_no, warehouseID: warehouseID
                    },
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            var tableHtml = '';
                            // var totalAmount = 0;
                            var VoucherDate = '';
                            var VoucherNo = 0;
                            var BankAccount = 0;

                            $.each(response.data, function(i, v) {
                                if (v.credit != null) {
                                    tableHtml += `<tr>`;
                                    // tableHtml +=
                                    //     `<td>${v.parties.code} - ${v.parties.party_name}<input type='hidden' name='party_id[]' id='party_id' value='${v.parties.id}' /></td>`;
                                    var option = `<option value="${v.parties.id}" selected>${v.parties.code} - ${v.parties.party_name}</option>`;
                                        $.each(response.Accountsbelow, function(i, v) {
                                            option +=`<option value="${v.id}">${v.code} - ${v.party_name}</option>`;
                                        });
                                        tableHtml +=`<td><select class="form-control grades" name="party_id[]" id="party_id">
                                         ${option}
                                        </select>
                                            </td>`;
                                        tableHtml +=
                                        `<td><input type='text' name='narration[]' id='narration' value='${v.narration}' class='form-control'/></td>`;
                                    tableHtml +=
                                        `<td style="display: none;"><input type='text' name='cheque_no[]' id='cheque_no' value='${v.cheque_no}' class='form-control'/></td>`;
                                    tableHtml +=
                                        `<td style="display: none;"><input type='date' name='cheque_date[]' id='cheque_date' value='${v.cheque_date}' class='form-control'/></td>`;
                                    tableHtml +=
                                        `<td><input type='text' name='amount[]' id='amount' value='${parseFloat(v.credit).toFixed(2)}' onkeyup='TotalGrandAmount()' onkeypress='return isNumberKey(event)' class='form-control'/></td>`;
                                    tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;

                                    // totalAmount += parseInt(v.credit);
                                    VoucherNo = v.voucher_no;
                                    VoucherDate = v.date;
                                    BankAccount = v.other_head_id;
                                }
                            });

                            $('#GridTable').html(tableHtml);
                            // $('#TotalAmount').text(totalAmount);
                            $(".grades").select2({
                            placeholder: "Select Account",
                            allowClear: true
                            });
                            TotalGrandAmount();
                            $('#update_voucher_no').val(VoucherNo);
                            $('#updated_by_name').removeClass('d-none');
                            $('#voucher_no').val(VoucherNo);
                            $('#voucher_date').val(VoucherDate);
                            $('#account_id').val(BankAccount).select2();
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('#GridTable').html(null);
                            $('#TotalAmount').text(0);
                            $('#update_voucher_no').val(null);
                            $('#updated_by_name').addClass('d-none');
                            var current_date = $('#current_date').val();
                            $('#voucher_date').val(current_date);
                            $('#account_id').val(null).select2();
                            $('#select2-account_id-container').text('Select Bank Account Name');
                            $('#select2-account_id-container').attr('title',
                                'Select Bank Account Name');
                        }
                    }
                });
            });
            // End Here of Load Next Record


            // Load Previous Record
            $('.load-previous-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                $.ajax({
                    url: "{{ URL::to('bank-receipts/load/previous/record') }}",
                    type: 'get',
                    data: {
                        voucher_no: voucher_no, warehouseID: warehouseID
                    },
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            var tableHtml = '';
                            // var totalAmount = 0;
                            var VoucherDate = '';
                            var VoucherNo = 0;
                            var BankAccount = 0;

                            $.each(response.data, function(i, v) {
                                if (v.credit != null) {
                                    tableHtml += `<tr>`;
                                    // tableHtml +=
                                        // `<td>${v.parties.code} - ${v.parties.party_name}<input type='hidden' name='party_id[]' id='party_id' value='${v.parties.id}'/></td>`;

                                        var option = `<option value="${v.parties.id}" selected>${v.parties.code} - ${v.parties.party_name}</option>`;
                                        $.each(response.Accountsbelow, function(i, v) {
                                            option +=`<option value="${v.id}">${v.code} - ${v.party_name}</option>`;
                                        });
                                        tableHtml +=`<td><select class="form-control grades" name="party_id[]" id="party_id">
                                         ${option}
                                        </select>
                                            </td>`;
                                    tableHtml +=
                                        `<td><input type='text' name='narration[]' id='narration' value='${v.narration}' class='form-control'/></td>`;
                                    tableHtml +=
                                        `<td style="display: none;"><input type='text' name='cheque_no[]' id='cheque_no' value='${v.cheque_no}' class='form-control'/></td>`;
                                    tableHtml +=
                                        `<td style="display: none;"><input type='date' name='cheque_date[]' id='cheque_date' value='${v.cheque_date}' class='form-control'/></td>`;
                                    tableHtml +=
                                        `<td><input type='text' name='amount[]' id='amount' value='${parseFloat(v.credit).toFixed(2)}' onkeyup='TotalGrandAmount()' onkeypress='return isNumberKey(event)' class='form-control'/></td>`;
                                    tableHtml +=
                                        `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                    tableHtml += `</tr>`;
                                    // totalAmount += parseInt(v.credit);
                                    VoucherNo = v.voucher_no;
                                    // alert(VoucherNo);
                                    VoucherDate = v.date;
                                    BankAccount = v.other_head_id;
                                }
                            });

                            $('#GridTable').html(tableHtml);
                            // $('#TotalAmount').text(totalAmount);
                            $(".grades").select2({
                            placeholder: "Select Account",
                            allowClear: true
                            });
                            TotalGrandAmount();
                            $('#update_voucher_no').val(VoucherNo);
                            $('#updated_by_name').removeClass('d-none');
                            $('#voucher_no').val(VoucherNo);
                            $('#voucher_date').val(VoucherDate);
                            $('#account_id').val(BankAccount).select2();
                            
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('#GridTable').html(null);
                            $('#TotalAmount').text(0);
                            $('#update_voucher_no').val(null);
                            $('#updated_by_name').addClass('d-none');
                            var current_date = $('#current_date').val();
                            $('#voucher_date').val(current_date);
                            $('#account_id').val(null).select2();
                            $('#select2-account_id-container').text('Select Bank Account Name');
                            $('#select2-account_id-container').attr('title',
                                'Select Bank Account Name');
                        }
                    }
                });
            });
            // End Here of Load Previous Record

            $('#warehouse_id').change(function() {
                // var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                // alert(warehouseID);
                $.ajax({
                    url: "{{ URL::to('bank-receipts/warehouse/voucherno') }}",
                    type: 'get',
                    data: {
                        warehouseID: warehouseID
                    },
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.codes != '') {

                            // $('#GridTable').html(tableHtml);
                            // $('#TotalAmount').text(totalAmount);
                            $('#voucher_no').val(response.codes);
                        }
                    }
                });
            });
        });
    </script>
    <!-- End Load & Edit Record -->

    @include('include.toast-messages')
@stop
