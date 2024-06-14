<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Hiring extends Model
{
    use Sortable;
    use HasFactory;
    protected $fillable = [
        'job_position',
        'job_description',
        'salary_grade',
        'contract_type',
        'job_type',
        'department',
        'closing',
        'reference',
        'job_status',
        'competency_exam_date',
        'pre_employment_exam_date',
        'initial_interview_date',
        'final_interview_date',
        'psycho_test_date',
        'bei_date',
    ];  
    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

}

