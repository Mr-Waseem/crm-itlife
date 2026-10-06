<!DOCTYPE html>
<html>

<head>
    <title>OPEN BALANCE</title>
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
        <center>OPEN BALANCE</center>
    </h3>
    <hr /><br />
    <div style="clear:both">
       
        <div style="float: left;"><b>Voucher.No: </b>{{ $openbalance[0]->voucher->voucher_no }}</div>
    </div>

    <div style="clear:both">
        <div style="float: right; margin-top:-20px;"><b>Voucher
                Date: </b>{{ date('d/m/Y', strtotime($openbalance[0]->voucher->voucher_date)) }}</div>
    </div>
   
    <br>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Party Name</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalcredit = 0;
                $totaldebit = 0;
            @endphp
            @foreach ($openbalance as $value)
                {{-- @if ($value->credit !== 0) --}}
                <tr>
                    <td>{{ $value->parties->party_name }}</td>
                    <td>{{ $value->narration }}</td>
                    <td>{{ $value->debit }}</td>
                    <td>{{ $value->credit }}</td>
                </tr>
                @php
                    $totalcredit += $value->credit;
                    $totaldebit += $value->debit;
                    
                @endphp
                {{-- @endif --}}
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <!-- <th>Unit</th> -->
                <th>{{ $totaldebit }}</th>
                <th>{{ $totalcredit }}</th>

            </tr>
        </tfoot>
    </table>
    <br />
    <table>
        <tbody>
            <tr>
                <td>Signature: __________</td>
            </tr><br />
            <tr>
                <td>Name & Designation: __________</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
