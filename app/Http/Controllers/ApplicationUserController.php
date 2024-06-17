<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Hiring;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
class ApplicationUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function uploadRequirement(Request $request)
    {
        $userId = auth()->id();
        $requirements = Requirement::where("user_id", $userId)->get();
        return view("User.requirements-upload", compact("requirements"));
    }

    public function storeRequirement(Request $request)
    {
        $validatedData = $request->validate([
            'csc_form' => 'nullable|file|mimes:pdf|max:2048',
            'tor_diploma' => 'nullable|file|mimes:pdf|max:2048',
            'training_cert' => 'nullable|file|mimes:pdf|max:2048',
            'eligibility' => 'nullable|file|mimes:pdf|max:2048',
        ]);
        
        $userId = Auth::id();
        $requirement = Requirement::updateOrCreate(['user_id' => $userId], []);

        foreach (['csc_form', 'tor_diploma', 'training_cert', 'eligibility'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $path = $file->store('requirements', 'public');
                $requirement->$field = $path;

            }
        }
        $requirement->save();

        return redirect()->route('home')->with('success', 'Requirements uploaded successfully');
    }

    public function updateRequirement(Request $request, $id)
    {
        
    }
    public function applyProcess(Request $request)
    {
        $id = $request->hiringID;
        
        $userId = Auth::id();

        if (Applicant::where('hiring_id', $id)->where('user_id', $userId)->exists()) {
            return redirect()->back()->with('error', 'You have already applied for this job');
        } else {
            $app = new Applicant();
            $app->hiring_id = $id;
            $app->user_id = $userId;
            $app->save();
            
            $notification = new Notification();
            $notification->receiver_id = 4506385;
            $notification->sender_id = $userId;
            $notification->hiring_id = $id;
            $notification->Message = 'You have received an application for a job from ' . Auth::user()->name ;
            $notification->type = 'application';
            $notification->Status = 'unread';
            $notification->save();
            
            return redirect()->route('home')->with('success', 'Application submitted successfully');
        }
    }

    public function apply(Request $request)
    {
        $id = $request->hiringID;
        $hiringData = Hiring::where('id', $id)->get();
        $userID = Auth::id();
        $requirements = Requirement::select('csc_form', 'tor_diploma', 'training_cert', 'eligibility')
        ->where('user_id', $userID)->get();
        return view('User.application-process', compact('hiringData', 'requirements'));;
    }

    public function cancelApplication(Request $request)
    {
        $hiringID = $request->input('hiringID');
        $userID = Auth::id();
    
        // Find the application and update its status
        $application = Applicant::where('hiring_id', $hiringID)->where('user_id', $userID)->first();
        
        if ($application) {
            $application->application_status = 'Cancelled';
            $application->save();

            $notification = new Notification();
            $notification->receiver_id = 4506385;
            $notification->sender_id = Auth::id();
            $notification->Message = Auth::user()->name . 'Has cancelled an application for a job';
            $notification->type = 'application';
            $notification->Status = 'unread';
            $notification->save();

            return redirect()->back()->with('success', 'Application cancelled successfully.');
        } else {
            return redirect()->back()->with('error', 'Application not found.');
        }
    }
    
}
