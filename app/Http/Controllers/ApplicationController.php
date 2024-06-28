<?php

namespace App\Http\Controllers;

use App\Models\Hiring;
use App\Models\Applicant;
use App\Models\SalaryGrade;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Base query for applicants with left joins to hirings and users
        $hiringsOpen = Hiring::with('applicants.user')
            ->where('job_status', '!=' ,'Archived')
            ->orderBy('created_at', 'desc')
            ->get();

        // Prepare the data for the view
        $applicantsOpen = $hiringsOpen->map(function ($hiring) {
            return [
                'hiring_id' => $hiring->id,
                'job_position' => $hiring->job_position, // or other fields from the hirings table
                'applicants' => $hiring->applicants->map(function ($applicant) {
                    return [
                        'applicant_id' => $applicant->id,
                        'user_id' => $applicant->user_id,
                        'user_name' => $applicant->user->name,
                        'application_status' => $applicant->application_status,
                        'date_applied' => $applicant->created_at,
                        'csc_form' => $applicant->user->requirement->csc_form,
                        'tor_diploma' => $applicant->user->requirement->tor_diploma,
                        'training_cert' => $applicant->user->requirement->training_cert,
                        'eligibility' => $applicant->user->requirement->eligibility,
                    ];
                })
            ];
        });

        // Paginate the mapped data
        $applicantsOpen = new LengthAwarePaginator(
            $applicantsOpen->forPage(request()->page, 3),
            $hiringsOpen->count(),
            3,
            request()->page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        $hiringsClosed = Hiring::with('applicants.user')
            ->where('job_status', 'Archived')
            ->orderBy('created_at', 'desc')
            ->get();

        // Prepare the data for the view
        $applicantsClosed = $hiringsClosed->map(function ($hiring) {
            return [
                'hiring_id' => $hiring->id,
                'job_position' => $hiring->job_position, // or other fields from the hirings table
                'applicants' => $hiring->applicants->map(function ($applicant) {
                    return [
                        'applicant_id' => $applicant->id,
                        'user_id' => $applicant->user_id,
                        'user_name' => $applicant->user->name,
                        'application_status' => $applicant->application_status,
                        'date_applied' => $applicant->created_at,
                        'csc_form' => $applicant->user->requirement->csc_form,
                        'tor_diploma' => $applicant->user->requirement->tor_diploma,
                        'training_cert' => $applicant->user->requirement->training_cert,
                        'eligibility' => $applicant->user->requirement->eligibility,
                    ];
                })
            ];
        });

        // Paginate the mapped data
        $applicantsClosed = new LengthAwarePaginator(
            $applicantsClosed->forPage(request()->page, 3),
            $hiringsClosed->count(),
            3,
            request()->page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('Admin.application', compact('applicantsOpen', 'applicantsClosed'));
    }

    public function viewApplications(Request $request)
    {
        $id = $request->hiringID;
        $dateToday = Carbon::now()->format('Y-m-d');
        // Fetch hiring details with applicants and user requirements
        $hiring = Hiring::with(['applicants.SalaryGrade', 'applicants.user.requirement'])
                        ->where('id', $id)
                        ->first();
                        
        // Fetch hiring status separately
        $hiringStatus = Hiring::where('id', $id)->value('job_status');

        // Filter applicants based on user type and hiring status
        if (Auth::user()->usertype === 'guest' || Auth::user()->usertype === 'selection board') {
            // Filter applicants based on user type and hiring status
            if (in_array($hiringStatus, ['Pre-Employment Exam', 'Competency Exam', 'Initial Interview', 'Final Interview', 'PsychoTest', 'BEI'])) {
                $applicants = $hiring->applicants->filter(function ($applicant) use ($hiringStatus) {
                    // Filter only if $hiringStatus is in the specified array and application status is 'Passed'
                    return $applicant->application_status === 'Passed';
                })->map(function ($applicant) {
                    // Map filtered applicants to desired format
                    return [
                        'applicant_id' => $applicant->id,
                        'user_id' => $applicant->user_id,
                        'user_name' => $applicant->user->name,
                        'application_status' => $applicant->application_status,
                        'competency' => $applicant->competency_exam,
                        'competency_result' => $applicant->competency_exam_result,
                        'pre_employment' => $applicant->pre_employment_exam,
                        'pre_result' => $applicant->pre_employment_exam_result,
                        'initial' => $applicant->initial_interview,
                        'initial_result' => $applicant->initial_interview_result,
                        'final' => $applicant->final_interview,
                        'psycho' => $applicant->psycho_test,
                        'psycho_result' => $applicant->psycho_test_result,
                        'bei' => $applicant->bei_result,
                        'date_applied' => $applicant->created_at,
                        'csc_form' => optional($applicant->user->requirement)->csc_form,
                        'tor_diploma' => optional($applicant->user->requirement)->tor_diploma,
                        'training_cert' => optional($applicant->user->requirement)->training_cert,
                        'eligibility' => optional($applicant->user->requirement)->eligibility,
                        'job_type' => optional($applicant->hiring)->job_type,
                        'selection_id' => optional($applicant->SalaryGrade)->selection_board_id,
                        'applicantId' => optional($applicant->SalaryGrade)->applicant_id,
                    ];
                });
            } else {
                // If $hiringStatus is not in the specified array, fetch all applicants without filtering
                $applicants = $hiring->applicants->map(function ($applicant) {
                    return [
                        'applicant_id' => $applicant->id,
                        'user_id' => $applicant->user_id,
                        'user_name' => $applicant->user->name,
                        'application_status' => $applicant->application_status,
                        'competency' => $applicant->competency_exam,
                        'competency_result' => $applicant->competency_exam_result,
                        'pre_employment' => $applicant->pre_employment_exam,
                        'pre_result' => $applicant->pre_employment_exam_result,
                        'initial' => $applicant->initial_interview,
                        'initial_result' => $applicant->initial_interview_result,
                        'final' => $applicant->final_interview,
                        'psycho' => $applicant->psycho_test,
                        'psycho_result' => $applicant->psycho_test_result,
                        'bei' => $applicant->bei_result,
                        'date_applied' => $applicant->created_at,
                        'csc_form' => optional($applicant->user->requirement)->csc_form,
                        'tor_diploma' => optional($applicant->user->requirement)->tor_diploma,
                        'training_cert' => optional($applicant->user->requirement)->training_cert,
                        'eligibility' => optional($applicant->user->requirement)->eligibility,
                        'job_type' => optional($applicant->hiring)->job_type,
                        'selection_id' => optional($applicant->SalaryGrade)->selection_board_id,
                        'applicantId' => optional($applicant->SalaryGrade)->applicant_id,
                    ];
                });
            }
        } else {
            // For other user types, fetch all applicants without filtering
            $applicants = $hiring->applicants->map(function ($applicant) {
                return [
                    'applicant_id' => $applicant->id,
                    'user_id' => $applicant->user_id,
                    'user_name' => $applicant->user->name,
                    'application_status' => $applicant->application_status,
                    'competency' => $applicant->competency_exam,
                    'competency_result' => $applicant->competency_exam_result,
                    'pre_employment' => $applicant->pre_employment_exam,
                    'pre_result' => $applicant->pre_employment_exam_result,
                    'initial' => $applicant->initial_interview,
                    'initial_result' => $applicant->initial_interview_result,
                    'final' => $applicant->final_interview,
                    'psycho' => $applicant->psycho_test,
                    'psycho_result' => $applicant->psycho_test_result,
                    'bei' => $applicant->bei_result,
                    'date_applied' => $applicant->created_at,
                    'csc_form' => optional($applicant->user->requirement)->csc_form,
                    'tor_diploma' => optional($applicant->user->requirement)->tor_diploma,
                    'training_cert' => optional($applicant->user->requirement)->training_cert,
                    'eligibility' => optional($applicant->user->requirement)->eligibility,
                    'job_type' => optional($applicant->hiring)->job_type,
                    'selection_id' => optional($applicant->SalaryGrade)->selection_board_id,
                    'applicantId' => optional($applicant->SalaryGrade)->applicant_id,
                ];
            });
        }        

        // Prepare the data for the view
        $data = [
            'hiring_id' => $hiring->id,
            'job_position' => $hiring->job_position,
            'hiring_status' => $hiring->job_status,
            'job_description' => $hiring->job_description,
            'contract_type' => $hiring->contract_type,
            'job_type' => $hiring->job_type,
            'salary_grade' => $hiring->salary_grade,
            'department' => $hiring->department,
            'competency_date' => $hiring->competency_exam_date,
            'employment_date' => $hiring->pre_employment_exam_date,
            'initial_date' => $hiring->initial_interview_date,
            'final_date' => $hiring->final_interview_date,
            'psycho_date' => $hiring->psycho_test_date,
            'bei_date' => $hiring->bei_date,
            'created_at' => $hiring->created_at,
            'closing' => $hiring->closing,
            'applicants' => $applicants,
            'date' => $dateToday,
        ];
        
        // Determine the view based on user type
        if (Auth::user()->usertype === 'guest') {
            return view('User.Guest.joboverview', $data);
        } else {
            return view('Admin.application_view', $data);
        }
    }


    public function guestSelectQualified(Request $request)
    {
        $hiringID = $request->hiringID;
        $job = Hiring::select('job_status', 'job_position')->where('id', $hiringID)->first();
        $selectedIds = $request->applicantSelected;
        $allApplicantIds = Applicant::where('hiring_id', $hiringID)->pluck('id');
        $notSelectedIds = $allApplicantIds->diff($selectedIds ?? []);

        // Update application statuses for not selected applicants
        Applicant::whereIn('id', $notSelectedIds)->update(['application_status' => 'Failed']);
        if ($selectedIds !== NULL) {
            // Update application statuses for selected applicants
            Applicant::whereIn('id', $selectedIds)->update(['application_status' => 'Passed']);
        }

        // Update job status based on the current status
        $updateStatus = Hiring::find($hiringID);
        switch ($job->job_status) {
            case 'Closed':
                $updateStatus->job_status = 'Competency Exam';
                break;
            case 'Competency Exam':
                $updateStatus->job_status = 'Pre-Employment Exam';
                break;
            case 'Pre-Employment Exam':
                $updateStatus->job_status = 'Initial Interview';
                break;
        }

        if ($updateStatus->save()) {
            // Create notifications for not selected applicants
            $notSelectedMessage = 'Thank you for your interest in' . $job->job_position . ', but sadly you cannot advance to the ' . $updateStatus->job_status . ' stage of the process. Maybe you should consider applying again in the future.';
            foreach ($notSelectedIds as $id) {
                Notification::create([
                    'user_id' => $id,
                    'type' => 'update',
                    'message' => $notSelectedMessage,
                    'status' => 'unread',
                ]);
            }

            // Create notifications for selected applicants if any
            if ($selectedIds !== NULL) {
                $selectedMessage = 'Thank you for your interest, your application for the job ' . $job->job_position . ' has advanced to the ' . $updateStatus->job_status . ' stage of the process. Please wait for further instructions.';
                foreach ($selectedIds as $id) {
                    Notification::create([
                        'user_id' => $id,
                        'type' => 'update',
                        'message' => $selectedMessage,
                        'status' => 'unread',
                    ]);
                }
            }

            return redirect()->back()->with('info', 'Applicants updated successfully.');
        } else {
            return redirect()->back()->with('danger', 'Applicants updated unsuccessfully.');
        }
    }

    public function updateApplicant(Request $request){
        $Purpose = $request->for;
        $ApplicantID = $request->applicant_id;

        $applicant = Applicant::with('user', 'hiring')
            ->where('id', $ApplicantID)
            ->first();

        if($Purpose == 'Competency'){
            // Step 3: Perform validation on the incoming data
            $validatedData = $request->validate([
                'competencyFile' => 'required|file|mimes:pdf|max:2048',
                'CompetencyResult' => 'required|in:Passed,Failed',
            ]);
    
            // Handle competency file upload
            if ($request->hasFile('competencyFile')) {
                $file = $request->file('competencyFile');
                $path = $file->store('exam_results_file', 'public');
                $validatedData['competency_exam'] = $path;
            }
    
            // Update competency information in the database
            $affected = DB::table('applicants')
                ->where('id', $ApplicantID)
                ->update([
                    'competency_exam' => $validatedData['competency_exam'],
                    'competency_exam_result' => $request->CompetencyResult,
                ]);
    
            // Check if the update was successful
            if($affected){
                return redirect()->back()->with('success', 'Applicant updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update applicant.');
            }
        } elseif($Purpose == 'Pre-Employment'){
            // Step 3: Perform validation on the incoming data
            $validatedData = $request->validate([
                'preEmploymentFile' => 'required|file|mimes:pdf|max:2048',
                'preEmploymentResult' => 'required|in:Passed,Failed',
            ]);
    
            // Handle pre-employment file upload
            if ($request->hasFile('preEmploymentFile')) {
                $file = $request->file('preEmploymentFile');
                $path = $file->store('exam_results_file', 'public');
                $validatedData['pre_employment_exam'] = $path;
            }
    
            // Update pre-employment information in the database
            $affected = DB::table('applicants')
                ->where('id', $ApplicantID)
                ->update([
                    'pre_employment_exam' => $validatedData['pre_employment_exam'],
                    'pre_employment_exam_result' => $request->preEmploymentResult,
                ]);
    
            if($affected){
                return redirect()->back()->with('success', 'Applicant updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update applicant.');
            }
        }elseif($Purpose == 'Initial Interview'){
            // Step 3: Perform validation on the incoming data
            $validatedData = $request->validate([
                'InitialResult' => 'required|in:Passed,Failed',
            ]);
    
            // Update pre-employment information in the database
            $affected = DB::table('applicants')
                ->where('id', $ApplicantID)
                ->update([
                    'initial_interview' => $request->InitialResult,
                    'initial_interview_result' => $request->InitialResult,
                ]);
    
            if($affected){
                return redirect()->back()->with('success', 'Applicant updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update applicant.');
            }
        }elseif($Purpose == 'Psychometric Test'){
            // Step 3: Perform validation on the incoming data
            $validatedData = $request->validate([
                'PsychoFile' => 'required|file|mimes:pdf|max:2048',
                'PsychoResult' => 'required|in:Passed,Failed',
            ]);
            
            // Handle pre-employment file upload
            if ($request->hasFile('PsychoFile')) {
                $file = $request->file('PsychoFile');
                $path = $file->store('exam_results_file', 'public');
                $validatedData['psycho_testPath'] = $path;
            }
            
            // Update pre-employment information in the database
            $affected = DB::table('applicants')
                ->where('id', $ApplicantID)
                ->update([
                    'psycho_test' => $validatedData['psycho_testPath'],
                    'psycho_test_result' => $request->PsychoResult,
                ]);
            
            if($affected){
                return redirect()->back()->with('success', 'Applicant updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update applicant.');
            }            
        }elseif ($Purpose == 'Final Interview') {
            $id = $request->hiringID;
            // Step 3: Perform validation on the incoming data
            $affected = DB::table('applicants')
                ->where('id', $ApplicantID)
                ->update([
                    'final_interview' => $request->FinalResult,
                ]);
        
            if ($affected) {
                if ($request->FinalResult === 'Passed') {
                    $applicantsFailed = Applicant::select('id')
                        ->where('hiring_id', $id)
                        ->where('application_status', 'Passed')
                        ->where('id', '!=', $ApplicantID)
                        ->get();

                    foreach ($applicantsFailed as $failed) {
                        Applicant::where('id', $failed->id)
                            ->update([
                                'application_status' => 'Failed',
                                'final_interview' => 'Failed',
                            ]);
                            $message = 'This notification is to inform you that your final interview was outstanding for the '. $applicant->hiring->job_position . ', but some applicant surpass your performance. Thank you!';
                            Notification::create([
                                'sender_id' => NULL,
                                'receiver_id' => $failed->id,
                                'message' => $message,
                                'status' => 'unread',
                                'type' => 'update',
                            ]);
                        }

                    $message = 'This notification is to inform you that you have passed the final interview for '. $applicant->hiring->job_position . '. Please wait for our call or mail. Thank you!';
                    Notification::create([
                        'sender_id' => NULL,
                        'receiver_id' => $applicant->user_id,
                        'message' => $message,
                        'status' => 'unread',
                        'type' => 'update',
                    ]);
                } else {
                    $message = 'This notification is to inform you that your final interview was outstanding for the '. $applicant->hiring->job_position . ', but some applicant surpass your performance. Thank you!';
                    Notification::create([
                        'sender_id' => NULL,
                        'receiver_id' => $applicant->user_id,
                        'message' => $message,
                        'status' => 'unread',
                        'type' => 'update',
                    ]);
                }
                if ($request->FinalResult === 'Passed') {
                    $changedStatus = Hiring::where('id', $id)
                        ->update([
                            'job_status' => 'Archived',
                        ]);
                    return redirect()->back()->with('success', 'Applicant updated successfully.');
                }else{
                    return redirect()->back()->with('success', 'Applicant updated successfully.');
                }
            } else {
                return redirect()->back()->with('error', 'Failed to update applicant.');
            }
        }elseif($Purpose == 'BEI'){
            // Step 3: Perform validation on the incoming data
            $affected = DB::table('applicants')
                ->where('id', $ApplicantID)
                ->update([
                    'bei_result' => $request->BEIResult,
                ]);
    
            if($affected){
                return redirect()->back()->with('success', 'Applicant updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update applicant.');
            }
        }
    }    
    
    
    public function IndividualBEI(Request $request){
        $id = $request->applicantID;
        $fetchInfo = Applicant::with(['user', 'hiring', 'SalaryGrade'])
        ->where('id', $id)
        ->first();

        $competenciesSG1to6 = [
                ['name' => 'DEPENDABILITY', 'labelDB' => 'dependability'],
                ['name' => 'CREATIVE & INNOVATIVE THINKING', 'labelDB' => 'creative'],
                ['name' => 'INITIATIVE', 'labelDB' => 'initiative'],
                ['name' => 'TIME MANAGEMENT', 'labelDB' => 'time_management'],
                ['name' => 'PLANNING & ORGANIZING', 'labelDB' => 'planning']
            ];
        
            $competenciesSG9to16 = [
                ['name' => 'DEPENDABILITY', 'labelDB' => 'dependability'],
                ['name' => 'ADAPTABILITY', 'labelDB' => 'adaptability'],
                ['name' => 'CREATIVE & INNOVATIVE THINKING', 'labelDB' => 'creative'],
                ['name' => 'TEAMWORK', 'labelDB' => 'teamwork'],
                ['name' => 'SELF MANAGEMENT', 'labelDB' => 'self_management'],
                ['name' => 'ORGANIZATIONAL AWARENESS', 'labelDB' => 'org_awareness'],
                ['name' => 'COMMUNICATION', 'labelDB' => 'communication'],
                ['name' => 'INITIATIVE', 'labelDB' => 'initiative'],
                ['name' => 'SERVICE DELIVERY', 'labelDB' => 'service_delivery'],
                ['name' => 'CUSTOMER FOCUS', 'labelDB' => 'customer_focus']
            ];
        
            $competenciesSG18to24 = [
                ['name' => 'CREATIVE & INNOVATIVE THINKING', 'labelDB' => 'creative'],
                ['name' => 'TEAMWORK', 'labelDB' => 'teamwork'],
                ['name' => 'SELF MANAGEMENT', 'labelDB' => 'self_management'],
                ['name' => 'MANAGING PROJECTS OR PROGRAMS', 'labelDB' => 'management'],
                ['name' => 'STAFF MANAGEMENT', 'labelDB' => 'staff_management'],
                ['name' => 'ORGANIZATIONAL AWARENESS', 'labelDB' => 'org_awareness'],
                ['name' => 'STRATEGIC PLANNING', 'labelDB' => 'strategic_planning'],
                ['name' => 'MONITORING AND EVALUATING', 'labelDB' => 'monitor_evaluation'],
                ['name' => 'PLANNING, ORGANISING & DELIVERY', 'labelDB' => 'planning'],
                ['name' => 'SERVICE DELIVERY', 'labelDB' => 'service_delivery']
            ];
        
            $leadershipCompetencies = [
                ['name' => 'THINKING STRATEGICALLY & CREATIVELY', 'labelDB' => 'strategy_creatively'],
                ['name' => 'LEADING CHANGE', 'labelDB' => 'leading_change'],
                ['name' => 'BUILDING COLLABORATIVE INCLUSIVE WORKING RELATIONSHIPS', 'labelDB' => 'building_relationship'],
                ['name' => 'MANAGING PERFORMANCE AND COACHING FOR RESULTS', 'labelDB' => 'coaching'],
                ['name' => 'CREATING & NURTURING A HIGH PERFORMING ORGANISATION', 'labelDB' => 'create_nurture_performance']
            ];
            
        $data = [
            'salaryGrade' => $fetchInfo->hiring->salary_grade,
            'jobTitle' => $fetchInfo->hiring->job_position,
            'name' => $fetchInfo->user->name,
            'applicantID' => $id,
            'hiringID' => $fetchInfo->hiring->id,
            'leadershipCompetencies' => NULL,
        ];

        if ($fetchInfo->hiring->salary_grade >= 1 && $fetchInfo->hiring->salary_grade <= 8) {
            $data['competencies'] = $competenciesSG1to6;
        } elseif ($fetchInfo->hiring->salary_grade >= 9 && $fetchInfo->hiring->salary_grade <= 17) {
            $data['competencies'] = $competenciesSG9to16;
        } elseif ($fetchInfo->hiring->salary_grade >= 18 && $fetchInfo->hiring->salary_grade <= 24) {
            $data['competencies'] = $competenciesSG18to24;
            $data['leadershipCompetencies'] = $leadershipCompetencies;
        }
        
        //check if there is an occuring BEI for the applicant 
        return view('User.Guest.layouts.addBEIGuest', $data);
    }
    public function IndividualBEIGuest(Request $request){
        $id = $request->applicantID;
        $fetchInfo = Applicant::with(['user', 'hiring', 'SalaryGrade'])
        ->where('id', $id)
        ->first();

        $competenciesSG1to6 = [
                ['name' => 'DEPENDABILITY', 'labelDB' => 'dependability'],
                ['name' => 'CREATIVE & INNOVATIVE THINKING', 'labelDB' => 'creative'],
                ['name' => 'INITIATIVE', 'labelDB' => 'initiative'],
                ['name' => 'TIME MANAGEMENT', 'labelDB' => 'time_management'],
                ['name' => 'PLANNING & ORGANIZING', 'labelDB' => 'planning']
            ];
        
            $competenciesSG9to16 = [
                ['name' => 'DEPENDABILITY', 'labelDB' => 'dependability'],
                ['name' => 'ADAPTABILITY', 'labelDB' => 'adaptability'],
                ['name' => 'CREATIVE & INNOVATIVE THINKING', 'labelDB' => 'creative'],
                ['name' => 'TEAMWORK', 'labelDB' => 'teamwork'],
                ['name' => 'SELF MANAGEMENT', 'labelDB' => 'self_management'],
                ['name' => 'ORGANIZATIONAL AWARENESS', 'labelDB' => 'org_awareness'],
                ['name' => 'COMMUNICATION', 'labelDB' => 'communication'],
                ['name' => 'INITIATIVE', 'labelDB' => 'initiative'],
                ['name' => 'SERVICE DELIVERY', 'labelDB' => 'service_delivery'],
                ['name' => 'CUSTOMER FOCUS', 'labelDB' => 'customer_focus']
            ];
        
            $competenciesSG18to24 = [
                ['name' => 'CREATIVE & INNOVATIVE THINKING', 'labelDB' => 'creative'],
                ['name' => 'TEAMWORK', 'labelDB' => 'teamwork'],
                ['name' => 'SELF MANAGEMENT', 'labelDB' => 'self_management'],
                ['name' => 'MANAGING PROJECTS OR PROGRAMS', 'labelDB' => 'management'],
                ['name' => 'STAFF MANAGEMENT', 'labelDB' => 'staff_management'],
                ['name' => 'ORGANIZATIONAL AWARENESS', 'labelDB' => 'org_awareness'],
                ['name' => 'STRATEGIC PLANNING', 'labelDB' => 'strategic_planning'],
                ['name' => 'MONITORING AND EVALUATING', 'labelDB' => 'monitor_evaluation'],
                ['name' => 'PLANNING, ORGANISING & DELIVERY', 'labelDB' => 'planning'],
                ['name' => 'SERVICE DELIVERY', 'labelDB' => 'service_delivery']
            ];
        
            $leadershipCompetencies = [
                ['name' => 'THINKING STRATEGICALLY & CREATIVELY', 'labelDB' => 'strategy_creatively'],
                ['name' => 'LEADING CHANGE', 'labelDB' => 'leading_change'],
                ['name' => 'BUILDING COLLABORATIVE INCLUSIVE WORKING RELATIONSHIPS', 'labelDB' => 'building_relationship'],
                ['name' => 'MANAGING PERFORMANCE AND COACHING FOR RESULTS', 'labelDB' => 'coaching'],
                ['name' => 'CREATING & NURTURING A HIGH PERFORMING ORGANISATION', 'labelDB' => 'create_nurture_performance']
            ];
            
        $data = [
            'salaryGrade' => $fetchInfo->hiring->salary_grade,
            'jobTitle' => $fetchInfo->hiring->job_position,
            'name' => $fetchInfo->user->name,
            'applicantID' => $id,
            'hiringID' => $fetchInfo->hiring->id,
            'leadershipCompetencies' => NULL,
        ];

        if ($fetchInfo->hiring->salary_grade >= 1 && $fetchInfo->hiring->salary_grade <= 8) {
            $data['competencies'] = $competenciesSG1to6;
        } elseif ($fetchInfo->hiring->salary_grade >= 9 && $fetchInfo->hiring->salary_grade <= 17) {
            $data['competencies'] = $competenciesSG9to16;
        } elseif ($fetchInfo->hiring->salary_grade >= 18 && $fetchInfo->hiring->salary_grade <= 24) {
            $data['competencies'] = $competenciesSG18to24;
            $data['leadershipCompetencies'] = $leadershipCompetencies;
        }
        
        //check if there is an occuring BEI for the applicant 
        return view('User.Guest.layouts.addBEIGuest', $data);
    }
    
    public function UploadBEI(Request $request){
        $applicantID = $request->applicationID;
        $hiringID = $request->hiringID;

        $salaryGrade = $request->salaryGrade;
        if($salaryGrade >= 1 && $salaryGrade <= 8){
            $data = [
                'dependability' => $request->dependability_rate.','. $request->dependability_situation. ','. $request->dependability_action. ','. $request->dependability_result,
                'creative' => $request->creative_rate.','. $request->creative_situation. ','. $request->creative_action. ','. $request->creative_result,
                'initiative' => $request->initiative_rate.','. $request->initiative_situation. ','. $request->initiative_action. ','. $request->initiative_result,
                'time_management' => $request->time_management_rate.','. $request->time_management_situation. ','. $request->time_management_action. ','. $request->time_management_result,
                'planning' => $request->planning_rate.','. $request->planning_situation. ','. $request->planning_action. ','. $request->planning_result,
            ];
        } elseif($salaryGrade >= 9 && $salaryGrade <= 17){
            $data = [
                'dependability' => $request->dependability_rate.','. $request->dependability_situation. ','. $request->dependability_action. ','. $request->dependability_result,
                'creative' => $request->creative_rate.','. $request->creative_situation. ','. $request->creative_action. ','. $request->creative_result,
                'adaptability' => $request->adaptability_rate.','. $request->adaptability_situation. ','. $request->adaptability_action. ','. $request->adaptability_result,
                'teamwork' => $request->teamwork_rate.','. $request->teamwork_situation. ','. $request->teamwork_action. ','. $request->teamwork_result,
                'self_management' => $request->self_management_rate.','. $request->self_management_situation. ','. $request->self_management_action. ','. $request->self_management_result,
                'org_awareness' => $request->org_awareness_rate.','. $request->org_awareness_situation. ','. $request->org_awareness_action. ','. $request->org_awareness_result,
                'communication' => $request->communication_rate.','. $request->communication_situation. ','. $request->communication_action. ','. $request->communication_result,
                'initiative' => $request->initiative_rate.','. $request->initiative_situation. ','. $request->initiative_action. ','. $request->initiative_result,
                'service_delivery' => $request->service_delivery_rate.','. $request->service_delivery_situation. ','. $request->service_delivery_action. ','. $request->service_delivery_result,
                'customer_focus' => $request->customer_focus_rate.','. $request->customer_focus_situation. ','. $request->customer_focus_action. ','. $request->customer_focus_result,
            ];
        } elseif($salaryGrade >= 18 && $salaryGrade <= 24){
            $data = [
                'creative' => $request->creative_rate.','. $request->creative_situation. ','. $request->creative_action. ','. $request->creative_result,
                'teamwork' => $request->teamwork_rate.','. $request->teamwork_situation. ','. $request->teamwork_action. ','. $request->teamwork_result,
                'self_management' => $request->self_management_rate.','. $request->self_management_situation. ','. $request->self_management_action. ','. $request->self_management_result,
                'management' => $request->management_rate.','. $request->management_situation. ','. $request->management_action. ','. $request->management_result,
                'staff_management' => $request->staff_management_rate.','. $request->staff_management_situation. ','. $request->staff_management_action. ','. $request->staff_management_result,
                'org_awareness' => $request->org_awareness_rate.','. $request->org_awareness_situation. ','. $request->org_awareness_action. ','. $request->org_awareness_result,
                'strategic_planning' => $request->strategic_planning_rate.','. $request->strategic_planning_situation. ','. $request->strategic_planning_action. ','. $request->strategic_planning_result,
                'monitor_evaluation' => $request->monitor_evaluation_rate.','. $request->monitor_evaluation_situation. ','. $request->monitor_evaluation_action. ','. $request->monitor_evaluation_result,
                'planning' => $request->planning_rate.','. $request->planning_situation. ','. $request->planning_action. ','. $request->planning_result,
                'service_delivery' => $request->service_delivery_rate.','. $request->service_delivery_situation. ','. $request->service_delivery_action. ','. $request->service_delivery_result,
                'strategy_creatively' => $request->strategy_creatively_rate.','. $request->strategy_creatively_situation. ','. $request->strategy_creatively_action. ','. $request->strategy_creatively_result,
                'leading_change' => $request->leading_change_rate.','. $request->leading_change_situation. ','. $request->leading_change_action. ','. $request->leading_change_result,
                'building_relationship' => $request->building_relationship_rate.','. $request->building_relationship_situation. ','. $request->building_relationship_action. ','. $request->building_relationship_result,
                'coaching' => $request->coaching_rate.','. $request->coaching_situation. ','. $request->coaching_action. ','. $request->coaching_result,
                'create_nurture_performance' => $request->create_nurture_performance_rate.','. $request->create_nurture_performance_situation. ','. $request->create_nurture_performance_action. ','. $request->create_nurture_performance_result,
            ];
        }
        $beiApplicant = new SalaryGrade($data);
        $beiApplicant->applicant_id = $applicantID;
        $beiApplicant->salary_grade = $salaryGrade;
        $beiApplicant->selection_board_id = Auth::user()->id;

        if ($beiApplicant->save()) {
            return redirect()->route('applications.view', ['hiringID' => $hiringID])
                             ->with('success', 'BEI has been successfully added.');
        } else {
            return redirect()->route('applications.view', ['hiringID' => $hiringID])
                             ->with('error', 'BEI has not been added.');
        }
    }

    public function generateBEI(Request $request)
    {
        $usertype = Auth::user()->usertype;

        if ($usertype == 'admin' || $usertype == 'hr') {
            $beiData = SalaryGrade::select('s.*', 'h.job_type', 'h.job_position', 'h.initial_interview_date', 'h.bei_date', 'u.name', 'a.id')
            ->from('salary_grades as s')
            ->leftJoin('applicants as a', 'a.id', '=', 's.applicant_id')
            ->leftJoin('users as u', 'u.id', '=', 'a.user_id')
            ->leftJoin('hirings as h', 'h.id', '=', 'a.hiring_id')
            ->where('applicant_id', $request->applicantID)
            ->get();// Assuming only one applicant
        } else {
            $beiData = SalaryGrade::select('s.*', 'h.job_type', 'h.job_position','h.initial_interview_date', 'h.bei_date', 'u.name', 'a.id')
                ->from('salary_grades as s')
                ->leftJoin('applicants as a', 'a.id', '=', 's.applicant_id')
                ->leftJoin('users as u', 'u.id', '=', 'a.user_id')
                ->leftJoin('hirings as h', 'h.id', '=', 'a.hiring_id')
                ->where('applicant_id', $request->applicantID)
                ->where('s.selection_board_id', Auth::user()->id)
                ->get();
        }

        $allCompetencies = [];
        // Iterate over each SalaryGrade
        foreach ($beiData as $salaryGrade) {
            $sgID = $salaryGrade->salary_grade;
            $competencies = [];

            $competencies = [];

            if ($sgID >= 1 && $sgID <= 8) {
                $competencies = [
                    'dependability' => ['label' => 'DEPENDABILITY', 'data' => explode(',', $salaryGrade->dependability)],
                    'creative' => ['label' => 'CREATIVE & INNOVATIVE THINKING', 'data' => explode(',', $salaryGrade->creative)],
                    'initiative' => ['label' => 'INITIATIVE', 'data' => explode(',', $salaryGrade->initiative)],
                    'time_management' => ['label' => 'TIME MANAGEMENT', 'data' => explode(',', $salaryGrade->time_management)],
                    'planning' => ['label' => 'PLANNING & ORGANIZING', 'data' => explode(',', $salaryGrade->planning)],
                ];
            } elseif ($sgID >= 9 && $sgID <= 17) {
                $competencies = [
                    'dependability' => ['label' => 'DEPENDABILITY', 'data' => explode(',', $salaryGrade->dependability)],
                    'creative' => ['label' => 'CREATIVE & INNOVATIVE THINKING', 'data' => explode(',', $salaryGrade->creative)],
                    'adaptability' => ['label' => 'ADAPTABILITY', 'data' => explode(',', $salaryGrade->adaptability)],
                    'teamwork' => ['label' => 'TEAMWORK', 'data' => explode(',', $salaryGrade->teamwork)],
                    'self_management' => ['label' => 'SELF MANAGEMENT', 'data' => explode(',', $salaryGrade->self_management)],
                    'org_awareness' => ['label' => 'ORGANIZATIONAL AWARENESS', 'data' => explode(',', $salaryGrade->org_awareness)],
                    'communication' => ['label' => 'COMMUNICATION', 'data' => explode(',', $salaryGrade->communication)],
                    'initiative' => ['label' => 'INITIATIVE', 'data' => explode(',', $salaryGrade->initiative)],
                    'service_delivery' => ['label' => 'SERVICE DELIVERY', 'data' => explode(',', $salaryGrade->service_delivery)],
                    'customer_focus' => ['label' => 'CUSTOMER FOCUS', 'data' => explode(',', $salaryGrade->customer_focus)],
                ];
            } elseif ($sgID >= 18 && $sgID <= 24) {
                $competencies = [
                    'creative' => ['label' => 'CREATIVE & INNOVATIVE THINKING', 'data' => explode(',', $salaryGrade->creative)],
                    'teamwork' => ['label' => 'TEAMWORK', 'data' => explode(',', $salaryGrade->teamwork)],
                    'self_management' => ['label' => 'SELF MANAGEMENT', 'data' => explode(',', $salaryGrade->self_management)],
                    'management' => ['label' => 'MANAGING PROJECTS OR PROGRAMS', 'data' => explode(',', $salaryGrade->management)],
                    'staff_management' => ['label' => 'STAFF MANAGEMENT', 'data' => explode(',', $salaryGrade->staff_management)],
                    'org_awareness' => ['label' => 'ORGANIZATIONAL AWARENESS', 'data' => explode(',', $salaryGrade->org_awareness)],
                    'strategic_planning' => ['label' => 'STRATEGIC PLANNING', 'data' => explode(',', $salaryGrade->strategic_planning)],
                    'monitor_evaluation' => ['label' => 'MONITORING AND EVALUATING', 'data' => explode(',', $salaryGrade->monitor_evaluation)],
                    'planning' => ['label' => 'PLANNING, ORGANISING & DELIVERY', 'data' => explode(',', $salaryGrade->planning)],
                    'service_delivery' => ['label' => 'SERVICE DELIVERY', 'data' => explode(',', $salaryGrade->service_delivery)],
                    'leadership' => [
                        'strategy_creatively' => ['label' => 'THINKING STRATEGICALLY & CREATIVELY', 'data' => explode(',', $salaryGrade->strategy_creatively)],
                        'leading_change' => ['label' => 'LEADING CHANGE', 'data' => explode(',', $salaryGrade->leading_change)],
                        'building_relationship' => ['label' => 'BUILDING COLLABORATIVE INCLUSIVE WORKING RELATIONSHIPS', 'data' => explode(',', $salaryGrade->building_relationship)],
                        'coaching' => ['label' => 'MANAGING PERFORMANCE AND COACHING FOR RESULTS', 'data' => explode(',', $salaryGrade->coaching)],
                        'create_nurture_performance' => ['label' => 'CREATING & NURTURING A HIGH PERFORMING ORGANISATION', 'data' => explode(',', $salaryGrade->create_nurture_performance)],
                    ]
                ];
            }
            $allCompetencies[] = [
                'sgID' => $salaryGrade->salary_grade,
                'competencies' => $competencies
            ];
        };

        foreach($beiData as $item){
            $name = $item->name;
            $jobPosition = $item->job_position;
            $beiDate = $item->bei_date;
            $initial_interview = $item->initial_interview_date;
            $jobType = $item->job_type;
        }
        $data = [
            'name' => $name,
            'position' => $jobPosition,
            'beiDate' => $beiDate,
            'initialInterview' => $initial_interview,
            'jobType' => $jobType,
            'beiDatas' => $allCompetencies,
        ];

        $pdf = Pdf::loadView('BEI_template', $data)->setPaper('a4', 'landscape');

        return $pdf->stream('BEI.pdf');
    }

    public function initialInterview(Request $request) {
        $hiringID = $request->hiringID;
    
        $hiring = Hiring::with(['applicants.user.requirement'])
                    ->where('id', $hiringID)
                    ->whereHas('applicants', function($query) {
                        $query->where('application_status', 'Passed');
                    })
                    ->first();
    
        $applicants = $hiring->applicants->map(function ($applicant) {
            return [
                'applicant_id' => $applicant->id,
                'user_id' => $applicant->user_id,
                'user_name' => $applicant->user->name,
            ];
        });
    
        $data = [
            'hiring_id' => $hiringID,
            'salary_grade' => $hiring->salary_grade,
            'applicants' => $applicants,
        ];
        
        return view('User.Guest.layouts.initial_interview', $data);
    }
    
    public function shortlistHR(Request $request)
    {
        $hiringID = $request->hiringID;
        $statusHiring = Hiring::select('job_status')->where('id', $hiringID)->first();
        $typeHiring = Hiring::select('job_type')->where('id', $hiringID)->first();
        $job = Hiring::select('job_position')->where('id', $hiringID)->pluck('job_position')->first();
        $selectedIds = $request->applicantSelected;
        $allApplicantIds = Applicant::where('hiring_id', $hiringID)->pluck('id');
        $notSelectedIds = $allApplicantIds->diff($selectedIds);

        // Update application_status for not selected applicants
        Applicant::whereIn('id', $notSelectedIds)->update(['application_status' => 'Failed']);
        foreach($notSelectedIds as $not){
            $user_id = Applicant::select('user_id')->where('id', $not)->get();
            $user_id = $user_id[0]['user_id'];
            $message = "The shortlisting is done for the position " . $job . " you applied for, and your application did not pass the requirements. Please try again next time.";
            Notification::create([
                'sender_id' => Auth::user()->id,
                'receiver_id' => $user_id,
                'message' => $message,
                'status' => 'unread',
                'type' => 'update',
            ]);
        }

        // If there are selected applicants, update their status
        if ($selectedIds !== null) {
            Applicant::whereIn('id', $selectedIds)->update(['application_status' => 'Passed']);
            foreach($selectedIds as $sel){
                $user_id = Applicant::select('user_id')->where('id', $sel)->get();
                $user_id = $user_id[0]['user_id'];
                $message = "The shortlisting is done for the position " . $job . "you applied for, and your application has been shortlisted. Please wait for the date for update about the next stage."; 
                Notification::create([
                   'sender_id' => Auth::user()->id,
                   'receiver_id' => $user_id,
                   'message' => $message,
                   'status' => 'unread',
                    'type' => 'update',
                ]);
            }
        }

        // Update job_status in the hiring record
        $updateStatus = Hiring::find($hiringID);
        if ($typeHiring->job_type === 'SRS-1' || $statusHiring->job_status === 'Entry') {
            $updateStatus->job_status = 'Pre-Employment Exam';
        } else {
            $updateStatus->job_status = 'BEI';
        }

        // Save the updated status and return appropriate response
        if ($updateStatus->save()) {
            return redirect()->back()->with('info', 'Applicants updated successfully.');
        } else {
            return redirect()->back()->with('danger', 'Applicants updated unsuccessfully.');
        }
    }
}