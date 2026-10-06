<!DOCTYPE html>
<html>

<head>
    <title>PURCHASE VOUCHER</title>
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
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    <h3>
        <center>PURCHASE VOUCHER </center>
    </h3>
    <hr /><br />
    <div style="clear:both">
    <div style="float: left;"><b>Vr.#: </b>{{$purchaseDetails[0]->salepurchase->voucher_no }} || <b>Date.#: </b>{{ date('d/m/Y', strtotime($purchaseDetails[0]->salepurchase->date)) }}</div>
        <div style="float: right;">
            <b>Grn No:</b> 
            @if($purchaseDetails[0]->salepurchase->grn)
            {{ $purchaseDetails[0]->salepurchase->grn->voucher_no}} 
            @else{{"No Record"}} 
            @endif || 
            <b>Date: </b>
            @if($purchaseDetails[0]->salepurchase->grn)
            {{ date('d/m/Y', strtotime($purchaseDetails[0]->salepurchase->grn->date)) }}
            @endif
    </div>
        <br />
       
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>IGP NO: </b>
        @if($purchaseDetails[0]->salepurchase->grn->inward_gatepass)
            {{$purchaseDetails[0]->salepurchase->grn->inward_gatepass->bill_no}} || 
            @endif
            <b>Date: </b>
            @if($purchaseDetails[0]->salepurchase->grn->inward_gatepass)
            {{ date('d/m/Y', strtotime($purchaseDetails[0]->salepurchase->grn->inward_gatepass->date)) }}
        </div>
            @else
            {{"Not Found!"}}
            @endif
        <div style="float: right;"><b>Request#:</b> 
        @if($purchaseDetails[0]->salepurchase->grn->inward_gatepass->request_generate)
        {{$purchaseDetails[0]->salepurchase->grn->inward_gatepass->request_generate->bill_no}} || 
        @endif
        <b>Date: </b>
            @if($purchaseDetails[0]->salepurchase->grn->inward_gatepass->request_generate)
                {{ date('d/m/Y', strtotime($purchaseDetails[0]->salepurchase->grn->inward_gatepass->request_generate->date)) }}
            @else
            {{"Not Found!"}}
                @endif
        </div>
        
    </div>
    <br />
    <div style="clear:both">
        <div style="float:left;"><b>Supplier:</b> 
        @if($purchaseDetails[0]->salepurchase->grn->inward_gatepass->supplier)
        {{ $purchaseDetails[0]->salepurchase->grn->inward_gatepass->supplier->party_name}}
         @else
         {{"No Record"}}
         @endif
         @if($purchaseDetails[0]->salepurchase->credit_to == "SUPPLIER")
         ({{"Credit"}})
         @endif
        </div>
        <div style="float:right;"><b>Purchaser:</b> 
        @if($purchaseDetails[0]->salepurchase->grn->inward_gatepass->purchaser)
        {{ $purchaseDetails[0]->salepurchase->grn->inward_gatepass->purchaser->party_name}}
         @else
         {{"No Record"}}
          @endif
          @if($purchaseDetails[0]->salepurchase->credit_to == "PURCHASER")
        ({{"Credit"}})
         @endif
        </div>
    </div>
   <div style="clear:both">
        <div style="float:left;"><b>Grn No: </b> @if($purchaseDetails[0]->salepurchase->grn){{ $purchaseDetails[0]->salepurchase->grn->voucher_no}} @else{{"No Record"}} @endif
        </div>
        <div style="float:right;"><b>Vehicle No#: </b> @if($purchaseDetails[0]->salepurchase->grn->inward_gatepass){{ $purchaseDetails[0]->salepurchase->grn->inward_gatepass->vehicle_no}} @else{{"No Record"}} @endif
        </div>
    </div>
   <div style="clear:both">
        <div style="float:left;"><b>Transport Company: </b> @if($purchaseDetails[0]->salepurchase->grn->inward_gatepass){{ $purchaseDetails[0]->salepurchase->grn->inward_gatepass->transport_company}}@else{{"No Record"}} @endif
        </div>
        <div style="float:right;"><b>Driver Name: </b> @if($purchaseDetails[0]->salepurchase->grn->inward_gatepass){{ $purchaseDetails[0]->salepurchase->grn->inward_gatepass->driver_name}}@else{{"No Record"}} @endif
        </div>
    </div>
   <div style="clear:both">
        <div style="float:left;"><b>Builty Number: </b> @if($purchaseDetails[0]->salepurchase->grn->inward_gatepass){{ $purchaseDetails[0]->salepurchase->grn->inward_gatepass->builty_no}}@else{{"No Record"}} @endif
        </div>
        <div style="float:right;"><b>Driver PhoneNo: </b> @if($purchaseDetails[0]->salepurchase->grn->inward_gatepass){{ $purchaseDetails[0]->salepurchase->grn->inward_gatepass->driver_phoneno}}@else{{"No Record"}}@endif
        </div>
    </div>
   <div style="clear:both">
        <div style="float:left;"><b>Remarks: </b> @if($purchaseDetails[0]->salepurchase->remarks){{ $purchaseDetails[0]->salepurchase->remarks}}@else{{"No Record"}} @endif
        </div>
    </div>
   <br>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Code</th>
                <th style="text-align: left;">Product Name</th>
                <th>Unit</th>
                <th>GRN.Qty</th>
                <th>Rec.Qty</th>
                <th>Rate</th>
                <!-- <th>Excl.val</th>
                <th>ST Rate</th>
                <th>Sale Tax</th> -->
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php 
            $grandtotal=0;
             $totalRecQty=0; 
             $totalrate=0;
             $totalexclval=0;
             $totalStrate=0;
             $totalSaletax=0;
             $totaldemandQty=0;
             @endphp
            @foreach ($purchaseDetails as $value)
                <tr style="border-top: 1px solid;">
                    <td>@if($value->product){{ $value->product->code }}@else{{ 'No Record' }} @endif</td>
                    <td style="text-align: left;">@if($value->product){{ $value->product->product_name }}@else{{ 'No Record' }} @endif</td>
                    <td>@if($value->product){{ $value->product->uom }}@else{{ 'No Record' }} @endif</td>
                    <td>{{ number_format($value->demandQty)}}</td> 
                    <td>{{ number_format($value->qty, 2)}}</td>
                    <td>{{ number_format($value->rate, 2)}}</td>
                    <!-- <td>{{ number_format($value->excl_val)}}</td>
                    <td>{{ $value->st_rate}}</td>
                    <td>{{ number_format($value->sale_tax)}}</td> -->
                    <td>{{ number_format($value->total)}}</td>
                </tr>
                @php
                 $totaldemandQty+=$value->demandQty; 
                 $totalRecQty+=$value->qty; 
                 $totalrate+=$value->rate; 
                 $totalexclval+=$value->excl_val; 
                 $totalStrate+=$value->st_rate; 
                 $totalSaletax+=$value->sale_tax; 
                 $grandtotal+=$value->total; 
                @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th>{{ number_format($totaldemandQty,2)}}</th>
                <th>{{ number_format($totalRecQty,2)}}</th>
                <th></th>
                <!-- <th>{{ number_format($totalexclval,2)}}</th>
                <th></th>
                <th>{{ number_format($totalSaletax,2)}}</th> -->
                <th>{{ number_format($grandtotal)}}</th>
               
            </tr>
        </tfoot>
    </table>
    @include('include.numberconvert')
  <span style="text-transform: capitalize; float:right">Rupees: {{ convertNumber($grandtotal) }}</span>
    <br /><br>
    <table>
        <tbody>
            <tr>
                <td colspan="5">Generated by:<b><u>{{ $purchaseDetails[0]->salepurchase->user->name }}</u></b></td>
                <td>Checked by:________________</td>
                <td>Approved by:_______________</td>
            </tr>
            <tr>
                <td colspan="5">Print:{{ date('d/m/Y') }} Time:{{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
