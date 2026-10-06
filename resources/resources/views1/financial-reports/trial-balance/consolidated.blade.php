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

        #designed tbody tr th{
            border-right: 1px solid black;
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
    <!-- @if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    @endif -->
    <h1><center>{{ SettingsFacade::data()->title }}</center></h1>
    <h3><center>TRIAL BALANCE (Consolidated)</center></h3>
    <hr />
    <!-- <div style="clear:both">
        <div style="float: left;"><b>Account Name: </b></div>
        <div style="float: right;"><b>Account Code: </b></div>
    </div>
    <br /> -->
    <p style="text-align: center;">From date: {{date("d/m/Y", strtotime($fromDate))}} To Date: {{date("d/m/Y", strtotime($toDate))}}</p>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
        <tr style="border-top: 1px solid;">
                <th rowspan="2">Sr#</th>
                <th rowspan="2">Code</th>
                <th rowspan="2">Account Name</th>
                <th colspan="2">OPENING</th>
                <th colspan="2">TRANSACTION</th>
                <th colspan="2">CLOSING</th>
               
            </tr>
            <tr>
                <th>Debit</th>
                <th>Credit</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Debit</th>
                <th>Credit</th>
            </tr>
        </thead>
        @php $grandopeningDebit = 0; $grandopeningCredit= 0; $grandtotaldebit = 0; 
        $grandtotalcredit = 0; $grandclosingdebit = 0; $grandclosingcredit = 0; @endphp
        @foreach($customerLedger as $ag1)
            <tr style="border-top: 1px solid;color:green;">
                <th>{{$loop->iteration }}</th>
                <th>{{ $ag1->code }}</th>
                <th style="text-align:left;">{{ $ag1->name }}</th>
                <th colspan="6"></th>
            </tr>
            
                @foreach($ag1->account_group_2 as $ag2)
                <tr style="border-top: 1px solid; color:blue;">
                    <!-- <th colspan="9" style="text-align:left;margin-left:20%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $ag2->code }} - {{ $ag2->name }}</th> -->
                    <th></th>
                    <th>{{ $ag2->code }}</th>
                    <th style="text-align:left;">{{ $ag2->name }}</th>
                    <th colspan="6"></th>
                </tr>
                
                    @foreach($ag2->account_group_3 as $ag3)
                    <tr style="border-top: 1px solid; color:purple;">
                        <!-- <th colspan="9" style="text-align:left;margin-left:200px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $ag3->code }} - {{ $ag3->name }}</th> -->
                        <th></th>
                    <th>{{ $ag3->code }}</th>
                    <th style="text-align:left;">{{ $ag3->name }}</th>
                    <th colspan="6"></th>
                    </tr>
                    
                    @php $totalopeningDebit = 0; $totalopeningCredit = 0; $totalopeningBalance = 0; $totaldebit = 0; $totalcredit = 0; 
                    $totalclosingdebit = 0; $totalclosingcredit = 0; @endphp
                        @foreach($ag3->parties as $data)
                        @php $closingbalance = 0; @endphp
                        <tr style="border-bottom: 1px solid; border-top: 1px solid;">
                                <td>{{$loop->iteration}}</td>
                                <td>{{$data->code}}</td>
                                <td style="text-align: left;">{{$data->party_name}}</td>
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
                            <td style="text-align:right;">{{number_format($totalopeningDebit, 2)}}</td>
                            <td style="text-align:right;">{{number_format($totalopeningCredit, 2)}}</td>
                            <td style="text-align:right;">{{number_format($totaldebit, 2)}}</td>
                            <td style="text-align:right;">{{number_format($totalcredit, 2)}}</td>
                            <td style="text-align:right;">{{number_format($totalclosingdebit, 2)}}</td>
                            <td style="text-align:right;">{{number_format($totalclosingcredit, 2)}}</td>
                        </tr>
                        @php 
                        $grandopeningDebit += $totalopeningDebit;
                        $grandopeningCredit += $totalopeningCredit;
                        $grandtotaldebit += $totaldebit;
                        $grandtotalcredit += $totalcredit;
                        $grandclosingdebit += $totalclosingdebit;
                        $grandclosingcredit += $totalclosingcredit;
                        @endphp
                       
                    @endforeach
               
            @endforeach
           
        @endforeach
        <tr style="border-top: 1px solid;">
            <td colspan="3">Total</td>
            <td style="text-align:right;">{{number_format($grandopeningDebit, 2)}}</td>
            <td style="text-align:right;">{{number_format($grandopeningCredit, 2)}}</td>
            <td style="text-align:right;">{{number_format($grandtotaldebit, 2)}}</td>
            <td style="text-align:right;">{{number_format($grandtotalcredit, 2)}}</td>
            <td style="text-align:right;">{{number_format($grandclosingdebit, 2)}}</td>
            <td style="text-align:right;">{{number_format($grandclosingcredit, 2)}}</td>
        </tr>
        
        
    <!-- <div style="float: left;width:33.3%;font-family:sans-serif;">Prepared: {{Auth::User()->name}}</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Checked: __________</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Authorized: __________</div>
    <p><small style="font-family:sans-serif;">Print Date:{{ date('d/m/Y') }} Time:{{ date('h:i:s A') }}</small></p> -->
</body>
</html>
