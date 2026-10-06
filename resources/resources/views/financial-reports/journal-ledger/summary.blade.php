<!DOCTYPE html>
<html>

<head>
    <title>CUSTOMER LEDGER (SUMMARY)</title>
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
        <center>JOURNAL LEDGER (SUMMARY)</center>
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
    </div> -->
    <!-- <div style="clear:both">
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
    <p style="text-align: center;">From date: {{date("d/m/Y", strtotime($fromDate))}} To Date: {{date("d/m/Y", strtotime($toDate))}}</p>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Date</th>
                <th>Vr.No</th>
                <th>NARRATION</th>
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
                    <td></td> 
                    <td style="text-align: left; font-size: 12px;">OPENING BALANCE</td>
                    <td></td>
                    <td></td>
                    <td>{{number_format($OpeningBalance, 2)}}</td>
                </tr>
            @foreach($customerLedger as $data)
            @php 
            $sum += 1; 
            $DebitAmount += $data->debit; 
            $CreditAmount += $data->credit; 
            $TotalDebit += $data->debit; 
            $TotalCredit += $data->credit;
            @endphp
                <tr style="border: 1px solid;">
                    <td>{{$sum}}</td>
                    <td>{{date("d/m/Y", strtotime($data->date))}}</td>
                    <!-- <td>{{$data->v_type}}</td> -->


                    @if($data->v_type == "SALESTAX INVOICE")
                        <td>STI-{{$data->v_type}}</td>
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
                    @elseif($data->v_type == "JV MAWB")
                        <td>JVM-{{$data->voucher_no}}</td>
                    @elseif($data->v_type == "DIRECT SALES")
                        <td>SALE-{{$data->voucher_no}}</td>
                    @elseif($data->v_type == "DIRECT PURCHASE")
                        <td>PUR-{{$data->voucher_no}}</td>
                    @else
                        <td>{{$data->v_type}}-{{$data->voucher_no}}</td>
                    @endif



                    <td style="text-align: left; font-size: 12px;">{{$data->narration}}</td>
                    <td>
                        @if($data->debit)
                        {{number_format($data->debit)}}
                        @endif
                    </td>
                    <td>
                    @if($data->credit)
                    {{number_format($data->credit)}}
                    @endif
                    </td>
                    <td>{{number_format($DebitAmount-$CreditAmount+$OpeningBalance, 2)}}</td>
                </tr>
            @endforeach
        </tbody>
        <tr style="border-top: 1px solid;">
            <td colspan="4">Total</td>
            <td>{{number_format($TotalDebit)}}</td>
            <td>{{number_format($TotalCredit)}}</td>
            <td>{{number_format($TotalDebit-$TotalCredit+$OpeningBalance, 2)}}</td>
        </tr>
    </table>
    @include('include.numberconvert')
    <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: {{ convertNumber(abs($TotalDebit-$TotalCredit+$OpeningBalance)) }}</span>
    <br /><br>
    <br />
    <div style="float: left;width:33.3%;font-family:sans-serif;">Prepared: {{Auth::User()->name}}</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Checked: __________</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Authorized: __________</div>
    <p><small style="font-family:sans-serif;">Print Date:{{ date('d/m/Y') }} Time:{{ date('h:i:s A') }}</small></p>
</body>
</html>
