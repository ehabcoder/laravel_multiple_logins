<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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
        // get all users with that email from the database
        $users = DB::table('users')->where('email', $request->only('email'))->get();
        //make the credentials for login
        $credentials = $request->only('email', 'password');
        if (count($users) > 1) { // if we have more than one user
            // put the email and password into session because we will
            // need them in the checkUserType()
            $request->session()->put('email', $request->only('email'));
            $request->session()->put('password', $request->only('password'));
            // then we redirect the user the the chechUserType
            // to check the type of the user Orchastra or Musician
            return redirect("checkUserType");
        } else {
            // now we are certainly know that the user has only one email
            // with one type so let's save that type in a session
            $request->session()->put('userType', $request->only('type'));
            // then we sign the user in using the credentials
            if (Auth::attempt($credentials)) {
                return redirect()->intended('dashboard')->with('success', 'signed in!');
            }
            // if there is an error in the creds then
            // we redirect him to the login page again
            return redirect("login")->with('error', 'Login details are not valid');
        }
    }

    public function checkUserType()
    {
        // get the users with the same email to render
        // them in the check to let user choose whatever
        // type he wants to register with...
        $users = DB::table('users')->where('email', session('email'))->get();

        // render the view that he will choose from it what type He
        // is registering with
        return view('auth.userType')->with('users', $users);
        // Note the user will be asked about the type 'Orchastra, Musician or Member'
        // Then he will be redirected to /login/{type} which will render
        // the dashboard controller.
    }

    // To get the regester orchastra page
    public function regesterOrchastra()
    {
        return view('auth.registerOrchastra');
    }

    // To get the regester musician page
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

        /*
        validating and creating the user (orchestra or musician) into the database
        validating the type to be certain that our database will have
        only 2 types (for now) orchestra, musicians
        we will not combine the Member type now with them for the purpose
        of a security issue so we can not let any one to create a member
        only the registered Orchestras or Musician can do.
         */
        $user=0;
        if ($data['type'] == 'orchestra' || $data['type'] == 'musician') {
            // get all the users with that email from the database
            $users = DB::table('users')->where('email', $data['email'])->get();
            /* loop over users to check if we already have that email
            with that type */
            for ($i = 0; $i < count($users); $i++) {
                /* if we find the same email with the same type we redirect
                the user back with error msg */
                if ($users[$i]->type == $data['type']) {
                    return redirect()->back()->with('error', 'This email with that type is already exists.');
                }
                /* if the password not the same as the passwords of the
                other accounts we again redirect him back with error msg */
                if (!Hash::check($data['password'], $users[$i]->password)) {
                    return redirect()->back()->with('error', 'The Password must be equal to the password you used in other accounts.');
                }
            }

            /*
            if it skipped the for loop that means we are ready to create
            the new user
             */
            $user = User::create([
                'type' => $data['type'],
                'email' => $data['email'],
                'gender' => $data['gender'],
                'first_name' => $data['fname'],
                'surname' => $data['surname'],
                // note orchastra name will be null
                // if the type is musician
                'orchastraName' => $data['orchastraName'],
                'password' => Hash::make($data['password']),
            ]);
            event(new Registered($user));
        }
        $query = $user->save();
        if($query) {
            // Authenticate the user before rendering the dashboard
            $credentials = $request->only('email', 'password');
            Auth::attempt($credentials);
            // redirect to the dashboard
            return redirect("dashboard")->with('info','Your account has been created successfully');      
        }
        else
        {
            return redirect()->back()->with('error','Something went wrong. Please try again');
        }
    }

    // // Notice
    // public function emailVerificationNotice()
    // {
    //     return view('auth.verifyEmail');
    // }

    public function emailVerificationVerify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect('dashboard')->with('success', 'Email verified successfully!');
    }

    public function resendEmailVerificationLink(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('info','Verification link sent!');
    }

    // The datshboard where all stuff rendered
    public function dashboard(Request $request)
    {
        // if we have email and password in the session
        // then we authenticate the user
        if (Session::has('email') && Session::has('password')) {
            $credentials = array("email" => session('email')['email'], "password" => session('password')['password']);
            // Authenticate the user before rendering the next view
            Auth::attempt($credentials);
        }
        if (Auth::check()) {
            if ($request->input('type')) {
                $request->session()->put('userType', $request->input('type'));
                return view('dashboard');
            }
            return view('dashboard');
        }

        return redirect("login")->with('success', 'You are not allowed to access');
    }

    // Signing the user out
    public function signOut()
    {
        Session::flush();
        Auth::logout();
        return Redirect('login')->with('success', 'Signed out!');
    }
}
