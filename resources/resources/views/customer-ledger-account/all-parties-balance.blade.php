<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer Balance</title>
    <style>
        table{
            border-collapse: collapse;
        }
        table tbody tr th,
        table tbody tr td {
            /* padding: 7px !important; */
            font-size: 12px;
        }
    </style>
</head>
<body>
<h1><center>{{ SettingsFacade::data()->title }}</center></h1>
    <h3><center>ALL CUSTOMER BALANCE</center></h3>
    <hr /><br />
    <!-- <h1 style="text-align: center;margin-top:-10px;">Customer Balance</h1> -->
    <div style="clear:both">
        <div style="text-align:center;"><b>From Date: </b>{{date("d/m/Y", strtotime($fromDate))}}<b>&emsp;&emsp;&emsp;To Date: </b>{{date("d/m/Y", strtotime($toDate))}}
        </div>
    </div>
    <table border="1" style="width:100%;">
        <tbody>
            <thead>
                <th>Sr#</th>
                <th>Code</th>
                <th>Party Name</th>
                <th>Opening</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Closing</th>
            </thead>
            @php $totalopening = 0; $totaldebit = 0; $totalcredit = 0; $totalclosing = 0; @endphp
            @foreach($customerLedger as $party)
            <tr>
                <td style="">{{ $loop->iteration }}</td>
                <td style="">{{ $party->code }}</td>
                <td style="">{{ $party->party_name }}</td>
                @php $openingbalance = $party->openingDebit - $party->openingCredit; @endphp
                <td style="text-align:right;">{{number_format($openingbalance, 2)}}</td>
                <td style="text-align:right;">{{number_format($party->debit, 2)}}</td>
                <td style="text-align:right;">{{number_format($party->credit, 2)}}</td>
                @php $transactionbalance = $openingbalance + $party->debit - $party->credit; @endphp
                <td style="text-align:right;">{{ number_format($transactionbalance, 2) }}</td>
                @php 
                $totalopening += $openingbalance;
                $totaldebit += $party->debit;
                $totalcredit += $party->credit;
                $totalclosing += $transactionbalance;
                @endphp
            </tr>
            @endforeach
            <tr>
            <td colspan="3">Total</td>
            <td style="text-align:right;">{{number_format($totalopening)}}</td>
            <td style="text-align:right;">{{number_format($totaldebit)}}</td>
            <td style="text-align:right;">{{number_format($totalcredit)}}</td>
            <td style="text-align:right;">{{number_format($totalclosing)}}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
