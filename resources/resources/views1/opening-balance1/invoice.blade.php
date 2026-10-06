<!DOCTYPE html>
<html>

<head>
    <title>OPEN BALANCE</title>
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
        <center>OPENING BALANCE</center>
    </h3>
    <hr /><br />
    <div style="clear:both">
       
        <div style="float: left;"><b>Voucher.No: </b>{{ $openbalance[0]->voucher_no }}</div>
    </div>
    <div style="clear:both">
        <div style="float: right; margin-top:-20px;"><b>Voucher Date: </b>{{ date('d/m/Y', strtotime($openbalance[0]->voucher_date)) }}</div>
    </div>
    <div style="clear:both">
       <div style="float: left;"><b>Warehouse Name: </b>{{ $openbalance[0]->warehouse->name }}</div>
   </div>
    <br>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Party Code</th>
                <th style="text-align: left;">Party Name</th>
                <th style="text-align: left;">Description</th>
                <th>Debit</th>
                <th>Credit</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalcredit = 0;
                $totaldebit = 0;
            @endphp
            @foreach ($openbalance[0]->voucher_details as $value)
                {{-- @if ($value->credit !== 0) --}}
                <tr style="border: 1px solid;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $value->parties->code }}</td>
                    <td style="text-align:left;">{{ $value->parties->party_name }}</td>
                    <td style="text-align:left;">{{ $value->narration }}</td>
                    <td style="text-align:right;">
                    @if($value->debit)
                    {{ number_format($value->debit, 2) }}
                    @endif
                </td>
                    <td style="text-align:right;">
                    @if($value->credit)  
                    {{ number_format($value->credit, 2) }}
                    @endif
                </td>
                </tr>
                @php
                    $totalcredit += $value->credit;
                    $totaldebit += $value->debit;
                    
                @endphp
                {{-- @endif --}}
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Total</th>
                <!-- <th>Unit</th> -->
                <th style="text-align:right;">{{ number_format($totaldebit, 2) }}</th>
                <th style="text-align:right;">{{ number_format($totalcredit, 2) }}</th>

            </tr>
        </tfoot>
    </table>
    @include('include.numberconvert')
    <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: {{ convertNumber($totalcredit) }} Only/-</span>
    <br /><br>
    <table>
        <tbody>
            <tr>
                @if($openbalance[0]->billers)
                <td>Prepared by:<b><u>{{$openbalance[0]->billers->name}}&nbsp;&nbsp;</u></b></td>
                @else
                <td>Prepared by:<b><u>____________</u></b></td>
                @endif
                <td>Finance:<b><u>_____________</u></b></td>
                <td>Authorized:<b><u>____________</u></b></td>
                <td>Recipient:<b><u>____________</u></b></td>
            </tr>
            <tr>
                <td colspan="5">Print Date: {{ date('d/m/Y') }} || {{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
