@extends('app')
@section('head')
    <title>Opening Balance</title>
    <!--  Select 2 library start-->
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!--  Select 2 library end-->
@stop
@section('content')
    <!-- Sticky Left Side bar -->
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
            <h1>Opening Balance</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Opening Balance</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>Opening Balance</h6>
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
                                    {!! Form::open(['url' => 'opening-balance', 'class' => 'form-horizontal', 'id' => 'journal-voucher-form']) !!}
                                    {!! Form::hidden('update_voucher_no', null, ['id' => 'update_voucher_no']) !!}
                                    {!! Form::hidden('v_type', 'Opening Balance', ['id' => 'v_type', 'class' => 'form-control']) !!}
                                    {!! Form::hidden('biller', Auth::User()->id, ['id' => 'created_by']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <label for="voucher_date"><i class="fa fa-caret-right"></i> Voucher Date. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::date('voucher_date', date('Y-m-d'), [
                                                'id' => 'voucher_date',
                                                'class' => 'form-control',
                                                'autofocus' => 'autofocus',
                                            ]) !!}
                                            <input type="hidden" id="userrole" name="userrole" value="{{Auth::User()->role}}">
                                            @error('voucher_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            <label for="account_id"><i class="fa fa-caret-right"></i>Select Warehouse.
                                                <span class="text-danger">*</span></label>
                                            {!! Form::select('warehouse_id', $warehouse, null, [
                                                'id' => 'warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '2',
                                            ]) !!}
                                        </div>
                                        @if(Auth::User()->role == "Admin")
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', null, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control'
                                            ]) !!}
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @else
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Voucher No. <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $codes, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control'
                                            ]) !!}
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @endif
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="table-responsive mb-2">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="bg-primary text-center">
                                                            <th style="width: 30%;">Party / Customer</th>
                                                            <th>Description</th>
                                                            <th>Debit</th>
                                                            <th>Credit</th>
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
                                                                    'class' => 'form-control bg-white',
                                                                    'placeholder' => 'Description',
                                                                    'tabindex' => '4',
                                                                ]) !!}
                                                                <span class="narration1_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('debit1', null, [
                                                                    'id' => 'debit1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Debit',
                                                                    'onkeyup' => 'TotalDebit()',
                                                                    'onkeypress' => 'return isNumberKey(event)'
                                                                ]) !!}
                                                                <span class="debit1_err text-danger"></span>
                                                            </td>
                                                            <td>
                                                                {!! Form::text('credit1', null, [
                                                                    'id' => 'credit1',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Credit',
                                                                    'onkeypress' => 'return isNumberKey(event)'
                                                                ]) !!}
                                                                
                                                                <span class="credit1_err text-danger"></span>
                                                            </td>
                                                            
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
                                                                <th>Description</th>
                                                                <th>Debit</th>
                                                                <th>Credit</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2"><strong>Total</strong></td>
                                                                <td class="bg-primary" id="TotalDebit">0</td>
                                                                <td class="bg-info" id="TotalCredit">0</td>
                                                                <td class="bg-info" id="Difference">0</td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        
                                        <div class="col-lg-9 col-md-12 col-12">
                                            <!-- <div class="note note-danger">UnPosted By :</div> -->
                                        </div>
                                        <!-- <div class="col-lg-3 col-md-12 col-12">
                                            <div class="note note-warning">Posted By : {{ Auth::User()->name }}</div>
                                        </div>
                                        <div class="col-lg-3 col-md-12 col-12">
                                            <div class="note note-info">Updated By : <span class="d-none" id="updated_by_name"> {{ Auth::User()->name }}</span></div>
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
                <form action="{{ URL::to('opening-balance/delete-voucher') }}" method="post" id="delete_voucher_form">
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
                
                var userRole = $('#userrole').val();
                // alert(userRole);
                if(userRole == "Admin"){
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
                        $("#warehouse_id").select2('open');
                    }
                }else{
                    // $('#warehouse_id').select2().trigger('select2:close');
                    $("#voucher_no").focus();
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
                    $("#account_head_id1").select2('open');
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
                    $("#debit1").focus();
                }
            });
            $('#debit1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var debit = parseInt($('#debit1').val());
                    if (debit > 0) {
                        $('#credit1').val(0);
                        AddGridData();
                    }
                    $('#credit1').focus();
                }
            });
            $('#credit1').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var credit = parseInt($('#credit1').val());
                    if (credit > 0) {
                        $('#debit1').val(0);
                        AddGridData();
                    } else {
                        AddGridData();
                    }

                }
            });
        });
    </script>
    <!-- End Focus on next field -->

    <!-- Append New Data on Table -->
    <script>
        function AddGridData() {
            $('.account_head_id1_err').text('');
            $('.narration-err').text('');
            $('.debit1_err').text('');
            $('.credit1_err').text('');

            var account_head_id = $('#account_head_id1').val();
            var narration = $('#narration1').val();
            var debit = parseFloat($('#debit1').val());
            var credit = parseFloat($('#credit1').val());

            if (!account_head_id) {
                $('.account_head_id1_err').text('This field is required');
                return false;
            } else {
                $('.account_head_id_err').text('');
            }


            // if (!narration) {
            //     $('.narration-err').text('This field is required');
            //     $('#narration1').focus();
            //     return false;
            // } else {
            //     $('.narration-err').text('');
            // }

            if (debit <= 0 && credit <= 0 || !debit && !credit) {
                $('.debit1_err').text('This field is required & Must be greater than zero');
                $('.credit1_err').text('This field is required & Must be greater than zero');
                $('#debit1').focus();
                return false;
            } else {
                $('.debit1_err').text('');
                $('.credit1_err').text('');
            }

            var partyId = document.getElementById('account_head_id1').value.split('_')[0];
            var partyName = document.getElementById('account_head_id1').value.split('_')[1];
            var partycode = document.getElementById('account_head_id1').value.split('_')[2];
            var narration = document.getElementById('narration1').value;
            var debit = document.getElementById('debit1').value;
            var credit = document.getElementById('credit1').value;
            // var TotalDebit = parseInt(document.getElementById('TotalDebit').innerHTML);
            // var TotalCredit = parseInt(document.getElementById('TotalCredit').innerHTML);
            // var grandTotalDebit = TotalDebit + debit;
            // var grandTotalCredit = TotalCredit + credit;

            var tableHtml = `<tr>`;
            tableHtml += `<td>${partycode}-${partyName}<input type='hidden' name='party_id[]' id='party_id' value='${partyId}' /></td>`;
            tableHtml +=
                `<td><input type='text' name='narration[]' id='narration' value='${narration}' class='form-control' /></td>`;
            tableHtml += `<td><input type='text' name='debit[]' id='debit' value='${debit}' onkeyup="TotalDebit()" onkeypress='return isNumberKey(event)', class='form-control'/></td>`;
            tableHtml += `<td><input type='text' name='credit[]' id='credit' value='${credit}' onkeyup="TotalCredit()" onkeypress='return isNumberKey(event)', class='form-control'/></td>`;
            tableHtml +=
                `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
            tableHtml += `</tr>`;

            $('#GridTable').append(tableHtml);
            $('#account_head_id1').val(null).select2('open');
            $('#select2-account_head_id1-container').text('Select Party/Account');
            $('#select2-account_head_id1-container').attr('title', '');
            // $('#narration1').val(null);
            $('#debit1').val(null);
            $('#credit1').val(null);
            // $('#TotalDebit').text(grandTotalDebit);
            // $('#TotalCredit').text(grandTotalCredit);
            TotalDebit();
            TotalCredit();
        }


        function TotalDebit() {
            var tableData = document.getElementById('GridTable');
            var leng = tableData.rows.length;
            // alert(leng);
            var TotalDebit = 0; var TotalCredit = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                var DebitValue = tableData.rows[i].cells[2].getElementsByTagName('input')[0].value;
                // alert(CurrentValue);
                if(DebitValue ==""){
                    DebitValue = 0;
                }
                TotalDebit += parseFloat(DebitValue);
                // alert(TotalDebit);
                var CreditValue = tableData.rows[i].cells[3].getElementsByTagName('input')[0].value;
                // alert(CurrentValue);
                if(CreditValue ==""){
                    CreditValue = 0;
                }
                TotalCredit += parseFloat(CreditValue);
                // alert(TotalCredit);
                // alert(sum);
            }
            
            document.getElementById('TotalDebit').innerText = TotalDebit.toLocaleString('en-US');
            document.getElementById('TotalCredit').innerText = TotalCredit.toLocaleString('en-US');
            // document.getElementById('Difference').innerText = TotalDebit-TotalCredit.toLocaleString('en-US');
            document.getElementById('Difference').innerText = parseFloat(TotalDebit-TotalCredit).toFixed(2);
            
        }

        function TotalCredit() {
            var tableData = document.getElementById('GridTable');
            var leng = tableData.rows.length;
            // alert(leng);
            var TotalCredit = 0; var TotalDebit = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                var CreditValue = tableData.rows[i].cells[3].getElementsByTagName('input')[0].value;
                // alert(CurrentValue);
                if(CreditValue ==""){
                    CreditValue = 0;
                }
                TotalCredit += parseFloat(CreditValue);
                

                var DebitValue = tableData.rows[i].cells[2].getElementsByTagName('input')[0].value;
                // alert(CurrentValue);
                if(DebitValue ==""){
                    DebitValue = 0;
                }
                TotalDebit += parseFloat(DebitValue);
            }
            // alert(TotalCredit);
            //     alert(TotalDebit);
            document.getElementById('TotalCredit').innerText = TotalCredit.toLocaleString('en-US');
            document.getElementById('TotalDebit').innerText = TotalDebit.toLocaleString('en-US');
            document.getElementById('Difference').innerText = parseFloat(TotalDebit-TotalCredit).toFixed(2);
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
                    var TotalDebit =  document.getElementById('TotalDebit').innerHTML;
                    var TotalCredit =  document.getElementById('TotalCredit').innerHTML;
                    if(TotalDebit != TotalCredit){
                        window.scroll({top: 100, left: 100, behavior: "smooth"});
                        $('#show_err').html(
                    '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="false">×</button><b>Warning: Total Debit and Total Credit are not equal!</b></div>'
                    );
                    return false;
                    }else{
                        $('#journal-voucher-form').submit();
                        $('.submit-form').attr('disabled', true);
                    }
                    // alert(TotalDebit);
                    // alert(TotalCredit);

                // var table = document.getElementById("GridTable");
                // var totalRowCount = table.rows.length; 
                // var even = totalRowCount % 2;
                // if(even == 0){
                //      $('#journal-voucher-form').submit();
                // }else{
                    
                //     window.scroll({top: 100, left: 100, behavior: "smooth"});
                    
                //     $('#show_err').html(
                //     '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="false">×</button><b>Warning: Invalid JV Format!</b></div>'
                // );
                // return false;
                // }
                
            });
            // End FOrm Submit

            // Reset btn feature
            $('.reset-btn').click(function() {
                $('#account_id').val(null).select2();
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
                var warehouseID = parseInt($('#warehouse_id').val())
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
                    url: "{{ URL::to('opening-balance/print/voucher') }}",
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
                            console.log(response);
                            $('#print-receipt-modal-body').html(
                                `<object data="${base_url}/resources/upload/opening-balance/${response}" type="application/pdf" width="100%" height="800"></object>`
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
            var TotalDebit = parseInt(document.getElementById('TotalDebit').innerHTML);
            var NewDebit = parseInt($(row).find("td:eq('2')").find('input').val());
            document.getElementById('TotalDebit').innerHTML = (TotalDebit - NewDebit);

            var TotalCredit = parseInt(document.getElementById('TotalCredit').innerHTML);
            var NewCredit = parseInt($(row).find("td:eq('3')").find('input').val());
            document.getElementById('TotalCredit').innerHTML = (TotalCredit - NewCredit);

            $(row).remove();
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
                    url: "{{ URL::to('opening-balance/load/record') }}",
                    data: {
                        voucher_no: voucher_no, warehouseID: warehouseID
                    },
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            var tableHtml = '';
                            var VoucherDate = '';
                            var VoucherNo = 0;
                            // var totalDebit = 0;
                            // var totalCredit = 0;

                            $.each(response.data, function(i, v) {
                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.parties.code} - ${v.parties.party_name}<input type='hidden' name='party_id[]' id='party_id' value='${v.parties.id}' /></td>`;
                                    tableHtml +=
                                    `<td><input type='text' name='narration[]' id='narration' value='${v.narration}' class='form-control' /></td>`;
                                    if(v.debit){
                                    tableHtml +=
                                    `<td><input type='text' name='debit[]' id='debit' value='${parseFloat(v.debit).toFixed(2)}' onkeyup="TotalDebit()" onkeypress='return isNumberKey(event)', class='form-control'/></td>`;
                                }else{
                                    tableHtml +=
                                    `<td><input type='text' name='debit[]' id='debit' value='' onkeyup="TotalDebit()" onkeypress='return isNumberKey(event)', class='form-control'/></td>`;
                                }
                                if(v.credit){
                                    tableHtml +=
                                    `<td><input type='text' name='credit[]' id='credit' value='${parseFloat(v.credit).toFixed(2)}' onkeyup="TotalCredit()" onkeypress='return isNumberKey(event)', class='form-control'/></td>`;
                                }else{
                                    tableHtml +=
                                    `<td><input type='text' name='credit[]' id='credit' value='' onkeyup="TotalCredit()" onkeypress='return isNumberKey(event)', class='form-control'/></td>`;
                                }
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;

                                VoucherNo = v.voucher_no;
                                VoucherDate = v.date;
                                // totalDebit += parseInt(v.debit);
                                // totalCredit += parseInt(v.credit);
                            });

                            $('#GridTable').html(tableHtml);
                            $('#voucher_no').val(VoucherNo);
                            $('#voucher_date').val(VoucherDate);
                            TotalDebit();
                            TotalCredit();
                            // $('#TotalDebit').text(totalDebit);
                            // $('#TotalCredit').text(totalCredit);
                            $('#update_voucher_no').val(VoucherNo);
                            $('#updated_by_name').removeClass('d-none');
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('#GridTable').html(null);
                            $('#TotalDebit').text(0);
                            $('#TotalCredit').text(0);
                            $('#update_voucher_no').val(null);
                            $('#updated_by_name').addClass('d-none');
                        }
                    }
                });
            });

            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                $.ajax({
                    url: "{{ URL::to('opening-balance/load/next/record') }}",
                    data: {
                        voucher_no: voucher_no, warehouseID: warehouseID
                    },
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            var tableHtml = '';
                            var VoucherDate = '';
                            var VoucherNo = 0;
                            // var totalDebit = 0;
                            // var totalCredit = 0;

                            $.each(response.data, function(i, v) {
                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.parties.code} - ${v.parties.party_name}<input type='hidden' name='party_id[]' id='party_id' value='${v.parties.id}' /></td>`;
                                    tableHtml +=
                                    `<td><input type='text' name='narration[]' id='narration' value='${v.narration}' class='form-control' /></td>`;
                                    if(v.debit){
                                    tableHtml +=
                                    `<td><input type='text' name='debit[]' id='debit' value='${parseFloat(v.debit).toFixed(2)}' onkeyup="TotalDebit()" onkeypress='return isNumberKey(event)', class='form-control'/></td>`;
                                }else{
                                    tableHtml +=
                                    `<td><input type='text' name='debit[]' id='debit' value='' onkeyup="TotalDebit()" onkeypress='return isNumberKey(event)', class='form-control'/></td>`;
                                }
                                if(v.credit){
                                    tableHtml +=
                                    `<td><input type='text' name='credit[]' id='credit' value='${parseFloat(v.credit).toFixed(2)}' onkeyup="TotalCredit()" onkeypress='return isNumberKey(event)', class='form-control'/></td>`;
                                }else{
                                    tableHtml +=
                                    `<td><input type='text' name='credit[]' id='credit' value='' onkeyup="TotalCredit()" onkeypress='return isNumberKey(event)', class='form-control'/></td>`;
                                }
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;

                                VoucherNo = v.voucher_no;
                                VoucherDate = v.date;
                                // totalDebit += parseInt(v.debit);
                                // totalCredit += parseInt(v.credit);
                            });

                            $('#GridTable').html(tableHtml);
                            $('#voucher_no').val(VoucherNo);
                            $('#voucher_date').val(VoucherDate);
                            TotalDebit();
                            TotalCredit();
                            // $('#TotalDebit').text(totalDebit);
                            // $('#TotalCredit').text(totalCredit);
                            $('#update_voucher_no').val(VoucherNo);
                            $('#updated_by_name').removeClass('d-none');
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('#GridTable').html(null);
                            $('#TotalDebit').text(0);
                            $('#TotalCredit').text(0);
                            $('#update_voucher_no').val(null);
                            $('#updated_by_name').addClass('d-none');
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
                    url: "{{ URL::to('opening-balance/load/previous/record') }}",
                    data: {
                        voucher_no: voucher_no, warehouseID: warehouseID
                    },
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            var tableHtml = '';
                            var VoucherDate = '';
                            var VoucherNo = 0;
                            // var totalDebit = 0;
                            // var totalCredit = 0;

                            $.each(response.data, function(i, v) {
                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${v.parties.code} - ${v.parties.party_name}<input type='hidden' name='party_id[]' id='party_id' value='${v.parties.id}' /></td>`;
                                tableHtml +=
                                    `<td><input type='text' name='narration[]' id='narration' value='${v.narration}' class='form-control' /></td>`;
                                
                                if(v.debit){
                                    tableHtml +=
                                    `<td><input type='text' name='debit[]' id='debit' value='${parseFloat(v.debit).toFixed(2)}' onkeyup="TotalDebit()" onkeypress='return isNumberKey(event)', class='form-control'/></td>`;
                                }else{
                                    tableHtml +=
                                    `<td><input type='text' name='debit[]' id='debit' value='' onkeyup="TotalDebit()" onkeypress='return isNumberKey(event)', class='form-control'/></td>`;
                                }
                                if(v.credit){
                                    tableHtml +=
                                    `<td><input type='text' name='credit[]' id='credit' value='${parseFloat(v.credit).toFixed(2)}' onkeyup="TotalCredit()" onkeypress='return isNumberKey(event)', class='form-control'/></td>`;
                                }else{
                                    tableHtml +=
                                    `<td><input type='text' name='credit[]' id='credit' value='' onkeyup="TotalCredit()" onkeypress='return isNumberKey(event)', class='form-control'/></td>`;
                                }
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                                VoucherNo = v.voucher_no;
                                VoucherDate = v.date;
                                // totalDebit += parseFloat(v.debit);
                                // totalCredit += parseFloat(v.credit);
                            });

                            $('#GridTable').html(tableHtml);
                            $('#voucher_no').val(VoucherNo);
                            $('#voucher_date').val(VoucherDate);
                            TotalDebit();
                            TotalCredit();
                            // $('#TotalDebit').text(totalDebit);
                            // $('#TotalCredit').text(totalCredit);
                            $('#update_voucher_no').val(VoucherNo);
                            $('#updated_by_name').removeClass('d-none');
                        } else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );
                            $('#GridTable').html(null);
                            $('#TotalDebit').text(0);
                            $('#TotalCredit').text(0);
                            $('#update_voucher_no').val(null);
                            $('#updated_by_name').addClass('d-none');
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
                    url: "{{ URL::to('opening-balance/warehouse/voucherno') }}",
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
