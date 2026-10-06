<!DOCTYPE html>
<html>

<head>
    <title>SALETAX VOUCHER</title>
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
    
    <p style="margin-top:-35px;">
        <center>Address: {{ SettingsFacade::data()->address }}</center>
    </p>
    <p style="margin-top:-15px;">
        <center>Phone: {{ SettingsFacade::data()->phone }} &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;Email: {{ SettingsFacade::data()->email }}</center>
    </p>
    <p style="margin-top:-15px;">
        <center>NTN: {{ optional($warehouse)->ntn }} &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;STRN: {{ optional($warehouse)->strn }}</center>
    </p>
    <hr />
    <h3>
        <center>SALETAX INVOICE </center>
    </h3>
    <div style="clear:both; margin-top: -20px;">
        <div style="float: left;"><b>Invocie.No: </b>{{ $salevoucherDetails[0]->salepurchase->voucher_no }}</div>
        <div style="float: right;"><b>Invocie Date:
            </b>{{ date('d/m/Y', strtotime($salevoucherDetails[0]->salepurchase->date)) }}</div><br />
    </div>
    <br />
    <div style="clear:both">
        <div style="float: left; font-size: 20px;"><b>Buyer Name: {{ $salevoucherDetails[0]->salepurchase->party->party_name }}</b></div>
        <!-- <div style="float: right;"><b>Phone:</b>{{ $salevoucherDetails[0]->salepurchase->party->phone }}</div><br /> -->
    </div><br /><br />
    <div style="clear:both">
        <div style="float: left;"><b>NTN: </b>{{ $salevoucherDetails[0]->salepurchase->party->ntn }}</div>
        <div style="float: right;"><b>STRN:
            </b>{{ $salevoucherDetails[0]->salepurchase->party->strn }}</div><br />
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>Phone: </b>{{ $salevoucherDetails[0]->salepurchase->party->phone }}</div>
        <div style="float: right;"><b>Email: 
            </b>@if($salevoucherDetails[0]->salepurchase->party->email != null)
            {{ $salevoucherDetails[0]->salepurchase->party->email }}
        @else
        {{"No Email Found"}} 
        @endif
        </div><br />
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>PO NO: </b>{{ $salevoucherDetails[0]->salepurchase->dc->po_no }}</div>
        <div style="float: right;"><b>PO Date: 
            </b>@if($salevoucherDetails[0]->salepurchase->dc->po_date)
            {{ date('d/m/Y', strtotime($salevoucherDetails[0]->salepurchase->dc->po_date)) }}
        @else
        {{"No Data Found"}} 
        @endif
        </div><br />
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>Address: </b>{{ $salevoucherDetails[0]->salepurchase->party->address }}</div>
        <!-- <div style="float: right;"><b>Phone:
            </b>{{ $salevoucherDetails[0]->salepurchase->party->phone }}</div> -->
    </div>
    
    <!-- <div style="clear:both">
        <div style="float:left;"><b>DC No: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->voucher_no }} @else{{ 'No Record' }}
            @endif
        </div>
        <div style="float:right;"><b>Vehicle No#: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->vehicle_no }} @else{{ 'No Record' }}
            @endif
        </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Transport Company: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->transport_company }}@else{{ 'No Record' }}
            @endif
        </div>
        <div style="float:right;"><b>Driver Name: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->driver_name }}@else{{ 'No Record' }}
            @endif
        </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Builty Number: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->builty_no }}@else{{ 'No Record' }}
            @endif
        </div>
        <div style="float:right;"><b>Driver PhoneNo: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->driver_phoneno }}@else{{ 'No Record' }}
            @endif
        </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Freight: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->freight }}@else{{ 'No Record' }}
            @endif
        </div>
        <div style="float:right;"><b>Remarks: </b>
            @if ($salevoucherDetails[0]->salepurchase->remarks)
                {{ $salevoucherDetails[0]->salepurchase->remarks }}@else{{ 'No Record' }}
            @endif
        </div>
    </div> -->
    <br><br>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Code</th>
                <th>Product Name</th>
                <th>Unit</th>
                <th>Packing</th>
                <th>Qty</th>
                <th>Sale Qty</th>
                <th>Rate</th>
                
                <th>Excl.val</th>
                <th>ST Rate</th>
                <th>Sale Tax</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandtotal = 0;
                $totalRecQty = 0;
                $totalrate = 0;
                $totalexclval = 0;
                $totalStrate = 0;
                $totalSaletax = 0;
                $totalsaleQty = 0;
            @endphp
            @foreach ($salevoucherDetails as $value)
                <tr>
                @if($status ==0)
                        <td>
                            @if ($value->cusproduct)
                                {{ $value->cusproduct->product_code }}@else{{ 'No Record' }}
                            @endif
                        </td>
                        <td style="text-align:left;">
                            @if ($value->cusproduct)
                                {{ $value->cusproduct->product_name }}@else{{ 'No Record' }}
                            @endif
                        </td>
                  
                    @else
                        <td>
                            @if ($value->product)
                                {{ $value->product->code }}@else{{ 'No Record' }}
                            @endif
                        </td>
                        <td style="text-align:left;">
                            @if ($value->product)
                                {{ $value->product->product_name }}@else{{ 'No Record' }}
                            @endif
                        </td>
                       
                     
                    @endif
                    {{-- <td>{{ $value->demandQty}}</td>  --}}
                    <td>{{ $value->product->uom }}</td>
                    <td>{{ $value->product->packing }}</td>
                    <td>{{ $value->qty }}</td>
                    <td>{{ number_format($value->sale_qty, 2) }}</td>
                    <td>{{ $value->rate }}</td>
                    
                    <td>{{ number_format($value->excl_val) }}</td>
                    <td>{{ $value->st_rate }}</td>
                    <td>{{ number_format($value->sale_tax) }}</td>
                    <td>{{ number_format($value->total) }}</td>
                </tr>
                @php
                    $totalRecQty += $value->qty;
                    $totalrate += $value->rate;
                    $totalsaleQty += $value->sale_qty;
                    $totalexclval += $value->excl_val;
                    $totalStrate += $value->st_rate;
                    $totalSaletax += $value->sale_tax;
                    $grandtotal += $value->total;
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Total</th>
                <th>{{ number_format($totalRecQty, 2) }}</th>
                <!-- <th>{{ number_format($totalrate, 2) }}</th> -->
                <th>{{ number_format($totalsaleQty, 2) }}</th>
                <th></th>
                
                <th>{{ number_format($totalexclval) }}</th>
                <!-- <th>{{ number_format($totalStrate, 2) }}</th> -->
                <th></th>
                <th>{{ number_format($totalSaletax) }}</th>
                <th>{{ number_format($grandtotal) }}</th>

            </tr>
        </tfoot>
    </table>
    @include('include.numberconvert')
    <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">Rupees: {{ convertNumber($grandtotal) }}</span>
    <div style="clear:both">
        <div style="float:left;"><b>Remarks: </b>
            @if ($salevoucherDetails[0]->salepurchase->remarks)
                {{ $salevoucherDetails[0]->salepurchase->remarks }}@else{{ 'No Record' }}
            @endif
        </div>
    </div>
    <br /><br><br/><br/><br/>
    <table>
        <tbody>
            <tr>
                <td>Generated by:<b><u>
                    @if($salevoucherDetails[0]->salepurchase->dc->CreatedBy)
                     {{ $salevoucherDetails[0]->salepurchase->dc->CreatedBy->name }}
                     @endif
                </u></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>Checked by:<b><u>
                @if($salevoucherDetails[0]->salepurchase->user)
                {{ $salevoucherDetails[0]->salepurchase->user->name }}
                @endif
            
            </u></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>Approved by:<b><u></u></b></td>
            </tr>
            <tr>
                <td colspan="5">Print Date: {{ date('d/m/Y') }} || {{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>


</body>

</html>
<!DOCTYPE html>
<html>

<head>
    <title>SALETAX VOUCHER</title>
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
    
    <p style="margin-top:-35px;">
        <center>Address: {{ SettingsFacade::data()->address }}</center>
    </p>
    <p style="margin-top:-15px;">
        <center>Phone: {{ SettingsFacade::data()->phone }} &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;Email: {{ SettingsFacade::data()->email }}</center>
    </p>
    <p style="margin-top:-15px;">
        <center>NTN: {{ optional($warehouse)->ntn }} &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;STRN: {{ optional($warehouse)->strn }}</center>
    </p>
    <hr />
    <h3>
        <center>SALETAX INVOICE </center>
    </h3>
    <div style="clear:both; margin-top: -20px;">
        <div style="float: left;"><b>Invocie.No: </b>{{ $salevoucherDetails[0]->salepurchase->voucher_no }}</div>
        <div style="float: right;"><b>Invocie Date:
            </b>{{ date('d/m/Y', strtotime($salevoucherDetails[0]->salepurchase->date)) }}</div><br />
    </div>
    <br />
    <div style="clear:both">
        <div style="float: left; font-size: 20px;"><b>Buyer Name: {{ $salevoucherDetails[0]->salepurchase->party->party_name }}</b></div>
        <!-- <div style="float: right;"><b>Phone:</b>{{ $salevoucherDetails[0]->salepurchase->party->phone }}</div><br /> -->
    </div><br /><br />
    <div style="clear:both">
        <div style="float: left;"><b>NTN: </b>{{ $salevoucherDetails[0]->salepurchase->party->ntn }}</div>
        <div style="float: right;"><b>STRN:
            </b>{{ $salevoucherDetails[0]->salepurchase->party->strn }}</div><br />
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>Phone: </b>{{ $salevoucherDetails[0]->salepurchase->party->phone }}</div>
        <div style="float: right;"><b>Email: 
            </b>@if($salevoucherDetails[0]->salepurchase->party->email != null)
            {{ $salevoucherDetails[0]->salepurchase->party->email }}
        @else
        {{"No Email Found"}} 
        @endif
        </div><br />
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>PO NO: </b>{{ $salevoucherDetails[0]->salepurchase->dc->po_no }}</div>
        <div style="float: right;"><b>PO Date: 
            </b>@if($salevoucherDetails[0]->salepurchase->dc->po_date)
            {{ date('d/m/Y', strtotime($salevoucherDetails[0]->salepurchase->dc->po_date)) }}
        @else
        {{"No Data Found"}} 
        @endif
        </div><br />
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>Address: </b>{{ $salevoucherDetails[0]->salepurchase->party->address }}</div>
        <!-- <div style="float: right;"><b>Phone:
            </b>{{ $salevoucherDetails[0]->salepurchase->party->phone }}</div> -->
    </div>
    
    <!-- <div style="clear:both">
        <div style="float:left;"><b>DC No: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->voucher_no }} @else{{ 'No Record' }}
            @endif
        </div>
        <div style="float:right;"><b>Vehicle No#: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->vehicle_no }} @else{{ 'No Record' }}
            @endif
        </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Transport Company: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->transport_company }}@else{{ 'No Record' }}
            @endif
        </div>
        <div style="float:right;"><b>Driver Name: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->driver_name }}@else{{ 'No Record' }}
            @endif
        </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Builty Number: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->builty_no }}@else{{ 'No Record' }}
            @endif
        </div>
        <div style="float:right;"><b>Driver PhoneNo: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->driver_phoneno }}@else{{ 'No Record' }}
            @endif
        </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Freight: </b>
            @if ($salevoucherDetails[0]->salepurchase->dc)
                {{ $salevoucherDetails[0]->salepurchase->dc->freight }}@else{{ 'No Record' }}
            @endif
        </div>
        <div style="float:right;"><b>Remarks: </b>
            @if ($salevoucherDetails[0]->salepurchase->remarks)
                {{ $salevoucherDetails[0]->salepurchase->remarks }}@else{{ 'No Record' }}
            @endif
        </div>
    </div> -->
    <br><br>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Code</th>
                <th>Product Name</th>
                <th>Unit</th>
                <th>Packing</th>
                <th>Qty</th>
                <th>Sale Qty</th>
                <th>Rate</th>
                
                <th>Excl.val</th>
                <th>ST Rate</th>
                <th>Sale Tax</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandtotal = 0;
                $totalRecQty = 0;
                $totalrate = 0;
                $totalexclval = 0;
                $totalStrate = 0;
                $totalSaletax = 0;
                $totalsaleQty = 0;
            @endphp
            @foreach ($salevoucherDetails as $value)
                <tr>
                @if($status ==0)
                        <td>
                            @if ($value->cusproduct)
                                {{ $value->cusproduct->product_code }}@else{{ 'No Record' }}
                            @endif
                        </td>
                        <td style="text-align:left;">
                            @if ($value->cusproduct)
                                {{ $value->cusproduct->product_name }}@else{{ 'No Record' }}
                            @endif
                        </td>
                  
                    @else
                        <td>
                            @if ($value->product)
                                {{ $value->product->code }}@else{{ 'No Record' }}
                            @endif
                        </td>
                        <td style="text-align:left;">
                            @if ($value->product)
                                {{ $value->product->product_name }}@else{{ 'No Record' }}
                            @endif
                        </td>
                       
                     
                    @endif
                    {{-- <td>{{ $value->demandQty}}</td>  --}}
                    <td>{{ $value->product->uom }}</td>
                    <td>{{ $value->product->packing }}</td>
                    <td>{{ $value->qty }}</td>
                    <td>{{ number_format($value->sale_qty, 2) }}</td>
                    <td>{{ $value->rate }}</td>
                    
                    <td>{{ number_format($value->excl_val) }}</td>
                    <td>{{ $value->st_rate }}</td>
                    <td>{{ number_format($value->sale_tax) }}</td>
                    <td>{{ number_format($value->total) }}</td>
                </tr>
                @php
                    $totalRecQty += $value->qty;
                    $totalrate += $value->rate;
                    $totalsaleQty += $value->sale_qty;
                    $totalexclval += $value->excl_val;
                    $totalStrate += $value->st_rate;
                    $totalSaletax += $value->sale_tax;
                    $grandtotal += $value->total;
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Total</th>
                <th>{{ number_format($totalRecQty, 2) }}</th>
                <!-- <th>{{ number_format($totalrate, 2) }}</th> -->
                <th>{{ number_format($totalsaleQty, 2) }}</th>
                <th></th>
                
                <th>{{ number_format($totalexclval) }}</th>
                <!-- <th>{{ number_format($totalStrate, 2) }}</th> -->
                <th></th>
                <th>{{ number_format($totalSaletax) }}</th>
                <th>{{ number_format($grandtotal) }}</th>

            </tr>
        </tfoot>
    </table>
    @include('include.numberconvert')
    <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">Rupees: {{ convertNumber($grandtotal) }}</span>
    <div style="clear:both">
        <div style="float:left;"><b>Remarks: </b>
            @if ($salevoucherDetails[0]->salepurchase->remarks)
                {{ $salevoucherDetails[0]->salepurchase->remarks }}@else{{ 'No Record' }}
            @endif
        </div>
    </div>
    <br /><br><br/><br/><br/>
    <table>
        <tbody>
            <tr>
                <td>Generated by:<b><u>
                    @if($salevoucherDetails[0]->salepurchase->dc->CreatedBy)
                     {{ $salevoucherDetails[0]->salepurchase->dc->CreatedBy->name }}
                     @endif
                </u></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>Checked by:<b><u>
                @if($salevoucherDetails[0]->salepurchase->user)
                {{ $salevoucherDetails[0]->salepurchase->user->name }}
                @endif
            
            </u></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>Approved by:<b><u></u></b></td>
            </tr>
            <tr>
                <td colspan="5">Print Date: {{ date('d/m/Y') }} || {{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>


</body>

</html>
