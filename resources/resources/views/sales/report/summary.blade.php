<!DOCTYPE html>
<html>

<head>
    <title>SALES REPORT (SUMMARY)</title>
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
        <center>SALES REPORT (SUMMARY)</center>
    </h3>
    <hr /><br />
    @if(isset($customer))
    <div style="clear:both">
        <div style="float: left;"><b>Account Name: {{$customer->party_name}} </b></div>
        <div style="float: right;"><b>Account Code: {{$customer->code}}</b></div>
    </div>
    <br />
    @endif
 
    <p style="text-align: center;">From date: {{date("d/m/Y", strtotime($fromDate))}} To Date: {{date("d/m/Y", strtotime($toDate))}}</p>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Date</th>
                <th>Vr.No</th>
                <th>Account Name</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Discount</th>
                <th>Ext.Chrgs</th>
                <th>Ext.Disc</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
        @php $totalqty = 0; $totalTotal1 = 0; $totaldiscount = 0; $totalExtChrg = 0;
        $totalExtDis = 0; $totalAmount1 = 0; @endphp
        @foreach($summaryReport as $data)
            <tr style="border: 1px solid;">
                <td>{{$loop->iteration}}</td>
                <td>{{date("d/m/Y", strtotime($data->date))}}</td>
                <td>{{$data->voucher_no}}</td>
                <td style="text-align: left;">{{$data->party_name}}</td>
                <td style="text-align: right;">{{number_format($data->qty, 2)}}</td>
                @php
                $totalTotal = $data->total + $data->discount;
                $totalamount = $data->total + $data->extra_charges - $data->extra_discount;
                @endphp
                <td style="text-align: right;">{{number_format($totalTotal, 2)}}</td>
                <td style="text-align: right;">{{number_format($data->discount, 2)}}</td>
                <td style="text-align: right;">{{number_format($data->extra_charges, 2)}}</td>
                <td style="text-align: right;">{{number_format($data->extra_discount, 2)}}</td>
                <td style="text-align: right;">{{number_format($totalamount, 2)}}</td>
                @php
                $totalqty += $data->qty;
                $totalTotal1 += $totalTotal;
                $totaldiscount += $data->discount;
                $totalExtChrg += $data->extra_charges;
                $totalExtDis += $data->extra_discount;
                $totalAmount1 += $totalamount;
                
                @endphp
            </tr>
        @endforeach
        </tbody>
        <tr style="border-top: 1px solid;">
            <td colspan="4">Total</td>
            <td style="text-align: right;">{{number_format($totalqty, 2)}}</td>
            <td style="text-align: right;">{{number_format($totalTotal1, 2)}}</td>
            <td style="text-align: right;">{{number_format($totaldiscount, 2)}}</td>
            <td style="text-align: right;">{{number_format($totalExtChrg, 2)}}</td>
            <td style="text-align: right;">{{number_format($totalExtDis, 2)}}</td>
            <td style="text-align: right;">{{number_format($totalAmount1, 2)}}</td>
        </tr>
    </table>
    @include('include.numberconvert')
    <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: {{ convertNumber(abs($totalAmount1)) }} Only/--</span>
    <br /><br>
    <br />
    <div style="float: left;width:33.3%;font-family:sans-serif;">Print By: {{Auth::User()->name}}</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Checked: __________</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Authorized: __________</div>
    <p><small style="font-family:sans-serif;">Print Date:{{ date('d/m/Y') }} Time:{{ date('h:i:s A') }}</small></p>
</body>
</html>
