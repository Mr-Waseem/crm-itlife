<!DOCTYPE html>
<html>

<head>
    <title>SALE ORDER VOUCHER</title>
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
            border-top: 1px solid black;
            border-left:  1px solid black;
            border-right:  1px solid black;
            font-size: 14px;
        }
        #designed tbody tr td{
            border-left: 1px solid black;
        }

        #designed tfoot tr th {
            border-top: 1px solid black;
            border-right: 1px solid black;
            border-left: 1px solid black;
            border-bottom: 1px solid black;
            font-size: 14px;
        }
        #title{
            border: 1.5px solid;
            background-color:lightblue;
            padding-bottom: 10px;
        }
        #voucher{
            border: 1.5px solid;
            background-color: lightblue;
            margin-top: -3%;
        }
    </style>
</head>

<body>
    <h1 id="title">
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    <h2 id="voucher">
        <center>SALE ORDER VOUCHER </center>
    </h2>
   <br />
    <div style="clear:both">
         <div style="float: left;"><b>Voucher.No: </b>{{$saleorderDetails[0]->sale_order->voucher_no }}<b> | Customer Order No:  </b>{{$saleorderDetails[0]->sale_order->party_voucher_no }}</div>
        <div style="float: right;"><b>Voucher
                Date: </b>{{ date('d/m/Y', strtotime($saleorderDetails[0]->sale_order->voucher_date)) }}</div>
    </div>
    <br />
    <div style="clear:both">
        <div style="float:left;"><b>Party.Name: </b>@if($saleorderDetails[0]->sale_order->party) {{ $saleorderDetails[0]->sale_order->party->party_name}} @else{{" No Party "}} @endif</div>
        <div style="float:right;"><b>Address:</b> @if($saleorderDetails[0]->sale_order->party){{ $saleorderDetails[0]->sale_order->party->address }}@else{{" No Address"}} @endif</div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Remarks:</b> @if($saleorderDetails[0]->sale_order){{ $saleorderDetails[0]->sale_order->remarks }} @else{{" No Remarks"}} @endif</div>
        <div style="float:right;"><b>Payment Mode:</b> @if($saleorderDetails[0]->sale_order){{ $saleorderDetails[0]->sale_order->payment_mode }} @else{{" No Payment Mode"}} @endif</div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Credit Days:</b> @if($saleorderDetails[0]->sale_order){{ $saleorderDetails[0]->sale_order->credit_days }} @else{{" No Credit Days"}} @endif</div>
        <div style="float: right;"><b>P.O DATE: </b>@if($saleorderDetails[0]->sale_order){{ date('d/m/Y', strtotime($saleorderDetails[0]->sale_order->po_date)) }}@else{{" No P.O DATE"}} @endif</div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>P.O.NO#:</b> @if($saleorderDetails[0]->sale_order){{ $saleorderDetails[0]->sale_order->po_no }} @else{{" No P.O.NO#"}} @endif</div>
        <div style="float:right;"><b>Shipment Terms:</b> @if($saleorderDetails[0]->sale_order){{ $saleorderDetails[0]->sale_order->shipment_term }} @else{{" No Shipment Terms"}} @endif</div>
    </div>
    <br /><br />
    <table id="designed" style="width:100%;">
        <thead>
            <tr>
                <th>Code</th>
                <th>Product Name</th>
                <th>Unit</th>
                <th>Qty</th>
                <th>Packing</th>
                <th>Or.Qty</th>
                <th>Rate</th>
                <th>Excl.Val</th>
                <th>S.Tax</th>
                <th>ST.Value</th>
                <th>Incl.Value</th>
                <th>Del Date</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php $total=0; $qty=0; $totalOrderQty=0; $totalExclValue=0; @endphp
            @foreach ($saleorderDetails as $value)
                <tr>
                    @if($status ==0)
                    <td>@if($value->customer_product){{ $value->customer_product->product_code }} @else {{"No Product"}} @endif</td>
                    <td style="text-align:left;">@if($value->customer_product){{ $value->customer_product->product_name }} @else {{"No Product"}} @endif</td>
                    <td>@if($value->customer_product->product){{ $value->customer_product->product->uom }} @else {{"No Unit"}} @endif</td>
                    @else
                    <td>@if($value->product){{ $value->product->code }} @else {{"No Product"}} @endif</td>
                    <td style="text-align:left;">@if($value->product){{ $value->product->product_name }} @else {{"No Product"}} @endif</td>
                    <td>@if($value->product){{ $value->product->uom }} @else {{"No Unit"}} @endif</td>
                    @endif
                    <td>{{ $value->qty}}</td> 
                    <td>{{ $value->packing}}</td> 
                    <td>{{ number_format($value->order_qty)}}</td> 
                    <td>{{ $value->sale_rate}}</td>
                    <td>{{ number_format($value->excl_value)}}</td>
                    <td>{{ number_format($value->s_tax)}}</td>
                    <td>{{ number_format($value->st_value)}}</td>
                    <td>{{ number_format($value->sale_amount)}}</td> 
                    <td>{{ date('d/m/Y', strtotime($value->delivery_date))}}</td> 
                    <td>{{ $value->remark}}</td> 
                </tr>
                @php
                 $qty+=$value->qty; 
                 $totalOrderQty+=$value->order_qty; 
                 $totalExclValue+=$value->excl_value; 
                 $total+=$value->sale_amount; 
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Grand Total</th>
                <th>{{ number_format($qty)}}</th>
                <th></th>
                <th>{{ number_format($totalOrderQty)}}</th>
                <th></th>
                <th>{{ number_format($totalExclValue)}}</th>
                <th></th>
                <th></th>
                <th>{{ number_format($total)}}</th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    @include('include.numberconvert')
    @if(($total) > 0) 
  <span style="text-transform: capitalize; float:right">{{ SettingsFacade::data()->currency }}: {{ convertNumber($total) }}</span>
    
    @endif
    <br />
    <table>
        <tbody>
            <tr>
                <td colspan="5">Generated by:<b><u>{{ $saleorderDetails[0]->sale_order->user->name }}</u></b></td>
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
