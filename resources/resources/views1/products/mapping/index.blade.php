@extends('app')
@section('head')
    <title>Products</title>
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Products Mapping</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Products Mapping</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>Product Mapping</h6>
                            <!-- <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul> -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                 @include('errors.validation')
                                <div class="col">
                                    {!! Form::open([
                                        'url' => 'products-mapping',
                                        'class' => 'form-horizontal',
                                        'id' => 'product-form',
                                    ]) !!}
                                        @foreach($warehouses as $warehouse)
                                    <div class="row">
                                        
                                            <div class="col-lg-5 col-md-5 col-5">
                                                <div class="form-group">
                                                    <h5>{{$warehouse->name}} <span class="text-danger">*</span></h5>
                                                    <!-- {!! Form::hidden('warehouse_id', null, [
                                                        'warehouse_id' => 'product_name',
                                                    ]) !!} -->
                                                        <input type="hidden" id="warehouse_id" name="warehouse_id[]" value="{{$warehouse->id}}">
                                                </div>
                                            </div>
                                            @if($warehouse->products_mapping)
                                            <div class="col-lg-5 col-md-5 col-5">
                                                <div class="form-group">
                                                    <!-- <h5>Name <span class="text-danger">*</span></h5> -->
                                                    {!! Form::select('product_warehouse_id[]', $warehousesmap, $warehouse->products_mapping->product_warehouse_id, [
                                                        'id' => 'product_warehouse_id',
                                                        'class' => 'form-control select2',
                                                    ]) !!}
                                                   
                                                </div>
                                            </div>
                                            @else
                                            <div class="col-lg-5 col-md-5 col-5">
                                                <div class="form-group">
                                                    <!-- <h5>Name <span class="text-danger">*</span></h5> -->
                                                    {!! Form::select('product_warehouse_id[]', $warehousesmap, null, [
                                                        'id' => 'product_warehouse_id',
                                                        'class' => 'form-control select2',
                                                    ]) !!}
                                                   
                                                </div>
                                            </div>
                                            @endif
                                        
                                    </div>
                                    @endforeach
                                    <div class="text-xs-right bt-1 pt-10">
                                        <button type="submit" class="btn btn-info submit_btn" tabindex="14"
                                            onclick="FormSubmit()">Submits</button>
                                        <!-- <button type="reset" class="btn btn-primary reset_btn" tabindex="15">Reset</button> -->
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
<script src="{{ URL::asset('dashboard/datatables/jquery.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.validate.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript">
        $(document).ready(function() {
        function FormSubmit() {
            alert "Ddd";
                // $('#product-form').submit();
                // return true;
            }

    });
    </script>
    @stop