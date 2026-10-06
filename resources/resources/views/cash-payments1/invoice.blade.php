<!DOCTYPE html>
<html>

<head>
    <title>CASH PAYMENTS VOUCHER</title>
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
            border-top: 0px;
            border-left:  0px;
            border-right:  0px;
            font-size: 14px;
        }
        #designed tbody tr td{
            border-left: 1px solid black;
            border-bottom: 1px solid black;
        }

        #designed tfoot tr th {
            border-top: 1px solid black;
            border-right: 1px solid black;
            border-left: 1px solid black;
            border-bottom: 1px solid black;
            font-size: 14px;
        }
        #title{
            border: 1.5px solid;
            background-color:lightblue;
        }
        #voucher{
            border: 1.5px solid;
            background-color: lightblue;
            margin-top: -3%;
        }
    </style>
</head>

<body>
@if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1 id="title"><center>{{ SettingsFacade::data()->title }}</center></h1>
    @endif
    <h3 id="voucher">
        <center>CASH PAYMENT VOUCHER</center>
    </h3>
    <br />
    <div style="clear:both">
        <div style="float: left;"><b>Voucher.No: </b>{{ $generalVoucher[0]->voucher_no }}</div>
        <div style="float: right;"><b>Voucher
                Date: </b>{{ date('d/m/Y', strtotime($generalVoucher[0]->voucher_date)) }}</div>
    </div>

    <div style="clear:both">
    <div style="float:left;"><b>Payment Type: CASH IN HAND</div>
         <div style="float:right;"><b>Warehouse:</b> {{$generalVoucher[0]->warehouse->name}}</div>
    </div><br><br>
    <table id="designed" style="width:100%;">
        <thead>
            <tr> 
                <th>Code</th>
                <th style="text-align:left;">Party Name</th>
                <th style="text-align:left;">Description</th>
                <th style="text-align:right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $total=0; @endphp
            @foreach ($generalVoucher[0]->voucher_details as $value)
                <tr>
                    <td>{{ $value->parties->code }}</td>
                    <td style="text-align:left;">{{ $value->parties->party_name }}</td>
                    <td style="text-align:left;">{{ $value->narration }}</td>
                    <td style="text-align:right;">{{ number_format($value->debit, 2) }}</td>
                </tr>
                @php $total+=$value->debit; @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th style="text-align:right;">{{ number_format($total, 2) }}</th>
            </tr>
        </tfoot>
    </table>
    @include('include.numberconvert')
    <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: {{ convertNumber($total) }} Only/-</span>
    <br /><br>
    <table>
        <tbody>
            <tr>
                @if($generalVoucher[0]->billers)
                <td>Prepared by:<b><u>{{$generalVoucher[0]->billers->name}}&nbsp;&nbsp;</u></b></td>
                @else
                <td>Prepared by:<b><u>____________</u></b></td>
                @endif
                <td>Finance:<b><u>_____________</u></b></td>
                <td>Authorized:<b><u>____________</u></b></td>
                <td>Recipient:<b><u>____________</u></b></td>
            </tr>
            <tr>
                <td colspan="5">Print Date: {{ date('d/m/Y') }} || {{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
