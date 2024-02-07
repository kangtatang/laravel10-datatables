<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'address',
        'birth_date',
        'hire_date',
        'department_id',
        'total_leave_requests',
    ];

    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'department_id');
    }

    public function leaveRequests()
    {
        return $this->hasMany('App\Models\LeaveRequest');
    }

    public function salaryRecords()
    {
        return $this->hasMany('App\Models\SalaryRecord');
    }
}
