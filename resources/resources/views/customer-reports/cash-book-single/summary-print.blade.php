<!DOCTYPE html>
<html>

<head>
    <title>CASH BOOK (SUMMARY)</title>
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/report.css') }}">
</head>

<body>
    @if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    @endif
    <!-- <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1> -->

    <h3>
        <center>CASH BOOK SINGLE DATE (SUMMARY)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Date: </b>{{date("d/m/Y", strtotime($fromDate))}}<b></center>
    </h3>
    <hr />
    <div style="clear:both">
        <div style="float: left;"><b>Account Name: </b>{{$customer->party_name}}</div>
        <div style="float: right;"><b>Account Code: </b>{{$customer->code}}</div>
    </div>
    <br /><br />
    <!-- <div style="clear:both">
        <div style="float:left;"><b>Phone: </b> {{$customer->phone}}
        </div>
        <div style="float:right;"><b>City:</b> {{$customer->city}}</div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Address: </b> {{$customer->address}}
        </div>
    </div> -->
    <!-- <div style="clear:both">
        <div style="float:left;"><b>Product Code: </b> DFDSFDS
        </div>
        <div style="float:right;"><b>Godown: </b>
           DFDSFD
        </div>
    </div> -->
    <!-- <div style="clear:both">
        <div style="text-align:center;"><b>Date: </b>{{date("d/m/Y", strtotime($fromDate))}}<b>
        </div>
    </div> -->
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Date</th>
                <th>Vr.Type</th>
                <th>Warehouse</th>
                <th>Code</th>
                <th style="text-align:left;">Account Name</th>
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
                    <td>OB</td> 
                    <td></td>
                    <td></td>
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

                    @if($data->v_type == "SALESTAX INVOICE")
                    <td>STI-{{$data->voucher_no}}</td>
                    @elseif($data->v_type == "Cash Receipt")
                    <td>CR-{{$data->voucher_no}}</td>
                    @elseif($data->v_type == "Cash Payment")
                    <td>CP-{{$data->voucher_no}}</td>
                    @elseif($data->v_type == "Bank Receipt")
                    <td>BR-{{$data->voucher_no}}</td>
                    @elseif($data->v_type == "Bank Payment")
                    <td>BP-{{$data->voucher_no}}</td>
                    @elseif($data->v_type == "Journal Voucher")
                    <td>JV-{{$data->voucher_no}}</td>
                    @elseif($data->v_type == "Opening Balance")
                    <td>OB-{{$data->voucher_no}}</td>
                    @elseif($data->v_type == "PURCHASE")
                    <td>PUR-{{$data->voucher_no}}</td>
                    @else
                    <td>{{$data->v_type}}-{{$data->voucher_no}}</td>
                    @endif
                    
                    <td>{{$data->name}}</td>
                    <td>{{$data->code}}</td>
                    <td style="text-align:left;">{{$data->party_name}}</td>
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
    <br /><br />
    <table>
        <tbody>
            <tr>
                <td colspan="5">Generated by:<b><u>{{Auth::User()->name}}</u></b></td>
                <td>Checked&nbsp;by:________________</td>
                <td>Approved&nbsp;by:_______________</td>
            </tr>
            <tr>
                <td colspan="5">Print Date: {{ date('d/m/Y') }}, {{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
