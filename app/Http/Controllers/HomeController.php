<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Requirement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Hiring;
use App\Models\Applicant;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        $filter = '';
        $querySearch = $request->querySearch;

        // Base query for hirings
        $hiringQuery = Hiring::where('job_status', 'Open');

        // If there is a search query, add conditions to the query
        if (!empty($querySearch)) {
            $hiringQuery->where(function($query) use ($querySearch) {
                $query->where('job_position', 'LIKE', '%' . $querySearch . '%')
                    ->orWhere('department', 'LIKE', '%' . $querySearch . '%');
            });
        }

        // If there is a filter, add it to the query
        if (!empty($request->filter)) {
            $filter = $request->filter;
            $hiringQuery->where('contract_type', $filter);
        }

        // Get paginated results
        $hirings = $hiringQuery->paginate(5);

        // If no results, fallback to the default query
        if ($hirings->count() == 0) {
            $hiringQuery = Hiring::where('job_status', 'Open');

            if (!empty($request->filter)) {
                $hiringQuery->where('contract_type', $filter);
            }

            $hirings = $hiringQuery->paginate(5);
        }

        // If the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            $requirements = Requirement::where('user_id', $user->id)->get();

            // Check if the user has an ongoing application for each hiring job
            foreach ($hirings as $hiring) {
                $applicant = Applicant::where('user_id', $user->id)
                                    ->where('hiring_id', $hiring->id)
                                    ->first();

                // If an applicant is found, set the hasApplication flag and retrieve the application_status
                if ($applicant) {
                    $hiring->hasApplication = true;
                    $hiring->application_status = $applicant->application_status;
                } else {
                    $hiring->hasApplication = false;
                    $hiring->application_status = null;
                }
            }

            // Redirect based on user type
            switch ($user->usertype) {
                case 'guest':
                    $ID = $user->name;
                    $getID = Hiring::where('reference', $ID)->pluck('id')->first();
                    return redirect()->route('applications.view', ['hiringID' => $getID]);
                case 'selection board':
                    return redirect()->route('applicants.index')->with("success", "You are logged in.");
                case 'admin':
                case 'hr':
                    return redirect()->route('admin')->with("success", "You are logged in");
                default:
                    $data = [
                        'hirings' => $hirings,
                        'requirements' => $requirements,
                        'filter' => $filter,
                        'search' => $querySearch,
                    ];
                    return view('Home', $data);
            }
        } else {
            // If the user is not authenticated
            $data = [
                'hirings' => $hirings,
                'filter' => $filter,
                'search' => $querySearch,
            ];

            return view('Home', $data);
        }
    }

    
    //Login form
    public function loginPost(Request $request) {
        $request->validate([
            "email" => "required|email",
            "password" => "required|string|min:8",
        ]);
    
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');
    
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            if ($user->usertype === 'admin' || $user->usertype === 'hr' || $user->usertype === 'selection board') {
                return redirect()->route('admin')->with("success", "You are logged in");
            }
            elseif ($user->usertype === 'guest'){
                $ID = $user->name;
                $getID = Hiring::where('reference', $ID)->pluck('id')->first();
                return redirect()->route('applications.view', ['hiringID' => $getID]);
            }
            return redirect()->intended(route("home"))->with("success", "You are logged in");
        }
    
        return redirect()->back()->with("error", "Invalid credentials");
    }
                                    

    //Register form
    public function registerPost(Request $request) {
        // Validate the request data
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users|max:255",
            "address" => "required|string|max:255",
            "number" => "required|string|max:15",
            "password" => "required|string|min:8|confirmed",
        ]);
    
        // Use transaction to ensure atomicity
        DB::beginTransaction();
    
        try {
            // Create a new user instance with the validated data
            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->address = $validated['address'];
            $user->number = $validated['number'];
            $user->password = Hash::make($validated['password']);
            
            // Save the user
            if ($user->save()) {
                // Create a new requirement instance and associate it with the user
                $requirement = new Requirement();
                $requirement->user_id = $user->id;
                $requirement->save();
    
                // Commit the transaction
                DB::commit();
    
                // Log the user in
                Auth::login($user);
    
                // Redirect to the home page with a success message
                return redirect()->route("home")->with("success", "You are registered");
            } else {
                // Rollback the transaction if user save failed
                DB::rollBack();
                return redirect()->back()->with("error", "Something went wrong");
            }
        } catch (\Exception $e) {
            // Rollback the transaction on exception
            DB::rollBack();
            return redirect()->back()->with("error", "Something went wrong: " . $e->getMessage());
        }
    }
    
    /**
     * Display a listing of the resource.
     */
    
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('home')->with('success', 'You have been logged out');
    }
    
}