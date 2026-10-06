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
    <p style="text-align: center;"><span>From: {{date("d/m/Y", strtotime($fromDate))}} To: {{date("d/m/Y", strtotime($toDate))}} @if($warehouse)</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$warehouse->name}}@endif</p>
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
                <th>PID</th>
                <th style="text-align:left !important;">Product.Description</th>
                <th>Packing</th>
                <th>Closing</br>Qty</th>
                <th>Closing</br>Pack</th>
            </tr>
        </thead>
        <tbody>

            <!-- <tr style="border-top: 1px solid; border-bottom: 1px solid;">
                    <th colspan="2">Category Code: 1</th>
                    <th colspan="4">Category Name: 2</th>
            </tr> -->
            @php
            $sum = 0;
            $packingvalue=0;
            $OpeningQtyIn=0;
            $OpeningQtyOut=0;
            $OpeningQty=0;
            $totalOpeningQty=0;
            $OpeningPack=0;
            $TotalOpeningPack=0;
            $QtyIn=0;
            $QtyOut=0;
            $OpeningOutPack=0;
            $TotalIssuePack=0;
            $ProductBalance=0;
            $totalCurrentQty=0;
            $TotalClosingPack=0;
            $totalQtyIn=0;
            $TotalReceivePack=0;
            $totalQtyOut=0;

            @endphp
            @foreach($stockReportSummaryWise as $product)
            @php $sum += 1; @endphp

                <tr style="border: 1px solid;">
                    <td>{{$sum}}</td>
                    <td>{{$product->code}}</td>
                    <td style="text-align:left !important;">{{$product->product_name}}</td>

                    @if($product->packing)
                        @php $packingvalue = $product->packing; @endphp
                    @else
                    @php $packingvalue = 0; @endphp
                    @endif
                    <td>{{number_format($packingvalue)}}</td>


                    @if($product->openingIn)
                    @php $OpeningQtyIn = $product->openingIn; @endphp
                    @else
                    @php $OpeningQtyIn=0; @endphp
                    @endif

                    @if($product->openingOut)
                    @php $OpeningQtyOut = $product->openingOut; @endphp
                    @else
                    @php $OpeningQtyOut=0; @endphp
                    @endif

                    @php
                    $OpeningQty = $OpeningQtyIn - $OpeningQtyOut;
                    $totalOpeningQty = $totalOpeningQty + $OpeningQty;
                    @endphp

                    @if($product->qty_in)
                       @php 
                       $QtyIn = $product->qty_in;
                        $totalQtyIn = $totalQtyIn + $QtyIn;
                        @endphp
                    @else
                    @php $QtyIn=0; @endphp
                    @endif

                    @if($product->qty_out)
                    @php 
                    $QtyOut = $product->qty_out; 
                     $totalQtyOut = $totalQtyOut + $QtyOut;
                     @endphp
                    @else
                    @php $QtyOut=0; @endphp
                    @endif

                    @if($product->packing)
                    @php $packingvalue = $product->packing; @endphp
                    @else
                    @php $packingvalue=0; @endphp
                    @endif

                    @php 
                    $totalQty = $QtyIn - $QtyOut;
                    $totalCurrentQty = $totalCurrentQty + $totalQty;
                    @endphp

                    @php
                    $ProductBalance = $OpeningQty+$totalQty;
                    @endphp
                    @if($ProductBalance < 0)
                        <td style="color:red;">{{number_format($ProductBalance, 2)}}</td>
                    @elseif($ProductBalance > 0)
                        <td>{{number_format($ProductBalance, 2)}}</td>
                    @else
                    <td>0</td>
                    @endif


                    @if($ProductBalance != 0 && $packingvalue > 0)
                           @php 
                           $OpeningClosingPack = $ProductBalance/$packingvalue;
                            $TotalClosingPack = $TotalClosingPack + $OpeningClosingPack; 
                            @endphp

                        <td>{{number_format($OpeningClosingPack, 2)}}</td>`;
                        @else
                        <td>0</td>
                        @endif

                </tr>
            @endforeach
            @php
            $grandTotal = $totalOpeningQty+$totalCurrentQty;
            @endphp
            <tr style="border-top: 1px solid;">
                <td colspan="4">Total</td>
                <!-- <td>{{number_format($totalOpeningQty, 2)}}</td>
                <td>{{number_format($TotalOpeningPack, 2)}}</td>
                <td>{{number_format($totalQtyIn, 2)}}</td>
                <td>{{number_format($TotalReceivePack, 2)}}</td>
                <td>{{number_format($totalQtyOut, 2)}}</td>
                <td>{{number_format($TotalIssuePack, 2)}}</td> -->
                <td>{{number_format($grandTotal, 2)}}</td>
                <td>{{number_format($TotalClosingPack, 2)}}</td>
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
