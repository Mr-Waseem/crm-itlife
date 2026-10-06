<!DOCTYPE html>
<html>

<head>
    <title>DELIVERY CHALLAN VOUCHER</title>
    <style>
        #designed {
            border-collapse: collapse;
            font-family: "Times New Roman", serif;
            /* font-family: "Lucida Console", "Courier New", monospace; */
        }

        #designed thead tr th,
        #designed tbody tr td {
            border-right: 1px solid black;
            font-family: "Times New Roman", serif;
            text-align: center;
            font-size: 14px;
            /* font-family: "Lucida Console", "Courier New", monospace; */
        }

        #designed thead tr th {
            border-bottom: 1px solid black;
            font-family: "Times New Roman", serif;
            font-size: 14px;
        }

        #designed tfoot tr th {
            border-top: 1px solid black;
            border-right: 1px solid black;
            font-family: "Times New Roman", serif;
            font-size: 14px;
        }
        b{
            font-family: "Times New Roman", serif;
        }
        td{
            font-family: "Times New Roman", serif;
        }
        th{
            font-family: "Times New Roman", serif;
        }
    </style>
</head>

<body>
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    <h3>
        <center>DELIVERY CHALLAN</center>
    </h3>
    <hr /><br />
    <div style="clear:both">
         <div style="float: left;"><b>Voucher.No: </b>{{ $deliverychallanDetail[0]->delivery_challan->voucher_no }}</div>
        <div style="float: right;"><b>Voucher
                Date: </b>{{ date('d/m/Y', strtotime($deliverychallanDetail[0]->delivery_challan->voucher_date)) }}</div>
    </div>
    <br />
    <div style="clear:both">
        <div style="float:left;"><b>Party.Name: </b> @if($deliverychallanDetail[0]->delivery_challan->party){{ $deliverychallanDetail[0]->delivery_challan->party->party_name}}
        @else{{" No Party"}} @endif
        </div>
      
        <div style="float:right;"><b>Address:</b>@if($deliverychallanDetail[0]->delivery_challan->party){{ $deliverychallanDetail[0]->delivery_challan->party->address }}
              @else{{" No Address"}}
            @endif</div>
        
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Remarks:</b> @if($deliverychallanDetail[0]->delivery_challan){{ $deliverychallanDetail[0]->delivery_challan->remarks }} @else {{" No Remarks"}}@endif</div> 
        <div style="float:right;"><b>SaleOrder No:</b> @if($deliverychallanDetail[0]->delivery_challan->sale_order){{ $deliverychallanDetail[0]->delivery_challan->sale_order->voucher_no }} @else{{" No Record"}}@endif</div> 
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Vehicle no:</b> @if($deliverychallanDetail[0]->delivery_challan){{ $deliverychallanDetail[0]->delivery_challan->vehicle_no }} @else {{" No Record"}}@endif</div> 
        <div style="float:right;"><b>Transport Company:</b> @if($deliverychallanDetail[0]->delivery_challan){{ $deliverychallanDetail[0]->delivery_challan->transport_company }} @else{{" No Record"}}@endif</div> 
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Driver Name:</b> @if($deliverychallanDetail[0]->delivery_challan){{ $deliverychallanDetail[0]->delivery_challan->driver_name }} @else {{" No Record"}}@endif</div> 
        <div style="float:right;"><b>Builty Number:</b> @if($deliverychallanDetail[0]->delivery_challan){{ $deliverychallanDetail[0]->delivery_challan->builty_no }} @else{{" No Record"}}@endif</div> 
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Driver PhoneNo:</b> @if($deliverychallanDetail[0]->delivery_challan){{ $deliverychallanDetail[0]->delivery_challan->driver_phoneno }} @else {{" No Record"}}@endif</div> 
        <div style="float:right;"><b>Freight:</b> @if($deliverychallanDetail[0]->delivery_challan){{ $deliverychallanDetail[0]->delivery_challan->freight }} @else{{" No Record"}}@endif</div> 
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>PO Date:</b> 
        @if($deliverychallanDetail[0]->delivery_challan)
        {{ date('d/m/Y', strtotime($deliverychallanDetail[0]->delivery_challan->po_date)) }}
        @else {{" No Record"}}
        @endif</div> 
        <div style="float:right;"><b>PO No:</b> 
        @if($deliverychallanDetail[0]->delivery_challan)
        {{ $deliverychallanDetail[0]->delivery_challan->po_no }} 
        @else{{" No Record"}}
        @endif</div> 
    </div>
    <br /><br />
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Unit</th>
                <th>Demand</th>
                <th>Dem PCS</th>
                <th>Packing</th>
                <th>Dispatch Qty</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php  $qty=0; $totalsale_qty=0; $totalDemand=0; @endphp
            @foreach ($deliverychallanDetail as $value)
                <tr style="border-bottom: 1px solid;">
                @if($status ==0)
                    <td style="text-align:left;">
                        @if($value->customer_product)
                            {{ $value->customer_product->product_code }} - {{ $value->customer_product->product_name }}
                         @else
                            {{"No Product"}} 
                         @endif</td>
                    @else
                    <td style="text-align:left;">
                        @if($value->product)
                        {{ $value->product->code }} - {{ $value->product->product_name }}
                        @else
                            {{"No Product"}} 
                        @endif</td>
                    @endif
                    <td>@if($value->product){{ $value->product->uom }} @else{{"No Unit"}} @endif</td>
                    <td>{{ number_format($value->quantity, 2)}}</td>
                    <td>{{ number_format($value->demandPCS)}}</td>
                    <td>{{ number_format($value->packing)}}</td>
                    <td>{{ number_format($value->sale_qty)}}</td>
                    <td>{{ $value->comments}}</td>
                </tr>
                @php
                 $qty+=$value->quantity; 
                 $totalsale_qty+=$value->sale_qty; 
                 $totalDemand+=$value->demandPCS; 
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <th>{{ number_format($qty,2)}}</th>
                <th>{{ number_format($totalDemand)}}</th>
                <th></th>
                <th>{{ number_format($totalsale_qty)}}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    @include('include.numberconvert')
  <span style="text-transform: capitalize; float:right">Total Dispatch: {{ convertNumber($totalsale_qty) }}</span>
    <br />
    <table>
        <tbody>
            <tr>
                <td colspan="5">Generated by:<b><u>{{ $deliverychallanDetail[0]->user->name }}</u></b></td>
                <td>Checked by:________________</td>
                <td>Approved by:_______________</td>
            </tr>
            <tr>
                <td colspan="5">Print:{{ date('d/m/y') }}, {{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>
    @if(SettingsFacade::data()->dc_iso ==  1)
    @include('delivery-challan.iso');
    @endif
</body>

</html>
