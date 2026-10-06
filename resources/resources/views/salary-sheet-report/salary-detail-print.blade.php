<!DOCTYPE html>
<html>

<head>
    <title>CASH RECEIPT INVOICE</title>
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
            border-top: 0px;
            border-left:  0px;
            border-right:  0px;
            font-size: 14px;
        }
        #designed tbody tr td{
            border-left: 1px solid black;
            border-bottom: 1px solid black;
            font-size: 12px;
        }

        #designed tfoot tr th {
            border-top: 1px solid black;
            border-right: 1px solid black;
            border-left: 1px solid black;
            border-bottom: 1px solid black;
            font-size: 14px;
        }
        #title{
            border: 1.5px solid;
            background-color:lightblue;
        }
        #voucher{
            border: 1.5px solid;
            background-color: lightblue;
            margin-top: -3%;
        }
    </style>
</head>
<body>
@if(SettingsFacade::data()->titletype == "WAREHOUSE")
    {{-- <h2 style="text-align:center; margin-top: -20px;"><u>{{$generalVoucher[0]->warehouse->name}}</u></h2> --}}
    @else
    <h1 style="margin-top: -20px;"><center><u>{{ SettingsFacade::data()->title }}</u></center></h1>
    @endif
    <h3 style="margin-top: -10px;"><center>EMPLOYEE DETAIL PAYROLL</center></h3>
   <br />
<div style="clear:both">
    <div style="float: left;"><b>Warehouse: </b>{{$employee->warehouse->name}}</div>
    <div style="float: right;"><b>Designation: </b>{{ $employee->designation->title }}</div><br />
</div>
<div style="clear:both">
    <div style="float: left;"><b>Employee Name: </b>{{ $employee->party_name }}</div>
    <div style="float: right;"><b>Employee Code: </b>{{ $employee->code }}</div><br />
</div>
<div style="clear:both">
    <div style="float: left;"><b>Monthly Salary: </b>{{ number_format($employee->employeehistory->monthly_salary) }}</div>
    <div style="float: right;"><b>Basic Salary: </b>{{ number_format($employee->employeehistory->basic_salary) }}</div><br />
</div>
<div style="clear:both">
    <div style="float: left;"><b>House Rent: </b>{{ number_format($employee->employeehistory->house_rent) }}</div>
    <div style="float: right;"><b>Medical Allowance: </b>{{ number_format($employee->employeehistory->medical_allowance) }}</div><br />
</div>
<div style="clear:both">
    <div style="float: left;"><b>Attendance Allowance: </b>{{ number_format($employee->employeehistory->attendance_allowance) }}</div>
    <div style="float: right;"><b>Paid Leaves: </b>{{ number_format($employee->employeehistory->paid_leaves) }}</div><br />
</div>
<div style="clear:both">
    <div style="float: left;"><b>Travelling Allowance: </b>{{ number_format($employee->employeehistory->travelling_allowance) }}</div>
    <div style="float: right;"><b>Mobile Allowance: </b>{{ number_format($employee->employeehistory->mobile_allowance) }}</div><br />
</div>
<div style="clear:both">
    <div style="float: left;"><b>OverTime:</b> 
    @if($employee->employeehistory->over_time == 1)
   {{"YES"}}
    @else
    {{"NO"}}
    @endif
    || <b>Bonus Type: </b> {{ number_format($employee->employeehistory->bonus_type) }}</div>
    <div style="float: right;"><b>Other Allowance: </b> {{ number_format($employee->employeehistory->other_allowance) }}</div><br />
</div>
<!-- <div style="clear:both">
    <div style="float: left;"><b>Date:
        </b></div>
</div> -->

   
    <br>
    <br>
    <table id="designed" style="width:100%;">
        <thead>
            <tr style="border: 2px solid;">
                <th style="border-right: 2px solid;">Sr#</th>
                <th style="border-right: 2px solid;">Date</th>
                <th style="text-align:right; border-right: 2px solid;">Time In</th>
                <th style="text-align:right; border-right: 2px solid;">Time Out</th>
                <th style="text-align:right; border-right: 2px solid;">Working Hours</th>
                <th style="border-right: 2px solid;">Status</th>
                <th style="text-align:right; border-right: 2px solid;">Overt Time</th>
                <th style="text-align:right; border-right: 2px solid;">Extra Production</th>
                <th style="text-align:right; border-right: 2px solid;">Day.RS</th>
                <th style="text-align:right; border-right: 2px solid;">OT.Rs</th>
                <th style="text-align:right; border-right: 2px solid;">Extra.Rs</th>
            </tr>
        </thead>
        <tbody>
            @php $totalWorkingHours=0; $totalOverTime=0; $totalDaysRs = 0; $totalOvertimeRs = 0;  $totalExtraRs = 0; @endphp
            @foreach($employee->attendance as $value)
                <tr>
                    <td>{{  $loop->iteration }}</td>
                    <td>{{ date("d/m/Y", strtotime($value->date)) }}</td>
                    <td style="text-align:right;">{{ \Carbon\Carbon::parse($value->time_in)->format('h:i A') }}</td>
                    <td style="text-align:right;">{{ \Carbon\Carbon::parse($value->time_out)->format('h:i A') }}</td>
                    <td style="text-align:right;">
                    @if($value->working_hours > 0)
                    {{ $value->working_hours }}
                    @php $totalWorkingHours += $value->working_hours; @endphp
                    @endif
                </td>
                    <td>{{ $value->status }}</td>
                    <td style="text-align:right;">
                    @if($employee->employeehistory->over_time == 1)
                        @if($value->over_time > 0)
                        {{ $value->over_time }}
                        @php $totalOverTime += $value->over_time; @endphp
                        @endif
                    @endif
                </td>
                    <td style="text-align:right;">{{ $value->extra_production }}</td>
                    @php 
                    $oneDaySalary = $employee->monthly_salary / $Monthdays;
                    $employeeOneDayHours = $employee->working_hours;
                    $employeeOneHourSalary = $oneDaySalary / $employeeOneDayHours;
                    $employeeOneDaySalary = $employeeOneHourSalary * $value->working_hours;
                    $employeeOverTimeSalary = $employeeOneHourSalary * $value->over_time;
                    $employeeExtraSalary = $employeeOneHourSalary * $value->extra_production;
                    @endphp
                    <td style="text-align:right;">
                    @if($employeeOneDaySalary > 0)
                        {{ number_format($employeeOneDaySalary, 2) }}
                        @php $totalDaysRs += $employeeOneDaySalary; @endphp
                    @endif
                </td>
                <td style="text-align:right;">
                @if($employee->employeehistory->over_time == 1)
                    @if($employeeOverTimeSalary > 0)    
                    {{ number_format($employeeOverTimeSalary, 2) }}
                    @php $totalOvertimeRs += $employeeOverTimeSalary; @endphp
                    @endif
                    @endif
                </td>
                <td style="text-align:right;">
                    @if($employeeExtraSalary > 0)    
                    {{ number_format($employeeExtraSalary, 2) }}
                    @php $totalExtraRs += $employeeExtraSalary; @endphp
                    @endif
                </td>
                </tr>

               
            @endforeach
        </tbody>
        <tfoot>
             <tr>
                <th colspan="4">Total</th>
                <th style="text-align:right;">{{ number_format($totalWorkingHours, 2) }}</th>
                <th style="text-align:right;"></th>
                <th style="text-align:right;">
                    @if($employee->employeehistory->over_time == 1)    
                        {{ number_format($totalOverTime, 2) }}
                    @endif
                </th>
                <th style="text-align:right;"></th>
                <th style="text-align:right;">{{ number_format($totalDaysRs, 2) }}</th>
                <th style="text-align:right;">
                    @if($employee->employeehistory->over_time == 1)
                        {{ number_format($totalOvertimeRs, 2) }}
                    @endif
                </th>
                <th style="text-align:right;">{{ number_format($totalExtraRs, 2) }}</th>
            </tr> 
        </tfoot>
    </table>
    @include('include.numberconvert')
    <!-- <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: {{ convertNumber($totalWorkingHours) }} Only/-</span> -->
    <br /><br>
    <table id="designed" style="width:30%; float:left; margin-left: 35%;">
        <thead>
            <tr style="border: 2px solid;">
                <th style="border-right: 2px solid;">Days Rs</th>
                <td style="border-right: 2px solid;">{{ number_format($totalDaysRs, 2) }}</td>
            </tr>
            @if($employee->employeehistory->over_time == 1)
            <tr style="border: 2px solid;">
                <th style="border-right: 2px solid;">OverTime Rs</th>
                <td style="border-right: 2px solid;">{{ number_format($totalOvertimeRs, 2) }}</td>
            </tr>
                @php $totalSalary = $totalDaysRs + $totalOvertimeRs + $totalExtraRs; @endphp
            @else
             @php $totalSalary = $totalDaysRs + $totalExtraRs; @endphp
            @endif
           
            <tr style="border: 2px solid;">
                <th style="border-right: 2px solid;">Extra Rs</th>
                <td style="border-right: 2px solid;">{{ number_format($totalExtraRs, 2) }}</td>
            </tr>
           
            <tr style="border: 2px solid;">
                <th style="border-right: 2px solid;">Total Salary</th>
                <td style="border-right: 2px solid;">{{ number_format($totalSalary, 2) }}</td>
            </tr>
        </thead>
    </table>
    <table id="designed" style="width:30%; float:right;">
        <thead>
            @php $totalAdvance = 0; @endphp
        @foreach($employee->employees_advance as $advance)
            <tr style="border: 2px solid;">
                <th style="border-right: 2px solid;">{{$advance->advance_types}}</th>
                <td style="border-right: 2px solid;">{{ number_format($advance->debit, 2) }}</td>
                
            </tr>
            @php $totalAdvance += $advance->debit; @endphp
            @endforeach
            <!-- <tr style="border: 2px solid;">
                <th style="border-right: 2px solid;">OT Rs</th>
                <td style="border-right: 2px solid;">{{ number_format($totalOvertimeRs, 2) }}</td>
            </tr>
            <tr style="border: 2px solid;">
                <th style="border-right: 2px solid;">Extra Rs</th>
                <td style="border-right: 2px solid;">{{ number_format($totalExtraRs, 2) }}</td>
            </tr> -->
            
            <tr style="border: 2px solid;">
                <th style="border-right: 2px solid;">Total Advance</th>
                <td style="border-right: 2px solid;">{{ number_format($totalAdvance, 2) }}</td>
            </tr>
            <tr style="border: 2px solid;">
                <th style="border-right: 2px solid;">Payable Salary</th>
                <td style="border-right: 2px solid;">{{ number_format($totalSalary-$totalAdvance, 2) }}</td>
            </tr>
        </thead>
    </table>
    <!-- <br/><br/>
    <table>
        <tbody>
        <tr>
                {{-- @if($generalVoucher[0]->billers)
                <td>Prepared by:<b><u>{{$generalVoucher[0]->billers->name}}&nbsp;&nbsp;</u></b></td>
                @else --}}
                <td>Prepared by:<b><u>____________</u></b></td>
                {{-- @endif --}}
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>Approved:<b><u>____________</u></b></td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>Recipient:<b><u>____________</u></b></td>
            </tr>
             <tr>
                <td colspan="5">Print Date: {{ date('d/m/Y') }} || {{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table> -->
</body>

</html>
