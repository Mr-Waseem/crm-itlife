<!DOCTYPE html>
<html>

<head>
    <title>PURCHASE ORDER VOUCHER</title>
    <style>
        #designed {
            border-collapse: collapse;
        }

        #designed thead tr th,
        #designed tbody tr td,
        #designed tfoot tr th{
            border-top: 2px solid black;
            border-bottom: 2px solid black;
            text-align: center;
        }

        #designed thead tr th {
            background-color: #D4D4D4 !important;
        }

        
        #designed1 tbody tr td,
        {
            text-align:center;
        }
       
    </style>
</head>

<body>
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    <h3>
        <center>PURCHASE ORDER VOUCHER </center>
    </h3>
    <hr /><br />
    <div style="clear:both">
        <p><b>Supplier (Billed From)</b></p>
        <div style="float: left;"><b>Supplier:</b> @if($purchaseOrderDetail[0]->purchaseorder->supplier){{ $purchaseOrderDetail[0]->purchaseorder->supplier->party_name}} @else{{"No Record"}}@endif</div>
        <div style="float: right;"><b> Date: </b>{{ date('d/m/Y', strtotime($purchaseOrderDetail[0]->purchaseorder->date)) }}</div>
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>Code:</b> @if($purchaseOrderDetail[0]->purchaseorder->supplier){{ $purchaseOrderDetail[0]->purchaseorder->supplier->code}} @else{{"No Record"}}@endif</div>
        <div style="float: right;"><b>Voucher.No: </b>{{$purchaseOrderDetail[0]->purchaseorder->bill_no }}</div>
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>Address:</b> @if($purchaseOrderDetail[0]->purchaseorder->supplier){{ $purchaseOrderDetail[0]->purchaseorder->supplier->address}} @else{{"No Record"}}@endif</div>
        <div style="float: right;"><b>Created By: </b>{{ $purchaseOrderDetail[0]->purchaseorder->user->name }}</div>
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>City:</b> @if($purchaseOrderDetail[0]->purchaseorder->supplier){{ $purchaseOrderDetail[0]->purchaseorder->supplier->city}} @else{{"No Record"}}@endif</div>
        <div style="float: right;"></div>
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>Phone:</b> @if($purchaseOrderDetail[0]->purchaseorder->supplier){{ $purchaseOrderDetail[0]->purchaseorder->supplier->phone}} @else{{"No Record"}}@endif</div>
        <div style="float: right;"></div>
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>Ntn:</b> @if($purchaseOrderDetail[0]->purchaseorder->supplier){{ $purchaseOrderDetail[0]->purchaseorder->supplier->ntn}} @else{{"No Record"}}@endif</div>
        <div style="float: right;"></div>
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>Strn:</b> @if($purchaseOrderDetail[0]->purchaseorder->supplier){{ $purchaseOrderDetail[0]->purchaseorder->supplier->strn}} @else{{"No Record"}}@endif</div>
        <div style="float: right;"></div>
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>Email:</b> @if($purchaseOrderDetail[0]->purchaseorder->supplier->user){{ $purchaseOrderDetail[0]->purchaseorder->supplier->user->email}} @else{{"No Record"}}@endif</div>
        <div style="float: right;"></div>
    </div>
   <br><br>
    <table id="designed" style=" width:100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Unit</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Excl.val</th>
                <th>ST Rate</th>
                <th>Sale Tax</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php 
            $grandtotal=0;
             $totalQty=0; 
             $totalrate=0;
             $totalexclval=0;
             $totalStrate=0;
             $totalSaletax=0;
             @endphp
            @foreach ($purchaseOrderDetail as $value)
                <tr>
                    <td>@if($value->product){{ $value->product->code }}@else{{ 'No Record' }} @endif</td>
                    <td>@if($value->product){{ $value->product->product_name }}@else{{ 'No Record' }} @endif</td>
                    <td>@if($value->product){{ $value->product->uom }}@else{{ 'No Record' }} @endif</td>
                    <td>{{ $value->qty}}</td>
                    <td>{{ $value->rate}}</td>
                    <td>{{ $value->excl_val}}</td>
                    <td>{{ $value->st_rate}}</td>
                    <td>{{ $value->sale_tax}}</td>
                    <td>{{ number_format($value->total)}}</td>
                </tr>
                @php
                 $totalQty+=$value->qty; 
                 $totalrate+=$value->rate; 
                 $totalexclval+=$value->excl_val; 
                 $totalStrate+=$value->st_rate; 
                 $totalSaletax+=$value->sale_tax; 
                 $grandtotal+=$value->total; 
                @endphp
            @endforeach

        </tbody>
        
    </table>
    <table  id ="designed1" style=" width:60%; float: right ; margin-top:5px;">
        <tbody>
            <tr>
                <td><b>Total Including Sales Tax</b></td>
                <td>{{ number_format($grandtotal) }}</td>
            </tr>
        </tbody>
    </table>
    <div style="width:100%;hight:10px; background-color:#D4D4D4; margin-top:40px;" >
        <h4 style="padding:5px;">Other Terms & Conditions
        </h4>
        <p style="padding:5px;">Payment Terms : 45 Days </p>
    </div>
    <div style="width:100%;hight:10px; background-color:#D4D4D4; margin-top:40px;" >
        <p style="margin-left: 10px;margin-top: 10px;"><b>Standard Terms & Conditions</b></p>
        <ol style="margin-top:-7px;">
            <li> All payments will be made as per rate approved on PO issuance date. Any price variation and adjustment after PO issuance will not
                considered valid.</li>
            <li>Delivery of all supplies will be made as per quantities mentioned on PO. Any changes in quantities, delivery schedule and delivery
                location will not be facilitated.
                </li>
            <li>Purchase Order with financial information is to be considered strictly confidential and should not be shared with the supply chain /
                delivery team under any circumstances; Spi Stores reserves the right to take suitable action against vendors in case this document is
                shared otherwise.
                </li>
            <li>Spi Stores reserves right to cancel any order or impose penalties related loss of sales in case of any deviation in agreed terms of
                delivery</li>
            <li>Quantitative PO submission is mandatory at time of delivery; PO number should be mentioned on all transactional documentation.</li>
            <li>GRN Acknowledged by Spi Stores Representative will be provided to vendor as an acknowledgement of delivery.</li>
            <li>Vendor will submit invoice along with acknowledged GRN at the time of delivery, unless agreed otherwise with the commercial team
                of Spi Stores.</li>
            <li>All delivered good should be in saleable condition.</li>
            <li>az Stores will not accept any access quantity and rejected goods</li>
            <li>Any charges related to transportation of goods to Spi Stores should not be the part of invoice and are to be bourne by vendor.</li>
            <li>Acceptance of this PO consitutes a valid contract.</li>
            <li>Payment terms will be effective from the date of subbmission of invoice on billing address.</li>
            <li>In case of any queries please reach us on Buyer@Spisupermarket.com.pk</li>
        </ol>
    </div>
    <div style="width:100%;">
    <div style="width:50%; float:left;">
        <p style="margin-left: 10px;margin-top: 10px;"><b><u>{{ SettingsFacade::data()->title }}&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</u></b></p>
        <p style="margin-left: 10px;"><b>A:</b> &nbsp; &nbsp;&nbsp;{{ SettingsFacade::data()->address }}</p>
        <p style="margin-left: 10px;"><b>U:</b> &nbsp; &nbsp;&nbsp;{{ SettingsFacade::data()->phone }}</p>
        <p style="margin-left: 10px;"><b>L:</b> &nbsp; &nbsp;&nbsp;{{ SettingsFacade::data()->phone }}</p>
        <p style="margin-left: 10px;"><b>W:</b> &nbsp; &nbsp;&nbsp;www.spi.com.pk</p>
    </div>
    <div style="width:50%;  float:right; margin-top: 32px;">
        <p style="margin-left: 20px;">Printed By: &nbsp; &nbsp;&nbsp;{{ $purchaseOrderDetail[0]->purchaseorder->user->name }}</p>
        <p style="margin-left: 20px;">Printed On: &nbsp; &nbsp;&nbsp; {{ date('d-m-Y h:i:sa')}}</>
    </div>
   </div>
    
</body>

</html>
