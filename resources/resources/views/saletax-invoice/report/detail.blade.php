<!DOCTYPE html>
<html>

<head>
    <title>SALESTAX REPORT (DETAIL)</title>
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/invoice.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 10px;
            color: #333;
        }
        h1 {
            font-size: 22px;
            margin: 10px 0;
            text-align: center;
            font-weight: bold;
        }
        h3 {
            font-size: 16px;
            margin: 10px 0;
            text-align: center;
            font-weight: bold;
        }
        .header-info {
            margin: 15px 0;
            font-size: 13px;
        }
        .header-info div {
            margin: 5px 0;
        }
        .date-range {
            text-align: center;
            margin: 10px 0;
            font-size: 13px;
            font-weight: bold;
        }
        table#designed {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            border: 2px solid #333;
        }
        table#designed thead {
            background-color: #4472C4;
            color: white;
        }
        table#designed th {
            padding: 12px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            border: 1px solid #333;
        }
        table#designed tbody td {
            padding: 8px;
            border: 1px solid #999;
            font-size: 11px;
        }
        table#designed tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }
        table#designed tbody tr:hover {
            background-color: #e8f0f8;
        }
        .subtotal-row {
            background-color: #e2efda;
            font-weight: bold;
        }
        .subtotal-row td {
            padding: 8px;
            border: 1px solid #333;
            font-size: 11px;
        }
        .grandtotal-row {
            background-color: #d9e1f2;
            font-weight: bold;
            border: 2px solid #333;
        }
        .grandtotal-row td {
            padding: 10px;
            border: 2px solid #333;
            font-size: 12px;
        }
        .footer-info {
            margin-top: 20px;
            font-size: 12px;
            width: 100%;
        }
        .footer-info div {
            display: inline-block;
            width: 32%;
            text-align: center;
            vertical-align: top;
        }
        .print-date {
            margin-top: 15px;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>

<body>
    <h1>{{ SettingsFacade::data()->title }}</h1>
    <h3>SALESTAX REPORT (DETAIL)</h3>
    <hr style="border: none; border-top: 2px solid #333; margin: 10px 0;">
    
    @if(isset($customer))
    <div class="header-info">
        <div><b>Account Name:</b> {{$customer->party_name}}</div>
        <div><b>Account Code:</b> {{$customer->code}}</div>
    </div>
    @endif
 
    <div class="date-range">From date: {{date("d/m/Y", strtotime($fromDate))}} To Date: {{date("d/m/Y", strtotime($toDate))}}</div>
    
    <table id="designed">
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Date</th>
                <th>Voucher#</th>
                <th>Product Name</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Excl.Value</th>
                <th>Tax.Value</th>
                <th>Amount</th>
                <th>Account Name</th>
                <th>NTN</th>
            </tr>
        </thead>
        <tbody>
       @php $grandqty = 0; $qrandexclusive = 0; $grandtaxvalue =0; $grandAmount = 0; $rowNum = 1; @endphp
        @foreach($summaryReport as $data1)
        @php $totalqty = 0; $totalexclusive = 0; $totalTax = 0; $totalAmount = 0; @endphp
        @foreach($data1->sale_purchase_details as $data)
            <tr>
                <td style="text-align: center;">{{$rowNum}}</td>
                <td style="text-align: center;">{{date("d/m/Y", strtotime($data->date))}}</td>
                <td>{{$data->voucher_no}} - 
                @if($data->type == "DIRECT SALESTAX INVOICE") 
                    {{"DST"}}
                    @elseif($data->type == "SALESTAX INVOICE")
                    {{"ST"}}
                    @elseif($data->type == "SALESTAX RETURN")
                    {{"STR"}}
                    @else
                    {{$data->type}}
                    @endif
                </td>
                <td>{{$data->product->code}} - {{$data->product->product_name}}</td>
                <td style="text-align: right;">{{number_format($data->qty, 2)}}</td>
                <td style="text-align: right;">{{number_format($data->rate, 2)}}</td>
                <td style="text-align: right;">{{number_format($data->excl_val, 2)}}</td>
                <td style="text-align: right;">{{number_format($data->sale_tax, 2)}}</td>
                <td style="text-align: right;">{{number_format($data->total, 2)}}</td>
                <td>{{$data1->party->party_name}}</td>
                <td style="text-align: center;">{{$data1->party->ntn}}</td>
            </tr>
            @php 
            $totalqty += $data->qty; 
            $totalexclusive += $data->excl_val; 
            $totalTax += $data->sale_tax; 
            $totalAmount += $data->total; 
            $rowNum++;
            @endphp
        @endforeach
        <tr class="subtotal-row">
            <td colspan="4" style="text-align: right;"><b>Subtotal</b></td>
            <td style="text-align: right;"><b>{{number_format($totalqty, 2)}}</b></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><b>{{number_format($totalexclusive, 2)}}</b></td>
            <td style="text-align: right;"><b>{{number_format($totalTax, 2)}}</b></td>
            <td style="text-align: right;"><b>{{number_format($totalAmount, 2)}}</b></td>
            <td colspan="2"></td>
        </tr>

        @php 
        $grandqty += $totalqty; 
        $qrandexclusive += $totalexclusive; 
        $grandtaxvalue += $totalTax; 
        $grandAmount += $totalAmount; 
        @endphp
        @endforeach
        </tbody>
        <tr class="grandtotal-row">
            <td colspan="4" style="text-align: right;"><b>GRAND TOTAL</b></td>
            <td style="text-align: right;"><b>{{number_format($grandqty, 2)}}</b></td>
            <td style="text-align: right;"></td>
            <td style="text-align: right;"><b>{{number_format($qrandexclusive, 2)}}</b></td>
            <td style="text-align: right;"><b>{{number_format($grandtaxvalue, 2)}}</b></td>
            <td style="text-align: right;"><b>{{number_format($grandAmount, 2)}}</b></td>
            <td colspan="2"></td>
        </tr>
    </table>
    <!-- @include('include.numberconvert')
    <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: {{ convertNumber(abs(23333)) }} Only/--</span> -->
    <br />
    <div class="footer-info">
        <div><b>Print By:</b> {{ optional(Auth::user())->name ?? 'N/A' }}</div>
        <div><b>Checked:</b> __________</div>
        <div><b>Authorized:</b> __________</div>
    </div>
    <p class="print-date"><b>Print Date:</b> {{ date('d/m/Y') }} | <b>Time:</b> {{ date('h:i:s A') }}</p>
</body>
</html>
