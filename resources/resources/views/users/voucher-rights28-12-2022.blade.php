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
                            {!! Form::hidden('total_vouchers', $voucher_names->count(), ['id' => 'total_vouchers']) !!}
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
                                                Vouchers
                                            </li>
                                            @foreach ($voucher_names as $key => $voucher)
                                                @php
                                                    $flag = false;
                                                @endphp
                                                @foreach ($selected_vouchers_names as $selected_voucher_name)
                                                    @if ($selected_voucher_name->voucher_name == $voucher->title)
                                                        <li class="list-group-item list-group-item-action">
                                                            <input type="checkbox" name="voucher_name[]"
                                                                id="md_checkbox{{ $key + 1 }}"
                                                                idd="{{ $key + 1 }}" class="chk-col-red list"
                                                                value="{{ $voucher->title }}" checked>
                                                            <label for="md_checkbox{{ $key + 1 }}"
                                                                style="margin-bottom: 0px!important;font-weight: bold;">{{ $voucher->title }}</label>
                                                        </li>
                                                        @php
                                                            $flag = true;
                                                            break;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                @if ($flag == false)
                                                    <li class="list-group-item list-group-item-action">
                                                        <input type="checkbox" name="voucher_name[]"
                                                            id="md_checkbox{{ $key + 1 }}" idd="{{ $key + 1 }}"
                                                            class="chk-col-red list" value="{{ $voucher->title }}">
                                                        <label for="md_checkbox{{ $key + 1 }}"
                                                            style="margin-bottom: 0px!important;font-weight: bold;">{{ $voucher->title }}</label>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <h3 style="margin-top: -31px;color:black">User: {{ $user->name }} || GoDown:
                                        {{ $user->warehouse->name }}</h3>
                                    <ul class="list-group mt-1">
                                        <li class="list-group-item list-group-item-action active"
                                            style="background-color: #3598DC;border-color:#3598DC;color:white;font-weight: bold;">
                                            Rights</li>
                                        @foreach ($voucher_names as $key => $voucher)
                                            <li
                                                class="list-group-item list-group-item-action list-group-item{{ $key + 1 }} d-none">
                                                @foreach ($rightNames as $key1 => $right)
                                                    @php
                                                        $flag = false;
                                                    @endphp
                                                    @foreach ($selected_vouchers->where('user_id', $user_id)->where('voucher_name', $voucher->title)->where('right_name', $right->name) as $value)
                                                        <input type="checkbox" name="right_name[]"
                                                            id="md_checkbox{{ $key }}{{ $key1 + 1 }}"
                                                            class="chk-col-red"
                                                            value="{{ $right->name }}_{{ $voucher->title }}" checked>
                                                        <label for="md_checkbox{{ $key }}{{ $key1 + 1 }}"
                                                            style="margin-bottom: 0px!important;font-weight: bold;">
                                                            {{ $right->name }}
                                                        </label>
                                                        &emsp;
                                                        @php
                                                            $flag = true;
                                                            break;
                                                        @endphp
                                                    @endforeach

                                                    @if ($flag == false)
                                                        <input type="checkbox" name="right_name[]"
                                                            id="md_checkbox{{ $key }}{{ $key1 + 1 }}"
                                                            class="chk-col-red"
                                                            value="{{ $right->name }}_{{ $voucher->title }}">
                                                        <label for="md_checkbox{{ $key }}{{ $key1 + 1 }}"
                                                            style="margin-bottom: 0px!important;font-weight: bold;">
                                                            {{ $right->name }}
                                                        </label>
                                                        &emsp;
                                                    @endif
                                                @endforeach
                                            </li>
                                        @endforeach
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
                let total_vouchers = $('#total_vouchers').val();


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
                    $(`.list-group-item${id} input`).removeAttr('checked');
                }
            });
        });
    </script>
    <!-- End Show Level Rights -->
    @include('include.toast-messages')
@stop
