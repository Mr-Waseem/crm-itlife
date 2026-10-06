<!DOCTYPE html>
<html>

<head>
    <title>GENERAL JOURNAL</title>
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
    <!-- @if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    @endif -->
    <h1><center>{{ SettingsFacade::data()->title }}</center></h1>
    <h3><center>GENERAL JOURNAL</center></h3>
    <hr />
    <!-- <div style="clear:both">
        <div style="float: left;"><b>Account Name: </b></div>
        <div style="float: right;"><b>Account Code: </b></div>
    </div>
    <br /> -->
    <p style="text-align: center;">From date: {{date("d/m/Y", strtotime($fromDate))}} To Date: {{date("d/m/Y", strtotime($toDate))}}</p>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Date</th>
                <th>Vr.No</th>
                <th>Warehouse</th>
                <th>Code</th>
                <th>Account Name</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
            </tr>
        </thead>
        <tbody>
            @php $totaldebit = 0; $totalcredit = 0; @endphp
            @foreach($customerLedger as $data)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{date("d/m/Y", strtotime($data->date))}}</td>
                <td style="text-align:left;">
                    @if($data->v_type == "SALESTAX INVOICE")
                    {{"STI"}} - {{$data->voucher_no}}
                    @elseif($data->v_type == "Cash Receipt")
                    {{"CR"}} - {{$data->voucher_no}}
                    @elseif($data->v_type == "Cash Payment")
                    {{"CP"}} - {{$data->voucher_no}}
                    @elseif($data->v_type == "Bank Receipt")
                    {{"BR"}} - {{$data->voucher_no}}

                    @elseif($data->v_type == "Bank Payment")
                    {{"BP"}} - {{$data->voucher_no}}
                    @elseif($data->v_type == "Journal Voucher")
                    {{"JV"}} - {{$data->voucher_no}}
                    @elseif($data->v_type == "Opening Balance")
                    {{"OB"}} - {{$data->voucher_no}}
                    @elseif($data->v_type == "ISSUANCE")
                    {{"ISS"}} - {{$data->voucher_no}}
                    @elseif($data->v_type == "ISSUANCE RETURN")
                    {{"ISS-RET"}} - {{$data->voucher_no}}
                    @elseif($data->v_type == "SALE")
                    {{"SALE"}} - {{$data->voucher_no}}
                    @elseif($data->v_type == "SALE RETURN")
                    {{"SALE-RET"}} - {{$data->voucher_no}}
                    @elseif($data->v_type == "PURCHASE")
                    {{"PUR"}} - {{$data->voucher_no}}
                    @elseif($data->v_type == "PURCHASE RETURN")
                    {{"PUR-RET"}} - {{$data->voucher_no}}
                    @else
                    {{$data->v_type}} - {{$data->voucher_no}}
                    @endif
                </td>
                <td style="text-align:left;">{{$data->name}}</td>
                <td>{{$data->code}}</td>
                <td style="text-align:left;">{{$data->party_name}}</td>
                <td style="text-align:left;">{{$data->narration}}</td>
                <td style="text-align:right;">{{number_format($data->debit, 2)}}</td>
                <td style="text-align:right;">{{number_format($data->credit, 2)}}</td>
            </tr>
            @php 
                $totaldebit = $totaldebit + $data->debit;
                $totalcredit = $totalcredit + $data->credit;
            @endphp
            @endforeach
            <tr style="border-top: 1px solid;">
               <td colspan="7">Total</td>
                <td>{{number_format($totaldebit, 2)}}</td>
                <td>{{number_format($totalcredit, 2)}}</td>
            </tr>
        </tbody>
    <!-- <div style="float: left;width:33.3%;font-family:sans-serif;">Prepared: {{Auth::User()->name}}</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Checked: __________</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Authorized: __________</div>
    <p><small style="font-family:sans-serif;">Print Date:{{ date('d/m/Y') }} Time:{{ date('h:i:s A') }}</small></p> -->
</body>
</html>
