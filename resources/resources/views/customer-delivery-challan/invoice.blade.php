<!DOCTYPE html>
<html>

<head>
    <title> CUSTOMER DELIVERY CHALLAN VOUCHER</title>
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
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    <h3>
        <center> CUSTOMER DELIVERY CHALLAN VOUCHER </center>
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
                <th>Product Name</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Packing</th>
                <th>Sale Qty</th>
            </tr>
        </thead>
        <tbody>
            @php $total=0; $qty=0; $netpacking=0; @endphp
            @foreach ($deliverychallanDetail as $value)
                <tr>
                    <td>@if($value->cusproduct){{ $value->cusproduct->product_name }} @else{{"No Product"}} @endif</td>
                    <td>@if($value->cusproduct->product){{ $value->cusproduct->product->uom }} @else{{"No Unit"}} @endif</td>
                    <td>{{ number_format($value->quantity,2)}}</td>
                    <td>{{ number_format($value->packing,2)}}</td>
                    <td>{{ number_format($value->sale_qty)}}</td>
                </tr>
                @php
                 $qty+=$value->quantity; 
                 $netpacking+=$value->packing; 
                 $total+=$value->sale_qty
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <th>{{ number_format($qty,2)}}</th>
                <th>{{ number_format($netpacking,2)}}</th>
                <th>{{ number_format($total)}}</th>
               
            </tr>
        </tfoot>
    </table>
    <br />
    <table>
        <tbody>
            <tr>
                <td>Signature: __________</td>
            </tr><br />
            <tr>
                <td>Name & Designation: __________</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
