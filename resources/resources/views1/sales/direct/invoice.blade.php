<!DOCTYPE html>
<html>

<head>
    <title>SALE VOUCHER</title>
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
        <center>SALE VOUCHER DIRECT </center>
    </h3>
    <hr /><br />
    <div style="clear:both">
        <div style="float: left;"><b>Voucher.No: </b>{{ $salevoucherDetails[0]->salepurchase->voucher_no }}</div>
        <div style="float: right;"><b>Voucher Date:
            </b>{{ date('d/m/Y', strtotime($salevoucherDetails[0]->salepurchase->date)) }}</div><br />
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>Party Name: </b>{{ $salevoucherDetails[0]->party->party_name }}</div>
        <div style="float: right;"><b>Party Code:</b>{{ $salevoucherDetails[0]->party->code }}</div><br />
    </div>
    <br />
    @if ($salevoucherDetails[0]->salepurchase->remarks)
    <div style="clear:both">
        <div style="float:left;"><b>Remarks: </b>
       
                {{ $salevoucherDetails[0]->salepurchase->remarks }}
          
        </div>
    </div>
    <br><br>
    @endif
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Code</th>
                <th style="text-align: left;">Product Name</th>
                <th>Unit</th>
                <th>Thickness</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Discount</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandtotal = 0;
                $totalRecQty = 0;
                $totalrate = 0;
                $totalsaleQty = 0;
                $totalDiscount = 0;
            @endphp
            @foreach ($salevoucherDetails as $value)
                <tr>
                   
                        <td>
                            @if ($value->product)
                                {{ $value->product->code }}@else{{ 'No Record' }}
                            @endif
                        </td>
                        <td  style="text-align: left;">
                            @if ($value->product)
                                {{ $value->product->product_name }}@else{{ 'No Record' }}
                            @endif
                        </td>
                        <td>
                            @if ($value->product)
                                {{ $value->product->uom }}@else{{ 'No Record' }}
                            @endif
                        </td>

                    <td>{{ number_format($value->thickness) }}</td>
                    <td>{{ number_format($value->sale_qty, 2) }}</td>
                    <td>{{ number_format($value->rate, 2) }}</td>
                    <td>{{ number_format($value->discount, 2) }}</td>
                    <td>{{ number_format($value->total, 2) }}</td>
                </tr>
                @php
                    $totalRecQty += $value->qty;
                    $totalrate += $value->rate;
                    $totalsaleQty += $value->sale_qty;
                    $totalDiscount += $value->discount;
                    $grandtotal += $value->total;
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Total</th>
                <th>{{ number_format($totalsaleQty) }}</th>
                <th></th>
                <th>{{ number_format($totalDiscount) }}</th>
                <th>{{ number_format($grandtotal, 2) }}</th>
            </tr>
            <!-- <tr>
            <th colspan="5" style="border-right: none;"></th>
                <th>Extra Charges (Cutting / Service etc)</th>
                <th>{{ number_format($salevoucherDetails[0]->salepurchase->extra_charges) }}</th>
            </tr>
            <tr>
                <th colspan="5" style="border-right: none;"></th>
                <th>Discount</th>
                <th>{{ number_format($salevoucherDetails[0]->salepurchase->discount) }}</th>
            </tr> -->
        </tfoot>
    </table>
    @include('include.numberconvert')
    @if(SettingsFacade::data()->extra_charges_sale == 0 && SettingsFacade::data()->discount_sale ==0)
    <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: {{ convertNumber($grandtotal) }} Only/--</span>
    <br /><br>
    <br/>
    @endif
    <!-- Discounted table -->
    @if(SettingsFacade::data()->extra_charges_discount_sale != 0)
    <br/>
    <table id="designed" style="border:2px solid; width:50%; float:right;">
    <thead>
            @if($salevoucherDetails[0]->salepurchase->extra_charges > 0)
            <tr>
                <th style="text-align:left;">Extra Charges (Cutting / Service etc)</th>
                <td style="border-bottom: 1px solid; text-align:right; width: 30%;">
                {{ number_format($salevoucherDetails[0]->salepurchase->extra_charges) }}
                </td>
            </tr>
           @endif
           @if($salevoucherDetails[0]->salepurchase->extra_discount > 0)
            <tr>
                <th style="text-align:left;">Discount</th>
                <td style="border-bottom: 1px solid; text-align:right;">
                {{ number_format($salevoucherDetails[0]->salepurchase->extra_discount) }}
                    </td>
            </tr>
           @endif
            <tr>
                <th style="text-align:left;">Total Amount</th>
                <td style="border-bottom: 1px solid; text-align:right;">
                    @php
                        $totaldiscountedAmount = $grandtotal + $salevoucherDetails[0]->salepurchase->extra_charges - $salevoucherDetails[0]->salepurchase->extra_discount;
                    @endphp
                {{ number_format($totaldiscountedAmount, 2) }}
                    </td>
            </tr>
         
        </thead>
    </table><br/><br/><br/><br/> 
    <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: {{ convertNumber($totaldiscountedAmount) }} Only/--</span>
    @endif
    <br /><br>
    <br/>
    <br/><br/><br/><br/><br/>
   
    <table>


        <tbody>
            <tr>
                <td colspan="5">Generated by:<b><u>{{ $salevoucherDetails[0]->salepurchase->user->name }}</u></b>
                </td>
                <td>Checked by:________________</td>
                <td>Approved by:_______________</td>
            </tr>
            <tr>
                <td colspan="10">Print Date:{{ date('d/m/Y') }} Time: {{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
