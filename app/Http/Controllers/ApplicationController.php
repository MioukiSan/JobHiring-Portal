<?php

namespace App\Http\Controllers;

use App\Models\Hiring;
use App\Models\Applicant;
use App\Models\SalaryGrade;
use App\Models\Notification;
use Illuminate\Http\Request;
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

        // Fetch hiring details with applicants and user requirements
        $hiring = Hiring::with(['applicants.SalaryGrade', 'applicants.user.requirement'])
                        ->where('id', $id)
                        ->first();
                        
        // Fetch hiring status separately
        $hiringStatus = Hiring::where('id', $id)->value('job_status');

        // Filter applicants based on user type and hiring status
        if (Auth::user()->usertype === 'guest' || Auth::user()->usertype === 'hr') {
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
                        'dependability' => $applicant->SalaryGrade->dependability,
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
        $statusHiring = Hiring::select('job_status')->where('id', $hiringID)->get();
        $selectedIds = $request->applicantSelected;
        $allApplicantIds = Applicant::where('hiring_id', $hiringID)->pluck('id');
        $notSelectedIds = $allApplicantIds->diff($selectedIds);

        if($selectedIds === NULL){
            Applicant::whereIn('id', $notSelectedIds)->update(['application_status' => 'Failed']);
            $updateStatus = Hiring::find($hiringID);
            $updateStatus->job_status = ($statusHiring === 'Closed' || $statusHiring === 'Final Shortlisting') ? 'Competency Exam' : 'Initial Interview';
            if($updateStatus->save()){
                return redirect()->back()->with('info', 'Applicants updated successfully.');
            } else {
                return redirect()->back()->with('danger', 'Applicants updated unsuccessfully.');
            }
        } else {
            Applicant::whereIn('id', $notSelectedIds)->update(['application_status' => 'Failed']);
            Applicant::whereIn('id', $selectedIds)->update(['application_status' => 'Passed']);
            $updateStatus = Hiring::find($hiringID);
            $updateStatus->job_status = ($statusHiring === 'Closed' || $statusHiring === 'Final Shortlisting') ? 'Competency Exam' : 'Initial Interview';
            if($updateStatus->save()){
                return redirect()->back()->with('info', 'Applicants updated successfully.');
            } else {
                return redirect()->back()->with('danger', 'Applicants updated unsuccessfully.');
            }
        }
    }
    
    public function updateApplicant(Request $request){
        $Purpose = $request->for;
        $ApplicantID = $request->applicant_id;

        
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
                'InitialFile' => 'required|file|mimes:pdf|max:2048',
                'InitialResult' => 'required|in:Passed,Failed',
            ]);
    
            // Handle pre-employment file upload
            if ($request->hasFile('InitialFile')) {
                $file = $request->file('InitialFile');
                $path = $file->store('exam_results_file', 'public');
                $validatedData['initial_interview'] = $path;
            }
    
            // Update pre-employment information in the database
            $affected = DB::table('applicants')
                ->where('id', $ApplicantID)
                ->update([
                    'initial_interview' => $validatedData['initial_interview'],
                    'initial_interview_result' => $request->InitialResult,
                ]);
    
            if($affected){
                return redirect()->back()->with('success', 'Applicant updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update applicant.');
            }
        }
    }    
    
    public function IndividualBEI(Request $request){
        $applicantID = $request->ApplicantID;
        $fetchInfo = Applicant::with(['user', 'hiring'])
        ->where('id', $applicantID)
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
                ['name' => 'CREATING & NURTURING A HIGH PERFORMING ORGANISATION', 'labelDB' => 'creating_nurture_performance']
            ];

        $data = [
            'salaryGrade' => $fetchInfo->hiring->salary_grade,
            'jobTitle' => $fetchInfo->hiring->job_position,
            'name' => $fetchInfo->user->name,
            'applicantID' => $applicantID,
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

        return view('Admin.addBEI', $data);
    }
    
    public function UploadBEI(Request $request){
        $applicantID = $request->applicationID;
        $salaryGrade = $request->salaryGrade;
        $rate = $request->creative_rate .',' . $request->creative_situation .',' . $request->creative_action .',' . $request->creative_result;
        dd($rate);
        if($salaryGrade >= 1 && $salaryGrade <= 8){
            $competencies = ['dependability','creative', 'initiative',
                'time_management', 'planning'];
            foreach($competencies as $competency) {
                echo $competency;
                // Build the string using values from the request
                $competency =   $request->{$competency . '_rate'} . ',' . 
                                $request->{$competency . '_situation'} . ',' . 
                                $request->{$competency . '_action'} . ',' . 
                                $request->{$competency . '_result'};
            }
        }
        // } elseif($salaryGrade >= 9 && $salaryGrade <= 17){
        //     $competencies = ['dependability','adaptability', 'creative', 'teamwork',
        //        'self_management', 'org_awareness', 'communication', 'initiative',
        //        'service_delivery', 'customer_focus'];
        //     foreach($competencies as $competency){
        //         foreach($array as $item){
        //             $validated = $request->validate([
        //                 $competency.$item =>'required',
        //             ]);
        //         };
        //     }
        // } elseif($salaryGrade >= 18 && $salaryGrade <= 24){
        //     $competencies = ['creative', 'teamwork','self_management','management',
        //        'staff_management', 'org_awareness','strategic_planning','monitor_evaluation',
        //         'planning','service_delivery', 'strategy_creatively', 'leading_change', 
        //         'building_relationship', 'coaching', 'creating_nurture_performance'];
        //     foreach($competencies as $competency){
        //         foreach($array as $item){
        //             $validated = $request->validate([
        //                 $competency.$item =>'required',
        //             ]);
        //         };
        //     }
        // }
    }

    public function generateBEI()
    {
        // Define the data to pass to the view
        $data = [
            'content' => ['beach', 'fuck up']
        ];
        $pdf = Pdf::loadView('BEI_template', $data)->setPaper('a4', 'landscape');

        // Download the generated PDF
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
        $job = Hiring::select('job_position')->where('id', $hiringID)->first();
        $selectedIds = $request->applicantSelected;
        $allApplicantIds = Applicant::where('hiring_id', $hiringID)->pluck('id');
        $notSelectedIds = $allApplicantIds->diff($selectedIds);

        // Update application_status for not selected applicants
        Applicant::whereIn('id', $notSelectedIds)->update(['application_status' => 'Failed']);
        foreach($notSelectedIds as $not){
            $user_id = Applicant::select('user_id')->where('id', $not)->get();
            $user_id = $user_id[0]['user_id'];
            $message = "The shortlisting is done for the position " . $job . "you applied for, and your application did not pass the requirements. Please try again next time.";
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