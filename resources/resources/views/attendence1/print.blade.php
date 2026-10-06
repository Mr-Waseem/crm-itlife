<!DOCTYPE html>
<html>

<head>
    <title>Employee Print</title>
    <style>
        .main {
            width: 100%;
            height: auto;
        }
        .section1 {
            float: left;
            width: 70%;
        }
        

        .section2 {
            width: 30%;
        }
       
        .main2 {
            width: 100%;
        }
        .front{
            float: left;
            width: 50%;
        }
        .back{
            width: 50%;
        }
    </style>
</head>

<body>
    <h1>
        <center>{{ SettingsFacade::data()->title }}</center>
    </h1>
    <h3 style="text-align: center;"><u>APPOINTMENT LETTER FOR 4555</u></h3>
    <p>Dear Sir/Mam,</p>
    <p>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;I am pleased to inform you that you have been appointed for the role of 1234 at 89988. This is an official letter confirming your employment which starts from {{date("d/m/Y", Strtotime($employee->created_at))}} under the folowing terms and conditions.</p>
    <div class="main">
        <div class="section1">
            <table>
                <tbody>
                    <tr>
                        <td>Employee ID</td>
                        <td>: {{ $employee->employee_id }}</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td colspan="2">: {{ $employee->party_name }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td colspan="2">: {{ $employee->user->email }}</td>
                    </tr>
                    <tr>
                        <td>PhoneNo</td>
                        <td colspan="2">: {{ $employee->phone }}</td>
                    </tr>
                    <tr>
                        <td>CNIC</td>
                        <td colspan="2">: {{ $employee->cnic_no }}</td>
                    </tr>
                    <tr>
                        <td>Salary</td>
                        <td colspan="2">: {{ $employee->monthly_salary }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--end of section1-->
        <div class="section2">
            @if($employee->current_picture)
            <div class="text-center text-sm-right">
                <img src="{{asset($employee->current_picture)}}" style="height: 140px; width: 200px;">
            </div>
            @endif 
        </div>
    </div>
    <div>
        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td>Address:{{ $employee->address }}</td>
                </tr>
                <tr>
                    <td>Spous Of :{{ $employee->spous_of }}</td>
                    <td>Personal Mobile no# : {{ $employee->personal_mbl_no }}</td>
                </tr>
                <tr>
                    <td>Blood Relative Moblile:{{ $employee->blood_relative_mbl }}</td>
                    <td>Relationship : {{ $employee->relationship }}</td>
                </tr>
                <tr>
                    <td>Joining Date:{{ date('d/m/Y',strtotime($employee->joining_date)) }}</td>
                    <td>Dept : {{ $employee->dept }}</td>
                </tr>
                <tr>
                    <td>Monthly Salary:{{$employee->monthly_salary }}</td>
                    <td>Basic Salary : {{ $employee->basic_salary }}</td>
                </tr>
                <tr>
                    <td>House Rent:{{ $employee->house_rent }}</td>
                    <td>Medical Allowance : {{ $employee->medical_allowance }}</td>
                </tr>
                <tr>
                    <td>Attendance Allowance:{{ $employee->attendance_allowance}}</td>
                    <td>Referred Dept:{{ $employee->referred_dept }}</td>
                </tr>
                <tr>
                    <td>paid_leaves:{{ $employee->paid_leaves }}</td>
                    <td>Travelling Allowance : {{ $employee->travelling_allowance }}</td>
                </tr>
                <tr>
                    <td>Referred_by_Emp no#:{{ $employee->referred_by_emp_no }}</td>
                    <td>Referred Cnic : {{ $employee->referred_cnic }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="clear:both">
    <div class="main2">
        <b><h2 style="text-align: center;"><u>CNIC (FRONT | BACK)</u></h2></b>
           <div class="front">
            
            @if($employee->cnic_front_img)
            <img src="{{asset($employee->cnic_front_img)}}" style="width: 98%;">
             @endif
           </div>
           <div class="back">
            @if($employee->cnic_back_img)                                       
            <img src="{{asset($employee->cnic_back_img)}}" style="width: 98%;">                                             
            @endif
           </div>
    </div>
    </div>
    <div class="footer">
                <p><label style="color:black; font-weight: 700;">GM Signature _______________________________________</label></p>
                <p><label style="color:black; font-weight: 700;">DM Signature _______________________________________</label></p>
                <p> <label style="color:black; font-weight: 700;">CEO Signature_______________________________________</label></p>  
    </div>
</body>

</html>