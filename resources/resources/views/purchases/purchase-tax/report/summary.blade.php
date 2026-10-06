<!DOCTYPE html>
<html>

<head>
    <title>PURCHASE TAX REPORT(SUMMARY)</title>
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/report1.css') }}">
</head>
<body>
    <!-- @if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1><center>{{ SettingsFacade::data()->title }}</center></h1>
    @endif -->
    <h1><center>{{ SettingsFacade::data()->title }}</center></h1>
    <h3><center>PURCHASE TAX REPORT (SUMMARY)</center></h3>
    <hr />
    <div style="clear:both">
        <div style="float: left;">
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
                <th>Vr#</th>
                <th>Date</th>
                <th>GRN#</th>
                <th>Supplier Name</th>
                <th>Purchaser Name</th>
                <th>Credit</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Excl.Val</th>
                <th>ST%</th>
                <th>ST.Val</th>
                <th>Amount</th>
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
                @php $sum = 0; $TotalQty = 0; $exclusive =0; $saletax=0; $TotalAmount = 0; @endphp
                @foreach($PurchaseSummary as $data)
                @php 
                    $sum += 1; 
                    $TotalQty += $data->qty; 
                    $exclusive += $data->excl_val; 
                    $saletax += $data->sale_tax; 
                    $TotalAmount += $data->total; 
                @endphp
                <tr>
                    <td>{{$sum}}</td>
                    <td>{{$data->voucher_no}}</td>
                    <td>{{date("d/m/Y", strtotime($data->date))}}</td>
                    <td>{{$data->grnNo}}</td>
                    <td style="text-align:left;">{{$data->supplier_name}}</td>
                    <td style="text-align:left;">{{$data->purchaser_name}}</td>
                    <td>{{$data->credit_to}}</td>
                    <td>{{number_format($data->qty, 2)}}</td>
                    <td>{{number_format($data->rate, 2)}}</td>
                    <td>{{number_format($data->excl_val, 2)}}</td>
                    <td>{{number_format($data->st_rate, 2)}}</td>
                    <td>{{number_format($data->sale_tax, 2)}}</td>
                    <td>{{number_format($data->total, 2)}}</td>
                </tr>
            @endforeach

        </tbody>
        <tr style="border-top: 1px solid;">
            <td colspan="7">Total</td>
          
            <td>{{number_format($TotalQty, 2)}}</td>
            <td></td>
            <td>{{number_format($exclusive, 2)}}</td>
            <td></td>
            <td>{{number_format($saletax, 2)}}</td>
            <td>{{number_format($TotalAmount, 2)}}</td>
        </tr>
    </table>
    @include('include.numberconvert')

    <!-- <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: </span> -->
    <!-- <span style="text-transform: capitalize; float:right">Qty in Words: {{ convertNumber($TotalQty) }}</span> -->
    <br /><br>
    <br />
    <div style="float: left;width:33.3%;font-family:sans-serif;">Prepared: {{Auth::User()->name}}</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Checked: __________</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Authorized: __________</div>
    <p><small style="font-family:sans-serif;">Print Date:{{ date('d/m/Y') }} : {{ date('h:i:s A') }}</small></p>
</body>
</html>
