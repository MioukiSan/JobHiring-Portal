<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Hiring;
use App\Models\Applicant;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Pagination\Paginator;

class JobHiringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
     public function index()
     {
        

         // Use left join to ensure jobs without applicants are also included
         $hiringOpen = Hiring::leftJoin('applicants', 'hirings.id', '=', 'applicants.hiring_id')
             ->select('hirings.*', DB::raw('COUNT(applicants.id) as application_count'))
             ->where('hirings.job_status', 'Open')
             ->groupBy('hirings.id')
             ->sortable()
             ->paginate(5);
     
             $hiringInProgress =  Hiring::leftJoin('applicants', 'hirings.id', '=', 'applicants.hiring_id')
             ->select('hirings.*', DB::raw('COUNT(applicants.id) as application_count'))
             ->where(function ($query) {
                 $query->where('hirings.job_status', '!=', 'Archived');
             })
             ->groupBy('hirings.id')
             ->sortable()
             ->paginate(5);
        
        $hiringArchived = Hiring::leftJoin('applicants', 'hirings.id', '=', 'applicants.hiring_id')
             ->select('hirings.*', DB::raw('COUNT(applicants.id) as application_count'))
             ->where('hirings.job_status', 'Archived')
             ->groupBy('hirings.id')
             ->sortable()
             ->paginate(5);
     
         return view('Admin.hiring', compact('hiringOpen', 'hiringInProgress', 'hiringArchived'));;
     }
     

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'job_position' => 'required|string|max:255',
            'JobDescriptionPDF' => 'required|file|mimes:pdf|max:2048',
            'salary_grade' => 'required|string|max:50',
            'contract_type' => 'required|in:COS,Permanent',
            'job_type' => 'required|in:Entry,SRS-1,SRS-2',
            'department' => 'required',
            'closing' => 'required|date_format:Y-m-d\TH:i',
        ]);

        // Handle the file upload
        if ($request->hasFile('JobDescriptionPDF')) {
            $file = $request->file('JobDescriptionPDF');
            $path = $file->store('job_descriptions', 'public');
            $validatedData['job_description'] = $path;
        }

        // Retrieve the last hiring record
        $lastHiring = Hiring::orderBy('id', 'desc')->first();
        $newReferenceNumber = $lastHiring ? intval($lastHiring->reference) + 1 : 1;

        // Generate reference, email, and password
        $reference = str_pad($newReferenceNumber, 5, '0', STR_PAD_LEFT);
        $email = $reference . '@example.com';
        $password = $reference . $validatedData['department'];
        $hashedPassword = Hash::make($password);

        // Create and save the new Hiring instance
        $hiring = new Hiring($validatedData);
        $hiring->reference = $reference;

        // Save the new hiring record
        if ($hiring->save()) {
            if($validatedData['contract_type'] == 'Permanent') {
                if($validatedData['job_type'] == 'SRS-1' || $validatedData['job_type'] == 'Entry') {
                    $user = new User();
                    $user->name = $reference;
                    $user->email = $email;
                    $user->address = $password; // This should be properly set according to your User model's schema
                    $user->password = $hashedPassword;
                    $user->usertype = 'guest';
                    
                    if ($user->save()) {
                        return redirect()->back()->with('success', 'Hiring and user created successfully');
                    } else {
                        return redirect()->back()->with('error', 'Hiring created but user creation failed');
                    }
                } else {
                    return redirect()->back()->with('success', 'Hiring and user created successfully');
                }
            } elseif($validatedData['contract_type'] == 'COS') {
                if($validatedData['job_type'] == 'SRS-1' || $validatedData['job_type'] == 'Entry') {
                    $user = new User();
                    $user->name = $reference;
                    $user->email = $email;
                    $user->address = $password; // This should be properly set according to your User model's schema
                    $user->password = $hashedPassword;
                    $user->usertype = 'guest';
                    
                    if ($user->save()) {
                        return redirect()->back()->with('success', 'Hiring and user created successfully');
                    } else {
                        return redirect()->back()->with('error', 'Hiring created but user creation failed');
                    }
                } else {
                    return redirect()->back()->with('error', 'Something went wrong');
                }
            }
            
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }


    public function show(Hiring $hiring)
    {
        $getID = $hiring->id;
        $reference = $hiring->reference;
        $getGuest = User::select('email', 'address')
        ->where('name', $reference)->get();

        // Ensure you have the correct names for your User model and the columns
        $applications = Applicant::leftJoin('users', 'applicants.user_id', '=', 'users.id')
            ->where('hiring_id', $getID)
            ->select('users.name', 'applicants.*') // Adjust the selected columns as needed
            ->get();
    
        return view('Admin.hiringview', compact('hiring', 'applications', 'getGuest'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hiring $hiring)
    {
        return view("Admin.hiringview", with(compact("hiring")));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hiring $hiring)
    {
        // Validate the incoming request data
        $request->validate([
            'job_position' => 'required|string|max:255',
            'JobDescriptionPDF' => 'nullable|file|mimes:pdf|max:2048',
            'contract_type' => 'required|string|in:COS,Permanent',
            'job_type' => 'required|string|in:Entry,SRS-1,SRS-2',
            'job_status' => 'required|string',
            'salary_grade' => 'required|integer|min:1|max:24',
            'department' => 'required|string',
            'closing' => 'required|date_format:Y-m-d\TH:i',
            'competency_exam_date' => 'nullable|date',
            'pre_employment_exam_date' => 'nullable|date',
            'initial_interview_date' => 'nullable|date',
            'final_interview_date' => 'nullable|date',
            'bei_date' => 'nullable|date',
            'pyscho_test_date' => 'nullable|date',
        ]);

        // Update the hiring with validated data
        $hiring->job_position = $request->job_position;
        $hiring->contract_type = $request->contract_type;
        $hiring->job_type = $request->job_type;
        $hiring->job_status = $request->job_status;
        $hiring->salary_grade = $request->salary_grade;
        $hiring->department = $request->department;
        $hiring->closing = $request->closing;

        // Handle file upload for Job Description PDF
        if ($request->hasFile('JobDescriptionPDF')) {
            // Delete the old file if it exists
            if ($hiring->job_description && Storage::exists($hiring->job_description)) {
                Storage::delete($hiring->job_description);
            }

            // Store the new file
            $path = $request->file('JobDescriptionPDF')->store('public/job_descriptions');
            // Update the hiring's job_description field with the new file path
            $hiring->job_description = $path;
        }

        // Update the additional date fields based on the contract and job type
        $dateFields = [
            'competency_exam_date' => 'Competency Exam Date',
            'pre_employment_exam_date' => 'Pre-employment Exam Date',
            'initial_interview_date' => 'Initial Interview Date',
            'final_interview_date' => 'Final Interview Date',
            'bei_date' => 'BEI Date',
            'psycho_test_date' => 'Psycho Test Date'
        ];
        
        foreach ($dateFields as $field => $fieldName) {
            if ($request->has($field) && $request->$field != $hiring->$field) {
                $hiring->$field = $request->$field;
    
                // Create notifications for each applicant
                foreach ($hiring->applicants()->where('application_status', 'Passed')->get() as $applicant) {
                    $message = "The {$fieldName} for {$hiring->job_position} has been updated to " . Carbon::parse($request->$field)->format('Y-m-d'). ". Please save the date.";
                    Notification::create([
                        'sender_id' => NULL,
                        'receiver_id' => $applicant->id,
                        'message' => $message,
                        'status' => 'unread',
                        'type' => 'update',
                    ]);
                }
            }
        }

        // Save the updated hiring
        $hiring->save();

        return redirect()->back()->with('success', 'Hiring updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hiring $hiring)
    {
        // Delete all applicants associated with the hiring
        $hiring->applicants()->delete();
    
        // Delete the hiring
        $hiring->delete();
    
        return redirect()->route('hirings.index')->with('success', 'Hiring and associated applicants deleted successfully');
    }
    
}
