    <p style="page-break-after: always;"></p>

    <table id="designed" style="border:1px solid; width:100%;">
        <tbody>
            <tr style="border-bottom: 1px solid;">
                <td rowspan="2"><img src="{{asset('dashboard/plastic.png')}}" style="width: 60px;"></td>
                <td colspan="4" style="width: 60%; font-size: 20px;">{{ SettingsFacade::data()->system_name }}</td>
                <td rowspan="2" style="text-align:left;">
                    <span style="font-size:10px;"><b>Document No: SPI-FSTL-PRP-01<br/>
                    Issue No: 01<br/>
                    Revision No: 00<br/>
                    Effective Date: 22-01-2021</b></span>
                </td>
            </tr>
            <tr>
                <td colspan="4"><b>Vehicle Inspection Checklist</b></td>
            </tr>
        </tbody>
    </table>
    <br/><br/>
    <!-- <span style="font-size: 20px;">Consignment Details</span> -->

    <div style="clear:both">
        <div style="float:left;"><span style="font-size: 20px;">Consignment Details</span></div> 
        <div style="float:right;"><span style="font-size: 20px;">Date: {{ date('d-M-y', strtotime($deliverychallanDetail[0]->delivery_challan->voucher_date)) }}</span></div> 
    </div>
    <br /><br />

    <table id="designed" style="border:2px solid; width:100%;">
        <tbody>
            <tr style="border-bottom: 1px solid;">
                <td style="width:50%; text-align:left;">Incoming/Outgoing Material Name</td>
                <td style="width:50%;">PET BOXES </td>
            </tr>
            <tr style="border-bottom: 1px solid;">
                <td style="width:50%; text-align:left;">Supplier</td>
                <th style="width:50%; text-align:left; font-size: 12px;">
                @if($deliverychallanDetail[0]->delivery_challan->party)
                {{ $deliverychallanDetail[0]->delivery_challan->party->party_name}}
                @else{{" No Party"}} @endif
                </th>
            </tr>
            <tr style="border-bottom: 1px solid;">
                <td style="width:50%; text-align:left;">Quantity</td>
                <th style="width:50%;">
                {{ number_format($totalsale_qty)}}
                </th>
            </tr>
            <tr style="border-bottom: 1px solid;">
                <td style="width:50%; text-align:left;">Vehicle # {{ $deliverychallanDetail[0]->delivery_challan->voucher_no }}</td>
                <th style="width:50%;">
                @if($deliverychallanDetail[0]->delivery_challan)
                {{ $deliverychallanDetail[0]->delivery_challan->vehicle_no }} 
                @else {{" No Record"}}
                @endif
                </th>
            </tr>
            <tr style="border-bottom: 1px solid;">
                <td style="width:50%; text-align:left;">.</td>
                <td style="width:50%;"> </td>
            </tr>
        </tbody>
    </table>
    <span style="font-size: 20px;">Product Bags / Cartons / Pallets in Good Condition?</span>
    <table id="designed" style="border:2px solid; width:100%;">
        <tbody>
            <tr style="border-bottom: 1px solid;">
                <th style="text-align:left; border-right:1px solid;">Product Bags / Cartons / Pallets in Good Condition?</th>
                <th style="border-right:1px solid;">YES</th>
                <th style="border-right:1px solid;">NO</th>
                <th style="border-right:1px solid;">REMARKS</th>
            </tr>
            <tr style="border-bottom: 1px solid;">
                <td style="text-align:left; border-right:1px solid;">Vehicle Cleaned from Inside & Ourside?</td>
                <td style="border-right:1px solid;"><img src="{{asset('dashboard/checkbox.png')}}" style="width: 20px;"></td>
                <td style="border-right:1px solid;"></td>
                <td style="border-right:1px solid;"></td>
            </tr>
            <tr style="border-bottom: 1px solid;">
                <td style="text-align:left; border-right:1px solid;">Product Bags/Cartons/Pallets in Good Condition?</td>
                <td style="border-right:1px solid;"><img src="{{asset('dashboard/checkbox.png')}}" style="width: 20px;"></td>
                <td style="border-right:1px solid;"></td>
                <td style="border-right:1px solid;"></td>
            </tr>
            <tr style="border-bottom: 1px solid;">
                <td style="text-align:left; border-right:1px solid;">Any unusual aroma?</td>
                
                <td style="border-right:1px solid;"></td>
                <td style="border-right:1px solid;"><img src="{{asset('dashboard/checkbox.png')}}" style="width: 20px;"></td>
                <td style="border-right:1px solid;"></td>
            </tr>
            <tr style="border-bottom: 1px solid;">
                <td style="text-align:left; border-right:1px solid;">Insects? Glass Pieces?</td>
                
                <td style="border-right:1px solid;"></td>
                <td style="border-right:1px solid;"><img src="{{asset('dashboard/checkbox.png')}}" style="width: 20px;"></td>
                <td style="border-right:1px solid;"></td>
            </tr>
            <tr style="border-bottom: 1px solid;">
                <td style="text-align:left; border-right:1px solid;">Vehicle is Locked?</td>

                <td style="border-right:1px solid;"></td>
                <td style="border-right:1px solid;"><img src="{{asset('dashboard/checkbox.png')}}" style="width: 20px;"></td>
                <td style="border-right:1px solid;"></td>
            </tr>
            <tr style="border-bottom: 1px solid;">
                <td style="text-align:left; border-right:1px solid;">Any Oil/Diesel/Kerosene Oil in contact with Material?</td>

                <td style="border-right:1px solid;"></td>
                <td style="border-right:1px solid;"><img src="{{asset('dashboard/checkbox.png')}}" style="width: 20px;"></td>
                <td style="border-right:1px solid;"></td>
            </tr>
            <tr style="border-bottom: 1px solid;">
                <td style="text-align:left; border-right:1px solid;">Any Suspecious Material?</td>

                <td style="border-right:1px solid;"></td>
                <td style="border-right:1px solid;"><img src="{{asset('dashboard/checkbox.png')}}" style="width: 20px;"></td>
                <td style="border-right:1px solid;"></td>
            </tr>
        </tbody>
    </table> <br />
    <span>Note: If any deviation observed immediately informed to the respective in charge.</span>
    <br />
    <br />
    <table>
        <tbody>
            <tr>
                <td>Inspected By:<b>DESPATCHER</b></td>
            </tr>
        
        </tbody>
    </table>
    <hr/>