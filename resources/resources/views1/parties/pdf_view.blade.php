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
        <tbody>
            @foreach ($parties as $ag1)
            <tr>
                <td style="padding-left: 10px!important;">
                    <strong style="color:green;"><span style="margin-right:10px;margin-left:10px;"></span>{{ $ag1->code }} - {{ $ag1->name }}</strong>
                    @foreach ($ag1->account_group_2 as $ag2)
                    <tr>
                        <td style="color:blue;"><span style="margin-left:40px;margin-right:10px;">{{ $ag2->code }} - {{ $ag2->name }}</span>
                            @foreach ($ag2->account_group_3 as $ag3)
                            <tr>
                                <td style="color:purple;">
                                    <span style="margin-left:50px;margin-right:10px;">{{ $ag3->code }} - {{ $ag3->name }}</span>
                                    @foreach ($ag3->parties as $party)
                                    <tr>
                                        <td>
                                            <span style="margin-left:70px;margin-right:10px;">{{ $party->code }}</span>

                                             <span style="margin-left:10px;margin-right:10px;"> {{ $party->party_name }}</span>

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
