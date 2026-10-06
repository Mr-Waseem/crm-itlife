<!DOCTYPE html>
<html>

<head>
    <title>SUPPLIER / PURCHASER LEDGER (DETAIL)</title>
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
        <center>SUPPLIER / PURCHASER LEDGER (DETAIL)</center>
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
                <th>Voucher</th>
                <!-- <th>Party Name</th> -->
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>ST%</th>
                <th>ST.Val</th>
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
                    <td colspan="4">OPENING BALANCE</td>
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
                    <!-- <td>{{$sum}}</td> -->
                    <td>{{date("d/m/Y", strtotime($data->date))}}</td>
                    <!-- <td style="font-size:10px;">{{$data->v_type}}</td> -->
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
                    <!-- <td style="font-size:10px;">{{$data->other_parties->party_name}}</td> -->
                    @if($data->stats == 0)
                    <td style="font-size:10px; text-align:left;">
                        @if($data->customer_products)
                        {{$data->customer_products->product_code}}-{{$data->customer_products->product_name}}
                        @endif
                    </td>
                    @endif
                    @if($data->stats == 1)
                    <td style="font-size:10px; text-align:left;">
                        @if($data->products)
                        {{$data->products->code}}-{{$data->products->product_name}}
                        @endif
                    </td>
                    @endif
                    <td>
                        @if($data->quantity)
                        {{number_format($data->quantity, 2)}}
                        @endif
                    </td>
                    <td>
                    @if($data->rate)
                    {{number_format($data->rate, 2)}}
                    @endif
                </td>
                    <td>
                    @if($data->strate)
                    {{number_format($data->strate, 2)}}
                    @endif
                </td>
                    <td>
                    @if($data->stvalue)
                    {{number_format($data->stvalue)}}
                    @endif
                </td>

                    
                    <td>{{number_format($data->debit)}}</td>
                    <td>{{number_format($data->credit)}}</td>
                    <!-- <td>{{number_format($DebitAmount-$CreditAmount+$OpeningBalance)}}</td> -->
                    <td>{{number_format($CreditAmount-$DebitAmount+$OpeningBalance)}}</td>
                </tr>
            @endforeach
        </tbody>
        <tr style="border-top: 1px solid;">
            <td colspan="3">Total</td>
            
            <td>{{number_format($TotalQty)}}</td>
            <td></td>
            <td></td>
            <td>{{number_format($TotalSTvalue)}}</td>
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
    <p><small style="font-family:sans-serif;">Print Date:{{ date('d/m/Y') }} Time:{{ date('h:i:s A') }}</small></p>
</body>
</html>
