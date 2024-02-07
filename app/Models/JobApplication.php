<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_title_id',
        'applicant_id',
        'application_date',
        'status',
    ];

    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class);
    }

    public function applicant()
    {
        return $this->belongsTo(Employee::class, 'applicant_id');
    }
}
