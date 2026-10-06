<!DOCTYPE html>
<html>
<head>
    <title>PRINT PRODUCTS</title>
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

    <h3><center>ALL PRODUCTS</center></h3>
    <h5><center>WAREHOUSE: {{$warehouseData->name}}</center></h5>
    <hr /><br />
    <!-- <div style="clear:both">
        <div style="float: left;"><b>Voucher.No: </b>DFDSF</div>
        <div style="float: right;"><b>Voucher
                Date: </b>DFDSFDS</div>
    </div>
    <br />
    <div style="clear:both">
        <div style="float:left;"><b>Product: </b> DFDSFDS
        </div>
        <div style="float:right;"><b>Unit:</b> DDSFDS</div>
    </div>
    <div style="clear:both">
        <div style="float:left;"><b>Product Code: </b> DFDSFDS
        </div>
        <div style="float:right;"><b>Godown: </b>
           DFDSFD
        </div>
    </div>
    <br /><br /> -->
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr style="border: 1px solid;">
                <th>Code</th>
                <th style="width:45%; text-align:left !important;"> Product Name</th>
                <th>Unit</th>
                <th>Pack.Type</th>
                <th>Packing</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allproducts as $category)
            @if(count($category->products) > 0)
                    <tr style="border-top: 1px solid; border-bottom: 1px solid;"> 
                        <th colspan="3" style="text-align:left;">Category Name: {{$category->catagory_name}}</th>
                        <th colspan="2" style="text-align:left;">Category Code: {{$category->catagory_code}}</th>
                    </tr>
                    @if(count($category->products) > 0)
                        @foreach($category->products as $product)
                            <tr style="border-bottom: 1px solid;">
                                <td>{{$product->code}}</td>
                                <td style="width:35%; text-align:left !important;"> {{$product->product_name}}</td>
                                <td>{{$product->uom}}</td>
                                <td>{{$product->pack_type}}</td>
                                <td>{{number_format($product->packing)}}</td>
                            </tr>
                        @endforeach
                    @else
                    <tr style="border-top: 1px solid;">
                        <td colspan="5">No Products Found!</td>
                    </tr>
                    @endif
                @endif
            @endforeach
        </tbody>
    </table>
    <br /><br /><br />
    <div style="float: left;width:33.3%;font-family:sans-serif;">Print By: {{Auth::User()->name}}</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Checked: __________</div>
    <div style="float: left;width:33.3%;font-family:sans-serif;">Authorized: __________</div>
    <p><small style="font-family:sans-serif;">Print: {{ date('d/m/Y') }}  Time: {{ date('h:i:s A') }}</small></p>
</body>
</html>
