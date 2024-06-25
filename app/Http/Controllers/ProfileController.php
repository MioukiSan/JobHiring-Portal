<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Hiring;
use App\Models\Applicant;
use GuzzleHttp\Psr7\Query;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user()->id;
        $requirements = Requirement::where('user_id', $user)->get();
        $applicationsOngoing = Applicant::select('hirings.job_position', 'applicants.application_status', 'hirings.job_status', 'applicants.created_at')
            ->leftJoin('hirings', 'hirings.id', '=', 'applicants.hiring_id')
            ->where('applicants.user_id', $user)
            ->where('hirings.job_status', '!=', 'Archived')
            ->where('applicants.application_status', '=', 'Passed')
            ->orderBy('applicants.created_at', 'asc')
            ->get();

        $applicationsHistory = Applicant::select('hirings.job_position', 'applicants.application_status', 'hirings.job_status', 'applicants.created_at')
            ->leftJoin('hirings', 'hirings.id', '=', 'applicants.hiring_id')
            ->where('applicants.user_id', $user)
            ->where('hirings.job_status', '=', 'Archived')
            ->orderBy('applicants.created_at', 'asc')
            ->get();

        $countApplication = Applicant::where('user_id', $user)->count();
        
        $data = [
            'requirements' => $requirements,
            'applicationOngoing' => $applicationsOngoing,
            'applicationHistory' => $applicationsHistory,
            'countApplication' => $countApplication,
        ];
        return view('User.profile', $data);
    }

    public function verifyAccount(Request $request){
        $code = $request->verification_code;

        $user = User::find(Auth::user()->id);

        if($code === $user->account_status){
            $user->account_status = 'verified';
            $user->save();
            return back()->with('success', 'Your account is verified.');
        }else{
            return back()->with('error', 'Your request to verify your application cant complete due to mismatch of verification code.');
        }
    }

    public function sendVerification(){
        $user = User::find(Auth::user()->id);

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codeLength = 5;
        $code = '';

        for ($i = 0; $i < $codeLength; $i++) {
            $randomIndex = mt_rand(0, strlen($characters) - 1);
            $code .= $characters[$randomIndex];
        }
        
        $ch = curl_init();
        $parameters = array(
            'apikey' => 'a98eb9abe2636f1d3c09370d98663a40', //Your API KEY
            'number' => '0' . $user->number,
            'message' => 'Good day, this is from DOST V HR. To confirm your account here is your verification ' . $code . '. Thank you and have a nice day!',
            'sendername' => 'SEMAPHORE'
        );
        curl_setopt( $ch, CURLOPT_URL,'https://semaphore.co/api/v4/messages' );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        
        //Send the parameters set above with the request
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $parameters ) );
        
        // Receive response from server
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $output = curl_exec( $ch );
        curl_close ($ch);

        $user->account_status = $code;

        $user->save();
        return back()->with('info', 'Verification code is already sent to your number. Please check your inbox. Thank you!!!');
    }
    public function changePass(Request $request) {
        // Validate the input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
        ]);
    
        // Retrieve the authenticated user
        // $user = Auth::user();
        $user = User::find(Auth::user()->id);
    
        // Check if the provided current password matches the stored password
        if (Hash::check($request->current_password, $user->password)) {
            // Update the user's password
            $user->password = Hash::make($request->new_password);
            if($user->save()){
                return back()->with('success', 'Password successfully changed.');
            } else {
                return back()->with('error', 'Unsuccessful changing password.');
            }
        } else {
            // Return error message
            return back()->with('error', 'Current password does not match.');
        }
    }
    
    public function destroy($id){
        $user = User::find($id);
        $requirementID = Requirement::select('id')->where('user_id', $id)->first();

        $requirementID->delete();
        $user->delete();
        // Redirect or return response
        return redirect()->route('home')->with('success', 'Account successfully deleted.');
        
    }
    
    public function CalendarSchedule(Request $request)
    {
        $id = auth()->user()->id;
        $schedules = Applicant::select(
            'h.job_position',
            'h.competency_exam_date', 
            'h.pre_employment_exam_date', 
            'h.initial_interview_date', 
            'h.final_interview_date', 
            'h.psycho_test_date', 
            'h.closing'
        )
        ->leftJoin('hirings as h', 'h.id', '=', 'applicants.hiring_id')
        ->where('applicants.user_id', $id)
        ->where('applicants.application_status', '!=', 'Passed')
        ->where('job_status', '!=', 'archived')
        ->get();

        $events = $schedules->flatMap(function ($date) {
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

    
    public function update(Request $request)
    {
        $user = User::find(Auth::id());
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'number' => 'required|max:11',
            'address' => 'required|max:255',
        ]);

        $user->update($validatedData);
        
        return redirect()->back()->with('success', 'Profile updated successfully');
    }
    
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

}
