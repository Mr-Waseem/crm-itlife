<!DOCTYPE html>
<html>

<head>
    <title>INWARD GATE PASS</title>
    <style>
        #designed {
            border-collapse: collapse;
        }

        #designed thead tr th,
        #designed tbody tr td {
            border-right: 1px solid black;
            text-align: center;
            font-size: 14px;
        }

        #designed thead tr th {
            border-bottom: 1px solid black;
            font-size: 14px;
        }

        #designed tfoot tr th {
            border-top: 1px solid black;
            border-right: 1px solid black;
            font-size: 14px;
        }
    </style>
</head>

<body>
    @if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1><center>{{ SettingsFacade::data()->title }}</center></h1>
    @endif
    <h3>
        <center>INWARD GATE PASS</center>
    </h3>
    <hr /><br />
    <div style="clear:both">
         <div style="float: left;"><b>Voucher.No: </b>{{$inwardgatepassDetails[0]->inward->bill_no }}</div>
        <div style="float: right;"><b>Voucher
                Date: </b>{{ date('d/m/Y', strtotime($inwardgatepassDetails[0]->inward->date)) }}</div>
    </div>
    <br />
    <div style="clear:both">
        <div style="float:left;"><b>Driver Name: </b> @if($inwardgatepassDetails[0]->inward){{ $inwardgatepassDetails[0]->inward->driver_name}} @else{{"No Driver"}}@endif</div>
        <div style="float:right;"><b>Vehicle No:</b> @if($inwardgatepassDetails[0]->inward){{ $inwardgatepassDetails[0]->inward->vehicle_no }}@else{{"No Vehicle#"}}@endif</div></div>

    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Driver Number: </b>@if($inwardgatepassDetails[0]->inward) {{ $inwardgatepassDetails[0]->inward->driver_phoneno}}@else{{"No Driver Phone"}}@endif</div>
        <div style="float:right;"><b>Transport Company:</b> @if($inwardgatepassDetails[0]->inward){{ $inwardgatepassDetails[0]->inward->transport_company }}@else{{"No Transport Company"}}@endif</div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Builty Number: </b> @if($inwardgatepassDetails[0]->inward){{ $inwardgatepassDetails[0]->inward->builty_no}}@else{{"No Builty#"}}@endif</div>
        <div style="float:right;"><b> Supplier:</b> @if($inwardgatepassDetails[0]->inward->supplier){{ $inwardgatepassDetails[0]->inward->supplier->party_name}}@else{{"No Supplier"}}@endif</div>
    </div>
    <div style="clear:both">

        <div style="float:left;"><b>Purchaser:</b> @if($inwardgatepassDetails[0]->inward->purchaser){{ $inwardgatepassDetails[0]->inward->purchaser->party_name}}@else{{"No Purchaser"}}@endif</div>
        <!-- <div style="float:right;"><b>Request Generate No:</b>@if($inwardgatepassDetails[0]->inward->request_generate) {{ $inwardgatepassDetails[0]->inward->request_generate->bill_no}}@else{{"No Request Generate Bill#"}}@endif</div> -->
        <div style="float:right;"><b>Warehouse:</b> @if($inwardgatepassDetails[0]->inward->warehouse){{ $inwardgatepassDetails[0]->inward->warehouse->name}}@else{{"No Warehouse"}}@endif</div>
    </div>
    <!-- <div style="clear:both">

        <div style="float:left;"><b>Warehouse:</b> @if($inwardgatepassDetails[0]->inward->warehouse){{ $inwardgatepassDetails[0]->inward->warehouse->name}}@else{{"No Warehouse"}}@endif</div>
    </div> -->
    <br /><br />
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Request#</th>
                <th>Code</th>
                <th>Product Name</th>
                <th>Unit</th>
                <th>DemandQty</th>
                <th>Qty</th>
                <th>Balance</th>
                <th>Comments</th>
            </tr>
        </thead>
        <tbody>
            @php $total=0; $qty=0; $totalprice=0; $totalBalance=0;  @endphp
            @foreach ($inwardgatepassDetails as $value)
                <tr>
                    <td>{{$value->request_no}}</td>
                    <td>@if($value->product){{ $value->product->code }} @else{{"No Code"}}@endif</td>
                    <td>@if($value->product){{ $value->product->product_name }}@else{{"No Product"}}@endif</td>
                    <td>@if($value->product){{ $value->product->uom }}@else{{"No Unit"}}@endif</td>
                    <td>{{ number_format($value->qtyshow,2)}}</td> 
                    <td>{{ number_format($value->qty,2)}}</td> 
                    
                    @php $total =0; $Balance=0; @endphp
                    @foreach($value->product->gatepass_details as $data)
                    @php 
                    $total = $total + $data->qty;
                    $Balance = $value->qtyshow-$total;
                    
                    @endphp
                    @endforeach
                    <!-- $stock = $value->demandPCS-$total; -->
                    <td>{{ number_format($Balance)}}</td>
                    <td>{{ $value->comments}}</td> 
                </tr>
                @php
                 $qty+=$value->qty; 
                 $totalBalance+=$Balance; 
                 
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="5">Total</th>
                <th>{{ number_format($qty,2)}}</th>
                <th>{{ number_format($totalBalance)}}</th>
                <!-- <th></th> -->
                <th></th>
                
               
               
            </tr>
        </tfoot>
    </table>
    @include('include.numberconvert')
  <!-- <span style="text-transform: capitalize; float:right">{{ SettingsFacade::data()->currency }}: {{ convertNumber($qty) }}</span> -->
    <br />
    <table>
        <tbody>
            <tr>
                <td colspan="5">Prepared by:<b><u>{{ $inwardgatepassDetails[0]->user->name }}</u></b></td>
                <td>Checked by:________________</td>
                <td>Approved by:_______________</td>
            </tr>
            <tr>
                <td colspan="5">Print Date:{{ date('d/m/Y') }} Time:{{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
