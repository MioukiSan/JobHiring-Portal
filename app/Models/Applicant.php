<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'hiring_id',
        'application_status',
        'competency_exam', //file
        'competency_exam_result', //passed or failed
        'pre_employment_exam', //file
        'pre_employment_exam_result',
        'initial_interview',
        'initial_interview_result',
        'final_interview',
        'psycho_test',
        'psycho_test_result',
        'bei_result',
    ];

    /**
     * Get the user that owns the applicant.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the hiring that owns the applicant.
     */
    public function hiring()
    {
        return $this->belongsTo(Hiring::class);
    }

    public function SalaryGrade()
    {
        return $this->hasOne(SalaryGrade::class);
    }
}
