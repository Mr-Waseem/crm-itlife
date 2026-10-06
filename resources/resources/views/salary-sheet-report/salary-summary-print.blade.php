<!DOCTYPE html>
<html>

<head>
    <title>SALARY SHEET REPORT</title>
    <link rel="stylesheet" href="{{ URL::asset('dashboard/css/report.css') }}">
</head>

<body>
    @if(SettingsFacade::data()->UnRegisteredTitle !=0)
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    @endif
    <!-- <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1> -->

    <h3>
        <center>PAYROLL FOR THE MONTH OF <br></b>{{date("F Y", strtotime($fromDate))}}<b></center>
    </h3>
   
    <table id="designed" style="border:2px solid; width:100%;">
        <thead>
            <tr>
                <th>SrNo</th>
                <th>Emp Code</th>
                <th>Name F/Z/S of DOJ</th>
                <th>Designation Current Bonus</th>
                <th>Basic Pay</th>
                <th>T.Days</th>
                <th>Day Rs.</th>
                <th>O.Time</th>
                <th>O.T Rs.</th>
                <th>Extra Prod./Fix</th>
                <th>Travel Protel Etc</th>
                <th>Bonus</th>
                <th>Gross Salary</th>
                <th>Cateen</th>
                <th>Adv.Ded.</th>
                <th>Loan.Ded.</th>
                <th>Penalty</th>
                <th>Payable Salary</th>
                <th>Paid</th>
                <th>Sign</th>
            </tr>
        </thead>
         <tbody>
            @php
               $sum = 0; 
               $bonus=0;
          @endphp
            @foreach($employee as $data)
            @php 
            $sum += 1; 
            $bonus=$data->medical_allowance + $data->attendance_allowance + $data->travelling_allowance + $data->house_rent;
            $year = date('Y'); // Assume the current year or extract it from $data->date
            $month = date('m', strtotime($data->date)); // Extract the month from $data->date
            $total_days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $days_rs=$data->basic_salary / $total_days_in_month;
            $over_time_rs=($days_rs/8) * $data->totalOverTime;
            $total_days_rs = ($data->basic_salary / $total_days_in_month) * $data->present_count;
            $grass_salary= $bonus+ $over_time_rs;
            $total_advance_type=$data->totalcanteen+$data->totalAdvance+$data->totalloan+$data->totalpenalty;
            $payable_Salary=$grass_salary-$total_advance_type;

            $GrossSalary = 0; $GrossAdvance = 0;
            @endphp
                <tr style="border-bottom: 1px solid;">
                    <td>{{$sum}}</td>
                    <td>{{$data->code}}</td>
                    <td>{{$data->party_name}}<br>
                        @if($data->spous_of)
                        {{$data->spous_of}}<br>
                        @endif
                        {{date("d/m/y", strtotime($data->joining_date))}}
                    </td>
                    <td>
                        {{$data->title}}<br>
                        {{number_format($data->monthly_salary)}}<br>
                        {{number_format($bonus)}}
                    </td>
                    <td>
                        {{number_format($data->basic_salary)}}
                    </td>
                    <td>
                        444
                    </td>
                    <td>{{number_format($total_days_rs)}}</td>
                    <td>
                        {{$data->totalOverTime}}
                    </td>
                     <td>{{number_format($over_time_rs)}}</td>
                     <td></td>
                     <td>{{number_format($data->travelling_allowance)}}</td>
                     <td>{{number_format($bonus)}}</td>
                     @php 
                        $GrossSalary = $bonus + $data->basic_salary;

                        $GrossAdvance = $data->Total_Canteen + $data->Total_Advance + $data->Total_Loan + $data->Total_Penalty;
                     @endphp
                     <td>{{number_format($GrossSalary)}}</td>
                     <td>{{!empty($data->Total_Canteen)? number_format($data->Total_Canteen):''}}</td>
                     <td>{{!empty($data->Total_Advance)? number_format($data->Total_Advance):''}}</td>
                     <td>{{!empty($data->Total_Loan)? number_format($data->Total_Loan):''}}</td>
                     <td>{{!empty($data->Total_Penalty)? number_format($data->Total_Penalty):''}}</td>
                     <td>{{number_format($GrossSalary - $GrossAdvance)}}</td>
                     <td></td>
                     <td></td>
                </tr>
            @endforeach
        </tbody>
        {{-- <tr style="border-top: 1px solid;">
            <td colspan="6">Total</td>
            <td>{{number_format($TotalDebit)}}</td>
            <td>{{number_format($TotalCredit)}}</td>
            <td>{{number_format($TotalDebit-$TotalCredit+$OpeningBalance)}}</td>
        </tr> --}}
    </table>
    {{-- @include('include.numberconvert')
    @php $lastvalue = abs($TotalDebit-$TotalCredit+$OpeningBalance);  @endphp
    <span style="text-transform: capitalize; float:right;margin-top:5px;margin-bottom:10px;">{{ SettingsFacade::data()->currency }}: {{ convertNumber($lastvalue) }}</span> --}}
    <br /><br />
    <table>
        <tbody>
            <tr>
                <td colspan="5">Generated by:<b><u>{{Auth::User()->name}}</u></b></td>
                <td>Checked&nbsp;by:________________</td>
                <td>Approved&nbsp;by:_______________</td>
            </tr>
            <tr>
                <td colspan="5">Print Date: {{ date('d/m/Y') }}, {{ date('h:i:s A') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
