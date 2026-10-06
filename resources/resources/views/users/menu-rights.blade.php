@extends('app')
@section('head')
    <title>Menu Rights</title>
    <style>
        /* Loader */
        .loader {
            text-align: center;
            align-items: center;
            margin: 0px auto;
            margin-top: 130px;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid blue;
            border-right: 16px solid green;
            border-bottom: 16px solid red;
            border-left: 16px solid pink;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

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

        .box-body ul {
            line-height: 0px !important;
        }

        .list-group-item:first-child {
            border-radius: 0px !important;
            margin-top: -10px;
        }
    </style>
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Menu Rights
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="{{ URL::to('user-rights') }}">Users Rights</a></li>
                <li class="breadcrumb-item active"><a href="#">Menu Rights</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> MENU RIGHTS</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            {!! Form::open(['url' => 'menus-rights', 'class' => 'form-horizontal']) !!}
                            {!! Form::hidden('user_id', $user_id, ['id' => 'user_id']) !!}
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                    <a href="{{ URL::to('user-rights') }}" class="btn btn-secondary btn-sm">Close</a>
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
                                                style="background-color: #E7505A;border-color:#E7505A;color:white;">
                                                Level 1</li>
                                            @foreach ($rightsLevel1 as $key => $value)
                                                @php $data = ''; @endphp
                                                @foreach ($MenuRights1 as $menuRight)
                                                    @if ($menuRight->level_id == $value->id)
                                                        @php
                                                            $data = $value->id;
                                                            break;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                @if ($data == $value->id)
                                                    <li class="list-group-item list-group-item-action">
                                                        <input type="checkbox" name="level1_id[]"
                                                            id="md_checkbox1_{{ $key }}" idd="{{ $value->id }}"
                                                            class="chk-col-red list1" value="{{ $value->id }}" checked>
                                                        <label for="md_checkbox1_{{ $key }}"
                                                            style="margin-bottom: 0px!important;">{{ $value->title }}</label>
                                                    </li>
                                                @else
                                                    <li class="list-group-item list-group-item-action">
                                                        <input type="checkbox" name="level1_id[]"
                                                            id="md_checkbox1_{{ $key }}" idd="{{ $value->id }}"
                                                            class="chk-col-red list1" value="{{ $value->id }}">
                                                        <label for="md_checkbox1_{{ $key }}"
                                                            style="margin-bottom: 0px!important;">{{ $value->title }}</label>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <h3 style="margin-top: -31px;" id="show-right-level2-user-name">User :
                                        {{ $user->name }}
                                    </h3>
                                    <div class="demo-checkbox">
                                        <ul class="list-group">
                                            <li class="list-group-item list-group-item-action active"
                                                style="background-color: #E7505A;border-color:#E7505A;color:white;">
                                                Level 2</li>
                                            @foreach ($rightsLevel2 as $key2 => $level2)
                                                @php
                                                    $data = '';
                                                @endphp
                                                @foreach ($MenuRights2 as $menuRight)
                                                    @if ($menuRight->level_id == $level2->id)
                                                        @php
                                                            $data = $level2->id;
                                                            break;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                @foreach ($rightsLevel1 as $key1 => $level1)
                                                    @if ($level1->id == $level2->right_level1_id)
                                                        @if ($data == $level2->id)
                                                            <li class="list-group-item list-group-item-action list2Parent d-none"
                                                                id="rightLevel2{{ $level1->id }}">
                                                                <input type="checkbox" name="level2_id[]"
                                                                    id="md_checkbox2_{{ $key2 }}"
                                                                    idd="{{ $level2->id }}" class="chk-col-red list2"
                                                                    value="{{ $level2->id }}" checked>
                                                                <label for="md_checkbox2_{{ $key2 }}"
                                                                    style="margin-bottom: 0px!important;">{{ $level2->title }}</label>
                                                            </li>
                                                        @else
                                                            <li class="list-group-item list-group-item-action list2Parent d-none"
                                                                id="rightLevel2{{ $level1->id }}">
                                                                <input type="checkbox" name="level2_id[]"
                                                                    id="md_checkbox2_{{ $key2 }}"
                                                                    idd="{{ $level2->id }}" class="chk-col-red list2"
                                                                    value="{{ $level2->id }}">
                                                                <label for="md_checkbox2_{{ $key2 }}"
                                                                    style="margin-bottom: 0px!important;">{{ $level2->title }}</label>
                                                            </li>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <h3 style="margin-top: -31px;" id="show-right-level3-user-name">User :
                                        {{ $user->name }}
                                    </h3>
                                    <div class="demo-checkbox">
                                        <ul class="list-group mt-1">
                                            <li class="list-group-item list-group-item-action active"
                                                style="background-color: #E7505A;border-color:#E7505A;color:white;">
                                                Level 3</li>
                                            @foreach ($rightsLevel3 as $key3 => $level3)
                                                @php
                                                    $data = '';
                                                @endphp
                                                @foreach ($MenuRights3 as $menuRight)
                                                    @if ($menuRight->level_id == $level3->id)
                                                        @php
                                                            $data = $level3->id;
                                                            break;
                                                        @endphp
                                                    @endif
                                                @endforeach

                                                @foreach ($rightsLevel2 as $key2 => $level2)
                                                    @if ($level2->id == $level3->right_level2_id)
                                                        @if ($data == $level3->id)
                                                            <li class="list-group-item list-group-item-action list3Parent d-none"
                                                                id="rightLevel3{{ $level3->right_level2_id }}">
                                                                <input type="checkbox" name="level3_id[]"
                                                                    id="md_checkbox3_{{ $key3 }}"
                                                                    class="chk-col-red list3" value="{{ $level3->id }}"
                                                                    checked>
                                                                <label for="md_checkbox3_{{ $key3 }}"
                                                                    style="margin-bottom: 0px!important;">{{ $level3->title }}</label>
                                                            </li>
                                                        @else
                                                            <li class="list-group-item list-group-item-action list3Parent d-none"
                                                                id="rightLevel3{{ $level3->right_level2_id }}">
                                                                <input type="checkbox" name="level3_id[]"
                                                                    id="md_checkbox3_{{ $key3 }}"
                                                                    class="chk-col-red list3"
                                                                    value="{{ $level3->id }}">
                                                                <label for="md_checkbox3_{{ $key3 }}"
                                                                    style="margin-bottom: 0px!important;">{{ $level3->title }}</label>
                                                            </li>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </ul>
                                    </div>
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
    <!-- Show Levvel 2 & 3 -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('.list1').click(function() {
                let id = $(this).attr('idd');

                if ($(this).is(':checked')) {
                    $('.list2Parent').addClass('d-none');
                    const rightLevel2 = document.querySelectorAll(`#rightLevel2${id}`);
                    rightLevel2.forEach(box => {
                        box.classList.remove('d-none');
                    });
                } else {
                    $('.list2Parent').addClass('d-none');
                    $('.list3Parent').addClass('d-none');
                }
            });
            $('.list2').click(function() {
                let id = $(this).attr('idd');

                if ($(this).is(':checked')) {
                    $('.list3Parent').addClass('d-none');
                    const rightLevel3 = document.querySelectorAll(`#rightLevel3${id}`);
                    rightLevel3.forEach(box => {
                        box.classList.remove('d-none');
                    });
                } else {
                    $('.list3Parent').addClass('d-none');
                }
            });
        });
    </script>
    <!-- End Show Levvel 2 & 3 -->
    @include('include.toast-messages')
@stop
