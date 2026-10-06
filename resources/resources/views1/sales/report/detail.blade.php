<!DOCTYPE html>
<html>

<head>
    <title>SALES REPORT (DETAIL)</title>
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
        <center>SALES REPORT (DETAIL)</center>
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
                <th>Product Name</th>
                <th>Thickness</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Total</th>
                <th>Discount</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
       @php $grandqty1 = 0; $qrandtotal1 = 0; $granddiscount1 =0; $grandAmont1 = 0; @endphp
        @foreach($summaryReport as $data1)
        @php $totalqty = 0; $totalTotal1 = 0; $totalDis = 0; $totalAmount = 0; @endphp
        @foreach($data1->sale_purchase_details as $data)
            <tr style="border: 1px solid;">
                <td>{{$loop->iteration}}</td>
                <td>{{date("d/m/Y", strtotime($data->date))}}</td>
                <td>{{$data->voucher_no}}</td>
                <td style="text-align: left;">{{$data1->party->party_name}}</td>
                <td style="text-align: left;">{{$data->product->code}} - {{$data->product->product_name}}</td>
               
                <td style="text-align: right;">{{$data->thickness}}</td>
                <td style="text-align: right;">{{number_format($data->qty, 2)}}</td>
                <td style="text-align: right;">{{number_format($data->rate, 2)}}</td>
                @php
                $totalTotal = $data->qty * $data->rate;
                @endphp
                <td style="text-align: right;">{{number_format($totalTotal, 2)}}</td>
                <td style="text-align: right;">{{number_format($data->discount, 2)}}</td>
                <td style="text-align: right;">{{number_format($data->total, 2)}}</td>
               
            </tr>
            @php 
            $totalqty += $data->qty; 
            $totalTotal1 += $totalTotal; 
            $totalDis += $data->discount; 
            $totalAmount += $data->total; 
            @endphp
        @endforeach
        <tr style="border-top: 1px solid;">
            <th colspan="6">Total</th>
            <th style="text-align: right;">{{number_format($totalqty, 2)}}</th>
            <th style="text-align: right;"></th>
            <th style="text-align: right;">{{number_format($totalTotal1, 2)}}</th>
            <th style="text-align: right;">{{number_format($totalDis, 2)}}</th>
            <th style="text-align: right;">{{number_format($totalAmount, 2)}}</th>
        </tr>

        @php 
        $grandqty1 += $totalqty; 
        $qrandtotal1 += $totalTotal1; 
        $granddiscount1 += $totalDis; 
        
        $grandExCharges = $data1->extra_charges;
        $grandExDiscount = $data1->extra_discount;
        @endphp

        <tr style="border-top: 1px solid;">
            <th colspan="2">Ext.Charges: 
            @if($grandExCharges > 0)
            {{number_format($grandExCharges, 2)}}
            @endif</th>
            <th colspan="2">Ext.Discount: 
                @if($grandExDiscount > 0)
            {{number_format($grandExDiscount, 2)}}
            @endif</th>
            <!-- <th></th> -->
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            @php 
            $grandvalue = $totalAmount + $data1->extra_charges - $data1->extra_discount; 
            $grandAmont1 += $grandvalue; 
            @endphp
            <th style="text-align: right;">{{number_format($grandvalue, 2)}}</th>
           
        </tr>
      
        @endforeach
        </tbody>
        <tr style="border-top: 1px solid;">
            <td colspan="6">Total</td>
            <td style="text-align: right;">{{number_format($grandqty1, 2)}}</td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;">{{number_format($qrandtotal1, 2)}}</td>
            <td style="text-align: right;">{{number_format($granddiscount1, 2)}}</td>
            <td style="text-align: right;">{{number_format($grandAmont1, 2)}}</td>

        </tr>
    </table>
    @include('include.numberconvert')
    <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: {{ convertNumber(abs(23333)) }} Only/--</span>
    <br /><br>
    <br />
    <div style="float: left;width:33.3%;font-family:sans-serif;">Print By: {{Auth::User()->name}}</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Checked: __________</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Authorized: __________</div>
    <p><small style="font-family:sans-serif;">Print Date:{{ date('d/m/Y') }} Time:{{ date('h:i:s A') }}</small></p>
</body>
</html>
