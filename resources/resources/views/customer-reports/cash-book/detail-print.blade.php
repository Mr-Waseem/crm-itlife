<!DOCTYPE html>
<html>

<head>
    <title>CASH BOOK (DETAIL)</title>
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/report.css') }}">
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
        <center>CASH BOOK (DETAIL)</center>
    </h3>
    <hr /><br />
    <div style="clear:both">
        <div style="float: left;"><b>Account Name: </b>{{$customer->party_name}}</div>
        <div style="float: right;"><b>Account Code: </b>{{$customer->code}}</div>
    </div>
    <br />
    <!-- <div style="clear:both">
        <div style="float:left;"><b>Phone: </b> {{$customer->phone}}
        </div>
        <div style="float:right;"><b>City:</b> {{$customer->city}}</div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Address: </b> {{$customer->address}}
        </div>
    </div> -->
    <div style="clear:both">
        <div style="text-align:center;"><b>From Date: </b>{{date("d/m/Y", strtotime($fromDate))}}<b>&emsp;&emsp;&emsp;To Date: </b>{{date("d/m/Y", strtotime($toDate))}}
        </div>
    </div>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <!-- <th>Sr#</th> -->
                <th>Date</th>
                <!-- <th>Vr.Type</th> -->
                <th>Vr#</th>
                <th>Warehouse</th>
                <!-- <th>Party Name</th> -->
                <th>Code</th>
                <th>Account Name</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
        @php 
        $sum = 0; 
        $TotalQty = 0; 
        $TotalSTvalue = 0; 
        $DebitAmount = 0; 
        $CreditAmount = 0; 
        $TotalDebit = 0; 
        $TotalCredit = 0;
        $OpeningBalance =$OpeningcustomerLedger->debit-$OpeningcustomerLedger->credit;
        @endphp
                <tr style="border-bottom: 1px solid;">
                    <td></td>
                    <td></td>
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
            $TotalQty += $data->quantity;
            $TotalSTvalue += $data->stvalue;
            $DebitAmount += $data->debit; 
            $CreditAmount += $data->credit; 
            $TotalDebit += $data->debit; 
            $TotalCredit += $data->credit;
            
            @endphp
                <tr>
                    <td>{{date("d/m/Y", strtotime($data->date))}}</td>
                    <td>
                        @if($data->v_type == "SALESTAX INVOICE")
                        {{"STI"}}-{{$data->voucher_no}}
                        @elseif($data->v_type == "Cash Receipt")
                        {{"CR"}}-{{$data->voucher_no}}
                        @elseif($data->v_type == "Cash Payment")
                        {{"CP"}}-{{$data->voucher_no}}
                        @elseif($data->v_type == "Bank Receipt")
                        {{"BR"}}-{{$data->voucher_no}}
                        @elseif($data->v_type == "Bank Payment")
                        {{"BP"}}-{{$data->voucher_no}}
                        @elseif($data->v_type == "Journal Voucher")
                        {{"JV"}}-{{$data->voucher_no}}
                        @elseif($data->v_type == "Opening Balance")
                        {{"OB"}}-{{$data->voucher_no}}
                        @else
                        {{$data->v_type}}-{{$data->voucher_no}}
                        @endif
                    </td>
                    <td style="text-align:left;">{{$data->warehouse->name}}</td>
                    <td style="text-align:left;">{{$data->other_parties->code}}</td>
                    <td style="text-align:left;">{{$data->other_parties->party_name}}</td>
                    <td style="text-align:left;">{{$data->narration}}</td>
                    <td>{{number_format($data->debit)}}</td>
                    <td>{{number_format($data->credit)}}</td>
                    <td>{{number_format($DebitAmount-$CreditAmount+$OpeningBalance)}}</td>
                </tr>
            @endforeach
        </tbody>
        <tr style="border-top: 1px solid;">
            <td colspan="6">Total</td>
            <td>{{number_format($TotalDebit)}}</td>
            <td>{{number_format($TotalCredit)}}</td>
            <td>{{number_format($TotalDebit-$TotalCredit+$OpeningBalance)}}</td>
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
    <p><small style="font-family:sans-serif;">Print Date:{{ date('d/m/Y') }} Time:{{ date('h:i:s A') }}</small></p>
</body>
</html>
