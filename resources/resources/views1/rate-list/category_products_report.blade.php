<!DOCTYPE html>
<html>

<head>
    <title>CATEGORY PRODUCTS RATE LIST REPORT</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table thead tr th,
        table tbody tr td {
            border: 1px solid black;
            font-size: 14px;
        }

        .report-title {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1 class="report-title">{{ SettingsFacade::data()->title }}</h1>
    <h3 class="report-title">RATE LIST VOUCHER</h3>
    @foreach ($data as $value)
        <table>
            <thead>
                <tr style="padding-top:10px;padding-bottom:10px;">
                    <th colspan="5">
                        <br />
                        {{ $value->code }} --  {{ $value->product_name }}
                        <br /><br />
                    </th>
                </tr>
                <tr>
                    <th>V.No</th>
                    <th>V.Date</th>
                    <th>Previous Rate</th>
                    <th>New Rate</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($value->rate_list_details as $rate_list_details)
                <tr align="center">
                    <td>{{ number_format($rate_list_details->rate_list->voucher_no) }}</td>
                    <td>{{ date('d/m/Y',strtotime($rate_list_details->rate_list->voucher_date)) }}</td>
                    <td>{{ number_format($rate_list_details->previous_rate) }}</td>
                    <td>{{ number_format($rate_list_details->new_rate) }}</td>
                    <td>{{ $rate_list_details->remarks }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>

</html>
