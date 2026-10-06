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
        <center>REQUEST REPORT (SUMMARY)</center>
    </h3>
    <hr />
 
    <div style="clear:both">
    <center><span>Request From: {{$requestFrom}}   Request To: {{$requestTo}} </span></center>
    </div>
    <br />
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Date</th>
                <th>Vr.No</th>
                <th>Supplier Name</th>
                <th>Purchaser Name</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>

                <!-- <tr>
                    <td></td>
                    <td></td>
                    <td>OPENING BALANCE</td> 
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> -->
                @php $sum = 0; $TotalQty = 0; @endphp
                @foreach($ReqGeneReportSummary as $data)
                @php 
                    $sum += 1; 
                    $TotalQty += $data->qty; 
                @endphp
                <tr>
                    <td>{{$sum}}</td>
                    <td>{{date("d/m/Y", strtotime($data->date))}}</td>
                    
                    <td>{{$data->bill_no}}</td>
                    <td style="text-align:left;">{{$data->supplier_name}}</td>
                    <td style="text-align:left;">{{$data->purchaser_name}}</td>
                    <td>{{number_format($data->qty, 2)}}</td>
                </tr>
            @endforeach

        </tbody>
        <tr style="border-top: 1px solid;">
            <td colspan="5">Total</td>
          
            <td>{{number_format($TotalQty, 2)}}</td>
        </tr>
    </table>
    @include('include.numberconvert')

    <!-- <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: </span> -->
    <span style="text-transform: capitalize; float:right">Qty in Words: {{ convertNumber($TotalQty) }}</span>
    <br /><br>
    <br />
    <div style="float: left;width:33.3%;font-family:sans-serif;">Prepared: {{Auth::User()->name}}</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Checked: __________</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Authorized: __________</div>
    <p><small style="font-family:sans-serif;">Print:{{ date('d/m/Y') }} : {{ date('h:i:s A') }}</small></p>
</body>
</html>
