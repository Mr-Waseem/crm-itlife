<!DOCTYPE html>
<html>

<head>
    <title>SUPPLIER / PURCHASER LEDGER (SUMMARY)</title>
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
    </style>
</head>

<body>
    <!-- @if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    @endif -->
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>

    <h3>
        <center>SUPPLIER / PURCHASER LEDGER (SUMMARY)</center>
    </h3>
    <hr /><br />
    <div style="clear:both">
        <div style="float: left;"><b>Account Name: </b>{{$customer->party_name}}</div>
        <div style="float: right;"><b>Account Code: </b>{{$customer->code}}</div>
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
    <!-- <div style="clear:both">
        <div style="float:left;"><b>Product Code: </b> DFDSFDS
        </div>
        <div style="float:right;"><b>Godown: </b>
           DFDSFD
        </div>
    </div> -->
    <br /><br />
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Date</th>
                <th>Vr.Type</th>
                <th>Vr.No</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
        @php 
        $sum = 0; 
        $DebitAmount = 0; 
        $CreditAmount = 0; 
        $TotalDebit = 0; 
        $TotalCredit = 0;
        $OpeningBalance =$OpeningcustomerLedger->debit-$OpeningcustomerLedger->credit;
        @endphp
                <tr>
                    <td></td>
                    <td></td>
                    <td>OPENING BALANCE</td> 
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{number_format($OpeningBalance)}}</td>
                </tr>
            @foreach($customerLedger as $data)
            @php 
            $sum += 1; 
            $DebitAmount += $data->debit; 
            $CreditAmount += $data->credit; 
            $TotalDebit += $data->debit; 
            $TotalCredit += $data->credit;
            @endphp
                <tr>
                    <td>{{$sum}}</td>
                    <td>{{date("d/m/Y", strtotime($data->date))}}</td>
                    <td>{{$data->v_type}}</td>
                    <td>{{$data->voucher_no}}</td>
                    <td>{{number_format($data->debit)}}</td>
                    <td>{{number_format($data->credit)}}</td>
                    <td>{{number_format($CreditAmount-$DebitAmount+$OpeningBalance)}}</td>
                </tr>
            @endforeach
        </tbody>
        <tr style="border-top: 1px solid;">
            <td colspan="4">Total</td>
            <td>{{number_format($TotalDebit)}}</td>
            <td>{{number_format($TotalCredit)}}</td>
            <td>{{number_format($TotalCredit-$TotalDebit+$OpeningBalance)}}</td>
        </tr>
    </table>
    @include('include.numberconvert')
    @php $lastvalue = abs($TotalDebit-$TotalCredit+$OpeningBalance);  @endphp
    <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: {{ convertNumber($lastvalue) }}</span>
    <br /><br>
    <br />
    <div style="float: left;width:33.3%;font-family:sans-serif;">Prepared: {{Auth::User()->name}}</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Checked: __________</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Authorized: __________</div>
    <p><small style="font-family:sans-serif;">Date & Time :</small></p>
</body>
</html>
