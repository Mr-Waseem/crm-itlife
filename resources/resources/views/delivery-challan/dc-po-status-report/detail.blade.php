<!DOCTYPE html>
<html>

<head>
    <title>DC ORDER PO STATUS REPORT (SUMMARY)</title>
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
    <h3><center>DC ORDER PO STATUS REPORT (DETAIL)</center></h3>
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
                <th>Vr#</th>  
                <th>PO No</th>  
                <th>PO Date</th>  
                <th>Code</th>  
                <th>Product Name</th>
                <th>Demand Qty</th>
            </tr>
        </thead>
        <tbody>
                @php $sum = 0; $totalDemand=0; @endphp
                @foreach($dcdata as $data1)
                    @foreach($data1->sale_order_details as $data)
                    <tr style="border-bottom: 1px solid;">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$data->voucher_no}}</td>
                        <td>{{$data1->po_no}}</td>
                        <td>{{date("d/m/Y", strtotime($data1->po_date))}}</td>


                            @if($status == 1)
                            <td>{{$data->customer_product->product_code}}</td>
                            <td style="text-align:left;">{{$data->customer_product->product_name}}</td>
                            @else
                            <td>{{$data->product->code}}</td>
                            <td style="text-align:left;">{{$data->product->product_name}}</td>

                            @endif
                        <td>{{number_format($data->order_qty)}}</td>
                    </tr>
                    @php 
                    $totalDemand = $totalDemand + $data->order_qty;
                     @endphp
                    @endforeach
                @endforeach

        </tbody>
        <tr style="border-top: 1px solid;">
            <td colspan="6"><b>Total Demand</b></td>
            <td><b>{{number_format($totalDemand)}}</b></td>
        </tr>
    </table>
    <!-----------------------2------------------------------------------------------------->
    <br/><br/>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Vr#</th>  
                <th>Date</th>  
                <th>PO No</th>
                <th>Code</th>  
                <th>Product Name</th>
                <th>Despatch Qty</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
                @php $totalDespatch = 0; $totalBalance =0; @endphp
                @foreach($dcdata as $data2)
                    @foreach($data2->sale_order_details as $data1)
                        @php $Despatch = 0; $balance =0; @endphp
                        @foreach($data1->dc_details2 as $data)
                        <tr style="border-bottom: 1px solid;">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$data->voucher_no}}</td>
                            <td>{{date("d/m/Y", strtotime($data->voucher_date))}}</td>
                            <td>{{$data->po_no}}</td>
                            @if($status == 1)
                            <td>{{$data1->customer_product->product_code}}</td>
                            <td style="text-align:left;">{{$data1->customer_product->product_name}}</td>
                            @else
                            <td>{{$data1->product->code}}</td>
                            <td style="text-align:left;">{{$data1->product->product_name}}</td>
                            @endif
                            <td>{{number_format($data->sale_qty)}}</td>
                            @php 
                            $Despatch = $Despatch + $data->sale_qty;
                            $balance = $data1->order_qty - $Despatch;
                             @endphp
                            <td>{{number_format($balance)}}</td>
                        </tr>
                        @endforeach
                        <tr style="border-top: 1px solid; border-bottom: 1px solid;" >
                            <td colspan="6"><b>Total</b></td>
                            <td><b>{{number_format($Despatch)}}</b></td>
                            <td><b>{{number_format($balance)}}</b></td>
                        </tr>
                        @php 
                            $totalDespatch = $totalDespatch + $Despatch;
                            $totalBalance = $totalBalance + $balance;
                             @endphp
                @endforeach
            @endforeach

        </tbody>
        <tr style="border-top: 1px solid;">
            <td colspan="6"><b>Total Balance</b></td>
          
            <td><b>{{number_format($totalDespatch)}}</b></td>
            <td><b>{{number_format($totalBalance)}}</b></td>
            <!-- <td></td> -->
        </tr>
    </table>
    @include('include.numberconvert')
    <br />
    <div style="float: left;width:33.3%;font-family:sans-serif;">Prepared: {{Auth::User()->name}}</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Checked: __________</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Authorized: __________</div>
    <p><small style="font-family:sans-serif;">Print Date:{{ date('d/m/Y') }} Time: {{ date('h:i:s A') }}</small></p>
</body>
</html>
