<!DOCTYPE html>
<html>

<head>
    <title>PURCHASE ORDER VOUCHER</title>
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
        <center>PURCHASE ORDER VOUCHER</center>
    </h3>

    <hr /><br />
    <div style="clear:both">
        <div style="float: left;"><b>Dept: </b>@if($RequestGenerateDetails[0]->request_generate->warehouse){{ $RequestGenerateDetails[0]->request_generate->warehouse->name }} @else{{ "No Dept" }}@endif</div>
        <div style="float: right;"><b>Request#: </b>{{ $RequestGenerateDetails[0]->request_generate->request_no }}</div>
    </div>
    <div style="clear:both">
        <div style="float: left;"><b>PO.No: </b>{{ $RequestGenerateDetails[0]->request_generate->po }}</div>
        <div style="float: right;"><b>Voucher
                Date: </b>{{ date('d/m/Y', strtotime($RequestGenerateDetails[0]->request_generate->date)) }}</div>
    </div>
    <br />
    <div style="clear:both">
        <div style="float:left;"><b>Supplier: </b>@if($RequestGenerateDetails[0]->request_generate->supplier) {{  $RequestGenerateDetails[0]->request_generate->supplier->party_name  }}
            @else{{ "No Supplier" }}
            @endif
        </div>
         <div style="float:right;"><b>Purchaser:</b> @if($RequestGenerateDetails[0]->request_generate->purchaser){{  $RequestGenerateDetails[0]->request_generate->purchaser->party_name  }}
            @else{{ "No Purchaser" }}
            @endif
        </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Remarks: </b>@if($RequestGenerateDetails[0]->request_generate) {{  $RequestGenerateDetails[0]->request_generate->remarks }}
            @else{{ "No Remarks" }}
            @endif
        </div>
    </div>
    <br /><br />
     <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>  
                <th>Code</th>
                <th>Product</th>
                <th>Unit</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Excl.val</th>
                <th>ST.Rate</th>
                <th>Sale Tax</th>
                <th>Comment</th>
            </tr>
        </thead>
        <tbody>
            @php $total=0; $totalexclval=0; $totalSaletax=0; @endphp
            @foreach ($RequestGenerateDetails as $value)
                <tr>
                    <td>@if($value->product){{ $value->product->code }} @else{{ 'NO Code' }} @endif</td> 
                     <td>@if($value->product){{ $value->product->product_name }} @else{{ 'NO Product' }}@endif</td> 
                     <td>@if($value->product){{ $value->product->uom }} @else{{ 'NO Unit' }} @endif</td> 
                    <td>{{ $value->qty }}</td>
                    <td>{{ $value->rate}}</td>
                    <td>{{ $value->excl_val}}</td>
                    <td>{{ $value->st_rate}}</td>
                    <td>{{ $value->sale_tax}}</td>
                    <td>{{ $value->comments }}</td>
                </tr>
                 @php 
                 $total+=$value->qty; 
                 $totalexclval+=$value->excl_val; 
                 $totalSaletax+=$value->sale_tax; 
                 
                 @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <th></th>
                <th>{{ number_format($total,2) }}</th>
                <th></th>
                <th>{{ number_format($totalexclval) }}</th>
                <th></th>
                <th>{{ number_format($totalSaletax) }}</th>
                <th></th>

            </tr>
        </tfoot>
    </table>
    @include('include.numberconvert')
  <span style="text-transform: capitalize; float:right">Qty In Words: {{ convertNumber($total) }} Only</span>

    <br />
    <br />
    <table>
        <tbody>
            <tr>
                <td colspan="2">Prepared by:<b><u>
                    @if($RequestGenerateDetails[0]->request_generate->created_by_user_po)
                    {{ $RequestGenerateDetails[0]->request_generate->created_by_user_po->name }}
                    @else
                    ________________
                    @endif
                </u></b></td>
                <td colspan="2">Request by:<b><u>
                    
                    @if($RequestGenerateDetails[0]->request_generate->user)
                    {{ $RequestGenerateDetails[0]->request_generate->user->name }} 
                    @else
                    ________________
                    @endif
                </u></b></td>
                
                <td>Checked by:________________</td>
                <td>Approved by:_______________</td>
            </tr>
            <tr>
                <td colspan="5">Print Date:{{ date('d/m/Y') }}, {{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
