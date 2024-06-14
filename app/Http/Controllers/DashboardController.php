<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Hiring;
use App\Models\Applicant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $activeJobHiringsCount = Hiring::where('job_status', '!=', 'archived')->count();
        $archivedJobHiringsCount = Hiring::where('job_status', 'archived')->count();
        $usersCount = User::where('usertype', 'user')->count();
        $applicantsCount = Applicant::whereHas('Hiring', function ($query) {
            $query->where('job_status', '!=', 'archived');
        })->count();

        return view('Admin.index', [
            'activeJobHiringsCount' => $activeJobHiringsCount,
            'archivedJobHiringsCount' => $archivedJobHiringsCount,
            'usersCount' => $usersCount,
            'applicantsCount' => $applicantsCount,
        ]);
    }

    public function CalendarDates(Request $request)
    {
        $hiringDates = Hiring::select(
            'job_position',
            'competency_exam_date', 
            'pre_employment_exam_date', 
            'initial_interview_date', 
            'final_interview_date', 
            'psycho_test_date', 
            'closing'
        )
        ->where('job_status', '!=', 'archived')
        ->get();

        $events = $hiringDates->flatMap(function ($date) {
            return [
                [
                    'title' => $date->job_position . '-Competency Exam',
                    'start' => $date->competency_exam_date ? Carbon::parse($date->competency_exam_date)->format('Y-m-d') : null,
                    'backgroundColor' => '#f56954',
                    'borderColor' => '#f56954',
                    'allDay' => true
                ],
                [
                    'title' => $date->job_position . '-Pre-employment Exam',
                    'start' => $date->pre_employment_exam_date ? Carbon::parse($date->pre_employment_exam_date)->format('Y-m-d') : null,
                    'backgroundColor' => '#f39c12',
                    'borderColor' => '#f39c12',
                    'allDay' => true
                ],
                [
                    'title' => $date->job_position . '-Initial Interview',
                    'start' => $date->initial_interview_date ? Carbon::parse($date->initial_interview_date)->format('Y-m-d') : null,
                    'backgroundColor' => '#0073b7',
                    'borderColor' => '#0073b7',
                    'allDay' => true
                ],
                [
                    'title' => $date->job_position . '-Final Interview',
                    'start' => $date->final_interview_date ? Carbon::parse($date->final_interview_date)->format('Y-m-d') : null,
                    'backgroundColor' => '#00a65a',
                    'borderColor' => '#00a65a',
                    'allDay' => true
                ],
                [
                    'title' => $date->job_position . '-Psycho Test',
                    'start' => $date->psycho_test_date ? Carbon::parse($date->psycho_test_date)->format('Y-m-d') : null,
                    'backgroundColor' => '#3c8dbc',
                    'borderColor' => '#3c8dbc',
                    'allDay' => true
                ],
                [
                    'title' => $date->job_position . '-Closing',
                    'start' => $date->closing ? Carbon::parse($date->closing)->format('Y-m-d') : null,
                    'backgroundColor' => '#d2d6de',
                    'borderColor' => '#d2d6de',
                    'allDay' => true
                ]
            ];
        })->filter(function ($event) {
            return $event['start'] !== null;
        });
        
        return response()->json($events->values());
    }
}
