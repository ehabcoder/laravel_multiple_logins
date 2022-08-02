<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CustomAuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $users = DB::table('users')->where('email', $request->only('email'))->get();
        
        $credentials = $request->only('email', 'password');

        if(count($users) > 1) {
            // put the email and password into session so we will need them 
            // in the checkUserType()
            $request->session()->put('email', $request->only('email'));
            $request->session()->put('password', $request->only('password'));
           return redirect("checkUserType");
        } else {
            // now we are certainly know that the user has only one email
            // with one type so let's save that type in a session
            $request->session()->put('userType', $request->only('type'));
            if (Auth::attempt($credentials)) {
                return redirect()->intended('dashboard')
                    ->withSuccess('Signed in');
            }
            return redirect("login")->withSuccess('Login details are not valid');
        }
    }

    public function checkUserType() {
        // get the users with the same email to render
        // them in the check to let user choose whatever 
        // type he wants to register with
        $users = DB::table('users')->where('email', session('email'))->get();
        $credentials = array("email"=> session('email')['email'], "password"=> session('password')['password']);
        // Authenticate the user before rendering the next view
        Auth::attempt($credentials);
        // render the view that he will choose from it what type He
        // is registering with
        return view('auth.userType')->with('users', $users);
    }

    public function regesterOrchastra()
    {
        return view('auth.registerOrchastra');
    }

    public function registerMusician()
    {
        return view('auth.registerMusician');
    }

    // This is a post function for handling the registration of the user.
    public function customRegistration(Request $request)
    {
        // validating the inputs
        $request->validate([
            'email' => 'required|email',
            'fname' => 'required',
            'surname' => 'required',
            'type' => 'required',
            'gender' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        // get all the data from the request
        $data = $request->all();

        // validating and creating the user (orchestra or musician) into the database
        // validate the type to be certain that our database will have
        // only 2 types for now orchestra, musicians 
        // we will not combine the member type with them for the purpose
        // of a security issue
        if ($data['type'] == 'orchestra' || $data['type'] == 'musician') {
            // if skip this part we get all the users with that email from
            // the database
            $users = DB::table('users')->where('email', $data['email'])->get();
            // loop over users to check if we already have that email
            // with that type
            for ($i = 0; $i < count($users); $i++) {
                // if we find the same email with the same type we redirect
                // the user back with error msg
                if ($users[$i]->type == $data['type']) {
                    return redirect()->back()->with('msg', 'This email with that type is already exists.');
                }
                // if the password not the same as the passwords of the 
                // other accounts we again redirect him back with error msg
                if (!Hash::check($data['password'], $users[$i]->password)) {
                    return redirect()->back()->with('msg', 'The Password must be equal to the password you used in other accounts.');
                }
            }
            // if it skipped the for loop that means we are ready to create the new user
            // Note we will check to see if we have orchastraName or not
            // if we have orchastraName then we will create User which
            // will have type of Orchestra.
            if ($data['type'] == 'orchestra') {
                User::create([
                    'type' => $data['type'],
                    'email' => $data['email'],
                    'gender' => $data['gender'],
                    'first_name' => $data['fname'],
                    'surname' => $data['surname'],
                    'orchastraName' => $data['orchastraName'],
                    'password' => Hash::make($data['password']),
                ]);
            } else {
                User::create([
                    'type' => $data['type'],
                    'email' => $data['email'],
                    'gender' => $data['gender'],
                    'first_name' => $data['fname'],
                    'surname' => $data['surname'],
                    'password' => Hash::make($data['password']),
                ]);
            }
        }
        // redirect to the dashboard
        return redirect("dashboard")->withSuccess('You have signed-in');
    }

    public function validate_then_create(array $data)
    {
        
    }

    public function dashboard(Request $request)
    {
        if (Auth::check()) {
            if($request->input('type')) {
                $request->session()->put('userType', $request->input('type'));
                return view('dashboard');
            }
            return view('dashboard');
        }

        return redirect("login")->withSuccess('You are not allowed to access');
    }

    public function signOut()
    {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}
