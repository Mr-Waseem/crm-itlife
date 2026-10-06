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
    @if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1><center>{{ SettingsFacade::data()->title }}</center></h1>
    @endif
    <h3><center>{{$productiondetail[0]->production->warehouse->name}} PRODUCTION</center></h3>
    <hr /><br />
    <div style="clear:both">
        <div style="float: left;"><b>Voucher.No: </b>{{ $productiondetail[0]->production->voucher_no }}</div>
        <div style="float: right;"><b>Voucher
                Date: </b>{{ date('d/m/Y', strtotime($productiondetail[0]->production->date)) }}</div>
    </div>
    <br />
    <div style="clear:both">
        <div style="float:left;"><b>Product: </b> {{  $productiondetail[0]->production->product->product_name}}</div>
         <div style="float:right;"><b>Unit:</b> {{  $productiondetail[0]->production->unit->uom }}</div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Quantity (Net Weight): </b> {{  $productiondetail[0]->production->total_qty }}</div>
        <div style="float:right;"><b>Quantity (Gross Weight): </b> {{  $productiondetail[0]->production->gross_weight }}</div>
        <!-- <div style="float:right;"><b>Warehouse: </b> {{  $productiondetail[0]->production->warehouse->name }}</div> -->
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Total Amount: </b> {{  number_format($productiondetail[0]->production->total_amount,2) }}</div>
        <div style="float:right;"><b>BatchNo: </b> {{  $productiondetail[0]->production->batchNo }}</div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Machine: </b> 
        @if($productiondetail[0]->production->machine)
        {{ $productiondetail[0]->production->machine->machine_name }}
        @endif
    </div>
        <div style="float:right;"><b>Shift: </b>
        @if($productiondetail[0]->production->shift)
        {{  $productiondetail[0]->production->shift->shift_name }}
        @endif
    </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Forman: </b> 
        @if($productiondetail[0]->production->Forman)
        {{ $productiondetail[0]->production->Forman->party_name }}
        @endif
    </div>
        <div style="float:right;"><b>Operator: </b> 
        @if($productiondetail[0]->production->Operator)
        {{  $productiondetail[0]->production->Operator->party_name }}
        @endif
    </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Thickness: </b>
       
        {{ $productiondetail[0]->production->thickness }}
       
    </div>
        <div style="float:right;"><b>Width: </b> 
       
        {{  $productiondetail[0]->production->width }}
        
    </div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Remarks: </b> {{  $productiondetail[0]->production->remarks }}</div>
    </div>
    <br /><br />
     <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>  
                <th>Sr#</th>
                <th>Product</th>
                <th>Unit</th>
                <th>Status</th>
                <th>Rcipe Qty</th>
                <th>Current Qty</th>
                <th>Consumed Qty</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $totalqty=0; $totalconsumedqty=0; $totalamount=0;  $i=1; $totalrecipeqty=0; @endphp
            @foreach ($productiondetail as $value)
                <tr>
                    <td align="center">{{ $i++ }}</td>
                     <td>{{ $value->product->product_name }}</td> 
                     <td align="center">{{ $value->unit->uom }}</td>
                     <td align="center">{{ $value->status}}</td>
                    <td align="center">{{ $value->recipe_qty }}</td>
                    <td align="center">{{ $value->quantity }}</td>
                    <td align="center">{{ $value->showqty }}</td>
                    <td align="center">{{ number_format($value->rate,2) }}</td>
                    <td align="center">{{ number_format($value->amount,2) }}</td>
                </tr>
                 @php
                  $totalrecipeqty+=$value->recipe_qty; 
                  $totalqty+=$value->quantity; 
                  $totalconsumedqty+=$value->showqty; 
                  $totalamount+=$value->amount; 
                 @endphp
                 
            @endforeach
        </tbody>
        <tfoot>
            <tr align="center">
                <th colspan="4">Total</th>
               
                <th>{{ number_format($totalrecipeqty,4) }}</th>
                <th>{{ number_format($totalqty,4) }}</th>
                <th>{{ number_format($totalconsumedqty,4) }}</th>
                <th></th>
                <th>{{  number_format($totalamount,2)}}</th>
              

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
                <td>Generated by:<b><u>
                    {{$productiondetail[0]->production->generated_by->name}}
                
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
