<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'leave_start_date',
        'leave_end_date',
        'leave_type',
        'leave_status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
