<!DOCTYPE html>
<html>

<head>
    <title>PURCHASE RETURN VOUCHER</title>
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
        <center>PURCHASE RETURN VOUCHER </center>
    </h3>
    <hr /><br />
    <div style="clear:both">
         <div style="float: left;"><b>Voucher.No: </b>{{$purchaseDetails[0]->stock->voucher_no }}</div>
        <div style="float: right;"><b>Voucher
                Date: </b>{{ date('d/m/Y', strtotime($purchaseDetails[0]->stock->date)) }}</div>
    </div>
    <br />
    <div style="clear:both">
        <div style="float:left;"><b>Party.Name: </b> {{ $purchaseDetails[0]->stock->parties->party_name}}</div>
      
        <div style="float:right;"><b>Address:</b> @if($purchaseDetails[0]->stock->parties){{ $purchaseDetails[0]->stock->parties->address }}@endif</div>
        
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Remarks:</b> @if($purchaseDetails[0]->stock){{ $purchaseDetails[0]->stock->remarks }}@endif</div> 
        <div style="float:right;"><b>Payment Type:</b> {{ $purchaseDetails[0]->stock->transaction_type}}</div> 
    </div>
    <div style="clear:both">
         <div style="float:left;"><b> Supplier:</b> @if($purchaseDetails[0]->stock->supplier){{ $purchaseDetails[0]->stock->supplier->party_name}}@endif</div> 
        <div style="float:right;"><b>Purchaser:</b>@if($purchaseDetails[0]->stock->purchaser) {{ $purchaseDetails[0]->stock->purchaser->party_name}}@endif</div>
        
    </div>
    <br /><br />
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Godown</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Packing</th>
                <th>Net Weight</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $total=0; $qty=0; $netweight=0; @endphp
            @foreach ($purchaseDetails as $value)
                <tr>
                    <td>{{ $value->products->product_name }}</td>
                    <td>{{ $value->warehouse->name }}</td>
                    <td>{{ $value->products->uom }}</td>
                    <td>{{ $value->qty_out}}</td> 
                    <td>{{ $value->packing}}</td>
                    <td>{{ $value->net_weight}}</td>
                    <td>{{ $value->sale_rate}}</td>
                    <td>{{ $value->sale_amount}}</td>
                </tr>
                @php
                 $qty+=$value->qty_out; 
                 $netweight+=$value->net_weight; 
                 $total+=$value->sale_amount; 
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <th></th>
                <th>{{ number_format($qty,2)}}</th>
                <th></th>
                <th>{{ number_format($netweight,2)}}</th>
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
