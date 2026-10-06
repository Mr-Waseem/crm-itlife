<!DOCTYPE html>
<html>

<head>
    <title>PURCHASER STOCK</title>
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
        <center>PURCHASER STOCK</center>
    </h3>
    <hr /><br />
    <div style="clear:both">
         <div style="float: left;"><b>Voucher.No: </b>{{$PurchaserStockDetails[0]->purchase_stock->bill_no }}</div>
        <div style="float: right;"><b>Voucher
                Date: </b>{{ date('d/m/Y', strtotime($PurchaserStockDetails[0]->purchase_stock->date)) }}</div>
    </div>
    <br />
    <div style="clear:both">
        <div style="float:left;"><b>Inward GatePass No: </b> @if($PurchaserStockDetails[0]->purchase_stock){{ $PurchaserStockDetails[0]->purchase_stock->igp_number}}@endif</div>
        <div style="float:right;"><b>Vehicle No:</b> @if($PurchaserStockDetails[0]->purchase_stock->supplier){{ $PurchaserStockDetails[0]->purchase_stock->supplier->party_name }}@endif</div>

    </div>
   
    <br /><br />
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Code</th>
                <th>Product Name</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $total=0; $qty=0; $totalprice=0; @endphp
            @foreach ($PurchaserStockDetails as $value)
                <tr>
                    <td>{{ $value->product->product_code }}</td>
                    <td>{{ $value->product->product_name }}</td>
                     <td>{{ $value->product->uom }}</td>
                     <td>{{ $value->qty}}</td> 
                    <td>{{ $value->price}}</td>
                    <td>{{ $value->total_amount}}</td>
                </tr>
                @php
                 $qty+=$value->qty; 
                 $totalprice+=$value->price; 
                 $total+=$value->total_amount; 
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <th></th>
                <th>{{ $qty}}</th>
                <th>{{ $totalprice}}</th>
                <th>{{ $total}}</th>
               
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
