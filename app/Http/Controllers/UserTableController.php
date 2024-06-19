<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class UserTableController extends Controller

{

    public function index(Request $request)
    {
        $data = DB::table("users")
            ->leftJoin("requirements", "users.id", "=", "requirements.user_id")
            ->select("users.*", "requirements.csc_form", "requirements.tor_diploma", "requirements.training_cert", "requirements.eligibility")
            ->where('usertype', 'user')
            ->paginate(5);

        foreach ($data as $user) {
            $progress = 0;
            if (!is_null($user->csc_form)) $progress += 25;
            if (!is_null($user->tor_diploma)) $progress += 25;
            if (!is_null($user->training_cert)) $progress += 25;
            if (!is_null($user->eligibility)) $progress += 25;
            // Add more fields as necessary
            $user->progress = $progress;
        }

        $otherUsers = User::select('id', 'name', 'email', 'usertype')
            ->whereIn('usertype', ['hr', 'selection board', 'guest'])
            ->get();
        
        $data = [
            "data" => $data,
            "otherUsers" => $otherUsers,
        ];

        return view("Admin.user", $data);
    }

    // public function search(Request $request)
    // {
        // if($request->ajax()) {
        //     $data = DB::table("users")
        //             ->join("requirements", "users.id", "=", "requirements.user_id")
        //             ->select("users.*", "requirements.*")
        //             ->where("name","LIKE","%". $request->search ."%")
        //             ->get();
        // foreach ($data as $user) {
        //     $progress = 0;
        //     if (!is_null($user->csc_form)) $progress += 25;
        //     if (!is_null($user->tor_diploma)) $progress += 25;
        //     if (!is_null($user->training_cert)) $progress += 25;
        //     if (!is_null($user->eligibility)) $progress += 25;
        //     // Add more fields as necessary
        //     $user->progress = $progress;
        //     }
        //     return view("Admin.user", compact("data"));
        // }
    // }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            'password' => 'required',
            'usertype' => 'required|string',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->usertype = $request->usertype;
        if($user->save()){
            return redirect()->back()->with("success", "User registered successfully");
        }else{
            return redirect()->back()->with("error", "Something went wrong");
        }
    }

}
