<!DOCTYPE html>
<html>

<head>
    <title>RATE LIST VOUCHER</title>
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
        <center>RATE LIST VOUCHER</center>
    </h3>
    <div style="clear:both">
        <div><b>Document.No: </b>{{ $data->voucher_no }} || <b>Tax Rate:</b> {{ number_format($data->tax_rate, 2) }}</div>
        <div><b>Voucher.Date: </b>{{ date('d/m/Y', strtotime($data->voucher_date)) }}</div>
    </div>
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Packing</th>
                <th>Previous Rate</th>
                <th>New Rate</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->rate_list_details as $value)
                <tr>
                    <td>{{ $value->product->product_code }}</td>
                    <td>{{ $value->product->product_name }}</td>
                    <td>{{ $value->product->packing }}</td>
                    <td>{{ number_format($value->product->product_price) }}</td>
                    <td>{{ number_format($value->product->product_price) }}</td>
                    <td>{{ $value->product->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
