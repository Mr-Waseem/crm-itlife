@extends('app')
@section('head')
    <title>Batch Stock Transfer</title>
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
            <h1>Batch Stock Transfer</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Batch Stock Transfer</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section class="content">
                    <div class="box">
                        <div class="box-header with-border">
                            <h6 class="box-subtitle"><i class="fa fa-plus-circle"></i>Batch Stock Transfer</h6>
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
                                    @include('errors.validation')
                                    {!! Form::open(['url' => 'slitting-stock-transfer', 'class' => 'form-horizontal', 'id' => 'opening-stock-form']) !!}
                                    {!! Form::hidden('update_voucher_id', null, ['id' => 'update_voucher_id']) !!}
                                    {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}
                                    <div class="row">
                                        <div class="col-lg-2 col-md-12 col-12">
                                            <button class="btn btn-primary submit-form" type="button">Save</button>
                                            <button class="btn btn-secondary reset-btn" type="reset">Reset</button>
                                        </div>
                                        <div class="col-lg-3 col-md-12 col-12">
                                            <div class="note note-danger">UnPosted By :</div>
                                        </div>
                                        <div class="col-lg-3 col-md-12 col-12">
                                            <div class="note note-warning">Posted By : {{ Auth::User()->name }}</div>
                                        </div>
                                        <div class="col-lg-3 col-md-12 col-12">
                                            <div class="note note-info">Updated By : <span class="d-none"
                                                    id="updated_by_name"> {{ Auth::User()->name }}</span></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-4 col-sm-12 mt-1">
                                            <label for="date"><i class="fa fa-caret-right"></i> Date</label>
                                            {!! Form::date('date', date('Y-m-d'), [
                                                'id' => 'date',
                                                'class' => 'form-control',
                                                'tabindex' => '0',
                                                'required' => 'required',
                                            ]) !!}
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-1 col-md-4 col-sm-12 mt-1">
                                            <label for="voucher_no"><i class="fa fa-caret-right"></i> Bill No#<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('voucher_no', $codes, [
                                                'id' => 'voucher_no',
                                                'class' => 'form-control',
                                                'onkeypress'=>"return isNumberKeyNoPoint(event)",
                                                'tabindex' => '1',
                                                'required' => 'required',
                                                'autofocus' => 'autofocus',
                                            ]) !!}
                                            @error('voucher_no')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12 mt-1">
                                            <label for="remarks"><i class="fa fa-caret-right"></i> Remarks</span></label>
                                            {!! Form::text('remarks', null, [
                                                'id' => 'remarks',
                                                'class' => 'form-control',
                                                'tabindex' => '2',
                                                
                                            ]) !!}
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 mt-1">
                                            <label for="from_warehouse_id"><i class="fa fa-caret-right"></i> From <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::text('from_warehouse_id1', $warehouseFrom->name, [
                                                'id' => 'from_warehouse_id1',
                                                'class' => 'form-control',
                                                'disabled' => 'disabled',
                                            ]) !!}
                                            {!! Form::hidden('from_warehouse_id', Auth::User()->warehouse_id, [
                                                'id' => 'from_warehouse_id',
                                                'class' => 'form-control',
                                            ]) !!}
                                            @error('from_warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 mt-1">
                                            <label for="to_warehouse_id"><i class="fa fa-caret-right"></i> TO<span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select('to_warehouse_id', $warehouseTo, null, [
                                                'id' => 'to_warehouse_id',
                                                'class' => 'form-control select2',
                                                'tabindex' => '3',
                                            ]) !!}
                                            @error('to_warehouse_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                      
                                    </div>
                                    <div class="row">
                                       
                                        
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="table-responsive mb-2">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                            <th>Sr#</th>
                                                            <th>Vr#</th>
                                                            <th>Date</th>
                                                            <th>Product</th>
                                                            <th>Unit</th>
                                                            <th>Thickness</th>
                                                            <th>Width</th>
                                                            <th>Length</th>
                                                            <th>Qty</th>
                                                            <th>Packing</th>
                                                            <th>Weight</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="GridTable" style="overflow-y:scroll;overflow-x:hidden;">
                                                    
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="8"><strong>Total</strong></td>
                                                                <td class="bg-success" id="TotalWeight">0</td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
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
                <form action="{{ URL::to('slitting-stock-transfer/delete-voucher') }}" method="post" id="delete_voucher_form">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Voucher?</p>
                        <input type="hidden" name="delete_voucher_no" id="delete_voucher_no" value="">
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
    <script src="{{ URL::asset('resources/resources/views/opening-stock/grid.js') }}"></script>
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

           
            $('#voucher_no').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#consume_product_id").select2('open');
                }
            });
            $('#consume_product_id').change(function(event) {
                var warehouse_id = $(this).val();
                if (warehouse_id) {
                    $('#consume_product_id').select2().trigger('select2:close');
                    $('#remarks').focus();
                }
            });

            $('#remarks').keydown(function(event) {
                // alert("ddd")
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    $("#product_id2").select2('open');
                }
            });
            $('#product_id2').change(function(event) {
                var product_id = $(this).val();
                //  alert(product_id.split('_')[3])
                if (product_id != null) {
                    $('#unit2').val(product_id.split('_')[3]);
                    $('#product_id2').select2().trigger('select2:close');
                    // alert('ddd')
                    $('#thickness2').focus();
                }
            });


            $('#thickness2').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = $(this).val();
                    if (qty <= 0) {
                        $(this).focus();
                        $('.thickness2_err').text('This field is required & Must be greater than zero');
                    } else {
                        $('#width2').focus();
                    }
                }
            });

            $('#width2').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = $(this).val();
                    if (qty <= 0) {
                        $(this).focus();
                        $('.width2_err').text('This field is required & Must be greater than zero');
                    } else {
                        $('#length2').focus();
                    }
                }
            });

            $('#length2').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = $(this).val();
                    if (qty <= 0) {
                        $(this).focus();
                        $('.length2_err').text('This field is required & Must be greater than zero');
                    } else {
                        $('#qty2').focus();
                    }
                }
            });

            $('#qty2').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = $(this).val();
                    if (qty <= 0) {
                        $(this).focus();
                        $('.qty2_err').text('This field is required & Must be greater than zero');
                    } else {
                        $('#packing2').focus();
                    }
                }
            });

            $('#packing2').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    var qty = $(this).val();
                    if (qty <= 0) {
                        $(this).focus();
                        $('.packing2_err').text('This field is required & Must be greater than zero');
                    } else {
                        $('#weight2').focus();
                    }
                }
            });

            $('#weight2').keyup(function(event) {
                var TotalWeight = parseInt(document.getElementById('TotalWeight').innerHTML);
                var CurrentWeight =  $(this).val();;
                var TotalBalance = $('#balance-for-formula').val();
                var Balance = TotalBalance - CurrentWeight - TotalWeight;
                $('#balance').val(Balance);
            });

            $('#weight2').keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == '13') {
                    // $('#weight').focus();
                    $("#product_id2").select2('open');
                        AddGridData();
                    
                }
            });
        });
    </script>
    <!-- End Focus on next field -->

    <!-- Append New Data on Table -->
    <script>
        $('#warehouse_id').change(function() {
            var WarehouseID = $('#warehouse_id').val();
            // $('#remarks').focus();
            // alert(WarehouseID)
            $.ajax({
                    url: "{{ URL::to('opening-pet-rolls/change/warehouse') }}?WarehouseID=" +
                    WarehouseID,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },

                    success: function(response) {
                            var option = `<option value="" selected>Select Product</option>`;
                                if (response.length > 0) {
                            $.each(response, function(i, v) {
                                option +=
                                    `<option value="${v.id}_${v.code}_${v.product_name}_${v.uom}_${v.product_cost}_${v.packing}">${v.code} - ${v.product_name}</option>`;

                            });
                            $('#product_id1').html(option);
                            
                       
                        }
                        else {
                        var option = '<option value="" selected>Product Not Found</option>';

                        $('#product_id1').html(option);
                    }
                }


          
            });
            
        });

        // function boxChecked(){
        //         alert("ddd")
        //         if ($('#remeber').is(':checked')) {
        //             alert("on");
        //         } else {
        //             alert("off");
        //         }
        //     }

        function AddGridData() {
            
            var product_id = $('#product_id1').val();
            // alert(product_id)
            var pro_id = document.getElementById('product_id2').value.split('_')[0];
            var pro_code = document.getElementById('product_id2').value.split('_')[1];
            var pro_name = document.getElementById('product_id2').value.split('_')[2];
            var pro_unit = document.getElementById('product_id2').value.split('_')[3];
            
            // var product_unit_id = document.getElementById('product_id1').value.split('_')[4];
            // var pro_cost = parseInt(document.getElementById('product_id1').value.split('_')[7]);
                
            var thickness = document.getElementById('thickness2').value;
            var width = document.getElementById('width2').value;
            var Length = document.getElementById('length2').value;
            var qty = document.getElementById('qty2').value;
            var packing = document.getElementById('packing2').value;
            var weight = document.getElementById('weight2').value;
            // alert("ddd")
            // alert(net_weight)
            // var TotalQty = parseInt(document.getElementById('TotalQty').innerHTML);
             var TotalWeight = parseInt(document.getElementById('TotalWeight').innerHTML);
            //  var TotalNetVal = parseInt(document.getElementById('TotalNet').innerHTML);


             var grandGrossWeight = parseFloat(TotalWeight) + parseFloat(weight);
            //  var grandNetWeight = parseFloat(TotalNetVal) + parseFloat(net_weight);
            // var grandTotalAmount = parseInt(TotalAmount) + parseInt(total);
            var totalRowCount = GridTable.rows.length;
            // alert(totalRowCount)
            var tableHtml = `<tr>`;
            tableHtml +=
                `<td>${totalRowCount+1}</td>`;
            // tableHtml +=
            //     `<td>${pro_code}<input type='hidden' name='code[]' id='code' value='${pro_code}' /></td>`;
            tableHtml += `<td>
                            ${pro_name}
                            <input type='hidden' name='product_id[]' id='product_id' value='${pro_id}' />
                        </td>`;
            tableHtml += `<td>${pro_unit}</td>`;
            tableHtml += `<td>${thickness.toLocaleString('en-US')}<input type='hidden' name='thickness[]' id='thickness' value='${thickness}' /></td>`;
            tableHtml +=`<td>${width.toLocaleString('en-US')}<input type='hidden' name='width[]' id='width' value='${width}' /></td>`;
            tableHtml += `<td>${Length.toLocaleString('en-US')}<input type='hidden' name='length[]' id='length' value='${Length}' /></td>`;
            tableHtml +=`<td>${qty.toLocaleString('en-US')}<input type='hidden' name='qty[]' id='qty' value='${qty}' /></td>`;
            tableHtml +=`<td>${packing.toLocaleString('en-US')}<input type='hidden' name='packing[]' id='packing' value='${packing}' /></td>`;
            tableHtml +=`<td>${weight.toLocaleString('en-US')}<input type='hidden' name='weight[]' id='weight' value='${weight}' /></td>`;
            tableHtml +=
                `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
            tableHtml += `</tr>`;
            $('#GridTable').append(tableHtml);
            $('#thickness2').val(null);
            $('#width2').val(null);
            $('#length2').val(null);
            $('#qty2').val(null);
            $('#packing2').val(null);
            $('#weight2').val(null);


            // $('#TotalQty').html(grandTotalQty.toLocaleString('en-US'));
            $('#TotalWeight').html(grandGrossWeight.toLocaleString('en-US'));
            // $('#TotalNet').html(grandNetWeight.toLocaleString('en-US'));

            $('#product_id2').select2('open');
            $('#product_id2').val(null);
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
                $('#opening-stock-form').submit();
            });
            // End FOrm Submit

            // Reset btn feature
            $('.reset-btn').click(function() {
                $('#warehouse_id').val(null).select2();
                $('#department_id').val(null).select2();
                $('#update_voucher_id').val(null);
                $('#updated_by_name').addClass('d-none');
                $('#GridTable').html('');
                $('#TotalQty').html(0);
                $('#TotalAmount').html(0);
            });
            // End Reset btn feature
        });
    </script>


    <!-- OnChange Qty -->
    <script>
        function changeQty(row) {
            var tableData = document.getElementById('GridTable');
            var sum = 0;
            for (var i = 0; i < tableData.rows.length; i++) {
                sum += parseInt(tableData.rows[i].cells[3].getElementsByTagName('input')[0].value);
            }
            document.getElementById('TotalQty').innerText = sum;
        }

        function DeleteRow(row) {
            var TotalAmount = parseInt(document.getElementById('TotalWeight').innerText);
            var NewAmount = parseInt($(row).find("td:eq('8')").find('input').val());
            document.getElementById('TotalWeight').innerText = (TotalAmount - NewAmount);

            var balance = document.getElementById('balance').value
            var total = parseFloat(balance) + parseFloat(NewAmount);
            document.getElementById('balance').value = total;

            // var TotalQty = parseInt(document.getElementById('TotalNet').innerText);
            // var NewQty = parseInt($(row).find("td:eq('7')").find('input').val());
            // document.getElementById('TotalNet').innerText = (TotalQty - NewQty);

            $(row).remove();
        }

        function PriceKeyUp(price) {
            var quantity = document.getElementById('qty1').value;
            if (quantity == '') {
                document.getElementById('total1').value = price;
            } else {
                var total = quantity * price;
                document.getElementById('total1').value = total;
            }
        }

        function QuantityKeyUp(quantity) {
            
            var price = document.getElementById('price1').value;
            var total = quantity * price;
            document.getElementById('total1').value = total;

            var packing = document.getElementById('packing1').value;
            var totalPack = (quantity / packing).toFixed(2);

            // alert(totalPack)
            document.getElementById('PackQty1').value = totalPack;
        }
    </script>
    <!-- End OnChange Qty -->


    <!-- Main Features -->
    <script>
        $(document).ready(function() {
            // Delete Record
            $('.delete_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('delete-record-modal'), {});
                myModal.toggle();
                var voucher_no = parseInt($('#voucher_no').val());
                $('#delete_voucher_no').val(voucher_no);
            });
             // Print Record
             $('.print_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                myModal.toggle();

                var voucher_no = parseInt($('#voucher_no').val());
                var base_url = $('#base_url').val();
                // alert(base_url)
                $.ajax({
                    url: "{{ URL::to('slitting-stock-transfer/print/voucher') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    beforeSend: function(response) {
                        $('#print-receipt-modal-body').html(
                            '<div class="spinner-border text-danger" role="status"><span class="sr-only">Loading...</span></div>'
                        );
                    },
                    success: function(response) {
                        if (response != null) {
                            $('#print-receipt-modal-body').html(
                                `<object data="${base_url}/resources/upload/stock-transfer/slitting/${response}" type="application/pdf" width="100%" height="800"></object>`
                            );
                        } else {
                            alert('null');
                        }
                    }
                });
            });
        });
    </script>
    <!-- End Main Features -->

    <!-- Load & Edit Record -->
    <script>
        $(document).ready(function() {

            $('#consume_product_id').change(function() {
            var product_id = parseInt($('#consume_product_id').val());
            // alert(product_id)
            //  alert("ddd")


                    $.ajax({
                        url: "{{ URL::to('slitting-production/load-role-data') }}",
                        type: 'get',
                        data: {
                        product_id: product_id,
                        },

                        success: function(response) {
                            if (response != null && response != 0) {
                               $('#color1').val(response.color);
                               $('#thickness1').val(response.thickness);
                               $('#net_weight1').val(response.total_qty);
                               $('#balance').val(response.total_qty);
                               $('#balance-for-formula').val(response.total_qty);
                               $('#gross_weight1').val(response.total_qty);
                               $('#width1').val(response.width);
                               $('#product_name1').val(response.product.product_name);
                            } else {
                                
                            }
                        }
                    });
                });


            $('.load-edit-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('slitting-production/load/record') }}?voucher_no=" + voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                
                    success: function(response) {
                        // alert("success")
                        if (response.production.production_details != '') {
                            // alert("success")
                            var tableHtml = '';
                            var sum = 0;
                            var TotalWeight = 0;

                            $.each(response.production.production_details, function(i, v) {
                                // alert("loop")
                                sum += 1;
                                TotalWeight += parseFloat(v.weight);
                                // TotalNet += parseFloat(v.net_weight);
                                // <input type='text' name='total[]' id='total' value='${v.amount}' />

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${sum}</td>`;
                               tableHtml +=
                                    `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                              
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='batchNo[]' id='batchNo' value='${v.thickness}' /></td>`;
                                tableHtml +=
                                    `<td>${v.thickness}<input type='hidden' name='thickness[]' id='thickness' value='${v.thickness}' /></td>`;
                                tableHtml +=
                                    `<td>${v.width}<input type='hidden' name='width[]' id='width' value='${v.width}' /></td>`;
                                   
                                tableHtml +=
                                    `<td>${v.length}<input type='hidden' name='length[]' id='length' value='${v.length}' /></td>`;
                                tableHtml +=
                                    `<td>${v.qty}<input type='hidden' name='qty[]' id='qty' value='${v.qty}' /></td>`;
                                tableHtml +=
                                 `<td>${v.packing}<input type='hidden' name='packing[]' id='packing' value='${v.packing}' /></td>`;
                                 tableHtml +=
                                 `<td>${v.weight}<input type='hidden' name='weight[]' id='weight' value='${v.weight}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalWeight').text(TotalWeight.toLocaleString('en-US'));
                            // $('#TotalNet').text(TotalNet.toLocaleString('en-US'));
                             $('#update_voucher_id').val(response.production.id);
                            $('#date').val(response.production.date);
                            $('#voucher_no').val(response.production.voucher_no);
                            $('#thickness1').val(response.production.consumed_production.thickness);
                            $('#width1').val(response.production.consumed_production.width);
                            $('#color1').val(response.production.consumed_production.color);
                            $('#gross_weight1').val(response.production.consumed_production.total_qty);
                            $('#net_weight1').val(response.production.consumed_production.total_qty);
                            $('#product_name1').val(response.production.consumed_production.product.product_name);
                            $('#consume_product_id').val(response.production.consumed_production.id)
                                .select2();
                            // $('#warehouse_id').val(response.data[0].godownstock.id);
                            // $('#product_name1').val(response.production.remarks);
                            $('#remarks').val(response.production.remarks);
                            $('#voucher_no').focus();

                        }
                         else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );


                        }
                    }
                });
            });


            // Load Next Record
            $('.load-next-record').click(function() {
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('slitting-production/load/next/record') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        // alert("success")
                        if (response.production.production_details != '') {
                            // alert("success")
                            var tableHtml = '';
                            var sum = 0;
                            var TotalWeight = 0;

                            $.each(response.production.production_details, function(i, v) {
                                // alert("loop")
                                sum += 1;
                                TotalWeight += parseFloat(v.weight);
                                // TotalNet += parseFloat(v.net_weight);
                                // <input type='text' name='total[]' id='total' value='${v.amount}' />

                                tableHtml += `<tr>`;
                                tableHtml +=
                                    `<td>${sum}</td>`;
                               tableHtml +=
                                    `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product.id}' /></td>`;
                              
                                tableHtml +=
                                    `<td>${v.product.uom}<input type='hidden' name='batchNo[]' id='batchNo' value='${v.thickness}' /></td>`;
                                tableHtml +=
                                    `<td>${v.thickness}<input type='hidden' name='thickness[]' id='thickness' value='${v.thickness}' /></td>`;
                                tableHtml +=
                                    `<td>${v.width}<input type='hidden' name='width[]' id='width' value='${v.width}' /></td>`;
                                   
                                tableHtml +=
                                    `<td>${v.length}<input type='hidden' name='length[]' id='length' value='${v.length}' /></td>`;
                                tableHtml +=
                                    `<td>${v.qty}<input type='hidden' name='qty[]' id='qty' value='${v.qty}' /></td>`;
                                tableHtml +=
                                 `<td>${v.packing}<input type='hidden' name='packing[]' id='packing' value='${v.packing}' /></td>`;
                                 tableHtml +=
                                 `<td>${v.weight}<input type='hidden' name='weight[]' id='weight' value='${v.weight}' /></td>`;
                                tableHtml +=
                                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });

                            $('#GridTable').html(tableHtml);
                            $('#TotalWeight').text(TotalWeight.toLocaleString('en-US'));
                            // $('#TotalNet').text(TotalNet.toLocaleString('en-US'));
                             $('#update_voucher_id').val(response.production.id);
                            $('#date').val(response.production.date);
                            $('#voucher_no').val(response.production.voucher_no);
                            $('#thickness1').val(response.production.consumed_production.thickness);
                            $('#width1').val(response.production.consumed_production.width);
                            $('#color1').val(response.production.consumed_production.color);
                            $('#gross_weight1').val(response.production.consumed_production.total_qty);
                            $('#net_weight1').val(response.production.consumed_production.total_qty);
                            $('#product_name1').val(response.production.consumed_production.product.product_name);
                            $('#consume_product_id').val(response.production.consumed_production.id)
                                .select2();
                            // $('#warehouse_id').val(response.data[0].godownstock.id);
                            // $('#product_name1').val(response.production.remarks);
                            $('#remarks').val(response.production.remarks);
                            $('#voucher_no').focus();

                        } 
                        else {
                            $('#show_err').html(
                                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                            );


                        }
                    }
                });
            });
            // End Here of Load Next Record
        
            // $('.load-previous-record').click(function() {

            // });

            // Load Previous Record
            $('.load-previous-record').click(function() {
                $('#productGroup').hide();
                var voucher_no = parseInt($('#voucher_no').val());
                $.ajax({
                    url: "{{ URL::to('slitting-stock-transfer/load/previous/record') }}?voucher_no=" +
                        voucher_no,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response.data != '') {
                            
                            // alert("success")
                            var tableHtml = '';
                            var sum = 0;
                            var TotalWeight = 0;
    
                            $.each(response.data, function(i, v) {
                                // alert("loop")
                                sum += 1;
                                // TotalWeight += parseFloat(v.weight);
                                // TotalNet += parseFloat(v.net_weight);
                                // <input type='text' name='total[]' id='total' value='${v.amount}' />
    
                                tableHtml += `<tr>`;
                                tableHtml +=
                                    // `<td><input type='checkbox' name="status[]"  id="${v.id}" ${v.id}.is(":checked") ? "checked" : "";/><label for="${v.id}"></label></td>`;
                                    `<td></td>`;
                            tableHtml +=
                                    `<td>${v.godown.voucher_no}<input type='hidden' name="slitting_detail_id[]" value="${v.id}" id="${v.id}"/></td>`;
                            
                                tableHtml +=
                                    `<td>${v.date}</td>`;
                                tableHtml +=
                                    `<td>${v.product.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product_id}' /></td>`;
                                tableHtml +=
                                    `<td>${v.product.uom}</td>`;
                                
                                tableHtml +=
                                    `<td>${v.thickness}</td>`;
                                tableHtml +=
                                    `<td>${v.width}</td>`;
                                tableHtml +=
                                `<td>${v.length}</td>`;
                                tableHtml +=
                                `<td>${v.qty}</td>`;
                                tableHtml +=
                                `<td>${v.packing}</td>`;
                                tableHtml +=
                                `<td>${v.weight}<input type="hidden" id="weight" name="weight[]" value="${v.weight}"></td>`;
                                // tableHtml +=
                                //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                                tableHtml += `</tr>`;
                            });
    
                            $('#GridTable').html(tableHtml);
                        // $('#TotalWeight').text(TotalWeight.toLocaleString('en-US'));
                        // // $('#TotalNet').text(TotalNet.toLocaleString('en-US'));
                         $('#update_voucher_id').val(response.godowndata.id);
                        $('#date').val(response.godowndata.date);
                        $('#voucher_no').val(response.godowndata.voucher_no);
                        $('#remarks').val(response.godowndata.remarks);
                        // $('#thickness1').val(response.production.consumed_production.thickness);
                        // $('#width1').val(response.production.consumed_production.width);
                        // $('#color1').val(response.production.consumed_production.color);
                        // $('#gross_weight1').val(response.production.consumed_production.total_qty);
                        // $('#net_weight1').val(response.production.consumed_production.total_qty);
                        // $('#product_name1').val(response.production.consumed_production.product.product_name);
                        $('#to_warehouse_id').val(response.godowndata.to_warehouse_id)
                            .select2();
                        // // $('#warehouse_id').val(response.data[0].godownstock.id);
                        // // $('#product_name1').val(response.production.remarks);
                        // $('#remarks').val(response.production.remarks);
                        // $('#voucher_no').focus();
    
                 } else {
                $('#show_err').html(
                    '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
                );
            }
                        
                    }
                });
            });

            
            $('#product_group_id').change(function() {
                // var table = document.getElementById('GridTable');
                //  table.rows.remove();
                var groupId = parseInt($('#product_group_id').val());
                $.ajax({
                    url: "{{ URL::to('slitting-stock-transfer/load/products') }}?groupId=" +
                    groupId,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function(response) {
                        $('#show_err').html(
                            '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Please Wait!!!</div>'
                        );
                    },
                    success: function(response) {
                        if (response != '') {
                            
                        // alert("success")
                        var tableHtml = '';
                        var sum = 0;
                        var TotalWeight = 0;

                        $.each(response, function(i, v) {
                            // alert("loop")
                            sum += 1;
                            // TotalWeight += parseFloat(v.weight);
                            // TotalNet += parseFloat(v.net_weight);
                            // <input type='text' name='total[]' id='total' value='${v.amount}' />

                            tableHtml += `<tr>`;
                            tableHtml +=
                                `<td><input type='checkbox' class='report_type' name="slitting_detail_id[]" value="${v.id}" id="${v.id}"/><label for="${v.id}"></label></td>`;
                        tableHtml +=
                                `<td>${v.voucher_no}</td>`;
                        
                            tableHtml +=
                                `<td>${v.date}</td>`;
                            tableHtml +=
                                `<td>${v.product_name}<input type='hidden' name='product_id[]' id='product_id' value='${v.product_id}' /></td>`;
                            tableHtml +=
                                `<td>${v.uom}</td>`;
                            
                            tableHtml +=
                                `<td>${v.thickness}</td>`;
                            tableHtml +=
                                `<td>${v.width}</td>`;
                            tableHtml +=
                            `<td>${v.length}</td>`;
                            tableHtml +=
                            `<td>${v.qty}</td>`;
                            tableHtml +=
                            `<td>${v.packing}</td>`;
                            tableHtml +=
                            `<td>${v.weight}<input type="hidden" id="weight" name="weight[]" value="${v.weight}"></td>`;
                            // tableHtml +=
                            //     `<td><button type="button" class="btn btn-danger btn-sm" onclick="DeleteRow($(this).closest('tr'));"><i class="fa fa-trash"></i></button></td>`;
                            tableHtml += `</tr>`;
                        });

                        $('#GridTable').html(tableHtml);
                    // $('#TotalWeight').text(TotalWeight.toLocaleString('en-US'));
                    // // $('#TotalNet').text(TotalNet.toLocaleString('en-US'));
                    //  $('#update_voucher_id').val(response.production.id);
                    // $('#date').val(response.production.date);
                    // $('#voucher_no').val(response.production.voucher_no);
                    // $('#thickness1').val(response.production.consumed_production.thickness);
                    // $('#width1').val(response.production.consumed_production.width);
                    // $('#color1').val(response.production.consumed_production.color);
                    // $('#gross_weight1').val(response.production.consumed_production.total_qty);
                    // $('#net_weight1').val(response.production.consumed_production.total_qty);
                    // $('#product_name1').val(response.production.consumed_production.product.product_name);
                    // $('#consume_product_id').val(response.production.consumed_production.id)
                    //     .select2();
                    // // $('#warehouse_id').val(response.data[0].godownstock.id);
                    // // $('#product_name1').val(response.production.remarks);
                    // $('#remarks').val(response.production.remarks);
                    // $('#voucher_no').focus();

             } else {
            $('#show_err').html(
                '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> Voucher No Not Exist!</div>'
            );
        }
                      
                    }
                });
            });

            

            
            // End Here of Load Previous Record
        });
    </script>
    <!-- End Load & Edit Record -->

    @include('include.toast-messages')
@stop
