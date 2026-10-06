<!DOCTYPE html>
<html>

<head>
    <title>PURCHASETAX RETURN VOUCHER</title>
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
        <center>PURCHASETAX RETURN</center>
    </h3>
    <hr /><br />
    <div style="clear:both">
        <div style="float: left;"><b>Voucher.No: </b>{{ $salevoucherDetails[0]->salepurchase->voucher_no }}</div>
        <div style="float: right;"><b>Voucher Date:
            </b>{{ date('d/m/Y', strtotime($salevoucherDetails[0]->salepurchase->date)) }}</div><br />
    </div>
    <div style="clear:both">
        @if($salevoucherDetails[0]->salepurchase->sale_return_type == "INVOICE")
        <div style="float:left;"><b>DC No: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->voucher_no }} 
                @else{{ 'No Record' }}
            @endif
        </div>
        <div style="float:left;"><b>&nbsp;&nbsp;&nbsp;Invoice No: </b>
            @if ($salevoucherDetails[0]->salepurchase)
                {{ $salevoucherDetails[0]->salepurchase->sale_return_invoice_no }} @else{{ 'No Record' }}
            @endif
        </div>
        @else
        <div style="float:left;"><b>Type: </b>
            @if ($salevoucherDetails[0]->salepurchase)
                {{ $salevoucherDetails[0]->salepurchase->sale_return_type }}
            @endif
        </div>
        @endif
        <div style="float:right;"><b>Vehicle No#: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->vehicle_no }} 
                @else
                {{ $salevoucherDetails[0]->salepurchase->vehicle_no }} 
            @endif
        </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Transport Company: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->transport_company }}
                @else
                {{ $salevoucherDetails[0]->salepurchase->transport_company }}
            @endif
        </div>
        <div style="float:right;"><b>Driver Name: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->driver_name }}
                @else
                {{ $salevoucherDetails[0]->salepurchase->driver_name }}
            @endif
        </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Builty Number: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->builty_no }}
                @else
                {{ $salevoucherDetails[0]->salepurchase->builty_no }}
            @endif
        </div>
        <div style="float:right;"><b>Driver PhoneNo: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->driver_phoneno }}
                @else
                {{ $salevoucherDetails[0]->salepurchase->driver_phoneno }}
            @endif
        </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Freight: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->freight }}
                @else
                {{ $salevoucherDetails[0]->salepurchase->freight }}
            @endif
        </div>
        <div style="float:right;"><b>Remarks: </b>
            @if ($salevoucherDetails[0]->salepurchase->remarks)
                {{ $salevoucherDetails[0]->salepurchase->remarks }}
            @endif
        </div>
    </div>
    <br><br>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>PO#</th>
                <th style="text-align: left;">Product Name</th>
                <th>Unit</th>
                <th>Packing</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Sale Qty</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandtotal = 0;
                $totalRecQty = 0;
                $totalrate = 0;
                $totalsaleQty = 0;
            @endphp
            @foreach ($salevoucherDetails as $value)
                <tr style="border-top: 1px solid;">
                    @if ($salevoucherDetails[0]->salepurchase->challan_type == 'CDC')
                        <td>
                        {{$value->po_no}}
                        </td>
                        <td style="text-align: left;">
                            @if ($value->cusproduct)
                            {{ $value->cusproduct->code }} - {{ $value->cusproduct->product_name }}@else{{ 'No Record' }}
                            @endif
                        </td>
                        <td>
                            @if ($value->cusproduct)
                                {{ $value->cusproduct->product->uom }}@else{{ 'No Record' }}
                            @endif
                        </td>
                    @else
                        <td>{{$value->po_no}}</td>
                        <td  style="text-align: left;">
                            @if ($value->product)
                            {{ $value->product->code }} - {{ $value->product->product_name }}
                            @endif
                        </td>
                        <td>
                            @if ($value->product)
                                {{ $value->product->uom }}@else{{ 'No Record' }}
                            @endif
                        </td>
                        <td>
                            @if ($value->product)
                                {{ number_format($value->product->packing) }}@else{{ 'No Record' }}
                            @endif
                        </td>
                    @endif
                    {{-- <td>{{ $value->demandQty}}</td>  --}}
                    <td>{{ number_format($value->qty) }}</td>
                    <td>{{ number_format($value->rate) }}</td>
                    <td>{{ number_format($value->sale_qty) }}</td>
                    <td>{{ number_format($value->total) }}</td>
                </tr>
                @php
                    $totalRecQty += $value->qty;
                    $totalrate += $value->rate;
                    $totalsaleQty += $value->sale_qty;
                    $grandtotal += $value->total;
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Total</th>
                <th>{{ number_format($totalRecQty) }}</th>
                <!-- <th>{{ number_format($totalrate, 2) }}</th> -->
                <th></th>
                <th>{{ number_format($totalsaleQty) }}</th>
                <th>{{ number_format($grandtotal) }}</th>

            </tr>
        </tfoot>
    </table>
    @include('include.numberconvert')
    <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: {{ convertNumber($grandtotal) }}</span>
    <br /><br>
    <table>
        <tbody>
            <tr>
                <td colspan="5">Prepared by:<b><u>{{ $salevoucherDetails[0]->salepurchase->user->name }}</u></b>
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
