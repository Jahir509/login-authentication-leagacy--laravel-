<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use  Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo ='/dashboard';
    protected $redirectAfterLogout = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
     //Overrided method
     public function logout(Request $request)
     {
         $this->guard()->logout();
 
         $request->session()->invalidate();
 
         $request->session()->regenerateToken();
 
         if ($response = $this->loggedOut($request)) {
             return $response;
         }
 
         return $request->wantsJson()
             ? new Response('', 204)
             : redirect($this->redirectAfterLogout);
     }
 
     public function username()
     {
         // jahir : jahir@gmail.com
 
         $fieldValue = request()->input('username_or_email');
 
         $fieldName = filter_var($fieldValue,FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
 
         request()->merge([$fieldName => $fieldValue]);
 
         return $fieldName;
     }
}
