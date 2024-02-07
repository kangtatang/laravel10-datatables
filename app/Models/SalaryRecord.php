<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'salary_amount',
        'bonus_amount',
        'deduction_amount',
        'payment_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
