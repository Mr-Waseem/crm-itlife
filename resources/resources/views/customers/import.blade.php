@extends('app')
@section('head')
    <title>IMPORT CUSTOMERS</title>
    <!--  Select 2 library end-->
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
            IMPORT CUSTOMERS
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">IMPORT CUSTOMERS</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> IMPORT CUSTOMERS</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                        @include('errors.validation')
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col">
                                    {!! Form::open(['url' => 'import-customers', 'class' => 'form-horizontal', 'id' => 'customer-form', 'files' => 'true', 'enctype' => 'multipart/form-data']) !!}
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Choose File <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            {!! Form::file('customer_file', null, [
                                                'id' => 'customer_file',
                                                'class' => 'form-control',
                                                'autofocus' => 'autofocus',
                                                'required' => 'required',
                                                'tabindex' => '1',
                                            ]) !!}
                                            <span id="party_name_err" class="text-danger"></span>
                                            @error('party_name')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                   


                                    <div class="text-xs-right bt-1 pt-10 mt-2">
                                    <button class="btn btn-primary"><a href="{{asset('resources/upload/Import-Customers.xlsx')}}" style="color:white;">Download Format</a></button>
                                        <button type="submit" class="btn btn-info">Submit</button>
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
@stop

@section('scripts')
@include('include.toast-messages')
@stop
