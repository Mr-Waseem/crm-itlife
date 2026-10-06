<!DOCTYPE html>
<html>

<head>
    <title> PRODUCTION </title>
    <style>
        #designed {
            border-collapse: collapse;
        }

        #designed thead tr th,
        #designed tbody tr td {
            border-right: 1px solid black;
            /* text-align: center; */
            font-size: 10px;
        }

        #designed thead tr th {
            border-bottom: 1px solid black;
            font-size: 10px;
        }

        #designed tfoot tr th {
            border-top: 1px solid black;
            border-right: 1px solid black;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    <h3>
        <center>THERMOFORMING PRODUCTION</center>
    </h3>
    <hr /><br />

    <!-- <div style="clear:both">
        <div style="float:left;"><b>Voucher No: </b></div>
         <div style="float:right;"><b>Voucher Date:</b>  </div>
    </div> -->
    <!-- <div style="clear:both">
        <div style="float:left;"><b>Remarks: </b> 3</div>
    </div>
    <br /><br /> --><br />
     <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>  
                <th>Date</th>
                <th align="left">Batch#</th>
                <!-- <th>Thick Width</th> -->
                <th>Net Weight</th>
                <th>Consumed</th>
                <th>Color</th>
                <th>Product&nbsp;Name&nbsp;&&nbsp;SKU</th>
                <th>Dye PCS</th>
                <th>Oper ID</th>
                <th>Machine</th>
                <th>PM ID</th>
                <th>P No</th>
                <th>Sheets</th>
                <th>Check</th>
                <th>Waste</th>
                <th>Net S</th>
                <th>Net SKU</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sum = 0;
                $NetWeight = 0;
                $Consumed = 0;
                $TotalSheets = 0;
                $CheckSheets = 0;
                $Wastage = 0;
                $NetSheets = 0;
                $NetSKU = 0;
            @endphp
            @foreach($production as $data)
            @php
            $NetWeight = $NetWeight + $data->roll_production->total_qty;
            $Consumed = $Consumed + $data->consumed;
            $TotalSheets = $TotalSheets + $data->total_sheets;
            $CheckSheets = $CheckSheets + $data->check_sheets;
            $Wastage = $Wastage + $data->wastage;
            $NetSheets = $NetSheets + $data->net_sheets;
            $NetSKU = $NetSKU + $data->net_sku;
            @endphp
                <tr>
                    <td style="font-size: 10px;" align="center">{{date("d/m/y", strtotime($data->date))}}</td>
                    <td style="font-size: 8px !important;">{{$data->roll_production->batchNo}}
                    <br/>
                    {{number_format($data->roll_production->thickness)}}x{{$data->roll_production->width}}
                    </td> 
                    <!-- <td align="center">{{$data->roll_production->thickness}}x{{$data->roll_production->width}}</td> -->
                    <td align="center">{{$data->roll_production->total_qty}}</td>
                    <td align="center">{{$data->consumed}}</td>
                    <td align="center">{{$data->roll_production->color}}</td>
                    <td style="font-size: 8px !important;">{{$data->product->code}}-{{$data->product->product_name}}</td>
                    <td align="center">{{$data->product->dye_pcs}}</td>
                    <td align="center">{{$data->operator->code}} - {{$data->operator->party_name}}</td>
                    <td align="center">{{$data->machine->machine_name}}</td>
                    <td align="center">{{$data->pressman->code}} - {{$data->pressman->party_name}}</td>
                    <td align="center">{{number_format($data->pressman_no)}}</td>
                    <td align="center">{{number_format($data->total_sheets)}}</td>
                    <td align="center">{{$data->check_sheets}}</td>
                    <td align="center">{{number_format($data->wastage)}}</td>
                    <td align="center">{{number_format($data->net_sheets)}}</td>
                    <td align="center">{{number_format($data->net_sku)}}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr align="center">
                <th colspan="2">Total</th>
               
                <th>{{number_format($NetWeight)}}</th>
                <th>{{number_format($Consumed)}}</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>{{number_format($TotalSheets)}}</th>
                <th>{{number_format($CheckSheets)}}</th>
                <th>{{number_format($Wastage)}}</th>
                <th>{{number_format($NetSheets)}}</th>
                <th>{{number_format($NetSKU)}}</th>
            </tr>
        </tfoot>
    </table>
    <br />
    <table>
        <tbody>
            <!-- <tr>
                <td>Signature: __________</td>
            </tr><br />
            <tr>
                <td>Name & Designation: __________</td>
            </tr> -->
            <tr>
                <td>Generated by:<b><u>{{Auth::user()->name}}
                  
                
                </u></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>Checked by:<b><u></u></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>Approved by:<b><u></u></b></td>
            </tr>
            <tr>
                <td colspan="5">Print:{{ date('d/m/Y') }}  {{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
