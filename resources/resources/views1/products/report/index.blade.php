@extends('app')
@section('head')
    <title>Products Report</title>
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
                Products Report
            </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Products Report</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-th-list"></i>PRODUCTS REPORT</h6>
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
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
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
                                        <button type="button" class="btn btn-info print_record_btn" tabindex="14">Submit</button>
                                        
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


            $('.print_record_btn').click(function() {
                    var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                    myModal.toggle();

                    var base_url = $('#base_url').val();
                    // alert(base_url)
                    
                    var WarehouseID = $('#warehouse_productID').val();
                    if (!WarehouseID || WarehouseID == '') {
                    // alert("ddd")
                    WarehouseID = 0;
                    }

                    $.ajax({
                        // url: "{{ URL::to('products/print/voucher') }}",
                        url: "{{ URL::to('products-report/print/voucher') }}?Warehouse_ID=" + WarehouseID,
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

        });



        function FormSubmit() {
            alert("ddsds")
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
                    var option;
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
