<!DOCTYPE html>
<html>
<head>
    <title>DC DEMAND PO STATUS REPORT (SUMMARY)</title>
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
    <h1><center>{{ SettingsFacade::data()->title }}</center></h1>
    <h3><center>DC DEMAND PO STATUS REPORT (SUMMARY)</center></h3>
    <!-- <div style="clear:both">
        <div style="float:left;"><b>PONO: </b>
        </div>
        <div style="float:right;"><b>Party Name: </b>
        </div>
    </div>
    <br/><br/> -->
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Sr#</th>
                <th>PO#</th>
                <th>Date</th>
                <th>Code</th>  
                <th>Product Name</th>
                <th>Party Name</th>
                <th>Demand Qty</th>
                <th>Despatch Qty</th>
                <th>Balance</th>
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
                @php $sum = 0; $balance =0; $DespatchQty =0; $BalanceQty =0; $OrderQty = 0; @endphp
                @foreach($dcdata as $data)
                @php
                    $OrderQty = $OrderQty + $data->demandPCS;
                    $DespatchQty = $DespatchQty + $data->sale_qty;
                    $balance = $data->demandPCS - $data->sale_qty;
                    $BalanceQty = $BalanceQty + $balance;
                @endphp
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$data->po_no}}</td>
                    <td>{{date("d/m/Y", strtotime($data->po_date))}}</td>
                    <td>{{$data->code}}</td>
                    <td style="text-align:left;">{{$data->product_name}}</td>
                    <td style="text-align:left;">{{$data->party_name}}</td>
                    <td>{{number_format($data->demandPCS)}}</td>
                    <td>{{number_format($data->sale_qty)}}</td>
                    <td>{{number_format($balance)}}</td>
                </tr>
            @endforeach

        </tbody>
        <tr style="border-top: 1px solid;">
            <td colspan="6">Total</td>
          
            <td>{{number_format($OrderQty)}}</td>
            <td>{{number_format($DespatchQty)}}</td>
            <td>{{number_format($BalanceQty)}}</td>
        </tr>
    </table>
    <br/><br/>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Prepared: {{Auth::User()->name}}</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Checked: __________</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Authorized: __________</div>
    <p><small style="font-family:sans-serif;">Print Date:{{ date('d/m/Y') }} Time: {{ date('h:i:s A') }}</small></p>
</body>
</html>
