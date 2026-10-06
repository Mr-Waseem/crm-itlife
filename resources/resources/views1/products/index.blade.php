@extends('app')
@section('head')
    <title>Products</title>
    <link href="{{ URL::asset('dashboard/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <!--  Select 2 library start-->
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/optiscroll.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!--  Select 2 library end-->
    <style>
        .show_image > img {
          transition: transform .2s; 
          width: 200px;
          height: 200px;
          margin: 0 auto;
        }
        
        .show_image > img:hover {
          transform: scale(1.5); 
        }
        </style>
@stop
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Products
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Products</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i> Add New Product</h6>
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
                                        'url' => 'products',
                                        'class' => 'form-horizontal',
                                        'id' => 'product-form',
                                        'enctype' => 'multipart/form-data',
                                    ]) !!}
                                    {!! Form::hidden('idd', null, ['id' => 'idd']) !!}
                                    {!! Form::hidden('pack_weight', 50, ['id' => 'pack_weight']) !!}
                                    {!! Form::hidden('category_id1',null, ['id' => 'category_id1']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    {!! Form::hidden('copydata', null, ['id' => 'copydata']) !!}
                                    
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="form-group">
                                                <h5>Name <span class="text-danger">*</span></h5>
                                                {!! Form::text('product_name', null, [
                                                    'id' => 'product_name',
                                                    'class' => 'form-control',
                                                    'autofocus' => 'autofocus',
                                                    'taxindex' => '1',
                                                ]) !!}
                                                <span class="product_name_err text-danger"></span>
                                                @error('product_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group warehouse-box">
                                                <h5>Godown <span class="text-danger">*</span></h5>
                                                {!! Form::select('warehouse_id', $warehouses, null, [
                                                    'id' => 'warehouse_id',
                                                    'class' => 'form-control select2',
                                                    'taxindex' => '2',
                                                ]) !!}
                                                <span class="warehouse_id_err text-danger"></span>
                                                @error('warehouse_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group warehouse-box">
                                                <h5>Departments <span class="text-danger">*</span></h5>
                                                {!! Form::select('department_id', $dept, null, [
                                                    'id' => 'department_id',
                                                    'class' => 'form-control select2',
                                                    'taxindex' => '3',
                                                ]) !!}
                                                <span class="department_id_err text-danger"></span>
                                                @error('department_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group category-box">
                                                <h5>Product Category <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::select('category_id', $catagories, null, [
                                                        'id' => 'category_id',
                                                        'class' => 'form-control select2',
                                                        'taxindex' => '4',
                                                    ]) !!}
                                                    <span class="category_id_err text-danger"></span>
                                                    @error('category_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Product Type <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::select('product_type', $product_type, null, [
                                                        'id' => 'product_type',
                                                        'class' => 'form-control select2',
                                                        'taxindex' => '5',
                                                    ]) !!}
                                                    <span class="product_type_err text-danger"></span>
                                                    @error('product_type')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group uom-box">
                                                <h5>Unit <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::text('uom', null, [
                                                        'id' => 'uom',
                                                        'class' => 'form-control',
                                                        'taxindex' => '6',
                                                    ]) !!}
                                                    <span class="uom_err text-danger"></span>
                                                    @error('uom')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group packtype-box">
                                                <h5>Pack Type <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::select('pack_type', $pack_type, null, [
                                                        'id' => 'pack_type',
                                                        'class' => 'form-control select2',
                                                        'taxindex' => '7',
                                                    ]) !!}
                                                    <span class="pack_type_err text-danger"></span>
                                                    @error('pack_type')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Purchase Rate <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::text('product_cost', null, [
                                                        'id' => 'product_cost',
                                                        'class' => 'form-control',
                                                        'taxindex' => '8',
                                                        'onkeypress'=>"return onlyNumberKey(event)"
                                                    ]) !!}
                                                    <div class="product_cost_err text-danger"></div>
                                                    @error('product_cost')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Sale Rate <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::text('product_price', null, [
                                                        'id' => 'product_price',
                                                        'class' => 'form-control',
                                                        'taxindex' => '9',
                                                        'onkeypress'=>"return onlyNumberKey(event)"
                                                    ]) !!}
                                                    <span class="product_price_err text-danger"></span>
                                                    @error('product_price')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Sales Tax <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::text('tax', 17, [
                                                        'id' => 'tax',
                                                        'class' => 'form-control',
                                                        'taxindex' => '10',
                                                        'onkeypress'=>"return onlyNumberKey(event)"
                                                    ]) !!}
                                                    <span class="tax_err text-danger"></span>
                                                    @error('tax')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Packing <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::text('packing', null, [
                                                        'id' => 'packing',
                                                        'class' => 'form-control',
                                                        'taxindex' => '11',
                                                        'onkeypress'=>"return onlyNumberKey(event)"
                                                    ]) !!}
                                                    <span class="packing_err text-danger"></span>
                                                    @error('packing')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Weight <span class="text-danger">*</span></h5>
                                                <div class="form-group">
                                                    {!! Form::text('weight', null, [
                                                        'id' => 'weight',
                                                        'class' => 'form-control',
                                                        'taxindex' => '12',
                                                        'onkeypress'=>"return onlyNumberKey(event)"
                                                    ]) !!}
                                                    <span class="weight_err text-danger"></span>
                                                    @error('weight')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Dye Pcs</h5>
                                                <div class="form-group">
                                                    {!! Form::text('dye_pcs', null, [
                                                        'id' => 'dye_pcs',
                                                        'class' => 'form-control',
                                                        'taxindex' => '12',
                                                        'onkeypress'=>"return onlyNumberKey(event)"
                                                    ]) !!}
                                                    <span class="weight_err text-danger"></span>
                                                    @error('weight')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <h5>Image</h5>
                                                <div class="form-group">
                                                    <input type="file" name="image" id="image" class="form-control"
                                                        tabindex="13">
                                                    <span class="show_image"></span>
                                                    @error('image')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-xs-right bt-1 pt-10">
                                        <button type="button" class="btn btn-info submit_btn" tabindex="14" onclick="FormSubmit('0')">Submit</button>
                                        <button type="button" class="btn btn-warning copy_btn d-none" tabindex="14" onclick="FormSubmit('1')">Copy</button>
                                        <button type="reset" class="btn btn-primary reset_btn" tabindex="15">Reset</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12">
              
            <!-- <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>Choose Warehouse</h6>
                            <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#"></a></li>
                            </ul>
                        </div>
                     
                        <div class="box-body">
                            <div class="row">
                                 @include('errors.validation')
                                <div class="col">
                                    {!! Form::open([
                                        'url' => 'products/warehouse-products',
                                        'class' => 'form-horizontal',
                                        'id' => 'warehouse-product-form',
                                        'enctype' => 'multipart/form-data',
                                    ]) !!}
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="mt-1">Name <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                        {!! Form::select('warehouse_productID', $warehouses, null, [
                                                    'id' => 'warehouse_productID',
                                                    'class' => 'form-control select2',
                                                    'required' => 'required',
                                                    'taxindex' => '2',
                                                ]) !!}
                                            <span id="warehouse_productID_err" class="text-danger"></span>
                                            @error('warehouse_productID')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                        <button type="button" class="btn btn-info print_warehouse_product" tabindex="14">Load</button>
                                        <button type="button" class="btn btn-primary print_record_btn" tabindex="14"><i class="fa fa-print" style="color: white;"></i></button>
                                        
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>                                       -->
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i> LIST OF PRODUCTS</h6>
                            <!-- <ul class="box-controls pull-right">
                                <li><a class="box-btn-fullscreen" href="#">Print</a></li>
                            </ul> -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                        <div class="row">
                                 @include('errors.validation')
                                <div class="col">
                                    {!! Form::open([
                                        'url' => 'products/warehouse-products',
                                        'class' => 'form-horizontal',
                                        'id' => 'warehouse-product-form',
                                        'enctype' => 'multipart/form-data',
                                    ]) !!}
                                    <div class="row">
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <label class="mt-1">Choose<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                        {!! Form::select('warehouse_productID', $warehouses, null, [
                                                    'id' => 'warehouse_productID',
                                                    'class' => 'form-control select2',
                                                    'required' => 'required',
                                                    'taxindex' => '2',
                                                ]) !!}
                                            <span id="warehouse_productID_err" class="text-danger"></span>
                                            @error('warehouse_productID')
                                                <p class="invalid-feedback1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12">
                                        <!-- <button type="button" class="btn btn-info print_warehouse_product" tabindex="14">Load</button> -->
                                        <button type="button" class="btn btn-primary btn-md print_record_btn"><i class="fa fa-print" style="color: white;"></i></button>
                                        
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover data-table">
                                            <thead>
                                                <tr>
                                                    <th>Sr.</th>
                                                       <th>Code</th>
                                                    <th>Name</th>
                                                    <th>Category</th>
                                                    <th>Type</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="GridTable"></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>Category</th>
                                                    <th>Type</th>
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

    <!-- Print Record Modal -->
        <div class="modal hide fade" id="print-record-modal" role="dialog"
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
                ajax: "{{ URL::to('products') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
                    
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'product_type',
                        name: 'product_type'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>

    <!-- Products Selected Value on Edit -->
    <script>
        $(document).ready(function() {
            // $('.print_warehouse_product').click(function() {
            //     var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
            //     myModal.toggle();

            //     var voucher_no = parseInt($('#voucher_no').val());
            //     var base_url = $('#base_url').val();
            //     $.ajax({
            //         url: "{{ URL::to('production/print/voucher') }}?voucher_no=" +
            //             voucher_no,
            //         type: 'get',
            //         beforeSend: function(response) {
            //             $('#print-receipt-modal-body').html(
            //                 '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
            //             );
            //         },
            //         success: function(response) {
            //             if (response != null && response!=0) {
            //                 $('#print-receipt-modal-body').html(
            //                     `<object data="${base_url}/resources/upload/production/${response}" type="application/pdf" width="100%" height="800"></object>`
            //                 );
            //             } else {
            //                 $('#print-receipt-modal-body').html('<h2 style="color:red;text-align:center;">Voucher Not Exist</h2>');
            //             }
            //         }
            //     });
            // });

            // $('.print_warehouse_product').click(function() {
            $('#warehouse_productID').change(function() {
                // alert("ddd")
                var WarehouseID = parseInt($('#warehouse_productID').val());

                if (!WarehouseID || WarehouseID == '') {
                    // alert("ddd")
                $('#warehouse_productID_err').html('The Warehouse field is required');
                $('#warehouse_productID').focus();
                return false;
            } 
                
                $.ajax({
                    url: "{{ URL::to('products/warehouse-products') }}?Warehouse_ID=" + WarehouseID,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    
                    success: function(response) {
                        if (response != '') {
                            var tableHtml = '';
                            var sum = 0;
                            
                            $.each(response, function(i, v) {
                                sum = sum + 1;
                                tableHtml += `<tr>`;
                                tableHtml += 
                                `<td>${sum}</td>`;
                                tableHtml += 
                                `<td>${v.code}</td>`;
                                tableHtml += 
                                `<td>${v.product_name}</td>`;
                                if(v.category){
                                    tableHtml += 
                                `<td>${v.category.catagory_name}</td>`;
                                }else{
                                    tableHtml += 
                                `<td>No Data</td>`; 
                                }
                                
                                tableHtml += 
                                `<td>${v.product_type}</td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-primary btn-sm edit_btn" 
                                    name="${v.id}_${v.product_name}_${v.warehouse_id}_${v.category_id}_${v.uom}_${v.pack_type}_${v.product_cost}_${v.product_price}_${v.tax}_${v.image}_${v.product_type}_${v.department_id}_${v.packing}_${v.weight}"><i class="fa fa-pencil"></i></button> 
                                    <button type="button" class="btn btn-danger btn-sm remove-product" name="${v.id}"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });
                            $('#GridTable').html(tableHtml);

                        }
                    }
                });
            });

            $('.print_record_btn').click(function() {
                    var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                    myModal.toggle();

                    var base_url = $('#base_url').val();
                    
                    var WarehouseID = $('#warehouse_productID').val();
                    if (!WarehouseID || WarehouseID == '') {
                    // alert("ddd")
                    WarehouseID = 0;
                    }

                    $.ajax({
                        // url: "{{ URL::to('products/print/voucher') }}",
                        url: "{{ URL::to('products/print/voucher') }}?Warehouse_ID=" + WarehouseID,
                        type: 'get',
                        beforeSend: function(response) {
                            $('#print-receipt-modal-body').html(
                                '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                            );
                        },
                        success: function(response) {
                            if (response != null && response != 0) {
                                //  alert("dd")
                                $('#print-receipt-modal-body').html(
                                    `<object data="${base_url}/resources/upload/products/${response}" type="application/pdf" width="100%" height="800"></object>`
                                );
                            } else {
                                $('#print-receipt-modal-body').html(
                                    '<h2 style="color:red;text-align:center;">Voucher Not Exist</h2>'
                                );
                            }
                        }
                    });
                });

            $('#image').change(function(event) {
                alert("www")
                var filePath1 = URL.createObjectURL(event.target.files[0]);
                $(".show_image").html('<img src="' + filePath1 + '" width="100px" />');
            });

            $(document).on('click', '.edit_btn', function() {
                $('.copy_btn').removeClass('d-none');
                $(".show_image").html('');
                var data = $(this).attr('name');
                // alert(data)
                $('#idd').val(data.split('_')[0]);
                $('#product_name').val(data.split('_')[1]);
                $('#warehouse_id').val(data.split('_')[2]).select2();
                $('#category_id').val(data.split('_')[3]).select2();
                $('#uom').val(data.split('_')[4]);
                $('#pack_type').val(data.split('_')[5]).select2();
                $('#product_cost').val(data.split('_')[6]);
                $('#product_price').val(data.split('_')[7]);
                $('#tax').val(data.split('_')[8]);
                var image = data.split('_')[9];
                if(image)
                {
                    $(".show_image").html(
                    `<img src="{{ URL::asset('root/upload/products/${image}') }}" width="100px" />`
                );
                }
                
                $('#product_type').val(data.split('_')[10]).select2();
                $('#department_id').val(data.split('_')[11]).select2();
                $('#department_id1').val(data.split('_')[11]);
                $('#packing').val(data.split('_')[12]);
                $('#weight').val(data.split('_')[13]);
                $('#dye_pcs').val(data.split('_')[14]);
                
                //alert(data.split('_')[11]);
            });

            $('.reset_btn').click(function() {
                $('#idd').val(null);
                $('#warehouse_id').val(null).select2();
                $('#department_id').val(null).select2();
                $('#category_id').val(null).select2();
                $('#pack_type').val(null).select2();
            });


            // Move to next input field
            $('#product_name').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#warehouse_id").select2("open");
                }
            });
            $('#warehouse_id').change(function(event) {
                var warehouse = $(this).val();
                if (warehouse) {
                    $('#warehouse_id').select2().trigger("select2:close");
                    $("#department_id").select2("open");
                }
            });
            $('#department_id').change(function(event) {
                var warehouse = $(this).val();
                if (warehouse) {
                    $('#warehouse_id').select2().trigger("select2:close");
                    $("#category_id").select2("open");
                }
            });
            $('#category_id').change(function() {
                var category_id = $(this).val();
                if (category_id) {
                    $('#category_id').select2().trigger("select2:close")
                    $('#product_type').select2('open');
                }
            });
            $('#product_type').change(function() {
                var product_type = $(this).val();
                if (product_type) {
                    $('#product_type').select2().trigger("select2:close")
                    $('#uom').focus();
                }
            });

            $('#uom').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#pack_type").select2("open");
                }
            });
            $('#pack_type').change(function(event) {
                $('#pack_type').select2().trigger("select2:close");
                $('#product_cost').focus();
            });
            $('#product_cost').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#product_price").focus();
                }
            });
            $('#product_price').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#tax").focus();
                }
            });
            $('#tax').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#packing").focus();
                }
            });
            $('#packing').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#weight").focus();
                }
            });
            $('#weight').keypress(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // FormSubmit();
                    $("#dye_pcs").focus();
                }
            });
            // End Here
        });



        function FormSubmit(data) {
            
            document.getElementById('copydata').value = data;
            // alert(data);
            var product_name = $('#product_name').val();
            var department_id = $('#department_id').val();
            var warehouse_id = $('#warehouse_id').val();
            var category_id = $('#category_id').val();
            var product_type = $('#product_type').val();
            var uom = $('#uom').val();
            var pack_type = $('#pack_type').val();
            var product_cost = parseInt($('#product_cost').val());
            var product_price = parseInt($('#product_price').val());
            var tax = $('#tax').val();
            


            $('.product_name_err').text('');
            $('.warehouse_id_err').text('');
            $('.department_id_err').text('');
            $('.category_id_err').text('');
            $('.product_type_err').text('');
            $('.uom_err').text('');
            $('.pack_type_err').text('');
            $('.product_cost_err').text('');
            $('.product_price_err').text('');
            $('.tax_err').text('');


            if (!product_name || product_name == '') {
                $('.product_name_err').html('The Product Name field is required');
                $('#product_name').focus();
                return false;
            } else
            if (!warehouse_id) {
                $('.warehouse_id_err').html('The Godown field is required');
                $('#warehouse_id').select2('open');
                return false;
            } else
            if (!department_id) {
                $('.department_id_err').html('The Department field is required');
                $('#department_id').select2('open');
                return false;
            } else
            if (!category_id) {
                $('.category_id_err').html('The Product Category field is required');
                $('#category_id').select2('open');
                return false;
            } else
            if (!product_type) {
                $('.product_type_err').html('The Product Type field is required');
                $('#product_type').select2('open');
                return false;
            } else
            if (!pack_type) {
                $('.pack_type_err').html('The Pack Type field is required');
                $('#pack_type').select2('open');
                return false;
            } else
            if (!uom) {
                $('.uom_err').html('The Unit field is required');
                $('#uom').focus();
                return false;
            } else
            if (!product_cost || product_cost <= 0) {
                $('.product_cost_err').html('This Field is Required & should be greater than 0').css('color', 'red');
                $('#product_cost').focus();
                return false;
            } else
            if (!product_price || product_price <= 0) {
                $('.product_price_err').html('This Field is Required & should be greater than 0').css('color', 'red');
                $('#product_price').focus();
                return false;
            } else
            if (!tax) {
                $('.tax_err').html('');
                $('#tax').focus();
                return false;
            } else {
                $('.product_cost_err').html('');
                $('#product-form').submit();
                return true;
            }
        }


        function WarehouseFormSubmit() {
            var product_name = $('#warehouse_productID').val();

            if (!product_name || product_name == '') {
                $('.warehouseProductID').html('The Warehouse field is required');
                $('#warehouse_productID').focus();
                return false;
            } else {
                $('#warehouse-product-form').submit();
                return true;
            }
        }
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

    <!-- Products Remove | Swal Notification-->
    <script>
        // Swal Confirmation
        $(document).ready(function() {
            $(document).on('click', '.remove-product', function() {
                let id = $(this).attr('name');
                Swal.fire({
                    title: "Are You Sure?",
                    text: "Are you sure you want to delete this product?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    confirmButtonColor: '#28A745',
                    cancelButtonText: 'No, cancel!',
                    cancelButtonColor: '#DC3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = `{{ URL::to('products/destroy/${id}') }}`;
                    }
                });
            });
        });
    </script>
                
    <script>
    $(document).ready(function(){
        $('#warehouse_id').change(function(){
            var godownId=$(this).val();
            $.ajax({
               url: "{{ asset('products/godown/record') }}?godownId=" + godownId,
               type: 'get',
                dataType: 'json',
               success:function(response){
                if (response.length > 0) {
                    console.log(response.dept);
                    // var option;
                    var option = `<option value="" selected>Select Department</option>`;
                    $.each(response, function(i, v) {
                        option += `<option value="${v.id}">${v.name}</option>`;
                        
                    });
                    $('#department_id').html(option);
               }
               else {
                    var option = '<option value="" selected>No Record Found</option>';
                    $('#department_id').html(option);
                }
            }
            });
        });
    });
    </script>
    @include('include.toast-messages')
@stop
