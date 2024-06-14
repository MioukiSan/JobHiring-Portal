<?php

namespace App\Models;

use Illuminate\Console\Application;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryGrade extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'applicant_id',
        'selection_board_id',
        'salary_grade',
        'dependability',
        'creative',
        'initiative',
        'time_management',
        'planning',
        'adaptability',
        'teamwork',
        'self_management',
        'communication',
        'service_delivery',
        'customer_focus',
        'management',
        'staff_management',
        'strategic_planning',
        'org_awareness',
        'monitor_evaluation',
        'strategy_creatively',
        'leading_change',
        'building_relationship',
        'coaching',
        'create_nurture_performance',
    ];

    protected $casts = [
        'dependability' => 'array',
        'creative' => 'array',
        'initiative' => 'array',
        'time_management' => 'array',
        'planning' => 'array',
        'adaptability' => 'array',
        'teamwork' => 'array',
        'self_management' => 'array',
        'communication' => 'array',
        'service_delivery' => 'array',
        'customer_focus' => 'array',
        'management' => 'array',
        'staff_management' => 'array',
        'strategic_planning' => 'array',
        'org_awareness' => 'array',
        'monitor_evaluation' => 'array',
        'strategy_creatively' => 'array',
        'leading_change' => 'array',
        'building_relationship' => 'array',
        'coaching' => 'array',
        'create_nurture_performance' => 'array'
    ];
    
    public function applicants()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
