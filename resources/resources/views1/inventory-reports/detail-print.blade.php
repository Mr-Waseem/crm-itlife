<!DOCTYPE html>
<html>

<head>
    <title>STOCK REPORT (DETAIL)</title>
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
    <h1><center>{{ SettingsFacade::data()->title }}</center></h1>
    @endif
    <h3><center>STOCK REPORT (DETAIL)</center></h3>
    <p style="text-align: center;"><span>From: {{date("d/m/Y", strtotime($fromDate))}} To: {{date("d/m/Y", strtotime($toDate))}} </span>@if($warehouse)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$warehouse->name}}@endif</p>
    
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
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr style="border: 1px solid;">
                <th>Sr#</th>
                <th>Date</th>
                <th>Vr#</th>
                <th>From</th>
                <th>To</th>
                <th>Party Name</th>
                <th>Product Name</th>
                <th>Packing</th>
                <th>Rec</br>Trf</th>
                <th>Rec</br>Pack</th>
                <th>Issue</br>Trf</th>
                <th>Issue</br>Pack</th>
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
            $packingvalue='';
            $QtyIn=0;
            $QtyOut=0;
            $totalQtyIn=0;
            $OpeningInPack = 0;
            $OpeningOutPack = 0;
            $TotalReceivePack = 0;
            $TotalIssuePack = 0;
            $balqty = 0;
            $balqtyIn = 0;
            $balqtyOut = 0;
            $totalQtyOut = 0;
            $OpeningQtyIn='';
            $OpeningQtyOut='';
            $OpeningQty='';
            $totalOpeningQty='';
            $OpeningInvalue=0;
            $OpeningOutvalue=0;
            $totalopening=0;
            $SinglePacking=0;
            $totalopenings=0;
            $OpeningClosingPack=0;
            $TotalClosingPack=0;
            @endphp



            @if($openingStock)
                @if($openingStock->qty_in)
                @php $OpeningInvalue = $openingStock->qty_in; @endphp
                @else
                @php $OpeningInvalue=0; @endphp
                @endif
                @if($openingStock->qty_out)
                @php $OpeningOutvalue = $openingStock->qty_out; @endphp
                @else
                @php $OpeningOutvalue=0; @endphp
                @endif
            @else
            @php $OpeningInvalue=0;
            $OpeningOutvalue=0; @endphp
            @endif

            @if($openingStock->packing != null)
                @php $SinglePacking = $openingStock->packing; @endphp
            @else
               @php $SinglePacking=0; @endphp
            @endif


            @php
            $totalopening = $OpeningInvalue - $OpeningOutvalue;
            $totalopenings = $totalopening;
            @endphp

            @if($totalopenings != 0 && $SinglePacking > 0)
          
                @php $OpeningCotton = $totalopenings / $SinglePacking; @endphp
            @else
                @php $OpeningCotton = 0; @endphp
            @endif
            <tr style="border-bottom: 1px solid;">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="3">OPENING STOCK</td>
                <td>{{number_format($SinglePacking)}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{number_format($totalopening)}}</td>
                <td>{{number_format($OpeningCotton, 2)}}</td>
            </tr>
            @foreach($stockReportDetailWise as $product)
            @php $sum += 1; @endphp
                <tr style="border: 1px solid;">
                    <td>{{$sum}}</td>
                    <td>{{date("d/m/Y", strtotime($product->date))}}</td>


                    @if($product->type == "OPENING STOCK")
                    <td style="font-size: 8px; text-align:left;">{{"OS"}} - {{$product->voucher_no}}</td>
                    @elseif($product->type == "GRN")
                    <td style="font-size: 8px; text-align:left;">{{"GRN"}} - {{$product->voucher_no}}</td>
                    @elseif($product->type == "STOCK TRANSFER")
                    <td style="font-size: 8px; text-align:left;">{{"TRANS"}} - {{$product->voucher_no}}</td>
                    @elseif($product->type == "DCNonGST")
                    <td style="font-size: 8px; text-align:left;">{{"DC-D"}} - {{$product->voucher_no}}</td>
                    @elseif($product->type == "DC")
                    <td style="font-size: 8px; text-align:left;">{{"DC-O"}} - {{$product->voucher_no}}</td>
                    @elseif($product->type == "PRODUCTION")
                    <td style="font-size: 8px; text-align:left;">{{"PRO"}} - {{$product->voucher_no}}</td>
                    @elseif($product->type == "OPENING PET ROLL")
                    <td style="font-size: 8px; text-align:left;">{{"OPR"}} - {{$product->voucher_no}}</td>
                    @elseif($product->type == "BATCH STOCK TRANSFER")
                    <td style="font-size: 8px; text-align:left;">{{"BST"}} - {{$product->voucher_no}}</td>
                    @else
                    <td style="font-size: 8px; text-align:left;">{{$product->type}} - {{$product->voucher_no}}</td>
                    @endif
                    @if($product->type == "PRODUCTION ONE" || $product->type == "PRODUCTION")
                    <td></td>
                    <td></td>
                    @else
                    @if($product->godownstock)
                        <td style="text-align: left; font-size: 10px;">
                            @if($product->godownstock->warehouse_from)
                            {{$product->godownstock->warehouse_from->name}}
                            @endif
                        </td>
                        <td  style="text-align: left; font-size: 10px;">
                            @if($product->godownstock->warehouse_to)
                            {{$product->godownstock->warehouse_to->name}}
                            @endif
                        </td>
                    @else
                    
                        <td style="text-align: left; font-size: 10px;">
                            @if($product)
                                @if($product->delivery_challan)
                                    @if($product->delivery_challan->warehouse)
                                    {{$product->delivery_challan->warehouse->name}}
                                    @endif
                                @endif
                            @endif
                        </td>
                        <td style="text-align: left; font-size: 10px;">
                            
                        </td>
                    @endif
                    @endif
                    

                    @if($product->product->packing)
                       @php $packingvalue = $product->product->packing; @endphp
                    @else
                       @php $packingvalue = 0; @endphp
                    @endif

                    <td style="text-align: left; font-size: 10px;">
                        @if($product->party)
                        {{$product->party->code}} - {{$product->party->party_name}}
                        @endif
                    </td>
                    
                    @if($product->product)
                    <td style="text-align: left; font-size: 10px;">{{$product->product->code}} - {{$product->product->product_name}}</td>
                    <td>{{number_format($packingvalue)}}</td>
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                
                    @if($product->qty_in)
                       @php $QtyIn = $product->qty_in; @endphp
                       @php $totalQtyIn = $totalQtyIn + $QtyIn; @endphp
                    @else
                        @php $QtyIn=0; @endphp
                    @endif

                    @if($QtyIn < 0)
                    <td style="color:red;">{{number_format($QtyIn)}}</td>
                    @elseif($QtyIn > 0)
                    <td>{{number_format($QtyIn)}}</td>
                    @else
                    <td></td>
                    @endif

                    @if($QtyIn > 0 && $packingvalue > 0)
                        @php
                        $OpeningInPack = $QtyIn/$packingvalue;
                        $TotalReceivePack +=$OpeningInPack;
                        @endphp
                    <td>{{number_format($OpeningInPack, 2)}}</td>
                    @else
                    <td></td>
                    @endif


                    @if($product->qty_out)
                       @php $QtyOut = $product->qty_out; @endphp
                       @php $totalQtyOut = $totalQtyOut + $QtyOut; @endphp
                    @else
                        @php $QtyOut=0; @endphp
                    @endif



                    @if($QtyOut < 0)
                    <td style="color:red;">{{number_format($QtyOut, 2)}}</td>
                    @elseif($QtyOut > 0)
                    <td>{{number_format($QtyOut, 2)}}</td>
                    @else
                    <td></td>
                    @endif

                    @if($QtyOut > 0 && $packingvalue > 0)
                    @php
                    $OpeningOutPack = $QtyOut/$packingvalue;
                    $TotalIssuePack +=$OpeningOutPack;
                    @endphp
                    <td>{{number_format($OpeningOutPack, 2)}}</td>
                    @else
                    <td></td>
                    @endif

                    @php 
                    $balqtyIn = $balqtyIn + $QtyIn;
                    $balqtyOut = $balqtyOut + $QtyOut;
                    $balqty = $totalopening + $balqtyIn - $balqtyOut;
                    @endphp

                    @if($balqty < 0)
                    <td style="color:red;">{{number_format($balqty, 2)}}</td>
                    @elseif($balqty > 0)
                    <td>{{number_format($balqty, 2)}}</td>
                    @else
                    <td>{{number_format($balqty, 2)}}</td>
                    @endif

                    @if($balqty != 0 && $packingvalue > 0)
                       @php $OpeningClosingPack = $balqty/$packingvalue; @endphp
                       @php $TotalClosingPack += $OpeningClosingPack; @endphp
                       <td>{{number_format($OpeningClosingPack, 2)}}</td>
                    @else
                    <td></td>
                    @endif


                    
                    
                </tr>
            @endforeach

            @php
                $GrandOutQty = $OpeningOutvalue+$QtyOut;
                $GrandDetailTotal = $totalopening+$totalQtyIn-$totalQtyOut;
            @endphp
            <tr style="border-top: 1px solid;">
                <td colspan="8">Total</td>
                <td>{{number_format($totalQtyIn, 2)}}</td>
                <td>{{number_format($TotalReceivePack, 2)}}</td>
                <td>{{number_format($totalQtyOut, 2)}}</td>
                <td>{{number_format($TotalIssuePack, 2)}}</td>
                <td>{{number_format($GrandDetailTotal, 2)}}</td>
                <td>{{number_format($TotalReceivePack-$TotalIssuePack+$OpeningCotton, 2)}}</td>
                <!-- <td>{{number_format($TotalClosingPack)}}</td> -->
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
