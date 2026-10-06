<!DOCTYPE html>
<html>

<head>
    <title>Delivery Challan</title>
    <script>
    window.onload = function() {
        window.print();
    };
</script>
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
@if($warehouseTitle->warehouse_titles ==1)
    <h1><center>{{ $warehouse->name }}</center></h1>
    <p style="margin-top:-35px;">
        <center>Address: {{ $warehouse->address }}</center>
    </p>
    <p style="margin-top:-15px;">
        <center>Phone: {{ $warehouse->phone }} &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;Email: {{ $warehouse->email }}</center>
    </p>
    <p style="margin-top:-15px;">
        <center>NTN: {{ $warehouse->ntn }} &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;STRN: {{ $warehouse->strn }}</center>
    </p>
    @else
    <h1><center>{{ SettingsFacade::data()->title }}</center></h1>
    <p style="margin-top:-35px;">
        <center>Address: {{ SettingsFacade::data()->address }}</center>
    </p>
    <p style="margin-top:-15px;">
        <center>Phone: {{ SettingsFacade::data()->phone }} &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;Email: {{ SettingsFacade::data()->email }}</center>
    </p>
    <p style="margin-top:-15px;">
        <center>NTN: {{ SettingsFacade::data()->ntn }} &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;STRN: {{ SettingsFacade::data()->strn }}</center>
    </p>
    @endif
    <hr />
    <h3>
        <center>Delivery Challan</center>
    </h3>
    <div style="clear:both; margin-top: -20px;">
        <div style="float: left;"><b>DC No: </b>{{ $salevoucherDetails[0]->salepurchase->voucher_no }}</div>
        <div style="float: right;"><b>Date:
            </b>{{ date('d/m/Y', strtotime($salevoucherDetails[0]->salepurchase->date)) }}</div><br />
    </div>
    <br />
    <div style="clear:both">
        <div style="float: left; font-size: 20px;"><b>Buyer Name: {{ $salevoucherDetails[0]->salepurchase->party->party_name }}</b></div>
    </div><br /><br />
    <div style="clear:both">
        <div style="float: left;"><b>Phone: </b>{{ $salevoucherDetails[0]->salepurchase->party->phone }}</div>
        <div style="float: right;"><b>Address: </b>{{ $salevoucherDetails[0]->salepurchase->party->address }}</div><br />
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>Vehicle No: </b>{{ $salevoucherDetails[0]->salepurchase->vehicle_no ?? 'No Record' }}</div>
        <div style="float: right;"><b>Driver Name: </b>{{ $salevoucherDetails[0]->salepurchase->driver_name ?? 'No Record' }}</div><br />
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>Transport Company: </b>{{ $salevoucherDetails[0]->salepurchase->transport_company ?? 'No Record' }}</div>
        <div style="float: right;"><b>Driver PhoneNo: </b>{{ $salevoucherDetails[0]->salepurchase->driver_phoneno ?? 'No Record' }}</div><br />
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>Builty No: </b>{{ $salevoucherDetails[0]->salepurchase->builty_no ?? 'No Record' }}</div>
        <div style="float: right;"><b>Freight: </b>{{ $salevoucherDetails[0]->salepurchase->freight ?? 'No Record' }}</div><br />
    </div>
    <br><br>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Code</th>
                <th>Product Name</th>
                <th>Unit</th>
                <th>Packing</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalQty = 0;
            @endphp
            @foreach ($salevoucherDetails as $key => $value)
                <tr>
                    <td>{{ $key + 1 }}</td>
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
                    <td>{{ $value->product->uom }}</td>
                    <td>{{ $value->product->packing }}</td>
                    <td>{{ $value->sale_qty }}</td>
                </tr>
                @php
                    $totalQty += $value->sale_qty;
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align:right;">Total Qty</th>
                <th>{{ number_format($totalQty, 2) }}</th>
            </tr>
        </tfoot>
    </table>
    <div style="clear:both; margin-top:10px;">
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
                    @if($salevoucherDetails[0]->salepurchase->CreatedBy)
                     {{ $salevoucherDetails[0]->salepurchase->CreatedBy->name }}
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
    @if($warehouseTitle->system_generated_invoice ==1)
    <p style="text-align: center;">This is a system-generated document and does not require a signature or stamp.</p>
    @endif
    <script>
    window.onload = function () {
        window.print();
    }
</script>

</body>

</html>
