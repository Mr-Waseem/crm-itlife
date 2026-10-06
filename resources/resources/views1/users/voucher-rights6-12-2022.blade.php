@extends('app')
@section('head')
    <title>Voucher Rights</title>
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Voucher Rights
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="{{ URL::to('user-rights') }}">Users Rights</a></li>
                <li class="breadcrumb-item active"><a href="#">Voucher Rights</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> VOUCHER RIGHTS</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            {!! Form::open(['url' => 'vouchers-rights', 'class' => 'form-horizontal']) !!}
                            {!! Form::hidden('user_id', $user_id, ['id' => 'user_id']) !!}
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-dismiss="modal">Close</button>
                                    @error('level1_id')
                                        <div class="alert alert-danger alert-dismissable text-white">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">×</button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="demo-checkbox">
                                        <ul class="list-group mt-1">
                                            <li class="list-group-item list-group-item-action active"
                                                style="background-color: #E7505A;border-color:#E7505A;color:white;font-weight: bold;">
                                                Vouchers</li>
                                            <li class="list-group-item list-group-item-action">
                                                <input type="checkbox" name="voucher_name1" id="md_checkbox1" idd="1"
                                                    class="chk-col-red list" value="CASH_RECEIPT_VOUCHER">
                                                <label for="md_checkbox1"
                                                    style="margin-bottom: 0px!important;font-weight: bold;">CASH
                                                    RECEIPT VOUCHER</label>
                                            </li>
                                            <li class="list-group-item list-group-item-action">
                                                <input type="checkbox" name="voucher_name2" id="md_checkbox2" idd="2"
                                                    class="chk-col-red list" value="CASH_PAYMENT_VOUCHER">
                                                <label for="md_checkbox2"
                                                    style="margin-bottom: 0px!important;font-weight: bold;">CASH
                                                    PAYMENT VOUCHER</label>
                                            </li>
                                            <li class="list-group-item list-group-item-action">
                                                <input type="checkbox" name="voucher_name3" id="md_checkbox3" idd="3"
                                                    class="chk-col-red list" value="BANK_RECEIPT_VOUCHER">
                                                <label for="md_checkbox3"
                                                    style="margin-bottom: 0px!important;font-weight: bold;">BANK
                                                    RECEIPT VOUCHER</label>
                                            </li>
                                            <li class="list-group-item list-group-item-action">
                                                <input type="checkbox" name="voucher_name4" id="md_checkbox4" idd="4"
                                                    class="chk-col-red list" value="BANK_PAYMENT_VOUCHER">
                                                <label for="md_checkbox4"
                                                    style="margin-bottom: 0px!important;font-weight: bold;">BANK
                                                    PAYMENT VOUCHER</label>
                                            </li>
                                            <li class="list-group-item list-group-item-action">
                                                <input type="checkbox" name="voucher_name5" id="md_checkbox5" idd="5"
                                                    class="chk-col-red list" value="JOURNAL_VOUCHER">
                                                <label for="md_checkbox5"
                                                    style="margin-bottom: 0px!important;font-weight: bold;">JOURNAL
                                                    VOUCHER</label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <ul class="list-group mt-1">
                                        <li class="list-group-item list-group-item-action active"
                                            style="background-color: #3598DC;border-color:#3598DC;color:white;font-weight: bold;">
                                            Rights</li>
                                        <li class="list-group-item list-group-item-action list-group-item1 d-none">
                                            <input type="checkbox" name="right_name1[]" id="md_checkbox11"
                                                class="chk-col-red" value="ADD">
                                            <label for="md_checkbox11"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                ADD
                                            </label>
                                            &emsp;
                                            <input type="checkbox" name="right_name1[]" id="md_checkbox12"
                                                class="chk-col-red" value="EDIT">
                                            <label for="md_checkbox12"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                EDIT
                                            </label>
                                            &emsp;
                                            <input type="checkbox" name="right_name1[]" id="md_checkbox13"
                                                class="chk-col-red" value="DELETE">
                                            <label for="md_checkbox13"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                DELETE
                                            </label>
                                            &emsp;
                                            <input type="checkbox" name="right_name1[]" id="md_checkbox14"
                                                class="chk-col-red" value="PRINT">
                                            <label for="md_checkbox14"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                PRINT
                                            </label>
                                        </li>
                                        <li class="list-group-item list-group-item-action list-group-item2 d-none">
                                            <input type="checkbox" name="right_name2[]" id="md_checkbox21"
                                                class="chk-col-red" value="ADD">
                                            <label for="md_checkbox21"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                ADD
                                            </label>
                                            &emsp;
                                            <input type="checkbox" name="right_name2[]" id="md_checkbox22"
                                                class="chk-col-red" value="EDIT">
                                            <label for="md_checkbox22"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                EDIT
                                            </label>
                                            &emsp;
                                            <input type="checkbox" name="right_name2[]" id="md_checkbox23"
                                                class="chk-col-red" value="DELETE">
                                            <label for="md_checkbox23"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                DELETE
                                            </label>
                                            &emsp;
                                            <input type="checkbox" name="right_name2[]" id="md_checkbox24"
                                                class="chk-col-red" value="PRINT">
                                            <label for="md_checkbox24"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                PRINT
                                            </label>
                                        </li>
                                        <li class="list-group-item list-group-item-action list-group-item3 d-none">
                                            <input type="checkbox" name="right_name3[]" id="md_checkbox31"
                                                class="chk-col-red" value="ADD">
                                            <label for="md_checkbox31"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                ADD
                                            </label>
                                            &emsp;
                                            <input type="checkbox" name="right_name3[]" id="md_checkbox32"
                                                class="chk-col-red" value="EDIT">
                                            <label for="md_checkbox32"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                EDIT
                                            </label>
                                            &emsp;
                                            <input type="checkbox" name="right_name3[]" id="md_checkbox33"
                                                class="chk-col-red" value="DELETE">
                                            <label for="md_checkbox33"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                DELETE
                                            </label>
                                            &emsp;
                                            <input type="checkbox" name="right_name3[]" id="md_checkbox34"
                                                class="chk-col-red" value="PRINT">
                                            <label for="md_checkbox34"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                PRINT
                                            </label>
                                        </li>
                                        <li class="list-group-item list-group-item-action list-group-item4 d-none">
                                            <input type="checkbox" name="right_name4[]" id="md_checkbox41"
                                                class="chk-col-red" value="ADD">
                                            <label for="md_checkbox41"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                ADD
                                            </label>
                                            &emsp;
                                            <input type="checkbox" name="right_name4[]" id="md_checkbox42"
                                                class="chk-col-red" value="EDIT">
                                            <label for="md_checkbox42"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                EDIT
                                            </label>
                                            &emsp;
                                            <input type="checkbox" name="right_name4[]" id="md_checkbox43"
                                                class="chk-col-red" value="DELETE">
                                            <label for="md_checkbox43"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                DELETE
                                            </label>
                                            &emsp;
                                            <input type="checkbox" name="right_name4[]" id="md_checkbox44"
                                                class="chk-col-red" value="PRINT">
                                            <label for="md_checkbox44"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                PRINT
                                            </label>
                                        </li>
                                        <li class="list-group-item list-group-item-action list-group-item5 d-none">
                                            <input type="checkbox" name="right_name5[]" id="md_checkbox51"
                                                class="chk-col-red" value="ADD">
                                            <label for="md_checkbox51"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                ADD
                                            </label>
                                            &emsp;
                                            <input type="checkbox" name="right_name5[]" id="md_checkbox52"
                                                class="chk-col-red" value="EDIT">
                                            <label for="md_checkbox52"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                EDIT
                                            </label>
                                            &emsp;
                                            <input type="checkbox" name="right_name5[]" id="md_checkbox53"
                                                class="chk-col-red" value="DELETE">
                                            <label for="md_checkbox53"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                DELETE
                                            </label>
                                            &emsp;
                                            <input type="checkbox" name="right_name5[]" id="md_checkbox54"
                                                class="chk-col-red" value="PRINT">
                                            <label for="md_checkbox54"
                                                style="margin-bottom: 0px!important;font-weight: bold;">
                                                PRINT
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <!-- Show Level Rights -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('.list').click(function() {
                let id = parseInt($(this).attr('idd'));
                if ($(this).is(':checked')) {
                    $(`.list-group-item${id}`).removeClass('d-none');
                    for (i = 1; i < 6; i++) {
                        if (i != id) {
                            $(`.list-group-item${i}`).addClass('d-none');
                        }
                    }
                } else {
                    $(`.list-group-item${id}`).addClass('d-none');
                }
            });
        });
    </script>
    <!-- End Show Level Rights -->
    @include('include.toast-messages')
@stop
