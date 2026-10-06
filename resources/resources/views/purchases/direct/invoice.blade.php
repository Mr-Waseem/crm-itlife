<!DOCTYPE html>
<html>

<head>
    <title>DIRECT PURCHASE</title>
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
    <h3><center>PURCHASE VOUCHER </center></h3>
    <hr /><br />
    <div style="clear:both">
        <div style="float: left;"><b>Voucher.No: </b>{{ $salevoucher->voucher_no }}</div>
        <div style="float: right;"><b>Voucher Date:
            </b>{{ date('d/m/Y', strtotime($salevoucher->date)) }}</div><br />
    </div>
    <div style="clear:both"> 
        <div style="float:left;"><b>Party Name: </b>
        {{ $salevoucher->party->party_name }} 
        </div>
        <div style="float:right;"><b>Warehouse: </b>
                {{ $salevoucher->warehouse->name }} 
        </div>
    </div>
    <div style="clear:both"> 
        @if($salevoucher->purchaser)
        <div style="float:left;"><b>Purchaser: </b>
        {{ $salevoucher->purchaser->party_name }}
        </div>
        @endif
        @if($salevoucher->vehicle_no)
        <div style="float:right;"><b>Vehicle No#: </b>
                {{ $salevoucher->vehicle_no }} 
        </div>
        @endif
    </div>
    <div style="clear:both">
        @if($salevoucher->transport_company)
        <div style="float:left;"><b>Transport Company: </b>
                {{ $salevoucher->transport_company }}
        </div>
        @endif
        @if($salevoucher->driver_name)
        <div style="float:right;"><b>Driver Name: </b>
                {{ $salevoucher->driver_name }}
        </div>
        @endif
    </div>
    <div style="clear:both">
        @if($salevoucher->builty_no)
        <div style="float:left;"><b>Builty Number: </b>
                {{ $salevoucher->builty_no }}
        </div>
        @endif
        @if($salevoucher->driver_phoneno)
        <div style="float:right;"><b>Driver PhoneNo: </b>
                {{ $salevoucher->driver_phoneno }}
        </div>
        @endif
    </div>
    <div style="clear:both">
        @if($salevoucher->freight)
        <div style="float:left;"><b>Freight: </b>
                {{ $salevoucher->freight }}
        </div>
        @endif
        @if($salevoucher->remarks)
        <div style="float:right;"><b>Remarks: </b>
                {{ $salevoucher->remarks }}
        </div>
        @endif
    </div>
    <br><br>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Code</th>
                <th style="text-align: left;">Product Name</th>
                <th>Pack</th>
                <th>Unit</th>
                <th>Packing</th>
                <th>Qty</th>
                <th>Rate</th>
                <!-- <th>Sale Qty</th> -->
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $grandtotal = 0; $totalPackQty = 0; $totalsaleQty = 0; @endphp
            @foreach($salevoucher->sale_purchase_details as $value)
                <tr style="border-top: 1px solid;">
                    <td>
                        @if($value->product)
                            {{ $value->product->code }}
                        @endif
                    </td>
                    <td  style="text-align: left;">
                        @if($value->product)
                            {{ $value->product->product_name }}
                        @endif
                    </td>
                    <td>{{ number_format($value->qty) }}</td>
                    <td>
                        @if($value->product)
                            {{ $value->product->uom }}
                        @endif
                    </td>
                    <td>
                        @if($value->product)
                            {{ number_format($value->product->packing) }}
                        @endif
                    </td>
                    <td>{{ number_format($value->sale_qty, 2) }}</td>
                    <td>{{ number_format($value->rate, 2) }}</td>
                    <td>{{ number_format($value->total, 2) }}</td>
                </tr>
                @php
                    $totalPackQty += $value->qty;
                    $totalsaleQty += $value->sale_qty;
                    $grandtotal += $value->total;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <th>{{ number_format($totalPackQty) }}</th>
                <th></th>
                <th></th>
                <th>{{ number_format($totalsaleQty, 2) }}</th>
                <th></th>
                <th>{{ number_format($grandtotal, 2) }}</th>
            </tr>
        </tfoot>
    </table>
    @include('include.numberconvert')
    <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: {{ convertNumber($grandtotal) }} Only/--</span>
    <br /><br>
    <table>
        <tbody>
            <tr>
                <td colspan="5">Prepared by:<b><u>{{ $salevoucher->user->name }}</u></b>
                </td>
                <td>Checked by:________________</td>
                <td>Approved by:_______________</td>
            </tr>
            <tr>
                <td colspan="5">Print: {{ date('d/m/Y') }} Time: {{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
