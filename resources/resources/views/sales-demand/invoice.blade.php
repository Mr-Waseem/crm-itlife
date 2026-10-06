<!DOCTYPE html>
<html>

<head>
    <title>SALE DEMAND</title>
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
            border-left: 1px solid black;
            border-right: 1px solid black;
            font-size: 14px;
        }

        #designed tbody tr td {
            border-left: 1px solid black;
        }

        #designed tfoot tr th {
            border-top: 1px solid black;
            border-right: 1px solid black;
            border-left: 1px solid black;
            border-bottom: 1px solid black;
            font-size: 14px;
        }

        #title {
            border: 1.5px solid;
            /* background-color: lightblue; */
        }

        #voucher {
            border: 1.5px solid;
            /* background-color: lightblue; */
            margin-top: -3%;
        }
    </style>
</head>

<body>
@if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    @endif
    <h2 id="voucher">
        <center>SALE DEMAND</center>
    </h2>
    <br />
    <div style="clear:both">
        <div style="float: left;"><b>Voucher.No: </b>{{ $saleorderDetails[0]->sale_order->voucher_no }}</div>
        <div style="float: right;"><b>Voucher
                Date: </b>{{ date('d/m/Y', strtotime($saleorderDetails[0]->sale_order->voucher_date)) }}</div>
    </div>
    <br />
    <div style="clear:both">
        <div style="float:left;"><b>Party.Name: </b>
            @if ($saleorderDetails[0]->sale_order->party)
                {{ $saleorderDetails[0]->sale_order->party->party_name }} @else{{ ' No Party ' }}
            @endif
        </div>
        <div style="float:right;"><b>Address:</b>
            @if ($saleorderDetails[0]->sale_order->party)
                {{ $saleorderDetails[0]->sale_order->party->address }}@else{{ ' No Address' }}
            @endif
        </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Remarks:</b>
            @if ($saleorderDetails[0]->sale_order)
                {{ $saleorderDetails[0]->sale_order->remarks }} @else{{ ' No Remarks' }}
            @endif
        </div>
        <div style="float:right;"><b>Payment Mode:</b>
            @if ($saleorderDetails[0]->sale_order)
                {{ $saleorderDetails[0]->sale_order->payment_mode }} @else{{ ' No Payment Mode' }}
            @endif
        </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Credit Days:</b>
            @if ($saleorderDetails[0]->sale_order)
                {{ $saleorderDetails[0]->sale_order->credit_days }} @else{{ ' No Credit Days' }}
            @endif
        </div>
        <div style="float: right;"><b>P.O DATE: </b>
            @if ($saleorderDetails[0]->sale_order)
                {{ date('d/m/Y', strtotime($saleorderDetails[0]->sale_order->po_date)) }}@else{{ ' No P.O DATE' }}
            @endif
        </div>
    </div>
    <div style="clear:both">
       
        <div style="float:left;"><b>Shipment Terms:</b>
            @if ($saleorderDetails[0]->sale_order)
                {{ $saleorderDetails[0]->sale_order->shipment_term }} @else{{ ' No Shipment Terms' }}
            @endif
        </div>
        <div style="float:right;"><b>P.O.NO#:</b>
            @if ($saleorderDetails[0]->sale_order)
                {{ $saleorderDetails[0]->sale_order->po_no }} @else{{ ' No P.O.NO#' }}
            @endif
        </div>
    </div>
    <br /><br />
    <table id="designed" style="width:100%;">
        <thead>
            <tr>
                <th>Code</th>
                <th style="text-align:left;">Product Name</th>
                
                
                <th>Packing</th>
                <th>Pack.Qty</th>
                <th>Qty</th>
                <th>Unit</th>
                {{-- <th>Rate</th> --}}
                <th>Del Date</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
                $qty = 0;
                $totalOrderQty = 0;
            @endphp
            @foreach ($saleorderDetails as $value)
                <tr style="border-bottom: 1px solid;">
                    @if ($status == 0)
                        <td>
                            @if ($value->product->customer_product)
                                {{ $value->product->customer_product->product_code }}
                            @endif
                        </td>
                        <td style="text-align:left;">
                            @if ($value->product->customer_product)
                                {{ $value->product->customer_product->product_name }}
                            @endif
                        </td>
                    @else
                        <td>
                            @if ($value->product)
                                {{ $value->product->code }}
                            @endif
                        </td>
                        <td style="text-align:left;">
                            @if ($value->product)
                                {{ $value->product->product_name }}
                            @endif
                        </td>
                    @endif

                    <td>{{ number_format($value->packing) }}</td>
                    <td>{{ $value->qty }}</td>
                    <td>{{ number_format($value->order_qty) }}</td>
                    <td>
                        @if ($value->product)
                            {{ $value->product->uom }}
                        @endif
                    </td>
                    {{-- <td>{{ number_format($value->sale_rate, 2) }}</td> --}}
                    <td>{{ date('d/m/y', strtotime($value->delivery_date)) }}</td>
                    <td>{{ $value->remark }}</td>
                </tr>
                @php
                    $qty += $value->qty;
                    $totalOrderQty += $value->order_qty;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Grand Total</th>
                <th>{{ number_format($qty) }}</th>
                <th>{{ number_format($totalOrderQty) }}</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    {{-- @include('include.numberconvert')
  <span style="text-transform: capitalize; float:right">Rupees: {{ convertNumber($total) }}</span> --}}
    <br />
    <table>
        <tbody>
            <tr>
                <td colspan="5">Prepared by:<b><u>{{ $saleorderDetails[0]->sale_order->user->name }}</u></b></td>
                <td>Checked by:________________</td>
                <td>Approved by:_______________</td>
            </tr>
            <tr>
                <td colspan="5">Print:{{ date('d-M-y') }},{{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
