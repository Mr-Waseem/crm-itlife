<!DOCTYPE html>
<html>

<head>
    <title>SALE RETURN VOUCHER</title>
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
        <center>SALE RETURN VOUCHER </center>
    </h3>
    <hr /><br />
    <div style="clear:both">
         <div style="float: left;"><b>Voucher.No: </b>{{$salereturnDetails[0]->stock->voucher_no }}</div>
        <div style="float: right;"><b>Voucher
                Date: </b>{{ date('d/m/Y', strtotime($salereturnDetails[0]->stock->date)) }}</div>
    </div>
    <br />
    <div style="clear:both">
        <div style="float:left;"><b>Party.Name: </b> @if($salereturnDetails[0]->stock->parties){{ $salereturnDetails[0]->stock->parties->party_name}}@else{{"No Party"}} @endif</div>
      
        <div style="float:right;"><b>Address:</b>@if($salereturnDetails[0]->stock->parties){{ $salereturnDetails[0]->stock->parties->address }}@else{{" No Address"}} @endif</div>
        
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Remarks:</b>@if($salereturnDetails[0]->stock) {{ $salereturnDetails[0]->stock->remarks }} @else{{" No Remarks"}}@endif</div> 
        <div style="float:right;"><b> Payment Type:</b> @if($salereturnDetails[0]->stock){{ $salereturnDetails[0]->stock->transaction_type}} @else{{" No Record"}}@endif</div> 
       
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>DeliveryChallan:</b> @if($salereturnDetails[0]->stock){{ $salereturnDetails[0]->stock->dcn_no}}@else{{" No DC#"}} @endif</div> 
    </div>
    <br /><br />
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $total=0; $qty=0; @endphp
            @foreach ($salereturnDetails as $value)
                <tr>
                    <td>@if($value->products){{ $value->products->product_name }} @else{{"No Product"}} @endif</td> 
                    <td>@if($value->products){{ $value->products->uom }} @else{{"No Unit"}} @endif</td>
                    <td>{{ $value->qty_in}}</td> 
                    <td>{{ $value->sale_rate}}</td>
                    <td>{{ $value->sale_amount}}</td> 
                </tr>
                @php
                 $qty+=$value->qty_in; 
                 $total+=$value->sale_amount; 
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="1">Total</th>
              <th></th>
               <th>{{ number_format($qty,2)}}</th>
                <th></th>
                <th>{{ number_format($total,2)}}</th>
               
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
