﻿<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="{{ URL::asset('bootstrap4/bootstrap.min.css') }}" />
    <title>REQUEST GENERATE VOUCHER</title>
    <style>
        body {
            background: #f8f8f8
        }
		.card{
			box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
		}
		.request_generate_table_row{
			border: 2px solid black;
		}
        .table thead th {
            border-bottom: 2px solid black;
            padding: .15rem;
			text-align: center;
        }
        .table tbody td {
			text-align: center;
            padding: .15rem;
        }
		.bottom-line{
			border-bottom: 2px solid black;
			width: 150px;
		}
		.mt-5{
			margin-top: 50px;
		}
    </style>
</head>

<body>
    <div class="container">
        <div class="row flex-lg-nowrap">
            <div class="col">
                <div class="row">
                    <div class="col mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="e-profile">
                                    @include('include.header')
                                    <h3 style="text-align: center;" class="text-uppercase text-decoration-underline mt-2">
                                        <u>REQUEST GENERATE VOUCHER</u>
                                    </h3>
									<div class="row mt-2">
										<div class="col-lg-12 col-md-12 col-sm-12">
											<table>
												<tbody>
													<tr>
														<td><b>Date</b></td>
														<td>: {{ date('d/m/Y',strtotime($RequestGenerate->date)) }}</td>
													</tr>
													<tr>
														<td><b>Bill No</b></td>
														<td>: {{ $RequestGenerate->bill_no }}</td>
													</tr>
													<tr>
														<td><b>Supplier</b></td>
														<td>: 
															@if ($RequestGenerate->supplier_id==0)
																No Supplier Recommended...
															@else
															{{ $RequestGenerate->supplier->party_name }}
															@endif
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									<h4 class="mt-3"><i>Details...</i></h4>
                                    <table class="table table-bordered">
										<thead>
											<tr class="request_generate_table_row">
												<th>Sr.</th>
												<th>Product</th>
												<th>UNIT</th>
												<th>Qty</th>
												<th>Comment</th>
											</tr>
										</thead>
                                        <tbody>
											@php $totalQty = 0; @endphp
											@foreach ($RequestGenerate->request_generate_details as $key=>$value)
												<tr class="request_generate_table_row">
													<td><b>{{ $key+1 }}</b></td>
													<td>{{ $value->product->product_name }}</td>
													<td>{{ $value->unit }}</td>
													<td>{{ number_format($value->qty,2) }}</td>
													<td>{{ $value->comments }}</td>
												</tr>
												@php $totalQty += $value->qty; @endphp
											@endforeach
                                        </tbody>
										<tfoot>
											<tr class="request_generate_table_row">
												<td colspan="3"><b>Total</b></td>
												<td>{{ number_format($totalQty,2) }}</td>
											</tr>
										</tfoot>
                                    </table>
									<div class="row mt-5">
										<div class="col-lg-3 col-md-3 col-sm-12">
											<h6>PREPARED BY</h6>
											<p class="bottom-line">&nbsp;</p>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12">
											<h6>CHECKED BY</h6>
											<p class="bottom-line">&nbsp;</p>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12">
											<h6>APPROVED BY</h6>
											<p class="bottom-line">&nbsp;</p>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12">
											<h6>RECEIVED BY</h6>
											<p class="bottom-line">&nbsp;</p>
										</div>
									</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
