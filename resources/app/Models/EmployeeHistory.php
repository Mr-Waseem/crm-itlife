<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeHistory extends Model
{
    use HasFactory;
    protected $fillable = array(
        'employee_id',
        'monthly_salary',
        'basic_salary',
        'house_rent',
        'medical_allowance',
        'attendance_allowance',
        'paid_leaves',
        'travelling_allowance',
        'mobile_allowance',
        'eidi',
        'other_allowance',
        'bonus_type',
        'working_hours',
        'over_time',
        'employee_status'
    );
}
