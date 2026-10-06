<!DOCTYPE html>
<html>
<head>
    <title>BATCH STOCK REPORT</title>
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/report.css') }}">
</head>
<body>
    <!-- @if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1><center>{{ SettingsFacade::data()->title }}</center></h1>
    @endif -->
    @if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1><center>{{ SettingsFacade::data()->title }}</center></h1>
    @endif
    <h3><center>BATCH STOCK REPORT</center></h3>
    <hr />
    <div style="clear:both">
        <div style="float: left;">
        @if($warhouse)
        <b>Warehouse:</b> {{$warhouse->name}} || &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        @else
        <b>Warehouses:</b> {{"ALL"}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @endif

        @if($machine)
        <b>Machine:</b> {{$machine->machine_name}} || &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        @else
        <b>Machines:</b> {{"ALL"}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @endif

        @if($shift)
        <b>Shift:</b> {{$shift->shift_name}} || &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        @else
        <b>Shifts:</b> {{"ALL"}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @endif
        @if($forman)
        <b>Forman:</b> {{$forman->code}} - {{$forman->party_name}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @else
        <b>Formans:</b> {{"ALL"}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @endif
        @if($color)
        <b>Color:</b> {{$color->name}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @else
        <b>Colors:</b> {{"ALL"}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @endif

        @if($thickness)
        <b>Thickness:</b> {{$thickness}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @else
        <b>Thickness:</b> {{"ALL"}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @endif

        @if($product)
        <b>Product:</b> {{$product->code}} - {{$product->product_name}}
        @else
        <b>Products:</b> {{"ALL"}}
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
                <th>Vr#</th>
                <th>From</th>
                <th>To</th>
                <th>M#</th>
                <th>Shift</th>
                <th>Foreman</th>
                <th>Opt</th>
                <th>Color</th>
                <th>Batch#</th>
                <th>Width</th>
                <th>Thickness</th>
                <th>Gross<br/>Weight</th>
                <th>Net<br/>Weight</th>
                <th>Transfer<br/>Weight</th>
                
            </tr>
        </thead>
        <tbody>
            @if(count($stock) > 0)
                @php $sum = 0; $GrossWeight = 0; $TotalQty = 0; $OutWeight = 0; @endphp
                @foreach($stock as $data)
                @php 
                    $sum += 1; 
                    $TotalQty += $data->net_weight; 
                    $GrossWeight += $data->gross_weight; 
                    $OutWeight += $data->total_out; 
                @endphp
                <tr>
                    <td>{{$sum}}</td>
                    <td>{{date("d/m/Y", strtotime($data->date))}}</td>
                    <td>
                        {{$data->voucher_no}} - 
                        {{$data->p_type}}
                    </td>
                    <td>
                        @if($data->from_warehouse)
                        {{$data->from_warehouse}}
                        @endif
                    </td>
                    <td>
                        @if($data->to_warehouse)
                        {{$data->to_warehouse}}
                        @endif
                    </td>
                    <td>{{$data->machine_name}}</td>
                    <td>{{$data->shift_name}}</td>
                    <td style="text-align:left;">{{$data->forman}}</td>
                    <td style="text-align:left;">{{$data->operator}}</td>
                    <td>{{$data->color}}</td>
                    <td>{{$data->batchNo}}</td>
                    <td>{{$data->width}}</td>
                    <td>{{$data->thickness}}</td>
                    <td>{{number_format($data->gross_weight, 2)}}</td>
                    <td>{{number_format($data->net_weight, 2)}}</td>
                    <td>{{number_format($data->total_out, 2)}}</td>
                    
                </tr>
                @endforeach
                </tbody>
                <tr style="border-top: 1px solid;">
                    <td colspan="13">Total</td>
                    <td>{{number_format($GrossWeight, 2)}}</td>
                    <td>{{number_format($TotalQty, 2)}}</td>
                    <td>{{number_format($OutWeight, 2)}}</td>
                    
                </tr>
                    <!-- @include('include.numberconvert') -->

    <!-- <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: </span> -->
    <!-- <span style="text-transform: capitalize; float:right">Qty in Words: {{ convertNumber($TotalQty) }}</span> -->
            @else
            <tr style="border-top: 1px solid;">
                <td colspan="16" style="text-align:center;">Records Not Found!</td>
            </tr>
            @endif
    </table>

    <br /><br>
    <br />
    <div style="float: left;width:33.3%;font-family:sans-serif;">Prepared: {{Auth::User()->name}}</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Checked: __________</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Authorized: __________</div>
    <p><small style="font-family:sans-serif;">Print Date:{{ date('d/m/Y') }} : {{ date('h:i:s A') }}</small></p>
</body>
</html>
