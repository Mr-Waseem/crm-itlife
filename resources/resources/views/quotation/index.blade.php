@extends('app')
@section('head')
    <title>Add Quotation</title>
    <link href="{{ URL::asset('dashboard/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('dashboard/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .quotation-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 18px 20px 24px;
        }
        .quotation-card h5.section-title {
            font-weight: 600;
            margin: 18px 0 10px;
            color: #111;
        }
        .btn-add-red {
            background: #e53935;
            border-color: #e53935;
            color: #fff;
            min-width: 72px;
        }
        .btn-add-red:hover { background: #c62828; color: #fff; }
        #productGridTable th, #milestoneGridTable th {
            background: #f8fafc;
            font-size: 13px;
        }
    </style>
@stop
@section('content')
    <div class="icon-bar">
        <a href="javascript:void(0);" class="load-previous-record"><i class="fa fa-angle-left"></i></a>
        <a href="javascript:void(0);" class="load-edit-record"><i class="fa fa-repeat"></i></a>
        <a href="javascript:void(0);" class="load-next-record"><i class="fa fa-angle-right"></i></a>
        <a href="javascript:void(0);" class="delete_record_btn"><i class="fa fa-trash-o"></i></a>
        <a href="javascript:void(0);" class="print_record_btn"><i class="fa fa-print"></i></a>
    </div>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Add Quotation</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Quotation</a></li>
            </ol>
        </section>
        <div class="row">
            <div class="col-lg-12">
                <section class="content">
                    <div class="box">
                        <div class="box-body">
                            @if (Session::has('failure_message'))
                                <div class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ Session::get('failure_message') }}
                                </div>
                            @endif
                            <div id="show_err"></div>

                            <div class="quotation-card">
                                {!! Form::open(['url' => 'quotation', 'class' => 'form-horizontal', 'id' => 'quotation-form']) !!}
                                {!! Form::hidden('update_voucher_id', null, ['id' => 'update_voucher_id']) !!}
                                {!! Form::hidden('base_url', Request::root(), ['id' => 'base_url']) !!}

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 mt-2">
                                        <label>Quotation No</label>
                                        {!! Form::text('voucher_no', $codes, ['id' => 'voucher_no', 'class' => 'form-control', 'onkeypress' => 'return isNumberKeyNoPoint(event)']) !!}
                                    </div>
                                    <div class="col-lg-6 col-md-6 mt-2">
                                        <label>Date</label>
                                        {!! Form::date('date', date('Y-m-d'), ['id' => 'date', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-lg-6 col-md-6 mt-2">
                                        <label>Valid To</label>
                                        {!! Form::date('valid_to', date('Y-m-d'), ['id' => 'valid_to', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-lg-6 col-md-6 mt-2">
                                        <label>Warehouse</label>
                                        {!! Form::select('warehouse_id', $warehouse, null, ['id' => 'warehouse_id', 'class' => 'form-control select2']) !!}
                                    </div>
                                    <div class="col-lg-6 col-md-6 mt-2">
                                        <label>Party / Company</label>
                                        {!! Form::select('party_id', $customers, null, ['id' => 'party_id', 'class' => 'form-control select2']) !!}
                                        <span class="text-danger party_name_err"></span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 mt-2">
                                        <label>Atten</label>
                                        {!! Form::text('atten', null, ['id' => 'atten', 'class' => 'form-control', 'placeholder' => 'Attention / Contact person']) !!}
                                    </div>
                                    <div class="col-lg-6 col-md-6 mt-2">
                                        <label>Address</label>
                                        {!! Form::text('party_address', null, ['id' => 'party_address', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                                    </div>
                                    <div class="col-lg-6 col-md-6 mt-2">
                                        <label>City</label>
                                        {!! Form::text('party_city', null, ['id' => 'party_city', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                                    </div>
                                    <div class="col-lg-12 col-md-12 mt-2">
                                        <label>Subject</label>
                                        {!! Form::text('subject', null, ['id' => 'subject', 'class' => 'form-control', 'placeholder' => 'Subject']) !!}
                                    </div>
                                </div>

                                <h5 class="section-title">Application Features</h5>
                                <div class="row">
                                    <div class="col-lg-12">
                                        {!! Form::textarea('features', null, [
                                            'id' => 'features',
                                            'class' => 'form-control',
                                            'rows' => 5,
                                            'placeholder' => "Enter one feature per line\nExample:\nUser login & roles\nReports dashboard\nMobile responsive UI",
                                        ]) !!}
                                        <small class="text-muted">Each line will print as a bullet point.</small>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-lg-6 col-md-6 mt-2">
                                        <label>Deadline (Working Days)</label>
                                        {!! Form::text('deadline_days', null, ['id' => 'deadline_days', 'class' => 'form-control', 'onkeypress' => 'return isNumberKeyNoPoint(event)']) !!}
                                    </div>
                                    <div class="col-lg-6 col-md-6 mt-2">
                                        <label>Warranty (Months)</label>
                                        {!! Form::text('warranty_months', null, ['id' => 'warranty_months', 'class' => 'form-control', 'onkeypress' => 'return isNumberKeyNoPoint(event)']) !!}
                                    </div>
                                </div>

                                <h5 class="section-title">Products</h5>
                                <div class="row align-items-end">
                                    <div class="col-lg-4 col-md-4 mt-2">
                                        <label>Product Name</label>
                                        {!! Form::select('product_pick', $products, null, ['id' => 'product_pick', 'class' => 'form-control select2']) !!}
                                        {!! Form::text('product_name1', null, ['id' => 'product_name1', 'class' => 'form-control mt-1', 'placeholder' => 'Or type product name']) !!}
                                        {!! Form::hidden('product_id1', null, ['id' => 'product_id1']) !!}
                                    </div>
                                    <div class="col-lg-4 col-md-4 mt-2">
                                        <label>Description</label>
                                        {!! Form::text('description1', null, ['id' => 'description1', 'class' => 'form-control', 'placeholder' => 'Description']) !!}
                                    </div>
                                    <div class="col-lg-2 col-md-2 mt-2">
                                        <label>Date</label>
                                        {!! Form::date('line_date1', date('Y-m-d'), ['id' => 'line_date1', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-lg-2 col-md-2 mt-2">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-add-red btn-block" onclick="AddProductRow()">Add</button>
                                    </div>
                                </div>
                                <div class="table-responsive mt-2">
                                    <table class="table table-bordered" id="productGridTable">
                                        <thead>
                                            <tr>
                                                <th style="width:30%;">Product Name</th>
                                                <th>Description</th>
                                                <th style="width:140px;">Date</th>
                                                <th style="width:60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="ProductGrid"></tbody>
                                    </table>
                                </div>

                                <h5 class="section-title">Module Milestone</h5>
                                <div class="row align-items-end">
                                    <div class="col-lg-4 col-md-4 mt-2">
                                        <label>Module Milestone</label>
                                        {!! Form::text('module_name1', null, ['id' => 'module_name1', 'class' => 'form-control', 'placeholder' => 'Module Milestone']) !!}
                                    </div>
                                    <div class="col-lg-3 col-md-3 mt-2">
                                        <label>Payment</label>
                                        {!! Form::select('payment_percent1', $paymentOptions, '20', ['id' => 'payment_percent1', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-lg-3 col-md-3 mt-2">
                                        <label>Timeframe</label>
                                        {!! Form::select('timeframe_days1', $timeframeOptions, '0', ['id' => 'timeframe_days1', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-lg-2 col-md-2 mt-2">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-add-red btn-block" onclick="AddMilestoneRow()">Add</button>
                                    </div>
                                </div>
                                <div class="table-responsive mt-2">
                                    <table class="table table-bordered" id="milestoneGridTable">
                                        <thead>
                                            <tr>
                                                <th>Module</th>
                                                <th style="width:120px;">Payment %</th>
                                                <th style="width:160px;">Timeframe</th>
                                                <th style="width:60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="MilestoneGrid"></tbody>
                                    </table>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <button class="btn btn-primary submit-form" type="button">Submit</button>
                                        <button class="btn btn-secondary reset-btn" type="button">Reset</button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-record-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-trash text-danger"></i> Delete</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="{{ URL::to('quotation/delete-voucher') }}" method="post" id="delete_voucher_form">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Quotation?</p>
                        <input type="hidden" name="delete_voucher_no" id="delete_voucher_no" value="">
                        <input type="hidden" name="delete_warehouse_id" id="delete_warehouse_id" value="">
                    </div>
                    <div class="modal-footer text-right">
                        <button type="button" class="btn btn-danger btn-sm" onclick="document.getElementById('delete_voucher_form').submit();">Delete</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="print-record-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-print"></i> Print Quotation</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body" id="print-receipt-modal-body"></div>
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ URL::asset('dashboard/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        var partiesData = @json($partiesData);
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2").select2({ width: "100%" });

        function isNumberKeyNoPoint(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
            return true;
        }

        function escapeHtml(str) {
            if (str == null) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        function AddProductRow(productId, productName, description, lineDate) {
            productId = productId || $('#product_id1').val() || '';
            productName = productName || $('#product_name1').val() || '';
            description = description || $('#description1').val() || '';
            lineDate = lineDate || $('#line_date1').val() || '';

            if (!productName.trim()) {
                alert('Enter / select Product Name');
                return;
            }

            var html = `<tr>
                <td>
                    <input type="hidden" name="product_id[]" value="${escapeHtml(productId)}">
                    <input type="text" name="product_name[]" class="form-control" value="${escapeHtml(productName)}">
                </td>
                <td><input type="text" name="description[]" class="form-control" value="${escapeHtml(description)}"></td>
                <td><input type="date" name="line_date[]" class="form-control" value="${escapeHtml(lineDate)}"></td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove();"><i class="fa fa-trash"></i></button></td>
            </tr>`;
            $('#ProductGrid').append(html);

            $('#product_pick').val('').trigger('change');
            $('#product_id1').val('');
            $('#product_name1').val('');
            $('#description1').val('');
            $('#line_date1').val('{{ date("Y-m-d") }}');
        }

        function AddMilestoneRow(moduleName, payment, timeframe) {
            moduleName = moduleName || $('#module_name1').val() || '';
            payment = payment != null ? payment : ($('#payment_percent1').val() || '0');
            timeframe = timeframe != null ? timeframe : ($('#timeframe_days1').val() || '0');

            if (!moduleName.trim()) {
                alert('Enter Module Milestone');
                return;
            }

            var html = `<tr>
                <td><input type="text" name="module_name[]" class="form-control" value="${escapeHtml(moduleName)}"></td>
                <td><input type="number" name="payment_percent[]" class="form-control" value="${escapeHtml(payment)}" min="0" max="100"></td>
                <td><input type="number" name="timeframe_days[]" class="form-control" value="${escapeHtml(timeframe)}" min="0"></td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove();"><i class="fa fa-trash"></i></button></td>
            </tr>`;
            $('#MilestoneGrid').append(html);
            $('#module_name1').val('');
            $('#payment_percent1').val('20');
            $('#timeframe_days1').val('0');
        }

        function fillFromRecord(d) {
            $('#update_voucher_id').val(d.id);
            $('#voucher_no').val(d.voucher_no);
            $('#date').val(d.date);
            $('#valid_to').val(d.valid_to);
            $('#warehouse_id').val(d.warehouse_id).trigger('change.select2');
            $('#party_id').val(d.party_id).trigger('change.select2');
            $('#atten').val(d.atten || '');
            $('#subject').val(d.subject || '');
            $('#features').val(d.features || '');
            $('#deadline_days').val(d.deadline_days || '');
            $('#warranty_months').val(d.warranty_months || '');
            if (d.party) {
                $('#party_address').val(d.party.address || '');
                $('#party_city').val(d.party.city || '');
            }

            $('#ProductGrid').html('');
            $.each(d.quotation_details || [], function(i, v) {
                AddProductRow(v.product_id || '', v.product_name || '', v.description || '', v.line_date || '');
            });

            $('#MilestoneGrid').html('');
            $.each(d.milestones || [], function(i, v) {
                AddMilestoneRow(v.module_name || '', v.payment_percent || 0, v.timeframe_days || 0);
            });
        }

        function loadQuotationRecord(url) {
            var voucher_no = parseInt($('#voucher_no').val());
            var warehouseID = parseInt($('#warehouse_id').val());
            if (!warehouseID) {
                alert('Select Warehouse first');
                return;
            }
            $.ajax({
                url: url,
                type: 'get',
                data: { voucher_no: voucher_no, warehouseID: warehouseID },
                dataType: 'json',
                beforeSend: function() {
                    $('#show_err').html('<div class="alert alert-info">Please Wait!!!</div>');
                },
                success: function(response) {
                    $('#show_err').html('');
                    if (response.data != '' && response.data != null) {
                        fillFromRecord(response.data);
                    } else {
                        $('#show_err').html('<div class="alert alert-danger">Record not found</div>');
                    }
                }
            });
        }

        $(document).ready(function() {
            $('#product_pick').change(function() {
                var raw = $(this).val();
                if (!raw) return;
                var parts = raw.split('_');
                $('#product_id1').val(parts[0] || '');
                $('#product_name1').val(parts[2] || '');
            });

            $('#warehouse_id').change(function() {
                var warehouseID = $(this).val();
                if (!warehouseID || $('#update_voucher_id').val()) return;
                $.get("{{ URL::to('quotation/warehouse/voucherno') }}", { warehouseID: warehouseID }, function(response) {
                    $('#voucher_no').val(response.codes);
                });
            });

            $('#party_id').change(function() {
                var partyId = $(this).val();
                if (!partyId) {
                    $('#party_address').val('');
                    $('#party_city').val('');
                    return;
                }
                if (partiesData[partyId]) {
                    $('#party_address').val(partiesData[partyId].address || '');
                    $('#party_city').val(partiesData[partyId].city || '');
                    return;
                }
                $.get("{{ URL::to('quotation/party/info') }}", { party_id: partyId }, function(res) {
                    if (res.data) {
                        $('#party_address').val(res.data.address || '');
                        $('#party_city').val(res.data.city || '');
                    }
                });
            });

            $('.submit-form').click(function() {
                if (!$('#warehouse_id').val()) { alert('Select Warehouse'); return; }
                if (!$('#party_id').val()) { $('.party_name_err').text('Party is required'); return; }
                if ($('#ProductGrid tr').length < 1) { alert('Please add at least 1 product'); return; }
                $('#quotation-form').submit();
            });

            $('.reset-btn').click(function() {
                $('#update_voucher_id').val('');
                $('#ProductGrid').html('');
                $('#MilestoneGrid').html('');
                $('#quotation-form')[0].reset();
                $('.select2').val('').trigger('change');
                $('#party_address').val('');
                $('#party_city').val('');
            });

            $('.delete_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('delete-record-modal'), {});
                myModal.toggle();
                $('#delete_voucher_no').val($('#voucher_no').val());
                $('#delete_warehouse_id').val($('#warehouse_id').val());
            });

            $('.print_record_btn').click(function() {
                var myModal = new bootstrap.Modal(document.getElementById('print-record-modal'), {});
                myModal.toggle();
                var voucher_no = parseInt($('#voucher_no').val());
                var warehouseID = parseInt($('#warehouse_id').val());
                var base_url = $('#base_url').val();
                $.ajax({
                    url: "{{ URL::to('quotation/print/voucher') }}",
                    type: 'get',
                    data: { voucher_no: voucher_no, warehouseID: warehouseID },
                    beforeSend: function() {
                        $('#print-receipt-modal-body').html('<div class="spinner-border text-danger" role="status"></div>');
                    },
                    success: function(response) {
                        if (response != null && response != 0 && response != false) {
                            $('#print-receipt-modal-body').html(
                                `<object data="${base_url}/resources/upload/quotation/${response}" type="application/pdf" width="100%" height="800"></object>`
                            );
                        } else {
                            $('#print-receipt-modal-body').html('<h2 style="color:red;text-align:center;">Quotation Not Exist</h2>');
                        }
                    }
                });
            });

            $('.load-edit-record').click(function() {
                loadQuotationRecord("{{ URL::to('quotation/load/record') }}");
            });
            $('.load-previous-record').click(function() {
                loadQuotationRecord("{{ URL::to('quotation/load/previous/record') }}");
            });
            $('.load-next-record').click(function() {
                loadQuotationRecord("{{ URL::to('quotation/load/next/record') }}");
            });
        });
    </script>
    @include('include.toast-messages')
@stop
