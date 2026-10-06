
<!DOCTYPE html>
<html>
<head>
    <title>GRN VOUCHER</title>
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
        <center>GRN VOUCHER</center>
    </h3>
    
    <div style="clear:both">
        <div style="float: left;"><b>Voucher Date: </b>{{ date('d/m/Y', strtotime($godownStockDetail[0]->godownstock->date)) }}</div>
         <div style="float: right;"><b>Voucher.No: </b>{{$godownStockDetail[0]->godownstock->voucher_no }}</div><br />
       
    </div>
    <hr /><br>
    <div style="clear:both">
        <div style="float:left;"><b>Supplier:</b> @if($godownStockDetail[0]->godownstock->inward_gatepass->supplier){{ $godownStockDetail[0]->godownstock->inward_gatepass->supplier->party_name}} @else{{"No Record"}}@endif
        </div>
        <div style="float:right;"><b>Purchaser:</b> @if($godownStockDetail[0]->godownstock->inward_gatepass->purchaser){{ $godownStockDetail[0]->godownstock->inward_gatepass->purchaser->party_name}} @else{{"No Record"}} @endif
        </div>
    </div>
    <br /><br />
   <div style="clear:both">
        <div style="float:left;"><b>Req# | IGP#: </b> 
        @if($godownStockDetail[0]->godownstock->inward_gatepass)
        @if($godownStockDetail[0]->godownstock->inward_gatepass->request_generate)
            {{$godownStockDetail[0]->godownstock->inward_gatepass->request_generate->bill_no}}
         @else{{"No Record"}} 
         @endif
         @endif
        |
        @if($godownStockDetail[0]->godownstock->inward_gatepass)
            {{$godownStockDetail[0]->godownstock->inward_gatepass->bill_no}}
         @else{{"No Record"}} 
         @endif
         
        </div>
        <div style="float:right;"><b>Vehicle No#: </b> @if($godownStockDetail[0]->godownstock->inward_gatepass){{ $godownStockDetail[0]->godownstock->inward_gatepass->vehicle_no}} @else{{"No Record"}} @endif
        </div>
    </div>
   <div style="clear:both">
        <div style="float:left;"><b>Transport Company: </b> @if($godownStockDetail[0]->godownstock->inward_gatepass){{ $godownStockDetail[0]->godownstock->inward_gatepass->transport_company}}@else{{"No Record"}} @endif
        </div>
        <div style="float:right;"><b>Driver Name: </b> @if($godownStockDetail[0]->godownstock->inward_gatepass){{ $godownStockDetail[0]->godownstock->inward_gatepass->driver_name}}@else{{"No Record"}} @endif
        </div>
    </div>
   <div style="clear:both">
        <div style="float:left;"><b>Builty Number: </b> @if($godownStockDetail[0]->godownstock->inward_gatepass){{ $godownStockDetail[0]->godownstock->inward_gatepass->builty_no}}@else{{"No Record"}} @endif
        </div>
        <div style="float:right;"><b>Driver PhoneNo: </b> @if($godownStockDetail[0]->godownstock->inward_gatepass){{ $godownStockDetail[0]->godownstock->inward_gatepass->driver_phoneno}}@else{{"No Record"}}@endif
        </div>
    </div>
   <div style="clear:both">
        <div style="float:left;"><b>Warehouse: </b> @if($godownStockDetail[0]->godownstock->warehouse){{ $godownStockDetail[0]->godownstock->warehouse->name}}@else{{"No Record"}}@endif
        </div>
    </div>
   <br><br>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Code</th>
                <th style="text-align:left;">Product Name</th>
                <th>Unit</th>
                <th>IGP Qty</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @php $total=0; $qty=0; @endphp
            @foreach ($godownStockDetail as $value)
                <tr>
                    <td>@if($value->product){{ $value->product->code }}@else{{"No Record"}}@endif</td>
                    <td style="text-align:left;">
                    @if($value->product)
                    {{ $value->product->product_name }}
                    @else{{"No Record"}}@endif
                </td>
                     <td>@if($value->product){{ $value->product->uom }}@else{{"No Record"}}@endif</td>
                    <td style="text-align:right;">{{ number_format($value->demand_qty,2)}}</td>
                    <td style="text-align:right;">{{ number_format($value->qty_in,2)}}</td> 
                </tr>
                @php
                 $qty+=$value->qty_in; 
                 $total+=$value->demand_qty; 
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th style="text-align:right;">{{ number_format($total,2)}}</th>
                 <th style="text-align:right;">{{ number_format($qty,2)}}</th>
                
            </tr>
           
        </tfoot>

    </table>
    @include('include.numberconvert')
  <span style="text-transform: capitalize; float:right">Qty In Words: {{ convertNumber($total) }}/-</span>
  <br /><br />
    <table>
        <tbody>
            <tr>
                <td colspan="2">Generated by:<b><u>
                    @if($godownStockDetail[0]->godownstock)
                        @if($godownStockDetail[0]->godownstock->user)
                        {{ $godownStockDetail[0]->godownstock->user->name }}
                        @endif
                    @endif
                </u></b></td>
                <td>Checked by:________________</td>
                <td>Approved by:_______________</td>
            </tr>
            <tr>
                <td colspan="5">Print Date: {{ date('d/m/Y') }} Time: {{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
