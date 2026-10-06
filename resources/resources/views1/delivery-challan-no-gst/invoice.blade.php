<!DOCTYPE html>
<html>

<head>
    <title>DELIVERY CHALLAN</title>
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
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    @endif
    <h3>
        <center>DELIVERY CHALLAN </center>
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
    <br /><br />
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Code</th>
                <th style="width: 30%; text-align:left;">Product Name</th>
                
                <!-- <th>Demand<br/>Pack</th>
                <th>Demand<br/>Pcs</th> -->
                <th>PO No</th>
                <th>Pack Qty</th>
                <th>Packing</th>
                <th>Dispatch Qty</th>
                <th>Unit</th>
                <th>Remarks</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @php  $qty=0; $DemandPack=0; $DemandPcs=0; $totalsale_qty=0; @endphp
            @foreach ($deliverychallanDetail as $value)
                <tr>
                    <td>@if($value->product){{ $value->product->code }} @else{{"No Product"}} @endif</td>
                    <td style="width: 30%; text-align:left;">
                    @if($value->product)
                    {{ $value->product->product_name }} 
                    @else{{"No Product"}} 
                    @endif
                </td>
                    
                    <!-- <td>{{ number_format($value->demandqty)}}</td>
                    <td>{{ number_format($value->demandPCS, 2)}}</td> -->
                    <td>{{ $value->po_no }}</td>
                    <td>{{ number_format($value->quantity)}}</td>
                    <td>{{ number_format($value->packing)}}</td>
                    <td>{{ $value->sale_qty}}</td>
                    <td>@if($value->product){{ $value->product->uom }} @else{{"No Unit"}} @endif</td>
                    <td>{{ $value->comments}}</td>
                    @php $total =0; @endphp
                    @foreach($value->product->dc_details as $data)
                    @php $total = $total + $data->sale_qty; @endphp
                    @endforeach
                    <!-- $stock = $value->demandPCS-$total; -->
                    <!-- <td>{{ number_format($value->demandPCS-$total)}}</td> -->
                    <td>{{ number_format($value->demandPCS-$total)}}</td>
                </tr>
                @php
                 $qty+=$value->quantity; 
                 $DemandPack+=$value->demandqty; 
                 $DemandPcs+=$value->demandPCS; 
                 $totalsale_qty+=$value->sale_qty; 
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <!-- <th>{{ number_format($DemandPack)}}</th>
                <th>{{ number_format($DemandPack,2)}}</th> -->
                <th>{{ number_format($qty)}}</th>
                <th></th>
                <th>{{ number_format($totalsale_qty,2)}}</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    @include('include.numberconvert')
  <!-- <span style="text-transform: capitalize; float:right">{{ SettingsFacade::data()->currency }} In Words: {{ convertNumber($totalsale_qty) }} Only</span> -->
    <br />
    <br />
    <table>
        <tbody>
            <tr>
                <td>Prepared by:<b><u>
                @if($deliverychallanDetail[0]->user)    
                {{ $deliverychallanDetail[0]->user->name }}</u></b></td>
                @endif
                <td>Security:________________</td>
                <td>Manager:_______________</td>
                <td>Recipient:_______________</td>
            </tr>
            <tr>
                <td colspan="5">Print Date:{{ date('d/m/Y') }} Time:{{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>
    @if(SettingsFacade::data()->dc_iso ==  1)
    @include('delivery-challan.iso');
    @endif
</body>

</html>
