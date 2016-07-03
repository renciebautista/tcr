<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Session;
use Carbon\Carbon;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Auth\AuthManager;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function getLogin(){
        return view('auth.form1');
    }

    public function postLogin(Request $request){
        $usernameinput =  $request->access;
        $password = $request->password;
        $field = filter_var($usernameinput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt(array($field => $usernameinput, 'password' => $password,'active'=>1), false)) {
   
            // if(Auth::user()->isActive()){
                // if(Auth::user()->hasRole('field')){
                //     Auth::logout();
                //     Session::flash('flash_message', 'Account not allowed to access the site.');
                //     Session::flash('flash_class', 'alert alert-danger');
                //     return \Redirect::back();
                // }
            // }else{
            //     Auth::logout();
            //     Session::flash('flash_message', 'User account is inactive, please contact the administrator');
            //     Session::flash('flash_class', 'alert alert-danger');
            //     return \Redirect::back();
            // }
            
            // return Redirect::action('DashboardController@index');
            return \Redirect::intended('/');
        }

        Auth::logout();
        Session::flash('flash_message', 'Invalid credentials, please try again.');
        Session::flash('flash_class', 'alert alert-danger');
        return \Redirect::back();
    }


    public function getLogout(){
        Auth::logout();
        Session::flush();
        return redirect('/login');

    }
}
