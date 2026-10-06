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

<body style="margin-top:-50px;">
    @if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    @endif

    <h3><center>STOCK REPORT (SUMMARY)</center></h3>
    <p style="text-align: center;"><span>From: {{date("d/m/Y", strtotime($fromDate))}} To: {{date("d/m/Y", strtotime($toDate))}}</p>
    <!-- <div style="clear:both">
        <div style="float: left;"><b>Voucher.No: </b>DFDSF</div>
        <div style="float: right;"><b>Voucher
                Date: </b>DFDSFDS</div>
    </div>
    <br />
    <div style="clear:both">
        <div style="float:left;"><b>Product: </b> DFDSFDS
        </div>
        <div style="float:right;"><b>Unit:</b> DDSFDS</div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Product Code: </b> DFDSFDS
        </div>
        <div style="float:right;"><b>Godown: </b>
           DFDSFD
        </div>
    </div>
    <br /><br /> -->
    <!-- <center><span>From: {{date("d/m/Y", strtotime($fromDate))}} To: {{date("d/m/Y", strtotime($toDate))}} </span></center> -->
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Vr#</th>
                <th>Date</th>
                <th>Type</th>
                <th style="text-align:left !important;">Party&nbsp;Name</th>
                <th>Code</th>
                <th style="text-align:left !important;">Product&nbsp;Name</th>
                <th>PO.Date</th>
                <th>Po#</th>
                <th>Ord.Qty</th>
                <th>Des.Qty</th>
                <th>Balance</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php $sum=0; $Despatch=0; $TotalDespatch=0; $TotalOrderQty=0; $TotalDespatchQty=0; @endphp
            @foreach($summaryReport as $data)
            @php $sum= $sum + 1; @endphp
                <tr>
                    <td>{{$sum}}</td>
                    <td>{{$data['voucher_no']}}</td>
                    <td>{{date("d/m/Y", strtotime($data['voucher_date']))}}</td>
                    <td>
                        @if($data['type'] == "SALE ORDER")
                        {{"ORDER"}}
                        @elseif($data['type'] == "SALE DEMAND")
                        {{"DEMAND"}}
                        @endif
                    </td>
                    
                    <td style="text-align:left !important;">{{$data['party_name']}}</td>
                    <td>{{$data['code']}}</td>
                    <td style="text-align:left !important;">{{$data['product_name']}}</td>
                    <td>{{date("d/m/Y", strtotime($data['po_date']))}}</td>
                    <td>{{$data['po_no']}}</td>
                    <td>{{number_format($data['OrderQty'])}}</td>
                    <td>{{number_format($data['DespatchQty'])}}</td>
                    @php 
                    $Despatch = $data['OrderQty']-$data['DespatchQty'];
                    $TotalOrderQty = $TotalOrderQty + $data['OrderQty'];
                    $TotalDespatchQty = $TotalDespatchQty + $data['DespatchQty'];
                    $TotalDespatch = $TotalDespatch + $Despatch;
                     @endphp
                    <td>{{number_format($Despatch)}}</td>
                    <td style="text-align:left !important;">{{$data['remarks']}}</td>
                
                </tr>
                @endforeach
            <tr style="border-top: 1px solid;">
                <td colspan="9">Total</td>
                <td>{{number_format($TotalOrderQty)}}</td>
                <td>{{number_format($TotalDespatchQty)}}</td>
                <td>{{number_format($TotalDespatch)}}</td>
                <td></td>
            </tr>
        </tbody>

    </table>
    <br />
    <div style="float: left;width:33.3%;font-family:sans-serif;">Prepared By: {{Auth::User()->name}}</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Checked By: __________</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Authorized By: __________</div>
    <p><small style="font-family:sans-serif;">Print Date:{{ date('d/m/Y') }} Time:{{ date('h:i:s A') }}</small></p>
</body>

</html>
