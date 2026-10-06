<!DOCTYPE html>
<html>
<head>
    <title>IGP REPORT(DETAIL)</title>
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/report.css') }}">
</head>
<body>
    <!-- @if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1><center>{{ SettingsFacade::data()->title }}</center></h1>
    @endif -->
    <h1> <center>{{ SettingsFacade::data()->title }}</center></h1>
    <h3><center>IGP REPORT(DETAIL)</center></h3>
    <hr />
    <div style="clear:both">
        <div style="float: left;" class="headings">
        @if($warehousName)
        <b>WAREHOUSE:</b> {{$warehousName->name}} || &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        @else
        <b>WAREHOUSES:</b> {{"ALL"}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @endif
        @if($supplier)
        <b>SUPPLIER:</b> {{$supplier->code}} - {{$supplier->party_name}} || &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        @else
        <b>SUPPLIER:</b> {{"ALL"}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @endif
        @if($purchaser)
        <b>PURCHASER:</b> {{$purchaser->code}} - {{$purchaser->party_name}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @else
        <b>PURCHASER:</b> {{"ALL"}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @endif
        @if($product)
        <b>PRODUCT:</b> {{$product->code}} - {{$product->product_name}}
        @else
        <b>PRODUCT:</b> {{"ALL"}}
        @endif
    
        <!-- <div style="float: right;"><b>Purchaser Code: </b></div> -->
    </div>
    <br />
    <div style="clear:both">
    <center><span>From: {{date("d/m/Y", strtotime($fromDate))}} To: {{date("d/m/Y", strtotime($toDate))}} </span></center>
    </div>
    <br />
    <!-- <div style="clear:both">
        <div style="float: left;"><b>Customer Name: </b></div>
        <div style="float: right;"><b>Customer Code: </b></div>
    </div>
    <br />
    <div style="clear:both">
        <div style="float:left;"><b>Phone: </b> 
        </div>
        <div style="float:right;"><b>City:</b> </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Address: </b> 
        </div>
    </div> -->
    <!-- <div style="clear:both">
        <div style="float:left;"><b>Product Code: </b> DFDSFDS
        </div>
        <div style="float:right;"><b>Godown: </b>
           DFDSFD
        </div>
    </div> -->
    <!-- <br /><br /> -->
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Date</th>
                <th>Vr.No</th>
                <th>Warehouse</th>
                <th>Req#</th>
                <th>Supplier&nbsp;Name</th>
                <th>Purchaser&nbsp;Name</th>
                <th>Code</th>
                <th>Product&nbsp;Name</th>
                <th>Quantity</th>
                <th>Vehicle</th>
                <th>T.Company</th>
                <th>D.Name</th>
                <th>Builty#</th>
                <th>D.Phone</th>
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
                @foreach($IGPSummary as $data)
                @php 
                    $sum += 1; 
                    $TotalQty += $data->qty; 
                @endphp
                <tr>
                    <td>{{$sum}}</td>
                    <td>{{date("d/m/Y", strtotime($data->date))}}</td>
                    
                    <td>{{$data->bill_no}}</td>
                    <td>{{$data->warehouse_name}}</td>
                    <td>{{$data->request_no}}</td>
                    <td>{{$data->supplier_name}}</td>
                    <td>{{$data->purchaser_name}}</td>
                    <td>{{$data->code}}</td>
                    <td>{{$data->product_name}}</td>
                    <td>{{number_format($data->qty, 2)}}</td>
                    <td>{{$data->vehicle_no}}</td>
                    <td>{{$data->transport_company}}</td>
                    <td>{{$data->driver_name}}</td>
                    <td>{{$data->builty_no}}</td>
                    <td>{{$data->driver_phoneno}}</td>
                </tr>
            @endforeach

        </tbody>
        <tr style="border-top: 1px solid;">
            <td colspan="9">Total</td>
          
            <td>{{number_format($TotalQty, 2)}}</td>
            <td colspan="5">Total</td>
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
