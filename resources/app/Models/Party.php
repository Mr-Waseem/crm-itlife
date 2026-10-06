<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    protected $fillable = [
        'account_group_id', 'account_type', 'code', 'party_name', 'party_email', 'location_id',
        'cnic', 'bank_id', 'bank_account_no', 'shop_id', 'phone', 'ntn', 'strn', 'city', 'address',
        'salary', 'employee_status', 'fatrate', 'unitrate', 'account_group_id2', 'account_group_id3',
        'type', 'show_products', 'status', 'role', 'created_by', 'updated_by', 'phone_normalized',
        'employee_id', 'spous_of', 'cnic_no', 'personal_mbl_no', 'blood_relative_mbl', 'relationship', 'cnic_front_img',
        'cnic_back_img', 'current_picture', 'police_report', 'academic_file', 'joining_date', 'dept',
        'monthly_salary', 'basic_salary', 'house_rent', 'medical_allowance', 'attendance_allowance',
        'paid_leaves', 'travelling_allowance', 'referred_by_emp_no', 'referred_cnic', 'signature', 'designation_id',
        'employee_type_id', 'time_in', 'time_out', 'working_hours', 'over_time'
    ];

    public function account_group()
    {
        return $this->belongsTo(AccountGroup::class, 'account_group_id');
    }

    public function account_group2()
    {
        return $this->belongsTo(AccountGroup2::class, 'account_group_id2');
    }

    public function account_group3()
    {
        return $this->belongsTo(AccountGroup3::class, 'account_group_id3');
    }

    public function purchase_detail()
    {
        return $this->hasMany('App\Models\PurchaseDetail');
    }

    public function attendance()
    {
        return $this->hasMany('App\Models\Attendance','employee_id','id');
    }

    public function sale_detail()
    {
        return $this->hasMany('App\Models\SaleDetail');
    }

    public function purchase_return_detail()
    {
        return $this->hasMany('App\Models\PurchaseReturnDetails');
    }

    public function sale_return_detail()
    {
        return $this->hasMany('App\Models\SaleReturnDetails');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function attendance_details()
    {
        return $this->hasMany('App\Models\AttendanceDetails', 'employee_id');
    }

    public function bank_payments()
    {
        return $this->hasMany('App\Models\BankPayment', 'account_head_id');
    }

    public function cash_receipts()
    {
        return $this->hasMany('App\Models\CashReceipt', 'account_head_id');
    }

    public function cash_payments()
    {
        return $this->hasMany('App\Models\CashPayment', 'account_head_id');
    }

    public function cheque_payments()
    {
        return $this->hasMany('App\Models\ChequePayment', 'account_head_id');
    }

    public function purchase_milk()
    {
        return $this->hasMany('App\Models\PurchaseMilk', 'supplier_id');
    }

    public function milk_collection()
    {
        return $this->hasMany('App\Models\CollectionMilk', 'supplier_id');
    }

    public function milk_location()
    {
        return $this->hasMany('App\Models\CollectionMilk', 'location_id');
    }

    public function ledger()
    {
        return $this->hasMany('App\Models\GeneralVoucher', 'account_head_id');
    }

    public function general_vouchers()
    {
        return $this->hasMany('App\Models\GeneralVoucher', 'account_head_id');
    }

    public function employees_advance()
    {
        return $this->hasMany('App\Models\GeneralVoucher', 'advance_employee_id');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\AccountGroup', 'location_id');
    }

    public function bank()
    {
        return $this->belongsTo('App\Models\Banks', 'bank_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'party_id');
    }

    public function leadDetails()
    {
        return $this->hasMany(LeadDetail::class);
    }

    public function latestLeadDetail()
    {
        return $this->hasOne(LeadDetail::class)->latestOfMany();
    }

    public function openLeadFollowup()
    {
        return $this->hasOne(LeadDetail::class)
            ->whereNotNull('follow_up_date')
            ->whereNull('completed_at')
            ->whereNull('cancelled_at')
            ->latestOfMany();
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'shop_id');
    }

    public function deparment()
    {
        return $this->belongsTo(Departments::class,'dept');
    }
    public function designation()
    {
        return $this->belongsTo('App\Models\Designation', 'designation_id');
    }

    public function employeehistory()
    {
        return $this->hasOne('App\Models\EmployeeHistory', 'employee_id');
    }
}
