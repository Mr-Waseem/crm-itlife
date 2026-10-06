<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chart Of Account</title>
    <style>
        table{
            border-collapse: collapse;
        }
        table tbody tr th,
        table tbody tr td {
            padding: 7px !important;
            /* font-size: 12px; */
        }
    </style>
</head>

<body>
    <h1 style="text-align: center;margin-top:-10px;">Chart Of Account</h1>
    <table border="1" style="width:100%;">
        {{-- <thead>
            <tr>
                <th>Sr.</th>
                <th>Code</th>
                <th>Name</th>
                <th>AG</th>
                <th>AG 2</th>
                <th>AG 3</th>
            </tr>
        </thead> --}}
        {{-- <tbody>
            @foreach ($party as $key => $data)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $data->code }}</td>
                    <td>{{ $data->party_name }}</td>
                    <td>{{ $data->account_group->name }}</td>
                    <td>{{ $data->account_group2->name }}</td>
                    <td>{{ $data->account_group3->name }}</td>
                </tr>
            @endforeach
        </tbody> --}}
        <tbody>
            @foreach ($parties as $ag1)
            <tr>
                <td style="padding-left: 10px!important;">
                    <strong><span style="margin-right:10px;margin-left:10px;">Level 1: </span> {{ $ag1->name }}</strong>
                    @foreach ($ag1->account_group_2 as $ag2)
                    <tr>
                        <td><span style="margin-left:40px;margin-right:10px;">Level 2: </span> {{ $ag2->name }}
                            @foreach ($ag2->account_group_3 as $ag3)
                            <tr>
                                <td>
                                    <span style="margin-left:50px;margin-right:10px;">Level 3: </span> {{ $ag3->name }}
                                    @foreach ($ag3->parties as $party)
                                    <tr>
                                        <td>
                                            <span style="margin-left:70px;margin-right:10px;">Level 4: </span>
                                            {{ $party->code }}
                                             <span style="margin-left:10px;margin-right:10px;">--</span>
                                            {{ $party->party_name }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td>------------------------------------------------------------------------------------------------------</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
