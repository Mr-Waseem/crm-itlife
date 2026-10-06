<!DOCTYPE html>
<html>
<head>
    <title>SUPPLIER LEDGER (SUMMARY)</title>
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
        #designed tbody tr td{
            border-top: 0px!important;
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
        #marginleft {
            text-align:left;
        }
    </style>
</head>
<body>
    <!-- @if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    @endif -->
    <h1><center>{{ SettingsFacade::data()->title }}</center></h1>
    <h3><center>SUPPLIER LEDGER (SUMMARY)</center></h3>
    <hr /><br />
    <div style="clear:both">
        <div style="float: left;"><b>Supplier Name: </b>{{$customer->party_name}}</div>
        <div style="float: right;"><b>Supplier Code: </b>{{$customer->code}}</div>
    </div>
    <br />
    <div style="clear:both">
        <div style="float:left;"><b>Phone: </b> {{$customer->phone}}
        </div>
        <div style="float:right;"><b>City:</b> {{$customer->city}}</div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Address: </b> {{$customer->address}}
        </div>
    </div>
    <div style="clear:both">
        <div style="text-align:center;"><b>From Date: </b>{{date("d/m/Y", strtotime($fromDate))}}<b>&emsp;&emsp;&emsp;To Date: </b>{{date("d/m/Y", strtotime($toDate))}}
        </div>
    </div>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Date</th>
                <th style="text-align: left;">Vr.Type</th>
                <th>Vr.No</th>
                <th style="text-align: right;">Debit</th>
                <th style="text-align: right;">Credit</th>
                <th style="text-align: right;">Balance</th>
            </tr>
        </thead>
        <tbody>
        @php 
        $sum = 0; $DebitAmount = 0; $CreditAmount = 0; $TotalDebit = 0; $TotalCredit = 0;
        $OpeningBalance =$OpeningcustomerLedger->debit-$OpeningcustomerLedger->credit;
        @endphp
                <tr style="border-bottom: 1px solid;">
                    <td></td>
                    <td></td>
                    <td style="text-align: left;">OB</td> 
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right;">{{number_format($OpeningBalance)}}</td>
                </tr>
            @foreach($customerLedger as $data)
            @php 
            $sum += 1; 
            $DebitAmount += $data->debit; 
            $CreditAmount += $data->credit; 
            $TotalDebit += $data->debit; 
            $TotalCredit += $data->credit;
            @endphp
                <tr style="border-bottom: 1px solid;">
                    <td>{{$sum}}</td>
                    <td>{{date("d/m/Y", strtotime($data->date))}}</td>
                    <td style="text-align: left;">
                    @if($data->v_type == "SALESTAX INVOICE")
                        {{"STI"}}
                        @elseif($data->v_type == "Cash Receipt")
                        {{"CR"}}
                        @elseif($data->v_type == "Cash Payment")
                        {{"CP"}}
                        @elseif($data->v_type == "Bank Receipt")
                        {{"BR"}}
                        @elseif($data->v_type == "Bank Payment")
                        {{"BP"}}
                        @elseif($data->v_type == "Journal Voucher")
                        {{"JV"}}
                        @elseif($data->v_type == "Opening Balance")
                        {{"OB"}}
                        @elseif($data->v_type == "SALE RETURN")
                        {{"SR"}}
                        @else
                        {{$data->v_type}}-{{$data->voucher_no}}
                        @endif
                    </td>
                    <td>{{$data->voucher_no}}</td>
                    <td style="text-align: right;">{{number_format($data->debit, 2)}}</td>
                    <td style="text-align: right;">{{number_format($data->credit, 2)}}</td>
                    <td style="text-align: right;">{{number_format($DebitAmount-$CreditAmount+$OpeningBalance, 2)}}</td>
                </tr>
            @endforeach
        </tbody>
        <tr style="border-top: 1px solid;">
            <td colspan="4">Total</td>
            <td style="text-align: right;">{{number_format($TotalDebit, 2)}}</td>
            <td style="text-align: right;">{{number_format($TotalCredit, 2)}}</td>
            <td style="text-align: right;">{{number_format($TotalDebit-$TotalCredit+$OpeningBalance, 2)}}</td>
        </tr>
    </table>
    @include('include.numberconvert')
    @php $lastvalue = abs($TotalDebit-$TotalCredit+$OpeningBalance);  @endphp
    @if($lastvalue != 0)
    <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: {{ convertNumber($lastvalue) }} Only/-</span>
    @endif
    <br /><br>
    <br />
    <table>
        <tbody>
            <tr>
                <td colspan="5">Prepared:<b><u>{{Auth::User()->name}}</u></b></td>
                <td>Checked&nbsp;by:________________</td>
                <td>Authorized&nbsp;by:_______________</td>
            </tr>
            <tr>
                <td colspan="5">Print Date: {{ date('d/m/Y') }}, {{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>


</body>
</html>
