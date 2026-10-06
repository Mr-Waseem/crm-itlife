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
         <div style="float: left;"><b>Vr#: </b>{{$purchaseDetails[0]->voucher_no }} <b>Invoice#: </b>{{$purchaseDetails[0]->sale_return_invoice_no }}</div>
        <div style="float: right;"><b>Date: </b>{{ date('d/m/Y', strtotime($purchaseDetails[0]->date)) }}</div>
    </div>
    <br />
    <div style="clear:both">
        <div style="float:left;"><b>Party.Name: </b> {{ $purchaseDetails[0]->party->party_name}}</div>
      
        <div style="float:right;"><b>Address:</b> @if($purchaseDetails[0]->party){{ $purchaseDetails[0]->party->address }}@endif</div>
        
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Remarks:</b>{{ $purchaseDetails[0]->remarks }}</div> 
        <div style="float:right;"><b>Debit To:</b> {{ $purchaseDetails[0]->credit_to}}</div> 
    </div>
    <div style="clear:both">
         <div style="float:left;"><b> Supplier:</b> @if($purchaseDetails[0]->party){{ $purchaseDetails[0]->party->party_name}}@endif</div> 
        <div style="float:right;"><b>Purchaser:</b>@if($purchaseDetails[0]->purchaser) {{ $purchaseDetails[0]->purchaser->party_name}}@endif</div>
        
    </div>
    <br /><br />
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Code</th>
                <th>Product Name</th>
                <th>Qty</th>
                <th>Unit</th>>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $total=0; $qty=0; $netweight=0; @endphp
            @foreach ($purchaseDetails[0]->sale_purchase_details as $value)
                <tr>
                    <td>{{ $value->product->code }}</td>
                    <td>{{ $value->product->product_name }}</td>
                    <td style="text-align:right;">{{ number_format($value->qty, 2)}}</td> 
                    <td>{{ $value->product->uom }}</td>
                    <td style="text-align:right;">{{ number_format($value->rate, 2)}}</td>
                    <td style="text-align:right;">{{ number_format($value->excl_val, 2)}}</td>
                </tr>
                @php
                 $qty+=$value->qty; 
                 $total+=$value->excl_val; 
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <th style="text-align:right;">{{ number_format($qty,2)}}</th>
                <th></th>
                <th></th>
                <th style="text-align:right;">{{ number_format($total,2)}}</th>
               
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
