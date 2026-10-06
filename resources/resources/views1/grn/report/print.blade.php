<!DOCTYPE html>
<html>

<head>
    <title>PRINT PRODUCTS</title>
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
    @if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    @endif
    <!-- <h1><center>{{ SettingsFacade::data()->title }}</center></h1> -->

    <h3><center>GRN REPORT (SUMMARY)</center></h3>
    <!-- <h5><center>WAREHOUSE: {{$warehouse->name}}</center></h5> -->
    <center><span>From: {{date("d/m/Y", strtotime($fromDate))}} To: {{date("d/m/Y", strtotime($toDate))}} </span></center>
    <hr />
    <div style="clear:both">
        <div style="float:left;"><b>SUPPLIER: </b> 
        @if($supplier)
        {{$supplier->code}} - {{$supplier->party_name}}
        @else
        {{"ALL"}}
        @endif
    </div>
        <div style="float:right;"><b>PURCHASER: </b> 
        @if($purchaser)
        {{$purchaser->code}} - {{$purchaser->party_name}}
        @else
        {{"ALL"}}
        @endif
        </div>
    </div><br />
    <div style="clear:both">
        <div style="float:left;"><b>PRODUCT: </b> 
        @if($product)
        {{$product->code}} - {{$product->product_name}}
        @else
        {{"ALL"}}
        @endif
        </div>
        <div style="float:right;"><b>WAREHOUSE: </b> {{$warehouse->name}}
        </div>
    </div><br /><br />
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Date</th>
                <th>Grn#</th>
                <th style="width:35%; text-align:left !important;">Supplier Name</th>
                <th style="width:35%; text-align:left !important;">Purchaser Name</th>
                <th>Demand&nbsp;Qty</th>
                <th>Receive&nbsp;Qty</th>
            </tr>
        </thead>
        <tbody>
        @if(count($summaryReport) > 0)
        @php $sum = 0; $totalDemand =0; $totalQty = 0; @endphp
        @foreach($summaryReport as $data)
        @php 
        $sum = $sum + 1;
        $totalDemand = $totalDemand + $data->demand_qty; 
        $totalQty = $totalQty + $data->total_qty;
         @endphp
            <tr>
                <td>{{$sum}}</td>
                <td>{{date('d/m/Y', strtotime($data->date))}}</td>
                <td>{{$data->voucher_no}}</td>
                <td style="text-align:left !important;">{{$data->supplier}}</td>
                <td style="text-align:left !important;">{{$data->purchaser}}</td>
                <td>{{number_format($data->demand_qty)}}</td>
                <td>{{number_format($data->total_qty)}}</td>
            </tr>
        @endforeach
        <tr style="border-top: 1px solid;">
                <td colspan="5"><b>Total</b></td>
                <td><b>{{number_format($totalDemand)}}</b></td>
                <td><b>{{number_format($totalQty)}}</b></td>
            </tr>
        @else
            <tr style="border-top: 1px solid;">
                <td colspan="7">No Data Found!</td>
            </tr>
        @endif
    
        </tbody>
    </table>
    <br /><br /><br />
    <div style="float: left;width:33.3%;font-family:sans-serif;">Prepared: {{Auth::User()->name}}</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Checked: __________</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Authorized: __________</div>
    <p><small style="font-family:sans-serif;">Print Date:{{ date('d/m/Y') }} ||  {{ date('h:i:s A') }}</small></p>
</body>
</html>
