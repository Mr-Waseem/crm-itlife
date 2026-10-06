<!DOCTYPE html>
<html>

<head>
    <title>TRIAL BALANCE</title>
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
        #designed tbody tr th{
            border-right: 1px solid black;
        }
        #designed tfoot tr th {
            border-top: 1px solid black;
            border-right: 1px solid black;
            font-size: 14px;
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
    <h3><center>TRIAL BALANCE (Detailed)</center></h3>
    <hr />
    <!-- <div style="clear:both">
        <div style="float: left;"><b>Account Name: </b></div>
        <div style="float: right;"><b>Account Code: </b></div>
    </div>
    <br /> -->
    <p style="text-align: center;">From date: {{date("d/m/Y", strtotime($fromDate))}} To Date: {{date("d/m/Y", strtotime($toDate))}}</p>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th rowspan="2">Sr#</th>
                <th rowspan="2">Code</th>
                <th rowspan="2">Account Name</th>
                <th colspan="2">OPENING</th>
                <th colspan="2">TRANSACTION</th>
                <th colspan="2">CLOSING</th>
               
            </tr>
            <tr>
                <!-- <th>Sr#</th>
                <th>Code</th>
                <th>Account Name</th> -->
                <th>Debit</th>
                <th>Credit</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Debit</th>
                <th>Credit</th>
            </tr>
        </thead>
        <tbody>
            @php $totalopeningDebit = 0; $totalopeningCredit = 0; $totalopeningBalance = 0; $totaldebit = 0; $totalcredit = 0; 
            $totalclosingdebit = 0; $totalclosingcredit = 0; @endphp
            @foreach($customerLedger as $data)
            @php $closingbalance = 0; @endphp
            <tr style="border-bottom: 1px solid;">
                <td>{{$loop->iteration}}</td>
                <td>{{$data->code}}</td>
                <td style="text-align:left;">{{$data->party_name}}</td>
               @php 
               $totalopeningBalance = $data->openingDebit - $data->openingCredit;
               @endphp
               @if($totalopeningBalance > 0)
               <td style="text-align:right;">{{number_format($totalopeningBalance, 2)}}</td>
               <td style="text-align:right;"></td>
               @php 
               $totalopeningDebit += $totalopeningBalance;
               @endphp
               @else
               <td style="text-align:right;"></td>
               <td style="text-align:right;">{{number_format(abs($totalopeningBalance), 2)}}</td>
               @php
               $totalopeningCredit += abs($totalopeningBalance);
               
               @endphp
               @endif
                <td style="text-align:right;">{{number_format($data->debit, 2)}}</td>
                <td style="text-align:right;">{{number_format($data->credit, 2)}}</td>
                @php $closingbalance = $totalopeningBalance + $data->debit - $data->credit; @endphp
               
               <td style="text-align:right;">
                    @if($closingbalance > 0)
                    {{number_format($closingbalance, 2)}}
                    @php $totalclosingdebit += $closingbalance; @endphp
                    @endif
                </td>
               <td style="text-align:right;">
                    @if($closingbalance < 0)
                    {{number_format(abs($closingbalance), 2)}}
                    @php $totalclosingcredit += abs($closingbalance); @endphp
                    @endif
                </td>
               
                
            </tr>
            @php 
                $totaldebit += $data->debit;
                $totalcredit += $data->credit;
                
                
            @endphp
            @endforeach
            <tr style="border-top: 1px solid;">
               <td colspan="3">Total</td>
                <td>{{number_format($totalopeningDebit, 2)}}</td>
                <td>{{number_format($totalopeningCredit, 2)}}</td>
                <td>{{number_format($totaldebit, 2)}}</td>
                <td>{{number_format($totalcredit, 2)}}</td>
                <td>{{number_format($totalclosingdebit, 2)}}</td>
                <td>{{number_format($totalclosingcredit, 2)}}</td>
            </tr>
        </tbody>
    <!-- <div style="float: left;width:33.3%;font-family:sans-serif;">Prepared: {{Auth::User()->name}}</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Checked: __________</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Authorized: __________</div>
    <p><small style="font-family:sans-serif;">Print Date:{{ date('d/m/Y') }} Time:{{ date('h:i:s A') }}</small></p> -->
</body>
</html>
