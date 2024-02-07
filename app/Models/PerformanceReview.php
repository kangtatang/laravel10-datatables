<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'review_date',
        'performance_rating',
        'review_notes',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
